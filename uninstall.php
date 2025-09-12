<?php
/**
 * WooDash Pro Uninstall Script
 * 
 * This file is executed when the plugin is deleted via the WordPress admin.
 * It removes all plugin data, options, and database tables.
 * 
 * @package WooDashPro
 * @since 1.0.0
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Define plugin constants for cleanup
define('WOODASHPRO_PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * Remove all plugin options
 */
function woodashpro_remove_options() {
    $options = array(
        'woodash_connected',
        'woodash_store_id',
        'woodash_api_key',
        'woodash_connected_at',
        'woodash_store_name',
        'woodash_email',
        'woodash_license_key',
        'woodash_last_sync',
        'woodashpro_version',
        'woodashpro_settings',
        'woodashpro_cache_analytics',
        'woodashpro_cache_products',
        'woodashpro_cache_orders',
        'woodashpro_cache_customers',
        'woodashpro_notifications_enabled',
        'woodashpro_auto_sync_enabled',
        'woodashpro_sync_interval',
        'woodashpro_export_format',
        'woodashpro_date_format',
        'woodashpro_currency_format',
        'woodashpro_dashboard_widgets',
        'woodashpro_user_roles',
        'woodashpro_api_rate_limit',
        'woodashpro_debug_mode'
    );

    foreach ($options as $option) {
        delete_option($option);
        delete_site_option($option); // For multisite
    }
}

/**
 * Remove user meta data
 */
function woodashpro_remove_user_meta() {
    global $wpdb;

    $user_meta_keys = array(
        'woodash_user_preferences',
        'woodash_dashboard_layout',
        'woodash_notifications_read',
        'woodash_last_login',
        'woodash_widget_settings'
    );

    foreach ($user_meta_keys as $meta_key) {
        $wpdb->delete(
            $wpdb->usermeta,
            array('meta_key' => $meta_key),
            array('%s')
        );
    }
}

/**
 * Remove custom database tables (if any were created)
 */
function woodashpro_remove_custom_tables() {
    global $wpdb;

    // List of custom tables that might have been created
    $tables = array(
        $wpdb->prefix . 'woodash_analytics',
        $wpdb->prefix . 'woodash_exports',
        $wpdb->prefix . 'woodash_logs',
        $wpdb->prefix . 'woodash_cache'
    );

    foreach ($tables as $table) {
        $wpdb->query("DROP TABLE IF EXISTS {$table}");
    }
}

/**
 * Remove uploaded files and cache
 */
function woodashpro_remove_files() {
    $upload_dir = wp_upload_dir();
    $plugin_upload_dir = $upload_dir['basedir'] . '/woodashpro/';
    
    if (is_dir($plugin_upload_dir)) {
        woodashpro_delete_directory($plugin_upload_dir);
    }

    // Remove cache files
    $cache_dir = WP_CONTENT_DIR . '/cache/woodashpro/';
    if (is_dir($cache_dir)) {
        woodashpro_delete_directory($cache_dir);
    }

    // Remove log files
    $log_file = WP_CONTENT_DIR . '/woodash-debug.log';
    if (file_exists($log_file)) {
        unlink($log_file);
    }
}

/**
 * Remove scheduled cron jobs
 */
function woodashpro_remove_cron_jobs() {
    $cron_hooks = array(
        'woodash_sync_data',
        'woodash_cleanup_cache',
        'woodash_send_reports',
        'woodash_backup_data',
        'woodash_check_updates'
    );

    foreach ($cron_hooks as $hook) {
        wp_clear_scheduled_hook($hook);
    }
}

/**
 * Remove capabilities from user roles
 */
function woodashpro_remove_capabilities() {
    $roles = array('administrator', 'shop_manager', 'editor');
    $capabilities = array(
        'manage_woodash',
        'view_woodash_analytics',
        'export_woodash_data',
        'configure_woodash'
    );

    foreach ($roles as $role_name) {
        $role = get_role($role_name);
        if ($role) {
            foreach ($capabilities as $cap) {
                $role->remove_cap($cap);
            }
        }
    }
}

/**
 * Remove transients
 */
function woodashpro_remove_transients() {
    global $wpdb;

    // Remove transients with our prefix
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_woodash_%' OR option_name LIKE '_transient_timeout_woodash_%'");
    
    // For multisite
    if (is_multisite()) {
        $wpdb->query("DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE '_site_transient_woodash_%' OR meta_key LIKE '_site_transient_timeout_woodash_%'");
    }
}

/**
 * Remove custom post types and taxonomies data
 */
function woodashpro_remove_custom_posts() {
    global $wpdb;

    // Remove any custom post types we might have created
    $custom_post_types = array(
        'woodash_report',
        'woodash_export',
        'woodash_notification'
    );

    foreach ($custom_post_types as $post_type) {
        $wpdb->delete($wpdb->posts, array('post_type' => $post_type));
    }

    // Clean up orphaned post meta
    $wpdb->query("DELETE pm FROM {$wpdb->postmeta} pm LEFT JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.ID IS NULL");
}

/**
 * Recursively delete directory
 */
function woodashpro_delete_directory($dir) {
    if (!is_dir($dir)) {
        return false;
    }

    $files = array_diff(scandir($dir), array('.', '..'));
    
    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            woodashpro_delete_directory($path);
        } else {
            unlink($path);
        }
    }
    
    return rmdir($dir);
}

/**
 * Log uninstall activity
 */
function woodashpro_log_uninstall() {
    $log_data = array(
        'timestamp' => current_time('mysql'),
        'site_url' => site_url(),
        'plugin_version' => get_option('woodashpro_version', 'unknown'),
        'wp_version' => get_bloginfo('version'),
        'php_version' => PHP_VERSION
    );

    // Only log if debug logging is enabled
    if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
        error_log('WooDash Pro uninstalled: ' . json_encode($log_data));
    }
}

/**
 * Main uninstall function
 */
function woodashpro_uninstall() {
    // Log the uninstall process
    woodashpro_log_uninstall();

    // Remove all plugin data
    woodashpro_remove_options();
    woodashpro_remove_user_meta();
    woodashpro_remove_custom_tables();
    woodashpro_remove_files();
    woodashpro_remove_cron_jobs();
    woodashpro_remove_capabilities();
    woodashpro_remove_transients();
    woodashpro_remove_custom_posts();

    // Clear any remaining caches
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }

    // Clear object cache if available
    if (function_exists('wp_cache_flush_runtime')) {
        wp_cache_flush_runtime();
    }
}

// Only proceed with uninstall if this is a single site installation
// or if we're on the main site of a multisite network
if (!is_multisite() || is_main_site()) {
    woodashpro_uninstall();
}

// For multisite, handle network-wide uninstall
if (is_multisite() && is_network_admin()) {
    global $wpdb;
    
    // Get all blog IDs
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
    
    foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        woodashpro_uninstall();
        restore_current_blog();
    }
}

// Final cleanup - remove any remaining plugin traces
delete_option('woodashpro_uninstall_flag');
delete_site_option('woodashpro_uninstall_flag');
