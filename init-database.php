<?php
/**
 * WooDash Pro Database Initialization
 * Simple script to initialize the database
 */

// Load WordPress
require_once('../../../wp-config.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Unauthorized access');
}

// Load the database class
require_once('includes/class-woodash-database.php');

echo "<h1>WooDash Pro Database Initialization</h1>";

try {
    // Initialize database
    $db = WoodashDatabase::get_instance();
    
    echo "<p>Creating database tables...</p>";
    $result = $db->create_tables();
    
    if ($result) {
        echo "<p style='color: green;'>✅ Database tables created successfully!</p>";
        
        // Set WooDash Pro as connected
        update_option('woodash_connected', true);
        update_option('woodash_store_id', 'local_' . time());
        update_option('woodash_api_key', wp_generate_password(32, false));
        
        echo "<p style='color: green;'>✅ WooDash Pro activated and connected!</p>";
        
        // Show table statistics
        $stats = $db->get_stats();
        echo "<h3>Database Statistics:</h3>";
        echo "<ul>";
        foreach ($stats as $table => $count) {
            echo "<li><strong>{$table}:</strong> {$count} records</li>";
        }
        echo "</ul>";
        
        echo "<p><a href='" . admin_url('admin.php?page=woodash-pro') . "' style='background: #0073aa; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px;'>Go to WooDash Pro Dashboard</a></p>";
        
    } else {
        echo "<p style='color: red;'>❌ Failed to create database tables!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='setup-database.php'>← Back to Database Setup</a></p>";
?>