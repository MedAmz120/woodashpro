<?php
/**
 * Test WordPress AJAX System
 * This creates a simple AJAX handler to test if WordPress AJAX is working
 */

// Load WordPress
require_once(dirname(__FILE__) . '/../../../wp-config.php');

// Add a simple test AJAX handler
add_action('wp_ajax_test_woodash_ajax', 'test_woodash_ajax_handler');

function test_woodash_ajax_handler() {
    // Check nonce
    if (!check_ajax_referer('woodash_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
        return;
    }
    
    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Unauthorized'));
        return;
    }
    
    // Return success
    wp_send_json_success(array(
        'message' => 'WordPress AJAX is working!',
        'timestamp' => current_time('mysql'),
        'user_id' => get_current_user_id()
    ));
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>WordPress AJAX Test</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>🧪 WordPress AJAX Test</h1>
    
    <button id="test-wp-ajax" style="padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Test WordPress AJAX
    </button>
    
    <button id="test-marketing-ajax" style="padding: 10px 20px; background: #00a32a; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
        Test Marketing AJAX
    </button>
    
    <button id="test-direct-ajax" style="padding: 10px 20px; background: #d63638; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
        Test Direct AJAX
    </button>
    
    <div id="result" style="margin-top: 20px; padding: 15px; background: #f9f9f9; border-radius: 5px; display: none;">
        <h3>Result:</h3>
        <pre id="result-content"></pre>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
        const nonce = '<?php echo wp_create_nonce('woodash_nonce'); ?>';
        
        function showResult(title, data) {
            $('#result').show();
            $('#result-content').text(title + '\n\n' + JSON.stringify(data, null, 2));
        }
        
        // Test 1: Simple WordPress AJAX
        $('#test-wp-ajax').click(function() {
            console.log('Testing WordPress AJAX...');
            
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'test_woodash_ajax',
                    nonce: nonce
                },
                success: function(response) {
                    console.log('WordPress AJAX Success:', response);
                    showResult('✅ WordPress AJAX Success', response);
                },
                error: function(xhr, status, error) {
                    console.error('WordPress AJAX Error:', xhr.responseText);
                    showResult('❌ WordPress AJAX Error', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                }
            });
        });
        
        // Test 2: Marketing AJAX
        $('#test-marketing-ajax').click(function() {
            console.log('Testing Marketing AJAX...');
            
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'woodash_get_marketing_stats',
                    nonce: nonce
                },
                success: function(response) {
                    console.log('Marketing AJAX Success:', response);
                    showResult('✅ Marketing AJAX Success', response);
                },
                error: function(xhr, status, error) {
                    console.error('Marketing AJAX Error:', xhr.responseText);
                    showResult('❌ Marketing AJAX Error', {
                        status: status,
                        error: error,
                        response: xhr.responseText.substring(0, 500) + '...'
                    });
                }
            });
        });
        
        // Test 3: Direct AJAX (bypass WordPress)
        $('#test-direct-ajax').click(function() {
            console.log('Testing Direct AJAX...');
            
            $.ajax({
                url: '<?php echo plugin_dir_url(__FILE__); ?>test-ajax-direct.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Direct AJAX Success:', response);
                    showResult('✅ Direct AJAX Success', response);
                },
                error: function(xhr, status, error) {
                    console.error('Direct AJAX Error:', xhr.responseText);
                    showResult('❌ Direct AJAX Error', {
                        status: status,
                        error: error,
                        response: xhr.responseText.substring(0, 500) + '...'
                    });
                }
            });
        });
    });
    </script>
    
    <hr style="margin: 40px 0;">
    
    <h2>📋 Debug Information</h2>
    <ul>
        <li><strong>AJAX URL:</strong> <?php echo admin_url('admin-ajax.php'); ?></li>
        <li><strong>Nonce:</strong> <?php echo wp_create_nonce('woodash_nonce'); ?></li>
        <li><strong>User ID:</strong> <?php echo get_current_user_id(); ?></li>
        <li><strong>User Can Manage:</strong> <?php echo current_user_can('manage_options') ? 'Yes' : 'No'; ?></li>
        <li><strong>WordPress Version:</strong> <?php echo get_bloginfo('version'); ?></li>
        <li><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></li>
    </ul>
    
    <h2>🔍 Instructions</h2>
    <ol>
        <li><strong>Test WordPress AJAX:</strong> Tests if the basic WordPress AJAX system is working</li>
        <li><strong>Test Marketing AJAX:</strong> Tests the actual marketing stats AJAX handler</li>
        <li><strong>Test Direct AJAX:</strong> Tests the marketing logic bypassing WordPress AJAX</li>
        <li>Check the browser console for detailed error messages</li>
        <li>Look for any HTML output before JSON in the error responses</li>
    </ol>
</body>
</html>