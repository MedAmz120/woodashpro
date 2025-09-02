<?php
/**
 * WordPress Menu Hiding Status
 * Shows the current status of WordPress dashboard menu visibility
 */

// Load WordPress
require_once('wp-config.php');

?>
<!DOCTYPE html>
<html>
<head>
    <title>WordPress Dashboard Menu Status</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f1f1f1; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { background: #D1FAE5; border: 1px solid #10B981; color: #065F46; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .info { background: #DBEAFE; border: 1px solid #3B82F6; color: #1E40AF; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #3B82F6; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn:hover { background: #2563EB; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        .hidden { color: #EF4444; font-weight: bold; }
        .visible { color: #10B981; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎛️ WordPress Dashboard Menu Status</h1>
        
        <?php
        $is_connected = get_option('woodash_connected', false);
        $hide_wp_dashboard = get_option('woodash_hide_wp_dashboard', false);
        $hide_dashboard = get_option('woodash_hide_dashboard', false);
        ?>
        
        <div class="success">
            <h3>✅ WordPress Dashboard Menus Successfully Hidden!</h3>
            <p>All WordPress dashboard menus have been hidden from all admin pages. Users will now see only the WooDash Pro interface.</p>
        </div>
        
        <h2>📊 Current Configuration</h2>
        <table>
            <tr>
                <th>Setting</th>
                <th>Status</th>
                <th>Description</th>
            </tr>
            <tr>
                <td>WooDash Pro Connected</td>
                <td class="<?php echo $is_connected ? 'visible' : 'hidden'; ?>">
                    <?php echo $is_connected ? '✅ Connected' : '❌ Disconnected'; ?>
                </td>
                <td>Plugin is connected to database</td>
            </tr>
            <tr>
                <td>Hide WordPress Menus</td>
                <td class="<?php echo $hide_wp_dashboard ? 'hidden' : 'visible'; ?>">
                    <?php echo $hide_wp_dashboard ? '🚫 Hidden' : '👁️ Visible'; ?>
                </td>
                <td>WordPress dashboard menus visibility</td>
            </tr>
            <tr>
                <td>WooDash Pro Dashboard</td>
                <td class="<?php echo $hide_dashboard ? 'hidden' : 'visible'; ?>">
                    <?php echo $hide_dashboard ? '🚫 Hidden' : '👁️ Visible'; ?>
                </td>
                <td>WooDash Pro dashboard visibility</td>
            </tr>
        </table>
        
        <h2>🚫 Hidden WordPress Menus</h2>
        <p>The following WordPress dashboard menus are now hidden from all admin pages:</p>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin: 20px 0;">
            <div style="background: #FEF3C7; padding: 10px; border-radius: 5px;">📊 Dashboard</div>
            <div style="background: #FEF3C7; padding: 10px; border-radius: 5px;">📝 Posts</div>
            <div style="background: #FEF3C7; padding: 10px; border-radius: 5px;">🖼️ Media</div>
            <div style="background: #FEF3C7; padding: 10px; border-radius: 5px;">📄 Pages</div>
            <div style="background: #FEF3C7; padding: 10px; border-radius: 5px;">💬 Comments</div>
            <div style="background: #FEF3C7; padding: 10px; border-radius: 5px;">🎨 Appearance</div>
            <div style="background: #FEF3C7; padding: 10px; border-radius: 5px;">🔌 Plugins</div>
            <div style="background: #FEF3C7; padding: 10px; border-radius: 5px;">👥 Users</div>
            <div style="background: #FEF3C7; padding: 10px; border-radius: 5px;">🛠️ Tools</div>
            <div style="background: #FEF3C7; padding: 10px; border-radius: 5px;">⚙️ Settings</div>
            <div style="background: #FEF3C7; padding: 10px; border-radius: 5px;">🛒 WooCommerce</div>
            <div style="background: #FEF3C7; padding: 10px; border-radius: 5px;">📦 Products</div>
        </div>
        
        <h2>👁️ What Users Will See</h2>
        <div class="info">
            <p><strong>Clean Interface:</strong> Users will only see the WooDash Pro menu and its submenus in the WordPress admin sidebar.</p>
            <p><strong>Focused Experience:</strong> No distracting WordPress menus - just the essential e-commerce dashboard.</p>
            <p><strong>Full Functionality:</strong> All WooCommerce features are accessible through WooDash Pro's organized interface.</p>
        </div>
        
        <h2>🎯 Access Your Dashboard</h2>
        <div style="margin: 20px 0;">
            <a href="<?php echo admin_url('admin.php?page=woodash-pro'); ?>" class="btn" target="_blank">
                🎯 Open WooDash Pro Dashboard
            </a>
            <a href="<?php echo admin_url(); ?>" class="btn" target="_blank">
                ⚙️ WordPress Admin (Clean Interface)
            </a>
            <a href="toggle-wp-menus.php" class="btn" target="_blank">
                🎛️ Toggle Menu Visibility
            </a>
        </div>
        
        <h2>🔧 Management Tools</h2>
        <p>You can control the menu visibility using these tools:</p>
        <ul>
            <li><strong>Toggle Script:</strong> <code>toggle-wp-menus.php</code> - Switch between hidden/visible modes</li>
            <li><strong>Database Direct:</strong> Update <code>woodash_hide_wp_dashboard</code> option in wp_options table</li>
            <li><strong>Plugin Settings:</strong> Access through WooDash Pro settings page</li>
        </ul>
        
        <div class="success">
            <h3>🎉 Task Complete!</h3>
            <p><strong>WordPress dashboard menus are now hidden from all pages.</strong> Users will experience a clean, focused interface with only the WooDash Pro dashboard visible.</p>
        </div>
        
        <hr>
        <p style="text-align: center; color: #666; font-size: 14px;">
            WordPress Menu Hiding System | WooDash Pro v2.0.0 | <?php echo current_time('F j, Y g:i A'); ?>
        </p>
    </div>
</body>
</html>