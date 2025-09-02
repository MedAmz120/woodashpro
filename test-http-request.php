<?php
// Test direct HTTP request to the admin page
$url = 'http://localhost/woodashpro/wp-admin/admin.php?page=woodash-pro';

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERAGENT, 'WoodDash Test Agent');
curl_setopt($ch, CURLOPT_COOKIE, 'wordpress_test_cookie=WP Cookie check'); // Simulate basic cookie

// Execute request
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

echo "URL: $url\n";
echo "HTTP Code: $http_code\n";

if ($error) {
    echo "cURL Error: $error\n";
} elseif ($http_code === 200) {
    echo "Success! Page loaded correctly.\n";
    echo "Response length: " . strlen($response) . " bytes\n";
    
    // Check if it contains WoodDash content
    if (strpos($response, 'WooDash Pro') !== false) {
        echo "WoodDash Pro content found in response\n";
    } else {
        echo "WoodDash Pro content NOT found in response\n";
    }
    
    // Check for errors in response
    if (strpos($response, 'Fatal error') !== false || strpos($response, 'Parse error') !== false) {
        echo "PHP errors detected in response\n";
    } else {
        echo "No PHP errors detected\n";
    }
} else {
    echo "HTTP Error: $http_code\n";
    echo "Response: " . substr($response, 0, 500) . "...\n";
}
?>
