<?php
// Set up WordPress environment
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-config.php';

// Test path resolution
echo "Plugin dir: " . plugin_dir_path(__FILE__) . "\n";
echo "Class file path: " . plugin_dir_path(__FILE__) . 'includes/class-woodash-pro.php' . "\n";
echo "File exists: " . (file_exists(plugin_dir_path(__FILE__) . 'includes/class-woodash-pro.php') ? 'Yes' : 'No') . "\n";

// List the includes directory
$includes_dir = plugin_dir_path(__FILE__) . 'includes/';
if (is_dir($includes_dir)) {
    echo "Includes directory contents:\n";
    $files = scandir($includes_dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "  - $file\n";
        }
    }
} else {
    echo "Includes directory does not exist\n";
}
?>
