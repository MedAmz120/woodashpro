<?php
/**
 * Test script to check plugin activation issues
 */

// Include WordPress if running standalone
if (!defined('ABSPATH')) {
    require_once(dirname(__FILE__) . '/../../../wp-config.php');
}

echo "Testing WooDash Pro Plugin...\n";

// Test if file can be parsed
echo "1. PHP Syntax Check: ";
$syntax_check = shell_exec('C:\xampp1\php\php.exe -l "' . __DIR__ . '/woodash-pro.php" 2>&1');
if (strpos($syntax_check, 'No syntax errors') !== false) {
    echo "PASSED\n";
} else {
    echo "FAILED\n";
    echo "Error: " . $syntax_check . "\n";
}

// Test WooCommerce availability
echo "2. WooCommerce Check: ";
if (class_exists('WooCommerce')) {
    echo "PASSED - WooCommerce is active\n";
} else {
    echo "FAILED - WooCommerce is not installed/active\n";
}

// Test required WordPress functions
echo "3. WordPress Functions Check: ";
$required_functions = ['wp_create_nonce', 'add_action', 'add_menu_page', 'current_user_can'];
$missing_functions = [];
foreach ($required_functions as $func) {
    if (!function_exists($func)) {
        $missing_functions[] = $func;
    }
}
if (empty($missing_functions)) {
    echo "PASSED\n";
} else {
    echo "FAILED - Missing functions: " . implode(', ', $missing_functions) . "\n";
}

// Test file permissions
echo "4. File Permissions Check: ";
if (is_readable(__DIR__ . '/woodash-pro.php')) {
    echo "PASSED\n";
} else {
    echo "FAILED - Cannot read main plugin file\n";
}

// Test memory limit
echo "5. Memory Limit Check: ";
$memory_limit = ini_get('memory_limit');
echo "Current: " . $memory_limit . "\n";

echo "\nTest completed.\n";
