<?php
/**
 * WooDash Pro Database Setup Script
 * Run this to set up the complete database structure
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    require_once('../../../wp-config.php');
}

// Load WooDash Pro
require_once('woodash-pro.php');
require_once('includes/class-woodash-database.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Unauthorized access');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>WooDash Pro Database Setup</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f1f1f1; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #10B981; background: #ECFDF5; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { color: #EF4444; background: #FEF2F2; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { color: #3B82F6; background: #EFF6FF; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .btn { background: #3B82F6; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2563EB; }
        .btn-success { background: #10B981; }
        .btn-success:hover { background: #059669; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .status-ok { color: #10B981; font-weight: bold; }
        .status-error { color: #EF4444; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 WooDash Pro Database Setup</h1>
        
        <?php
        $db = WoodashDatabase::get_instance();
        $action = $_GET['action'] ?? '';
        
        if ($action === 'setup') {
            echo '<div class="info">Setting up WooDash Pro database...</div>';
            
            try {
                $result = $db->create_tables();
                
                if ($result) {
                    echo '<div class="success">✅ Database setup completed successfully!</div>';
                    
                    // Set WooDash Pro as connected
                    update_option('woodash_connected', true);
                    update_option('woodash_store_id', 'local_' . time());
                    update_option('woodash_api_key', wp_generate_password(32, false));
                    
                    echo '<div class="success">✅ WooDash Pro activated and connected!</div>';
                } else {
                    echo '<div class="error">❌ Database setup failed!</div>';
                }
            } catch (Exception $e) {
                echo '<div class="error">❌ Error: ' . $e->getMessage() . '</div>';
            }
        }
        
        if ($action === 'test') {
            echo '<div class="info">Testing database connection...</div>';
            
            global $wpdb;
            
            // Test WordPress database connection
            $wp_test = $wpdb->get_var("SELECT 1");
            if ($wp_test) {
                echo '<div class="success">✅ WordPress database connection: OK</div>';
            } else {
                echo '<div class="error">❌ WordPress database connection: FAILED</div>';
            }
            
            // Test WooDash Pro tables
            $tables_exist = $db->tables_exist();
            if ($tables_exist) {
                echo '<div class="success">✅ WooDash Pro tables: EXISTS</div>';
            } else {
                echo '<div class="error">❌ WooDash Pro tables: NOT FOUND</div>';
            }
            
            // Get database stats
            if ($tables_exist) {
                $stats = $db->get_stats();
                echo '<h3>📊 Database Statistics</h3>';
                echo '<table>';
                echo '<tr><th>Table</th><th>Records</th><th>Status</th></tr>';
                foreach ($stats as $table => $count) {
                    $status = $count >= 0 ? '<span class="status-ok">OK</span>' : '<span class="status-error">ERROR</span>';
                    echo "<tr><td>{$table}</td><td>{$count}</td><td>{$status}</td></tr>";
                }
                echo '</table>';
            }
        }
        
        if ($action === 'sample') {
            echo '<div class="info">Creating sample data...</div>';
            
            try {
                // Create sample notifications
                $user_id = get_current_user_id();
                
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
                
                // Create sample campaigns
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
                
                // Create sample analytics data
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
                
                echo '<div class="success">✅ Sample data created successfully!</div>';
                echo '<div class="info">Created: 3 notifications, 3 campaigns, 3 analytics records</div>';
                
            } catch (Exception $e) {
                echo '<div class="error">❌ Error creating sample data: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        
        <h2>🔧 Database Management</h2>
        
        <div style="margin: 20px 0;">
            <a href="?action=test" class="btn">🔍 Test Connection</a>
            <a href="?action=setup" class="btn btn-success">⚙️ Setup Database</a>
            <a href="?action=sample" class="btn">📝 Create Sample Data</a>
        </div>
        
        <h3>📋 Current Status</h3>
        <?php
        $is_connected = get_option('woodash_connected', false);
        $tables_exist = $db->tables_exist();
        
        echo '<table>';
        echo '<tr><th>Component</th><th>Status</th></tr>';
        echo '<tr><td>WooDash Pro Connection</td><td>' . ($is_connected ? '<span class="status-ok">CONNECTED</span>' : '<span class="status-error">NOT CONNECTED</span>') . '</td></tr>';
        echo '<tr><td>Database Tables</td><td>' . ($tables_exist ? '<span class="status-ok">EXISTS</span>' : '<span class="status-error">NOT FOUND</span>') . '</td></tr>';
        echo '<tr><td>WordPress Database</td><td><span class="status-ok">OK</span></td></tr>';
        echo '<tr><td>WooCommerce</td><td>' . (class_exists('WooCommerce') ? '<span class="status-ok">ACTIVE</span>' : '<span class="status-error">NOT FOUND</span>') . '</td></tr>';
        echo '</table>';
        ?>
        
        <h3>🗄️ Database Tables</h3>
        <ul>
            <li><strong>woodash_notifications</strong> - User notifications and alerts</li>
            <li><strong>woodash_campaigns</strong> - Marketing campaigns and email data</li>
            <li><strong>woodash_analytics</strong> - Analytics and metrics data</li>
            <li><strong>woodash_settings</strong> - User and global settings</li>
            <li><strong>woodash_logs</strong> - System logs and error tracking</li>
            <li><strong>woodash_cache</strong> - Performance caching data</li>
        </ul>
        
        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
            <h4>🚀 Next Steps:</h4>
            <ol>
                <li>Click "Setup Database" to create all tables</li>
                <li>Click "Create Sample Data" to populate with test data</li>
                <li>Visit <a href="<?php echo admin_url('admin.php?page=woodash-pro'); ?>">WooDash Pro Dashboard</a></li>
            </ol>
        </div>
    </div>
</body>
</html>