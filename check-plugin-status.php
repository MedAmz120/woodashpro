<?php
// Check plugin activation status
require_once dirname(__FILE__) . '/wp-config.php';
require_once ABSPATH . 'wp-blog-header.php';

// Get active plugins
$active_plugins = get_option('active_plugins', array());
$plugin_path = 'WoodDash Pro/woodash-pro.php';

echo "Checking plugin activation status...\n";
echo "Plugin path: $plugin_path\n";
echo "Active plugins:\n";
foreach ($active_plugins as $plugin) {
    echo "  - $plugin\n";
}

if (in_array($plugin_path, $active_plugins)) {
    echo "\nPlugin is ACTIVE\n";
} else {
    echo "\nPlugin is NOT ACTIVE\n";
    echo "Activating plugin...\n";
    
    // Activate the plugin
    $result = activate_plugin($plugin_path);
    if (is_wp_error($result)) {
        echo "Error activating plugin: " . $result->get_error_message() . "\n";
    } else {
        echo "Plugin activated successfully\n";
    }
}

// Check if menu exists
echo "\nChecking admin menu...\n";
global $menu, $submenu;

if (isset($menu)) {
    foreach ($menu as $menu_item) {
        if (isset($menu_item[2]) && $menu_item[2] === 'woodash-pro') {
            echo "WoodDash Pro menu found: " . $menu_item[0] . "\n";
            break;
        }
    }
}
?>
