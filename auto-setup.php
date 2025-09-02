<?php
/**
 * WooDash Pro Auto Setup
 * This file will automatically set up the database when accessed via WordPress admin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

// Load the database class
require_once(WOODASH_PRO_PLUGIN_DIR . 'includes/class-woodash-database.php');

/**
 * Auto-setup WooDash Pro database and connection
 */
function woodash_auto_setup() {
    // Check if already set up
    $is_setup = get_option('woodash_auto_setup_complete', false);
    if ($is_setup) {
        return true;
    }
    
    try {
        // Initialize database
        $db = WoodashDatabase::get_instance();
        
        // Create all tables
        $result = $db->create_tables();
        
        if ($result) {
            // Set WooDash Pro as connected
            update_option('woodash_connected', true);
            update_option('woodash_store_id', 'local_' . time());
            update_option('woodash_api_key', wp_generate_password(32, false));
            
            // Create sample data
            woodash_create_sample_data($db);
            
            // Mark setup as complete
            update_option('woodash_auto_setup_complete', true);
            
            // Add admin notice
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible">';
                echo '<p><strong>WooDash Pro:</strong> Database setup completed successfully! ✅</p>';
                echo '</div>';
            });
            
            return true;
        }
    } catch (Exception $e) {
        // Add error notice
        add_action('admin_notices', function() use ($e) {
            echo '<div class="notice notice-error is-dismissible">';
            echo '<p><strong>WooDash Pro Error:</strong> ' . $e->getMessage() . '</p>';
            echo '</div>';
        });
        
        return false;
    }
    
    return false;
}

/**
 * Create sample data for testing
 */
function woodash_create_sample_data($db) {
    $user_id = get_current_user_id();
    
    // Sample notifications
    $sample_notifications = array(
        array(
            'user_id' => $user_id,
            'type' => 'order',
            'priority' => 'high',
            'title' => 'New Order Received',
            'message' => 'Order #WD-001 from John Doe - $299.99',
            'icon' => 'fa-shopping-cart',
            'color' => '#10B981',
            'bg_color' => '#10B981',
            'actions' => json_encode(array(
                array('label' => 'View Order', 'action' => 'viewOrder', 'primary' => true)
            )),
            'metadata' => json_encode(array('order_id' => 1)),
            'is_read' => 0
        ),
        array(
            'user_id' => $user_id,
            'type' => 'alert',
            'priority' => 'medium',
            'title' => 'Low Stock Alert',
            'message' => 'Product "Sample T-Shirt" is running low - Only 3 items left',
            'icon' => 'fa-exclamation-triangle',
            'color' => '#F59E0B',
            'bg_color' => '#F59E0B',
            'actions' => json_encode(array(
                array('label' => 'Restock', 'action' => 'restock', 'primary' => true)
            )),
            'metadata' => json_encode(array('product_id' => 1)),
            'is_read' => 0
        ),
        array(
            'user_id' => $user_id,
            'type' => 'review',
            'priority' => 'low',
            'title' => 'New Customer Review',
            'message' => '⭐⭐⭐⭐⭐ "Great product!" - Jane Smith',
            'icon' => 'fa-star',
            'color' => '#3B82F6',
            'bg_color' => '#3B82F6',
            'actions' => json_encode(array(
                array('label' => 'View Review', 'action' => 'viewReview', 'primary' => true)
            )),
            'metadata' => json_encode(array('review_id' => 1)),
            'is_read' => 0
        )
    );
    
    foreach ($sample_notifications as $notification) {
        $db->insert('notifications', $notification);
    }
    
    // Sample campaigns
    $sample_campaigns = array(
        array(
            'name' => 'Welcome Email Campaign',
            'type' => 'email',
            'status' => 'active',
            'subject' => 'Welcome to our store!',
            'content' => 'Thank you for joining us. Here\'s a 10% discount code: WELCOME10',
            'clicks' => 45,
            'opens' => 120,
            'conversions' => 8,
            'revenue' => 450.00
        ),
        array(
            'name' => 'Black Friday Sale',
            'type' => 'email',
            'status' => 'completed',
            'subject' => 'Black Friday - 50% OFF Everything!',
            'content' => 'Don\'t miss our biggest sale of the year!',
            'clicks' => 234,
            'opens' => 890,
            'conversions' => 67,
            'revenue' => 12450.00
        ),
        array(
            'name' => 'Product Launch',
            'type' => 'social',
            'status' => 'draft',
            'subject' => 'New Product Launch',
            'content' => 'Introducing our latest product line...',
            'clicks' => 0,
            'opens' => 0,
            'conversions' => 0,
            'revenue' => 0.00
        )
    );
    
    foreach ($sample_campaigns as $campaign) {
        $db->insert('campaigns', $campaign);
    }
    
    // Sample analytics data
    $sample_analytics = array(
        array(
            'date' => date('Y-m-d'),
            'metric_type' => 'sales',
            'metric_key' => 'total_revenue',
            'metric_value' => 1250.50,
            'metadata' => json_encode(array('currency' => 'USD'))
        ),
        array(
            'date' => date('Y-m-d'),
            'metric_type' => 'orders',
            'metric_key' => 'total_orders',
            'metric_value' => 15,
            'metadata' => json_encode(array('status' => 'completed'))
        ),
        array(
            'date' => date('Y-m-d'),
            'metric_type' => 'customers',
            'metric_key' => 'new_customers',
            'metric_value' => 8,
            'metadata' => json_encode(array('source' => 'organic'))
        )
    );
    
    foreach ($sample_analytics as $analytic) {
        $db->insert('analytics', $analytic);
    }
}

// Run auto-setup when WordPress admin is loaded
if (is_admin()) {
    add_action('admin_init', 'woodash_auto_setup');
}