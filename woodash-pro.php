<?php
/*
Plugin Name: WooDash Pro
Description: Modern WooCommerce analytics dashboard.
Version: v0.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

// Enable debugging
if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', true);
}
if (!defined('WP_DEBUG_LOG')) {
    define('WP_DEBUG_LOG', true);
}
if (!defined('WP_DEBUG_DISPLAY')) {
    define('WP_DEBUG_DISPLAY', false);
}
@ini_set('display_errors', 0);

// Hide admin bar and menu when viewing plugin page
function woodash_hide_admin_bar() {
    if (isset($_GET['page']) && $_GET['page'] === 'woodash-pro') {
        add_filter('show_admin_bar', '__return_false');
        add_action('admin_head', 'woodash_hide_admin_bar_style');
    }
}
add_action('init', 'woodash_hide_admin_bar');

function woodash_hide_admin_bar_style() { ?>
    <style>
        #wpadminbar { display: none !important; }
        html { margin-top: 0 !important; }
        #wpcontent { margin-left: 0 !important; padding-left: 0 !important; }
        #adminmenumain { display: none !important; }
        #wpfooter { display: none !important; }
        .woodash-fullscreen {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            z-index: 9999; background: #F8FAFC;
        }
    </style>
<?php }

// Enqueue scripts and styles
add_action('admin_enqueue_scripts', function($hook) {
    if ($hook !== 'toplevel_page_woodash-pro') return;
    wp_enqueue_style('woodash-tailwind', plugins_url('assets/css/tailwind.min.css', __FILE__));
    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', [], null, true);
    // Ensure wpApiSettings (root, nonce) are available in admin
    wp_enqueue_script('wp-api');
    wp_enqueue_script('woodash-dashboard', plugins_url('templates/dashboard.js', __FILE__), ['jquery','chartjs','wp-api'], null, true);
    wp_localize_script('woodash-dashboard', 'woodashData', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('woodash_nonce'),
        'today'   => current_time('Y-m-d'),
        'debug'   => true
    ]);
});

// Option: store activation status
function woodashh_is_activated() {
    return get_option('woodashh_activated', false);
}

// Admin menu
add_action('admin_menu', function() {
    $is_connected = get_option('woodash_connected');
    if ($is_connected) {
        add_menu_page('WooDash Pro', 'WooDash Pro', 'manage_options', 'woodash-pro', 'woodash_pro_dashboard_page', 'dashicons-chart-area', 56);
    } else {
        add_menu_page('WooDash Pro', 'WooDash Pro', 'manage_options', 'woodash-pro-activate', 'woodashh_activation_page', 'dashicons-chart-area', 56);
    }
});

// API credentials option
function woodashh_get_api_credentials() {
    return get_option('woodashh_api_credentials', [
        'consumer_key' => '',
        'consumer_secret' => '',
        'site_url' => ''
    ]);
}

// Test platform connection
function woodashh_test_api_connection($credentials) {
    $endpoint = 'https://saas.mohamedamzil.pw/wp-json/woodash/v1/verify';
    $site_url = str_replace('http://', 'https://', $credentials['site_url']);

    $args = [
        'method' => 'POST',
        'headers' => [ 'Content-Type' => 'application/json' ],
        'body' => json_encode([
            'site_url' => $site_url,
            'api_key' => $credentials['api_key'],
            'user_id' => $credentials['user_id']
        ]),
        'sslverify' => true,
        'timeout' => 30,
        'redirection' => 5,
        'httpversion' => '1.1',
        'blocking' => true,
        'data_format' => 'body'
    ];

    error_log('WooDash Pro: Attempting to connect to platform with credentials: ' . print_r([
        'site_url' => $site_url,
        'user_id' => $credentials['user_id']
    ], true));

    $response = wp_remote_post($endpoint, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        error_log('WooDash Pro API Error: ' . $error_message);
        if (strpos($error_message, 'SSL') !== false) {
            return new WP_Error('ssl_error', 'SSL connection failed. Please ensure your site has a valid SSL certificate.');
        } elseif (strpos($error_message, 'Could not resolve host') !== false) {
            return new WP_Error('dns_error', 'Could not connect to the platform. Please check your internet connection.');
        } else {
            return new WP_Error('connection_error', 'Connection failed: ' . $error_message);
        }
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    error_log('WooDash Pro API Response Code: ' . $response_code);
    error_log('WooDash Pro API Response Body: ' . $response_body);

    if ($response_code === 200) {
        $body = json_decode($response_body, true);
        if (isset($body['success']) && $body['success'] === true) {
            return true;
        } else {
            $message = isset($body['message']) ? $body['message'] : 'Unknown error';
            return new WP_Error('platform_error', $message);
        }
    }

    error_log('WooDash Pro: Connection failed with response code: ' . $response_code);
    return new WP_Error('http_error', 'Connection failed with status code: ' . $response_code);
}

// AJAX: Generate API keys
add_action('wp_ajax_woodashh_generate_keys', function() {
    check_ajax_referer('woodashh_generate_keys', 'nonce');
    try {
        $user = wp_get_current_user();
        $app_name = 'WooDash Pro - ' . get_bloginfo('name');
        error_log('WooDash Pro: Creating application password for user ' . $user->ID . ' with name: ' . $app_name);
        $new_password = WP_Application_Passwords::create_new_application_password($user->ID, [ 'name' => $app_name ]);
        error_log('WooDash Pro: Application password response: ' . print_r($new_password, true));
        if (is_wp_error($new_password)) throw new Exception($new_password->get_error_message());
        if (!is_array($new_password) || empty($new_password)) throw new Exception('Failed to generate application password - invalid response format');
        $api_key = $new_password[0] ?? null;
        if (!$api_key) throw new Exception('Failed to get application password - no password in response');
        error_log('WooDash Pro: Successfully generated API key');
        update_option('woodashh_api_credentials', [
            'api_key' => $api_key,
            'site_url' => get_site_url(),
            'user_id' => $user->ID
        ]);
        $credentials = [
            'api_key' => $api_key,
            'site_url' => get_site_url(),
            'user_id' => $user->ID
        ];
        $connection_result = woodashh_test_api_connection($credentials);
        if (is_wp_error($connection_result)) {
            wp_send_json_error(['message' => $connection_result->get_error_message()]);
        } else {
            wp_send_json_success(['api_key' => $api_key, 'site_url' => get_site_url() ]);
        }
    } catch (Exception $e) {
        error_log('WooDash Pro Error: ' . $e->getMessage());
        wp_send_json_error(['message' => $e->getMessage()]);
    }
});

// Activation page callback
function woodashh_activation_page() { ?>
    <div style="display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #f8f9fa;">
        <div style="max-width: 480px; background: #fff; border-radius: 16px; box-shadow: 0 4px 32px rgba(0,0,0,0.07); padding: 40px 32px; text-align: center; font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', sans-serif;">
            <div style="margin-bottom: 24px;">
                <img src="https://saas.mohamedamzil.pw/wp-content/uploads/2023/09/WooDash-600-x-180-px-transp.png" alt="WooDash Logo" style="width: 180px; height: auto; margin-bottom: 16px;">
                <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 8px; background: linear-gradient(90deg, #00CC61, #00b357); -webkit-background-clip: text; -webkit-text-fill-color: transparent; padding: 10px 0;">Connect WooDash Pro</h2>
                <p style="color: #555; font-size: 1.1rem; margin-bottom: 0;">To use WooDash Pro, please connect your site to your account.<br>Click the button below to get started.</p>
            </div>
            <a href="#" id="woodashh-connect-btn" style="display: inline-block; background: #19b677; color: #fff; font-weight: 600; font-size: 1.1rem; padding: 14px 36px; border-radius: 5px; box-shadow: 0 2px 8px rgba(25,182,119,0.08); text-decoration: none; transition: background 0.2s, box-shadow 0.2s; margin-top: 16px;">
                <span class="dashicons dashicons-admin-links" style="vertical-align: middle; margin-right: 8px;"></span> Connect to SaaS
            </a>
            <script>
            function woodashDebug(message, data) {
                console.log('WooDash Debug:', message, data);
                jQuery.ajax({ url: ajaxurl, type: 'POST', data: { action: 'woodashh_log', nonce: '<?php echo wp_create_nonce("woodashh_log"); ?>', message: message, data: JSON.stringify(data) } });
            }
            document.getElementById('woodashh-connect-btn').addEventListener('click', function(e) {
                e.preventDefault();
                woodashDebug('Connect button clicked');
                this.innerHTML = '<span class="dashicons dashicons-update" style="animation: spin 1s linear infinite;"></span> Connecting...';
                this.style.pointerEvents = 'none';
                var siteUrl = '<?php echo get_site_url(); ?>';
                var platformUrl = 'https://saas.mohamedamzil.pw/connect-woodash/?site_url=' + encodeURIComponent(siteUrl);
                var popup = window.open(platformUrl, 'woodash_connect', 'width=800,height=600');
                window.addEventListener('message', function(event) {
                    woodashDebug('Received message', event.data);
                    if (event.origin !== 'https://saas.mohamedamzil.pw') { woodashDebug('Invalid origin', event.origin); return; }
                    if (event.data.type === 'woodash_connect_success') {
                        woodashDebug('Connection successful', event.data);
                        jQuery.post(ajaxurl, { action: 'woodash_store_connection', store_id: event.data.store_id, api_key: event.data.api_key, nonce: '<?php echo wp_create_nonce('woodash_connect'); ?>' }, function(response) {
                            woodashDebug('Connection stored', response);
                            if (response.success) { if (window.opener) { window.close(); } window.location.href = response.data.redirect_url; }
                            else { woodashDebug('Failed to store connection', response); alert('Failed to complete connection: ' + response.data); resetButton(); }
                        }).fail(function(xhr, status, error) { woodashDebug('Connection request failed', { status, error }); alert('Failed to complete connection. Please try again.'); resetButton(); });
                    } else if (event.data.type === 'woodash_connect_error') { alert(event.data.message || 'Connection failed. Please try again.'); resetButton(); }
                });
            });
            function resetButton() { var btn = document.getElementById('woodashh-connect-btn'); btn.innerHTML = '<span class="dashicons dashicons-admin-links" style="vertical-align: middle; margin-right: 8px;"></span> Connect to SaaS'; btn.style.pointerEvents = 'auto'; }
            </script>
            <style>@keyframes spin { 0% { transform: rotate(0deg);} 100% { transform: rotate(360deg);} }</style>
        </div>
    </div>
<?php }

// AJAX: debug logger
add_action('wp_ajax_woodashh_log', function() {
    check_ajax_referer('woodashh_log', 'nonce');
    $message = sanitize_text_field($_POST['message']);
    $data = json_decode(stripslashes($_POST['data']), true);
    woodash_log($message, $data);
    wp_send_json_success();
});

// AJAX: store connection
add_action('wp_ajax_woodash_store_connection', function() {
    check_ajax_referer('woodash_connect', 'nonce');
    $store_id = sanitize_text_field($_POST['store_id']);
    $api_key = sanitize_text_field($_POST['api_key']);
    update_option('woodash_connected', true);
    update_option('woodash_store_id', $store_id);
    update_option('woodash_api_key', $api_key);
    wp_send_json_success([ 'redirect_url' => admin_url('admin.php?page=woodash-pro') ]);
});

// AJAX: logout
add_action('wp_ajax_woodash_logout', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    delete_option('woodash_connected');
    delete_option('woodash_store_id');
    delete_option('woodash_api_key');
    wp_send_json_success([ 'redirect_url' => admin_url('admin.php?page=woodash-pro-activate') ]);
});

// AJAX: check subscription (admin-ajax)
add_action('wp_ajax_woodash_check_subscription', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    $user_id = get_current_user_id();
    $has_subscription = false;
    if (class_exists('WC_Subscriptions')) {
        $subscriptions = wcs_get_users_subscriptions($user_id);
        foreach ($subscriptions as $subscription) {
            if ($subscription->get_status() === 'active') { $has_subscription = true; break; }
        }
    }
    if (!$has_subscription) {
        wp_send_json_error([ 'message' => 'No active subscription found', 'redirect_url' => 'https://saas.mohamedamzil.pw/pricing/' ]);
    }
    wp_send_json_success();
});

// Dashboard page
function woodash_pro_dashboard_page() {
    wp_enqueue_style('woodash-tailwind', plugins_url('assets/css/tailwind.min.css', __FILE__));
    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', [], null, true);
    wp_enqueue_script('woodash-dashboard', plugins_url('templates/dashboard.js', __FILE__), ['jquery','chartjs','wp-api'], null, true);
    wp_localize_script('woodash-dashboard', 'woodashData', [ 'ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('woodash_nonce') ]);
    include plugin_dir_path(__FILE__) . 'templates/dashboard.php';
}

// Additional data endpoints include (if exists)
$include_file = plugin_dir_path(__FILE__) . 'includes/data-endpoints.php';
if (file_exists($include_file)) { require_once $include_file; }

// AJAX: analytics data (enhanced logging)
add_action('wp_ajax_woodash_get_data', function() {
    // Debug capture pre-check
    $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'UNKNOWN';
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    $raw_input = file_get_contents('php://input');
    $parsed_body = [];
    if (!empty($raw_input)) { parse_str($raw_input, $parsed_body); }
    $in = array_merge($_GET, $_POST, $parsed_body);
    woodash_log('AJAX woodash_get_data: request received (pre-check)', [
        'method' => $method,
        'headers' => $headers,
        'raw_post' => $_POST,
        'raw_get' => $_GET,
        'php_input' => $raw_input,
        'parsed_body' => $parsed_body,
        'merged' => $in,
        'server_time' => current_time('mysql')
    ]);

    // Ensure nonce present for check
    if (!isset($_REQUEST['nonce']) && isset($parsed_body['nonce'])) {
        $_REQUEST['nonce'] = sanitize_text_field($parsed_body['nonce']);
    }

    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) wp_send_json_error('Unauthorized', 403);

    // Read inputs
    $date_from = isset($in['date_from']) ? sanitize_text_field($in['date_from']) : '';
    $date_to = isset($in['date_to']) ? sanitize_text_field($in['date_to']) : '';
    $granularity = isset($in['granularity']) ? sanitize_text_field($in['granularity']) : 'daily';

    if (empty($date_from) && empty($date_to)) {
        $today = current_time('Y-m-d');
        $date_from = $today;
        $date_to = $today;
    }

    woodash_log('AJAX woodash_get_data: computed dates', [ 'date_from' => $date_from, 'date_to' => $date_to, 'granularity' => $granularity ]);

    $args = [ 'status' => ['wc-completed'], 'limit' => -1, 'return' => 'ids' ];
    if ($date_from) $args['date_created']['after'] = $date_from . ' 00:00:00';
    if ($date_to) $args['date_created']['before'] = $date_to . ' 23:59:59';

    woodash_log('AJAX woodash_get_data: wc_get_orders args', $args);

    $order_ids = wc_get_orders($args);
    woodash_log('AJAX woodash_get_data: orders fetched', [ 'count' => is_array($order_ids) ? count($order_ids) : 0, 'ids_sample' => array_slice((array)$order_ids, 0, 10) ]);

    $total_sales = 0; $orders = [];
    foreach ($order_ids as $order_id) { $order = wc_get_order($order_id); $total_sales += (float) $order->get_total(); $orders[] = $order; }
    $total_orders = count($orders);
    $aov = $total_orders > 0 ? round($total_sales / $total_orders, 2) : 0;

    $sales_overview = ['labels' => [], 'data' => []];
    $grouped = [];
    foreach ($orders as $order) {
        $date = $order->get_date_created(); if (!$date) continue;
        if ($granularity === 'daily') { $key = $date->date_i18n('Y-m-d'); }
        elseif ($granularity === 'weekly') { $key = $date->date_i18n('o-\WW'); }
        else { $key = $date->date_i18n('Y-m'); }
        if (!isset($grouped[$key])) $grouped[$key] = 0;
        $grouped[$key] += (float) $order->get_total();
    }
    ksort($grouped);
    $sales_overview['labels'] = array_keys($grouped);
    $sales_overview['data'] = array_values($grouped);

    woodash_log('AJAX woodash_get_data: aggregates', [ 'total_sales' => $total_sales, 'total_orders' => $total_orders, 'aov' => $aov, 'sales_overview' => $sales_overview ]);

    // Top products
    $product_sales = [];
    foreach ($orders as $order) {
        foreach ($order->get_items() as $item) {
            $pid = $item->get_product_id(); if (!isset($product_sales[$pid])) $product_sales[$pid] = 0; $product_sales[$pid] += $item->get_quantity();
        }
    }
    arsort($product_sales);
    $top_products = [];
    foreach (array_slice($product_sales, 0, 5, true) as $pid => $qty) {
        $product = wc_get_product($pid);
        $top_products[] = [ 'name' => $product ? $product->get_name() : __('Unknown'), 'sales' => $qty ];
    }

    // Top customers
    $customer_orders = [];
    foreach ($orders as $order) { $cid = $order->get_customer_id(); if (!$cid) continue; if (!isset($customer_orders[$cid])) $customer_orders[$cid] = 0; $customer_orders[$cid]++; }
    arsort($customer_orders);
    $top_customers = [];
    foreach (array_slice($customer_orders, 0, 5, true) as $cid => $count) {
        $user = get_userdata($cid);
        $top_customers[] = [ 'name' => $user ? $user->display_name : __('Guest'), 'orders' => $count ];
    }

    woodash_log('AJAX woodash_get_data: response payload', [ 'total_sales' => wc_format_decimal($total_sales, 2), 'total_orders' => $total_orders, 'aov' => $aov, 'top_products_count' => count($top_products), 'top_customers_count' => count($top_customers) ]);

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
    $args = [ 'status' => ['wc-completed'], 'limit' => -1, 'return' => 'ids' ];
    if ($date_from) $args['date_created']['after'] = $date_from . ' 00:00:00';
    if ($date_to) $args['date_created']['before'] = $date_to . ' 23:59:59';
    $order_ids = wc_get_orders($args);
    $orders = [];
    foreach ($order_ids as $order_id) { $orders[] = wc_get_order($order_id); }
    $product_sales = [];
    foreach ($orders as $order) { foreach ($order->get_items() as $item) { $pid = $item->get_product_id(); if (!isset($product_sales[$pid])) $product_sales[$pid] = 0; $product_sales[$pid] += $item->get_quantity(); } }
    arsort($product_sales);
    header('Content-Type: text/csv'); header('Content-Disposition: attachment; filename="woodash-top-products.csv"');
    $output = fopen('php://output', 'w'); fputcsv($output, ['Product', 'Sales']);
    foreach (array_slice($product_sales, 0, 20, true) as $pid => $qty) { $product = wc_get_product($pid); fputcsv($output, [$product ? $product->get_name() : __('Unknown'), $qty]); }
    fclose($output); exit;
});

// Export Top Customers CSV
add_action('wp_ajax_woodash_export_customers_csv', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) wp_die('Unauthorized', 403);
    $date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : '';
    $date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : '';
    $granularity = isset($_GET['granularity']) ? sanitize_text_field($_GET['granularity']) : 'daily';
    $args = [ 'status' => ['wc-completed'], 'limit' => -1, 'return' => 'ids' ];
    if ($date_from) $args['date_created']['after'] = $date_from . ' 00:00:00';
    if ($date_to) $args['date_created']['before'] = $date_to . ' 23:59:59';
    $order_ids = wc_get_orders($args);
    $orders = [];
    foreach ($order_ids as $order_id) { $orders[] = wc_get_order($order_id); }
    $customer_orders = [];
    foreach ($orders as $order) { $cid = $order->get_customer_id(); if (!$cid) continue; if (!isset($customer_orders[$cid])) $customer_orders[$cid] = 0; $customer_orders[$cid]++; }
    arsort($customer_orders);
    header('Content-Type: text/csv'); header('Content-Disposition: attachment; filename="woodash-top-customers.csv"');
    $output = fopen('php://output', 'w'); fputcsv($output, ['Customer', 'Orders']);
    foreach (array_slice($customer_orders, 0, 20, true) as $cid => $count) { $user = get_userdata($cid); fputcsv($output, [$user ? $user->display_name : __('Guest'), $count]); }
    fclose($output); exit;
});

// Menu item styling
add_action('admin_head', function() { echo '<style>
#toplevel_page_woodash-pro { background: #00994a !important; opacity: 1 !important; }
#toplevel_page_woodash-pro .wp-menu-name, #toplevel_page_woodash-pro .wp-menu-image:before { color: #fff !important; }
#toplevel_page_woodash-pro:hover, #toplevel_page_woodash-pro.wp-has-current-submenu { background: #007a3d !important; }
</style>'; });

// REST routes
add_action('rest_api_init', function() {
    register_rest_route('woodash/v1', '/check-auth', [ 'methods' => 'GET', 'callback' => 'woodash_check_auth', 'permission_callback' => '__return_true' ]);
    register_rest_route('woodash/v1', '/check-subscription', [ 'methods' => 'GET', 'callback' => 'woodash_check_subscription', 'permission_callback' => '__return_true' ]);
    register_rest_route('woodash/v1', '/verify', [ 'methods' => 'POST', 'callback' => 'woodash_verify_store_connection', 'permission_callback' => '__return_true' ]);
    // CORS
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function($value) {
        $origin = get_http_origin(); if ($origin) {
            header('Access-Control-Allow-Origin: ' . esc_url_raw($origin));
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Headers: Authorization, Content-Type');
        }
        return $value;
    });
});

// Handle OPTIONS requests
add_action('init', function() {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        $origin = get_http_origin(); if ($origin) {
            header('Access-Control-Allow-Origin: ' . esc_url_raw($origin));
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Headers: Authorization, Content-Type');
        }
        exit;
    }
});

// REST: check auth
function woodash_check_auth() {
    if (!is_user_logged_in()) {
        return new WP_REST_Response([ 'is_logged_in' => false, 'message' => 'User not logged in' ]);
    }
    $user = wp_get_current_user();
    return new WP_REST_Response([
        'is_logged_in' => true,
        'user_id' => $user->ID,
        'user_email' => $user->user_email,
        'display_name' => $user->display_name
    ]);
}

// REST: check subscription
function woodash_check_subscription() {
    if (!is_user_logged_in()) {
        return new WP_REST_Response([ 'has_subscription' => false, 'message' => 'User not logged in' ], 401);
    }
    $user_id = get_current_user_id(); $has_subscription = false; $subscription_details = null;
    if (function_exists('wcs_get_users_subscriptions')) {
        $subscriptions = wcs_get_users_subscriptions($user_id);
        foreach ($subscriptions as $subscription) {
            if ($subscription->get_status() === 'active') {
                $has_subscription = true;
                $subscription_details = [ 'id' => $subscription->get_id(), 'status' => $subscription->get_status(), 'next_payment' => $subscription->get_date('next_payment'), 'product_name' => $subscription->get_product_name() ];
                break;
            }
        }
    }
    return new WP_REST_Response([ 'has_subscription' => $has_subscription, 'subscription_details' => $subscription_details, 'message' => $has_subscription ? 'Active subscription found' : 'No active subscription found' ]);
}

// REST: verify store connection
function woodash_verify_store_connection($request) {
    if (!is_user_logged_in()) { return new WP_REST_Response([ 'success' => false, 'message' => 'User not logged in' ], 401); }
    $params = $request->get_json_params();
    if (empty($params['site_url']) || empty($params['api_key']) || empty($params['user_id'])) { return new WP_REST_Response([ 'success' => false, 'message' => 'Missing required parameters' ], 400); }
    $user_id = get_current_user_id(); $has_subscription = false; $subscription_id = null;
    if (function_exists('wcs_get_users_subscriptions')) {
        $subscriptions = wcs_get_users_subscriptions($user_id);
        foreach ($subscriptions as $subscription) {
            if ($subscription->get_status() === 'active') { $has_subscription = true; $subscription_id = $subscription->get_id(); break; }
        }
    }
    if (!$has_subscription) { return new WP_REST_Response([ 'success' => false, 'message' => 'No active subscription found' ], 403); }
    $connections = get_option('woodash_connected_stores', []);
    foreach ($connections as $store_id => $connection) {
        if ($connection['site_url'] === $params['site_url']) {
            $connections[$store_id] = [ 'site_url' => $params['site_url'], 'user_id' => $params['user_id'], 'api_key' => $params['api_key'], 'connected_at' => current_time('mysql'), 'updated_at' => current_time('mysql'), 'status' => 'active', 'subscription_id' => $subscription_id ];
            update_option('woodash_connected_stores', $connections);
            return new WP_REST_Response([ 'success' => true, 'store_id' => $store_id, 'message' => 'Store connection updated successfully' ]);
        }
    }
    $store_id = wp_generate_uuid4();
    $connections[$store_id] = [ 'site_url' => $params['site_url'], 'user_id' => $params['user_id'], 'api_key' => $params['api_key'], 'connected_at' => current_time('mysql'), 'status' => 'active', 'subscription_id' => $subscription_id ];
    update_option('woodash_connected_stores', $connections);
    return new WP_REST_Response([ 'success' => true, 'store_id' => $store_id, 'message' => 'Store connected successfully' ]);
}

// Logging function
function woodash_log($message, $data = null) {
    $log_file = WP_CONTENT_DIR . '/woodash-debug.log';
    $timestamp = current_time('mysql');
    $log_message = "[{$timestamp}] {$message}";
    if ($data !== null) { $log_message .= "\nData: " . print_r($data, true); }
    $log_message .= "\n" . str_repeat('-', 80) . "\n";
    error_log($log_message, 3, $log_file);
}

// Client debug logger (from frontend)
add_action('wp_ajax_woodash_debug', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    $label = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : 'client';
    $data_json = isset($_POST['data']) ? wp_unslash($_POST['data']) : '';
    $data = json_decode($data_json, true);
    woodash_log('ClientDebug: ' . $label, $data);
    wp_send_json_success();
});

// AJAX: Get processing orders count
add_action('wp_ajax_woodash_get_processing_count', function() {
    check_ajax_referer('woodash_nonce', 'nonce');
    if (!current_user_can('manage_woocommerce')) wp_send_json_error('Unauthorized', 403);

    $after = isset($_POST['after']) ? sanitize_text_field($_POST['after']) : '';
    $before = isset($_POST['before']) ? sanitize_text_field($_POST['before']) : '';

    $after_date = $after ? substr($after, 0, 10) : current_time('Y-m-d');
    $before_date = $before ? substr($before, 0, 10) : current_time('Y-m-d');

    $args = [
        'status' => ['wc-processing'],
        'limit' => -1,
        'return' => 'ids',
        'date_created' => [
            'after' => $after_date . ' 00:00:00',
            'before' => $before_date . ' 23:59:59',
        ],
    ];

    $order_ids = wc_get_orders($args);
    $count = is_array($order_ids) ? count($order_ids) : 0;

    woodash_log('AJAX woodash_get_processing_count', [
        'after' => $after,
        'before' => $before,
        'normalized' => [ 'after' => $after_date, 'before' => $before_date ],
        'count' => $count,
    ]);

    wp_send_json_success([ 'processing' => $count ]);
});
