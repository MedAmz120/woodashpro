<?php
/**
 * Debug Marketing Data Loading
 * This script helps debug marketing data loading issues
 */

// Load WordPress
require_once(dirname(__FILE__) . '/../../../wp-config.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Access denied. You must be an administrator to use this tool.');
}

echo "<h1>Marketing Data Debug</h1>";

// Test database connection
global $wpdb;
$campaigns_table = $wpdb->prefix . 'woodash_campaigns';

echo "<h2>Database Connection Test</h2>";
try {
    $test_query = $wpdb->get_var("SELECT 1");
    echo "<p>✅ Database connection: OK</p>";
} catch (Exception $e) {
    echo "<p>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

// Test campaigns table
echo "<h2>Campaigns Table Test</h2>";
try {
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$campaigns_table'");
    if ($table_exists) {
        echo "<p>✅ Campaigns table exists: $campaigns_table</p>";
        
        $campaign_count = $wpdb->get_var("SELECT COUNT(*) FROM $campaigns_table");
        echo "<p>📊 Total campaigns: $campaign_count</p>";
        
        // Get sample data
        $campaigns = $wpdb->get_results("SELECT * FROM $campaigns_table LIMIT 3");
        if ($campaigns) {
            echo "<h3>Sample Campaigns:</h3>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Name</th><th>Type</th><th>Status</th><th>Clicks</th><th>Opens</th></tr>";
            foreach ($campaigns as $campaign) {
                echo "<tr>";
                echo "<td>{$campaign->id}</td>";
                echo "<td>{$campaign->name}</td>";
                echo "<td>{$campaign->type}</td>";
                echo "<td>{$campaign->status}</td>";
                echo "<td>{$campaign->clicks}</td>";
                echo "<td>{$campaign->opens}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<p>❌ Campaigns table does not exist</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Campaigns table error: " . $e->getMessage() . "</p>";
}

// Test marketing stats calculation
echo "<h2>Marketing Stats Calculation Test</h2>";
try {
    $total_campaigns = $wpdb->get_var("SELECT COUNT(*) FROM $campaigns_table");
    $active_campaigns = $wpdb->get_var("SELECT COUNT(*) FROM $campaigns_table WHERE status = 'active'");
    $total_clicks = $wpdb->get_var("SELECT SUM(clicks) FROM $campaigns_table") ?: 0;
    $total_conversions = $wpdb->get_var("SELECT SUM(conversions) FROM $campaigns_table") ?: 0;
    $total_revenue = $wpdb->get_var("SELECT SUM(revenue) FROM $campaigns_table") ?: 0;
    
    echo "<p>✅ Marketing stats calculated successfully:</p>";
    echo "<ul>";
    echo "<li>Total Campaigns: $total_campaigns</li>";
    echo "<li>Active Campaigns: $active_campaigns</li>";
    echo "<li>Total Clicks: $total_clicks</li>";
    echo "<li>Total Conversions: $total_conversions</li>";
    echo "<li>Total Revenue: $total_revenue</li>";
    echo "</ul>";
} catch (Exception $e) {
    echo "<p>❌ Marketing stats calculation failed: " . $e->getMessage() . "</p>";
}

// Test AJAX endpoint directly
echo "<h2>AJAX Endpoint Test</h2>";
echo "<p>Testing the marketing stats AJAX endpoint...</p>";

// Simulate AJAX request
$_POST['nonce'] = wp_create_nonce('woodash_nonce');
$_POST['action'] = 'woodash_get_marketing_stats';

// Get the plugin instance
$woodash_pro = WoodashPro::get_instance();

if (method_exists($woodash_pro, 'ajax_get_marketing_stats')) {
    echo "<p>✅ AJAX method exists</p>";
    
    // Test user permissions
    if (current_user_can('manage_options')) {
        echo "<p>✅ User has required permissions</p>";
        
        // Test nonce
        if (wp_verify_nonce($_POST['nonce'], 'woodash_nonce')) {
            echo "<p>✅ Nonce verification passed</p>";
            
            // Try to call the method directly
            try {
                ob_start();
                $woodash_pro->ajax_get_marketing_stats();
                $output = ob_get_clean();
                
                if (!empty($output)) {
                    echo "<p>✅ AJAX method executed successfully</p>";
                    echo "<pre>Output: $output</pre>";
                } else {
                    echo "<p>⚠️ AJAX method executed but no output</p>";
                }
            } catch (Exception $e) {
                echo "<p>❌ AJAX method execution failed: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p>❌ Nonce verification failed</p>";
        }
    } else {
        echo "<p>❌ User lacks required permissions</p>";
    }
} else {
    echo "<p>❌ AJAX method does not exist</p>";
}

// Test WordPress AJAX URL
echo "<h2>WordPress AJAX Configuration</h2>";
$ajax_url = admin_url('admin-ajax.php');
echo "<p>AJAX URL: <code>$ajax_url</code></p>";

// Test if WooCommerce is active
echo "<h2>WooCommerce Status</h2>";
if (class_exists('WooCommerce')) {
    echo "<p>✅ WooCommerce is active</p>";
    echo "<p>WooCommerce Version: " . WC()->version . "</p>";
} else {
    echo "<p>❌ WooCommerce is not active</p>";
}

// Test plugin status
echo "<h2>Plugin Status</h2>";
$is_connected = get_option('woodash_connected', false);
echo "<p>Plugin Connected: " . ($is_connected ? '✅ Yes' : '❌ No') . "</p>";

// Create a simple test form
echo "<h2>Manual Test</h2>";
echo "<p>Click the button below to test the marketing data loading via JavaScript:</p>";
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function testMarketingData() {
    console.log('Testing marketing data...');
    
    jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            action: 'woodash_get_marketing_stats',
            nonce: '<?php echo wp_create_nonce('woodash_nonce'); ?>'
        },
        success: function(response) {
            console.log('Success:', response);
            document.getElementById('test-result').innerHTML = '<div style="background: #d4edda; padding: 10px; border-radius: 5px; color: #155724;">✅ Success! Check browser console for details.</div>';
        },
        error: function(xhr, status, error) {
            console.error('Error:', xhr.responseText);
            document.getElementById('test-result').innerHTML = '<div style="background: #f8d7da; padding: 10px; border-radius: 5px; color: #721c24;">❌ Error: ' + error + '</div>';
        }
    });
}
</script>

<button onclick="testMarketingData()" style="padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 5px; cursor: pointer;">
    Test Marketing Data Loading
</button>

<div id="test-result" style="margin-top: 20px;"></div>

<hr>
<p><strong>Debug completed.</strong> Check the results above to identify any issues.</p>