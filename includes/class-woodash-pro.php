<?php
if (!defined('ABSPATH')) exit;

class WoodashPro {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
        register_activation_hook(WOODASH_PRO_PLUGIN_FILE, array($this, 'activate'));
        register_deactivation_hook(WOODASH_PRO_PLUGIN_FILE, array($this, 'deactivate'));
        register_uninstall_hook(WOODASH_PRO_PLUGIN_FILE, array('WoodashPro', 'uninstall'));
    }

    public function init() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            return;
        }

        // Load plugin textdomain
        load_plugin_textdomain('woodash-pro', false, dirname(WOODASH_PRO_PLUGIN_BASENAME) . '/languages');

        // Initialize plugin components
        $this->init_hooks();
        $this->init_admin();
    }

    private function init_hooks() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));

        // Enqueue scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // AJAX handlers
        add_action('wp_ajax_woodash_get_dashboard_data', array($this, 'get_dashboard_data'));
        add_action('wp_ajax_woodash_get_dashboard_stats', array($this, 'get_dashboard_stats'));
        add_action('wp_ajax_woodash_get_notifications', array($this, 'get_notifications'));
        add_action('wp_ajax_woodash_mark_notification_read', array($this, 'mark_notification_read'));
        add_action('wp_ajax_woodash_save_notification_settings', array($this, 'save_notification_settings'));
        add_action('wp_ajax_woodash_get_notification_settings', array($this, 'get_notification_settings'));
    }

    private function init_admin() {
        // Initialize admin components if needed
    }

    public function add_admin_menu() {
        add_menu_page(
            __('WooDash Pro', 'woodash-pro'),
            __('WooDash Pro', 'woodash-pro'),
            'manage_options',
            'woodash-pro',
            array($this, 'admin_page'),
            'dashicons-chart-bar',
            56
        );

        // Add submenu pages
        add_submenu_page(
            'woodash-pro',
            __('Dashboard', 'woodash-pro'),
            __('Dashboard', 'woodash-pro'),
            'manage_options',
            'woodash-pro',
            array($this, 'admin_page')
        );

        add_submenu_page(
            'woodash-pro',
            __('Orders', 'woodash-pro'),
            __('Orders', 'woodash-pro'),
            'manage_options',
            'woodash-pro-orders',
            array($this, 'orders_page')
        );

        add_submenu_page(
            'woodash-pro',
            __('Customers', 'woodash-pro'),
            __('Customers', 'woodash-pro'),
            'manage_options',
            'woodash-pro-customers',
            array($this, 'customers_page')
        );

        add_submenu_page(
            'woodash-pro',
            __('Products', 'woodash-pro'),
            __('Products', 'woodash-pro'),
            'manage_options',
            'woodash-pro-products',
            array($this, 'products_page')
        );

        add_submenu_page(
            'woodash-pro',
            __('Reports', 'woodash-pro'),
            __('Reports', 'woodash-pro'),
            'manage_options',
            'woodash-pro-reports',
            array($this, 'reports_page')
        );

        add_submenu_page(
            'woodash-pro',
            __('Stock', 'woodash-pro'),
            __('Stock', 'woodash-pro'),
            'manage_options',
            'woodash-pro-stock',
            array($this, 'stock_page')
        );

        add_submenu_page(
            'woodash-pro',
            __('Marketing', 'woodash-pro'),
            __('Marketing', 'woodash-pro'),
            'manage_options',
            'woodash-pro-marketing',
            array($this, 'marketing_page')
        );

        add_submenu_page(
            'woodash-pro',
            __('Reviews', 'woodash-pro'),
            __('Reviews', 'woodash-pro'),
            'manage_options',
            'woodash-pro-reviews',
            array($this, 'reviews_page')
        );

        add_submenu_page(
            'woodash-pro',
            __('Settings', 'woodash-pro'),
            __('Settings', 'woodash-pro'),
            'manage_options',
            'woodash-pro-settings',
            array($this, 'settings_page')
        );
    }

    public function admin_page() {
        include WOODASH_PRO_PLUGIN_DIR . 'templates/dashboard.php';
    }

    public function orders_page() {
        include WOODASH_PRO_PLUGIN_DIR . 'templates/orders.php';
    }

    public function customers_page() {
        include WOODASH_PRO_PLUGIN_DIR . 'templates/customers.php';
    }

    public function products_page() {
        include WOODASH_PRO_PLUGIN_DIR . 'templates/products.php';
    }

    public function reports_page() {
        include WOODASH_PRO_PLUGIN_DIR . 'templates/reports.php';
    }

    public function stock_page() {
        include WOODASH_PRO_PLUGIN_DIR . 'templates/stock.php';
    }

    public function marketing_page() {
        include WOODASH_PRO_PLUGIN_DIR . 'templates/marketing.php';
    }

    public function reviews_page() {
        include WOODASH_PRO_PLUGIN_DIR . 'templates/reviews.php';
    }

    public function settings_page() {
        include WOODASH_PRO_PLUGIN_DIR . 'templates/settings.php';
    }

    public function enqueue_admin_scripts($hook) {
        // Only enqueue on WooDash Pro pages
        $allowed_hooks = array(
            'toplevel_page_woodash-pro',
            'woodash-pro_page_woodash-pro-orders',
            'woodash-pro_page_woodash-pro-customers',
            'woodash-pro_page_woodash-pro-products',
            'woodash-pro_page_woodash-pro-reports',
            'woodash-pro_page_woodash-pro-stock',
            'woodash-pro_page_woodash-pro-marketing',
            'woodash-pro_page_woodash-pro-reviews',
            'woodash-pro_page_woodash-pro-settings'
        );

        if (!in_array($hook, $allowed_hooks)) {
            return;
        }

        // Ensure jQuery is loaded first with proper dependency
        wp_enqueue_script('jquery');
        
        // Ensure jQuery is available in noConflict mode
        wp_add_inline_script('jquery', '
            // Ensure jQuery is available globally in WordPress admin
            window.jQuery = jQuery;
            window.$ = jQuery;
        ');
        
        // Load Chart.js from CDN with higher priority
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array('jquery'), '4.4.0', true);

        // Load our dashboard script with dependencies
        wp_enqueue_script('woodash-pro-admin', WOODASH_PRO_PLUGIN_URL . 'assets/js/dashboard.js', array('jquery', 'chart-js'), WOODASH_PRO_VERSION, true);
        
        // Add inline script to initialize dashboard after scripts load
        wp_add_inline_script('woodash-pro-admin', '
            // Initialize dashboard when DOM is ready and all scripts are loaded
            jQuery(document).ready(function($) {
                if (typeof WoodashDashboard !== "undefined" && typeof WoodashDashboard.init === "function") {
                    // Initialize the dashboard
                    const dashboard = new WoodashDashboard();
                } else {
                    console.warn("WoodashDashboard class not found - script may not have loaded properly");
                }
            });
        ');
        
        // Localize script with necessary data
        wp_localize_script('woodash-pro-admin', 'woodashData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('woodash_nonce'),
            'restUrl' => rest_url('wc/v3/'),
            'restNonce' => wp_create_nonce('wp_rest'),
            'autoRefresh' => 30, // seconds
            'currency' => get_woocommerce_currency(),
            'currencySymbol' => get_woocommerce_currency_symbol(),
            'dateFormat' => get_option('date_format'),
            'timeFormat' => get_option('time_format'),
            'pluginUrl' => WOODASH_PRO_PLUGIN_URL,
            'version' => WOODASH_PRO_VERSION,
            'debug' => defined('WP_DEBUG') && WP_DEBUG
        ));
        
        wp_enqueue_style('woodash-pro-admin', WOODASH_PRO_PLUGIN_URL . 'assets/css/tailwind.min.css', array(), WOODASH_PRO_VERSION);

        // Add custom CSS to hide WordPress sidebar
        wp_add_inline_style('woodash-pro-admin', '
            .woodash-fullscreen #adminmenumain,
            .woodash-fullscreen #wpadminbar,
            .woodash-fullscreen #adminmenuwrap,
            .woodash-fullscreen #adminmenuback {
                display: none !important;
            }
            
            .woodash-fullscreen #wpcontent,
            .woodash-fullscreen #wpbody {
                margin-left: 0 !important;
                padding-left: 0 !important;
            }
            
            .woodash-fullscreen #wpbody-content {
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
            
            .woodash-fullscreen .wrap {
                margin-left: 0 !important;
                margin-right: 0 !important;
                max-width: none !important;
            }
            
            /* Additional styles for better fullscreen experience */
            .woodash-fullscreen .notice,
            .woodash-fullscreen .error,
            .woodash-fullscreen .updated {
                margin-left: 0 !important;
                margin-right: 0 !important;
                max-width: none !important;
            }
            
            .woodash-fullscreen #woodash-dashboard {
                max-width: none !important;
                width: 100% !important;
            }
            
            /* Style the toggle button */
            #woodash-sidebar-toggle {
                background: linear-gradient(135deg, #00CC61 0%, #00b357 100%) !important;
                border: none !important;
                color: white !important;
                padding: 10px 16px !important;
                border-radius: 6px !important;
                cursor: pointer !important;
                font-size: 13px !important;
                font-weight: 500 !important;
                box-shadow: 0 2px 4px rgba(0, 204, 97, 0.2) !important;
                transition: all 0.3s ease !important;
                z-index: 9999 !important;
            }
            
            #woodash-sidebar-toggle:hover {
                background: linear-gradient(135deg, #00b357 0%, #009945 100%) !important;
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 8px rgba(0, 204, 97, 0.3) !important;
            }
            
            #woodash-sidebar-toggle i {
                margin-right: 6px !important;
            }
        ');

        // Add JavaScript to toggle fullscreen mode
        wp_add_inline_script('woodash-pro-admin', '
            jQuery(document).ready(function($) {
                // Add fullscreen class to body
                $("body").addClass("woodash-fullscreen");
                
                
                // Add keyboard shortcut (Ctrl/Cmd + B to toggle sidebar)
                $(document).keydown(function(e) {
                    if ((e.ctrlKey || e.metaKey) && e.keyCode === 66) { // B key
                        e.preventDefault();
                        $("#woodash-sidebar-toggle").click();
                    }
                });
                
                // Add tooltip to the toggle button
                $("#woodash-sidebar-toggle").hover(
                    function() {
                        var title = $(this).attr("title");
                        $(this).attr("data-original-title", title);
                    }
                );
            });
        ');

        wp_localize_script('woodash-pro-admin', 'woodashData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('woodash_nonce'),
            'restUrl' => rest_url('woodash/v1/'),
            'restNonce' => wp_create_nonce('wp_rest'),
            'autoRefresh' => 30,
            'debug' => defined('WP_DEBUG') && WP_DEBUG,
            'jqueryLoaded' => true // Flag to indicate jQuery should be loaded
        ));
    }

    public function get_dashboard_data() {
        check_ajax_referer('woodash_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        try {
            // Get date range from request
            $date_from = isset($_POST['date_from']) ? sanitize_text_field($_POST['date_from']) : date('Y-m-d', strtotime('-30 days'));
            $date_to = isset($_POST['date_to']) ? sanitize_text_field($_POST['date_to']) : date('Y-m-d');

            // Get basic stats
            $total_orders = count(wc_get_orders(array(
                'date_created' => $date_from . '...' . $date_to,
                'return' => 'ids'
            )));

            $total_revenue = $this->get_total_revenue_in_range($date_from, $date_to);
            $aov = $total_orders > 0 ? $total_revenue / $total_orders : 0;

            // Get sales overview data for chart
            $sales_overview = $this->get_sales_overview($date_from, $date_to);

            // Get top products
            $top_products = $this->get_top_products(5, $date_from, $date_to);

            // Get top customers
            $top_customers = $this->get_top_customers(5, $date_from, $date_to);

            // Get processing orders count
            $processing_orders = count(wc_get_orders(array(
                'status' => 'wc-processing',
                'date_created' => $date_from . '...' . $date_to,
                'return' => 'ids'
            )));

            $data = array(
                'total_sales' => $total_revenue,
                'total_orders' => $total_orders,
                'aov' => $aov,
                'new_customers' => $this->get_new_customers_count($date_from, $date_to),
                'processing_orders' => $processing_orders,
                'sales_overview' => $sales_overview,
                'top_products' => $top_products,
                'top_customers' => $top_customers,
                'sales_trend' => $this->get_sales_trend(),
                'orders_trend' => $this->get_orders_trend(),
                'aov_trend' => $this->get_aov_trend(),
                'customers_trend' => $this->get_customers_trend()
            );

            wp_send_json_success($data);

        } catch (Exception $e) {
            wp_send_json_error('Error fetching dashboard data: ' . $e->getMessage());
        }
    }

    private function get_total_revenue_in_range($date_from, $date_to) {
        global $wpdb;

        $result = $wpdb->get_var($wpdb->prepare("
            SELECT SUM(meta_value)
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key = '_order_total'
            AND p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND DATE(p.post_date) BETWEEN %s AND %s
        ", $date_from, $date_to));

        return $result ? floatval($result) : 0;
    }

    private function get_sales_overview($date_from, $date_to) {
        global $wpdb;

        $days = array();
        $current = strtotime($date_from);
        $end = strtotime($date_to);

        while ($current <= $end) {
            $days[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        $labels = array();
        $data = array();

        foreach ($days as $day) {
            $labels[] = date('M j', strtotime($day));

            $revenue = $wpdb->get_var($wpdb->prepare("
                SELECT SUM(meta_value)
                FROM {$wpdb->postmeta} pm
                INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                WHERE pm.meta_key = '_order_total'
                AND p.post_type = 'shop_order'
                AND p.post_status IN ('wc-completed', 'wc-processing')
                AND DATE(p.post_date) = %s
            ", $day));

            $data[] = $revenue ? floatval($revenue) : 0;
        }

        return array(
            'labels' => $labels,
            'data' => $data
        );
    }

    private function get_top_products($limit = 5, $date_from = null, $date_to = null) {
        global $wpdb;

        $date_condition = '';
        if ($date_from && $date_to) {
            $date_condition = $wpdb->prepare("AND DATE(p.post_date) BETWEEN %s AND %s", $date_from, $date_to);
        }

        $results = $wpdb->get_results($wpdb->prepare("
            SELECT
                p.ID as product_id,
                p.post_title as name,
                SUM(woim.meta_value) as sales,
                SUM(woim2.meta_value) as revenue
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->prefix}woocommerce_order_items woi ON p.ID = woi.order_id
            INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta woim ON woi.order_item_id = woim.order_item_id
            INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta woim2 ON woi.order_item_id = woim2.order_item_id
            INNER JOIN {$wpdb->posts} order_post ON woi.order_id = order_post.ID
            WHERE p.post_type = 'product'
            AND woim.meta_key = '_qty'
            AND woim2.meta_key = '_line_total'
            AND order_post.post_status IN ('wc-completed', 'wc-processing')
            {$date_condition}
            GROUP BY p.ID
            ORDER BY sales DESC
            LIMIT %d
        ", $limit));

        $products = array();
        foreach ($results as $result) {
            $products[] = array(
                'id' => $result->product_id,
                'name' => $result->name,
                'sales' => intval($result->sales),
                'revenue' => floatval($result->revenue)
            );
        }

        return $products;
    }

    private function get_top_customers($limit = 5, $date_from = null, $date_to = null) {
        global $wpdb;

        $date_condition = '';
        if ($date_from && $date_to) {
            $date_condition = $wpdb->prepare("AND DATE(p.post_date) BETWEEN %s AND %s", $date_from, $date_to);
        }

        $results = $wpdb->get_results($wpdb->prepare("
            SELECT
                pm.meta_value as customer_id,
                COUNT(DISTINCT p.ID) as orders,
                SUM(pm2.meta_value) as total_spent,
                u.display_name as name
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id
            LEFT JOIN {$wpdb->users} u ON pm.meta_value = u.ID
            WHERE p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND pm.meta_key = '_customer_user'
            AND pm2.meta_key = '_order_total'
            {$date_condition}
            GROUP BY pm.meta_value
            ORDER BY total_spent DESC
            LIMIT %d
        ", $limit));

        $customers = array();
        foreach ($results as $result) {
            $customers[] = array(
                'id' => $result->customer_id,
                'name' => $result->name ?: 'Guest',
                'orders' => intval($result->orders),
                'total_spent' => floatval($result->total_spent)
            );
        }

        return $customers;
    }

    private function get_new_customers_count($date_from, $date_to) {
        global $wpdb;

        $result = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(DISTINCT u.ID)
            FROM {$wpdb->users} u
            INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
            WHERE um.meta_key = 'first_order_date'
            AND DATE(um.meta_value) BETWEEN %s AND %s
        ", $date_from, $date_to));

        return $result ? intval($result) : 0;
    }

    private function get_sales_trend() {
        // Generate sample trend data for the last 12 periods
        $labels = array();
        $values = array();

        for ($i = 11; $i >= 0; $i--) {
            $labels[] = date('M', strtotime("-$i months"));
            $values[] = rand(1000, 5000);
        }

        return array('labels' => $labels, 'values' => $values);
    }

    private function get_orders_trend() {
        $labels = array();
        $values = array();

        for ($i = 11; $i >= 0; $i--) {
            $labels[] = date('M', strtotime("-$i months"));
            $values[] = rand(10, 100);
        }

        return array('labels' => $labels, 'values' => $values);
    }

    private function get_aov_trend() {
        $labels = array();
        $values = array();

        for ($i = 11; $i >= 0; $i--) {
            $labels[] = date('M', strtotime("-$i months"));
            $values[] = rand(50, 200);
        }

        return array('labels' => $labels, 'values' => $values);
    }

    private function get_customers_trend() {
        $labels = array();
        $values = array();

        for ($i = 11; $i >= 0; $i--) {
            $labels[] = date('M', strtotime("-$i months"));
            $values[] = rand(5, 50);
        }

        return array('labels' => $labels, 'values' => $values);
    }
    
    // Additional AJAX handlers for dashboard functionality
    public function get_dashboard_stats() {
        // Check nonce for security
        if (!check_ajax_referer('woodash_nonce', 'nonce', false)) {
            wp_send_json_error('Invalid nonce');
            return;
        }
        
        try {
            $stats = array(
                'total_revenue' => $this->get_total_revenue(),
                'total_orders' => $this->get_total_orders(),
                'pending_orders' => $this->get_pending_orders(),
                'processing_orders' => $this->get_processing_orders(),
                'completed_orders' => $this->get_completed_orders(),
                'cancelled_orders' => $this->get_cancelled_orders(),
                'monthly_revenue' => $this->get_monthly_revenue(),
                'sales_chart_data' => $this->get_sales_overview(date('Y-m-01'), date('Y-m-d')),
                'recent_orders' => $this->get_recent_orders(),
                'top_products' => $this->get_top_products(5)
            );
            
            wp_send_json_success($stats);
        } catch (Exception $e) {
            wp_send_json_error('Error fetching dashboard stats: ' . $e->getMessage());
        }
    }
    
    public function get_notifications() {
        // Check nonce for security
        if (!check_ajax_referer('woodash_nonce', 'nonce', false)) {
            wp_send_json_error('Invalid nonce');
            return;
        }
        
        // Mock notifications for now
        $notifications = array(
            array(
                'id' => 1,
                'title' => 'New Order',
                'message' => 'Order #1234 has been placed',
                'type' => 'order',
                'priority' => 'high',
                'time' => current_time('timestamp'),
                'read' => false
            ),
            array(
                'id' => 2,
                'title' => 'Low Stock Alert',
                'message' => 'Product "Wireless Headphones" is running low on stock',
                'type' => 'alert',
                'priority' => 'medium',
                'time' => current_time('timestamp') - 3600,
                'read' => false
            ),
            array(
                'id' => 3,
                'title' => 'New Review',
                'message' => 'A customer left a 5-star review for "Gaming Mouse"',
                'type' => 'review',
                'priority' => 'low',
                'time' => current_time('timestamp') - 7200,
                'read' => true
            )
        );
        
        $unread_count = count(array_filter($notifications, function($n) { return !$n['read']; }));
        
        wp_send_json_success(array(
            'notifications' => $notifications,
            'unread_count' => $unread_count
        ));
    }
    
    public function mark_notification_read() {
        // Check nonce for security
        if (!check_ajax_referer('woodash_nonce', 'nonce', false)) {
            wp_send_json_error('Invalid nonce');
            return;
        }
        
        $notification_id = intval($_POST['notification_id']);
        
        // In a real implementation, this would update the database
        wp_send_json_success(array('message' => 'Notification marked as read'));
    }
    
    public function save_notification_settings() {
        // Check nonce for security
        if (!check_ajax_referer('woodash_nonce', 'nonce', false)) {
            wp_send_json_error('Invalid nonce');
            return;
        }
        
        $settings = wp_unslash($_POST['settings']);
        
        // Save to WordPress options
        update_option('woodash_notification_settings', $settings);
        
        wp_send_json_success(array('message' => 'Settings saved successfully'));
    }
    
    public function get_notification_settings() {
        // Check nonce for security
        if (!check_ajax_referer('woodash_nonce', 'nonce', false)) {
            wp_send_json_error('Invalid nonce');
            return;
        }
        
        $default_settings = array(
            'soundEnabled' => true,
            'desktopEnabled' => true,
            'emailDigest' => false,
            'categories' => array(
                'order' => true,
                'alert' => true,
                'review' => true,
                'system' => true,
                'marketing' => false
            )
        );
        
        $settings = get_option('woodash_notification_settings', $default_settings);
        
        wp_send_json_success($settings);
    }
    
    // Helper methods for statistics
    private function get_total_revenue() {
        global $wpdb;
        $result = $wpdb->get_var("
            SELECT SUM(meta_value)
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key = '_order_total'
            AND p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
        ");
        return $result ? floatval($result) : 0;
    }
    
    private function get_total_orders() {
        global $wpdb;
        $result = $wpdb->get_var("
            SELECT COUNT(*)
            FROM {$wpdb->posts}
            WHERE post_type = 'shop_order'
            AND post_status IN ('wc-completed', 'wc-processing', 'wc-pending', 'wc-on-hold')
        ");
        return $result ? intval($result) : 0;
    }
    
    private function get_pending_orders() {
        global $wpdb;
        $result = $wpdb->get_var("
            SELECT COUNT(*)
            FROM {$wpdb->posts}
            WHERE post_type = 'shop_order'
            AND post_status = 'wc-pending'
        ");
        return $result ? intval($result) : 0;
    }
    
    private function get_processing_orders() {
        global $wpdb;
        $result = $wpdb->get_var("
            SELECT COUNT(*)
            FROM {$wpdb->posts}
            WHERE post_type = 'shop_order'
            AND post_status = 'wc-processing'
        ");
        return $result ? intval($result) : 0;
    }
    
    private function get_completed_orders() {
        global $wpdb;
        $result = $wpdb->get_var("
            SELECT COUNT(*)
            FROM {$wpdb->posts}
            WHERE post_type = 'shop_order'
            AND post_status = 'wc-completed'
        ");
        return $result ? intval($result) : 0;
    }
    
    private function get_cancelled_orders() {
        global $wpdb;
        $result = $wpdb->get_var("
            SELECT COUNT(*)
            FROM {$wpdb->posts}
            WHERE post_type = 'shop_order'
            AND post_status = 'wc-cancelled'
        ");
        return $result ? intval($result) : 0;
    }
    
    private function get_monthly_revenue() {
        global $wpdb;
        $result = $wpdb->get_var($wpdb->prepare("
            SELECT SUM(meta_value)
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key = '_order_total'
            AND p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing')
            AND MONTH(p.post_date) = %d
            AND YEAR(p.post_date) = %d
        ", date('n'), date('Y')));
        return $result ? floatval($result) : 0;
    }
    
    private function get_recent_orders() {
        global $wpdb;
        $orders = $wpdb->get_results("
            SELECT p.ID, p.post_date, pm1.meta_value as total
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_order_total'
            WHERE p.post_type = 'shop_order'
            AND p.post_status IN ('wc-completed', 'wc-processing', 'wc-pending', 'wc-on-hold')
            ORDER BY p.post_date DESC
            LIMIT 10
        ");
        
        return $orders ? $orders : array();
    }
    
    public function activate() {
        // Activation tasks
        flush_rewrite_rules();
    }

    public function deactivate() {
        // Deactivation tasks
        flush_rewrite_rules();
    }

    public static function uninstall() {
        // Uninstall tasks
        // Remove plugin options, tables, etc.
    }

    public function woocommerce_missing_notice() {
        ?>
        <div class="error">
            <p><?php _e('WooDash Pro requires WooCommerce to be installed and active.', 'woodash-pro'); ?></p>
        </div>
        <?php
    }
}
