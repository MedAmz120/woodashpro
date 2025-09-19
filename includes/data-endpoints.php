<?php
if (!defined('ABSPATH')) exit;

// Debug function
function woodash_debug_log($message) {
    if (function_exists('woodashpro_log')) {
        woodashpro_log($message);
    } else {
        $log_file = WP_CONTENT_DIR . '/woodash-debug.log';
        $timestamp = date('[Y-m-d H:i:s] ');
        file_put_contents($log_file, $timestamp . $message . "\n", FILE_APPEND);
    }
}

// Fetch WooCommerce analytics data
add_action('wp_ajax_woodash_get_data', function() {
    // Improved nonce checking - make it optional for backward compatibility
    if (isset($_REQUEST['nonce'])) {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'woodash_nonce')) {
            wp_send_json_error(__('Invalid nonce', 'woodashpro'), 403);
            return;
        }
    }
    
    // Validate request
    if (!function_exists('woodashpro_validate_ajax_request') || !woodashpro_validate_ajax_request()) {
        // Fallback validation
        if (!is_user_logged_in()) {
            wp_send_json_error(__('User not logged in', 'woodashpro'), 401);
            return;
        }
        
        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error(__('Insufficient permissions', 'woodashpro'), 403);
            return;
        }
        
        if (!class_exists('WooCommerce')) {
            wp_send_json_error(__('WooCommerce is not active', 'woodashpro'), 503);
            return;
        }
    }

    try {
        // Debug: Log all request parameters
        woodash_debug_log('Request Parameters: ' . print_r($_REQUEST, true));

        // Get date range from request (check both GET and POST)
        $date_from = isset($_REQUEST['date_from']) ? sanitize_text_field($_REQUEST['date_from']) : null;
        $date_to = isset($_REQUEST['date_to']) ? sanitize_text_field($_REQUEST['date_to']) : null;

        // Use today's date if no dates provided
        if (!$date_from || !$date_to) {
            $today = current_time('Y-m-d');
            $date_from = $date_from ?: $today;
            $date_to = $date_to ?: $today;
        }

        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_from) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_to)) {
            wp_send_json_error(__('Invalid date format', 'woodashpro'), 400);
            return;
        }

        // Debug: Log date parameters
        woodash_debug_log('Date From: ' . $date_from . ', Date To: ' . $date_to);

        $order_args = array(
            'status' => array('wc-completed', 'wc-processing'),
            'limit' => -1,
            'date_created' => $date_from . ' 00:00:00...' . $date_to . ' 23:59:59',
            'return' => 'objects'
        );

        // Debug: Log the complete order arguments
        woodash_debug_log('Order Args: ' . print_r($order_args, true));

        $orders = wc_get_orders($order_args);
        if (!is_array($orders)) {
            $orders = array();
        }

        // Debug: Log the number of orders found
        woodash_debug_log('Orders Found: ' . count($orders));

        // Calculate total sales (sum of order totals)
        $total_sales_amount = 0;
        $order_count = 0;
        
        foreach ($orders as $order) {
            if ($order && is_object($order) && method_exists($order, 'get_total')) {
                $total_sales_amount += (float) $order->get_total();
                $order_count++;
            }
        }
        
        $total_sales = round($total_sales_amount, 2);

        // Fetch average order value
        $aov = $order_count > 0 ? round($total_sales_amount / $order_count, 2) : 0;

        // Fetch sales over time
        $sales_over_time = array();
        $start = strtotime($date_from);
        $end = strtotime($date_to);
        
        // Debug: Log date range for sales over time
        woodash_debug_log('Sales Over Time Range: ' . date('Y-m-d', $start) . ' to ' . date('Y-m-d', $end));
        
        // Initialize all dates with 0
        for ($d = $start; $d <= $end; $d += 86400) {
            $date = date('Y-m-d', $d);
            $sales_over_time[$date] = 0;
        }

        // Debug: Log the sales over time structure
        woodash_debug_log('Sales Over Time Structure: ' . print_r($sales_over_time, true));

        // Populate actual sales data
        foreach ($orders as $order) {
            if ($order && is_object($order) && method_exists($order, 'get_date_created') && $order->get_date_created()) {
                $order_date = $order->get_date_created()->date('Y-m-d');
                if (isset($sales_over_time[$order_date])) {
                    $sales_over_time[$order_date] += (float) $order->get_total();
                }
            }
        }

        // Debug: Log final sales over time data
        woodash_debug_log('Final Sales Over Time: ' . print_r($sales_over_time, true));

        // Get additional metrics
        $processing_orders_args = array(
            'status' => 'wc-processing',
            'limit' => -1,
            'return' => 'ids'
        );
        
        $processing_orders = wc_get_orders($processing_orders_args);
        if (!is_array($processing_orders)) {
            $processing_orders = array();
        }

        $response_data = array(
            'total_sales' => $total_sales,
            'total_orders' => $order_count,
            'aov' => $aov,
            'processing_orders' => count($processing_orders),
            'sales_over_time' => $sales_over_time,
            'date_from' => $date_from,
            'date_to' => $date_to
        );

        wp_send_json_success($response_data);
        
    } catch (Exception $e) {
        woodash_debug_log('Error in woodash_get_data: ' . $e->getMessage());
        wp_send_json_error(array(
            'message' => __('Failed to fetch analytics data', 'woodashpro'),
            'error' => $e->getMessage()
        ), 500);
    }
});
