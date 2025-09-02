<?php
/**
 * API class for WoodDash Pro
 *
 * @package WoodashPro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

class Woodash_API {
    
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
        add_action('init', array($this, 'handle_cors'));
    }
    
    /**
     * Register REST API routes
     */
    public function register_routes() {
        register_rest_route('woodash/v1', '/analytics', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_analytics'),
            'permission_callback' => array($this, 'check_permissions')
        ));
        
        register_rest_route('woodash/v1', '/orders', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_orders'),
            'permission_callback' => array($this, 'check_permissions')
        ));
        
        register_rest_route('woodash/v1', '/products', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_products'),
            'permission_callback' => array($this, 'check_permissions')
        ));
        
        register_rest_route('woodash/v1', '/customers', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_customers'),
            'permission_callback' => array($this, 'check_permissions')
        ));
        
        register_rest_route('woodash/v1', '/check-subscription', array(
            'methods' => 'GET',
            'callback' => array($this, 'check_subscription'),
            'permission_callback' => '__return_true'
        ));
        
        register_rest_route('woodash/v1', '/verify', array(
            'methods' => 'POST',
            'callback' => array($this, 'verify_store_connection'),
            'permission_callback' => '__return_true'
        ));
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
        
        // Add CORS headers to REST API responses
        remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
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
     * Check API permissions
     *
     * @param WP_REST_Request $request
     * @return bool
     */
    public function check_permissions($request) {
        return current_user_can('manage_options');
    }
    
    /**
     * Get analytics data
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_analytics($request) {
        $date_from = $request->get_param('date_from') ?: current_time('Y-m-d');
        $date_to = $request->get_param('date_to') ?: current_time('Y-m-d');
        $granularity = $request->get_param('granularity') ?: 'daily';
        
        if (class_exists('Woodash_Analytics')) {
            $analytics = new Woodash_Analytics();
            $data = $analytics->get_dashboard_data($date_from, $date_to, $granularity);
        } else {
            $data = array(
                'total_sales' => 0,
                'total_orders' => 0,
                'aov' => 0,
                'top_products' => array(),
                'top_customers' => array(),
                'sales_overview' => array('labels' => array(), 'data' => array())
            );
        }
        
        return new WP_REST_Response($data, 200);
    }
    
    /**
     * Get orders data
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_orders($request) {
        $page = max(1, intval($request->get_param('page')));
        $per_page = min(100, max(1, intval($request->get_param('per_page')) ?: 20));
        $status = $request->get_param('status') ?: 'any';
        
        $args = array(
            'limit' => $per_page,
            'offset' => ($page - 1) * $per_page,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        if ($status !== 'any') {
            $args['status'] = $status;
        }
        
        $orders = wc_get_orders($args);
        $formatted_orders = array();
        
        foreach ($orders as $order) {
            $formatted_orders[] = array(
                'id' => $order->get_id(),
                'number' => $order->get_order_number(),
                'status' => $order->get_status(),
                'total' => $order->get_total(),
                'currency' => $order->get_currency(),
                'date_created' => $order->get_date_created()->date('c'),
                'customer_name' => $order->get_formatted_billing_full_name(),
                'customer_email' => $order->get_billing_email()
            );
        }
        
        return new WP_REST_Response(array(
            'orders' => $formatted_orders,
            'total' => wc_orders_count($status),
            'page' => $page,
            'per_page' => $per_page
        ), 200);
    }
    
    /**
     * Get products data
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_products($request) {
        $page = max(1, intval($request->get_param('page')));
        $per_page = min(100, max(1, intval($request->get_param('per_page')) ?: 20));
        $search = sanitize_text_field($request->get_param('search') ?: '');
        
        $args = array(
            'limit' => $per_page,
            'offset' => ($page - 1) * $per_page,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        if ($search) {
            $args['s'] = $search;
        }
        
        $query = new WC_Product_Query($args);
        $products = $query->get_products();
        $formatted_products = array();
        
        foreach ($products as $product) {
            $formatted_products[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'sku' => $product->get_sku(),
                'price' => $product->get_price(),
                'regular_price' => $product->get_regular_price(),
                'sale_price' => $product->get_sale_price(),
                'stock_quantity' => $product->get_stock_quantity(),
                'stock_status' => $product->get_stock_status(),
                'status' => $product->get_status(),
                'type' => $product->get_type(),
                'featured' => $product->is_featured(),
                'date_created' => $product->get_date_created()->date('c')
            );
        }
        
        // Get total count
        $total_query = new WC_Product_Query(array('return' => 'ids', 'limit' => -1));
        $total = count($total_query->get_products());
        
        return new WP_REST_Response(array(
            'products' => $formatted_products,
            'total' => $total,
            'page' => $page,
            'per_page' => $per_page
        ), 200);
    }
    
    /**
     * Get customers data
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_customers($request) {
        $page = max(1, intval($request->get_param('page')));
        $per_page = min(100, max(1, intval($request->get_param('per_page')) ?: 20));
        
        $args = array(
            'number' => $per_page,
            'offset' => ($page - 1) * $per_page,
            'orderby' => 'registered',
            'order' => 'DESC',
            'meta_query' => array(
                array(
                    'key' => 'paying_customer',
                    'value' => '1',
                    'compare' => '='
                )
            )
        );
        
        $customers = get_users($args);
        $formatted_customers = array();
        
        foreach ($customers as $customer) {
            $customer_obj = new WC_Customer($customer->ID);
            
            $formatted_customers[] = array(
                'id' => $customer->ID,
                'email' => $customer->user_email,
                'first_name' => $customer_obj->get_first_name(),
                'last_name' => $customer_obj->get_last_name(),
                'display_name' => $customer->display_name,
                'orders_count' => $customer_obj->get_order_count(),
                'total_spent' => $customer_obj->get_total_spent(),
                'date_registered' => $customer->user_registered,
                'billing_city' => $customer_obj->get_billing_city(),
                'billing_country' => $customer_obj->get_billing_country()
            );
        }
        
        // Get total count
        $total_args = array('meta_key' => 'paying_customer', 'meta_value' => '1', 'count_total' => true);
        $total = count(get_users($total_args));
        
        return new WP_REST_Response(array(
            'customers' => $formatted_customers,
            'total' => $total,
            'page' => $page,
            'per_page' => $per_page
        ), 200);
    }
    
    /**
     * Check subscription status
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function check_subscription($request) {
        if (!is_user_logged_in()) {
            return new WP_REST_Response(array(
                'has_subscription' => false,
                'message' => __('User not logged in', 'woodash-pro')
            ), 401);
        }
        
        $user_id = get_current_user_id();
        $has_subscription = false;
        $subscription_details = null;
        
        // Check for WooCommerce Subscriptions
        if (function_exists('wcs_get_users_subscriptions')) {
            $subscriptions = wcs_get_users_subscriptions($user_id);
            foreach ($subscriptions as $subscription) {
                if ($subscription->get_status() === 'active') {
                    $has_subscription = true;
                    $subscription_details = array(
                        'id' => $subscription->get_id(),
                        'status' => $subscription->get_status(),
                        'next_payment' => $subscription->get_date('next_payment'),
                        'product_name' => $subscription->get_name()
                    );
                    break;
                }
            }
        }
        
        return new WP_REST_Response(array(
            'has_subscription' => $has_subscription,
            'subscription_details' => $subscription_details,
            'message' => $has_subscription 
                ? __('Active subscription found', 'woodash-pro') 
                : __('No active subscription found', 'woodash-pro')
        ), 200);
    }
    
    /**
     * Verify store connection
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function verify_store_connection($request) {
        $params = $request->get_json_params();
        
        if (empty($params['site_url']) || empty($params['api_key']) || empty($params['user_id'])) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('Missing required parameters', 'woodash-pro')
            ), 400);
        }
        
        // Here you would implement the actual verification logic
        // For now, we'll just return success
        $store_id = wp_generate_uuid4();
        
        $connections = get_option('woodash_connected_stores', array());
        $connections[$store_id] = array(
            'site_url' => sanitize_url($params['site_url']),
            'user_id' => intval($params['user_id']),
            'api_key' => sanitize_text_field($params['api_key']),
            'connected_at' => current_time('mysql'),
            'status' => 'active'
        );
        
        update_option('woodash_connected_stores', $connections);
        
        return new WP_REST_Response(array(
            'success' => true,
            'store_id' => $store_id,
            'message' => __('Store connected successfully', 'woodash-pro')
        ), 200);
    }
}
