<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Check if user has required capabilities
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'woodash-pro'));
}

// Verify nonce for any form submissions
if (isset($_POST['woodashh_products_nonce'])) {
    if (!wp_verify_nonce($_POST['woodashh_products_nonce'], 'woodashh_products_action')) {
        wp_die(__('Security check failed. Please try again.', 'woodashh'));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Dashboard</title>
    <!-- Google Fonts & Font Awesome for icons -->
    <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Tailwind CSS -->
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
                display: block;
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
        }

        .woodash-metric-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.125rem;
        }

        .woodash-metric-green {
            background: linear-gradient(135deg, #00CC61, #00b357);
        }

        .woodash-metric-red {
            background: linear-gradient(135deg, #EF4444, #DC2626);
        }

        /* Enhanced Button Styles */
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

        /* Enhanced Table Styles */
        .woodash-table {
            width: 100%;
            border-collapse: collapse;
        }

        .woodash-table th,
        .woodash-table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #E5E7EB;
        }

        .woodash-table th {
            font-weight: 600;
            color: #374151;
            background: #F9FAFB;
        }

        .woodash-table tbody tr:last-child td {
            border-bottom: none;
        }

        .woodash-table tr {
            transition: background-color 0.2s ease;
        }

        .woodash-table tr:hover {
            background: #F9FAFB;
        }

        /* Enhanced Search Bar */
        .woodash-search-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .woodash-search-container:focus-within {
            transform: scale(1.02);
        }

        .woodash-search-icon {
            position: absolute;
            left: 0.75rem;
            color: #9CA3AF;
        }

        .woodash-search-input {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            background: white;
            color: #1F2937;
        }

        .woodash-search-button {
            padding: 0.5rem 1rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0 0.5rem 0.5rem 0;
            margin-left: -1px;
            cursor: pointer;
        }

        .woodash-search-button:hover {
            background: var(--primary-dark);
        }

        .woodash-clear-product-search {
            position: absolute;
            right: 0.75rem;
            background: none;
            border: none;
            color: #9CA3AF;
            cursor: pointer;
        }

        .woodash-clear-product-search:hover {
            color: #EF4444;
        }

        .woodash-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            margin-top: 0.25rem;
            max-height: 20rem;
            overflow-y: auto;
            z-index: 50;
            display: none;
        }

        .woodash-search-results.active {
            display: block;
        }

        .woodash-search-result-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #F3F4F6;
            cursor: pointer;
        }

        .woodash-search-result-item:hover {
            background: #F9FAFB;
        }

        /* Enhanced Loading States */
        .woodash-loading {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading-shimmer 1.5s infinite;
        }

        .woodash-loading::after {
            content: '';
            display: block;
            height: 1rem;
            background: inherit;
        }

        @keyframes loading-shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .woodash-loading-spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            width: 1rem;
            height: 1rem;
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Enhanced Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .woodash-animate-in {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .woodash-slide-up {
            animation: slideUp 0.4s ease-out;
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0,0,0);
            }
            40%, 43% {
                transform: translate3d(0, -5px, 0);
            }
            70% {
                transform: translate3d(0, -3px, 0);
            }
        }

        .woodash-bounce {
            animation: bounce 1s ease infinite;
        }

        /* Enhanced Background Effects */

        /* Enhanced Scrollbar */
        .woodash-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #E2E8F0 #F8FAFC;
        }

        .woodash-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .woodash-scrollbar::-webkit-scrollbar-track {
            background: #F8FAFC;
        }

        .woodash-scrollbar::-webkit-scrollbar-thumb {
            background-color: #E2E8F0;
            border-radius: 3px;
        }

        /* Enhanced Notifications */
        .woodash-notification {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .woodash-notification-success {
            border-left: 4px solid var(--primary-color);
        }

        .woodash-notification-danger {
            border-left: 4px solid #EF4444;
        }

        .woodash-notification-warning {
             border-left: 4px solid #F59E0B;
        }

        /* Enhanced Dropdowns */
        .woodash-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            padding: 0.25rem 0;
            min-width: 10rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            z-index: 50;
            animation: fadeIn 0.2s ease-out;
        }

        .woodash-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            color: #374151;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .woodash-dropdown-item:hover {
            background: #F9FAFB;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Enhanced Progress Bars */
        .woodash-progress {
            width: 100%;
            height: 0.5rem;
            background: #E5E7EB;
            border-radius: 0.25rem;
            overflow: hidden;
        }

        .woodash-progress-bar {
            height: 100%;
            background: var(--primary-color);
            border-radius: 0.25rem;
            transition: width 0.3s ease;
        }

        .woodash-progress-bar-red {
            background: #EF4444;
        }

        /* Enhanced Badges */
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

        .woodash-badge-danger {
            background: #FEE2E2;
            color: #DC2626;
        }

        .woodash-badge-warning {
            background: #FEF3C7;
            color: #D97706;
        }

        .woodash-badge-blue {
            background: #DBEAFE;
            color: #2563EB;
        }

        /* Enhanced Charts */
        .woodash-chart-container {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 1rem;
            padding: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .woodash-chart-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Enhanced Footer */
        footer {
            background: white;
            border-top: 1px solid #E5E7EB;
            padding: 2rem 0;
            margin-top: 4rem;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 3rem;
            height: 3rem;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            margin-top: 1rem;
            color: #6B7280;
        }

        .chart-card {
            position: relative;
            overflow: hidden;
        }

        .chart-loading {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10;
        }

        .chart-loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            width: 2rem;
            height: 2rem;
            animation: spin 1s linear infinite;
        }

        .chart-loading-text {
            margin-top: 0.5rem;
            color: #6B7280;
            font-size: 0.875rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .page-transition {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--background-color);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .page-transition.active {
            opacity: 1;
            visibility: visible;
        }

        .page-transition-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 3rem;
            height: 3rem;
            animation: spin 1s linear infinite;
        }

        .skeleton-text {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading-shimmer 1.5s infinite;
            height: 1rem;
            border-radius: 0.25rem;
        }

        .skeleton-chart {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading-shimmer 1.5s infinite;
            height: 200px;
            border-radius: 0.5rem;
        }

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
                <i class="fa-solid fa-chart-line text-white text-xl"></i>
            </div>
            <h2 class="text-xl font-bold woodash-gradient-text">WooDash Pro</h2>
        </div>

        <nav class="space-y-1">
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-gauge w-5"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-orders')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-shopping-cart w-5"></i>
                <span>Orders</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-products')); ?>" class="woodash-nav-link active">
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
                    <h1 class="text-2xl font-bold woodash-gradient-text">Products</h1>
                    <p class="text-gray-500">Manage your product inventory and track performance.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="woodash-btn woodash-btn-primary" id="add-product-btn">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add Product</span>
                    </button>
                </div>
            </header>

            <!-- Product Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="woodash-metric-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">Total Products</h3>
                            <div class="woodash-metric-value font-bold text-gray-900">1,247</div>
                        </div>
                        <div class="woodash-metric-icon woodash-metric-green">
                            <i class="fa-solid fa-box"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-metric-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">In Stock</h3>
                            <div class="woodash-metric-value font-bold text-gray-900">1,089</div>
                        </div>
                        <div class="woodash-metric-icon bg-blue-100 text-blue-600">
                            <i class="fa-solid fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-metric-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">Low Stock</h3>
                            <div class="woodash-metric-value font-bold text-gray-900">23</div>
                        </div>
                        <div class="woodash-metric-icon bg-yellow-100 text-yellow-600">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-metric-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">Out of Stock</h3>
                            <div class="woodash-metric-value font-bold text-gray-900">135</div>
                        </div>
                        <div class="woodash-metric-icon woodash-metric-red">
                            <i class="fa-solid fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Table -->
            <div class="woodash-card p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <h2 class="text-lg font-bold woodash-gradient-text">Product List</h2>
                    <div class="flex items-center gap-3">
                        <div class="woodash-search-container">
                            <input type="text" placeholder="Search products..." class="woodash-search-input">
                            <i class="fa-solid fa-search woodash-search-icon"></i>
                        </div>
                        <select class="woodash-btn woodash-btn-secondary">
                            <option>All Products</option>
                            <option>In Stock</option>
                            <option>Low Stock</option>
                            <option>Out of Stock</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="woodash-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="products-table-body">
                            <!-- Product data will be loaded here -->
                        </tbody>
                    </table>
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

<!-- Add Product Modal -->
<div id="add-product-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden woodash-fade-in">
    <div class="woodash-card w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 woodash-glass-effect relative">
        <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-4">
            <h3 class="text-lg font-bold woodash-gradient-text">Add New Product</h3>
            <button id="close-product-modal" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <form id="add-product-form" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                <input type="text" name="product_name" class="woodash-search-input w-full" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                    <input type="text" name="sku" class="woodash-search-input w-full" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" class="woodash-search-input w-full" required>
                        <option value="">Select Category</option>
                        <option value="electronics">Electronics</option>
                        <option value="clothing">Clothing</option>
                        <option value="books">Books</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price ($)</label>
                    <input type="number" name="price" step="0.01" class="woodash-search-input w-full" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                    <input type="number" name="stock" class="woodash-search-input w-full" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4" class="woodash-search-input w-full"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancel-product-btn" class="woodash-btn woodash-btn-secondary">Cancel</button>
                <button type="submit" class="woodash-btn woodash-btn-primary">Add Product</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date('Y'); ?> WooDash Pro. All rights reserved.</p>
</footer>


<button id="woodash-scroll-to-top" class="fixed bottom-6 right-6 woodash-btn woodash-btn-primary rounded-full w-12 h-12 flex items-center justify-center woodash-glow" style="display: none;">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<div class="page-transition">
    <div class="page-transition-spinner"></div>
</div>

<script>
// Make AJAX URL and nonce available for backend integration
window.woodash_ajax = {
    ajax_url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
    nonce: '<?php echo wp_create_nonce('woodash_nonce'); ?>'
};

// Enhanced Products Dashboard with Backend Integration
const ProductsManager = {
    allProducts: [],
    charts: {},
    
    init() {
        this.loadProducts();
        this.loadProductStats();
        this.initializeEventListeners();
        this.initializeCharts();
    },
    
    loadProducts() {
        const formData = new FormData();
        formData.append('action', 'woodash_get_products');
        formData.append('nonce', woodash_ajax.nonce);
        
        fetch(woodash_ajax.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.allProducts = data.data;
                this.renderProductsTable(this.allProducts);
                this.updateProductCounts();
            } else {
                console.error('Error loading products:', data.data);
                this.loadFallbackProducts();
            }
        })
        .catch(error => {
            console.error('Network error loading products:', error);
            this.loadFallbackProducts();
        });
    },
    
    loadProductStats() {
        const formData = new FormData();
        formData.append('action', 'woodash_get_product_stats');
        formData.append('nonce', woodash_ajax.nonce);
        
        fetch(woodash_ajax.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateStatsCards(data.data);
                this.updateStockAlerts(data.data.stock_alerts);
                this.updateTopProductsChart(data.data.revenue_by_product);
            } else {
                console.error('Error loading product stats:', data.data);
            }
        })
        .catch(error => {
            console.error('Network error loading stats:', error);
        });
    },
    
    renderProductsTable(products) {
        const tbody = document.getElementById('products-table-body');
        if (!tbody) return;
        
        if (products.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-500">No products found</td></tr>';
            return;
        }
        
        tbody.innerHTML = products.map(product => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        ${product.image ? 
                            `<img src="${product.image}" alt="${product.name}" class="w-10 h-10 rounded object-cover mr-3">` :
                            `<div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center mr-3">
                                <i class="fas fa-box text-gray-400"></i>
                            </div>`
                        }
                        <div>
                            <div class="font-medium text-gray-900">${product.name}</div>
                            <div class="text-sm text-gray-500">SKU: ${product.sku || 'N/A'}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs font-medium rounded-full ${this.getStatusBadgeClass(product.status)}">
                        ${product.status.charAt(0).toUpperCase() + product.status.slice(1)}
                    </span>
                </td>
                <td class="px-6 py-4 font-medium">${product.formatted_price}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs font-medium rounded-full ${this.getStockBadgeClass(product.stock_status)}">
                        ${product.stock_status === 'instock' ? 'In Stock' : 'Out of Stock'}
                    </span>
                    ${product.stock_quantity !== null ? `<br><span class="text-xs text-gray-500">${product.stock_quantity} units</span>` : ''}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">${product.categories ? product.categories.join(', ') : 'N/A'}</td>
                <td class="px-6 py-4 text-sm text-gray-500">${new Date(product.date_created).toLocaleDateString()}</td>
                <td class="px-6 py-4">
                    <div class="flex space-x-2">
                        <button onclick="ProductsManager.editProduct(${product.id})" class="text-blue-600 hover:text-blue-800" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="ProductsManager.deleteProduct(${product.id})" class="text-red-600 hover:text-red-800" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    },
    
    updateStatsCards(stats) {
        // Update stats cards
        const statsElements = {
            'total-products': stats.total_products || 0,
            'out-of-stock': stats.out_of_stock || 0,
            'low-stock': stats.low_stock || 0,
            'categories': stats.categories || 0
        };
        
        Object.keys(statsElements).forEach(key => {
            const element = document.querySelector(`[data-stat="${key}"] .text-3xl`);
            if (element) {
                element.textContent = statsElements[key].toLocaleString();
            }
        });
    },
    
    updateStockAlerts(alerts) {
        const container = document.getElementById('stock-alerts');
        if (!container || !alerts) return;
        
        if (alerts.length === 0) {
            container.innerHTML = '<div class="text-center py-4 text-gray-500">No stock alerts</div>';
            return;
        }
        
        container.innerHTML = alerts.slice(0, 5).map(alert => `
            <div class="flex items-center justify-between p-3 border-l-4 ${alert.severity === 'danger' ? 'border-red-500 bg-red-50' : 'border-yellow-500 bg-yellow-50'} rounded">
                <div>
                    <div class="font-medium text-sm">${alert.product_name}</div>
                    <div class="text-xs text-gray-600">
                        ${alert.type === 'out_of_stock' ? 'Out of Stock' : `Low Stock: ${alert.stock_quantity} remaining`}
                    </div>
                </div>
                <div class="text-${alert.severity === 'danger' ? 'red' : 'yellow'}-600">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        `).join('');
    },
    
    updateTopProductsChart(revenueData) {
        if (!revenueData || revenueData.length === 0) return;
        
        const ctx = document.getElementById('topProductsChart');
        if (!ctx) return;
        
        if (this.charts.topProducts) {
            this.charts.topProducts.destroy();
        }
        
        this.charts.topProducts = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: revenueData.map(p => p.product_name.length > 20 ? p.product_name.substring(0, 20) + '...' : p.product_name),
                datasets: [{
                    label: 'Revenue',
                    data: revenueData.map(p => p.total_revenue),
                    backgroundColor: 'rgba(0, 204, 97, 0.7)',
                    borderColor: '#00CC61',
                    borderWidth: 1
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
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    },
    
    getStatusBadgeClass(status) {
        switch (status) {
            case 'publish':
                return 'bg-green-100 text-green-800';
            case 'draft':
                return 'bg-gray-100 text-gray-800';
            case 'private':
                return 'bg-blue-100 text-blue-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    },
    
    getStockBadgeClass(stockStatus) {
        return stockStatus === 'instock' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    },
    
    editProduct(productId) {
        // Implementation for editing product
        console.log('Edit product:', productId);
        this.showNotification('Edit product functionality can be implemented here', 'info');
    },
    
    deleteProduct(productId) {
        if (!confirm('Are you sure you want to delete this product?')) {
            return;
        }
        
        const formData = new FormData();
        formData.append('action', 'woodash_delete_product');
        formData.append('nonce', woodash_ajax.nonce);
        formData.append('product_id', productId);
        
        fetch(woodash_ajax.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification('Product deleted successfully', 'success');
                this.loadProducts(); // Reload products
                this.loadProductStats(); // Reload stats
            } else {
                this.showNotification('Error deleting product: ' + (data.data || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showNotification('Network error deleting product', 'error');
        });
    },
    
    initializeEventListeners() {
        // Search functionality
        const searchInput = document.getElementById('search-products');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.filterProducts(e.target.value);
            });
        }
        
        // Status filter
        const statusFilter = document.getElementById('filter-status');
        if (statusFilter) {
            statusFilter.addEventListener('change', (e) => {
                this.filterByStatus(e.target.value);
            });
        }
        
        // Stock filter
        const stockFilter = document.getElementById('filter-stock');
        if (stockFilter) {
            stockFilter.addEventListener('change', (e) => {
                this.filterByStock(e.target.value);
            });
        }
    },
    
    filterProducts(searchTerm) {
        if (!this.allProducts) return;
        
        const filtered = this.allProducts.filter(product => 
            product.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (product.sku && product.sku.toLowerCase().includes(searchTerm.toLowerCase()))
        );
        
        this.renderProductsTable(filtered);
    },
    
    filterByStatus(status) {
        if (!this.allProducts) return;
        
        const filtered = status ? 
            this.allProducts.filter(product => product.status === status) : 
            this.allProducts;
        
        this.renderProductsTable(filtered);
    },
    
    filterByStock(stockStatus) {
        if (!this.allProducts) return;
        
        const filtered = stockStatus ? 
            this.allProducts.filter(product => product.stock_status === stockStatus) : 
            this.allProducts;
        
        this.renderProductsTable(filtered);
    },
    
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    },
    
    loadFallbackProducts() {
        // Fallback demo data
        this.allProducts = [
            {
                id: 1,
                name: 'Sample Product 1',
                sku: 'SP001',
                status: 'publish',
                price: 29.99,
                formatted_price: '$29.99',
                stock_status: 'instock',
                stock_quantity: 50,
                categories: ['Electronics'],
                date_created: new Date().toISOString()
            }
        ];
        this.renderProductsTable(this.allProducts);
    },
    
    initializeCharts() {
        // Initialize any additional charts if needed
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    ProductsManager.init();
});
    // Helper function for debouncing
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Optimize chart rendering (using the helper function)
    function createOptimizedChart(ctx, config) {
        const chart = new Chart(ctx, {
            ...config,
            options: {
                ...config.options,
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                },
                responsiveAnimationDuration: 0,
                maintainAspectRatio: false
            }
        });

        // Optimize chart updates
        let updateTimeout;
        chart.update = function(animation = true) {
            if (updateTimeout) {
                clearTimeout(updateTimeout);
            }
            updateTimeout = setTimeout(() => {
                Chart.prototype.update.call(this, animation);
            }, 16);
        };

        return chart;
    }

    // Optimize notification system
    const notificationQueue = [];
    let isProcessingNotifications = false;

    function showNotification(message, type = 'success') {
        notificationQueue.push({ message, type });
        if (!isProcessingNotifications) {
            processNotificationQueue();
        }
    }

    function processNotificationQueue() {
        if (notificationQueue.length === 0) {
            isProcessingNotifications = false;
            return;
        }

        isProcessingNotifications = true;
        const { message, type } = notificationQueue.shift();

        const notification = document.createElement('div');
        notification.className = `woodash-notification woodash-notification-${type}`;
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'exclamation'}-circle"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
            processNotificationQueue();
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize product management functionality
        const ProductManager = {
            state: {
                searchTimeout: null,
                currentPage: 1,
                itemsPerPage: 10
            },

            init() {
                this.loadProducts();
                this.initEventListeners();
            },

            initEventListeners() {
                // Add product modal
                const addProductBtn = document.getElementById('add-product-btn');
                const addProductModal = document.getElementById('add-product-modal');
                const closeProductModal = document.getElementById('close-product-modal');
                const cancelProductBtn = document.getElementById('cancel-product-btn');
                
                addProductBtn?.addEventListener('click', () => {
                    addProductModal.classList.remove('hidden');
                });
                
                closeProductModal?.addEventListener('click', () => {
                    addProductModal.classList.add('hidden');
                });
                
                cancelProductBtn?.addEventListener('click', () => {
                    addProductModal.classList.add('hidden');
                });

                // Form submission
                const addProductForm = document.getElementById('add-product-form');
                addProductForm?.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handleAddProduct(addProductForm);
                });

                // Search functionality
                const searchInput = document.querySelector('.woodash-search-input');
                searchInput?.addEventListener('input', debounce((e) => {
                    this.searchProducts(e.target.value);
                }, 300));

                // Filter functionality
                const filterSelect = document.querySelector('select');
                filterSelect?.addEventListener('change', (e) => {
                    this.filterProducts(e.target.value);
                });
            },

            loadProducts() {
                // Sample product data - in real implementation, this would come from WooCommerce
                const products = [
                    {
                        id: 1,
                        name: 'Wireless Headphones',
                        sku: 'WH-001',
                        category: 'Electronics',
                        stock: 45,
                        price: '$129.99',
                        status: 'In Stock',
                        image: 'https://via.placeholder.com/40x40'
                    },
                    {
                        id: 2,
                        name: 'Cotton T-Shirt',
                        sku: 'CT-002',
                        category: 'Clothing',
                        stock: 8,
                        price: '$24.99',
                        status: 'Low Stock',
                        image: 'https://via.placeholder.com/40x40'
                    },
                    {
                        id: 3,
                        name: 'Programming Book',
                        sku: 'PB-003',
                        category: 'Books',
                        stock: 0,
                        price: '$49.99',
                        status: 'Out of Stock',
                        image: 'https://via.placeholder.com/40x40'
                    }
                ];

                this.renderProductTable(products);
            },

            renderProductTable(products) {
                const tbody = document.getElementById('products-table-body');
                if (!tbody) return;

                const html = products.map(product => `
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="${product.image}" alt="${product.name}" class="w-10 h-10 rounded-lg object-cover">
                                <span class="font-medium">${product.name}</span>
                            </div>
                        </td>
                        <td class="font-mono text-sm">${product.sku}</td>
                        <td>${product.category}</td>
                        <td>
                            <span class="font-medium ${product.stock === 0 ? 'text-red-600' : product.stock < 10 ? 'text-yellow-600' : 'text-green-600'}">
                                ${product.stock}
                            </span>
                        </td>
                        <td class="font-semibold">${product.price}</td>
                        <td>
                            <span class="woodash-badge ${this.getStatusBadgeClass(product.status)}">
                                ${product.status}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="View Product">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Edit Product">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete Product">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('');

                tbody.innerHTML = html;
            },

            getStatusBadgeClass(status) {
                switch (status) {
                    case 'In Stock':
                        return 'woodash-badge-success';
                    case 'Low Stock':
                        return 'woodash-badge-warning';
                    case 'Out of Stock':
                        return 'woodash-badge-danger';
                    default:
                        return 'bg-gray-100 text-gray-700';
                }
            },

            handleAddProduct(form) {
                const formData = new FormData(form);
                const productData = Object.fromEntries(formData);
                
                // Here you would send the data to your backend
                console.log('Adding product:', productData);
                
                showNotification('Product added successfully!', 'success');
                
                // Close modal and refresh product list
                document.getElementById('add-product-modal').classList.add('hidden');
                form.reset();
                this.loadProducts();
            },

            searchProducts(query) {
                // Implement product search
                console.log('Searching products:', query);
            },

            filterProducts(filter) {
                // Implement product filtering
                console.log('Filtering products:', filter);
            }
        };

        // Initialize the product manager
        ProductManager.init();

        // Initialize mobile menu toggle
        const menuToggle = document.getElementById('woodash-menu-toggle');
        const sidebar = document.querySelector('.woodash-sidebar');

        menuToggle?.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Handle click outside to close mobile menu
        const handleClickOutside = (e) => {
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !menuToggle.contains(e.target) && 
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        };

        document.addEventListener('click', handleClickOutside);

        // Initialize scroll to top button
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
    });

    // Show loading state
    function showLoading() {
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'loading-overlay';
        loadingOverlay.innerHTML = `
            <div class="text-center">
                <div class="loading-spinner"></div>
                <div class="loading-text">Loading...</div>
            </div>
        `;
        document.body.appendChild(loadingOverlay);
    }

    // Hide loading state
    function hideLoading() {
        const loadingOverlay = document.querySelector('.loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.remove();
        }
    }

    // Show page transition
    function showPageTransition() {
        const pageTransition = document.querySelector('.page-transition');
        if (pageTransition) {
            pageTransition.classList.add('active');
        }
    }

    // Hide page transition
    function hidePageTransition() {
        const pageTransition = document.querySelector('.page-transition');
        if (pageTransition) {
            pageTransition.classList.remove('active');
        }
    }
</script>

</body>
</html>
