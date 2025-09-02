<?php
/**
 * Test Marketing AJAX Directly
 * This script tests the marketing AJAX endpoints directly to debug the issue
 */

// Load WordPress
require_once(dirname(__FILE__) . '/../../../wp-config.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Access denied. You must be an administrator to use this tool.');
}

echo "<h1>🔧 Marketing AJAX Debug Test</h1>";

// Test 1: Direct function call
echo "<h2>Test 1: Direct Function Call</h2>";
try {
    $woodash_pro = WoodashPro::get_instance();
    
    // Simulate POST data
    $_POST['nonce'] = wp_create_nonce('woodash_nonce');
    $_POST['action'] = 'woodash_get_marketing_stats';
    
    echo "<p>✅ Plugin instance created</p>";
    echo "<p>✅ Nonce created: " . $_POST['nonce'] . "</p>";
    
    // Capture output
    ob_start();
    $woodash_pro->ajax_get_marketing_stats();
    $output = ob_get_clean();
    
    echo "<p><strong>Function Output:</strong></p>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>" . htmlspecialchars($output) . "</pre>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

// Test 2: Check database directly
echo "<h2>Test 2: Database Check</h2>";
global $wpdb;
$campaigns_table = $wpdb->prefix . 'woodash_campaigns';

try {
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$campaigns_table'");
    if ($table_exists) {
        echo "<p>✅ Campaigns table exists</p>";
        
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $campaigns_table");
        echo "<p>📊 Total campaigns: $count</p>";
        
        if ($count > 0) {
            $sample = $wpdb->get_row("SELECT * FROM $campaigns_table LIMIT 1");
            echo "<p>📝 Sample campaign:</p>";
            echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>" . print_r($sample, true) . "</pre>";
        }
    } else {
        echo "<p>❌ Campaigns table does not exist</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test 3: Test AJAX URL directly
echo "<h2>Test 3: AJAX URL Test</h2>";
$ajax_url = admin_url('admin-ajax.php');
echo "<p>AJAX URL: <code>$ajax_url</code></p>";

// Test 4: Check for PHP errors
echo "<h2>Test 4: PHP Error Check</h2>";
$error_log = ini_get('error_log');
echo "<p>PHP Error Log: <code>$error_log</code></p>";

// Test 5: Manual AJAX simulation
echo "<h2>Test 5: Manual AJAX Simulation</h2>";
echo "<button onclick='testAjax()' style='padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 5px; cursor: pointer;'>Test AJAX Call</button>";
echo "<div id='ajax-result' style='margin-top: 20px; padding: 10px; background: #f9f9f9; border-radius: 5px; display: none;'></div>";

?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function testAjax() {
    console.log('Testing AJAX call...');
    
    const resultDiv = document.getElementById('ajax-result');
    resultDiv.style.display = 'block';
    resultDiv.innerHTML = '<p>🔄 Testing AJAX call...</p>';
    
    jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            action: 'woodash_get_marketing_stats',
            nonce: '<?php echo wp_create_nonce('woodash_nonce'); ?>'
        },
        success: function(response) {
            console.log('AJAX Success:', response);
            resultDiv.innerHTML = '<h4>✅ AJAX Success</h4><pre>' + JSON.stringify(response, null, 2) + '</pre>';
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr.responseText);
            resultDiv.innerHTML = '<h4>❌ AJAX Error</h4><p><strong>Status:</strong> ' + status + '</p><p><strong>Error:</strong> ' + error + '</p><p><strong>Response:</strong></p><pre>' + xhr.responseText + '</pre>';
        }
    });
}
</script>

<style>
body { font-family: Arial, sans-serif; margin: 40px; }
pre { white-space: pre-wrap; word-wrap: break-word; max-height: 300px; overflow-y: auto; }
</style>

<hr>
<p><strong>Instructions:</strong></p>
<ol>
    <li>Check the direct function call output above</li>
    <li>Click the "Test AJAX Call" button to test the actual AJAX endpoint</li>
    <li>Check browser console for additional error details</li>
    <li>Look for any PHP errors or HTML output before JSON</li>
</ol>