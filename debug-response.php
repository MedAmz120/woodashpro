<?php
// Get the actual response content to debug
$url = 'http://localhost/woodashpro/wp-admin/admin.php?page=woodash-pro';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERAGENT, 'WoodDash Test Agent');

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $http_code\n";
echo "Response length: " . strlen($response) . " bytes\n\n";
echo "First 1000 characters of response:\n";
echo substr($response, 0, 1000) . "\n\n";

// Look for specific patterns
if (strpos($response, 'wp-login') !== false) {
    echo "LOGIN REDIRECT: User not logged in\n";
} elseif (strpos($response, 'dashboard') !== false) {
    echo "DASHBOARD: Redirected to main dashboard\n";
} elseif (strpos($response, 'WooDash') !== false) {
    echo "WOODASH: WoodDash content found\n";
} else {
    echo "UNKNOWN: Response type unclear\n";
}
?>
