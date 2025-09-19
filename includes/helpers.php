<?php
/**
 * WooDash Pro Helper Functions
 * 
 * @package WooDashPro
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Log function for debugging
 */
if (!function_exists('woodashpro_log')) {
    function woodashpro_log($message, $data = null) {
        if (!WP_DEBUG) {
            return;
        }
        
        $log_entry = '[' . date('Y-m-d H:i:s') . '] WooDash Pro: ' . $message;
        
        if ($data !== null) {
            $log_entry .= ' | Data: ' . print_r($data, true);
        }
        
        error_log($log_entry);
        
        // Also write to custom log file if possible
        $log_file = WP_CONTENT_DIR . '/woodash-debug.log';
        if (is_writable(WP_CONTENT_DIR)) {
            file_put_contents($log_file, $log_entry . "\n", FILE_APPEND | LOCK_EX);
        }
    }
}

/**
 * Get WooCommerce order statuses
 */
if (!function_exists('woodashpro_get_order_statuses')) {
    function woodashpro_get_order_statuses() {
        if (!function_exists('wc_get_order_statuses')) {
            return array(
                'wc-pending' => 'Pending',
                'wc-processing' => 'Processing',
                'wc-on-hold' => 'On Hold',
                'wc-completed' => 'Completed',
                'wc-cancelled' => 'Cancelled',
                'wc-refunded' => 'Refunded',
                'wc-failed' => 'Failed'
            );
        }
        
        return wc_get_order_statuses();
    }
}

/**
 * Format currency for display
 */
if (!function_exists('woodashpro_format_currency')) {
    function woodashpro_format_currency($amount) {
        if (function_exists('wc_price')) {
            return strip_tags(wc_price($amount));
        }
        
        return '$' . number_format((float)$amount, 2);
    }
}

/**
 * Get current date in WordPress timezone
 */
if (!function_exists('woodashpro_current_date')) {
    function woodashpro_current_date($format = 'Y-m-d') {
        return current_time($format);
    }
}

/**
 * Sanitize date input
 */
if (!function_exists('woodashpro_sanitize_date')) {
    function woodashpro_sanitize_date($date) {
        $sanitized = sanitize_text_field($date);
        
        // Validate date format
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $sanitized)) {
            return $sanitized;
        }
        
        return woodashpro_current_date();
    }
}

/**
 * Check if WooCommerce is active and loaded
 */
if (!function_exists('woodashpro_is_woocommerce_active')) {
    function woodashpro_is_woocommerce_active() {
        return class_exists('WooCommerce') && function_exists('wc_get_orders');
    }
}

/**
 * Get safe order data
 */
if (!function_exists('woodashpro_get_safe_order_data')) {
    function woodashpro_get_safe_order_data($order_id) {
        if (!woodashpro_is_woocommerce_active()) {
            return null;
        }
        
        $order = wc_get_order($order_id);
        if (!$order) {
            return null;
        }
        
        return array(
            'id' => $order->get_id(),
            'total' => (float) $order->get_total(),
            'status' => $order->get_status(),
            'date_created' => $order->get_date_created() ? $order->get_date_created()->date('Y-m-d H:i:s') : '',
            'customer_id' => $order->get_customer_id(),
            'billing_email' => $order->get_billing_email(),
            'billing_first_name' => $order->get_billing_first_name(),
            'billing_last_name' => $order->get_billing_last_name()
        );
    }
}

/**
 * Get orders with error handling
 */
if (!function_exists('woodashpro_get_orders_safe')) {
    function woodashpro_get_orders_safe($args = array()) {
        if (!woodashpro_is_woocommerce_active()) {
            return array();
        }
        
        try {
            $orders = wc_get_orders($args);
            return is_array($orders) ? $orders : array();
        } catch (Exception $e) {
            woodashpro_log('Error getting orders: ' . $e->getMessage(), $args);
            return array();
        }
    }
}

/**
 * Generate mock data for testing
 */
if (!function_exists('woodashpro_generate_mock_data')) {
    function woodashpro_generate_mock_data($type = 'orders') {
        switch ($type) {
            case 'orders':
                return array(
                    'total_orders' => 156,
                    'total_sales' => 72450.00,
                    'avg_order_value' => 464.42,
                    'processing_orders' => 12
                );
                
            case 'customers':
                return array(
                    'total_customers' => 89,
                    'new_customers' => 42,
                    'returning_customers' => 47
                );
                
            case 'products':
                return array(
                    array('name' => 'Wireless Headphones Pro', 'sales' => 234, 'revenue' => 23400),
                    array('name' => 'Smart Watch Series X', 'sales' => 189, 'revenue' => 18900),
                    array('name' => 'Gaming Laptop Ultra', 'sales' => 156, 'revenue' => 156000),
                    array('name' => 'Bluetooth Speaker', 'sales' => 134, 'revenue' => 6700),
                    array('name' => 'Phone Case Premium', 'sales' => 98, 'revenue' => 2940)
                );
                
            default:
                return array();
        }
    }
}

/**
 * Handle AJAX errors gracefully
 */
if (!function_exists('woodashpro_handle_ajax_error')) {
    function woodashpro_handle_ajax_error($error, $code = 500) {
        woodashpro_log('AJAX Error: ' . $error);
        
        wp_send_json_error(array(
            'message' => $error,
            'code' => $code,
            'timestamp' => current_time('mysql')
        ), $code);
    }
}

/**
 * Validate and sanitize AJAX request
 */
if (!function_exists('woodashpro_validate_ajax_request')) {
    function woodashpro_validate_ajax_request($required_capability = 'manage_woocommerce') {
        // Check if user is logged in
        if (!is_user_logged_in()) {
            woodashpro_handle_ajax_error(__('User not logged in', 'woodashpro'), 401);
            return false;
        }
        
        // Check user capability
        if (!current_user_can($required_capability)) {
            woodashpro_handle_ajax_error(__('Insufficient permissions', 'woodashpro'), 403);
            return false;
        }
        
        // Check if WooCommerce is active
        if (!woodashpro_is_woocommerce_active()) {
            woodashpro_handle_ajax_error(__('WooCommerce is not active', 'woodashpro'), 503);
            return false;
        }
        
        return true;
    }
}

/**
 * Get plugin version
 */
if (!function_exists('woodashpro_get_version')) {
    function woodashpro_get_version() {
        return defined('WOODASHPRO_VERSION') ? WOODASHPRO_VERSION : '1.0.0';
    }
}

/**
 * Check if debug mode is enabled
 */
if (!function_exists('woodashpro_is_debug')) {
    function woodashpro_is_debug() {
        return defined('WP_DEBUG') && WP_DEBUG;
    }
}
