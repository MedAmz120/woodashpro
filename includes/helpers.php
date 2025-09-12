<?php
/**
 * WooDash Pro Helper Functions
 * 
 * Collection of utility functions used throughout the plugin
 * 
 * @package WooDashPro
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Log debug messages
 * 
 * @param string $message The message to log
 * @param mixed $data Optional data to log
 */
function woodashpro_log($message, $data = null) {
    if (!WP_DEBUG) {
        return;
    }
    
    $log_file = WP_CONTENT_DIR . '/woodash-debug.log';
    $timestamp = current_time('mysql');
    $log_message = "[{$timestamp}] {$message}";
    
    if ($data !== null) {
        $log_message .= "\nData: " . print_r($data, true);
    }
    
    $log_message .= "\n" . str_repeat('-', 80) . "\n";
    
    error_log($log_message, 3, $log_file);
}

/**
 * Get plugin version
 * 
 * @return string Plugin version
 */
function woodashpro_get_version() {
    return WOODASHPRO_VERSION;
}

/**
 * Check if WooCommerce is active
 * 
 * @return bool True if WooCommerce is active
 */
function woodashpro_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Check if user has WooCommerce permissions
 * 
 * @return bool True if user can manage WooCommerce
 */
function woodashpro_can_manage_woocommerce() {
    return current_user_can('manage_woocommerce');
}

/**
 * Get formatted currency value
 * 
 * @param float $amount The amount to format
 * @return string Formatted currency value
 */
function woodashpro_format_currency($amount) {
    if (function_exists('wc_price')) {
        return wc_price($amount);
    }
    
    return '$' . number_format($amount, 2);
}

/**
 * Get date range for analytics
 * 
 * @param string $period The period (today, week, month, year)
 * @return array Array with start and end dates
 */
function woodashpro_get_date_range($period = 'today') {
    $end_date = current_time('Y-m-d');
    
    switch ($period) {
        case 'today':
            $start_date = $end_date;
            break;
        case 'week':
            $start_date = date('Y-m-d', strtotime('-7 days'));
            break;
        case 'month':
            $start_date = date('Y-m-d', strtotime('-30 days'));
            break;
        case 'year':
            $start_date = date('Y-m-d', strtotime('-365 days'));
            break;
        default:
            $start_date = $end_date;
    }
    
    return array(
        'start' => $start_date,
        'end' => $end_date
    );
}

/**
 * Get WooCommerce orders for date range
 * 
 * @param string $start_date Start date (Y-m-d format)
 * @param string $end_date End date (Y-m-d format)
 * @param array $status Order statuses to include
 * @return array Array of WC_Order objects
 */
function woodashpro_get_orders($start_date, $end_date, $status = array('completed')) {
    if (!woodashpro_is_woocommerce_active()) {
        return array();
    }
    
    $args = array(
        'status' => $status,
        'limit' => -1,
        'date_created' => array(
            'after' => $start_date . ' 00:00:00',
            'before' => $end_date . ' 23:59:59'
        )
    );
    
    return wc_get_orders($args);
}

/**
 * Calculate total sales for orders
 * 
 * @param array $orders Array of WC_Order objects
 * @return float Total sales amount
 */
function woodashpro_calculate_total_sales($orders) {
    $total = 0;
    
    foreach ($orders as $order) {
        $total += (float) $order->get_total();
    }
    
    return $total;
}

/**
 * Get order count
 * 
 * @param array $orders Array of WC_Order objects
 * @return int Order count
 */
function woodashpro_get_order_count($orders) {
    return count($orders);
}

/**
 * Calculate average order value
 * 
 * @param array $orders Array of WC_Order objects
 * @return float Average order value
 */
function woodashpro_calculate_aov($orders) {
    $total_sales = woodashpro_calculate_total_sales($orders);
    $order_count = woodashpro_get_order_count($orders);
    
    return $order_count > 0 ? round($total_sales / $order_count, 2) : 0;
}

/**
 * Get unique customers from orders
 * 
 * @param array $orders Array of WC_Order objects
 * @return array Array of unique customer IDs
 */
function woodashpro_get_unique_customers($orders) {
    $customers = array();
    
    foreach ($orders as $order) {
        $customer_id = $order->get_customer_id();
        if ($customer_id && !in_array($customer_id, $customers)) {
            $customers[] = $customer_id;
        }
    }
    
    return $customers;
}

/**
 * Get product sales data from orders
 * 
 * @param array $orders Array of WC_Order objects
 * @return array Product sales data
 */
function woodashpro_get_product_sales($orders) {
    $product_sales = array();
    
    foreach ($orders as $order) {
        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            
            if (!isset($product_sales[$product_id])) {
                $product_sales[$product_id] = array(
                    'quantity' => 0,
                    'revenue' => 0
                );
            }
            
            $product_sales[$product_id]['quantity'] += $item->get_quantity();
            $product_sales[$product_id]['revenue'] += $item->get_total();
        }
    }
    
    return $product_sales;
}

/**
 * Format number with appropriate suffix (K, M, B)
 * 
 * @param float $number The number to format
 * @param int $precision Decimal precision
 * @return string Formatted number
 */
function woodashpro_format_number($number, $precision = 1) {
    if ($number >= 1000000000) {
        return round($number / 1000000000, $precision) . 'B';
    } elseif ($number >= 1000000) {
        return round($number / 1000000, $precision) . 'M';
    } elseif ($number >= 1000) {
        return round($number / 1000, $precision) . 'K';
    } else {
        return number_format($number, $precision);
    }
}

/**
 * Sanitize and validate date string
 * 
 * @param string $date Date string
 * @return string|false Validated date string or false
 */
function woodashpro_validate_date($date) {
    $datetime = DateTime::createFromFormat('Y-m-d', $date);
    
    if ($datetime && $datetime->format('Y-m-d') === $date) {
        return $date;
    }
    
    return false;
}

/**
 * Get plugin settings
 * 
 * @param string $option_name Option name
 * @param mixed $default Default value
 * @return mixed Option value
 */
function woodashpro_get_setting($option_name, $default = false) {
    return get_option('woodashpro_' . $option_name, $default);
}

/**
 * Set plugin setting
 * 
 * @param string $option_name Option name
 * @param mixed $value Option value
 * @return bool True if successful
 */
function woodashpro_set_setting($option_name, $value) {
    return update_option('woodashpro_' . $option_name, $value);
}

/**
 * Check if plugin is connected to SaaS platform
 * 
 * @return bool True if connected
 */
function woodashpro_is_connected() {
    return (bool) get_option('woodash_connected', false);
}

/**
 * Get connection status
 * 
 * @return array Connection status information
 */
function woodashpro_get_connection_status() {
    return array(
        'connected' => woodashpro_is_connected(),
        'store_id' => get_option('woodash_store_id', ''),
        'api_key' => get_option('woodash_api_key', ''),
        'connected_at' => get_option('woodash_connected_at', '')
    );
}

/**
 * Generate nonce for AJAX requests
 * 
 * @param string $action Action name
 * @return string Nonce value
 */
function woodashpro_create_nonce($action = 'woodash_nonce') {
    return wp_create_nonce($action);
}

/**
 * Verify nonce for AJAX requests
 * 
 * @param string $nonce Nonce value
 * @param string $action Action name
 * @return bool True if valid
 */
function woodashpro_verify_nonce($nonce, $action = 'woodash_nonce') {
    return wp_verify_nonce($nonce, $action);
}

/**
 * Get localized script data
 * 
 * @return array Localized data array
 */
function woodashpro_get_localized_data() {
    return array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => woodashpro_create_nonce(),
        'restUrl' => rest_url('woodash/v1/'),
        'restNonce' => wp_create_nonce('wp_rest'),
        'today' => current_time('Y-m-d'),
        'version' => woodashpro_get_version(),
        'debug' => WP_DEBUG,
        'currency_symbol' => function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol() : '$',
        'is_connected' => woodashpro_is_connected(),
        'can_manage_woocommerce' => woodashpro_can_manage_woocommerce()
    );
}

/**
 * Load template file
 * 
 * @param string $template Template name
 * @param array $vars Variables to pass to template
 * @param bool $return Whether to return output or echo
 * @return string|void Template output if $return is true
 */
function woodashpro_load_template($template, $vars = array(), $return = false) {
    $template_file = WOODASHPRO_TEMPLATES_DIR . $template . '.php';
    
    if (!file_exists($template_file)) {
        if ($return) {
            return '';
        }
        return;
    }
    
    // Extract variables for template
    if (!empty($vars)) {
        extract($vars);
    }
    
    if ($return) {
        ob_start();
        include $template_file;
        return ob_get_clean();
    } else {
        include $template_file;
    }
}

/**
 * Get dashboard URL
 * 
 * @return string Dashboard URL
 */
function woodashpro_get_dashboard_url() {
    if (woodashpro_is_connected()) {
        return admin_url('admin.php?page=woodash-pro');
    } else {
        return admin_url('admin.php?page=woodash-pro-activate');
    }
}

/**
 * Check if current page is WooDash Pro page
 * 
 * @return bool True if on WooDash Pro page
 */
function woodashpro_is_dashboard_page() {
    return isset($_GET['page']) && in_array($_GET['page'], array('woodash-pro', 'woodash-pro-activate'));
}

/**
 * Get error message for display
 * 
 * @param string $error_code Error code
 * @return string Error message
 */
function woodashpro_get_error_message($error_code) {
    $messages = array(
        'woocommerce_missing' => __('WooCommerce is required for WooDash Pro to function.', 'woodashpro'),
        'permission_denied' => __('You do not have permission to access this feature.', 'woodashpro'),
        'connection_failed' => __('Failed to connect to WooDash Pro platform.', 'woodashpro'),
        'invalid_data' => __('Invalid data provided.', 'woodashpro'),
        'subscription_required' => __('An active subscription is required to use this feature.', 'woodashpro')
    );
    
    return isset($messages[$error_code]) ? $messages[$error_code] : __('An unknown error occurred.', 'woodashpro');
}

/**
 * Display admin notice
 * 
 * @param string $message Notice message
 * @param string $type Notice type (success, error, warning, info)
 */
function woodashpro_show_admin_notice($message, $type = 'info') {
    add_action('admin_notices', function() use ($message, $type) {
        echo '<div class="notice notice-' . esc_attr($type) . '"><p>' . esc_html($message) . '</p></div>';
    });
}
