<?php
/**
 * WooDash Pro Loader Class
 * 
 * Handles the loading and initialization of all plugin components
 * 
 * @package WooDashPro
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class WooDash_Loader {
    
    /**
     * Plugin version
     * 
     * @var string
     */
    public $version;
    
    /**
     * Admin instance
     * 
     * @var WooDash_Admin
     */
    public $admin;
    
    /**
     * Frontend instance
     * 
     * @var WooDash_Frontend
     */
    public $frontend;
    
    /**
     * API instance
     * 
     * @var WooDash_API
     */
    public $api;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->version = WOODASHPRO_VERSION;
        $this->define_constants();
        $this->include_files();
        $this->init_hooks();
        $this->init_components();
    }
    
    /**
     * Define additional constants
     */
    private function define_constants() {
        // Asset paths
        if (!defined('WOODASHPRO_ASSETS_URL')) {
            define('WOODASHPRO_ASSETS_URL', WOODASHPRO_PLUGIN_URL . 'assets/');
        }
        
        if (!defined('WOODASHPRO_CSS_URL')) {
            define('WOODASHPRO_CSS_URL', WOODASHPRO_PLUGIN_URL . 'css/');
        }
        
        if (!defined('WOODASHPRO_JS_URL')) {
            define('WOODASHPRO_JS_URL', WOODASHPRO_PLUGIN_URL . 'js/');
        }
        
        if (!defined('WOODASHPRO_TEMPLATES_DIR')) {
            define('WOODASHPRO_TEMPLATES_DIR', WOODASHPRO_PLUGIN_DIR . 'templates/');
        }
    }
    
    /**
     * Include required files
     */
    private function include_files() {
        // Helper functions
        require_once WOODASHPRO_PLUGIN_DIR . 'includes/helpers.php';
        
        // Core classes
        require_once WOODASHPRO_PLUGIN_DIR . 'includes/class-woodash-admin.php';
        require_once WOODASHPRO_PLUGIN_DIR . 'includes/class-woodash-frontend.php';
        require_once WOODASHPRO_PLUGIN_DIR . 'includes/class-woodash-api.php';
        
        // Legacy data endpoints (for backward compatibility)
        $legacy_endpoints = WOODASHPRO_PLUGIN_DIR . 'includes/data-endpoints.php';
        if (file_exists($legacy_endpoints)) {
            require_once $legacy_endpoints;
        }
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // Plugin loaded
        add_action('init', array($this, 'init'));
        
        // Scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue_scripts'));
        
        // AJAX hooks (for backward compatibility)
        $this->init_legacy_ajax_hooks();
        
        // Add custom admin styles
        add_action('admin_head', array($this, 'admin_custom_styles'));
    }
    
    /**
     * Initialize plugin components
     */
    private function init_components() {
        // Initialize admin
        if (is_admin()) {
            $this->admin = new WooDash_Admin();
        }
        
        // Initialize frontend
        if (!is_admin() || wp_doing_ajax()) {
            $this->frontend = new WooDash_Frontend();
        }
        
        // Initialize API
        $this->api = new WooDash_API();
    }
    
    /**
     * Initialize the plugin
     */
    public function init() {
        // Load text domain
        $this->load_textdomain();
        
        // Check if user has proper capabilities
        if (is_admin() && !current_user_can('manage_woocommerce')) {
            return;
        }
    }
    
    /**
     * Load plugin text domain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'woodashpro',
            false,
            dirname(WOODASHPRO_PLUGIN_BASENAME) . '/languages/'
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts($hook) {
        // Only load on our plugin pages
        if (!in_array($hook, array('toplevel_page_woodash-pro', 'toplevel_page_woodash-pro-activate'))) {
            return;
        }
        
        // Styles
        wp_enqueue_style(
            'woodashpro-admin',
            WOODASHPRO_CSS_URL . 'admin.css',
            array(),
            $this->version
        );
        
        // Tailwind CSS (keeping for backward compatibility)
        if (file_exists(WOODASHPRO_PLUGIN_DIR . 'assets/css/tailwind.min.css')) {
            wp_enqueue_style(
                'woodashpro-tailwind',
                WOODASHPRO_ASSETS_URL . 'css/tailwind.min.css',
                array(),
                $this->version
            );
        }
        
        // Scripts
        wp_enqueue_script('jquery');
        wp_enqueue_script('wp-api');
        
        // Chart.js
        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js',
            array(),
            '3.9.1',
            true
        );
        
        // Main admin script
        wp_enqueue_script(
            'woodashpro-admin',
            WOODASHPRO_JS_URL . 'admin.js',
            array('jquery', 'chartjs', 'wp-api'),
            $this->version,
            true
        );
        
        // Localize script
        wp_localize_script('woodashpro-admin', 'woodashData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('woodash_nonce'),
            'today' => current_time('Y-m-d'),
            'restUrl' => rest_url('woodash/v1/'),
            'restNonce' => wp_create_nonce('wp_rest'),
            'version' => $this->version,
            'debug' => WP_DEBUG
        ));
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function frontend_enqueue_scripts() {
        // Frontend styles
        wp_enqueue_style(
            'woodashpro-frontend',
            WOODASHPRO_CSS_URL . 'frontend.css',
            array(),
            $this->version
        );
        
        // Frontend scripts
        wp_enqueue_script(
            'woodashpro-frontend',
            WOODASHPRO_JS_URL . 'frontend.js',
            array('jquery'),
            $this->version,
            true
        );
    }
    
    /**
     * Add custom admin styles
     */
    public function admin_custom_styles() {
        ?>
        <style>
            #toplevel_page_woodash-pro { 
                background: #00994a !important; 
                opacity: 1 !important; 
            }
            #toplevel_page_woodash-pro .wp-menu-name, 
            #toplevel_page_woodash-pro .wp-menu-image:before { 
                color: #fff !important; 
            }
            #toplevel_page_woodash-pro:hover, 
            #toplevel_page_woodash-pro.wp-has-current-submenu { 
                background: #007a3d !important; 
            }
        </style>
        <?php
    }
    
    /**
     * Initialize legacy AJAX hooks for backward compatibility
     */
    private function init_legacy_ajax_hooks() {
        // Legacy AJAX actions from the original plugin
        $legacy_actions = array(
            'woodash_get_data',
            'woodash_export_products_csv',
            'woodash_export_customers_csv',
            'woodash_check_subscription',
            'woodash_store_connection',
            'woodash_logout',
            'woodash_debug',
            'woodash_get_processing_count'
        );
        
        foreach ($legacy_actions as $action) {
            if (has_action("wp_ajax_{$action}")) {
                continue; // Already registered in legacy file
            }
            
            // Register in API class
            add_action("wp_ajax_{$action}", array($this->api, 'handle_legacy_ajax'));
        }
    }
    
    /**
     * Get plugin info
     */
    public function get_plugin_info() {
        return array(
            'version' => $this->version,
            'plugin_dir' => WOODASHPRO_PLUGIN_DIR,
            'plugin_url' => WOODASHPRO_PLUGIN_URL,
            'plugin_file' => WOODASHPRO_PLUGIN_FILE
        );
    }
}
