<?php
/*
Plugin Name: WooDash Pro
Description: Modern WooCommerce analytics dashboard.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

// Add this after the plugin header and before any other code
function woodash_hide_admin_bar() {
    if (isset($_GET['page']) && $_GET['page'] === 'woodash-pro') {
        add_filter('show_admin_bar', '__return_false');
        add_action('admin_head', 'woodash_hide_admin_bar_style');
    }
}
add_action('init', 'woodash_hide_admin_bar');

function woodash_hide_admin_bar_style() {
    ?>
    <style>
        #wpadminbar {
            display: none !important;
        }
        html {
            margin-top: 0 !important;
        }
        #wpcontent {
            margin-left: 0 !important;
            padding-left: 0 !important;
        }
        #adminmenumain {
            display: none !important;
        }
        #wpfooter {
            display: none !important;
        }
        .woodash-fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            background: #F8FAFC;
        }
    </style>
    <?php
}

// Enqueue scripts and styles
add_action('admin_enqueue_scripts', function($hook) {
    if ($hook !== 'toplevel_page_woodash-pro') return;
    wp_enqueue_style('woodash-tailwind', plugins_url('assets/css/tailwind.min.css', __FILE__));
    wp_enqueue_script('woodash-chart', plugins_url('assets/js/chart.min.js', __FILE__), [], null, true);
    wp_enqueue_script('woodash-dashboard', plugins_url('templates/dashboard.js', __FILE__), ['jquery'], null, true);
    wp_localize_script('woodash-dashboard', 'woodashData', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('woodash_nonce')
    ]);
});

// Add menu
add_action('admin_menu', function() {
    add_menu_page(
        'WooDash Pro', 'WooDash Pro', 'manage_woocommerce',
        'woodash-pro', 'woodash_dashboard_page', 'dashicons-chart-area', 56
    );
});

// Dashboard page
function woodash_dashboard_page() {
    include plugin_dir_path(__FILE__) . 'templates/dashboard.php';
}

// AJAX endpoints
require_once plugin_dir_path(__FILE__) . 'includes/data-endpoints.php';

add_action('wp_ajax_woodash_get_data', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) wp_send_json_error('Unauthorized', 403);

    $date_from = isset($_POST['date_from']) ? sanitize_text_field($_POST['date_from']) : '';
    $date_to = isset($_POST['date_to']) ? sanitize_text_field($_POST['date_to']) : '';
    $granularity = isset($_POST['granularity']) ? sanitize_text_field($_POST['granularity']) : 'daily';

    $args = [
        'status' => ['wc-completed'],
        'limit' => -1,
        'return' => 'ids',
    ];
    if ($date_from) $args['date_created']['after'] = $date_from . ' 00:00:00';
    if ($date_to) $args['date_created']['before'] = $date_to . ' 23:59:59';

    $order_ids = wc_get_orders($args);
    $total_sales = 0;
    $orders = [];
    foreach ($order_ids as $order_id) {
        $order = wc_get_order($order_id);
        $total_sales += (float) $order->get_total();
        $orders[] = $order;
    }
    $total_orders = count($orders);
    $aov = $total_orders > 0 ? round($total_sales / $total_orders, 2) : 0;

    // Sales overview aggregation
    $sales_overview = ['labels' => [], 'data' => []];
    $grouped = [];
    foreach ($orders as $order) {
        $date = $order->get_date_created();
        if (!$date) continue;
        if ($granularity === 'daily') {
            $key = $date->date_i18n('Y-m-d');
        } elseif ($granularity === 'weekly') {
            $key = $date->date_i18n('o-\WW');
        } else {
            $key = $date->date_i18n('Y-m');
        }
        if (!isset($grouped[$key])) $grouped[$key] = 0;
        $grouped[$key] += (float) $order->get_total();
    }
    ksort($grouped);
    $sales_overview['labels'] = array_keys($grouped);
    $sales_overview['data'] = array_values($grouped);

    // Top products
    $product_sales = [];
    foreach ($orders as $order) {
        foreach ($order->get_items() as $item) {
            $pid = $item->get_product_id();
            if (!isset($product_sales[$pid])) $product_sales[$pid] = 0;
            $product_sales[$pid] += $item->get_quantity();
        }
    }
    arsort($product_sales);
    $top_products = [];
    foreach (array_slice($product_sales, 0, 5, true) as $pid => $qty) {
        $product = wc_get_product($pid);
        $top_products[] = [
            'name' => $product ? $product->get_name() : __('Unknown'),
            'sales' => $qty
        ];
    }

    // Top customers
    $customer_orders = [];
    foreach ($orders as $order) {
        $cid = $order->get_customer_id();
        if (!$cid) continue;
        if (!isset($customer_orders[$cid])) $customer_orders[$cid] = 0;
        $customer_orders[$cid]++;
    }
    arsort($customer_orders);
    $top_customers = [];
    foreach (array_slice($customer_orders, 0, 5, true) as $cid => $count) {
        $user = get_userdata($cid);
        $top_customers[] = [
            'name' => $user ? $user->display_name : __('Guest'),
            'orders' => $count
        ];
    }

    wp_send_json([
        'total_sales' => wc_format_decimal($total_sales, 2),
        'total_orders' => $total_orders,
        'aov' => $aov,
        'top_products' => $top_products,
        'top_customers' => $top_customers,
        'sales_overview' => $sales_overview
    ]);
});

// Export Top Products CSV
add_action('wp_ajax_woodash_export_products_csv', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) wp_die('Unauthorized', 403);

    $date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : '';
    $date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : '';
    $granularity = isset($_GET['granularity']) ? sanitize_text_field($_GET['granularity']) : 'daily';

    $args = [
        'status' => ['wc-completed'],
        'limit' => -1,
        'return' => 'ids',
    ];
    if ($date_from) $args['date_created']['after'] = $date_from . ' 00:00:00';
    if ($date_to) $args['date_created']['before'] = $date_to . ' 23:59:59';
    $order_ids = wc_get_orders($args);
    $orders = [];
    foreach ($order_ids as $order_id) {
        $orders[] = wc_get_order($order_id);
    }
    $product_sales = [];
    foreach ($orders as $order) {
        foreach ($order->get_items() as $item) {
            $pid = $item->get_product_id();
            if (!isset($product_sales[$pid])) $product_sales[$pid] = 0;
            $product_sales[$pid] += $item->get_quantity();
        }
    }
    arsort($product_sales);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="woodash-top-products.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Product', 'Sales']);
    foreach (array_slice($product_sales, 0, 20, true) as $pid => $qty) {
        $product = wc_get_product($pid);
        fputcsv($output, [$product ? $product->get_name() : __('Unknown'), $qty]);
    }
    fclose($output);
    exit;
});

// Export Top Customers CSV
add_action('wp_ajax_woodash_export_customers_csv', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) wp_die('Unauthorized', 403);

    $date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : '';
    $date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : '';
    $granularity = isset($_GET['granularity']) ? sanitize_text_field($_GET['granularity']) : 'daily';

    $args = [
        'status' => ['wc-completed'],
        'limit' => -1,
        'return' => 'ids',
    ];
    if ($date_from) $args['date_created']['after'] = $date_from . ' 00:00:00';
    if ($date_to) $args['date_created']['before'] = $date_to . ' 23:59:59';
    $order_ids = wc_get_orders($args);
    $orders = [];
    foreach ($order_ids as $order_id) {
        $orders[] = wc_get_order($order_id);
    }
    $customer_orders = [];
    foreach ($orders as $order) {
        $cid = $order->get_customer_id();
        if (!$cid) continue;
        if (!isset($customer_orders[$cid])) $customer_orders[$cid] = 0;
        $customer_orders[$cid]++;
    }
    arsort($customer_orders);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="woodash-top-customers.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Customer', 'Orders']);
    foreach (array_slice($customer_orders, 0, 20, true) as $cid => $count) {
        $user = get_userdata($cid);
        fputcsv($output, [$user ? $user->display_name : __('Guest'), $count]);
    }
    fclose($output);
    exit;
});

// Add custom CSS for the WooDash Pro menu item
add_action('admin_head', function() {
    echo '
    <style>
        #toplevel_page_woodash-pro {
            background: #00cc61 !important;
        }
        #toplevel_page_woodash-pro .wp-menu-name,
        #toplevel_page_woodash-pro .wp-menu-image:before {
            color: #fff !important;
        }
        #toplevel_page_woodash-pro:hover,
        #toplevel_page_woodash-pro.wp-has-current-submenu {
            background: #00994a !important; /* Slightly darker on hover/active */
        }
    </style>
    ';
});
