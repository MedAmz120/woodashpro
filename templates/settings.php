<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Check if user has required capabilities
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'woodashh'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings Dashboard</title>
    
    <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        /* Base Styles */
        :root {
            --primary-color: #00CC61;
            --primary-dark: #00b357;
            --secondary-color: #6B7280;
            --background-color: #F8FAFC;
            --card-background: rgba(255, 255, 255, 0.9);
            --border-color: rgba(0, 0, 0, 0.1);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --transition-base: all 0.3s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background-color);
            color: #374151;
            line-height: 1.5;
        }

        /* Utility Classes */
        .woodash-fullscreen {
            min-height: 100vh;
            width: 100%;
        }

        .woodash-glass-effect {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-md);
        }

        .woodash-gradient-text {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .woodash-glow {
            box-shadow: 0 0 15px rgba(0, 204, 97, 0.3);
        }

        /* Sidebar Styles */
        .woodash-sidebar {
            transition: transform 0.3s ease;
            z-index: 50;
            flex-shrink: 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
        }

        .woodash-nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: #6B7280;
            transition: var(--transition-base);
        }

        .woodash-nav-link:hover,
        .woodash-nav-link.active {
            background: rgba(0, 204, 97, 0.1);
            color: var(--primary-dark);
        }

        /* Main Content Area */
        .woodash-main {
            margin-left: 16rem;
            transition: margin-left 0.3s ease;
            flex-grow: 1;
            overflow-y: auto;
        }

        /* Card Styles */
        .woodash-card {
            background: var(--card-background);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            transition: var(--transition-base);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .woodash-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Button Styles */
        .woodash-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: var(--transition-base);
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .woodash-btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .woodash-btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }

        .woodash-btn-secondary {
            background: #F3F4F6;
            color: #374151;
        }

        .woodash-btn-secondary:hover {
            background: #E5E7EB;
        }

        .woodash-btn-danger {
            background: #EF4444;
            color: white;
        }

        .woodash-btn-danger:hover {
            background: #DC2626;
        }

        /* Form Styles */
        .woodash-form-group {
            margin-bottom: 1.5rem;
        }

        .woodash-form-group label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .woodash-form-group input,
        .woodash-form-group select,
        .woodash-form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            transition: var(--transition-base);
        }

        .woodash-form-group input:focus,
        .woodash-form-group select:focus,
        .woodash-form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 204, 97, 0.1);
        }

        /* Toggle Switch */
        .woodash-toggle {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 24px;
        }

        .woodash-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .woodash-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }

        .woodash-toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        .woodash-toggle input:checked + .woodash-toggle-slider {
            background-color: var(--primary-color);
        }

        .woodash-toggle input:checked + .woodash-toggle-slider:before {
            transform: translateX(24px);
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .woodash-sidebar {
                transform: translateX(-100%);
            }

            .woodash-sidebar.active {
                transform: translateX(0);
            }

            .woodash-main {
                margin-left: 0;
            }

            .woodash-menu-toggle {
                display: block;
            }
        }

        .woodash-menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 60;
        }
    </style>
</head>
<body>

<div id="woodash-dashboard" class="woodash-fullscreen flex">
    <!-- Sidebar -->
    <aside class="woodash-sidebar w-64 bg-white/90 border-r border-gray-100 p-6 woodash-glass-effect">
        <div class="flex items-center gap-3 mb-8 woodash-fade-in">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center woodash-glow">
                <i class="fa-solid fa-chart-line text-white text-xl"></i>
            </div>
            <h2 class="text-xl font-bold woodash-gradient-text">WooDash Pro</h2>
        </div>

        <!-- Main Navigation -->
        <nav class="space-y-1">
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-gauge w-5"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-orders')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-cart-shopping w-5"></i>
                <span>Orders</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-products')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-box w-5"></i>
                <span>Products</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-customers')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-users w-5"></i>
                <span>Customers</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-stock')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-boxes-stacked w-5"></i>
                <span>Stock</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-reviews')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-star w-5"></i>
                <span>Reviews</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-marketing')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-bullhorn w-5"></i>
                <span>Marketing</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-reports')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-file-lines w-5"></i>
                <span>Reports</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-settings')); ?>" class="woodash-nav-link active">
                <i class="fa-solid fa-gear w-5"></i>
                <span>Settings</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="woodash-main flex-1 p-6 md:p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold woodash-gradient-text">Settings</h1>
                    <p class="text-gray-500">Configure WooDash Pro plugin settings and preferences.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="woodash-btn woodash-btn-primary" id="save-settings-btn">
                        <i class="fa-solid fa-save"></i>
                        <span>Save Settings</span>
                    </button>
                </div>
            </header>

            <!-- Settings Sections -->
            <div class="space-y-6">
                <!-- General Settings -->
                <div class="woodash-card p-6">
                    <h2 class="text-lg font-bold woodash-gradient-text mb-4">General Settings</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="woodash-form-group">
                            <label>Dashboard Refresh Interval</label>
                            <select id="refresh-interval">
                                <option value="5">5 seconds</option>
                                <option value="10" selected>10 seconds</option>
                                <option value="30">30 seconds</option>
                                <option value="60">1 minute</option>
                                <option value="300">5 minutes</option>
                            </select>
                        </div>
                        <div class="woodash-form-group">
                            <label>Date Format</label>
                            <select id="date-format">
                                <option value="Y-m-d">YYYY-MM-DD</option>
                                <option value="m/d/Y" selected>MM/DD/YYYY</option>
                                <option value="d/m/Y">DD/MM/YYYY</option>
                                <option value="M j, Y">Month DD, YYYY</option>
                            </select>
                        </div>
                        <div class="woodash-form-group">
                            <label>Currency Symbol</label>
                            <input type="text" id="currency-symbol" value="$" placeholder="$">
                        </div>
                        <div class="woodash-form-group">
                            <label>Items Per Page</label>
                            <select id="items-per-page">
                                <option value="10">10</option>
                                <option value="25" selected>25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Settings -->
                <div class="woodash-card p-6">
                    <h2 class="text-lg font-bold woodash-gradient-text mb-4">Dashboard Settings</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium">Enable Real-time Updates</label>
                                <p class="text-sm text-gray-500">Automatically refresh dashboard data</p>
                            </div>
                            <label class="woodash-toggle">
                                <input type="checkbox" id="realtime-updates" checked>
                                <span class="woodash-toggle-slider"></span>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium">Show Animations</label>
                                <p class="text-sm text-gray-500">Enable loading animations and transitions</p>
                            </div>
                            <label class="woodash-toggle">
                                <input type="checkbox" id="show-animations" checked>
                                <span class="woodash-toggle-slider"></span>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium">Enable Dark Mode</label>
                                <p class="text-sm text-gray-500">Use dark theme for the dashboard</p>
                            </div>
                            <label class="woodash-toggle">
                                <input type="checkbox" id="dark-mode">
                                <span class="woodash-toggle-slider"></span>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium">Enable Desktop Notifications</label>
                                <p class="text-sm text-gray-500">Get notified about important events</p>
                            </div>
                            <label class="woodash-toggle">
                                <input type="checkbox" id="desktop-notifications">
                                <span class="woodash-toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Performance Settings -->
                <div class="woodash-card p-6">
                    <h2 class="text-lg font-bold woodash-gradient-text mb-4">Performance Settings</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium">Enable Data Caching</label>
                                <p class="text-sm text-gray-500">Cache dashboard data to improve performance</p>
                            </div>
                            <label class="woodash-toggle">
                                <input type="checkbox" id="enable-caching" checked>
                                <span class="woodash-toggle-slider"></span>
                            </label>
                        </div>
                        <div class="woodash-form-group">
                            <label>Cache Duration (minutes)</label>
                            <input type="number" id="cache-duration" value="30" min="1" max="1440">
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium">Lazy Load Charts</label>
                                <p class="text-sm text-gray-500">Load charts only when visible</p>
                            </div>
                            <label class="woodash-toggle">
                                <input type="checkbox" id="lazy-load-charts" checked>
                                <span class="woodash-toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="woodash-card p-6">
                    <h2 class="text-lg font-bold woodash-gradient-text mb-4">Notification Settings</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium">Low Stock Alerts</label>
                                <p class="text-sm text-gray-500">Get notified when products are low in stock</p>
                            </div>
                            <label class="woodash-toggle">
                                <input type="checkbox" id="low-stock-alerts" checked>
                                <span class="woodash-toggle-slider"></span>
                            </label>
                        </div>
                        <div class="woodash-form-group">
                            <label>Low Stock Threshold</label>
                            <input type="number" id="low-stock-threshold" value="10" min="1">
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium">New Order Notifications</label>
                                <p class="text-sm text-gray-500">Get notified about new orders</p>
                            </div>
                            <label class="woodash-toggle">
                                <input type="checkbox" id="new-order-notifications" checked>
                                <span class="woodash-toggle-slider"></span>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium">Review Notifications</label>
                                <p class="text-sm text-gray-500">Get notified about new customer reviews</p>
                            </div>
                            <label class="woodash-toggle">
                                <input type="checkbox" id="review-notifications" checked>
                                <span class="woodash-toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="woodash-card p-6">
                    <h2 class="text-lg font-bold woodash-gradient-text mb-4">Security Settings</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium">Enable Two-Factor Authentication</label>
                                <p class="text-sm text-gray-500">Add extra security to your account</p>
                            </div>
                            <label class="woodash-toggle">
                                <input type="checkbox" id="two-factor-auth">
                                <span class="woodash-toggle-slider"></span>
                            </label>
                        </div>
                        <div class="woodash-form-group">
                            <label>Session Timeout (minutes)</label>
                            <input type="number" id="session-timeout" value="120" min="15" max="1440">
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium">Enable Activity Logging</label>
                                <p class="text-sm text-gray-500">Log user activities for security purposes</p>
                            </div>
                            <label class="woodash-toggle">
                                <input type="checkbox" id="activity-logging" checked>
                                <span class="woodash-toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Backup & Export -->
                <div class="woodash-card p-6">
                    <h2 class="text-lg font-bold woodash-gradient-text mb-4">Backup & Export</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="font-medium">Automatic Backups</label>
                                <p class="text-sm text-gray-500">Automatically backup settings and data</p>
                            </div>
                            <label class="woodash-toggle">
                                <input type="checkbox" id="auto-backup" checked>
                                <span class="woodash-toggle-slider"></span>
                            </label>
                        </div>
                        <div class="woodash-form-group">
                            <label>Backup Frequency</label>
                            <select id="backup-frequency">
                                <option value="daily">Daily</option>
                                <option value="weekly" selected>Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-4">
                            <button class="woodash-btn woodash-btn-secondary">
                                <i class="fa-solid fa-download"></i>
                                Export Settings
                            </button>
                            <button class="woodash-btn woodash-btn-secondary">
                                <i class="fa-solid fa-upload"></i>
                                Import Settings
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="woodash-card p-6 border-red-200">
                    <h2 class="text-lg font-bold text-red-600 mb-4">Danger Zone</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                            <div>
                                <label class="font-medium text-red-700">Reset All Settings</label>
                                <p class="text-sm text-red-600">This will reset all plugin settings to default values</p>
                            </div>
                            <button class="woodash-btn woodash-btn-danger">
                                <i class="fa-solid fa-refresh"></i>
                                Reset Settings
                            </button>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                            <div>
                                <label class="font-medium text-red-700">Clear All Data</label>
                                <p class="text-sm text-red-600">This will permanently delete all plugin data</p>
                            </div>
                            <button class="woodash-btn woodash-btn-danger">
                                <i class="fa-solid fa-trash"></i>
                                Clear Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <div class="max-w-4xl mx-auto text-center py-4 text-gray-600 text-sm">
                <p>&copy; <?php echo date('Y'); ?> WooDash Pro. All rights reserved.</p>
            </div>
        </footer>
    </main>
</div>

<script>
// WordPress AJAX Configuration
window.woodash_ajax = {
    ajax_url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
    nonce: '<?php echo wp_create_nonce('woodash_nonce'); ?>'
};

document.addEventListener('DOMContentLoaded', function() {
    const SettingsManager = {
        state: {
            settings: {},
            hasChanges: false
        },

        init() {
            this.loadRealSettings();
            this.initEventListeners();
        },

        async loadRealSettings() {
            try {
                const formData = new FormData();
                formData.append('action', 'woodash_get_settings');
                formData.append('nonce', woodash_ajax.nonce);

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.state.settings = result.data;
                    this.applySettings(result.data);
                } else {
                    console.error('Failed to load settings:', result.data);
                    this.loadDefaultSettings();
                }
            } catch (error) {
                console.error('Error loading settings:', error);
                this.loadDefaultSettings();
            }
        },

        applySettings(settings) {
            // Apply settings to form elements
            const elements = {
                currency: document.getElementById('currency'),
                timezone: document.getElementById('timezone'),
                dateFormat: document.getElementById('date-format'),
                lowStockThreshold: document.getElementById('low-stock-threshold'),
                enableNotifications: document.getElementById('enable-notifications'),
                dashboardRefresh: document.getElementById('dashboard-refresh')
            };

            if (elements.currency && settings.currency) {
                elements.currency.value = settings.currency;
            }
            if (elements.timezone && settings.timezone) {
                elements.timezone.value = settings.timezone;
            }
            if (elements.dateFormat && settings.date_format) {
                elements.dateFormat.value = settings.date_format;
            }
            if (elements.lowStockThreshold && settings.low_stock_threshold) {
                elements.lowStockThreshold.value = settings.low_stock_threshold;
            }
            if (elements.enableNotifications && typeof settings.enable_notifications !== 'undefined') {
                elements.enableNotifications.checked = settings.enable_notifications;
            }
            if (elements.dashboardRefresh && settings.dashboard_refresh_interval) {
                elements.dashboardRefresh.value = settings.dashboard_refresh_interval;
            }
        },

        async saveSettings() {
            const formData = this.collectFormData();
            formData.append('action', 'woodash_update_settings');
            formData.append('nonce', woodash_ajax.nonce);

            try {
                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.showNotification('Settings saved successfully!', 'success');
                    this.state.hasChanges = false;
                    this.updateSaveButtonState();
                } else {
                    this.showNotification('Failed to save settings: ' + result.data, 'error');
                }
            } catch (error) {
                console.error('Error saving settings:', error);
                this.showNotification('Error saving settings', 'error');
            }
        },

        collectFormData() {
            const formData = new FormData();
            
            // Collect all form values
            const elements = {
                lowStockThreshold: document.getElementById('low-stock-threshold'),
                enableNotifications: document.getElementById('enable-notifications'),
                dashboardRefresh: document.getElementById('dashboard-refresh')
            };

            if (elements.lowStockThreshold) {
                formData.append('low_stock_threshold', elements.lowStockThreshold.value);
            }
            if (elements.enableNotifications) {
                formData.append('enable_notifications', elements.enableNotifications.checked ? '1' : '0');
            }
            if (elements.dashboardRefresh) {
                formData.append('dashboard_refresh_interval', elements.dashboardRefresh.value);
            }

            return formData;
        },

        async resetSettings() {
            if (!confirm('Are you sure you want to reset all settings to default values? This action cannot be undone.')) {
                return;
            }

            try {
                const formData = new FormData();
                formData.append('action', 'woodash_reset_settings');
                formData.append('nonce', woodash_ajax.nonce);

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.showNotification('Settings reset successfully!', 'success');
                    this.loadRealSettings(); // Reload settings
                } else {
                    this.showNotification('Failed to reset settings: ' + result.data, 'error');
                }
            } catch (error) {
                console.error('Error resetting settings:', error);
                this.showNotification('Error resetting settings', 'error');
            }
        },

        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg max-w-sm ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        },

        updateSaveButtonState() {
            const saveBtn = document.getElementById('save-settings-btn');
            if (saveBtn) {
                if (this.state.hasChanges) {
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save Changes';
                    saveBtn.className = saveBtn.className.replace('bg-gray-400', 'bg-green-500 hover:bg-green-600');
                } else {
                    saveBtn.disabled = true;
                    saveBtn.textContent = 'No Changes';
                    saveBtn.className = saveBtn.className.replace('bg-green-500 hover:bg-green-600', 'bg-gray-400');
                }
            }
        },

        markAsChanged() {
            this.state.hasChanges = true;
            this.updateSaveButtonState();
        },

        loadDefaultSettings() {
            // Load default settings
            const defaultSettings = {
                currency: 'USD',
                timezone: 'UTC',
                date_format: 'Y-m-d',
                low_stock_threshold: 5,
                enable_notifications: true,
                dashboard_refresh_interval: 30
            };
            this.applySettings(defaultSettings);
        },

        initEventListeners() {
            // Save settings button
            const saveSettingsBtn = document.getElementById('save-settings-btn');
            saveSettingsBtn?.addEventListener('click', () => {
                this.saveSettings();
            });

            // Reset settings button
            const resetSettingsBtn = document.getElementById('reset-settings-btn');
            resetSettingsBtn?.addEventListener('click', () => {
                this.resetSettings();
            });

            // Mobile menu toggle
            const menuToggle = document.getElementById('woodash-menu-toggle');
            const sidebar = document.querySelector('.woodash-sidebar');

            menuToggle?.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });

            // Handle click outside to close mobile menu
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768 && 
                    !sidebar.contains(e.target) && 
                    !menuToggle.contains(e.target) && 
                    sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            });

            // Mark as changed when form elements change
            const formElements = document.querySelectorAll('input, select');
            formElements.forEach(element => {
                element.addEventListener('change', () => {
                    this.markAsChanged();
                });
            });

            // Handle form submission
            const settingsForm = document.getElementById('settings-form');
            settingsForm?.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveSettings();
            });
        },

        validateSettings() {
            // Validate form inputs
            const lowStockThreshold = document.getElementById('low-stock-threshold');
            const dashboardRefresh = document.getElementById('dashboard-refresh');

            let isValid = true;

            if (lowStockThreshold && (lowStockThreshold.value < 0 || lowStockThreshold.value > 100)) {
                this.showNotification('Low stock threshold must be between 0 and 100', 'error');
                isValid = false;
            }

            if (dashboardRefresh && (dashboardRefresh.value < 10 || dashboardRefresh.value > 300)) {
                this.showNotification('Dashboard refresh interval must be between 10 and 300 seconds', 'error');
                isValid = false;
            }

            return isValid;
        },

        exportSettings() {
            // Export settings as JSON
            const dataStr = JSON.stringify(this.state.settings, null, 2);
            const dataBlob = new Blob([dataStr], {type: 'application/json'});
            
            const url = URL.createObjectURL(dataBlob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'woodash-settings-' + new Date().toISOString().split('T')[0] + '.json';
            link.click();
            URL.revokeObjectURL(url);
        },

        importSettings(file) {
            // Import settings from JSON file
            const reader = new FileReader();
            reader.onload = (e) => {
                try {
                    const settings = JSON.parse(e.target.result);
                    this.applySettings(settings);
                    this.markAsChanged();
                    this.showNotification('Settings imported successfully!', 'success');
                } catch (error) {
                    this.showNotification('Invalid settings file', 'error');
                }
            };
            reader.readAsText(file);
        }
    };

    // Initialize the settings manager with backend integration
    SettingsManager.init();

    // Add global functions for button clicks
    window.SettingsManager = SettingsManager;
});
</script>

</body>
</html>
