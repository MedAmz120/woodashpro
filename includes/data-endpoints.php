<?php
if (!defined('ABSPATH')) exit;

// Fetch WooCommerce analytics data
add_action('wp_ajax_woodash_get_data', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) wp_send_json_error('Unauthorized', 403);

    // Get date range from request
    $date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : null;
    $date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : null;

    $order_args = [
        'status' => 'completed',
        'limit' => -1,
    ];

    // Add date query if provided
    if ($date_from && $date_to) {
        $order_args['date_created'] = $date_from . ' 00:00:00...' . $date_to . ' 23:59:59';
    }

    $orders = wc_get_orders($order_args);

    // Calculate total sales (sum of order totals)
    $total_sales_amount = 0;
    foreach ($orders as $order) {
        $total_sales_amount += (float) $order->get_total();
    }
    $total_sales = round($total_sales_amount, 2);

    // Fetch average order value
    $aov = count($orders) > 0 ? round($total_sales_amount / count($orders), 2) : 0;

    // Fetch sales over time (for the selected range, or last 7 days by default)
    $sales_over_time = [];
    if ($date_from && $date_to) {
        $start = strtotime($date_from);
        $end = strtotime($date_to);
        for ($d = $start; $d <= $end; $d += 86400) {
            $date = date('Y-m-d', $d);
            $sales_over_time[$date] = 0;
        }
    } else {
        $date_range = range(7, 0);
        foreach ($date_range as $days_ago) {
            $date = date('Y-m-d', strtotime("-$days_ago days"));
            $sales_over_time[$date] = 0;
        }
    }
    foreach ($orders as $order) {
        $order_date = $order->get_date_created()->date('Y-m-d');
        if (isset($sales_over_time[$order_date])) {
            $sales_over_time[$order_date] += (float) $order->get_total();
        }
    }

    wp_send_json([
        'total_sales' => $total_sales,
        'aov' => $aov,
        'sales_over_time' => $sales_over_time,
    ]);
});

// Get coupon data
add_action('wp_ajax_woodash_get_coupon_data', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error('Unauthorized', 403);
    }

    $coupon_id = intval($_POST['coupon_id']);
    $coupon = new WC_Coupon($coupon_id);

    if (!$coupon) {
        wp_send_json_error('Coupon not found', 404);
    }

    $data = array(
        'id' => $coupon->get_id(),
        'code' => $coupon->get_code(),
        'type' => $coupon->get_discount_type(),
        'amount' => $coupon->get_amount(),
        'expiry_date' => $coupon->get_date_expires() ? $coupon->get_date_expires()->date('Y-m-d') : ''
    );

    wp_send_json_success($data);
});

// Update coupon
add_action('wp_ajax_woodash_update_coupon', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error('Unauthorized', 403);
    }

    $coupon_id = intval($_POST['coupon_id']);
    $coupon = new WC_Coupon($coupon_id);

    if (!$coupon) {
        wp_send_json_error('Coupon not found', 404);
    }

    // Update coupon data
    $coupon->set_code(sanitize_text_field($_POST['code']));
    $coupon->set_discount_type(sanitize_text_field($_POST['discount_type']));
    $coupon->set_amount(floatval($_POST['amount']));
    
    if (!empty($_POST['expiry_date'])) {
        $coupon->set_date_expires(strtotime($_POST['expiry_date']));
    } else {
        $coupon->set_date_expires(null);
    }

    $coupon->save();

    wp_send_json_success('Coupon updated successfully');
});

// Remove coupon
add_action('wp_ajax_woodash_remove_coupon', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error('Unauthorized', 403);
    }

    $coupon_id = intval($_POST['coupon_id']);
    $coupon = new WC_Coupon($coupon_id);

    if (!$coupon) {
        wp_send_json_error('Coupon not found', 404);
    }

    $coupon->delete(true);

    wp_send_json_success('Coupon removed successfully');
});

// Get coupons list
add_action('wp_ajax_woodash_get_coupons_list', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error('Unauthorized', 403);
    }

    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'shop_coupon',
        'post_status' => 'publish',
    );

    $coupons = get_posts($args);
    $html = '';

    foreach ($coupons as $coupon_post) {
        $coupon = new WC_Coupon($coupon_post->ID);
        $html .= sprintf(
            '<div class="woodash-card p-4 hover:shadow-md transition-shadow duration-200" data-coupon-id="%d">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="font-medium text-gray-900">%s</h4>
                        <p class="text-sm text-gray-600">%s</p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="edit-coupon woodash-btn woodash-btn-icon" title="Edit Coupon">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="remove-coupon woodash-btn woodash-btn-icon woodash-btn-danger" title="Remove Coupon">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>',
            $coupon->get_id(),
            esc_html($coupon->get_code()),
            $coupon->get_discount_type() === 'percent' ? 
                esc_html($coupon->get_amount()) . '%' : 
                '$' . esc_html($coupon->get_amount())
        );
    }

    wp_send_json_success($html);
});

// Create new coupon
add_action('wp_ajax_woodash_create_coupon', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error('Unauthorized', 403);
    }

    // Validate required fields
    if (empty($_POST['code']) || empty($_POST['discount_type']) || !isset($_POST['amount'])) {
        wp_send_json_error('Missing required fields', 400);
    }

    // Check if coupon code already exists
    $existing_coupon = new WC_Coupon($_POST['code']);
    if ($existing_coupon->get_id()) {
        wp_send_json_error('Coupon code already exists', 400);
    }

    // Create new coupon
    $coupon = new WC_Coupon();
    $coupon->set_code(sanitize_text_field($_POST['code']));
    $coupon->set_discount_type(sanitize_text_field($_POST['discount_type']));
    $coupon->set_amount(floatval($_POST['amount']));
    
    // Set optional fields
    if (!empty($_POST['expiry_date'])) {
        $coupon->set_date_expires(strtotime($_POST['expiry_date']));
    }
    
    if (!empty($_POST['usage_limit'])) {
        $coupon->set_usage_limit(intval($_POST['usage_limit']));
    }
    
    if (!empty($_POST['minimum_amount'])) {
        $coupon->set_minimum_amount(floatval($_POST['minimum_amount']));
    }
    
    if (!empty($_POST['maximum_amount'])) {
        $coupon->set_maximum_amount(floatval($_POST['maximum_amount']));
    }
    
    $coupon->set_individual_use(isset($_POST['individual_use']) && $_POST['individual_use'] === 'true');
    $coupon->set_exclude_sale_items(isset($_POST['exclude_sale_items']) && $_POST['exclude_sale_items'] === 'true');

    try {
        $coupon->save();
        wp_send_json_success('Coupon created successfully');
    } catch (Exception $e) {
        wp_send_json_error($e->getMessage(), 500);
    }
});

/**
 * Handle new item creation
 */
function woodash_add_item() {
    check_ajax_referer('woodash_nonce', 'nonce');

    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error('Insufficient permissions');
    }

    // Validate required fields
    $required_fields = array('item_name', 'sku', 'regular_price', 'stock_quantity');
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            wp_send_json_error("Missing required field: $field");
        }
    }

    // Create new product
    $product = new WC_Product_Simple();
    
    // Set basic information
    $product->set_name(sanitize_text_field($_POST['item_name']));
    $product->set_sku(sanitize_text_field($_POST['sku']));
    $product->set_regular_price(sanitize_text_field($_POST['regular_price']));
    
    if (!empty($_POST['sale_price'])) {
        $product->set_sale_price(sanitize_text_field($_POST['sale_price']));
    }
    
    $product->set_manage_stock(true);
    $product->set_stock_quantity(intval($_POST['stock_quantity']));
    
    // Set description
    if (!empty($_POST['description'])) {
        $product->set_description(wp_kses_post($_POST['description']));
    }

    // Set categories
    if (!empty($_POST['categories'])) {
        $product->set_category_ids(array_map('intval', $_POST['categories']));
    }

    // Set tags
    if (!empty($_POST['tags'])) {
        $tags = array_map('trim', explode(',', $_POST['tags']));
        $product->set_tag_ids(array_map('intval', $tags));
    }

    // Set status
    if (!empty($_POST['status'])) {
        $product->set_status(sanitize_text_field($_POST['status']));
    }

    // Set visibility
    if (!empty($_POST['visibility'])) {
        $product->set_catalog_visibility(sanitize_text_field($_POST['visibility']));
    }

    // Set featured image
    if (!empty($_POST['image_id'])) {
        $product->set_image_id(intval($_POST['image_id']));
    }

    // Save the product
    $product_id = $product->save();

    if ($product_id) {
        wp_send_json_success(array(
            'message' => 'Product created successfully',
            'product_id' => $product_id
        ));
    } else {
        wp_send_json_error('Failed to create product');
    }
}
add_action('wp_ajax_woodash_add_item', 'woodash_add_item');

/**
 * Handle image upload
 */
function woodash_upload_image() {
    check_ajax_referer('woodash_nonce', 'nonce');

    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error('Insufficient permissions');
    }

    if (empty($_FILES['image'])) {
        wp_send_json_error('No image file provided');
    }

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $attachment_id = media_handle_upload('image', 0);

    if (is_wp_error($attachment_id)) {
        wp_send_json_error($attachment_id->get_error_message());
    }

    wp_send_json_success(array(
        'id' => $attachment_id,
        'url' => wp_get_attachment_url($attachment_id)
    ));
}
add_action('wp_ajax_woodash_upload_image', 'woodash_upload_image');
