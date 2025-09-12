<?php
/**
 * Plugin Name: WooDash Pro
 * Plugin URI: https://woodashpro.com
 * Description: Modern WooCommerce analytics dashboard with advanced reporting and real-time insights.
 * Version: 1.0.0
 * Author: Mercodev
 * Author URI: https://mercodev.com
 * Text Domain: woodashpro
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.3
 * Requires PHP: 7.4
 * WC requires at least: 4.0
 * WC tested up to: 8.0
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Network: false
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WOODASHPRO_VERSION', '1.0.0');
define('WOODASHPRO_PLUGIN_FILE', __FILE__);
define('WOODASHPRO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WOODASHPRO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WOODASHPRO_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Check for WooCommerce dependency
add_action('admin_init', 'woodashpro_check_dependencies');

function woodashpro_check_dependencies() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', 'woodashpro_woocommerce_missing_notice');
        deactivate_plugins(WOODASHPRO_PLUGIN_BASENAME);
        return;
    }
}

function woodashpro_woocommerce_missing_notice() {
    echo '<div class="notice notice-error"><p><strong>' . 
         esc_html__('WooDash Pro requires WooCommerce to be installed and activated.', 'woodashpro') . 
         '</strong></p></div>';
}

// Initialize the plugin
add_action('plugins_loaded', 'woodashpro_init');

function woodashpro_init() {
    // Load text domain for translations
    load_plugin_textdomain('woodashpro', false, dirname(WOODASHPRO_PLUGIN_BASENAME) . '/languages');
    
    // Load the main loader class
    require_once WOODASHPRO_PLUGIN_DIR . 'includes/class-woodash-loader.php';
    
    // Initialize the plugin
    new WooDash_Loader();
}

// Activation hook
register_activation_hook(__FILE__, 'woodashpro_activate');

function woodashpro_activate() {
    // Check PHP version
    if (version_compare(PHP_VERSION, '7.4', '<')) {
        deactivate_plugins(WOODASHPRO_PLUGIN_BASENAME);
        wp_die(
            esc_html__('WooDash Pro requires PHP 7.4 or higher. Your current PHP version is ', 'woodashpro') . PHP_VERSION,
            esc_html__('Plugin Activation Error', 'woodashpro'),
            array('back_link' => true)
        );
    }
    
    // Check WordPress version
    if (version_compare(get_bloginfo('version'), '5.0', '<')) {
        deactivate_plugins(WOODASHPRO_PLUGIN_BASENAME);
        wp_die(
            esc_html__('WooDash Pro requires WordPress 5.0 or higher.', 'woodashpro'),
            esc_html__('Plugin Activation Error', 'woodashpro'),
            array('back_link' => true)
        );
    }
    
    // Set default options
    add_option('woodashpro_version', WOODASHPRO_VERSION);
    add_option('woodashpro_activated_time', time());
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'woodashpro_deactivate');

function woodashpro_deactivate() {
    // Cleanup tasks
    flush_rewrite_rules();
}

// Uninstall hook
register_uninstall_hook(__FILE__, 'woodashpro_uninstall');

function woodashpro_uninstall() {
    // Include uninstall file
    include_once WOODASHPRO_PLUGIN_DIR . 'uninstall.php';
}
