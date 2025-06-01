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
