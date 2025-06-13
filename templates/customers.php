<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers Dashboard</title>
    <!-- Google Fonts & Font Awesome for icons -->
    <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js: Use a valid CDN and only load once -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Lottie player: Only load once -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        /* Enhanced Base Styles */
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

        .woodash-float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Sidebar Specific Styles */
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
            margin-left: 16rem; /* Equivalent to w-64 */
            transition: margin-left 0.3s ease;
            flex-grow: 1;
            overflow-y: auto; /* Add this line */
        }

        /* Mobile Menu Toggle */
        .woodash-menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 60;
        }

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
                display: flex;
            }
        }

        /* Enhanced Card Styles */
        .woodash-card {
            background: var(--card-background);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            transition: var(--transition-base);
            box-shadow: var(--shadow-sm);
            overflow: hidden; /* Ensure child elements don't overflow rounded corners */
        }

        .woodash-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Metric Card Styles */
        .woodash-metric-card {
             background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            transition: var(--transition-base);
            box-shadow: var(--shadow-sm);
        }

        .woodash-metric-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .woodash-metric-title {
            font-size: 0.875rem; /* sm */
            font-weight: 500; /* medium */
            color: #6B7280; /* gray-500 */
            margin-bottom: 0.25rem;
        }

        .woodash-metric-value {
            font-size: 1.875rem; /* 3xl */
            font-weight: 700; /* bold */
            color: #1F2937; /* gray-900 */
        }

        .woodash-metric-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem; /* xl */
            color: white;
        }

        .woodash-metric-green {
            background: linear-gradient(135deg, #00CC61, #00b357);
        }

         .woodash-metric-yellow {
            background: linear-gradient(135deg, #F59E0B, #D97706);
        }

        .woodash-metric-red {
            background: linear-gradient(135deg, #EF4444, #DC2626);
        }

         .woodash-metric-blue {
            background: linear-gradient(135deg, #3B82F6, #2563EB);
        }

        /* Enhanced Button Styles */
        .woodash-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.625rem 1.25rem; /* px-5 py-2.5 */
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            transition: var(--transition-base);
            cursor: pointer;
            gap: 0.5rem;
        }

        .woodash-btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            box-shadow: var(--shadow-md);
        }

        .woodash-btn-primary:hover {
             background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }

         .woodash-btn-secondary {
            background: rgba(255, 255, 255, 0.8);
            color: #374151;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        .woodash-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.95);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        /* Enhanced Table Styles */
        .woodash-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .woodash-table thead th {
            background-color: #F9FAFB;
            color: #6B7280;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            text-align: left;
        }

        .woodash-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s ease;
        }

        .woodash-table tbody tr:last-child {
            border-bottom: none;
        }

        .woodash-table tbody td {
            padding: 1rem 1rem;
            font-size: 0.875rem;
            color: #374151;
        }

        .woodash-table tbody tr:hover {
            background-color: #F3F4F6;
        }

        .woodash-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px; /* full rounded */
            font-size: 0.75rem;
            font-weight: 500;
        }

        .woodash-search-container {
            position: relative;
            display: flex;
            align-items: center;
            flex-grow: 1;
            max-width: 300px;
        }

        .woodash-search-input {
            width: 100%;
            padding: 0.625rem 2.5rem 0.625rem 1rem; /* Adjust padding for icons */
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            background: rgba(255, 255, 255, 0.8);
            transition: var(--transition-base);
        }

        .woodash-search-input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 204, 97, 0.1);
        }

        .woodash-search-icon {
            position: absolute;
            left: 0.75rem;
            color: #6B7280;
            font-size: 1rem;
        }

         .woodash-search-button {
             position: absolute;
             right: 0.5rem;
             background: none;
             border: none;
             color: #6B7280;
             cursor: pointer;
             padding: 0.25rem;
             border-radius: 0.25rem;
             transition: var(--transition-base);
         }

         .woodash-search-button:hover {
             color: var(--primary-color);
             background-color: #F3F4F6;
         }

         .woodash-scrollbar::-webkit-scrollbar {
             width: 8px;
             height: 8px;
         }

         .woodash-scrollbar::-webkit-scrollbar-track {
             background: rgba(0, 0, 0, 0.05);
             border-radius: 10px;
         }

         .woodash-scrollbar::-webkit-scrollbar-thumb {
             background: rgba(0, 0, 0, 0.2);
             border-radius: 10px;
         }

         .woodash-scrollbar::-webkit-scrollbar-thumb:hover {
             background: rgba(0, 0, 0, 0.3);
         }

         /* Fade In Animation */
         .woodash-fade-in {
             opacity: 0;
             animation: fadeIn 0.5s ease-out forwards;
         }

         @keyframes fadeIn {
             to { opacity: 1; }
         }

         /* Slide Up Animation */
         .woodash-slide-up {
             opacity: 0;
             transform: translateY(10px);
             animation: slideUp 0.5s ease-out forwards;
         }

         @keyframes slideUp {
             to { opacity: 1; transform: translateY(0); }
         }

    </style>
</head>

<body>
<button id="woodash-menu-toggle" class="woodash-menu-toggle woodash-btn woodash-btn-secondary woodash-hover-card">
    <i class="fa-solid fa-bars"></i>
</button>

<div id="woodash-dashboard" class="woodash-fullscreen flex">
    <!-- Background Orbs -->
    <div class="woodash-bg-animation">
        <div class="woodash-orb woodash-orb-1"></div>
        <div class="woodash-orb woodash-orb-2"></div>
        <div class="woodash-orb woodash-orb-3"></div>

        <!-- Background Lines -->
        <div class="woodash-line woodash-line-1"></div>
        <div class="woodash-line woodash-line-2"></div>
        <div class="woodash-line woodash-line-3"></div>
        <div class="woodash-line woodash-line-4"></div>

        <!-- Background Shimmer -->
        <div class="woodash-shimmer"></div>
    </div>

    <!-- Sidebar -->
    <aside class="woodash-sidebar w-64 bg-white/90 border-r border-gray-100 p-6 woodash-glass-effect">
        <div class="flex items-center gap-3 mb-8 woodash-fade-in">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center woodash-glow">
                <i class="fa-solid fa-users text-white text-xl"></i>
            </div>
            <h2 class="text-xl font-bold woodash-gradient-text">WooDash Pro</h2>
        </div>

        <!-- Main Navigation -->
        <nav class="space-y-1">
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-dashboard')); ?>" 
               class="woodash-nav-link woodash-hover-card woodash-slide-up group" 
               style="animation-delay: 0.1s">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-gauge text-white"></i>
                    </div>
                    <span>Dashboard</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-products')); ?>" 
               class="woodash-nav-link woodash-hover-card woodash-slide-up group" 
               style="animation-delay: 0.2s">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-box text-white"></i>
                    </div>
                    <span>Products</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-customers')); ?>" 
               class="woodash-nav-link active woodash-hover-card woodash-slide-up group" 
               style="animation-delay: 0.3s">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-users text-white"></i>
                    </div>
                    <span>Customers</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-stock')); ?>" 
               class="woodash-nav-link woodash-hover-card woodash-slide-up group" 
               style="animation-delay: 0.4s">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-boxes-stacked text-white"></i>
                    </div>
                    <span>Stock</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-reviews')); ?>" 
               class="woodash-nav-link woodash-hover-card woodash-slide-up group" 
               style="animation-delay: 0.5s">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-star text-white"></i>
                    </div>
                    <span>Reviews</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-coupons')); ?>" 
               class="woodash-nav-link woodash-hover-card woodash-slide-up group" 
               style="animation-delay: 0.6s">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-ticket text-white"></i>
                    </div>
                    <span>Coupons</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-settings')); ?>"
               class="woodash-nav-link woodash-hover-card group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-gear text-white"></i>
                    </div>
                    <span>Settings</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>
        </nav>
    </aside>

    <main class="woodash-main flex-1 p-6 md:p-8">
        <div class="max-w-7xl mx-auto woodash-fade-in">
            <!-- Header -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 woodash-fade-in">
                <div>
                    <h1 class="text-2xl font-bold woodash-gradient-text">Customers</h1>
                    <p class="text-gray-500">Manage your customer database and track customer activity.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="woodash-btn woodash-btn-primary woodash-hover-card" id="add-customer-btn">
                        <i class="fa-solid fa-plus mr-2"></i>
                        <span>Add New Customer</span>
                    </button>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Customers -->
                <div class="woodash-metric-card woodash-animate-in woodash-hover-card" style="animation-delay: 0.1s">
                    <div class="flex items-center gap-4">
                        <div class="woodash-metric-icon woodash-metric-green woodash-glow">
                            <i class="fa-solid fa-users text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="woodash-metric-title">Total Customers</p>
                            <h3 class="woodash-metric-value">2,451</h3>
                        </div>
                    </div>
                </div>

                <!-- New Customers -->
                <div class="woodash-metric-card woodash-animate-in woodash-hover-card" style="animation-delay: 0.2s">
                    <div class="flex items-center gap-4">
                         <div class="woodash-metric-icon woodash-metric-blue woodash-glow">
                            <i class="fa-solid fa-user-plus text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="woodash-metric-title">New Customers</p>
                            <h3 class="woodash-metric-value">+128</h3>
                        </div>
                    </div>
                </div>

                <!-- Active Customers -->
                <div class="woodash-metric-card woodash-animate-in woodash-hover-card" style="animation-delay: 0.3s">
                    <div class="flex items-center gap-4">
                        <div class="woodash-metric-icon woodash-metric-blue woodash-glow">
                            <i class="fa-solid fa-user-check text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="woodash-metric-title">Active Customers</p>
                            <h3 class="woodash-metric-value">1,892</h3>
                        </div>
                    </div>
                </div>

                <!-- Average Order Value -->
                <div class="woodash-metric-card woodash-animate-in woodash-hover-card" style="animation-delay: 0.4s">
                    <div class="flex items-center gap-4">
                        <div class="woodash-metric-icon woodash-metric-yellow woodash-glow">
                            <i class="fa-solid fa-dollar-sign text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="woodash-metric-title">Avg. Order Value</p>
                            <h3 class="woodash-metric-value">$89.99</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Analytics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Customer Growth Chart -->
                <div class="woodash-card p-6 woodash-animate-in" style="animation-delay: 0.5s">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold woodash-gradient-text">Customer Growth</h3>
                            <p class="text-sm text-gray-500">Last 30 days</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card" data-period="7">7D</button>
                            <button class="woodash-btn woodash-btn-primary woodash-hover-card" data-period="30">30D</button>
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card" data-period="90">90D</button>
                        </div>
                    </div>
                    <div class="h-[300px]">
                        <canvas id="customer-growth-chart"></canvas>
                    </div>
                </div>

                <!-- Customer Segments -->
                <div class="woodash-card p-6 woodash-animate-in" style="animation-delay: 0.6s">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold woodash-gradient-text">Customer Segments</h3>
                            <p class="text-sm text-gray-500">Distribution by type</p>
                        </div>
                        <button class="woodash-btn woodash-btn-secondary woodash-hover-card">
                            <i class="fa-solid fa-download mr-2"></i>
                            <span>Export</span>
                        </button>
                    </div>
                    <div class="h-[300px]">
                        <canvas id="customer-segments-chart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Customer Activity Timeline -->
            <div class="woodash-card mb-8 woodash-animate-in" style="animation-delay: 0.7s">
                <div class="flex justify-between items-center p-6 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-bold woodash-gradient-text">Recent Customer Activity</h3>
                        <p class="text-sm text-gray-500">Latest customer interactions</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="refresh-activity">
                            <i class="fa-solid fa-rotate mr-2"></i>
                            <span>Refresh</span>
                        </button>
                        <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="export-activity">
                            <i class="fa-solid fa-download mr-2"></i>
                            <span>Export</span>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4" id="customer-activity-timeline">
                        <!-- Activity items will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Add this before the customers table -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4 woodash-fade-in">
                <div class="flex items-center gap-4">
                    <div class="flex gap-2" id="bulk-actions" style="display: none;">
                        <select class="woodash-btn woodash-btn-secondary woodash-hover-card" id="bulk-action-select">
                            <option value="">Bulk Actions</option>
                            <option value="export">Export Selected</option>
                            <option value="delete">Delete Selected</option>
                            <option value="change-role">Change Role</option>
                            <option value="change-status">Change Status</option>
                        </select>
                        <button class="woodash-btn woodash-btn-primary woodash-hover-card" id="apply-bulk-action">
                            Apply
                        </button>
                    </div>
                    <div class="text-sm text-gray-500">
                        Showing <span id="showing-count">1-10</span> of <span id="total-count">2,451</span> customers
                    </div>
                </div>
                <div class="flex items-center gap-4">
                     <div class="woodash-search-container flex-1">
                         <i class="fa-solid fa-search woodash-search-icon"></i>
                         <input type="text" 
                                placeholder="Search customers..." 
                                class="woodash-search-input" style="border:none;"
                                id="customer-search">
                         <button id="woodash-clear-customer-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-600 focus:outline-none hidden text-base transition-colors duration-200">
                             <i class="fa-solid fa-times"></i>
                         </button>
                         
                         <div class="woodash-search-results" id="customer-search-results">
                             <!-- Results will be populated here -->
                         </div>
                         <button class="woodash-search-button">
                             <i class="fa-solid fa-arrow-right"></i>
                         </button>
                     </div>

                    <div class="flex gap-2">
                        <select class="woodash-btn woodash-btn-secondary woodash-hover-card" id="status-filter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="blocked">Blocked</option>
                        </select>
                        <select class="woodash-btn woodash-btn-secondary woodash-hover-card" id="role-filter">
                            <option value="">All Roles</option>
                            <option value="customer">Customer</option>
                            <option value="wholesale">Wholesale</option>
                            <option value="vip">VIP</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Customers Table -->
            <div class="woodash-card overflow-hidden woodash-animate-in" style="animation-delay: 0.6s">
                <div class="overflow-x-auto woodash-scrollbar">
                    <table class="woodash-table w-full" id="customers-table">
                        <thead>
                            <tr>
                                <th class="w-12 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" class="rounded border-gray-300 text-[#00CC61] focus:ring-[#00CC61]" id="select-all">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="name">
                                    Customer <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="email">
                                    Email <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="orders">
                                    Orders <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="total">
                                    Total Spent <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="status">
                                    Status <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customers-table-body">
                            <!-- Customers will be loaded here by JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add this after the customers table -->
            <div class="flex justify-between items-center mt-4">
                <div class="flex items-center gap-2">
                    <select class="woodash-btn woodash-btn-secondary woodash-hover-card" id="items-per-page">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
                <div class="flex gap-2" id="pagination">
                    <!-- Pagination will be generated here -->
                </div>
            </div>
        </div>
    
    <footer>
    <div class="max-w-7xl mx-auto text-center py-4 text-gray-600 text-sm">
        <p>&copy; <?php echo date('Y'); ?> WooDash Pro. All rights reserved.</p>
    </div>
</footer>
    </main>

    <!-- Add Customer Modal -->
    <div id="add-customer-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden woodash-fade-in">
    <div class="woodash-card w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 woodash-glass-effect relative">
        <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-4">
            <h3 class="text-xl font-bold woodash-gradient-text">Add New Customer</h3>
            <button class="text-gray-400 hover:text-red-600 transition-colors" id="close-add-customer-modal">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <form id="add-customer-form" class="space-y-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer-first-name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                    <input type="text" id="customer-first-name" name="customer-first-name" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                </div>
                <div>
                    <label for="customer-last-name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                    <input type="text" id="customer-last-name" name="customer-last-name" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                </div>
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer-email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" id="customer-email" name="customer-email" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                </div>
                <div>
                    <label for="customer-phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="tel" id="customer-phone" name="customer-phone"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="customer-address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea id="customer-address" name="customer-address" rows="3"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2"></textarea>
            </div>

            <!-- Customer Role -->
            <div>
                <label for="customer-role" class="block text-sm font-medium text-gray-700 mb-1">Customer Role</label>
                <select id="customer-role" name="customer-role"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                    <option value="customer">Regular Customer</option>
                    <option value="wholesale">Wholesale Customer</option>
                    <option value="vip">VIP Customer</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" class="woodash-btn woodash-btn-secondary" id="cancel-add-customer-btn">Cancel</button>
                <button type="submit" class="woodash-btn woodash-btn-primary" id="save-customer-btn">
                    <i class="fa-solid fa-save mr-2"></i>
                    Save Customer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- User Profile -->
<div class="fixed bottom-0 left-0 p-4 bg-white/90 border-t border-r border-gray-100 rounded-tr-lg shadow-lg woodash-glass-effect" style="z-index:1000;">
    <div class="flex items-center gap-3 px-2">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center">
            <i class="fa-solid fa-user text-white"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">Admin User</p>
            <p class="text-xs text-gray-500 truncate">admin@example.com</p>
        </div>
        <button class="p-1 rounded-lg hover:bg-gray-100 transition-colors duration-200">
            <i class="fa-solid fa-ellipsis-vertical text-gray-400"></i>
        </button>
    </div>
</div>

<button id="woodash-scroll-to-top" class="fixed bottom-6 right-6 woodash-btn woodash-btn-primary rounded-full w-12 h-12 flex items-center justify-center woodash-hover-card woodash-glow" style="display: none;">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize customer management functionality
    const CustomerManager = {
        state: {
            currentPage: 1,
            itemsPerPage: 10,
            totalItems: 2451,
            sortColumn: 'name',
            sortDirection: 'asc',
            selectedItems: new Set(),
            filters: {
                status: '',
                role: ''
            },
            searchQuery: '',
            isLoading: false,
            searchTimeout: null
        },

        init() {
            this.initEventListeners();
            this.initSearch();
            this.initFilters();
            this.initBulkActions();
            this.loadCustomers();
            this.initAnimations();
            this.initCharts();
            this.loadCustomerActivity();
            this.initPagination();
        },

        initEventListeners() {
            // Add Customer Modal
            const addCustomerBtn = document.getElementById('add-customer-btn');
            const addCustomerModal = document.getElementById('add-customer-modal');
            const closeAddCustomerModal = document.getElementById('close-add-customer-modal');
            const cancelAddCustomerBtn = document.getElementById('cancel-add-customer-btn');
            const addCustomerForm = document.getElementById('add-customer-form');

            if (addCustomerBtn) {
                addCustomerBtn.addEventListener('click', () => {
                    addCustomerModal.classList.remove('hidden');
                });
            }

            if (closeAddCustomerModal) {
                closeAddCustomerModal.addEventListener('click', () => {
                    addCustomerModal.classList.add('hidden');
                });
            }

            if (cancelAddCustomerBtn) {
                cancelAddCustomerBtn.addEventListener('click', () => {
                    addCustomerModal.classList.add('hidden');
                });
            }

            if (addCustomerForm) {
                addCustomerForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handleAddCustomer(e.target);
                });
            }

            // Search functionality
            const searchInput = document.getElementById('customer-search');
            const clearSearchBtn = document.getElementById('woodash-clear-customer-search');

            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    // Debounce the search input to avoid excessive requests
                    if (this.searchTimeout) {
                        clearTimeout(this.searchTimeout);
                    }
                    this.searchTimeout = setTimeout(() => {
                        this.state.searchQuery = e.target.value.trim();
                        this.state.currentPage = 1; // Reset to first page on search
                        this.loadCustomers();
                    }, 300); // Wait for 300ms after the user stops typing

                    if (e.target.value.trim()) {
                        clearSearchBtn.classList.remove('hidden');
                    } else {
                        clearSearchBtn.classList.add('hidden');
                    }
                });
            }

            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    clearSearchBtn.classList.add('hidden');
                    this.state.searchQuery = '';
                    this.loadCustomers();
                });
            }

            // Sort functionality
            const sortHeaders = document.querySelectorAll('[data-sort]');
            sortHeaders.forEach(header => {
                header.addEventListener('click', () => {
                    const column = header.dataset.sort;
                    if (this.state.sortColumn === column) {
                        this.state.sortDirection = this.state.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.state.sortColumn = column;
                        this.state.sortDirection = 'asc';
                    }
                    this.loadCustomers();
                });
            });
        },

        initSearch() {
            // Initialize search functionality
            const searchInput = document.getElementById('customer-search');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    this.state.searchQuery = e.target.value;
                    this.loadCustomers();
                });
            }
        },

        initFilters() {
            // Initialize filter functionality
            const statusFilter = document.getElementById('status-filter');
            const roleFilter = document.getElementById('role-filter');

            if (statusFilter) {
                statusFilter.addEventListener('change', (e) => {
                    this.state.filters.status = e.target.value;
                    this.loadCustomers();
                });
            }

            if (roleFilter) {
                roleFilter.addEventListener('change', (e) => {
                    this.state.filters.role = e.target.value;
                    this.loadCustomers();
                });
            }
        },

        initBulkActions() {
            // Initialize bulk actions functionality
            const selectAll = document.getElementById('select-all');
            if (selectAll) {
                selectAll.addEventListener('change', (e) => {
                    const checkboxes = document.querySelectorAll('#customers-table-body input[type="checkbox"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = e.target.checked;
                        if (e.target.checked) {
                            this.state.selectedItems.add(checkbox.value);
                        } else {
                            this.state.selectedItems.delete(checkbox.value);
                        }
                    });
                });
            }
        },

        loadCustomers() {
            // Load customers data
            const tbody = document.getElementById('customers-table-body');
            if (!tbody) return;

            // Show loading state (optional)
            tbody.innerHTML = '<tr><td colspan="7" class="text-center p-4"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Loading customers...</td></tr>';

            // In a real scenario, you would make an AJAX call here
            // Example using fetch with a placeholder AJAX URL and nonce
            // fetch(ajaxurl, {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/x-www-form-urlencoded',
            //     },
            //     body: new URLSearchParams({
            //         action: 'woodash_fetch_customers',
            //         nonce: woodashData.nonce,
            //         page: this.state.currentPage,
            //         items_per_page: this.state.itemsPerPage,
            //         sort_column: this.state.sortColumn,
            //         sort_direction: this.state.sortDirection,
            //         filters: JSON.stringify(this.state.filters),
            //         search_query: this.state.searchQuery
            //     })
            // })
            // .then(response => response.json())
            // .then(data => {
            //     if (data.success) {
            //         this.renderCustomers(data.data.customers);
            //         this.updatePagination(data.data.total_items);
            //     } else {
            //         console.error('Error fetching customers:', data.data);
            //         tbody.innerHTML = '<tr><td colspan="7" class="text-center p-4 text-red-500"><i class="fa-solid fa-exclamation-circle mr-2"></i> Error loading customers.</td></tr>';
            //     }
            // })
            // .catch(error => {
            //     console.error('Fetch error:', error);
            //      tbody.innerHTML = '<tr><td colspan="7" class="text-center p-4 text-red-500"><i class="fa-solid fa-exclamation-circle mr-2"></i> An error occurred while loading customers.</td></tr>';
            // });

            // Sample data (replace with actual data fetching)
            const customers = [
                {
                    id: '1',
                    name: 'John Doe',
                    email: 'john@example.com',
                    orders: 12,
                    total: '$1,299.99',
                    status: 'active'
                },
                {
                    id: '2',
                    name: 'Jane Smith',
                    email: 'jane@example.com',
                    orders: 8,
                    total: '$899.99',
                    status: 'active'
                },
                {
                    id: '3',
                    name: 'Bob Johnson',
                    email: 'bob@example.com',
                    orders: 3,
                    total: '$299.99',
                    status: 'inactive'
                }
            ];

            let html = '';
            customers.forEach(customer => {
                const statusBadgeClass = customer.status === 'active' ? 'bg-green-100 text-green-800' :
                                       customer.status === 'inactive' ? 'bg-gray-100 text-gray-800' :
                                       'bg-red-100 text-red-800';

                html += `
                    <tr class="woodash-fade-in">
                        <td>
                            <input type="checkbox" class="rounded border-gray-300 text-[#00CC61] focus:ring-[#00CC61]" value="${customer.id}">
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fa-solid fa-user text-gray-500"></i>
                                </div>
                                <span class="font-medium text-gray-900">${customer.name}</span>
                            </div>
                        </td>
                        <td>${customer.email}</td>
                        <td>${customer.orders}</td>
                        <td>${customer.total}</td>
                        <td>
                            <span class="woodash-badge ${statusBadgeClass}">${customer.status.charAt(0).toUpperCase() + customer.status.slice(1)}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button class="woodash-btn woodash-btn-icon woodash-btn-secondary" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="woodash-btn woodash-btn-icon woodash-btn-secondary" title="View Orders">
                                    <i class="fa-solid fa-shopping-cart"></i>
                                </button>
                                <button class="woodash-btn woodash-btn-icon woodash-btn-danger" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        },

        initAnimations() {
            // Initialize animations
            const cards = document.querySelectorAll('.woodash-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        },

        handleAddCustomer(form) {
            // Handle adding a new customer
            const formData = new FormData(form);
            const customerData = Object.fromEntries(formData.entries());
            
            // Here you would typically make an API call to save the customer
            console.log('Adding customer:', customerData);
            
            // Close the modal and refresh the customer list
            document.getElementById('add-customer-modal').classList.add('hidden');
            this.loadCustomers();
        },

        initCharts() {
            // Customer Growth Chart
            const growthCtx = document.getElementById('customer-growth-chart')?.getContext('2d');
            if (growthCtx) {
                new Chart(growthCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'New Customers',
                            data: [65, 59, 80, 81, 56, 55],
                            borderColor: '#00CC61',
                            tension: 0.4,
                            fill: true,
                            backgroundColor: 'rgba(0, 204, 97, 0.1)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: { family: 'Inter', size: 14 },
                                bodyFont: { family: 'Inter', size: 13 }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0, 0, 0, 0.05)' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // Customer Segments Chart
            const segmentsCtx = document.getElementById('customer-segments-chart')?.getContext('2d');
            if (segmentsCtx) {
                new Chart(segmentsCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Regular', 'Wholesale', 'VIP'],
                        datasets: [{
                            data: [65, 25, 10],
                            backgroundColor: [
                                '#00CC61',
                                '#3B82F6',
                                '#8B5CF6'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            }
                        }
                    }
                });
            }
        },

        loadCustomerActivity() {
            const timeline = document.getElementById('customer-activity-timeline');
            if (!timeline) return;

            // Sample activity data
            const activities = [
                {
                    type: 'order',
                    customer: 'John Doe',
                    action: 'placed an order',
                    details: 'Order #1234 - $299.99',
                    time: '2 minutes ago'
                },
                {
                    type: 'registration',
                    customer: 'Jane Smith',
                    action: 'registered a new account',
                    details: 'VIP Customer',
                    time: '15 minutes ago'
                },
                {
                    type: 'review',
                    customer: 'Bob Johnson',
                    action: 'left a review',
                    details: '5 stars - "Great product!"',
                    time: '1 hour ago'
                }
            ];

            let html = '';
            activities.forEach(activity => {
                const iconClass = activity.type === 'order' ? 'fa-shopping-cart text-blue-500' :
                                 activity.type === 'registration' ? 'fa-user-plus text-green-500' :
                                 'fa-star text-yellow-500';

                html += `
                    <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 woodash-fade-in">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fa-solid ${iconClass}"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900">${activity.customer} ${activity.action}</p>
                                <span class="text-sm text-gray-500">${activity.time}</span>
                            </div>
                            <p class="text-sm text-gray-500">${activity.details}</p>
                        </div>
                    </div>
                `;
            });

            timeline.innerHTML = html;
        },

        initPagination() {
            const pagination = document.getElementById('pagination');
            if (!pagination) return;

            const totalPages = Math.ceil(this.state.totalItems / this.state.itemsPerPage);
            let html = '';

            // Previous button
            html += `
                <button class="woodash-btn woodash-btn-secondary woodash-hover-card ${this.state.currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}"
                        ${this.state.currentPage === 1 ? 'disabled' : ''}>
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
            `;

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= this.state.currentPage - 2 && i <= this.state.currentPage + 2)) {
                    html += `
                        <button class="woodash-btn ${i === this.state.currentPage ? 'woodash-btn-primary' : 'woodash-btn-secondary'} woodash-hover-card"
                                data-page="${i}">
                            ${i}
                        </button>
                    `;
                } else if (i === this.state.currentPage - 3 || i === this.state.currentPage + 3) {
                    html += `<span class="px-2">...</span>`;
                }
            }

            // Next button
            html += `
                <button class="woodash-btn woodash-btn-secondary woodash-hover-card ${this.state.currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}"
                        ${this.state.currentPage === totalPages ? 'disabled' : ''}>
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            `;

            pagination.innerHTML = html;

            // Add click handlers
            pagination.querySelectorAll('button[data-page]').forEach(button => {
                button.addEventListener('click', () => {
                    this.state.currentPage = parseInt(button.dataset.page);
                    this.loadCustomers();
                });
            });
        }
    };

    // Initialize the customer manager
    CustomerManager.init();
});
</script>

</body>
</html>
