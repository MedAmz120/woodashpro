<?php
/**
 * WooDash Pro Test Data Generator
 * Creates fake content in database to test dashboard functionality
 * 
 * IMPORTANT: Run this only once and in a development environment!
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    // Load WordPress if accessed directly
    require_once('../../../../../wp-config.php');
}

// Security check
if (!current_user_can('manage_options')) {
    wp_die('Unauthorized access');
}

echo "<h1>🚀 WooDash Pro Test Data Generator</h1>";
echo "<div style='max-width: 800px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;'>";

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    echo "<div style='color: red; padding: 10px; border: 1px solid red; margin: 10px 0;'>";
    echo "❌ WooCommerce is not active. Please activate WooCommerce first.";
    echo "</div>";
    exit;
}

global $wpdb;

// Create notifications table if it doesn't exist
$notifications_table = $wpdb->prefix . 'woodash_notifications';
$campaigns_table = $wpdb->prefix . 'woodash_campaigns';

echo "<h2>📊 Creating Database Tables...</h2>";

// Create notifications table
$notifications_sql = "CREATE TABLE IF NOT EXISTS $notifications_table (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    type varchar(50) NOT NULL,
    priority enum('low', 'medium', 'high') DEFAULT 'medium',
    title varchar(255) NOT NULL,
    message text NOT NULL,
    icon varchar(100) DEFAULT 'fa-bell',
    color varchar(50) DEFAULT '#3B82F6',
    bg_color varchar(50) DEFAULT '#3B82F6',
    actions text DEFAULT NULL,
    metadata text DEFAULT NULL,
    is_read tinyint(1) DEFAULT 0,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    read_at datetime DEFAULT NULL,
    PRIMARY KEY (id),
    KEY user_id (user_id),
    KEY type (type),
    KEY is_read (is_read),
    KEY created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

$wpdb->query($notifications_sql);

// Create campaigns table
$campaigns_sql = "CREATE TABLE IF NOT EXISTS $campaigns_table (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    type varchar(50) NOT NULL DEFAULT 'email',
    status varchar(20) NOT NULL DEFAULT 'draft',
    subject varchar(255),
    content longtext,
    target_audience text,
    settings text,
    clicks int(11) DEFAULT 0,
    opens int(11) DEFAULT 0,
    conversions int(11) DEFAULT 0,
    revenue decimal(10,2) DEFAULT 0.00,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    started_at datetime DEFAULT NULL,
    ended_at datetime DEFAULT NULL,
    PRIMARY KEY (id),
    KEY type (type),
    KEY status (status),
    KEY created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

$wpdb->query($campaigns_sql);

echo "<p>✅ Database tables created successfully!</p>";

// Get current user ID
$current_user_id = get_current_user_id();

echo "<h2>🛍️ Creating Test Products...</h2>";

// Create test products
$test_products = [
    [
        'name' => 'Wireless Bluetooth Headphones',
        'price' => 89.99,
        'stock' => 25,
        'category' => 'Electronics',
        'description' => 'High-quality wireless headphones with noise cancellation'
    ],
    [
        'name' => 'Smart Fitness Watch',
        'price' => 199.99,
        'stock' => 15,
        'category' => 'Electronics',
        'description' => 'Advanced fitness tracking with heart rate monitor'
    ],
    [
        'name' => 'Organic Coffee Beans',
        'price' => 24.99,
        'stock' => 50,
        'category' => 'Food & Beverages',
        'description' => 'Premium organic coffee beans from Colombia'
    ],
    [
        'name' => 'Yoga Mat Premium',
        'price' => 45.99,
        'stock' => 8,
        'category' => 'Sports & Fitness',
        'description' => 'Non-slip premium yoga mat for all exercises'
    ],
    [
        'name' => 'Stainless Steel Water Bottle',
        'price' => 29.99,
        'stock' => 3,
        'category' => 'Sports & Fitness',
        'description' => 'Insulated water bottle keeps drinks cold for 24 hours'
    ],
    [
        'name' => 'LED Desk Lamp',
        'price' => 39.99,
        'stock' => 20,
        'category' => 'Home & Office',
        'description' => 'Adjustable LED desk lamp with USB charging port'
    ]
];

$created_products = [];

foreach ($test_products as $product_data) {
    try {
        $product = new WC_Product_Simple();
        $product->set_name($product_data['name']);
        $product->set_regular_price($product_data['price']);
        $product->set_description($product_data['description']);
        $product->set_short_description(substr($product_data['description'], 0, 100) . '...');
        $product->set_manage_stock(true);
        $product->set_stock_quantity($product_data['stock']);
        $product->set_stock_status($product_data['stock'] > 0 ? 'instock' : 'outofstock');
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');
        
        // Create or get category
        $category = get_term_by('name', $product_data['category'], 'product_cat');
        if (!$category) {
            $category_result = wp_insert_term($product_data['category'], 'product_cat');
            if (!is_wp_error($category_result)) {
                $category_id = $category_result['term_id'];
            }
        } else {
            $category_id = $category->term_id;
        }
        
        if (isset($category_id)) {
            $product->set_category_ids(array($category_id));
        }
        
        $product_id = $product->save();
        $created_products[] = $product_id;
        
        echo "<p>✅ Created product: {$product_data['name']} (ID: $product_id)</p>";
        
    } catch (Exception $e) {
        echo "<p>❌ Failed to create product {$product_data['name']}: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>👥 Creating Test Customers...</h2>";

// Create test customers
$test_customers = [
    [
        'first_name' => 'John',
        'last_name' => 'Smith',
        'email' => 'john.smith@example.com',
        'phone' => '+1-555-0101'
    ],
    [
        'first_name' => 'Sarah',
        'last_name' => 'Johnson',
        'email' => 'sarah.johnson@example.com',
        'phone' => '+1-555-0102'
    ],
    [
        'first_name' => 'Mike',
        'last_name' => 'Davis',
        'email' => 'mike.davis@example.com',
        'phone' => '+1-555-0103'
    ],
    [
        'first_name' => 'Emily',
        'last_name' => 'Brown',
        'email' => 'emily.brown@example.com',
        'phone' => '+1-555-0104'
    ],
    [
        'first_name' => 'David',
        'last_name' => 'Wilson',
        'email' => 'david.wilson@example.com',
        'phone' => '+1-555-0105'
    ]
];

$created_customers = [];

foreach ($test_customers as $customer_data) {
    try {
        // Check if email already exists
        if (!email_exists($customer_data['email'])) {
            $customer = new WC_Customer();
            $customer->set_first_name($customer_data['first_name']);
            $customer->set_last_name($customer_data['last_name']);
            $customer->set_email($customer_data['email']);
            $customer->set_billing_phone($customer_data['phone']);
            $customer->set_billing_email($customer_data['email']);
            $customer->set_billing_first_name($customer_data['first_name']);
            $customer->set_billing_last_name($customer_data['last_name']);
            
            $customer_id = $customer->save();
            $created_customers[] = $customer_id;
            
            echo "<p>✅ Created customer: {$customer_data['first_name']} {$customer_data['last_name']} (ID: $customer_id)</p>";
        } else {
            echo "<p>⚠️ Customer {$customer_data['email']} already exists, skipping...</p>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ Failed to create customer {$customer_data['first_name']} {$customer_data['last_name']}: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>📦 Creating Test Orders...</h2>";

// Create test orders
$order_statuses = ['completed', 'processing', 'on-hold', 'pending'];
$orders_created = 0;

for ($i = 0; $i < 15; $i++) {
    try {
        $order = wc_create_order();
        
        // Random customer
        if (!empty($created_customers)) {
            $customer_id = $created_customers[array_rand($created_customers)];
            $customer = new WC_Customer($customer_id);
            
            $order->set_customer_id($customer_id);
            $order->set_billing_first_name($customer->get_first_name());
            $order->set_billing_last_name($customer->get_last_name());
            $order->set_billing_email($customer->get_email());
            $order->set_billing_phone($customer->get_billing_phone());
        }
        
        // Add random products
        $num_products = rand(1, 3);
        for ($j = 0; $j < $num_products; $j++) {
            if (!empty($created_products)) {
                $product_id = $created_products[array_rand($created_products)];
                $product = wc_get_product($product_id);
                $quantity = rand(1, 3);
                
                $order->add_product($product, $quantity);
            }
        }
        
        // Set random status
        $status = $order_statuses[array_rand($order_statuses)];
        $order->set_status($status);
        
        // Set random date within last 30 days
        $random_date = date('Y-m-d H:i:s', strtotime('-' . rand(0, 30) . ' days'));
        $order->set_date_created($random_date);
        
        $order->calculate_totals();
        $order->save();
        
        $orders_created++;
        echo "<p>✅ Created order #{$order->get_id()} - Status: $status - Total: " . $order->get_formatted_order_total() . "</p>";
        
    } catch (Exception $e) {
        echo "<p>❌ Failed to create order: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>🔔 Creating Test Notifications...</h2>";

// Create test notifications
$test_notifications = [
    [
        'type' => 'order',
        'priority' => 'high',
        'title' => 'New Order Received',
        'message' => 'Order #WD-' . rand(1000, 9999) . ' from John Smith - $89.99',
        'icon' => 'fa-shopping-cart',
        'color' => '#10B981',
        'bg_color' => '#10B981'
    ],
    [
        'type' => 'alert',
        'priority' => 'medium',
        'title' => 'Low Stock Alert',
        'message' => 'Stainless Steel Water Bottle is running low - Only 3 items left',
        'icon' => 'fa-exclamation-triangle',
        'color' => '#F59E0B',
        'bg_color' => '#F59E0B'
    ],
    [
        'type' => 'review',
        'priority' => 'low',
        'title' => 'New Customer Review',
        'message' => '⭐⭐⭐⭐⭐ "Great product quality!" - Sarah Johnson',
        'icon' => 'fa-star',
        'color' => '#3B82F6',
        'bg_color' => '#3B82F6'
    ],
    [
        'type' => 'system',
        'priority' => 'high',
        'title' => 'Security Alert',
        'message' => 'New login detected from IP: 192.168.1.100',
        'icon' => 'fa-shield-alt',
        'color' => '#EF4444',
        'bg_color' => '#EF4444'
    ],
    [
        'type' => 'order',
        'priority' => 'medium',
        'title' => 'Order Completed',
        'message' => 'Order #WD-' . rand(1000, 9999) . ' has been completed and shipped',
        'icon' => 'fa-truck',
        'color' => '#10B981',
        'bg_color' => '#10B981'
    ],
    [
        'type' => 'alert',
        'priority' => 'high',
        'title' => 'Out of Stock',
        'message' => 'Yoga Mat Premium is now out of stock',
        'icon' => 'fa-times-circle',
        'color' => '#EF4444',
        'bg_color' => '#EF4444'
    ]
];

$notifications_created = 0;

foreach ($test_notifications as $notification) {
    try {
        $result = $wpdb->insert(
            $notifications_table,
            [
                'user_id' => $current_user_id,
                'type' => $notification['type'],
                'priority' => $notification['priority'],
                'title' => $notification['title'],
                'message' => $notification['message'],
                'icon' => $notification['icon'],
                'color' => $notification['color'],
                'bg_color' => $notification['bg_color'],
                'actions' => json_encode([]),
                'metadata' => json_encode([]),
                'is_read' => rand(0, 1), // Random read status
                'created_at' => date('Y-m-d H:i:s', strtotime('-' . rand(0, 7) . ' days'))
            ],
            ['%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s']
        );
        
        if ($result) {
            $notifications_created++;
            echo "<p>✅ Created notification: {$notification['title']}</p>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ Failed to create notification: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>📧 Creating Test Marketing Campaigns...</h2>";

// Create test marketing campaigns
$test_campaigns = [
    [
        'name' => 'Summer Sale 2024',
        'type' => 'email',
        'status' => 'active',
        'subject' => 'Don\'t Miss Our Summer Sale - Up to 50% Off!',
        'content' => 'Get ready for summer with our amazing deals on electronics and fitness gear.',
        'clicks' => rand(150, 500),
        'opens' => rand(800, 1500),
        'conversions' => rand(25, 80),
        'revenue' => rand(2000, 8000)
    ],
    [
        'name' => 'New Product Launch',
        'type' => 'email',
        'status' => 'completed',
        'subject' => 'Introducing Our Latest Smart Fitness Watch',
        'content' => 'Discover the future of fitness tracking with our new smart watch.',
        'clicks' => rand(100, 300),
        'opens' => rand(600, 1200),
        'conversions' => rand(15, 50),
        'revenue' => rand(1500, 5000)
    ],
    [
        'name' => 'Customer Retention',
        'type' => 'email',
        'status' => 'draft',
        'subject' => 'We Miss You! Come Back for Exclusive Offers',
        'content' => 'Special offers just for our valued returning customers.',
        'clicks' => 0,
        'opens' => 0,
        'conversions' => 0,
        'revenue' => 0
    ],
    [
        'name' => 'Holiday Promotion',
        'type' => 'social',
        'status' => 'active',
        'subject' => 'Holiday Special - Free Shipping on All Orders',
        'content' => 'Celebrate the holidays with free shipping on everything!',
        'clicks' => rand(200, 600),
        'opens' => rand(1000, 2000),
        'conversions' => rand(40, 120),
        'revenue' => rand(3000, 10000)
    ]
];

$campaigns_created = 0;

foreach ($test_campaigns as $campaign) {
    try {
        $result = $wpdb->insert(
            $campaigns_table,
            [
                'name' => $campaign['name'],
                'type' => $campaign['type'],
                'status' => $campaign['status'],
                'subject' => $campaign['subject'],
                'content' => $campaign['content'],
                'target_audience' => json_encode(['all_customers']),
                'settings' => json_encode(['send_time' => '09:00']),
                'clicks' => $campaign['clicks'],
                'opens' => $campaign['opens'],
                'conversions' => $campaign['conversions'],
                'revenue' => $campaign['revenue'],
                'created_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')),
                'started_at' => $campaign['status'] !== 'draft' ? date('Y-m-d H:i:s', strtotime('-' . rand(1, 20) . ' days')) : null
            ]
        );
        
        if ($result) {
            $campaigns_created++;
            echo "<p>✅ Created campaign: {$campaign['name']}</p>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ Failed to create campaign: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>📊 Test Data Summary</h2>";
echo "<div style='background: #f0f9ff; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>✅ Successfully Created:</h3>";
echo "<ul>";
echo "<li>🛍️ <strong>" . count($created_products) . "</strong> Test Products</li>";
echo "<li>👥 <strong>" . count($created_customers) . "</strong> Test Customers</li>";
echo "<li>📦 <strong>$orders_created</strong> Test Orders</li>";
echo "<li>🔔 <strong>$notifications_created</strong> Test Notifications</li>";
echo "<li>📧 <strong>$campaigns_created</strong> Test Marketing Campaigns</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🎯 Next Steps</h2>";
echo "<div style='background: #f0fdf4; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
echo "<ol>";
echo "<li>🔄 <strong>Refresh your WooDash Pro dashboard</strong> to see the new data</li>";
echo "<li>🔔 <strong>Click the notifications button</strong> to see test notifications</li>";
echo "<li>📊 <strong>Check the analytics</strong> to see sales data and charts</li>";
echo "<li>📧 <strong>Visit the Marketing page</strong> to see campaign data</li>";
echo "<li>🛍️ <strong>Browse Products/Orders pages</strong> to see the test data</li>";
echo "</ol>";
echo "</div>";

echo "<div style='background: #fef3c7; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
echo "<p><strong>⚠️ Important:</strong> This is test data for development purposes. In a production environment, you should remove this script after testing.</p>";
echo "</div>";

echo "<p style='text-align: center; margin: 30px 0;'>";
echo "<a href='" . admin_url('admin.php?page=woodash-pro') . "' style='background: #00CC61; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;'>🚀 Go to WooDash Pro Dashboard</a>";
echo "</p>";

echo "</div>";
?>