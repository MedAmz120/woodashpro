<?php
// Test if the WoodDash Pro plugin is working
require_once 'wp-config.php';
require_once 'wp-blog-header.php';

echo "WordPress loaded: " . (defined('ABSPATH') ? 'Yes' : 'No') . "\n";
echo "WooCommerce active: " . (class_exists('WooCommerce') ? 'Yes' : 'No') . "\n";

// Test if our plugin class exists
$plugin_file = ABSPATH . 'wp-content/plugins/WoodDash Pro/woodash-pro.php';
if (file_exists($plugin_file)) {
    echo "Plugin file exists: Yes\n";
    include_once $plugin_file;
    echo "WoodashPro class exists: " . (class_exists('WoodashPro') ? 'Yes' : 'No') . "\n";
} else {
    echo "Plugin file exists: No\n";
}
?>
