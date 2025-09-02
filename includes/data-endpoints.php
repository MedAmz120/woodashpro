<?php
if (!defined('ABSPATH')) exit;

// Debug function
function woodash_debug_log($message) {
    $log_file = WP_CONTENT_DIR . '/woodash-debug.log';
    $timestamp = date('[Y-m-d H:i:s] ');
    file_put_contents($log_file, $timestamp . $message . "\n", FILE_APPEND);
}

/**
 * WooDash Pro API Endpoints
 * Comprehensive backend API for orders, customers, products, and analytics
 */

// Orders Management Endpoints
add_action('wp_ajax_woodash_get_orders', 'woodash_get_orders');
add_action('wp_ajax_woodash_create_order', 'woodash_create_order');
add_action('wp_ajax_woodash_update_order', 'woodash_update_order');
add_action('wp_ajax_woodash_delete_order', 'woodash_delete_order');
add_action('wp_ajax_woodash_get_order_details', 'woodash_get_order_details');

// Customer Management Endpoints
add_action('wp_ajax_woodash_get_customers', 'woodash_get_customers');
add_action('wp_ajax_woodash_create_customer', 'woodash_create_customer');

// Product Management Endpoints
add_action('wp_ajax_woodash_get_products', 'woodash_get_products');
add_action('wp_ajax_woodash_search_products', 'woodash_search_products');
add_action('wp_ajax_woodash_create_product', 'woodash_create_product');
add_action('wp_ajax_woodash_update_product', 'woodash_update_product');
add_action('wp_ajax_woodash_delete_product', 'woodash_delete_product');
add_action('wp_ajax_woodash_get_product_stats', 'woodash_get_product_stats');

// Dashboard Analytics Endpoints
add_action('wp_ajax_woodash_get_dashboard_stats', 'woodash_get_dashboard_stats');
add_action('wp_ajax_woodash_get_analytics_data', 'woodash_get_analytics_data');

// Extended Customer Management Endpoints
add_action('wp_ajax_woodash_get_customer_details', 'woodash_get_customer_details');
add_action('wp_ajax_woodash_update_customer', 'woodash_update_customer');
add_action('wp_ajax_woodash_delete_customer', 'woodash_delete_customer');
add_action('wp_ajax_woodash_get_customer_orders', 'woodash_get_customer_orders');
add_action('wp_ajax_woodash_get_customer_stats', 'woodash_get_customer_stats');

// Reports & Analytics Endpoints
add_action('wp_ajax_woodash_get_sales_report', 'woodash_get_sales_report');
add_action('wp_ajax_woodash_get_product_report', 'woodash_get_product_report');
add_action('wp_ajax_woodash_get_customer_report', 'woodash_get_customer_report');
add_action('wp_ajax_woodash_get_revenue_analytics', 'woodash_get_revenue_analytics');

// Enhanced Customer Endpoints
add_action('wp_ajax_woodash_get_customer_analytics', 'woodash_get_customer_analytics');
add_action('wp_ajax_woodash_get_customers_enhanced', 'woodash_get_customers_enhanced');
add_action('wp_ajax_woodash_get_customer_growth_data', 'woodash_get_customer_growth_data');

/**
 * Get orders with pagination and filtering
 */
function woodash_get_orders() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 20;
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        
        $args = array(
            'type' => 'shop_order',
            'limit' => $per_page,
            'offset' => ($page - 1) * $per_page,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        if ($status) {
            $args['status'] = array('wc-' . $status);
        }
        
        if ($search) {
            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key' => '_billing_first_name',
                    'value' => $search,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => '_billing_last_name',
                    'value' => $search,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => '_billing_email',
                    'value' => $search,
                    'compare' => 'LIKE'
                )
            );
        }
        
        $orders = wc_get_orders($args);
        $total_orders = wc_get_orders(array_merge($args, array('limit' => -1, 'offset' => 0, 'return' => 'ids')));
        
        $formatted_orders = array();
        foreach ($orders as $order) {
            $formatted_orders[] = woodash_format_order_data($order);
        }
        
        wp_send_json_success(array(
            'orders' => $formatted_orders,
            'total' => count($total_orders),
            'page' => $page,
            'per_page' => $per_page,
            'total_pages' => ceil(count($total_orders) / $per_page)
        ));
        
    } catch (Exception $e) {
        wp_send_json_error('Error fetching orders: ' . $e->getMessage());
    }
}

/**
 * Create new order
 */
function woodash_create_order() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $customer_type = isset($_POST['customer_type']) ? sanitize_text_field($_POST['customer_type']) : 'existing';
        $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
        $billing_data = isset($_POST['billing']) ? $_POST['billing'] : array();
        $items = isset($_POST['items']) ? $_POST['items'] : array();
        $order_status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : 'pending';
        $payment_method = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : '';
        $payment_status = isset($_POST['payment_status']) ? sanitize_text_field($_POST['payment_status']) : 'pending';
        $order_notes = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';
        $tax_rate = isset($_POST['tax_rate']) ? floatval($_POST['tax_rate']) : 0;
        $shipping_cost = isset($_POST['shipping_cost']) ? floatval($_POST['shipping_cost']) : 0;
        
        // Create new customer if needed
        if ($customer_type === 'new') {
            $first_name = sanitize_text_field($_POST['first_name']);
            $last_name = sanitize_text_field($_POST['last_name']);
            $email = sanitize_email($_POST['customer_email']);
            $phone = sanitize_text_field($_POST['customer_phone']);
            
            if (!$email) {
                wp_send_json_error('Customer email is required');
            }
            
            // Check if customer already exists
            $existing_customer = get_user_by('email', $email);
            if ($existing_customer) {
                $customer_id = $existing_customer->ID;
            } else {
                // Create new customer
                $customer_id = wc_create_new_customer($email, '', '', array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                ));
                
                if (is_wp_error($customer_id)) {
                    wp_send_json_error('Error creating customer: ' . $customer_id->get_error_message());
                }
                
                // Add phone number
                if ($phone) {
                    update_user_meta($customer_id, 'billing_phone', $phone);
                }
            }
        }
        
        // Create new order
        $order = wc_create_order();
        
        // Set customer
        if ($customer_id > 0) {
            $order->set_customer_id($customer_id);
        }
        
        // Set billing address
        if (!empty($billing_data)) {
            $billing_address = array(
                'first_name' => sanitize_text_field($billing_data['first_name']),
                'last_name' => sanitize_text_field($billing_data['last_name']),
                'address_1' => sanitize_text_field($billing_data['address_1']),
                'address_2' => sanitize_text_field($billing_data['address_2']),
                'city' => sanitize_text_field($billing_data['city']),
                'state' => sanitize_text_field($billing_data['state']),
                'postcode' => sanitize_text_field($billing_data['postcode']),
                'country' => sanitize_text_field($billing_data['country']),
                'email' => sanitize_email($billing_data['email']),
                'phone' => sanitize_text_field($billing_data['phone']),
            );
            $order->set_address($billing_address, 'billing');
        }
        
        // Add products
        foreach ($items as $item) {
            $product_id = intval($item['product_id']);
            $quantity = intval($item['quantity']);
            $custom_price = isset($item['price']) ? floatval($item['price']) : null;
            
            $product = wc_get_product($product_id);
            
            if ($product) {
                $item_id = $order->add_product($product, $quantity);
                
                // Set custom price if provided
                if ($custom_price && $item_id) {
                    $order_item = $order->get_item($item_id);
                    $order_item->set_subtotal($custom_price * $quantity);
                    $order_item->set_total($custom_price * $quantity);
                    $order_item->save();
                }
            }
        }
        
        // Add shipping
        if ($shipping_cost > 0) {
            $shipping_item = new WC_Order_Item_Shipping();
            $shipping_item->set_method_title('Custom Shipping');
            $shipping_item->set_method_id('custom_shipping');
            $shipping_item->set_total($shipping_cost);
            $order->add_item($shipping_item);
        }
        
        // Set order status
        $order->set_status($order_status);
        
        // Set payment method
        if ($payment_method) {
            $order->set_payment_method($payment_method);
            $order->set_payment_method_title(ucfirst(str_replace('-', ' ', $payment_method)));
        }
        
        // Add order notes
        if ($order_notes) {
            $order->add_order_note($order_notes);
        }
        
        // Calculate totals
        $order->calculate_totals();
        
        // Handle payment status
        if ($payment_status === 'paid') {
            $order->payment_complete();
        }
        
        // Save order
        $order->save();
        
        wp_send_json_success(array(
            'message' => 'Order created successfully',
            'order_id' => $order->get_id(),
            'order' => woodash_format_order_data($order)
        ));
        
    } catch (Exception $e) {
        wp_send_json_error('Error creating order: ' . $e->getMessage());
    }
}

/**
 * Update existing order
 */
function woodash_update_order() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $order = wc_get_order($order_id);
        
        if (!$order) {
            wp_send_json_error('Order not found');
        }
        
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
        $payment_status = isset($_POST['payment_status']) ? sanitize_text_field($_POST['payment_status']) : '';
        $notes = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';
        
        if ($status) {
            $order->set_status($status);
        }
        
        if ($payment_status === 'paid') {
            $order->payment_complete();
        }
        
        if ($notes) {
            $order->add_order_note($notes);
        }
        
        $order->save();
        
        wp_send_json_success(array(
            'message' => 'Order updated successfully',
            'order' => woodash_format_order_data($order)
        ));
        
    } catch (Exception $e) {
        wp_send_json_error('Error updating order: ' . $e->getMessage());
    }
}

/**
 * Get order details
 */
function woodash_get_order_details() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $order = wc_get_order($order_id);
        
        if (!$order) {
            wp_send_json_error('Order not found');
        }
        
        $order_data = woodash_format_order_data($order, true);
        
        wp_send_json_success($order_data);
        
    } catch (Exception $e) {
        wp_send_json_error('Error fetching order details: ' . $e->getMessage());
    }
}

/**
 * Get customers for dropdown
 */
function woodash_get_customers() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        
        $args = array(
            'role' => 'customer',
            'number' => 100,
            'orderby' => 'display_name',
            'order' => 'ASC'
        );
        
        if ($search) {
            $args['search'] = '*' . $search . '*';
            $args['search_columns'] = array('user_login', 'user_email', 'display_name');
        }
        
        $customers = get_users($args);
        
        $formatted_customers = array();
        foreach ($customers as $customer) {
            $first_name = get_user_meta($customer->ID, 'first_name', true);
            $last_name = get_user_meta($customer->ID, 'last_name', true);
            
            $formatted_customers[] = array(
                'id' => $customer->ID,
                'name' => trim($first_name . ' ' . $last_name) ?: $customer->display_name,
                'email' => $customer->user_email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone' => get_user_meta($customer->ID, 'billing_phone', true),
            );
        }
        
        wp_send_json_success($formatted_customers);
        
    } catch (Exception $e) {
        wp_send_json_error('Error fetching customers: ' . $e->getMessage());
    }
}

/**
 * Get products for order creation
 */
function woodash_get_products() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        
        $args = array(
            'type' => array('simple', 'variable'),
            'status' => 'publish',
            'limit' => 50,
            'orderby' => 'title',
            'order' => 'ASC',
        );
        
        if ($search) {
            $args['s'] = $search;
        }
        
        $products = wc_get_products($args);
        
        $formatted_products = array();
        foreach ($products as $product) {
            $formatted_products[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'price' => $product->get_price(),
                'formatted_price' => wc_price($product->get_price()),
                'stock_status' => $product->get_stock_status(),
                'stock_quantity' => $product->get_stock_quantity(),
                'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
            );
        }
        
        wp_send_json_success($formatted_products);
        
    } catch (Exception $e) {
        wp_send_json_error('Error fetching products: ' . $e->getMessage());
    }
}

/**
 * Search products with advanced filtering
 */
function woodash_search_products() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        $category = isset($_POST['category']) ? intval($_POST['category']) : 0;
        $stock_status = isset($_POST['stock_status']) ? sanitize_text_field($_POST['stock_status']) : '';
        $price_min = isset($_POST['price_min']) ? floatval($_POST['price_min']) : '';
        $price_max = isset($_POST['price_max']) ? floatval($_POST['price_max']) : '';
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 50;
        
        $args = array(
            'type' => array('simple', 'variable'),
            'status' => 'publish',
            'limit' => $limit,
            'orderby' => 'title',
            'order' => 'ASC',
        );
        
        // Search term
        if ($search) {
            $args['s'] = $search;
        }
        
        // Category filter
        if ($category > 0) {
            $args['category'] = array($category);
        }
        
        // Stock status filter
        if ($stock_status) {
            $args['stock_status'] = $stock_status;
        }
        
        // Price range filter
        if ($price_min !== '' || $price_max !== '') {
            $price_meta_query = array();
            
            if ($price_min !== '') {
                $price_meta_query[] = array(
                    'key' => '_price',
                    'value' => $price_min,
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                );
            }
            
            if ($price_max !== '') {
                $price_meta_query[] = array(
                    'key' => '_price',
                    'value' => $price_max,
                    'compare' => '<=',
                    'type' => 'NUMERIC'
                );
            }
            
            if (count($price_meta_query) > 1) {
                $price_meta_query['relation'] = 'AND';
            }
            
            $args['meta_query'] = $price_meta_query;
        }
        
        $products = wc_get_products($args);
        
        $formatted_products = array();
        foreach ($products as $product) {
            $formatted_products[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'sku' => $product->get_sku(),
                'price' => $product->get_price(),
                'regular_price' => $product->get_regular_price(),
                'sale_price' => $product->get_sale_price(),
                'formatted_price' => wc_price($product->get_price()),
                'stock_status' => $product->get_stock_status(),
                'stock_quantity' => $product->get_stock_quantity(),
                'manage_stock' => $product->get_manage_stock(),
                'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                'categories' => wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'names')),
                'total_sales' => $product->get_total_sales(),
            );
        }
        
        wp_send_json_success($formatted_products);
        
    } catch (Exception $e) {
        wp_send_json_error('Error searching products: ' . $e->getMessage());
    }
}

/**
 * Get dashboard statistics
 */
function woodash_get_dashboard_stats() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $stats = array(
            'total_orders' => woodash_get_orders_count(),
            'pending_orders' => woodash_get_orders_count('pending'),
            'processing_orders' => woodash_get_orders_count('processing'),
            'completed_orders' => woodash_get_orders_count('completed'),
            'cancelled_orders' => woodash_get_orders_count('cancelled'),
            'total_revenue' => woodash_get_total_revenue(),
            'monthly_revenue' => woodash_get_monthly_revenue(),
            'weekly_revenue' => woodash_get_weekly_revenue(),
            'daily_revenue' => woodash_get_daily_revenue(),
            'recent_orders' => woodash_get_recent_orders(10),
            'top_products' => woodash_get_top_products(5),
            'sales_chart_data' => woodash_get_sales_chart_data(),
        );
        
        wp_send_json_success($stats);
        
    } catch (Exception $e) {
        wp_send_json_error('Error fetching dashboard stats: ' . $e->getMessage());
    }
}

/**
 * Get analytics data for charts and reports
 */
function woodash_get_analytics_data() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : 'overview';
        $period = isset($_POST['period']) ? sanitize_text_field($_POST['period']) : '30days';

        $data = array();

        switch ($type) {
            case 'sales':
                $data = woodash_get_sales_chart_data();
                break;

            case 'revenue':
                $data = woodash_get_revenue_analytics_data($period);
                break;

            case 'customers':
                $data = woodash_get_customer_growth_data();
                break;

            case 'products':
                $data = woodash_get_top_products_analytics($period);
                break;

            case 'overview':
            default:
                $data = array(
                    'sales' => woodash_get_sales_chart_data(),
                    'revenue' => woodash_get_revenue_analytics_data($period),
                    'customers' => woodash_get_customer_growth_data(),
                    'products' => woodash_get_top_products_analytics($period)
                );
                break;
        }

        wp_send_json_success(array(
            'data' => $data,
            'type' => $type,
            'period' => $period
        ));

    } catch (Exception $e) {
        wp_send_json_error('Error fetching analytics data: ' . $e->get_message());
    }
}

/**
 * Get revenue analytics data (helper function)
 */
function woodash_get_revenue_analytics_data($period = '30days') {
    global $wpdb;

    $days = 30;
    switch ($period) {
        case '7days':
            $days = 7;
            break;
        case '30days':
            $days = 30;
            break;
        case '90days':
            $days = 90;
            break;
    }

    $data = array(
        'labels' => array(),
        'revenue' => array(),
        'orders' => array()
    );

    for ($i = $days - 1; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-{$i} days"));

        $revenue = $wpdb->get_var($wpdb->prepare("
            SELECT SUM(meta_value)
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key = '_order_total'
            AND p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND DATE(p.post_date) = %s
        ", $date));

        $orders = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*)
            FROM {$wpdb->posts} p
            WHERE p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND DATE(p.post_date) = %s
        ", $date));

        $data['labels'][] = date('M j', strtotime($date));
        $data['revenue'][] = floatval($revenue ?: 0);
        $data['orders'][] = intval($orders ?: 0);
    }

    return $data;
}

/**
 * Get top products analytics data (helper function)
 */
function woodash_get_top_products_analytics($period = '30days') {
    global $wpdb;

    $date_from = date('Y-m-d', strtotime("-{$period}"));

    $results = $wpdb->get_results($wpdb->prepare("
        SELECT
            p.ID as product_id,
            p.post_title as product_name,
            SUM(oi_qty.meta_value) as total_sales,
            SUM(oi_total.meta_value) as total_revenue
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->prefix}woocommerce_order_items oi ON p.ID = oi.order_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oi_qty ON oi.order_item_id = oi_qty.order_item_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oi_total ON oi.order_item_id = oi_total.order_item_id
        INNER JOIN {$wpdb->posts} ord ON oi.order_id = ord.ID
        WHERE p.post_type = 'shop_order'
        AND ord.post_status IN ('wc-completed', 'wc-processing')
        AND oi_qty.meta_key = '_qty'
        AND oi_total.meta_key = '_line_total'
        AND DATE(ord.post_date) >= %s
        GROUP BY p.ID
        ORDER BY total_revenue DESC
        LIMIT 10
    ", $date_from));

    $data = array();
    foreach ($results as $result) {
        $product = wc_get_product($result->product_id);
        $data[] = array(
            'product_id' => intval($result->product_id),
            'name' => $result->product_name,
            'sales' => intval($result->total_sales),
            'revenue' => floatval($result->total_revenue),
            'image' => $product ? wp_get_attachment_image_url($product->get_image_id(), 'thumbnail') : ''
        );
    }

    return $data;
}

/**
 * Helper Functions
 */

/**
 * Format order data for API response
 */
function woodash_format_order_data($order, $detailed = false) {
    $data = array(
        'id' => '#' . $order->get_order_number(),
        'order_id' => $order->get_id(),
        'status' => $order->get_status(),
        'date' => $order->get_date_created()->format('Y-m-d'),
        'date_created' => $order->get_date_created()->format('Y-m-d H:i:s'),
        'customer' => trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()),
        'email' => $order->get_billing_email(),
        'total' => $order->get_total(),
        'formatted_total' => wc_price($order->get_total()),
        'currency' => $order->get_currency(),
        'payment' => $order->is_paid() ? 'Paid' : 'Pending',
        'payment_method' => $order->get_payment_method_title(),
    );
    
    if ($detailed) {
        $data['billing_address'] = $order->get_address('billing');
        $data['shipping_address'] = $order->get_address('shipping');
        $data['items'] = array();
        
        foreach ($order->get_items() as $item) {
            $product = $item->get_product();
            $data['items'][] = array(
                'id' => $item->get_id(),
                'product_id' => $item->get_product_id(),
                'name' => $item->get_name(),
                'quantity' => $item->get_quantity(),
                'price' => wc_price($item->get_subtotal() / $item->get_quantity()),
                'qty' => $item->get_quantity(),
                'total' => wc_price($item->get_total()),
                'image' => $product ? wp_get_attachment_image_url($product->get_image_id(), 'thumbnail') : '',
            );
        }
        
        $data['order_notes'] = array();
        $notes = $order->get_customer_order_notes();
        foreach ($notes as $note) {
            $data['order_notes'][] = array(
                'date' => $note->comment_date,
                'note' => $note->comment_content,
            );
        }
        
        $data['subtotal'] = wc_price($order->get_subtotal());
        $data['tax'] = wc_price($order->get_total_tax());
        $data['shipping'] = wc_price($order->get_shipping_total());
    }
    
    return $data;
}

/**
 * Get orders count by status
 */
function woodash_get_orders_count($status = '') {
    $args = array('return' => 'ids');
    if ($status) {
        $args['status'] = 'wc-' . $status;
    }
    return count(wc_get_orders($args));
}

/**
 * Get total revenue
 */
function woodash_get_total_revenue() {
    global $wpdb;
    
    $result = $wpdb->get_var("
        SELECT SUM(meta_value) 
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE pm.meta_key = '_order_total'
        AND p.post_type = 'shop_order'
        AND p.post_status IN ('wc-completed', 'wc-processing')
    ");
    
    return $result ? floatval($result) : 0;
}

/**
 * Get monthly revenue
 */
function woodash_get_monthly_revenue() {
    global $wpdb;
    
    $result = $wpdb->get_var($wpdb->prepare("
        SELECT SUM(meta_value) 
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE pm.meta_key = '_order_total'
        AND p.post_type = 'shop_order'
        AND p.post_status IN ('wc-completed', 'wc-processing')
        AND p.post_date >= %s
    ", date('Y-m-01')));
    
    return $result ? floatval($result) : 0;
}

/**
 * Get weekly revenue
 */
function woodash_get_weekly_revenue() {
    global $wpdb;
    
    $result = $wpdb->get_var($wpdb->prepare("
        SELECT SUM(meta_value) 
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE pm.meta_key = '_order_total'
        AND p.post_type = 'shop_order'
        AND p.post_status IN ('wc-completed', 'wc-processing')
        AND p.post_date >= %s
    ", date('Y-m-d', strtotime('-7 days'))));
    
    return $result ? floatval($result) : 0;
}

/**
 * Get daily revenue
 */
function woodash_get_daily_revenue() {
    global $wpdb;
    
    $result = $wpdb->get_var($wpdb->prepare("
        SELECT SUM(meta_value) 
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE pm.meta_key = '_order_total'
        AND p.post_type = 'shop_order'
        AND p.post_status IN ('wc-completed', 'wc-processing')
        AND DATE(p.post_date) = %s
    ", date('Y-m-d')));
    
    return $result ? floatval($result) : 0;
}

/**
 * Get recent orders
 */
function woodash_get_recent_orders($limit = 5) {
    $orders = wc_get_orders(array(
        'limit' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
    ));
    
    $recent_orders = array();
    foreach ($orders as $order) {
        $recent_orders[] = woodash_format_order_data($order);
    }
    
    return $recent_orders;
}

/**
 * Get top selling products
 */
function woodash_get_top_products($limit = 5) {
    global $wpdb;
    
    $results = $wpdb->get_results($wpdb->prepare("
        SELECT 
            oim.meta_value as product_id,
            p.post_title as product_name,
            SUM(oim2.meta_value) as total_sales,
            SUM(oim3.meta_value) as total_revenue
        FROM {$wpdb->prefix}woocommerce_order_items oi
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim ON oi.order_item_id = oim.order_item_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim2 ON oi.order_item_id = oim2.order_item_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim3 ON oi.order_item_id = oim3.order_item_id
        INNER JOIN {$wpdb->posts} p ON oim.meta_value = p.ID
        INNER JOIN {$wpdb->posts} ord ON oi.order_id = ord.ID
        WHERE oim.meta_key = '_product_id'
        AND oim2.meta_key = '_qty'
        AND oim3.meta_key = '_line_total'
        AND ord.post_status IN ('wc-completed', 'wc-processing')
        AND p.post_status = 'publish'
        GROUP BY oim.meta_value
        ORDER BY total_sales DESC
        LIMIT %d
    ", $limit));
    
    $top_products = array();
    foreach ($results as $result) {
        $product = wc_get_product($result->product_id);
        if ($product) {
            $top_products[] = array(
                'id' => $result->product_id,
                'name' => $result->product_name,
                'total_sales' => intval($result->total_sales),
                'total_revenue' => floatval($result->total_revenue),
                'formatted_revenue' => wc_price($result->total_revenue),
                'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
            );
        }
    }
    
    return $top_products;
}

/**
 * Get sales chart data for the last 30 days
 */
function woodash_get_sales_chart_data() {
    global $wpdb;
    
    $data = array();
    
    for ($i = 29; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime('-' . $i . ' days'));
        
        $revenue = $wpdb->get_var($wpdb->prepare("
            SELECT SUM(meta_value) 
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key = '_order_total'
            AND p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND DATE(p.post_date) = %s
        ", $date));
        
        $orders_count = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) 
            FROM {$wpdb->posts} p
            WHERE p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND DATE(p.post_date) = %s
        ", $date));
        
        $data[] = array(
            'date' => $date,
            'revenue' => floatval($revenue ?: 0),
            'orders' => intval($orders_count ?: 0),
        );
    }
    
    return $data;
}

/**
 * Create new product
 */
function woodash_create_product() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $name = sanitize_text_field($_POST['name']);
        $description = sanitize_textarea_field($_POST['description']);
        $price = floatval($_POST['price']);
        $sale_price = isset($_POST['sale_price']) ? floatval($_POST['sale_price']) : '';
        $stock_quantity = intval($_POST['stock_quantity']);
        $sku = sanitize_text_field($_POST['sku']);
        $category_ids = isset($_POST['category_ids']) ? array_map('intval', $_POST['category_ids']) : array();
        
        // Create product
        $product = new WC_Product_Simple();
        $product->set_name($name);
        $product->set_description($description);
        $product->set_regular_price($price);
        
        if ($sale_price) {
            $product->set_sale_price($sale_price);
        }
        
        $product->set_stock_quantity($stock_quantity);
        $product->set_manage_stock(true);
        $product->set_sku($sku);
        $product->set_category_ids($category_ids);
        $product->set_status('publish');
        
        $product_id = $product->save();
        
        wp_send_json_success(array(
            'message' => 'Product created successfully',
            'product_id' => $product_id,
            'product' => woodash_format_product_data($product)
        ));
        
    } catch (Exception $e) {
        wp_send_json_error('Error creating product: ' . $e->getMessage());
    }
}

/**
 * Update existing product
 */
function woodash_update_product() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $product_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error('Product not found');
        }
        
        if (isset($_POST['name'])) {
            $product->set_name(sanitize_text_field($_POST['name']));
        }
        
        if (isset($_POST['price'])) {
            $product->set_regular_price(floatval($_POST['price']));
        }
        
        if (isset($_POST['sale_price'])) {
            $sale_price = $_POST['sale_price'];
            $product->set_sale_price($sale_price ? floatval($sale_price) : '');
        }
        
        if (isset($_POST['stock_quantity'])) {
            $product->set_stock_quantity(intval($_POST['stock_quantity']));
        }
        
        if (isset($_POST['status'])) {
            $product->set_status(sanitize_text_field($_POST['status']));
        }
        
        $product->save();
        
        wp_send_json_success(array(
            'message' => 'Product updated successfully',
            'product' => woodash_format_product_data($product)
        ));
        
    } catch (Exception $e) {
        wp_send_json_error('Error updating product: ' . $e->getMessage());
    }
}

/**
 * Delete product
 */
function woodash_delete_product() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $product_id = intval($_POST['product_id']);
        
        $result = wp_delete_post($product_id, true);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => 'Product deleted successfully'
            ));
        } else {
            wp_send_json_error('Failed to delete product');
        }
        
    } catch (Exception $e) {
        wp_send_json_error('Error deleting product: ' . $e->getMessage());
    }
}

/**
 * Get product statistics
 */
function woodash_get_product_stats() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $stats = array(
            'total_products' => wp_count_posts('product')->publish,
            'out_of_stock' => woodash_get_out_of_stock_count(),
            'low_stock' => woodash_get_low_stock_count(),
            'categories' => wp_count_terms('product_cat'),
            'revenue_by_product' => woodash_get_revenue_by_product(10),
            'stock_alerts' => woodash_get_stock_alerts(),
        );
        
        wp_send_json_success($stats);
        
    } catch (Exception $e) {
        wp_send_json_error('Error fetching product stats: ' . $e->getMessage());
    }
}

/**
 * Get customer details
 */
function woodash_get_customer_details() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
        $customer = get_user_by('id', $customer_id);

        if (!$customer) {
            wp_send_json_error('Customer not found');
        }

        // Get customer orders
        $orders = wc_get_orders(array(
            'customer_id' => $customer_id,
            'limit' => 10,
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        $formatted_orders = array();
        foreach ($orders as $order) {
            $formatted_orders[] = woodash_format_order_data($order);
        }

        // Get customer stats
        $stats = woodash_get_customer_stats_data($customer_id);

        $customer_data = array(
            'id' => $customer->ID,
            'name' => trim($customer->first_name . ' ' . $customer->last_name) ?: $customer->display_name,
            'email' => $customer->user_email,
            'first_name' => get_user_meta($customer->ID, 'first_name', true),
            'last_name' => get_user_meta($customer->ID, 'last_name', true),
            'phone' => get_user_meta($customer->ID, 'billing_phone', true),
            'billing_address' => array(
                'address_1' => get_user_meta($customer->ID, 'billing_address_1', true),
                'address_2' => get_user_meta($customer->ID, 'billing_address_2', true),
                'city' => get_user_meta($customer->ID, 'billing_city', true),
                'state' => get_user_meta($customer->ID, 'billing_state', true),
                'postcode' => get_user_meta($customer->ID, 'billing_postcode', true),
                'country' => get_user_meta($customer->ID, 'billing_country', true),
            ),
            'shipping_address' => array(
                'address_1' => get_user_meta($customer->ID, 'shipping_address_1', true),
                'address_2' => get_user_meta($customer->ID, 'shipping_address_2', true),
                'city' => get_user_meta($customer->ID, 'shipping_city', true),
                'state' => get_user_meta($customer->ID, 'shipping_state', true),
                'postcode' => get_user_meta($customer->ID, 'shipping_postcode', true),
                'country' => get_user_meta($customer->ID, 'shipping_country', true),
            ),
            'registered_date' => $customer->user_registered,
            'last_login' => get_user_meta($customer->ID, 'last_login', true),
            'orders' => $formatted_orders,
            'stats' => $stats
        );

        wp_send_json_success($customer_data);

    } catch (Exception $e) {
        wp_send_json_error('Error fetching customer details: ' . $e->getMessage());
    }
}

/**
 * Update customer
 */
function woodash_update_customer() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
        $customer = get_user_by('id', $customer_id);

        if (!$customer) {
            wp_send_json_error('Customer not found');
        }

        $update_data = array();

        // Update user data
        if (isset($_POST['first_name'])) {
            $update_data['first_name'] = sanitize_text_field($_POST['first_name']);
        }
        if (isset($_POST['last_name'])) {
            $update_data['last_name'] = sanitize_text_field($_POST['last_name']);
        }
        if (isset($_POST['email'])) {
            $update_data['user_email'] = sanitize_email($_POST['email']);
        }

        if (!empty($update_data)) {
            $update_data['ID'] = $customer_id;
            wp_update_user($update_data);
        }

        // Update meta data
        $meta_fields = array(
            'billing_phone', 'billing_address_1', 'billing_address_2', 'billing_city',
            'billing_state', 'billing_postcode', 'billing_country',
            'shipping_address_1', 'shipping_address_2', 'shipping_city',
            'shipping_state', 'shipping_postcode', 'shipping_country'
        );

        foreach ($meta_fields as $field) {
            if (isset($_POST[$field])) {
                update_user_meta($customer_id, $field, sanitize_text_field($_POST[$field]));
            }
        }

        wp_send_json_success(array(
            'message' => 'Customer updated successfully'
        ));

    } catch (Exception $e) {
        wp_send_json_error('Error updating customer: ' . $e->getMessage());
    }
}

/**
 * Delete customer
 */
function woodash_delete_customer() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;

        if (!$customer_id) {
            wp_send_json_error('Customer ID is required');
        }

        // Check if customer has orders
        $order_count = wc_get_orders(array(
            'customer_id' => $customer_id,
            'return' => 'ids'
        ));

        if (!empty($order_count)) {
            wp_send_json_error('Cannot delete customer with existing orders. Please reassign orders first.');
        }

        // Delete customer
        $result = wp_delete_user($customer_id);

        if ($result) {
            wp_send_json_success(array(
                'message' => 'Customer deleted successfully'
            ));
        } else {
            wp_send_json_error('Failed to delete customer');
        }

    } catch (Exception $e) {
        wp_send_json_error('Error deleting customer: ' . $e->getMessage());
    }
}

/**
 * Get customer orders
 */
function woodash_get_customer_orders() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 20;

        $orders = wc_get_orders(array(
            'customer_id' => $customer_id,
            'limit' => $per_page,
            'offset' => ($page - 1) * $per_page,
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        $formatted_orders = array();
        foreach ($orders as $order) {
            $formatted_orders[] = woodash_format_order_data($order);
        }

        wp_send_json_success(array(
            'orders' => $formatted_orders,
            'page' => $page,
            'per_page' => $per_page
        ));

    } catch (Exception $e) {
        wp_send_json_error('Error fetching customer orders: ' . $e->getMessage());
    }
}

/**
 * Get customer statistics
 */
function woodash_get_customer_stats() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;

        $stats = woodash_get_customer_stats_data($customer_id);

        wp_send_json_success($stats);

    } catch (Exception $e) {
        wp_send_json_error('Error fetching customer stats: ' . $e->getMessage());
    }
}

/**
 * Helper function to get customer statistics
 */
function woodash_get_customer_stats_data($customer_id) {
    global $wpdb;

    // Total orders
    $total_orders = wc_get_orders(array(
        'customer_id' => $customer_id,
        'return' => 'ids'
    ));

    // Total spent
    $total_spent = $wpdb->get_var($wpdb->prepare("
        SELECT SUM(meta_value)
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE pm.meta_key = '_order_total'
        AND p.post_type = 'shop_order'
        AND p.post_status IN ('wc-completed', 'wc-processing')
        AND pm.post_id IN (
            SELECT post_id FROM {$wpdb->postmeta}
            WHERE meta_key = '_customer_user' AND meta_value = %d
        )
    ", $customer_id));

    // Average order value
    $avg_order_value = count($total_orders) > 0 ? $total_spent / count($total_orders) : 0;

    // Last order date
    $last_order = wc_get_orders(array(
        'customer_id' => $customer_id,
        'limit' => 1,
        'orderby' => 'date',
        'order' => 'DESC'
    ));

    $last_order_date = !empty($last_order) ? $last_order[0]->get_date_created()->format('Y-m-d') : null;

    // Customer lifetime (days since registration)
    $customer = get_user_by('id', $customer_id);
    $registration_date = strtotime($customer->user_registered);
    $lifetime_days = floor((time() - $registration_date) / (60 * 60 * 24));

    return array(
        'total_orders' => count($total_orders),
        'total_spent' => floatval($total_spent ?: 0),
        'avg_order_value' => floatval($avg_order_value),
        'last_order_date' => $last_order_date,
        'lifetime_days' => $lifetime_days,
        'formatted_total_spent' => wc_price($total_spent ?: 0),
        'formatted_avg_order_value' => wc_price($avg_order_value)
    );
}

/**
 * Create new customer
 */
function woodash_create_customer() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);

        if (!$email) {
            wp_send_json_error('Email is required');
        }

        // Check if user already exists
        $existing_user = get_user_by('email', $email);
        if ($existing_user) {
            wp_send_json_error('A user with this email already exists');
        }

        // Create new user
        $user_id = wc_create_new_customer($email, '', '', array(
            'first_name' => $first_name,
            'last_name' => $last_name,
        ));

        if (is_wp_error($user_id)) {
            wp_send_json_error('Error creating customer: ' . $user_id->get_error_message());
        }

        // Update additional meta
        if ($phone) {
            update_user_meta($user_id, 'billing_phone', $phone);
        }

        wp_send_json_success(array(
            'message' => 'Customer created successfully',
            'customer_id' => $user_id
        ));

    } catch (Exception $e) {
        wp_send_json_error('Error creating customer: ' . $e->getMessage());
    }
}

/**
 * Get customer analytics data
 */
function woodash_get_customer_analytics() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        global $wpdb;

        // Total customers
        $total_customers = $wpdb->get_var("
            SELECT COUNT(*)
            FROM {$wpdb->users} u
            INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
            WHERE um.meta_key = '{$wpdb->prefix}capabilities'
            AND um.meta_value LIKE '%customer%'
        ");

        // New customers this month
        $new_customers_month = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*)
            FROM {$wpdb->users} u
            INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
            WHERE um.meta_key = '{$wpdb->prefix}capabilities'
            AND um.meta_value LIKE '%customer%'
            AND u.user_registered >= %s
        ", date('Y-m-01')));

        // Average customer value
        $avg_customer_value = $wpdb->get_var("
            SELECT AVG(total_spent) as avg_value
            FROM (
                SELECT SUM(oi_meta.meta_value) as total_spent
                FROM {$wpdb->prefix}woocommerce_order_items oi
                INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oi_meta ON oi.order_item_id = oi_meta.order_item_id
                INNER JOIN {$wpdb->posts} p ON oi.order_id = p.ID
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                WHERE oi_meta.meta_key = '_line_total'
                AND p.post_type = 'shop_order'
                AND p.post_status IN ('wc-completed', 'wc-processing')
                AND pm.meta_key = '_customer_user'
                AND pm.meta_value != '0'
                GROUP BY pm.meta_value
            ) as customer_totals
        ");

        // Customer retention rate (simplified - customers who ordered in last 30 days vs total)
        $active_customers_30 = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(DISTINCT pm.meta_value)
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key = '_customer_user'
            AND pm.meta_value != '0'
            AND p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND p.post_date >= %s
        ", date('Y-m-d', strtotime('-30 days'))));

        $retention_rate = $total_customers > 0 ? ($active_customers_30 / $total_customers) * 100 : 0;

        // Growth rate (new customers vs last month)
        $last_month_new = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*)
            FROM {$wpdb->users} u
            INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
            WHERE um.meta_key = '{$wpdb->prefix}capabilities'
            AND um.meta_value LIKE '%customer%'
            AND u.user_registered >= %s
            AND u.user_registered < %s
        ", date('Y-m-01', strtotime('-1 month')), date('Y-m-01')));

        $growth_rate = $last_month_new > 0 ? (($new_customers_month - $last_month_new) / $last_month_new) * 100 : 0;

        wp_send_json_success(array(
            'total_customers' => intval($total_customers),
            'new_customers_month' => intval($new_customers_month),
            'avg_customer_value' => floatval($avg_customer_value ?: 0),
            'retention_rate' => floatval($retention_rate),
            'growth_rate' => floatval($growth_rate)
        ));

    } catch (Exception $e) {
        wp_send_json_error('Error fetching customer analytics: ' . $e->get_message());
    }
}

/**
 * Get enhanced customers list with filtering
 */
function woodash_get_customers_enhanced() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        global $wpdb;

        $segment = isset($_POST['segment']) ? sanitize_text_field($_POST['segment']) : 'all';
        $date_filter = isset($_POST['date_filter']) ? sanitize_text_field($_POST['date_filter']) : 'all';
        $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 25;
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $offset = ($page - 1) * $per_page;

        // Base query for customers
        $where_conditions = array();
        $where_conditions[] = "um.meta_key = '{$wpdb->prefix}capabilities'";
        $where_conditions[] = "um.meta_value LIKE '%customer%'";

        // Date filter
        if ($date_filter !== 'all') {
            $date_from = '';
            switch ($date_filter) {
                case 'last30days':
                    $date_from = date('Y-m-d', strtotime('-30 days'));
                    break;
                case 'last90days':
                    $date_from = date('Y-m-d', strtotime('-90 days'));
                    break;
                case 'thisyear':
                    $date_from = date('Y-01-01');
                    break;
            }
            if ($date_from) {
                $where_conditions[] = $wpdb->prepare("u.user_registered >= %s", $date_from);
            }
        }

        $where_clause = implode(' AND ', $where_conditions);

        // Get total count
        $total_count = $wpdb->get_var("
            SELECT COUNT(DISTINCT u.ID)
            FROM {$wpdb->users} u
            INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
            WHERE {$where_clause}
        ");

        // Get customers with order data
        $customers_query = "
            SELECT
                u.ID,
                u.user_login,
                u.user_email,
                u.user_registered,
                u.display_name,
                COALESCE(first_name.meta_value, '') as first_name,
                COALESCE(last_name.meta_value, '') as last_name,
                COALESCE(phone.meta_value, '') as phone,
                COALESCE(order_stats.total_orders, 0) as total_orders,
                COALESCE(order_stats.total_spent, 0) as total_spent,
                order_stats.last_order_date
            FROM {$wpdb->users} u
            INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
            LEFT JOIN {$wpdb->usermeta} first_name ON u.ID = first_name.user_id AND first_name.meta_key = 'first_name'
            LEFT JOIN {$wpdb->usermeta} last_name ON u.ID = last_name.user_id AND last_name.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} phone ON u.ID = phone.user_id AND phone.meta_key = 'billing_phone'
            LEFT JOIN (
                SELECT
                    pm.meta_value as customer_id,
                    COUNT(DISTINCT p.ID) as total_orders,
                    SUM(oi_meta.meta_value) as total_spent,
                    MAX(p.post_date) as last_order_date
                FROM {$wpdb->postmeta} pm
                INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                LEFT JOIN {$wpdb->prefix}woocommerce_order_items oi ON p.ID = oi.order_id
                LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta oi_meta ON oi.order_item_id = oi_meta.order_item_id AND oi_meta.meta_key = '_line_total'
                WHERE pm.meta_key = '_customer_user'
                AND pm.meta_value != '0'
                AND p.post_type = 'shop_order'
                AND p.post_status IN ('wc-completed', 'wc-processing')
                GROUP BY pm.meta_value
            ) order_stats ON u.ID = order_stats.customer_id
            WHERE {$where_clause}
            GROUP BY u.ID
            ORDER BY u.user_registered DESC
            LIMIT %d OFFSET %d
        ";

        $customers = $wpdb->get_results($wpdb->prepare($customers_query, $per_page, $offset));

        $formatted_customers = array();
        foreach ($customers as $customer) {
            $first_name = $customer->first_name;
            $last_name = $customer->last_name;
            $name = trim($first_name . ' ' . $last_name) ?: $customer->display_name;

            $formatted_customers[] = array(
                'id' => intval($customer->ID),
                'name' => $name,
                'email' => $customer->user_email,
                'phone' => $customer->phone,
                'total_orders' => intval($customer->total_orders),
                'total_spent' => floatval($customer->total_spent),
                'last_order_date' => $customer->last_order_date,
                'registered_date' => $customer->user_registered
            );
        }

        // Apply segment filtering in PHP
        if ($segment !== 'all') {
            $formatted_customers = array_filter($formatted_customers, function($customer) use ($segment) {
                switch ($segment) {
                    case 'vip':
                        return $customer['total_orders'] >= 10;
                    case 'new':
                        return $customer['total_orders'] <= 1;
                    case 'returning':
                        return $customer['total_orders'] > 1 && $customer['total_orders'] < 10;
                    case 'inactive':
                        $last_order = $customer['last_order_date'] ? strtotime($customer['last_order_date']) : 0;
                        return (time() - $last_order) > (90 * 24 * 60 * 60); // 90 days
                    default:
                        return true;
                }
            });
        }

        wp_send_json_success(array(
            'customers' => array_values($formatted_customers),
            'total' => intval($total_count),
            'page' => $page,
            'per_page' => $per_page
        ));

    } catch (Exception $e) {
        wp_send_json_error('Error fetching customers: ' . $e->get_message());
    }
}

/**
 * Get customer growth data for charts
 */
function woodash_get_customer_growth_data() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        global $wpdb;

        $range = isset($_POST['range']) ? sanitize_text_field($_POST['range']) : '30days';

        // Determine date range
        $days = 30;
        switch ($range) {
            case '7days':
                $days = 7;
                break;
            case '30days':
                $days = 30;
                break;
            case '90days':
                $days = 90;
                break;
        }

        $data = array(
            'labels' => array(),
            'newCustomers' => array(),
            'totalCustomers' => array()
        );

        $total_customers = 0;

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $date_next = date('Y-m-d', strtotime("-" . ($i - 1) . " days"));

            // New customers on this date
            $new_customers = $wpdb->get_var($wpdb->prepare("
                SELECT COUNT(*)
                FROM {$wpdb->users} u
                INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
                WHERE um.meta_key = '{$wpdb->prefix}capabilities'
                AND um.meta_value LIKE '%customer%'
                AND DATE(u.user_registered) = %s
            ", $date));

            // Total customers up to this date
            $total_customers += $new_customers;

            $data['labels'][] = date('M j', strtotime($date));
            $data['newCustomers'][] = intval($new_customers);
            $data['totalCustomers'][] = $total_customers;
        }

        wp_send_json_success($data);

    } catch (Exception $e) {
        wp_send_json_error('Error fetching customer growth data: ' . $e->get_message());
    }
}

/**
 * Get out of stock products count
 */
function woodash_get_out_of_stock_count() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_stock_status',
                'value' => 'outofstock',
                'compare' => '='
            )
        )
    );

    $query = new WP_Query($args);
    return $query->found_posts;
}

/**
 * Get low stock products count
 */
function woodash_get_low_stock_count() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_stock_status',
                'value' => 'instock',
                'compare' => '='
            ),
            array(
                'key' => '_stock',
                'value' => get_option('woocommerce_notify_low_stock_amount', 2),
                'compare' => '<=',
                'type' => 'NUMERIC'
            ),
            array(
                'key' => '_manage_stock',
                'value' => 'yes',
                'compare' => '='
            )
        )
    );

    $query = new WP_Query($args);
    return $query->found_posts;
}

/**
 * Get revenue by product
 */
function woodash_get_revenue_by_product($limit = 10) {
    global $wpdb;

    $results = $wpdb->get_results($wpdb->prepare("
        SELECT
            p.ID,
            p.post_title as product_name,
            SUM(oi_meta.meta_value) as revenue,
            SUM(oi_qty.meta_value) as total_sales
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->prefix}woocommerce_order_items oi ON p.ID = oi.order_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oi_meta ON oi.order_item_id = oi_meta.order_item_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oi_qty ON oi.order_item_id = oi_qty.order_item_id
        WHERE p.post_type = 'shop_order'
        AND p.post_status IN ('wc-completed', 'wc-processing')
        AND oi_meta.meta_key = '_line_total'
        AND oi_qty.meta_key = '_qty'
        GROUP BY p.ID
        ORDER BY revenue DESC
        LIMIT %d
    ", $limit));

    $revenue_data = array();
    foreach ($results as $result) {
        $revenue_data[] = array(
            'product_id' => $result->ID,
            'product_name' => $result->product_name,
            'revenue' => floatval($result->revenue),
            'total_sales' => intval($result->total_sales),
            'formatted_revenue' => wc_price($result->revenue)
        );
    }

    return $revenue_data;
}

/**
 * Get stock alerts
 */
function woodash_get_stock_alerts() {
    $alerts = array();

    // Out of stock products
    $out_of_stock = woodash_get_out_of_stock_count();
    if ($out_of_stock > 0) {
        $alerts[] = array(
            'type' => 'out_of_stock',
            'message' => sprintf('%d products are out of stock', $out_of_stock),
            'count' => $out_of_stock,
            'severity' => 'high'
        );
    }

    // Low stock products
    $low_stock = woodash_get_low_stock_count();
    if ($low_stock > 0) {
        $alerts[] = array(
            'type' => 'low_stock',
            'message' => sprintf('%d products are low on stock', $low_stock),
            'count' => $low_stock,
            'severity' => 'medium'
        );
    }

    return $alerts;
}

/**
 * Format product data for API response
 */
function woodash_format_product_data($product) {
    return array(
        'id' => $product->get_id(),
        'name' => $product->get_name(),
        'sku' => $product->get_sku(),
        'status' => $product->get_status(),
        'price' => $product->get_price(),
        'regular_price' => $product->get_regular_price(),
        'sale_price' => $product->get_sale_price(),
        'formatted_price' => wc_price($product->get_price()),
        'stock_status' => $product->get_stock_status(),
        'stock_quantity' => $product->get_stock_quantity(),
        'manage_stock' => $product->get_manage_stock(),
        'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
        'categories' => wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'names')),
        'date_created' => $product->get_date_created()->format('Y-m-d H:i:s'),
        'total_sales' => $product->get_total_sales(),
    );
}

/**
 * Get sales report data
 */
function woodash_get_sales_report() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        global $wpdb;

        $date_from = isset($_POST['date_from']) ? sanitize_text_field($_POST['date_from']) : date('Y-m-d', strtotime('-30 days'));
        $date_to = isset($_POST['date_to']) ? sanitize_text_field($_POST['date_to']) : date('Y-m-d');
        $report_type = isset($_POST['report_type']) ? sanitize_text_field($_POST['report_type']) : 'daily';

        $data = array();

        if ($report_type === 'daily') {
            // Daily sales report
            $query = $wpdb->prepare("
                SELECT
                    DATE(p.post_date) as date,
                    COUNT(DISTINCT p.ID) as orders_count,
                    SUM(pm.meta_value) as revenue,
                    AVG(pm.meta_value) as avg_order_value
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                WHERE p.post_type = 'shop_order'
                AND p.post_status IN ('wc-completed', 'wc-processing')
                AND pm.meta_key = '_order_total'
                AND DATE(p.post_date) BETWEEN %s AND %s
                GROUP BY DATE(p.post_date)
                ORDER BY date ASC
            ", $date_from, $date_to);

            $results = $wpdb->get_results($query);

            foreach ($results as $result) {
                $data[] = array(
                    'date' => $result->date,
                    'orders' => intval($result->orders_count),
                    'revenue' => floatval($result->revenue),
                    'avg_order_value' => floatval($result->avg_order_value),
                    'formatted_revenue' => wc_price($result->revenue),
                    'formatted_avg_order' => wc_price($result->avg_order_value)
                );
            }
        } elseif ($report_type === 'monthly') {
            // Monthly sales report
            $query = $wpdb->prepare("
                SELECT
                    DATE_FORMAT(p.post_date, '%Y-%m') as month,
                    COUNT(DISTINCT p.ID) as orders_count,
                    SUM(pm.meta_value) as revenue,
                    AVG(pm.meta_value) as avg_order_value
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                WHERE p.post_type = 'shop_order'
                AND p.post_status IN ('wc-completed', 'wc-processing')
                AND pm.meta_key = '_order_total'
                AND DATE(p.post_date) BETWEEN %s AND %s
                GROUP BY DATE_FORMAT(p.post_date, '%Y-%m')
                ORDER BY month ASC
            ", $date_from, $date_to);

            $results = $wpdb->get_results($query);

            foreach ($results as $result) {
                $data[] = array(
                    'month' => $result->month,
                    'orders' => intval($result->orders_count),
                    'revenue' => floatval($result->revenue),
                    'avg_order_value' => floatval($result->avg_order_value),
                    'formatted_revenue' => wc_price($result->revenue),
                    'formatted_avg_order' => wc_price($result->avg_order_value)
                );
            }
        }

        wp_send_json_success(array(
            'data' => $data,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'report_type' => $report_type
        ));

    } catch (Exception $e) {
        wp_send_json_error('Error fetching sales report: ' . $e->get_message());
    }
}

/**
 * Get product report data
 */
function woodash_get_product_report() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        global $wpdb;

        $date_from = isset($_POST['date_from']) ? sanitize_text_field($_POST['date_from']) : date('Y-m-d', strtotime('-30 days'));
        $date_to = isset($_POST['date_to']) ? sanitize_text_field($_POST['date_to']) : date('Y-m-d');
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 20;

        $query = $wpdb->prepare("
            SELECT
                p.ID as product_id,
                p.post_title as product_name,
                SUM(oi_qty.meta_value) as total_sales,
                SUM(oi_total.meta_value) as total_revenue,
                COUNT(DISTINCT ord.ID) as orders_count,
                AVG(oi_total.meta_value) as avg_price
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->prefix}woocommerce_order_items oi ON p.ID = oi.order_id
            INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oi_qty ON oi.order_item_id = oi_qty.order_item_id
            INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oi_total ON oi.order_item_id = oi_total.order_item_id
            INNER JOIN {$wpdb->posts} ord ON oi.order_id = ord.ID
            WHERE p.post_type = 'shop_order'
            AND ord.post_status IN ('wc-completed', 'wc-processing')
            AND oi_qty.meta_key = '_qty'
            AND oi_total.meta_key = '_line_total'
            AND DATE(ord.post_date) BETWEEN %s AND %s
            GROUP BY p.ID
            ORDER BY total_revenue DESC
            LIMIT %d
        ", $date_from, $date_to, $limit);

        $results = $wpdb->get_results($query);

        $data = array();
        foreach ($results as $result) {
            $product = wc_get_product($result->product_id);
            $data[] = array(
                'product_id' => intval($result->product_id),
                'product_name' => $result->product_name,
                'total_sales' => intval($result->total_sales),
                'total_revenue' => floatval($result->total_revenue),
                'orders_count' => intval($result->orders_count),
                'avg_price' => floatval($result->avg_price),
                'formatted_revenue' => wc_price($result->total_revenue),
                'formatted_avg_price' => wc_price($result->avg_price),
                'image' => $product ? wp_get_attachment_image_url($product->get_image_id(), 'thumbnail') : ''
            );
        }

        wp_send_json_success(array(
            'data' => $data,
            'date_from' => $date_from,
            'date_to' => $date_to
        ));

    } catch (Exception $e) {
        wp_send_json_error('Error fetching product report: ' . $e->get_message());
    }
}

/**
 * Get customer report data
 */
function woodash_get_customer_report() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        global $wpdb;

        $date_from = isset($_POST['date_from']) ? sanitize_text_field($_POST['date_from']) : date('Y-m-d', strtotime('-30 days'));
        $date_to = isset($_POST['date_to']) ? sanitize_text_field($_POST['date_to']) : date('Y-m-d');
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 20;

        $query = $wpdb->prepare("
            SELECT
                pm.meta_value as customer_id,
                u.user_email,
                u.display_name,
                COUNT(DISTINCT p.ID) as orders_count,
                SUM(pm_total.meta_value) as total_spent,
                AVG(pm_total.meta_value) as avg_order_value,
                MAX(p.post_date) as last_order_date,
                MIN(p.post_date) as first_order_date
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            INNER JOIN {$wpdb->postmeta} pm_total ON p.ID = pm_total.post_id
            INNER JOIN {$wpdb->users} u ON pm.meta_value = u.ID
            WHERE pm.meta_key = '_customer_user'
            AND pm.meta_value != '0'
            AND pm_total.meta_key = '_order_total'
            AND p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND DATE(p.post_date) BETWEEN %s AND %s
            GROUP BY pm.meta_value
            ORDER BY total_spent DESC
            LIMIT %d
        ", $date_from, $date_to, $limit);

        $results = $wpdb->get_results($query);

        $data = array();
        foreach ($results as $result) {
            $data[] = array(
                'customer_id' => intval($result->customer_id),
                'customer_name' => $result->display_name,
                'customer_email' => $result->user_email,
                'orders_count' => intval($result->orders_count),
                'total_spent' => floatval($result->total_spent),
                'avg_order_value' => floatval($result->avg_order_value),
                'last_order_date' => $result->last_order_date,
                'first_order_date' => $result->first_order_date,
                'formatted_total_spent' => wc_price($result->total_spent),
                'formatted_avg_order' => wc_price($result->avg_order_value)
            );
        }

        wp_send_json_success(array(
            'data' => $data,
            'date_from' => $date_from,
            'date_to' => $date_to
        ));

    } catch (Exception $e) {
        wp_send_json_error('Error fetching customer report: ' . $e->get_message());
    }
}

/**
 * Get revenue analytics data
 */
function woodash_get_revenue_analytics() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized', 403);
    }

    try {
        global $wpdb;

        $period = isset($_POST['period']) ? sanitize_text_field($_POST['period']) : '30days';

        // Determine date range
        $days = 30;
        switch ($period) {
            case '7days':
                $days = 7;
                break;
            case '30days':
                $days = 30;
                break;
            case '90days':
                $days = 90;
                break;
            case '1year':
                $days = 365;
                break;
        }

        $data = array(
            'labels' => array(),
            'revenue' => array(),
            'orders' => array(),
            'avg_order_value' => array()
        );

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));

            // Revenue for this date
            $revenue = $wpdb->get_var($wpdb->prepare("
                SELECT SUM(meta_value)
                FROM {$wpdb->postmeta} pm
                INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                WHERE pm.meta_key = '_order_total'
                AND p.post_type = 'shop_order'
                AND p.post_status IN ('wc-completed', 'wc-processing')
                AND DATE(p.post_date) = %s
            ", $date));

            // Orders count for this date
            $orders_count = $wpdb->get_var($wpdb->prepare("
                SELECT COUNT(*)
                FROM {$wpdb->posts} p
                WHERE p.post_type = 'shop_order'
                AND p.post_status IN ('wc-completed', 'wc-processing')
                AND DATE(p.post_date) = %s
            ", $date));

            $avg_order_value = $orders_count > 0 ? $revenue / $orders_count : 0;

            $data['labels'][] = date('M j', strtotime($date));
            $data['revenue'][] = floatval($revenue ?: 0);
            $data['orders'][] = intval($orders_count ?: 0);
            $data['avg_order_value'][] = floatval($avg_order_value);
        }

        wp_send_json_success(array(
            'data' => $data,
            'period' => $period,
            'total_revenue' => array_sum($data['revenue']),
            'total_orders' => array_sum($data['orders']),
            'avg_order_value' => count(array_filter($data['avg_order_value'])) > 0 ?
                array_sum($data['avg_order_value']) / count(array_filter($data['avg_order_value'])) : 0
        ));

    } catch (Exception $e) {
        wp_send_json_error('Error fetching revenue analytics: ' . $e->get_message());
    }
}
