<?php
if (!defined('ABSPATH')) exit;

// Debug function
function woodash_debug_log($message) {
    $log_file = WP_CONTENT_DIR . '/woodash-debug.log';
    $timestamp = date('[Y-m-d H:i:s] ');
    file_put_contents($log_file, $timestamp . $message . "\n", FILE_APPEND);
}

// Fetch WooCommerce analytics data
add_action('wp_ajax_woodash_get_data', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) wp_send_json_error('Unauthorized', 403);

    // Debug: Log all request parameters
    woodash_debug_log('Request Parameters: ' . print_r($_GET, true));

    // Get date range from request
    $date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : null;
    $date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : null;

    // Debug: Log date parameters
    woodash_debug_log('Date From: ' . $date_from);
    woodash_debug_log('Date To: ' . $date_to);

    // Return error if no dates provided
    if (!$date_from || !$date_to) {
        wp_send_json_error('Date range is required');
        return;
    }

    $order_args = [
        'status' => 'completed',
        'limit' => -1,
        'date_created' => $date_from . ' 00:00:00...' . $date_to . ' 23:59:59'
    ];

    // Debug: Log the complete order arguments
    woodash_debug_log('Order Args: ' . print_r($order_args, true));

    $orders = wc_get_orders($order_args);

    // Debug: Log the number of orders found
    woodash_debug_log('Orders Found: ' . count($orders));

    // Calculate total sales (sum of order totals)
    $total_sales_amount = 0;
    foreach ($orders as $order) {
        $total_sales_amount += (float) $order->get_total();
    }
    $total_sales = round($total_sales_amount, 2);

    // Fetch average order value
    $aov = count($orders) > 0 ? round($total_sales_amount / count($orders), 2) : 0;

    // Fetch sales over time
    $sales_over_time = [];
    $start = strtotime($date_from);
    $end = strtotime($date_to);
    
    // Debug: Log date range for sales over time
    woodash_debug_log('Sales Over Time Range: ' . date('Y-m-d', $start) . ' to ' . date('Y-m-d', $end));
    
    for ($d = $start; $d <= $end; $d += 86400) {
        $date = date('Y-m-d', $d);
        $sales_over_time[$date] = 0;
    }

    // Debug: Log the sales over time structure
    woodash_debug_log('Sales Over Time Structure: ' . print_r($sales_over_time, true));

    foreach ($orders as $order) {
        $order_date = $order->get_date_created()->date('Y-m-d');
        if (isset($sales_over_time[$order_date])) {
            $sales_over_time[$order_date] += (float) $order->get_total();
        }
    }

    // Debug: Log final sales over time data
    woodash_debug_log('Final Sales Over Time: ' . print_r($sales_over_time, true));

    wp_send_json([
        'total_sales' => $total_sales,
        'aov' => $aov,
        'sales_over_time' => $sales_over_time,
    ]);
});
