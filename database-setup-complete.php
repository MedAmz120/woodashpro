<?php
/**
 * WooDash Pro Database Setup - Complete and Ready
 * This script sets up the database automatically when WordPress loads
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

// Auto-setup function
function woodash_complete_database_setup() {
    // Check if already set up
    if (get_option('woodash_auto_setup_complete', false)) {
        return;
    }
    
    // Only run for admin users
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Load database class
    $database_file = WOODASH_PRO_PLUGIN_DIR . 'includes/class-woodash-database.php';
    if (!file_exists($database_file)) {
        return;
    }
    
    require_once $database_file;
    
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
            woodash_create_complete_sample_data($db);
            
            // Mark setup as complete
            update_option('woodash_auto_setup_complete', true);
            
            // Show success message
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible" style="border-left: 4px solid #10B981;">';
                echo '<p><strong>🚀 WooDash Pro Database Setup Complete!</strong></p>';
                echo '<p>✅ All database tables created successfully<br>';
                echo '✅ Sample data populated<br>';
                echo '✅ WooDash Pro connected and ready to use</p>';
                echo '<p><a href="' . admin_url('admin.php?page=woodash-pro') . '" class="button button-primary">Go to WooDash Pro Dashboard</a></p>';
                echo '</div>';
            });
        }
    } catch (Exception $e) {
        add_action('admin_notices', function() use ($e) {
            echo '<div class="notice notice-error">';
            echo '<p><strong>WooDash Pro Setup Error:</strong> ' . esc_html($e->getMessage()) . '</p>';
            echo '</div>';
        });
    }
}

// Create comprehensive sample data
function woodash_create_complete_sample_data($db) {
    $user_id = get_current_user_id();
    
    // Sample notifications with variety
    $notifications = array(
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
                array('label' => 'View Order', 'action' => 'viewOrder', 'primary' => true),
                array('label' => 'Process', 'action' => 'processOrder', 'primary' => false)
            )),
            'metadata' => json_encode(array('order_id' => 1, 'customer' => 'John Doe')),
            'is_read' => 0
        ),
        array(
            'user_id' => $user_id,
            'type' => 'alert',
            'priority' => 'medium',
            'title' => 'Low Stock Alert',
            'message' => 'Product "Premium T-Shirt" is running low - Only 3 items left',
            'icon' => 'fa-exclamation-triangle',
            'color' => '#F59E0B',
            'bg_color' => '#F59E0B',
            'actions' => json_encode(array(
                array('label' => 'Restock Now', 'action' => 'restock', 'primary' => true),
                array('label' => 'View Product', 'action' => 'viewProduct', 'primary' => false)
            )),
            'metadata' => json_encode(array('product_id' => 1, 'stock_level' => 3)),
            'is_read' => 0
        ),
        array(
            'user_id' => $user_id,
            'type' => 'review',
            'priority' => 'low',
            'title' => 'New 5-Star Review',
            'message' => '⭐⭐⭐⭐⭐ "Excellent quality and fast shipping!" - Jane Smith',
            'icon' => 'fa-star',
            'color' => '#3B82F6',
            'bg_color' => '#3B82F6',
            'actions' => json_encode(array(
                array('label' => 'View Review', 'action' => 'viewReview', 'primary' => true),
                array('label' => 'Reply', 'action' => 'replyReview', 'primary' => false)
            )),
            'metadata' => json_encode(array('review_id' => 1, 'rating' => 5, 'product' => 'Premium T-Shirt')),
            'is_read' => 0
        ),
        array(
            'user_id' => $user_id,
            'type' => 'system',
            'priority' => 'high',
            'title' => 'Security Alert',
            'message' => 'New login from unusual location detected',
            'icon' => 'fa-shield-alt',
            'color' => '#EF4444',
            'bg_color' => '#EF4444',
            'actions' => json_encode(array(
                array('label' => 'Review Activity', 'action' => 'reviewActivity', 'primary' => true),
                array('label' => 'Secure Account', 'action' => 'secureAccount', 'primary' => false)
            )),
            'metadata' => json_encode(array('ip' => '192.168.1.100', 'location' => 'Unknown')),
            'is_read' => 0
        ),
        array(
            'user_id' => $user_id,
            'type' => 'marketing',
            'priority' => 'medium',
            'title' => 'Campaign Performance',
            'message' => 'Black Friday campaign achieved 25% conversion rate!',
            'icon' => 'fa-chart-line',
            'color' => '#8B5CF6',
            'bg_color' => '#8B5CF6',
            'actions' => json_encode(array(
                array('label' => 'View Report', 'action' => 'viewReport', 'primary' => true)
            )),
            'metadata' => json_encode(array('campaign_id' => 2, 'conversion_rate' => 25)),
            'is_read' => 1
        )
    );
    
    foreach ($notifications as $notification) {
        $db->insert('notifications', $notification);
    }
    
    // Sample marketing campaigns
    $campaigns = array(
        array(
            'name' => 'Welcome Email Series',
            'type' => 'email',
            'status' => 'active',
            'subject' => 'Welcome to our store! Here\'s 10% off',
            'content' => 'Thank you for joining us. Use code WELCOME10 for 10% off your first order.',
            'target_audience' => json_encode(array('new_customers' => true)),
            'settings' => json_encode(array('send_time' => '09:00', 'frequency' => 'immediate')),
            'clicks' => 156,
            'opens' => 423,
            'conversions' => 34,
            'revenue' => 1250.75,
            'started_at' => date('Y-m-d H:i:s', strtotime('-30 days'))
        ),
        array(
            'name' => 'Black Friday Mega Sale',
            'type' => 'email',
            'status' => 'completed',
            'subject' => 'BLACK FRIDAY: 50% OFF Everything!',
            'content' => 'Don\'t miss our biggest sale of the year! 50% off sitewide with code BLACKFRIDAY50',
            'target_audience' => json_encode(array('all_customers' => true)),
            'settings' => json_encode(array('send_time' => '08:00', 'frequency' => 'one_time')),
            'clicks' => 892,
            'opens' => 2341,
            'conversions' => 234,
            'revenue' => 15670.25,
            'started_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
            'ended_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
        ),
        array(
            'name' => 'Product Launch: Summer Collection',
            'type' => 'social',
            'status' => 'draft',
            'subject' => 'New Summer Collection Launch',
            'content' => 'Introducing our brand new summer collection with fresh designs and premium materials.',
            'target_audience' => json_encode(array('engaged_customers' => true)),
            'settings' => json_encode(array('platforms' => array('facebook', 'instagram', 'twitter'))),
            'clicks' => 0,
            'opens' => 0,
            'conversions' => 0,
            'revenue' => 0.00
        ),
        array(
            'name' => 'Customer Win-Back Campaign',
            'type' => 'email',
            'status' => 'active',
            'subject' => 'We miss you! Come back for 20% off',
            'content' => 'It\'s been a while since your last order. Here\'s 20% off to welcome you back!',
            'target_audience' => json_encode(array('inactive_customers' => true, 'days_since_purchase' => 90)),
            'settings' => json_encode(array('send_time' => '14:00', 'frequency' => 'weekly')),
            'clicks' => 67,
            'opens' => 189,
            'conversions' => 12,
            'revenue' => 456.80,
            'started_at' => date('Y-m-d H:i:s', strtotime('-14 days'))
        )
    );
    
    foreach ($campaigns as $campaign) {
        $db->insert('campaigns', $campaign);
    }
    
    // Sample analytics data for the last 7 days
    $analytics = array();
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-{$i} days"));
        
        // Daily sales metrics
        $analytics[] = array(
            'date' => $date,
            'metric_type' => 'sales',
            'metric_key' => 'total_revenue',
            'metric_value' => rand(800, 2500) + (rand(0, 99) / 100),
            'metadata' => json_encode(array('currency' => 'USD', 'source' => 'woocommerce'))
        );
        
        $analytics[] = array(
            'date' => $date,
            'metric_type' => 'orders',
            'metric_key' => 'total_orders',
            'metric_value' => rand(8, 35),
            'metadata' => json_encode(array('status' => 'all'))
        );
        
        $analytics[] = array(
            'date' => $date,
            'metric_type' => 'customers',
            'metric_key' => 'new_customers',
            'metric_value' => rand(2, 12),
            'metadata' => json_encode(array('source' => 'organic'))
        );
        
        $analytics[] = array(
            'date' => $date,
            'metric_type' => 'traffic',
            'metric_key' => 'page_views',
            'metric_value' => rand(150, 500),
            'metadata' => json_encode(array('source' => 'all'))
        );
        
        $analytics[] = array(
            'date' => $date,
            'metric_type' => 'conversion',
            'metric_key' => 'conversion_rate',
            'metric_value' => rand(200, 450) / 100, // 2.00% to 4.50%
            'metadata' => json_encode(array('type' => 'purchase'))
        );
    }
    
    foreach ($analytics as $analytic) {
        $db->insert('analytics', $analytic);
    }
    
    // Sample settings
    $settings = array(
        array(
            'user_id' => $user_id,
            'setting_key' => 'dashboard_layout',
            'setting_value' => json_encode(array('widgets' => array('sales', 'orders', 'customers', 'notifications'))),
            'setting_type' => 'json',
            'is_global' => 0
        ),
        array(
            'user_id' => null,
            'setting_key' => 'notification_settings',
            'setting_value' => json_encode(array('email_enabled' => true, 'sound_enabled' => true, 'desktop_enabled' => true)),
            'setting_type' => 'json',
            'is_global' => 1
        ),
        array(
            'user_id' => null,
            'setting_key' => 'marketing_automation',
            'setting_value' => json_encode(array('welcome_series' => true, 'abandoned_cart' => true, 'win_back' => true)),
            'setting_type' => 'json',
            'is_global' => 1
        )
    );
    
    foreach ($settings as $setting) {
        $db->insert('settings', $setting);
    }
}

// Hook the setup function
add_action('admin_init', 'woodash_complete_database_setup', 1);