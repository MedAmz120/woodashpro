<?php
// Debug WordPress admin page loading
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Starting WordPress admin test...\n";

// Set up WordPress environment manually
define('WP_USE_THEMES', false);
require_once dirname(__FILE__) . '/wp-config.php';
require_once dirname(__FILE__) . '/wp-blog-header.php';

echo "WordPress loaded successfully\n";

// Test admin functions
require_once ABSPATH . 'wp-admin/includes/admin.php';
echo "Admin functions loaded\n";

// Set current user to admin (needed for menu access)
wp_set_current_user(1);
echo "User set to admin\n";

// Test plugin loading
$plugin_file = ABSPATH . 'wp-content/plugins/WoodDash Pro/woodash-pro.php';
if (file_exists($plugin_file)) {
    echo "Including plugin file...\n";
    include_once $plugin_file;
    
    if (class_exists('WoodashPro')) {
        echo "WoodashPro class exists\n";
        
        $instance = WoodashPro::get_instance();
        echo "Plugin instance created\n";
        
        // Test admin page method
        if (method_exists($instance, 'admin_page')) {
            echo "admin_page method exists\n";
            
            // Check if template file exists
            $template_file = plugin_dir_path($plugin_file) . 'templates/dashboard.php';
            if (file_exists($template_file)) {
                echo "Template file exists: $template_file\n";
                
                // Try to include the template
                ob_start();
                try {
                    include $template_file;
                    $output = ob_get_contents();
                    echo "Template included successfully, output length: " . strlen($output) . " bytes\n";
                } catch (Exception $e) {
                    echo "Error including template: " . $e->getMessage() . "\n";
                } catch (Error $e) {
                    echo "Fatal error including template: " . $e->getMessage() . "\n";
                }
                ob_end_clean();
            } else {
                echo "Template file does not exist\n";
            }
        } else {
            echo "admin_page method does not exist\n";
        }
    } else {
        echo "WoodashPro class not found\n";
    }
} else {
    echo "Plugin file not found\n";
}

echo "Test completed\n";
?>
