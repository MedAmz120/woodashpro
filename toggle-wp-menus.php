<?php
/**
 * Toggle WordPress Dashboard Menus
 * This script allows you to show/hide WordPress dashboard menus
 */

// Load WordPress
require_once(dirname(__FILE__) . '/../../../wp-config.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Access denied. You must be an administrator to use this tool.');
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$current_setting = get_option('woodash_hide_wp_dashboard', false);

if ($action === 'toggle') {
    $new_setting = !$current_setting;
    update_option('woodash_hide_wp_dashboard', $new_setting);
    $current_setting = $new_setting;
    $message = $new_setting ? 'WordPress dashboard menus are now HIDDEN' : 'WordPress dashboard menus are now VISIBLE';
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>WooDash Pro - Toggle WordPress Menus</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f1f1f1; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; }
        .btn { display: inline-block; padding: 12px 24px; background: #3B82F6; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn:hover { background: #2563EB; }
        .btn-success { background: #10B981; }
        .btn-success:hover { background: #059669; }
        .btn-danger { background: #EF4444; }
        .btn-danger:hover { background: #DC2626; }
        .status { padding: 15px; border-radius: 5px; margin: 20px 0; }
        .status-hidden { background: #FEF3C7; border: 1px solid #F59E0B; color: #92400E; }
        .status-visible { background: #D1FAE5; border: 1px solid #10B981; color: #065F46; }
        .message { background: #DBEAFE; border: 1px solid #3B82F6; color: #1E40AF; padding: 15px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎛️ WooDash Pro - WordPress Menu Control</h1>
        
        <?php if (isset($message)): ?>
            <div class="message">
                <strong>✅ Success!</strong> <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <h2>Current Status</h2>
        <div class="status <?php echo $current_setting ? 'status-hidden' : 'status-visible'; ?>">
            <strong>WordPress Dashboard Menus:</strong> 
            <?php echo $current_setting ? '🚫 HIDDEN' : '👁️ VISIBLE'; ?>
        </div>
        
        <h2>What This Does</h2>
        <p>When WordPress dashboard menus are <strong>HIDDEN</strong>, the following menus will be removed from all admin pages:</p>
        <ul>
            <li>📊 Dashboard</li>
            <li>📝 Posts</li>
            <li>🖼️ Media</li>
            <li>📄 Pages</li>
            <li>💬 Comments</li>
            <li>🎨 Appearance</li>
            <li>🔌 Plugins</li>
            <li>👥 Users</li>
            <li>🛠️ Tools</li>
            <li>⚙️ Settings</li>
            <li>🛒 WooCommerce (accessible through WooDash Pro)</li>
            <li>📦 Products (accessible through WooDash Pro)</li>
        </ul>
        
        <p>This creates a clean, focused interface where users only see the WooDash Pro dashboard and its features.</p>
        
        <h2>Actions</h2>
        <div style="margin: 20px 0;">
            <?php if ($current_setting): ?>
                <a href="?action=toggle" class="btn btn-success">
                    👁️ Show WordPress Menus
                </a>
                <p><em>Click to make WordPress dashboard menus visible again</em></p>
            <?php else: ?>
                <a href="?action=toggle" class="btn btn-danger">
                    ���� Hide WordPress Menus
                </a>
                <p><em>Click to hide WordPress dashboard menus and show only WooDash Pro</em></p>
            <?php endif; ?>
        </div>
        
        <h2>Quick Access</h2>
        <div style="margin: 20px 0;">
            <a href="<?php echo admin_url('admin.php?page=woodash-pro'); ?>" class="btn" target="_blank">
                🎯 Open WooDash Pro Dashboard
            </a>
            <a href="<?php echo admin_url(); ?>" class="btn" target="_blank">
                ⚙️ WordPress Admin
            </a>
            <a href="<?php echo home_url(); ?>" class="btn" target="_blank">
                🏠 Visit Website
            </a>
        </div>
        
        <hr>
        <p style="color: #666; font-size: 14px;">
            <strong>Note:</strong> This setting only affects the admin menu visibility. All WordPress functionality remains intact and accessible through WooDash Pro or direct URLs.
        </p>
    </div>
</body>
</html>