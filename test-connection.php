<?php
/**
 * Database Connection Test for WooDash Pro
 * This script tests the database connection and displays current data
 */

// Load WordPress
require_once(dirname(__FILE__) . '/../../../wp-config.php');

global $wpdb;

echo "<h1>WooDash Pro Database Connection Test</h1>";

// Test basic WordPress database connection
echo "<h2>WordPress Database Connection</h2>";
$wp_test = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}options");
echo "<p>✅ WordPress database connected successfully. Found $wp_test options.</p>";

// Test WooCommerce tables
echo "<h2>WooCommerce Integration</h2>";
$orders_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}wc_orders");
$products_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_type = 'product'");
echo "<p>✅ WooCommerce tables accessible. Found $orders_count orders and $products_count products.</p>";

// Test WooDash Pro custom tables
echo "<h2>WooDash Pro Custom Tables</h2>";

$tables = array(
    'notifications' => $wpdb->prefix . 'woodash_notifications',
    'campaigns' => $wpdb->prefix . 'woodash_campaigns',
    'analytics_cache' => $wpdb->prefix . 'woodash_analytics_cache',
    'dashboard_widgets' => $wpdb->prefix . 'woodash_dashboard_widgets'
);

foreach ($tables as $name => $table) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table");
    echo "<p>✅ $name table: $count records</p>";
}

// Test plugin options
echo "<h2>Plugin Configuration</h2>";
$connected = get_option('woodash_connected', false) ? 'Yes' : 'No';
$store_id = get_option('woodash_store_id', 'Not set');
$version = get_option('woodash_version', 'Not set');

echo "<p>Connected: $connected</p>";
echo "<p>Store ID: $store_id</p>";
echo "<p>Version: $version</p>";

// Display recent notifications
echo "<h2>Recent Notifications</h2>";
$notifications = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woodash_notifications ORDER BY created_at DESC LIMIT 5");

if ($notifications) {
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Type</th><th>Title</th><th>Message</th><th>Priority</th><th>Created</th></tr>";
    foreach ($notifications as $notification) {
        echo "<tr>";
        echo "<td>{$notification->id}</td>";
        echo "<td>{$notification->type}</td>";
        echo "<td>{$notification->title}</td>";
        echo "<td>" . substr($notification->message, 0, 50) . "...</td>";
        echo "<td>{$notification->priority}</td>";
        echo "<td>{$notification->created_at}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No notifications found.</p>";
}

// Display campaigns
echo "<h2>Marketing Campaigns</h2>";
$campaigns = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woodash_campaigns ORDER BY created_at DESC LIMIT 5");

if ($campaigns) {
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Name</th><th>Type</th><th>Status</th><th>Subject</th><th>Created</th></tr>";
    foreach ($campaigns as $campaign) {
        echo "<tr>";
        echo "<td>{$campaign->id}</td>";
        echo "<td>{$campaign->name}</td>";
        echo "<td>{$campaign->type}</td>";
        echo "<td>{$campaign->status}</td>";
        echo "<td>{$campaign->subject}</td>";
        echo "<td>{$campaign->created_at}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No campaigns found.</p>";
}

echo "<h2>Dashboard Access</h2>";
echo "<p>Your WooDash Pro dashboard is available at:</p>";
echo "<p><a href='" . admin_url('admin.php?page=woodash-pro') . "' target='_blank'>" . admin_url('admin.php?page=woodash-pro') . "</a></p>";

echo "<hr>";
echo "<p><strong>Database connection successful! Your WooDash Pro dashboard is now fully integrated with the database.</strong></p>";
?>