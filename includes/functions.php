<?php
/**
 * Helper functions for WoodDash Pro
 *
 * @package WoodashPro
 */

wp_enqueue_script(
    'chartjs',
    'https://cdn.jsdelivr.net/npm/chart.js',
    array(),
    null,
    true
);

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Logging helper function
 *
 * @param string $message
 * @param array $context
 */
if (!function_exists('woodash_log')) {
    function woodash_log($message, $context = array()) {
        if (class_exists('Woodash_Logger')) {
            Woodash_Logger::log($message, $context);
        } else {
            // Fallback to error_log
            $log_message = '[WoodDash Pro] ' . $message;
            if (!empty($context)) {
                $log_message .= ' Context: ' . wp_json_encode($context);
            }
            error_log($log_message);
        }
    }
}

/**
 * Check if current page is a WoodDash Pro page
 *
 * @return bool
 */
function woodash_is_plugin_page() {
    if (!is_admin()) {
        return false;
    }
    
    $screen = get_current_screen();
    if (!$screen) {
        return false;
    }
    
    return strpos($screen->id, 'woodash') !== false;
}

/**
 * Get formatted currency amount
 *
 * @param float $amount
 * @return string
 */
function woodash_format_currency($amount) {
    return wc_price($amount);
}

/**
 * Get date range for analytics
 *
 * @param string $range
 * @return array
 */
function woodash_get_date_range($range = 'today') {
    $today = new DateTime();
    $start = clone $today;
    $end = clone $today;
    
    switch ($range) {
        case 'yesterday':
            $start->modify('-1 day');
            $end->modify('-1 day');
            break;
        case 'last7days':
            $start->modify('-6 days');
            break;
        case 'last30days':
            $start->modify('-29 days');
            break;
        case 'thismonth':
            $start->modify('first day of this month');
            break;
        case 'lastmonth':
            $start->modify('first day of last month');
            $end->modify('last day of last month');
            break;
        case 'thisyear':
            $start->modify('first day of January');
            break;
        case 'lastyear':
            $start->modify('first day of January last year');
            $end->modify('last day of December last year');
            break;
        default: // today
            break;
    }
    
    return array(
        'start' => $start->format('Y-m-d H:i:s'),
        'end' => $end->format('Y-m-d 23:59:59')
    );
}

/**
 * Get WooCommerce order statuses
 *
 * @return array
 */
function woodash_get_order_statuses() {
    return wc_get_order_statuses();
}

/**
 * Check if user has WoodDash Pro capabilities
 *
 * @param int $user_id
 * @return bool
 */
function woodash_user_can_access($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    return user_can($user_id, 'manage_options');
}

/**
 * Get plugin option with default
 *
 * @param string $option_name
 * @param mixed $default
 * @return mixed
 */
function woodash_get_option($option_name, $default = false) {
    return get_option('woodash_' . $option_name, $default);
}

/**
 * Update plugin option
 *
 * @param string $option_name
 * @param mixed $value
 * @return bool
 */
function woodash_update_option($option_name, $value) {
    return update_option('woodash_' . $option_name, $value);
}

// Note: woodash_log function already defined above

/**
 * Get chart colors
 *
 * @return array
 */
function woodash_get_chart_colors() {
    return array(
        'primary' => '#00CC61',
        'secondary' => '#00b357',
        'success' => '#10b981',
        'warning' => '#f59e0b',
        'danger' => '#ef4444',
        'info' => '#3b82f6',
        'light' => '#f8fafc',
        'dark' => '#1e293b'
    );
}

/**
 * Format number for display
 *
 * @param mixed $number
 * @param int $decimals
 * @return string
 */
function woodash_format_number($number, $decimals = 0) {
    if (!is_numeric($number)) {
        return '0';
    }
    
    return number_format((float) $number, $decimals);
}

/**
 * Get percentage change
 *
 * @param float $current
 * @param float $previous
 * @return array
 */
function woodash_get_percentage_change($current, $previous) {
    if ($previous == 0) {
        return array(
            'percentage' => $current > 0 ? 100 : 0,
            'direction' => $current > 0 ? 'up' : 'neutral',
            'formatted' => $current > 0 ? '+100%' : '0%'
        );
    }
    
    $change = (($current - $previous) / $previous) * 100;
    $direction = $change > 0 ? 'up' : ($change < 0 ? 'down' : 'neutral');
    $formatted = ($change > 0 ? '+' : '') . number_format($change, 1) . '%';
    
    return array(
        'percentage' => abs($change),
        'direction' => $direction,
        'formatted' => $formatted
    );
}

/**
 * Sanitize and validate date
 *
 * @param string $date
 * @return string|false
 */
function woodash_sanitize_date($date) {
    $date = sanitize_text_field($date);
    $timestamp = strtotime($date);
    
    if ($timestamp === false) {
        return false;
    }
    
    return date('Y-m-d', $timestamp);
}

/**
 * Get current user preferences
 *
 * @return array
 */
function woodash_get_user_preferences() {
    $user_id = get_current_user_id();
    $defaults = array(
        'default_date_range' => 'last7days',
        'dashboard_widgets' => array('sales', 'orders', 'customers', 'products'),
        'chart_type' => 'line',
        'currency_format' => 'symbol',
        'timezone' => get_option('timezone_string', 'UTC')
    );
    
    $preferences = get_user_meta($user_id, 'woodash_preferences', true);
    if (!is_array($preferences)) {
        $preferences = array();
    }
    
    return wp_parse_args($preferences, $defaults);
}

/**
 * Update user preferences
 *
 * @param array $preferences
 * @return bool
 */
function woodash_update_user_preferences($preferences) {
    $user_id = get_current_user_id();
    $current = woodash_get_user_preferences();
    $updated = wp_parse_args($preferences, $current);
    
    return update_user_meta($user_id, 'woodash_preferences', $updated);
}

/**
 * Check if feature is enabled
 *
 * @param string $feature
 * @return bool
 */
function woodash_is_feature_enabled($feature) {
    $enabled_features = woodash_get_option('enabled_features', array(
        'analytics', 'reports', 'exports', 'notifications'
    ));
    
    return in_array($feature, $enabled_features);
}

/**
 * Get plugin assets URL
 *
 * @param string $path
 * @return string
 */
function woodash_get_asset_url($path = '') {
    return WOODASH_PRO_PLUGIN_URL . 'assets/' . ltrim($path, '/');
}

/**
 * Get template path
 *
 * @param string $template
 * @return string
 */
function woodash_get_template_path($template) {
    return WOODASH_PRO_PLUGIN_DIR . 'templates/' . $template . '.php';
}

/**
 * Include template with variables
 *
 * @param string $template
 * @param array $vars
 */
function woodash_include_template($template, $vars = array()) {
    $template_path = woodash_get_template_path($template);
    
    if (file_exists($template_path)) {
        // Extract variables to template scope
        if (!empty($vars) && is_array($vars)) {
            extract($vars, EXTR_SKIP);
        }
        include $template_path;
    } else {
        echo '<div class="notice notice-error"><p>' . 
             sprintf(__('Template not found: %s', 'woodash-pro'), esc_html($template)) . 
             '</p></div>';
    }
}

/**
 * Get nonce field for AJAX requests
 *
 * @param string $action
 * @return string
 */
function woodash_get_nonce_field($action = 'woodash_nonce') {
    return wp_nonce_field($action, 'nonce', true, false);
}

/**
 * Verify nonce for AJAX requests
 *
 * @param string $action
 * @return bool
 */
function woodash_verify_nonce($action = 'woodash_nonce') {
    $nonce = $_REQUEST['nonce'] ?? '';
    return wp_verify_nonce($nonce, $action);
}

/**
 * Get safe redirect URL
 *
 * @param string $url
 * @param string $fallback
 * @return string
 */
function woodash_get_safe_redirect($url, $fallback = '') {
    if (empty($fallback)) {
        $fallback = admin_url('admin.php?page=woodash-pro');
    }
    
    return wp_validate_redirect($url, $fallback);
}
