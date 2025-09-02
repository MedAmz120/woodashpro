<?php
/*
Plugin Name: WooDash Pro
Plugin URI: https://woodashpro.com
Description: Advanced WooCommerce analytics dashboard with real-time insights, customizable reports, and modern UI.
Version: 2.0.0
Author: WooDash Team
Author URI: https://woodashpro.com
Text Domain: woodash-pro
Domain Path: /languages
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
WC requires at least: 5.0
WC tested up to: 8.5
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Network: false
*/

// Add this to your main plugin file (not in the template)

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

// Define plugin constants
define('WOODASH_PRO_VERSION', '2.0.0');
define('WOODASH_PRO_PLUGIN_FILE', __FILE__);
define('WOODASH_PRO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WOODASH_PRO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WOODASH_PRO_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include the main class using __DIR__ for better compatibility
$class_file = __DIR__ . '/includes/class-woodash-pro.php';
if (file_exists($class_file)) {
    require_once $class_file;
} else {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>WoodDash Pro: Main class file not found at: ' . esc_html(__DIR__ . '/includes/class-woodash-pro.php') . '</p></div>';
    });
    return;
}

// Check if class was loaded successfully
if (!class_exists('WoodashPro')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>WoodDash Pro: Main class could not be loaded.</p></div>';
    });
    return;
}

// Initialize the plugin
WoodashPro::get_instance();

// Declare WooCommerce HPOS compatibility
add_action('before_woocommerce_init', function() {
    if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});