<?php

/**
 * The main plugin class.
 */
class Woodashh {

    /**
     * Initialize the plugin.
     */
    public function __construct() {
        $this->load_dependencies();
        $this->define_admin_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     */
    private function load_dependencies() {
        // Load any required files here
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     */
    private function define_admin_hooks() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Add admin styles and scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_admin_styles() {
        wp_enqueue_style(
            'woodashh-admin',
            WOODASH_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            WOODASH_VERSION,
            'all'
        );
    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_admin_scripts() {
        wp_enqueue_script(
            'woodashh-admin',
            WOODASH_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            WOODASH_VERSION,
            false
        );
    }

    /**
     * Add admin menu pages.
     */
    public function add_admin_menu() {
        add_menu_page(
            'WooDash Pro',
            'WooDash Pro',
            'manage_woocommerce',
            'woodashh-dashboard',
            array($this, 'render_dashboard_page'),
            'dashicons-chart-line',
            55
        );

        add_submenu_page(
            'woodashh-dashboard',
            'Dashboard',
            'Dashboard',
            'manage_woocommerce',
            'woodashh-dashboard',
            array($this, 'render_dashboard_page')
        );

        add_submenu_page(
            'woodashh-dashboard',
            'Products',
            'Products',
            'manage_woocommerce',
            'woodashh-products',
            array($this, 'render_products_page')
        );

        add_submenu_page(
            'woodashh-dashboard',
            'Customers',
            'Customers',
            'manage_woocommerce',
            'woodashh-customers',
            array($this, 'render_customers_page')
        );

        add_submenu_page(
            'woodashh-dashboard',
            'Stock',
            'Stock',
            'manage_woocommerce',
            'woodashh-stock',
            array($this, 'render_stock_page')
        );

        add_submenu_page(
            'woodashh-dashboard',
            'Reviews',
            'Reviews',
            'moderate_comments',
            'woodashh-reviews',
            array($this, 'render_reviews_page')
        );

        add_submenu_page(
            'woodashh-dashboard',
            'Coupons',
            'Coupons',
            'manage_woocommerce',
            'woodashh-coupons',
            array($this, 'render_coupons_page')
        );

        add_submenu_page(
            'woodashh-dashboard',
            'Settings',
            'Settings',
            'manage_woocommerce',
            'woodashh-settings',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Render the dashboard page.
     */
    public function render_dashboard_page() {
        require_once WOODASH_PLUGIN_DIR . 'templates/dashboard.php';
    }

    /**
     * Render the products page.
     */
    public function render_products_page() {
        require_once WOODASH_PLUGIN_DIR . 'templates/products.php';
    }

    /**
     * Render the customers page.
     */
    public function render_customers_page() {
        require_once WOODASH_PLUGIN_DIR . 'templates/customers.php';
    }

    /**
     * Render the stock page.
     */
    public function render_stock_page() {
        require_once WOODASH_PLUGIN_DIR . 'templates/stock.php';
    }

    /**
     * Render the reviews page.
     */
    public function render_reviews_page() {
        require_once WOODASH_PLUGIN_DIR . 'templates/reviews.php';
    }

    /**
     * Render the coupons page.
     */
    public function render_coupons_page() {
        require_once WOODASH_PLUGIN_DIR . 'templates/coupons.php';
    }

    /**
     * Render the settings page.
     */
    public function render_settings_page() {
        require_once WOODASH_PLUGIN_DIR . 'templates/settings.php';
    }

    /**
     * Run the plugin.
     */
    public function run() {
        // Plugin initialization code
    }
} 