<?php
require_once('wp-load.php');
global $current_user;
wp_get_current_user();

echo 'User: ' . $current_user->user_login . PHP_EOL;
echo 'Roles: ' . implode(', ', $current_user->roles) . PHP_EOL;
echo 'Capabilities: ' . PHP_EOL;
print_r(array_keys($current_user->allcaps));
?>
