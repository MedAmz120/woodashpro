<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Check if user has required capabilities
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'woodash-pro'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketing Dashboard</title>
    
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

        /* Badge Styles */
        .woodash-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .woodash-badge-success {
            background: #DCFCE7;
            color: #16A34A;
        }

        .woodash-badge-warning {
            background: #FEF3C7;
            color: #D97706;
        }

        .woodash-badge-danger {
            background: #FEE2E2;
            color: #DC2626;
        }

        .woodash-badge-blue {
            background: #DBEAFE;
            color: #2563EB;
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
                <i class="fa-solid fa-shopping-cart w-5"></i>
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
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-marketing')); ?>" class="woodash-nav-link active">
                <i class="fa-solid fa-bullhorn w-5"></i>
                <span>Marketing</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-reports')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-file-chart-line w-5"></i>
                <span>Reports</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-settings')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-gear w-5"></i>
                <span>Settings</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="woodash-main flex-1 p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold woodash-gradient-text">Marketing</h1>
                    <p class="text-gray-500">Manage campaigns, track performance, and boost sales.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="woodash-btn woodash-btn-secondary" id="campaign-analytics-btn">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>Analytics</span>
                    </button>
                    <button class="woodash-btn woodash-btn-primary" id="add-campaign-btn">
                        <i class="fa-solid fa-plus"></i>
                        New Campaign
                    </button>
                </div>
            </header>

            <!-- Marketing Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Active Campaigns</h3>
                            <div class="text-3xl font-bold text-gray-900" data-stat="active-campaigns">8</div>
                            <div class="text-sm text-green-600 mt-1">
                                <i class="fa-solid fa-arrow-up"></i> +2 this week
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-bullhorn"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Email Open Rate</h3>
                            <div class="text-3xl font-bold text-gray-900" data-stat="open-rate">24.5%</div>
                            <div class="text-sm text-green-600 mt-1">
                                <i class="fa-solid fa-arrow-up"></i> +1.2% vs last month
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                            <i class="fa-solid fa-envelope-open"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Click-through Rate</h3>
                            <div class="text-3xl font-bold text-gray-900" data-stat="click-rate">4.2%</div>
                            <div class="text-sm text-red-600 mt-1">
                                <i class="fa-solid fa-arrow-down"></i> -0.3% vs last month
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-mouse-pointer"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Total Revenue</h3>
                            <div class="text-3xl font-bold text-gray-900" data-stat="total-revenue">$15.2K</div>
                            <div class="text-sm text-green-600 mt-1">
                                <i class="fa-solid fa-arrow-up"></i> +8.4% this month
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaign Performance Chart -->
            <div class="woodash-card p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold woodash-gradient-text">Campaign Performance</h3>
                    <div class="flex items-center gap-2">
                        <select id="chart-period" class="text-sm border border-gray-300 rounded-lg px-3 py-1">
                            <option value="7d">Last 7 days</option>
                            <option value="30d" selected>Last 30 days</option>
                            <option value="90d">Last 90 days</option>
                        </select>
                        <button class="woodash-btn woodash-btn-secondary text-sm" id="export-chart">
                            <i class="fa-solid fa-download"></i>
                            Export
                        </button>
                    </div>
                </div>
                <div style="height: 300px;">
                    <canvas id="campaignChart"></canvas>
                </div>
            </div>

            <!-- All Campaigns Table -->
            <div class="woodash-card p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold woodash-gradient-text">All Campaigns</h3>
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input type="text" id="campaign-search" placeholder="Search campaigns..." 
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <select id="campaign-filter" class="text-sm border border-gray-300 rounded-lg px-3 py-2">
                            <option value="all">All Campaigns</option>
                            <option value="active">Active</option>
                            <option value="paused">Paused</option>
                            <option value="draft">Draft</option>
                            <option value="completed">Completed</option>
                        </select>
                        <button class="woodash-btn woodash-btn-primary" id="add-campaign-btn">
                            <i class="fa-solid fa-plus"></i>
                            New Campaign
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full" id="campaigns-table">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Clicks</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Conversions</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic content will be loaded here -->
                        </tbody>
                    </table>
                </div>
                
                <div class="flex justify-between items-center mt-4">
                    <div class="text-sm text-gray-500" id="campaign-count">
                        Showing 0 of 0 campaigns
                    </div>
                    <div class="flex items-center gap-2" id="campaign-pagination">
                        <!-- Pagination will be rendered here -->
                    </div>
                </div>
            </div>

            <!-- Active Campaigns & Email Marketing -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Active Campaigns -->
                <div class="woodash-card p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold woodash-gradient-text">Active Campaigns</h3>
                        <button class="woodash-btn woodash-btn-secondary text-sm" id="view-all-campaigns">
                            View All
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <div>
                                    <div class="font-medium">Summer Sale 2025</div>
                                    <div class="text-sm text-gray-500">Email • Started Aug 1</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-green-600">+15.2%</div>
                                <div class="text-sm text-gray-500">CTR</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <div>
                                    <div class="font-medium">Back to School</div>
                                    <div class="text-sm text-gray-500">Social • Started Aug 10</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-blue-600">+8.7%</div>
                                <div class="text-sm text-gray-500">Engagement</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                                <div>
                                    <div class="font-medium">Flash Sale Weekend</div>
                                    <div class="text-sm text-gray-500">Display Ads • Started Aug 12</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-orange-600">+22.1%</div>
                                <div class="text-sm text-gray-500">Conversions</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email Marketing Stats -->
                <div class="woodash-card p-6">
                    <h3 class="text-lg font-bold woodash-gradient-text mb-4">Email Marketing</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Subscribers</span>
                            <span class="font-semibold">12,456</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Avg. Open Rate</span>
                            <span class="font-semibold text-green-600">24.5%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Avg. Click Rate</span>
                            <span class="font-semibold text-blue-600">4.2%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Unsubscribe Rate</span>
                            <span class="font-semibold text-red-600">1.8%</span>
                        </div>
                        <div class="pt-4 border-t">
                            <div class="mb-2 text-sm font-medium">Recent Performance</div>
                            <div style="height: 150px;">
                                <canvas id="emailChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Marketing Tools & Social Media -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Marketing Tools -->
                <div class="woodash-card p-6">
                    <h3 class="text-lg font-bold woodash-gradient-text mb-4">Marketing Tools</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <button class="p-4 border-2 border-dashed border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors text-center">
                            <i class="fa-solid fa-envelope text-2xl text-blue-600 mb-2"></i>
                            <div class="font-medium">Email Campaign</div>
                            <div class="text-sm text-gray-500">Create email sequence</div>
                        </button>
                        <button class="p-4 border-2 border-dashed border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors text-center">
                            <i class="fa-solid fa-share-nodes text-2xl text-purple-600 mb-2"></i>
                            <div class="font-medium">Social Media</div>
                            <div class="text-sm text-gray-500">Schedule posts</div>
                        </button>
                        <button class="p-4 border-2 border-dashed border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors text-center">
                            <i class="fa-solid fa-bullseye text-2xl text-red-600 mb-2"></i>
                            <div class="font-medium">Ad Campaign</div>
                            <div class="text-sm text-gray-500">Run targeted ads</div>
                        </button>
                        <button class="p-4 border-2 border-dashed border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors text-center">
                            <i class="fa-solid fa-mobile text-2xl text-green-600 mb-2"></i>
                            <div class="font-medium">SMS Marketing</div>
                            <div class="text-sm text-gray-500">Send text messages</div>
                        </button>
                    </div>
                </div>

                <!-- Social Media Performance -->
                <div class="woodash-card p-6">
                    <h3 class="text-lg font-bold woodash-gradient-text mb-4">Social Media Performance</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-facebook text-blue-600 text-xl"></i>
                                <div>
                                    <div class="font-medium">Facebook</div>
                                    <div class="text-sm text-gray-500">2.4K followers</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold">1,234</div>
                                <div class="text-sm text-gray-500">Reach</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-instagram text-pink-600 text-xl"></i>
                                <div>
                                    <div class="font-medium">Instagram</div>
                                    <div class="text-sm text-gray-500">3.1K followers</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold">2,876</div>
                                <div class="text-sm text-gray-500">Engagement</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-twitter text-blue-400 text-xl"></i>
                                <div>
                                    <div class="font-medium">Twitter</div>
                                    <div class="text-sm text-gray-500">1.8K followers</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold">987</div>
                                <div class="text-sm text-gray-500">Impressions</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-linkedin text-blue-700 text-xl"></i>
                                <div>
                                    <div class="font-medium">LinkedIn</div>
                                    <div class="text-sm text-gray-500">892 followers</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold">543</div>
                                <div class="text-sm text-gray-500">Clicks</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <div class="max-w-7xl mx-auto text-center py-4 text-gray-600 text-sm">
                <p>&copy; <?php echo date('Y'); ?> WooDash Pro. All rights reserved.</p>
            </div>
        </footer>
    </main>
</div>

<!-- Campaign Creation Modal -->
<div id="campaign-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold woodash-gradient-text">Create New Campaign</h3>
            <button id="close-campaign-modal" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="campaign-form">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Campaign Name *</label>
                    <input type="text" id="campaign-name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Campaign Type *</label>
                    <select id="campaign-type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="email">Email Marketing</option>
                        <option value="social">Social Media</option>
                        <option value="ppc">Pay-Per-Click</option>
                        <option value="display">Display Ads</option>
                        <option value="sms">SMS Marketing</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Subject/Title</label>
                <input type="text" id="campaign-subject"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Campaign Content</label>
                <textarea id="campaign-content" rows="6"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                          placeholder="Enter your campaign content here..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" id="cancel-campaign" class="woodash-btn woodash-btn-secondary">
                    Cancel
                </button>
                <button type="submit" class="woodash-btn woodash-btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Create Campaign
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 flex items-center gap-3">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-green-600"></div>
        <span class="text-gray-700">Loading...</span>
    </div>
</div>

<!-- Notification Toast -->
<div id="notification-toast" class="fixed top-4 right-4 hidden z-50">
    <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-4 min-w-[300px]">
        <div class="flex items-center gap-3">
            <div id="toast-icon" class="flex-shrink-0"></div>
            <div class="flex-1">
                <div id="toast-title" class="font-medium text-gray-900"></div>
                <div id="toast-message" class="text-sm text-gray-600"></div>
            </div>
            <button id="close-toast" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </div>
</div>

<script>
// WordPress AJAX Configuration
window.woodash_ajax = {
    ajax_url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
    nonce: '<?php echo wp_create_nonce('woodash_nonce'); ?>'
};

document.addEventListener('DOMContentLoaded', function() {
    const MarketingManager = {
        state: {
            campaigns: [],
            currentPage: 1,
            campaignsPerPage: 10,
            searchTerm: '',
            statusFilter: 'all',
            isLoading: false
        },

        init() {
            this.loadMarketingData();
            this.initCharts();
            this.initEventListeners();
            this.loadCampaigns();
        },

        showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
            this.state.isLoading = true;
        },

        hideLoading() {
            document.getElementById('loading-overlay').classList.add('hidden');
            this.state.isLoading = false;
        },

        showNotification(title, message, type = 'success') {
            const toast = document.getElementById('notification-toast');
            const icon = document.getElementById('toast-icon');
            const titleEl = document.getElementById('toast-title');
            const messageEl = document.getElementById('toast-message');
            
            titleEl.textContent = title;
            messageEl.textContent = message;
            
            const iconClass = type === 'success' ? 'fa-check-circle text-green-600' : 
                             type === 'error' ? 'fa-exclamation-circle text-red-600' : 
                             'fa-info-circle text-blue-600';
            
            icon.innerHTML = `<i class="fa-solid ${iconClass}"></i>`;
            
            toast.classList.remove('hidden');
            
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 5000);
        },

        async loadMarketingData() {
            try {
                this.showLoading();
                
                const formData = new FormData();
                formData.append('action', 'woodash_get_marketing_stats');
                formData.append('nonce', woodash_ajax.nonce);

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.updateMarketingStats(result.data);
                } else {
                    console.log('Using demo marketing data');
                    this.loadDemoMarketingData();
                }
            } catch (error) {
                console.error('Error loading marketing data:', error);
                this.showNotification('Error', 'Failed to load marketing data', 'error');
                this.loadDemoMarketingData();
            } finally {
                this.hideLoading();
            }
        },

        async loadCampaigns() {
            try {
                this.showLoading();
                
                const formData = new FormData();
                formData.append('action', 'woodash_get_campaigns');
                formData.append('nonce', woodash_ajax.nonce);
                formData.append('page', this.state.currentPage);
                formData.append('per_page', this.state.campaignsPerPage);

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.state.campaigns = result.data.campaigns || [];
                    this.renderCampaignsTable();
                    this.renderPagination(result.data);
                } else {
                    console.log('Using demo campaign data');
                    this.loadDemoCampaigns();
                }
            } catch (error) {
                console.error('Error loading campaigns:', error);
                this.showNotification('Error', 'Failed to load campaigns', 'error');
                this.loadDemoCampaigns();
            } finally {
                this.hideLoading();
            }
        },

        renderCampaignsTable() {
            const tbody = document.querySelector('#campaigns-table tbody');
            if (!tbody) return;

            tbody.innerHTML = '';

            if (this.state.campaigns.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-3">
                                <i class="fa-solid fa-bullhorn text-4xl text-gray-300"></i>
                                <div>
                                    <div class="font-medium">No campaigns found</div>
                                    <div class="text-sm">Create your first marketing campaign to get started</div>
                                </div>
                                <button onclick="MarketingManager.openCampaignModal()" 
                                        class="woodash-btn woodash-btn-primary">
                                    <i class="fa-solid fa-plus"></i>
                                    Create Campaign
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            this.state.campaigns.forEach(campaign => {
                const row = document.createElement('tr');
                row.className = 'border-b border-gray-200 hover:bg-gray-50 transition-colors';
                
                const statusClass = this.getStatusBadgeClass(campaign.status);
                const typeIcon = this.getTypeIcon(campaign.type);
                
                row.innerHTML = `
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                <i class="${typeIcon} text-gray-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">${this.escapeHtml(campaign.name)}</div>
                                <div class="text-sm text-gray-500">Created ${this.formatDate(campaign.created_at)}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 capitalize">${campaign.type}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                            ${campaign.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">${(campaign.clicks || 0).toLocaleString()}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${(campaign.conversions || 0).toLocaleString()}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">$${(campaign.revenue || 0).toFixed(2)}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button onclick="MarketingManager.editCampaign('${campaign.id}')" 
                                    class="text-blue-600 hover:text-blue-900 p-1 rounded transition-colors"
                                    title="Edit campaign">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button onclick="MarketingManager.duplicateCampaign('${campaign.id}')" 
                                    class="text-green-600 hover:text-green-900 p-1 rounded transition-colors"
                                    title="Duplicate campaign">
                                <i class="fa-solid fa-copy"></i>
                            </button>
                            <button onclick="MarketingManager.deleteCampaign('${campaign.id}')" 
                                    class="text-red-600 hover:text-red-900 p-1 rounded transition-colors"
                                    title="Delete campaign">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
            
            // Update campaign count
            const countElement = document.getElementById('campaign-count');
            if (countElement) {
                countElement.textContent = `Showing ${this.state.campaigns.length} campaigns`;
            }
        },

        renderPagination(data) {
            const pagination = document.getElementById('campaign-pagination');
            if (!pagination || !data.total_pages || data.total_pages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let paginationHtml = '';
            
            // Previous button
            if (data.page > 1) {
                paginationHtml += `
                    <button onclick="MarketingManager.changePage(${data.page - 1})" 
                            class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                        Previous
                    </button>
                `;
            }
            
            // Page numbers
            for (let i = 1; i <= data.total_pages; i++) {
                const isActive = i === data.page;
                paginationHtml += `
                    <button onclick="MarketingManager.changePage(${i})" 
                            class="px-3 py-1 border border-gray-300 rounded-lg text-sm ${isActive ? 'bg-green-600 text-white' : 'hover:bg-gray-50'}">
                        ${i}
                    </button>
                `;
            }
            
            // Next button
            if (data.page < data.total_pages) {
                paginationHtml += `
                    <button onclick="MarketingManager.changePage(${data.page + 1})" 
                            class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                        Next
                    </button>
                `;
            }
            
            pagination.innerHTML = paginationHtml;
        },

        changePage(page) {
            this.state.currentPage = page;
            this.loadCampaigns();
        },

        getStatusBadgeClass(status) {
            switch (status) {
                case 'active': return 'bg-green-100 text-green-800';
                case 'paused': return 'bg-yellow-100 text-yellow-800';
                case 'completed': return 'bg-blue-100 text-blue-800';
                case 'draft': return 'bg-gray-100 text-gray-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        },

        getTypeIcon(type) {
            switch (type) {
                case 'email': return 'fa-solid fa-envelope';
                case 'social': return 'fa-solid fa-share-nodes';
                case 'ppc': return 'fa-solid fa-bullseye';
                case 'display': return 'fa-solid fa-rectangle-ad';
                case 'sms': return 'fa-solid fa-mobile';
                default: return 'fa-solid fa-bullhorn';
            }
        },

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        },

        openCampaignModal() {
            document.getElementById('campaign-modal').classList.remove('hidden');
            document.getElementById('campaign-name').focus();
        },

        closeCampaignModal() {
            document.getElementById('campaign-modal').classList.add('hidden');
            document.getElementById('campaign-form').reset();
        },

        async createCampaign() {
            const name = document.getElementById('campaign-name').value.trim();
            const type = document.getElementById('campaign-type').value;
            const subject = document.getElementById('campaign-subject').value.trim();
            const content = document.getElementById('campaign-content').value.trim();

            if (!name) {
                this.showNotification('Error', 'Campaign name is required', 'error');
                return;
            }

            try {
                this.showLoading();
                
                const formData = new FormData();
                formData.append('action', 'woodash_create_campaign');
                formData.append('nonce', woodash_ajax.nonce);
                formData.append('name', name);
                formData.append('type', type);
                formData.append('subject', subject);
                formData.append('content', content);
                formData.append('status', 'draft');

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.showNotification('Success', 'Campaign created successfully');
                    this.closeCampaignModal();
                    this.loadCampaigns();
                    this.loadMarketingData(); // Refresh stats
                } else {
                    this.showNotification('Error', result.data || 'Failed to create campaign', 'error');
                }
            } catch (error) {
                console.error('Error creating campaign:', error);
                this.showNotification('Error', 'Network error occurred', 'error');
            } finally {
                this.hideLoading();
            }
        },

        async deleteCampaign(campaignId) {
            if (!confirm('Are you sure you want to delete this campaign? This action cannot be undone.')) {
                return;
            }

            try {
                this.showLoading();
                
                const formData = new FormData();
                formData.append('action', 'woodash_delete_campaign');
                formData.append('nonce', woodash_ajax.nonce);
                formData.append('campaign_id', campaignId);

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.showNotification('Success', 'Campaign deleted successfully');
                    this.loadCampaigns();
                    this.loadMarketingData(); // Refresh stats
                } else {
                    this.showNotification('Error', result.data || 'Failed to delete campaign', 'error');
                }
            } catch (error) {
                console.error('Error deleting campaign:', error);
                this.showNotification('Error', 'Network error occurred', 'error');
            } finally {
                this.hideLoading();
            }
        },

        editCampaign(campaignId) {
            // TODO: Implement edit functionality
            this.showNotification('Info', 'Edit functionality coming soon', 'info');
        },

        duplicateCampaign(campaignId) {
            // TODO: Implement duplicate functionality  
            this.showNotification('Info', 'Duplicate functionality coming soon', 'info');
        },

        updateMarketingStats(stats) {
            // Update stats cards with real data
            const elements = {
                activeCampaigns: document.querySelector('[data-stat="active-campaigns"]'),
                openRate: document.querySelector('[data-stat="open-rate"]'),
                clickRate: document.querySelector('[data-stat="click-rate"]'),
                totalRevenue: document.querySelector('[data-stat="total-revenue"]')
            };

            if (elements.activeCampaigns) {
                elements.activeCampaigns.textContent = stats.active_campaigns || '0';
            }
            if (elements.openRate && stats.email_stats) {
                elements.openRate.textContent = stats.email_stats.open_rate + '%';
            }
            if (elements.clickRate && stats.email_stats) {
                elements.clickRate.textContent = stats.email_stats.click_rate + '%';
            }
            if (elements.totalRevenue) {
                elements.totalRevenue.textContent = '$' + (stats.total_revenue || 0).toLocaleString();
            }
        },

        loadDemoMarketingData() {
            // Fallback demo data
            this.updateMarketingStats({
                active_campaigns: 8,
                total_revenue: 15200,
                email_stats: {
                    open_rate: 24.5,
                    click_rate: 4.2
                }
            });
        },

        loadDemoCampaigns() {
            // Fallback demo data
            this.state.campaigns = [
                {
                    id: '1',
                    name: 'Summer Sale Email Campaign',
                    type: 'email',
                    status: 'active',
                    clicks: 1234,
                    conversions: 89,
                    revenue: 2567.50,
                    created_at: '2025-08-01 10:00:00'
                },
                {
                    id: '2',
                    name: 'Social Media Back to School',
                    type: 'social',
                    status: 'paused',
                    clicks: 856,
                    conversions: 45,
                    revenue: 1289.00,
                    created_at: '2025-08-10 14:30:00'
                },
                {
                    id: '3',
                    name: 'Google Ads Holiday Promo',
                    type: 'ppc',
                    status: 'draft',
                    clicks: 0,
                    conversions: 0,
                    revenue: 0,
                    created_at: '2025-08-15 09:15:00'
                }
            ];
            this.renderCampaignsTable();
        },

        initEventListeners() {
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

            // Campaign modal events
            document.getElementById('add-campaign-btn')?.addEventListener('click', () => {
                this.openCampaignModal();
            });

            document.getElementById('close-campaign-modal')?.addEventListener('click', () => {
                this.closeCampaignModal();
            });

            document.getElementById('cancel-campaign')?.addEventListener('click', () => {
                this.closeCampaignModal();
            });

            // Campaign form submission
            document.getElementById('campaign-form')?.addEventListener('submit', (e) => {
                e.preventDefault();
                this.createCampaign();
            });

            // Toast close button
            document.getElementById('close-toast')?.addEventListener('click', () => {
                document.getElementById('notification-toast').classList.add('hidden');
            });

            // Search and filter
            document.getElementById('campaign-search')?.addEventListener('input', (e) => {
                this.state.searchTerm = e.target.value;
                this.filterCampaigns();
            });

            document.getElementById('campaign-filter')?.addEventListener('change', (e) => {
                this.state.statusFilter = e.target.value;
                this.filterCampaigns();
            });
        },

        filterCampaigns() {
            // TODO: Implement client-side filtering or server-side filtering
            // For now, just reload campaigns
            this.state.currentPage = 1;
            this.loadCampaigns();
        },

        initCharts() {
            this.initCampaignChart();
            this.initEmailChart();
        },

        initCampaignChart() {
            const ctx = document.getElementById('campaignChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Aug 1', 'Aug 5', 'Aug 10', 'Aug 14'],
                    datasets: [
                        {
                            label: 'Email Campaign',
                            data: [1200, 1800, 2400, 2800],
                            borderColor: '#00CC61',
                            backgroundColor: 'rgba(0, 204, 97, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Social Media',
                            data: [800, 1200, 1600, 2200],
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Display Ads',
                            data: [600, 900, 1400, 1900],
                            borderColor: '#F59E0B',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        },

        initEmailChart() {
            const ctx = document.getElementById('emailChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Open Rate %',
                        data: [22.1, 24.5, 26.8, 24.2],
                        backgroundColor: '#00CC61'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 30,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    };

    // Initialize the marketing manager
    MarketingManager.init();
});
</script>

</body>
</html>
