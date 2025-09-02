<?php
// Simulate the WordPress admin page load for WoodDash Pro
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set up WordPress environment
define('WP_USE_THEMES', false);
require_once dirname(__FILE__) . '/wp-config.php';

// Set up admin environment
define('WP_ADMIN', true);
require_once ABSPATH . 'wp-admin/includes/admin.php';

// Set current user to admin
wp_set_current_user(1);

// Now load the plugin
echo "Loading WoodDash Pro plugin...\n";

$plugin_file = ABSPATH . 'wp-content/plugins/WoodDash Pro/woodash-pro.php';
if (file_exists($plugin_file)) {
    include_once $plugin_file;
    echo "Plugin loaded successfully\n";
    
    if (class_exists('WoodashPro')) {
        echo "WoodashPro class exists\n";
        
        $instance = WoodashPro::get_instance();
        
        // Test the admin page
        echo "Testing admin page...\n";
        ob_start();
        $instance->admin_page();
        $output = ob_get_contents();
        ob_end_clean();
        
        echo "Admin page executed successfully\n";
        echo "Output length: " . strlen($output) . " bytes\n";
        
        if (strlen($output) > 0) {
            echo "Page content generated successfully\n";
        } else {
            echo "Warning: No content generated\n";
        }
        
    } else {
        echo "Error: WoodashPro class not found\n";
    }
} else {
    echo "Error: Plugin file not found\n";
}

echo "Test completed\n";
?>
