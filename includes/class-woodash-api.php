<?php
/**
 * WooDash Pro API Class
 * 
 * Handles REST API endpoints and AJAX requests
 * 
 * @package WooDashPro
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class WooDash_API {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize API hooks
     */
    private function init_hooks() {
        // REST API endpoints
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        
        // AJAX handlers
        $this->register_ajax_handlers();
        
        // CORS handling
        add_action('init', array($this, 'handle_cors'));
    }
    
    /**
     * Register REST API routes
     */
    public function register_rest_routes() {
        // Check authentication
        register_rest_route('woodash/v1', '/check-auth', array(
            'methods' => 'GET',
            'callback' => array($this, 'check_auth'),
            'permission_callback' => '__return_true'
        ));
        
        // Check subscription
        register_rest_route('woodash/v1', '/check-subscription', array(
            'methods' => 'GET',
            'callback' => array($this, 'check_subscription'),
            'permission_callback' => '__return_true'
        ));
        
        // Verify store connection
        register_rest_route('woodash/v1', '/verify', array(
            'methods' => 'POST',
            'callback' => array($this, 'verify_store_connection'),
            'permission_callback' => '__return_true'
        ));
        
        // Get dashboard data
        register_rest_route('woodash/v1', '/data', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_dashboard_data'),
            'permission_callback' => array($this, 'check_woocommerce_permission')
        ));
        
        // WooCommerce Analytics API endpoints (replacement for missing WC Analytics)
        register_rest_route('wc-analytics/reports', '/orders/stats', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_orders_stats'),
            'permission_callback' => array($this, 'check_woocommerce_permission')
        ));
        
        register_rest_route('wc-analytics/reports', '/revenue/stats', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_revenue_stats'),
            'permission_callback' => array($this, 'check_woocommerce_permission')
        ));
        
        register_rest_route('wc-analytics/reports', '/customers/stats', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_customers_stats'),
            'permission_callback' => array($this, 'check_woocommerce_permission')
        ));
        
        // Export data
        register_rest_route('woodash/v1', '/export/(?P<type>\w+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'export_data'),
            'permission_callback' => array($this, 'check_woocommerce_permission'),
            'args' => array(
                'type' => array(
                    'required' => true,
                    'validate_callback' => function($param) {
                        return in_array($param, array('products', 'customers', 'orders'));
                    }
                )
            )
        ));
    }
    
    /**
     * Register AJAX handlers
     */
    private function register_ajax_handlers() {
        $ajax_handlers = array(
            'woodash_get_data' => 'handle_get_data',
            'woodash_export_products_csv' => 'handle_export_products_csv',
            'woodash_export_customers_csv' => 'handle_export_customers_csv',
            'woodash_check_subscription' => 'handle_check_subscription',
            'woodash_debug' => 'handle_debug',
            'woodash_get_processing_count' => 'handle_get_processing_count'
        );
        
        foreach ($ajax_handlers as $action => $method) {
            add_action("wp_ajax_{$action}", array($this, $method));
        }
    }
    
    /**
     * Handle CORS requests
     */
    public function handle_cors() {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $origin = get_http_origin();
            if ($origin) {
                header('Access-Control-Allow-Origin: ' . esc_url_raw($origin));
                header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Allow-Headers: Authorization, Content-Type');
            }
            exit;
        }
        
        // Add CORS headers for REST API
        add_filter('rest_pre_serve_request', function($value) {
            $origin = get_http_origin();
            if ($origin) {
                header('Access-Control-Allow-Origin: ' . esc_url_raw($origin));
                header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Allow-Headers: Authorization, Content-Type');
            }
            return $value;
        });
    }
    
    /**
     * Check WooCommerce permission
     */
    public function check_woocommerce_permission() {
        return current_user_can('manage_woocommerce');
    }
    
    /**
     * REST: Check authentication
     */
    public function check_auth() {
        if (!is_user_logged_in()) {
            return new WP_REST_Response(array(
                'is_logged_in' => false,
                'message' => __('User not logged in', 'woodashpro')
            ));
        }
        
        $user = wp_get_current_user();
        return new WP_REST_Response(array(
            'is_logged_in' => true,
            'user_id' => $user->ID,
            'user_email' => $user->user_email,
            'display_name' => $user->display_name,
            'can_manage_woocommerce' => current_user_can('manage_woocommerce')
        ));
    }
    
    /**
     * REST: Check subscription
     */
    public function check_subscription() {
        if (!is_user_logged_in()) {
            return new WP_REST_Response(array(
                'has_subscription' => false,
                'message' => __('User not logged in', 'woodashpro')
            ), 401);
        }
        
        $user_id = get_current_user_id();
        $has_subscription = false;
        $subscription_details = null;
        
        if (function_exists('wcs_get_users_subscriptions')) {
            $subscriptions = wcs_get_users_subscriptions($user_id);
            foreach ($subscriptions as $subscription) {
                if ($subscription->get_status() === 'active') {
                    $has_subscription = true;
                    $subscription_details = array(
                        'id' => $subscription->get_id(),
                        'status' => $subscription->get_status(),
                        'next_payment' => $subscription->get_date('next_payment'),
                        'product_name' => $subscription->get_product_name()
                    );
                    break;
                }
            }
        }
        
        return new WP_REST_Response(array(
            'has_subscription' => $has_subscription,
            'subscription_details' => $subscription_details,
            'message' => $has_subscription 
                ? __('Active subscription found', 'woodashpro') 
                : __('No active subscription found', 'woodashpro')
        ));
    }
    
    /**
     * REST: Verify store connection
     */
    public function verify_store_connection($request) {
        if (!is_user_logged_in()) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('User not logged in', 'woodashpro')
            ), 401);
        }
        
        $params = $request->get_json_params();
        
        if (empty($params['site_url']) || empty($params['api_key']) || empty($params['user_id'])) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('Missing required parameters', 'woodashpro')
            ), 400);
        }
        
        // Check subscription
        $subscription_check = $this->check_subscription();
        if (!$subscription_check->data['has_subscription']) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('No active subscription found', 'woodashpro')
            ), 403);
        }
        
        // Store connection
        $store_id = wp_generate_uuid4();
        $connections = get_option('woodash_connected_stores', array());
        
        $connections[$store_id] = array(
            'site_url' => $params['site_url'],
            'user_id' => $params['user_id'],
            'api_key' => $params['api_key'],
            'connected_at' => current_time('mysql'),
            'status' => 'active',
            'subscription_id' => $subscription_check->data['subscription_details']['id'] ?? null
        );
        
        update_option('woodash_connected_stores', $connections);
        
        return new WP_REST_Response(array(
            'success' => true,
            'store_id' => $store_id,
            'message' => __('Store connected successfully', 'woodashpro')
        ));
    }
    
    /**
     * REST: Get dashboard data
     */
    public function get_dashboard_data($request) {
        $date_from = $request->get_param('date_from') ?: current_time('Y-m-d');
        $date_to = $request->get_param('date_to') ?: current_time('Y-m-d');
        $granularity = $request->get_param('granularity') ?: 'daily';
        
        return $this->get_analytics_data($date_from, $date_to, $granularity);
    }
    
    /**
     * REST: Export data
     */
    public function export_data($request) {
        $type = $request->get_param('type');
        $format = $request->get_param('format') ?: 'csv';
        
        switch ($type) {
            case 'products':
                return $this->export_products_data($format);
            case 'customers':
                return $this->export_customers_data($format);
            case 'orders':
                return $this->export_orders_data($format);
            default:
                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => __('Invalid export type', 'woodashpro')
                ), 400);
        }
    }
    
    /**
     * AJAX: Get data
     */
    public function handle_get_data() {
        // Check nonce - make it optional for backward compatibility
        if (isset($_REQUEST['nonce'])) {
            if (!wp_verify_nonce($_REQUEST['nonce'], 'woodash_nonce')) {
                wp_send_json_error(__('Invalid nonce', 'woodashpro'), 403);
                return;
            }
        }
        
        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error(__('Unauthorized', 'woodashpro'), 403);
            return;
        }
        
        // Get parameters from both GET and POST
        $date_from = sanitize_text_field($_REQUEST['date_from'] ?? '');
        $date_to = sanitize_text_field($_REQUEST['date_to'] ?? '');
        $granularity = sanitize_text_field($_REQUEST['granularity'] ?? 'daily');
        
        if (empty($date_from) && empty($date_to)) {
            $today = current_time('Y-m-d');
            $date_from = $today;
            $date_to = $today;
        }
        
        try {
            $data = $this->get_analytics_data($date_from, $date_to, $granularity);
            wp_send_json_success($data);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage(), 500);
        }
    }
    
    /**
     * Get analytics data
     */
    private function get_analytics_data($date_from, $date_to, $granularity) {
        $args = array(
            'status' => array('wc-completed'),
            'limit' => -1,
            'return' => 'ids'
        );
        
        if ($date_from) {
            $args['date_created']['after'] = $date_from . ' 00:00:00';
        }
        if ($date_to) {
            $args['date_created']['before'] = $date_to . ' 23:59:59';
        }
        
        $order_ids = wc_get_orders($args);
        $orders = array();
        $total_sales = 0;
        
        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);
            $orders[] = $order;
            $total_sales += (float) $order->get_total();
        }
        
        $total_orders = count($orders);
        $aov = $total_orders > 0 ? round($total_sales / $total_orders, 2) : 0;
        
        // Sales overview
        $sales_overview = $this->group_sales_by_period($orders, $granularity);
        
        // Top products
        $top_products = $this->get_top_products($orders);
        
        // Top customers
        $top_customers = $this->get_top_customers($orders);
        
        return array(
            'total_sales' => wc_format_decimal($total_sales, 2),
            'total_orders' => $total_orders,
            'aov' => $aov,
            'top_products' => $top_products,
            'top_customers' => $top_customers,
            'sales_overview' => $sales_overview
        );
    }
    
    /**
     * Group sales by period
     */
    private function group_sales_by_period($orders, $granularity) {
        $grouped = array();
        
        foreach ($orders as $order) {
            $date = $order->get_date_created();
            if (!$date) continue;
            
            switch ($granularity) {
                case 'daily':
                    $key = $date->date_i18n('Y-m-d');
                    break;
                case 'weekly':
                    $key = $date->date_i18n('o-\WW');
                    break;
                case 'monthly':
                    $key = $date->date_i18n('Y-m');
                    break;
                default:
                    $key = $date->date_i18n('Y-m-d');
            }
            
            if (!isset($grouped[$key])) {
                $grouped[$key] = 0;
            }
            $grouped[$key] += (float) $order->get_total();
        }
        
        ksort($grouped);
        
        return array(
            'labels' => array_keys($grouped),
            'data' => array_values($grouped)
        );
    }
    
    /**
     * Get top products
     */
    private function get_top_products($orders, $limit = 5) {
        $product_sales = array();
        
        foreach ($orders as $order) {
            foreach ($order->get_items() as $item) {
                $product_id = $item->get_product_id();
                if (!isset($product_sales[$product_id])) {
                    $product_sales[$product_id] = 0;
                }
                $product_sales[$product_id] += $item->get_quantity();
            }
        }
        
        arsort($product_sales);
        $top_products = array();
        
        foreach (array_slice($product_sales, 0, $limit, true) as $product_id => $quantity) {
            $product = wc_get_product($product_id);
            $top_products[] = array(
                'name' => $product ? $product->get_name() : __('Unknown Product', 'woodashpro'),
                'sales' => $quantity
            );
        }
        
        return $top_products;
    }
    
    /**
     * Get top customers
     */
    private function get_top_customers($orders, $limit = 5) {
        $customer_orders = array();
        
        foreach ($orders as $order) {
            $customer_id = $order->get_customer_id();
            if (!$customer_id) continue;
            
            if (!isset($customer_orders[$customer_id])) {
                $customer_orders[$customer_id] = 0;
            }
            $customer_orders[$customer_id]++;
        }
        
        arsort($customer_orders);
        $top_customers = array();
        
        foreach (array_slice($customer_orders, 0, $limit, true) as $customer_id => $order_count) {
            $user = get_userdata($customer_id);
            $top_customers[] = array(
                'name' => $user ? $user->display_name : __('Guest Customer', 'woodashpro'),
                'orders' => $order_count
            );
        }
        
        return $top_customers;
    }
    
    /**
     * WooCommerce Analytics API replacement methods
     */
    public function get_orders_stats($request) {
        $after = $request->get_param('after') ?: date('Y-m-d', strtotime('-30 days'));
        $before = $request->get_param('before') ?: date('Y-m-d');
        $interval = $request->get_param('interval') ?: 'day';
        
        $args = array(
            'status' => array('wc-completed', 'wc-processing', 'wc-on-hold'),
            'limit' => -1,
            'date_created' => $after . '...' . $before,
            'return' => 'ids'
        );
        
        $order_ids = wc_get_orders($args);
        $total_orders = count($order_ids);
        
        return new WP_REST_Response(array(
            'totals' => array(
                'orders_count' => $total_orders,
                'net_revenue' => $this->calculate_net_revenue($order_ids),
                'avg_order_value' => $total_orders > 0 ? $this->calculate_net_revenue($order_ids) / $total_orders : 0
            ),
            'intervals' => $this->get_order_intervals($order_ids, $after, $before, $interval)
        ));
    }
    
    public function get_revenue_stats($request) {
        $after = $request->get_param('after') ?: date('Y-m-d', strtotime('-30 days'));
        $before = $request->get_param('before') ?: date('Y-m-d');
        $interval = $request->get_param('interval') ?: 'day';
        
        $args = array(
            'status' => array('wc-completed', 'wc-processing'),
            'limit' => -1,
            'date_created' => $after . '...' . $before,
            'return' => 'ids'
        );
        
        $order_ids = wc_get_orders($args);
        $total_revenue = $this->calculate_net_revenue($order_ids);
        
        return new WP_REST_Response(array(
            'totals' => array(
                'total_sales' => $total_revenue,
                'net_revenue' => $total_revenue,
                'orders_count' => count($order_ids)
            ),
            'intervals' => $this->get_revenue_intervals($order_ids, $after, $before, $interval)
        ));
    }
    
    public function get_customers_stats($request) {
        $after = $request->get_param('after') ?: date('Y-m-d', strtotime('-30 days'));
        $before = $request->get_param('before') ?: date('Y-m-d');
        $customer_type = $request->get_param('customer_type');
        $segmentby = $request->get_param('segmentby');
        
        $args = array(
            'status' => array('wc-completed', 'wc-processing'),
            'limit' => -1,
            'date_created' => $after . '...' . $before,
            'return' => 'ids'
        );
        
        $order_ids = wc_get_orders($args);
        $customer_data = $this->analyze_customers($order_ids, $customer_type, $segmentby);
        
        return new WP_REST_Response(array(
            'totals' => $customer_data['totals'],
            'intervals' => $customer_data['intervals']
        ));
    }
    
    private function calculate_net_revenue($order_ids) {
        $total = 0;
        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);
            if ($order) {
                $total += (float) $order->get_total();
            }
        }
        return $total;
    }
    
    private function get_order_intervals($order_ids, $after, $before, $interval) {
        $intervals = array();
        $orders_by_date = array();
        
        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);
            if ($order) {
                $date = $order->get_date_created()->date('Y-m-d');
                if (!isset($orders_by_date[$date])) {
                    $orders_by_date[$date] = 0;
                }
                $orders_by_date[$date]++;
            }
        }
        
        $start = strtotime($after);
        $end = strtotime($before);
        
        for ($d = $start; $d <= $end; $d += 86400) {
            $date = date('Y-m-d', $d);
            $intervals[] = array(
                'interval' => $date,
                'date_start' => $date . 'T00:00:00',
                'date_end' => $date . 'T23:59:59',
                'subtotals' => array(
                    'orders_count' => isset($orders_by_date[$date]) ? $orders_by_date[$date] : 0
                )
            );
        }
        
        return $intervals;
    }
    
    private function get_revenue_intervals($order_ids, $after, $before, $interval) {
        $intervals = array();
        $revenue_by_date = array();
        
        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);
            if ($order) {
                $date = $order->get_date_created()->date('Y-m-d');
                if (!isset($revenue_by_date[$date])) {
                    $revenue_by_date[$date] = 0;
                }
                $revenue_by_date[$date] += (float) $order->get_total();
            }
        }
        
        $start = strtotime($after);
        $end = strtotime($before);
        
        for ($d = $start; $d <= $end; $d += 86400) {
            $date = date('Y-m-d', $d);
            $intervals[] = array(
                'interval' => $date,
                'date_start' => $date . 'T00:00:00',
                'date_end' => $date . 'T23:59:59',
                'subtotals' => array(
                    'total_sales' => isset($revenue_by_date[$date]) ? $revenue_by_date[$date] : 0,
                    'net_revenue' => isset($revenue_by_date[$date]) ? $revenue_by_date[$date] : 0
                )
            );
        }
        
        return $intervals;
    }
    
    private function analyze_customers($order_ids, $customer_type, $segmentby) {
        $customers = array();
        $new_customers = 0;
        $returning_customers = 0;
        
        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);
            if ($order) {
                $customer_id = $order->get_customer_id();
                if ($customer_id) {
                    if (!isset($customers[$customer_id])) {
                        $customers[$customer_id] = 0;
                        $new_customers++;
                    } else {
                        $returning_customers++;
                    }
                    $customers[$customer_id]++;
                }
            }
        }
        
        return array(
            'totals' => array(
                'customers_count' => count($customers),
                'new_customers' => $new_customers,
                'returning_customers' => $returning_customers
            ),
            'intervals' => array() // Simplified for now
        );
    }
    
    /**
     * Additional AJAX handlers for backward compatibility
     */
    public function handle_export_products_csv() {
        // Check nonce - make it optional for backward compatibility
        if (isset($_REQUEST['nonce'])) {
            if (!wp_verify_nonce($_REQUEST['nonce'], 'woodash_nonce')) {
                wp_send_json_error(__('Invalid nonce', 'woodashpro'), 403);
                return;
            }
        }
        
        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error(__('Unauthorized', 'woodashpro'), 403);
            return;
        }
        
        try {
            $this->export_products_csv();
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage(), 500);
        }
    }
    
    public function handle_export_customers_csv() {
        // Check nonce - make it optional for backward compatibility
        if (isset($_REQUEST['nonce'])) {
            if (!wp_verify_nonce($_REQUEST['nonce'], 'woodash_nonce')) {
                wp_send_json_error(__('Invalid nonce', 'woodashpro'), 403);
                return;
            }
        }
        
        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error(__('Unauthorized', 'woodashpro'), 403);
            return;
        }
        
        try {
            $this->export_customers_csv();
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage(), 500);
        }
    }
    
    public function handle_check_subscription() {
        // Check nonce - make it optional for backward compatibility
        if (isset($_REQUEST['nonce'])) {
            if (!wp_verify_nonce($_REQUEST['nonce'], 'woodash_nonce')) {
                wp_send_json_error(__('Invalid nonce', 'woodashpro'), 403);
                return;
            }
        }
        
        try {
            $response = $this->check_subscription();
            if (!$response->data['has_subscription']) {
                wp_send_json_error(array(
                    'message' => __('No active subscription found', 'woodashpro'),
                    'redirect_url' => 'https://saas2.mohamedamzil.pw/pricing/'
                ));
                return;
            }
            
            wp_send_json_success();
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage(), 500);
        }
    }
    
    public function handle_debug() {
        // Check nonce - make it optional for backward compatibility
        if (isset($_REQUEST['nonce'])) {
            if (!wp_verify_nonce($_REQUEST['nonce'], 'woodash_nonce')) {
                wp_send_json_error(__('Invalid nonce', 'woodashpro'), 403);
                return;
            }
        }
        
        try {
            $label = sanitize_text_field($_REQUEST['label'] ?? 'debug');
            $data = json_decode(wp_unslash($_REQUEST['data'] ?? ''), true);
            
            // Use error_log if woodashpro_log doesn't exist
            if (function_exists('woodashpro_log')) {
                woodashpro_log('Debug: ' . $label, $data);
            } else {
                error_log('WooDash Debug: ' . $label . ' - ' . print_r($data, true));
            }
            
            wp_send_json_success();
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage(), 500);
        }
    }
    
    public function handle_get_processing_count() {
        // Check nonce - make it optional for backward compatibility
        if (isset($_REQUEST['nonce'])) {
            if (!wp_verify_nonce($_REQUEST['nonce'], 'woodash_nonce')) {
                wp_send_json_error(__('Invalid nonce', 'woodashpro'), 403);
                return;
            }
        }
        
        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error(__('Unauthorized', 'woodashpro'), 403);
            return;
        }
        
        try {
            $after = sanitize_text_field($_REQUEST['after'] ?? '');
            $before = sanitize_text_field($_REQUEST['before'] ?? '');
            
            $after_date = $after ? substr($after, 0, 10) : current_time('Y-m-d');
            $before_date = $before ? substr($before, 0, 10) : current_time('Y-m-d');
            
            $args = array(
                'status' => array('wc-processing'),
                'limit' => -1,
                'return' => 'ids',
                'date_created' => array(
                    'after' => $after_date . ' 00:00:00',
                    'before' => $before_date . ' 23:59:59'
                )
            );
            
            $order_ids = wc_get_orders($args);
            $count = is_array($order_ids) ? count($order_ids) : 0;
            
            wp_send_json_success(array('processing' => $count));
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage(), 500);
        }
    }
    
    /**
     * Handle legacy AJAX requests
     */
    public function handle_legacy_ajax() {
        $action = str_replace('wp_ajax_', '', current_action());
        
        // Map legacy actions to new methods
        $method_map = array(
            'woodash_get_data' => 'handle_get_data',
            'woodash_export_products_csv' => 'handle_export_products_csv',
            'woodash_export_customers_csv' => 'handle_export_customers_csv',
            'woodash_check_subscription' => 'handle_check_subscription',
            'woodash_debug' => 'handle_debug',
            'woodash_get_processing_count' => 'handle_get_processing_count'
        );
        
        if (isset($method_map[$action]) && method_exists($this, $method_map[$action])) {
            call_user_func(array($this, $method_map[$action]));
        } else {
            wp_send_json_error('Method not found');
        }
    }
    
    /**
     * Export products to CSV
     */
    private function export_products_csv() {
        // Implementation for product CSV export
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="woodash-products.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, array('Product Name', 'SKU', 'Price', 'Stock'));
        
        $products = wc_get_products(array('limit' => -1));
        foreach ($products as $product) {
            fputcsv($output, array(
                $product->get_name(),
                $product->get_sku(),
                $product->get_price(),
                $product->get_stock_quantity()
            ));
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Export customers to CSV
     */
    private function export_customers_csv() {
        // Implementation for customer CSV export
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="woodash-customers.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, array('Customer Name', 'Email', 'Orders', 'Total Spent'));
        
        $customers = get_users(array('role' => 'customer'));
        foreach ($customers as $customer) {
            $orders = wc_get_orders(array('customer' => $customer->ID));
            $total_spent = 0;
            foreach ($orders as $order) {
                if ($order->get_status() === 'completed') {
                    $total_spent += $order->get_total();
                }
            }
            
            fputcsv($output, array(
                $customer->display_name,
                $customer->user_email,
                count($orders),
                $total_spent
            ));
        }
        
        fclose($output);
        exit;
    }
}
