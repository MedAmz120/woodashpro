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
    if (isset($_GET['page']) && ($_GET['page'] === 'woodashh-dashboard' || $_GET['page'] === 'woodashh-products' || $_GET['page'] === 'woodashh-customers' || $_GET['page'] === 'woodashh-stock')) {
        add_filter('show_admin_bar', '__return_false');
        add_action('admin_head', 'woodash_hide_admin_bar_style');
    }
}
add_action('init', 'woodash_hide_admin_bar');

// Add this function to wrap the initial content
function woodash_wrap_content($content) {
    if (isset($_GET['page']) && strpos($_GET['page'], 'woodashh-') === 0) {
        return '<div id="woodash-main-content">' . $content . '</div>';
    }
    return $content;
}
add_filter('the_content', 'woodash_wrap_content');

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
    // Load assets for both dashboard and products pages
    if (strpos($hook, 'woodashh-') !== false) {
        // Enqueue jQuery first
        wp_enqueue_script('jquery');
        
        // Enqueue styles
        wp_enqueue_style('woodash-tailwind', plugins_url('assets/css/tailwind.min.css', __FILE__));
        wp_enqueue_style('woodash-transitions', plugins_url('assets/css/page-transitions.css', __FILE__));
        wp_enqueue_style('woodash-dashboard', plugins_url('assets/css/dashboard.css', __FILE__));
        wp_enqueue_style('woodash-styles', plugins_url('assets/css/woodash-styles.css', __FILE__));
        
        // Enqueue scripts
        wp_enqueue_script('woodash-chart', plugins_url('assets/js/chart.min.js', __FILE__), ['jquery'], null, true);
        wp_enqueue_script('woodash-transitions', plugins_url('assets/js/page-transitions.js', __FILE__), ['jquery'], null, true);
        wp_enqueue_script('woodash-dashboard', plugins_url('assets/js/dashboard.js', __FILE__), ['jquery', 'woodash-chart'], null, true);
        wp_enqueue_script('woodash-coupons', plugins_url('assets/js/coupons.js', __FILE__), ['jquery'], null, true);
        
        // Enqueue Lottie Player only once
        if (!wp_script_is('lottie-player', 'enqueued')) {
            wp_enqueue_script('lottie-player', 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js', [], null, true);
        }
        
        // Localize script with necessary data
        wp_localize_script('woodash-transitions', 'woodashData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('woodash_nonce')
        ]);
    }
});

// AJAX endpoints
require_once plugin_dir_path(__FILE__) . 'includes/data-endpoints.php';

// AJAX handler for loading products content
add_action('wp_ajax_woodash_load_products_content', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error('Unauthorized', 403);
    }

    ob_start(); // Start output buffering
    include plugin_dir_path(__FILE__) . 'templates/products-panel-content.php';
    $content = ob_get_clean(); // Get buffered content

    wp_send_json_success($content);
});

// AJAX handler for loading page content
add_action('wp_ajax_woodash_load_page_content', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error('Unauthorized', 403);
    }

    $page = sanitize_text_field($_POST['page']);
    $template_file = '';

    // Debug log
    error_log('Loading page: ' . $page);

    // Map page parameter to template file
    switch ($page) {
        case 'woodashh-dashboard':
            $template_file = 'dashboard.php';
            break;
        case 'woodashh-products':
            $template_file = 'products.php';
            break;
        case 'woodashh-customers':
            $template_file = 'customers.php';
            break;
        case 'woodashh-stock':
            $template_file = 'stock.php';
            break;
        case 'woodashh-coupons':
            $template_file = 'coupons.php';
            break;
        case 'woodashh-reviews':
            $template_file = 'reviews.php';
            break;
        case 'woodashh-settings':
            $template_file = 'settings.php';
            break;
        default:
            error_log('Invalid page requested: ' . $page);
            wp_send_json_error('Invalid page', 400);
            return;
    }

    $template_path = plugin_dir_path(__FILE__) . 'templates/' . $template_file;
    if (!file_exists($template_path)) {
        error_log('Template file not found: ' . $template_path);
        wp_send_json_error('Template file not found', 404);
        return;
    }

    ob_start();
    // Extract only the main content area from the template
    include $template_path;
    $content = ob_get_clean();

    // Extract the main content area
    if (preg_match('/<main class="woodash-main[^>]*>(.*?)<\/main>/s', $content, $matches)) {
        $content = $matches[1];
    }

    if (empty($content)) {
        error_log('Empty content generated for page: ' . $page);
        wp_send_json_error('Empty content generated', 500);
        return;
    }

    wp_send_json_success($content);
});
