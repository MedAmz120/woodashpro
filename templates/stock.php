<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Dashboard</title>
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

         .woodash-modal-bg { background: rgba(0,0,0,0.5); }

    </style>
</head>
<body>

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
                <i class="fa-solid fa-boxes-stacked text-white text-xl"></i>
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
               class="woodash-nav-link woodash-hover-card woodash-slide-up group" 
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
               class="woodash-nav-link active woodash-hover-card woodash-slide-up group" 
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
               class="woodash-nav-link woodash-hover-card woodash-slide-up group" 
               style="animation-delay: 0.7s">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-gear text-white"></i>
                    </div>
                    <span>Settings</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>
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
        </nav>
    </aside>

    <main class="woodash-main flex-1 p-6 md:p-8">
        <div class="max-w-7xl mx-auto woodash-fade-in">
            <!-- Header -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 woodash-fade-in">
                <div>
                    <h1 class="text-2xl font-bold woodash-gradient-text">Stock</h1>
                    <p class="text-gray-500">Manage your product inventory and monitor stock levels.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="woodash-btn woodash-btn-primary woodash-hover-scale" id="add-stock-btn">
                        <i class="fa-solid fa-plus"></i>
                        Add Stock Item
                    </button>
                </div>
            </header>

            <!-- Stock Stats Cards (Placeholder) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Stock Items -->
                <div class="woodash-metric-card woodash-animate-in woodash-hover-card" style="animation-delay: 0.1s">
                    <div class="flex items-center gap-4">
                        <div class="woodash-metric-icon woodash-metric-yellow woodash-glow">
                            <i class="fa-solid fa-boxes-stacked text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="woodash-metric-title">Total Stock Items</p>
                            <h3 class="woodash-metric-value">[Count]</h3>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Items -->
                <div class="woodash-metric-card woodash-animate-in woodash-hover-card" style="animation-delay: 0.2s">
                    <div class="flex items-center gap-4">
                         <div class="woodash-metric-icon woodash-metric-red woodash-glow">
                            <i class="fa-solid fa-triangle-exclamation text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="woodash-metric-title">Low Stock Items</p>
                            <h3 class="woodash-metric-value">[Count]</h3>
                        </div>
                    </div>
                </div>

                <!-- Out of Stock Items -->
                <div class="woodash-metric-card woodash-animate-in woodash-hover-card" style="animation-delay: 0.3s">
                    <div class="flex items-center gap-4">
                        <div class="woodash-metric-icon woodash-metric-red woodash-glow">
                            <i class="fa-solid fa-circle-xmark text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="woodash-metric-title">Out of Stock Items</p>
                            <h3 class="woodash-metric-value">[Count]</h3>
                        </div>
                    </div>
                </div>

                <!-- Stock Value (Placeholder) -->
                <div class="woodash-metric-card woodash-animate-in woodash-hover-card" style="animation-delay: 0.4s">
                    <div class="flex items-center gap-4">
                        <div class="woodash-metric-icon woodash-metric-green woodash-glow">
                            <i class="fa-solid fa-sack-dollar text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="woodash-metric-title">Total Stock Value</p>
                            <h3 class="woodash-metric-value">[Value]</h3>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Low Stock Chart (Placeholder) -->
                <div class="woodash-chart-container mb-8 woodash-animate-in woodash-hover-card" style="animation-delay: 0.6s">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-bold woodash-gradient-text">Low Stock Overview</h3>
                        <p class="text-sm text-gray-500">Visualize products nearing low stock thresholds.</p>
                    </div>
                </div>
                <div class="h-[300px]">
                    <canvas id="low-stock-chart"></canvas>
                     <div class="text-gray-500 text-center py-8">Loading chart data...</div>
                </div>
            </div>

            <!-- Stock Table (Placeholder) -->
            <div class="woodash-card overflow-hidden mb-8 woodash-animate-in" style="animation-delay: 0.5s">
                 <div class="flex justify-between items-center p-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-bold woodash-gradient-text">Stock List</h3>
                        <p class="text-sm text-gray-500">Overview of all your products in stock.</p>
                    </div>
                    <div class="flex gap-2">
                         <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="export-stock-btn">
                            <i class="fa-solid fa-file-csv"></i>
                            <span>Export CSV</span>
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto woodash-scrollbar">
                    <table class="woodash-table w-full" id="stock-table">
                        <thead>
                            <tr>
                                <th class="w-12">
                                    <input type="checkbox" class="rounded border-gray-300 text-[#00CC61] focus:ring-[#00CC61]" id="select-all-stock">
                                </th>
                                <th class="cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="product">
                                    Product <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th class="cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="sku">
                                    SKU <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th class="cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="stock">
                                    Stock Quantity <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th class="cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="status">
                                    Stock Status <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th class="cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="price">
                                    Price <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="stock-table-body">
                            <!-- Stock items will be loaded here by JS -->
                             <tr>
                                <td colspan="7" class="text-center py-8">
                                    <p class="text-gray-500">Loading stock data...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination (Placeholder) -->
                <div class="flex items-center justify-between p-4 border-t border-gray-100">
                    <div class="text-sm text-gray-500">
                        Showing <span id="stock-pagination-start">0</span> to <span id="stock-pagination-end">0</span> of <span id="stock-pagination-total">0</span> items
                    </div>
                    <div class="flex items-center gap-2" id="stock-pagination">
                        <!-- Pagination buttons will be generated here -->
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

<!-- Add Stock Item Modal -->
<div id="add-stock-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-xl font-semibold text-gray-900">Add New Stock Item</h3>
            <button type="button" id="close-add-stock-modal" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="add-stock-form" class="space-y-6">
                <!-- Product Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div>
                        <label for="product-name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                        <input type="text" id="product-name" name="product-name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#00CC61] focus:border-transparent">
                    </div>

                    <!-- SKU -->
                    <div>
                        <label for="product-sku" class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                        <input type="text" id="product-sku" name="product-sku" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#00CC61] focus:border-transparent">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="product-category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select id="product-category" name="product-category" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#00CC61] focus:border-transparent">
                            <option value="">Select Category</option>
                            <option value="electronics">Electronics</option>
                            <option value="clothing">Clothing</option>
                            <option value="accessories">Accessories</option>
                            <option value="furniture">Furniture</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="product-price" class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" id="product-price" name="product-price" required min="0" step="0.01"
                                   class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#00CC61] focus:border-transparent">
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="product-quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                        <input type="number" id="product-quantity" name="product-quantity" required min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#00CC61] focus:border-transparent">
                    </div>

                    <!-- Low Stock Alert -->
                    <div>
                        <label for="low-stock-alert" class="block text-sm font-medium text-gray-700 mb-1">Low Stock Alert</label>
                        <input type="number" id="low-stock-alert" name="low-stock-alert" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#00CC61] focus:border-transparent"
                               placeholder="Minimum stock level">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="product-description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="product-description" name="product-description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#00CC61] focus:border-transparent"
                              placeholder="Enter product description..."></textarea>
                </div>

                <!-- Additional Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Supplier -->
                    <div>
                        <label for="product-supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <input type="text" id="product-supplier" name="product-supplier"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#00CC61] focus:border-transparent">
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="product-location" class="block text-sm font-medium text-gray-700 mb-1">Storage Location</label>
                        <input type="text" id="product-location" name="product-location"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#00CC61] focus:border-transparent"
                               placeholder="e.g., Warehouse A, Shelf B3">
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 p-6 border-t">
            <button type="button" id="cancel-add-stock-btn"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00CC61]">
                Cancel
            </button>
            <button type="submit" form="add-stock-form"
                    class="px-4 py-2 text-sm font-medium text-white bg-[#00CC61] border border-transparent rounded-md hover:bg-[#00b357] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00CC61]">
                Add Stock Item
            </button>
        </div>
    </div>
</div>

<!-- Admin User section (Moved to bottom left) -->
<div class="fixed bottom-0 left-0 p-4 bg-white/90 border-t border-r border-gray-100 rounded-tr-lg shadow-lg woodash-glass-effect">
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
    // Basic structure for Stock page JavaScript
    const StockManager = {
        state: {
            // Define state variables for stock management (e.g., currentPage, itemsPerPage, filters, etc.)
        },

        init() {
            this.initEventListeners();
            // Add other initialization functions here (e.g., initFilters, initSorting)
            this.loadStockData(); // Load fake data on init
        },

        initEventListeners() {
            // Event listeners for buttons, filters, etc.
            const addStockItemBtn = document.getElementById('add-stock-btn');
            const addStockItemModal = document.getElementById('add-stock-modal');
            const closeAddStockItemModal = document.getElementById('close-add-stock-modal');
            const cancelAddStockItemBtn = document.getElementById('cancel-add-stock-btn');
            const addStockItemForm = document.getElementById('add-stock-form');

             if (addStockItemBtn) {
                addStockItemBtn.addEventListener('click', () => {
                    addStockItemModal.classList.remove('hidden');
                });
            }

            if (closeAddStockItemModal) {
                closeAddStockItemModal.addEventListener('click', () => {
                    addStockItemModal.classList.add('hidden');
                });
            }

            if (cancelAddStockItemBtn) {
                cancelAddStockItemBtn.addEventListener('click', () => {
                    addStockItemModal.classList.add('hidden');
                });
            }

            if (addStockItemForm) {
                addStockItemForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    // Handle form submission (e.g., send data to server)
                    console.log('Stock item form submitted');
                    // Close modal and potentially refresh data
                    addStockItemModal.classList.add('hidden');
                    addStockItemForm.reset();
                });
            }

            // Add event listeners for sorting table headers (similar to customers.php)
            // Add event listeners for pagination buttons (similar to customers.php)
            // Add event listeners for filters (similar to customers.php)
        },

        loadStockData() {
            // Function to fetch and display stock data (using fake data for now)
            console.log('Loading fake stock data...');

            // --- Start Fake Data ---
            const fakeStockItems = [
                {
                    id: 1,
                    productName: 'Wireless Mouse',
                    sku: 'WM-101',
                    category: 'Electronics',
                    stockLevel: 150,
                    status: 'In Stock',
                    icon: 'fa-mouse'
                },
                {
                    id: 2,
                    productName: 'Mechanical Keyboard',
                    sku: 'MK-202',
                    category: 'Electronics',
                    stockLevel: 15,
                    status: 'Low Stock',
                    icon: 'fa-keyboard'
                },
                {
                    id: 3,
                    productName: 'Desk Chair',
                    sku: 'DC-303',
                    category: 'Furniture',
                    stockLevel: 0,
                    status: 'Out of Stock',
                     icon: 'fa-chair'
                },
                {
                    id: 4,
                    productName: 'Notebook (5-pack)',
                    sku: 'NB-404',
                    category: 'Stationery',
                    stockLevel: 200,
                    status: 'In Stock',
                    icon: 'fa-book'
                },
                 {
                    id: 5,
                    productName: 'Monitor Stand',
                    sku: 'MS-505',
                    category: 'Accessories',
                    stockLevel: 8,
                    status: 'Low Stock',
                    icon: 'fa-desktop'
                }
            ];
            // --- End Fake Data ---

            this.renderStockTable(fakeStockItems);
        },

        renderStockTable(items) {
            const tbody = document.getElementById('stock-table-body');
            if (!tbody) return;

            let html = '';
            if (items.length === 0) {
                html = `<tr><td colspan="6" class="text-center py-8 text-gray-500">No stock items found.</td></tr>`;
            } else {
                items.forEach((item, index) => {
                    const statusBadgeClass = item.status === 'In Stock' ? 'bg-green-100 text-green-800' :
                                           item.status === 'Low Stock' ? 'bg-yellow-100 text-yellow-800' :
                                           'bg-red-100 text-red-800';

                    html += `
                        <tr class="woodash-fade-in" style="animation-delay: ${index * 0.05}s">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center woodash-hover-scale">
                                        <i class="fa-solid ${item.icon} text-gray-500"></i>
                                    </div>
                                    <span class="font-medium">${item.productName}</span>
                                </div>
                            </td>
                            <td>${item.sku}</td>
                            <td>${item.category}</td>
                            <td>${item.stockLevel}</td>
                            <td>
                                <span class="woodash-badge ${statusBadgeClass}">${item.status}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <button class="woodash-btn woodash-btn-icon woodash-btn-secondary woodash-hover-scale" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="woodash-btn woodash-btn-icon woodash-btn-secondary woodash-hover-scale" title="View History">
                                        <i class="fa-solid fa-history"></i>
                                    </button>
                                    <button class="woodash-btn woodash-btn-icon woodash-btn-danger woodash-hover-scale" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }

            tbody.innerHTML = html;
        },

        initCharts() {
            // Function to initialize stock-related charts (e.g., low stock chart)
            const lowStockCtx = document.getElementById('low-stock-chart')?.getContext('2d');
            if (lowStockCtx) {
                new Chart(lowStockCtx, {
                    type: 'bar', // Example chart type
                    data: {
                        labels: ['Product A', 'Product B', 'Product C', 'Product D', 'Product E'],
                        datasets: [{
                            label: 'Stock Quantity',
                            data: [5, 10, 3, 8, 12], // Sample data
                            backgroundColor: ['#F59E0B', '#F59E0B', '#EF4444', '#F59E0B', '#F59E0B'], // Highlight low stock
                             borderColor: ['#D97706', '#D97706', '#DC2626', '#D97706', '#D97706'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            title: { display: true, text: 'Stock Quantity by Product (Sample)' }
                        },
                        scales: {
                            y: { beginAtZero: true },
                            x: { }
                        }
                    }
                });
            }
        }
        // Add other functions here (e.g., handleAddStock, handleDeleteStock, updateCharts, updateStats)
    };

    // Initialize the Stock manager
    StockManager.init();
    StockManager.initCharts(); // Initialize charts on load

     // Initialize scroll to top button (assuming it's the same as other pages)
    const scrollTopButton = document.getElementById('woodash-scroll-to-top');
    if (scrollTopButton) {
        window.addEventListener('scroll', () => {
            scrollTopButton.style.display = window.scrollY > 300 ? 'flex' : 'none';
        });

        scrollTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

      // Initialize mobile menu toggle (assuming it's the same as other pages)
    const menuToggle = document.getElementById('woodash-menu-toggle');
    const sidebar = document.querySelector('.woodash-sidebar');

    menuToggle?.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

     const handleClickOutside = (e) => {
        if (window.innerWidth <= 768 &&
            !sidebar.contains(e.target) &&
            !menuToggle.contains(e.target) &&
            sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }
    };

    document.addEventListener('click', handleClickOutside);

});
</script>

</body>
<footer>
    <p>&copy; <?php echo date('Y'); ?> WooDash Pro. All rights reserved.</p>
</footer>
</html>
