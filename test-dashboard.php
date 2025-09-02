<?php
/**
 * WooDash Pro Dashboard Test Page
 * Simple interface to generate test data and access dashboard
 */

// Load WordPress
require_once('../../../../../wp-config.php');

// Security check
if (!current_user_can('manage_options')) {
    wp_die('Unauthorized access. Please login as an administrator.');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WooDash Pro - Test Dashboard</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #00CC61 0%, #00b357 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 1.1em;
        }
        .content {
            padding: 40px;
        }
        .card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .card h3 {
            margin: 0 0 15px 0;
            color: #2d3748;
            font-size: 1.3em;
        }
        .card p {
            color: #4a5568;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }
        .btn {
            display: inline-block;
            background: #00CC61;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1em;
        }
        .btn:hover {
            background: #00b357;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 204, 97, 0.3);
        }
        .btn-secondary {
            background: #4a5568;
        }
        .btn-secondary:hover {
            background: #2d3748;
        }
        .btn-danger {
            background: #e53e3e;
        }
        .btn-danger:hover {
            background: #c53030;
        }
        .status {
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .status-info {
            background: #ebf8ff;
            border-left: 4px solid #3182ce;
            color: #2c5282;
        }
        .status-success {
            background: #f0fff4;
            border-left: 4px solid #38a169;
            color: #276749;
        }
        .status-warning {
            background: #fffbeb;
            border-left: 4px solid #d69e2e;
            color: #744210;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .feature-list li:before {
            content: "✅";
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 WooDash Pro</h1>
            <p>Test Dashboard & Data Generator</p>
        </div>
        
        <div class="content">
            <?php
            global $wpdb;
            
            // Check database status
            $notifications_table = $wpdb->prefix . 'woodash_notifications';
            $campaigns_table = $wpdb->prefix . 'woodash_campaigns';
            
            $notifications_exist = $wpdb->get_var("SHOW TABLES LIKE '$notifications_table'");
            $campaigns_exist = $wpdb->get_var("SHOW TABLES LIKE '$campaigns_table'");
            
            $notifications_count = 0;
            $campaigns_count = 0;
            $products_count = 0;
            $orders_count = 0;
            
            if ($notifications_exist) {
                $notifications_count = $wpdb->get_var("SELECT COUNT(*) FROM $notifications_table");
            }
            
            if ($campaigns_exist) {
                $campaigns_count = $wpdb->get_var("SELECT COUNT(*) FROM $campaigns_table");
            }
            
            // Check WooCommerce data
            if (class_exists('WooCommerce')) {
                $products_count = wp_count_posts('product')->publish;
                $orders_count = wp_count_posts('shop_order')->{'wc-completed'} + wp_count_posts('shop_order')->{'wc-processing'};
            }
            ?>
            
            <div class="status status-info">
                <h3>📊 Current Database Status</h3>
                <ul class="feature-list">
                    <li><strong><?php echo $notifications_count; ?></strong> Notifications in database</li>
                    <li><strong><?php echo $campaigns_count; ?></strong> Marketing campaigns in database</li>
                    <li><strong><?php echo $products_count; ?></strong> Products in WooCommerce</li>
                    <li><strong><?php echo $orders_count; ?></strong> Orders in WooCommerce</li>
                </ul>
            </div>
            
            <div class="grid">
                <div class="card">
                    <h3>🎯 Generate Test Data</h3>
                    <p>Create comprehensive test data including products, customers, orders, notifications, and marketing campaigns to fully test the dashboard functionality.</p>
                    <p><strong>This will create:</strong></p>
                    <ul class="feature-list">
                        <li>6 Test Products with different categories</li>
                        <li>5 Test Customers with contact info</li>
                        <li>15 Test Orders with various statuses</li>
                        <li>6 Test Notifications (different types)</li>
                        <li>4 Test Marketing Campaigns</li>
                    </ul>
                    <a href="create-test-data.php" class="btn">🚀 Generate Test Data</a>
                </div>
                
                <div class="card">
                    <h3>📊 Access Dashboard</h3>
                    <p>Open the WooDash Pro dashboard to see your data in action. The dashboard will display real-time analytics, notifications, and comprehensive store insights.</p>
                    <p><strong>Dashboard Features:</strong></p>
                    <ul class="feature-list">
                        <li>Real-time sales analytics</li>
                        <li>Interactive notifications system</li>
                        <li>Product & customer management</li>
                        <li>Marketing campaign tracking</li>
                        <li>Advanced reporting tools</li>
                    </ul>
                    <a href="<?php echo admin_url('admin.php?page=woodash-pro'); ?>" class="btn">📊 Open Dashboard</a>
                </div>
            </div>
            
            <div class="card">
                <h3>🔧 Quick Actions</h3>
                <p>Additional tools and shortcuts for testing and managing your WooDash Pro installation.</p>
                <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 20px;">
                    <a href="<?php echo admin_url('admin.php?page=woodash-pro-orders'); ?>" class="btn btn-secondary">📦 View Orders</a>
                    <a href="<?php echo admin_url('admin.php?page=woodash-pro-products'); ?>" class="btn btn-secondary">🛍️ View Products</a>
                    <a href="<?php echo admin_url('admin.php?page=woodash-pro-customers'); ?>" class="btn btn-secondary">👥 View Customers</a>
                    <a href="<?php echo admin_url('admin.php?page=woodash-pro-marketing'); ?>" class="btn btn-secondary">📧 Marketing</a>
                    <a href="<?php echo admin_url('admin.php?page=woodash-pro-analytics'); ?>" class="btn btn-secondary">📈 Analytics</a>
                </div>
            </div>
            
            <?php if (class_exists('WooCommerce')): ?>
                <div class="status status-success">
                    <h3>✅ WooCommerce Integration Active</h3>
                    <p>WooCommerce is properly installed and active. WooDash Pro can access all store data and functionality.</p>
                </div>
            <?php else: ?>
                <div class="status status-warning">
                    <h3>⚠️ WooCommerce Required</h3>
                    <p>WooCommerce plugin is required for WooDash Pro to function properly. Please install and activate WooCommerce first.</p>
                    <a href="<?php echo admin_url('plugin-install.php?s=woocommerce&tab=search&type=term'); ?>" class="btn">Install WooCommerce</a>
                </div>
            <?php endif; ?>
            
            <div class="status status-info">
                <h3>💡 Testing Tips</h3>
                <ul>
                    <li><strong>Notifications:</strong> Click the green notifications button in the dashboard to see real database notifications</li>
                    <li><strong>Analytics:</strong> Charts and metrics will populate with your test order data</li>
                    <li><strong>Real-time Updates:</strong> Create new orders in WooCommerce to see live notifications</li>
                    <li><strong>Marketing:</strong> View campaign performance and analytics in the Marketing section</li>
                    <li><strong>Responsive Design:</strong> Test the dashboard on different screen sizes</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>