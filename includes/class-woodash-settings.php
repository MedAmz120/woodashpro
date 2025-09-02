<?php
/**
 * Settings class for WoodDash Pro
 *
 * @package WoodashPro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

class Woodash_Settings {
    
    public function __construct() {
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_woodash_save_settings', array($this, 'ajax_save_settings'));
    }
    
    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('woodash_settings_group', 'woodash_cache_enabled');
        register_setting('woodash_settings_group', 'woodash_log_level');
        register_setting('woodash_settings_group', 'woodash_auto_refresh');
        register_setting('woodash_settings_group', 'woodash_date_format');
        register_setting('woodash_settings_group', 'woodash_currency_position');
        register_setting('woodash_settings_group', 'woodash_chart_colors');
    }
    
    /**
     * Get default settings
     *
     * @return array
     */
    public function get_defaults() {
        return array(
            'cache_enabled' => true,
            'log_level' => 'info',
            'auto_refresh' => 30,
            'date_format' => 'Y-m-d',
            'currency_position' => 'left',
            'chart_colors' => woodash_get_chart_colors()
        );
    }
    
    /**
     * Get all settings
     *
     * @return array
     */
    public function get_settings() {
        $defaults = $this->get_defaults();
        $settings = array();
        
        foreach ($defaults as $key => $default) {
            $settings[$key] = get_option('woodash_' . $key, $default);
        }
        
        return $settings;
    }
    
    /**
     * AJAX handler for saving settings
     */
    public function ajax_save_settings() {
        check_ajax_referer('woodash_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'woodash-pro'), 403);
        }
        
        $settings = $_POST['settings'] ?? array();
        $updated = array();
        
        foreach ($settings as $key => $value) {
            $option_name = 'woodash_' . sanitize_key($key);
            $sanitized_value = $this->sanitize_setting($key, $value);
            
            if (update_option($option_name, $sanitized_value)) {
                $updated[] = $key;
            }
        }
        
        wp_send_json_success(array(
            'message' => __('Settings saved successfully', 'woodash-pro'),
            'updated' => $updated
        ));
    }
    
    /**
     * Sanitize setting value
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    private function sanitize_setting($key, $value) {
        switch ($key) {
            case 'cache_enabled':
                return (bool) $value;
            case 'log_level':
                return in_array($value, array('debug', 'info', 'warning', 'error')) ? $value : 'info';
            case 'auto_refresh':
                return max(10, min(300, intval($value))); // Between 10 seconds and 5 minutes
            case 'date_format':
                return sanitize_text_field($value);
            case 'currency_position':
                return in_array($value, array('left', 'right')) ? $value : 'left';
            case 'chart_colors':
                return is_array($value) ? array_map('sanitize_hex_color', $value) : woodash_get_chart_colors();
            default:
                return sanitize_text_field($value);
        }
    }
}
