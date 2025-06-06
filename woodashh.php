<?php
/**
 * Plugin Name: WooDash Pro
 * Plugin URI: https://woodashpro.com
 * Description: A powerful dashboard for WooCommerce store management
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * Text Domain: woodashh
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('WOODASH_VERSION', '1.0.0');
define('WOODASH_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WOODASH_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once WOODASH_PLUGIN_DIR . 'includes/class-woodashh.php';

// Initialize the plugin
function run_woodashh() {
    $plugin = new Woodashh();
    $plugin->run();
}

// Hook into WordPress
add_action('plugins_loaded', 'run_woodashh');

// Activation hook
register_activation_hook(__FILE__, 'woodashh_activate');
function woodashh_activate() {
    // Activation tasks
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'woodashh_deactivate');
function woodashh_deactivate() {
    // Deactivation tasks
    flush_rewrite_rules();
}