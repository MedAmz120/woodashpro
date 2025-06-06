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
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('WOODASH_VERSION', '1.0.0');
define('WOODASH_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WOODASH_PLUGIN_URL', plugin_dir_url(__FILE__));

// Check if WooCommerce is active
function woodashh_check_woocommerce() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', 'woodashh_woocommerce_notice');
        return false;
    }
    return true;
}

// WooCommerce missing notice
function woodashh_woocommerce_notice() {
    ?>
    <div class="error">
        <p><?php _e('WooDash Pro requires WooCommerce to be installed and activated.', 'woodashh'); ?></p>
    </div>
    <?php
}

// Include required files
if (woodashh_check_woocommerce()) {
    require_once WOODASH_PLUGIN_DIR . 'includes/class-woodashh.php';
}

// Initialize the plugin
function run_woodashh() {
    if (woodashh_check_woocommerce()) {
        $plugin = new Woodashh();
        $plugin->run();
    }
}

// Hook into WordPress
add_action('plugins_loaded', 'run_woodashh');

// Activation hook
register_activation_hook(__FILE__, 'woodashh_activate');
function woodashh_activate() {
    // Check if WooCommerce is active
    if (!woodashh_check_woocommerce()) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(__('WooDash Pro requires WooCommerce to be installed and activated.', 'woodashh'));
    }
    
    // Create necessary directories
    $dirs = array(
        WOODASH_PLUGIN_DIR . 'assets',
        WOODASH_PLUGIN_DIR . 'assets/css',
        WOODASH_PLUGIN_DIR . 'assets/js',
    );
    
    foreach ($dirs as $dir) {
        if (!file_exists($dir)) {
            wp_mkdir_p($dir);
        }
    }
    
    // Activation tasks
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'woodashh_deactivate');
function woodashh_deactivate() {
    // Deactivation tasks
    flush_rewrite_rules();
}