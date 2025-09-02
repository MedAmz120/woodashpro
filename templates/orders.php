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
    <title>Orders Dashboard</title>
    
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

        /* Table Styles */
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

        .woodash-badge-purple {
            background: #F3E8FF;
            color: #7C3AED;
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
    <aside class="woodash-sidebar w-64 bg-white/90 border-r border-gray-100 woodash-glass-effect flex flex-col justify-between">
    <div class="pt-10 pb-4 px-6">
            <div class="flex items-center gap-4 mb-10 woodash-fade-in">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center woodash-glow">
                    <i class="fa-solid fa-chart-line text-white text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold woodash-gradient-text ml-1">WooDash Pro</h2>
            </div>
            <!-- Main Navigation -->
            <nav class="flex flex-col gap-1 mt-2">
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
                    <i class="fa-solid fa-gauge w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-orders')); ?>" class="woodash-nav-link active flex items-center gap-4 px-4 py-2 rounded-lg bg-[#e0f7ef] text-[#00b357] font-semibold">
                    <i class="fa-solid fa-shopping-cart w-5"></i>
                    <span>Orders</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-products')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
                    <i class="fa-solid fa-box w-5"></i>
                    <span>Products</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-customers')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
                    <i class="fa-solid fa-users w-5"></i>
                    <span>Customers</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-stock')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
                    <i class="fa-solid fa-boxes-stacked w-5"></i>
                    <span>Stock</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-reviews')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
                    <i class="fa-solid fa-star w-5"></i>
                    <span>Reviews</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-marketing')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
                    <i class="fa-solid fa-bullhorn w-5"></i>
                    <span>Marketing</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-reports')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
                    <i class="fa-solid fa-file-chart-line w-5"></i>
                    <span>Reports</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-settings')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
                    <i class="fa-solid fa-gear w-5"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="woodash-main flex-1 p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold woodash-gradient-text">Orders</h1>
                    <p class="text-gray-500">Manage and track customer orders efficiently.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="woodash-btn woodash-btn-secondary" id="export-orders-btn">
                        <i class="fa-solid fa-download"></i>
                        <span>Export</span>
                    </button>
                    <button class="woodash-btn woodash-btn-primary" id="add-order-btn">
                        <i class="fa-solid fa-plus"></i>
                        <span>New Order</span>
                    </button>
                </div>
            </header>

            <!-- Order Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Total Orders</h3>
                            <div class="text-3xl font-bold text-gray-900">1,234</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Pending</h3>
                            <div class="text-3xl font-bold text-orange-600">23</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Processing</h3>
                            <div class="text-3xl font-bold text-blue-600">45</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-cog"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Completed</h3>
                            <div class="text-3xl font-bold text-green-600">1,156</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Cancelled</h3>
                            <div class="text-3xl font-bold text-red-600">10</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                            <i class="fa-solid fa-times"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="woodash-card p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <h2 class="text-lg font-bold woodash-gradient-text">Recent Orders</h2>
                    <div class="flex items-center gap-3">
                        <input type="text" placeholder="Search orders..." class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" id="search-orders">
                        <select class="woodash-btn woodash-btn-secondary" id="filter-status">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="woodash-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="orders-table-body">
                            <!-- Orders data will be loaded here -->
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

<!-- Order Details Modal -->
<div id="order-details-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-lg font-bold woodash-gradient-text" id="modal-order-title">Order Details</h3>
            <button id="close-order-modal" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6" id="order-details-content">
            <!-- Order details will be loaded here -->
        </div>
    </div>
</div>

<!-- New Order Modal -->
<div id="new-order-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-lg font-bold woodash-gradient-text">Create New Order</h3>
            <button id="close-new-order-modal" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="new-order-form" class="space-y-6">
                <!-- Customer Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-bold mb-4 text-gray-800">Customer Information</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type</label>
                                <select id="customer-type" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="existing">Existing Customer</option>
                                    <option value="new">New Customer</option>
                                </select>
                            </div>
                            
                            <div id="existing-customer" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Customer</label>
                                    <select id="customer-select" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="">Choose a customer...</option>
                                        <option value="1">John Smith (john@example.com)</option>
                                        <option value="2">Sarah Johnson (sarah@example.com)</option>
                                        <option value="3">Mike Brown (mike@example.com)</option>
                                    </select>
                                </div>
                            </div>

                            <div id="new-customer" class="space-y-4 hidden">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                        <input type="text" id="first-name" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter first name">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                        <input type="text" id="last-name" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter last name">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" id="customer-email" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter email address">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                    <input type="tel" id="customer-phone" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter phone number">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <div>
                        <h4 class="font-bold mb-4 text-gray-800">Billing Address</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 1</label>
                                <input type="text" id="billing-address-1" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Street address">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 2</label>
                                <input type="text" id="billing-address-2" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Apartment, suite, etc. (optional)">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                    <input type="text" id="billing-city" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="City">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                                    <input type="text" id="billing-state" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="State">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
                                    <input type="text" id="billing-zip" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Zip code">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                    <select id="billing-country" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="US">United States</option>
                                        <option value="CA">Canada</option>
                                        <option value="UK">United Kingdom</option>
                                        <option value="AU">Australia</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-bold text-gray-800">Order Items</h4>
                        <button type="button" id="add-product-btn" class="woodash-btn woodash-btn-primary">
                            <i class="fa-solid fa-plus"></i>
                            Add Product
                        </button>
                    </div>
                    <div id="order-items" class="space-y-4">
                        <!-- Order items will be added here -->
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-bold mb-4 text-gray-800">Order Settings</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                                <select id="order-status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                                <select id="payment-method" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="credit-card">Credit Card</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="bank-transfer">Bank Transfer</option>
                                    <option value="cash-on-delivery">Cash on Delivery</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                                <select id="payment-status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="failed">Failed</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Order Total -->
                    <div>
                        <h4 class="font-bold mb-4 text-gray-800">Order Summary</h4>
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span id="order-subtotal">$0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tax:</span>
                                <span id="order-tax">$0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping:</span>
                                <span id="order-shipping">$0.00</span>
                            </div>
                            <hr>
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total:</span>
                                <span id="order-total">$0.00</span>
                            </div>
                        </div>
                        
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%)</label>
                                <input type="number" id="tax-rate" value="8.25" step="0.01" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Cost</label>
                                <input type="number" id="shipping-cost" value="0" step="0.01" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div>
                    <h4 class="font-bold mb-4 text-gray-800">Order Notes</h4>
                    <textarea id="order-notes" rows="4" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Add any special instructions or notes for this order..."></textarea>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-4 pt-6 border-t">
                    <button type="button" id="cancel-new-order" class="woodash-btn woodash-btn-secondary">
                        Cancel
                    </button>
                    <button type="submit" class="woodash-btn woodash-btn-primary">
                        <i class="fa-solid fa-save"></i>
                        Create Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Make AJAX URL and nonce available
window.woodash_ajax = {
    ajax_url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
    nonce: '<?php echo wp_create_nonce('woodash_nonce'); ?>'
};

document.addEventListener('DOMContentLoaded', function() {
    const OrdersManager = {
        init() {
            this.loadOrders();
            this.initEventListeners();
        },

        initEventListeners() {
            // Search functionality
            const searchInput = document.getElementById('search-orders');
            searchInput?.addEventListener('input', (e) => {
                this.filterOrders(e.target.value);
            });

            // Status filter
            const statusFilter = document.getElementById('filter-status');
            statusFilter?.addEventListener('change', (e) => {
                this.filterByStatus(e.target.value);
            });

            // Order details modal
            const orderDetailsModal = document.getElementById('order-details-modal');
            const closeOrderModal = document.getElementById('close-order-modal');
            
            closeOrderModal?.addEventListener('click', () => {
                orderDetailsModal.classList.add('hidden');
            });

            // New Order Modal
            const addOrderBtn = document.getElementById('add-order-btn');
            const newOrderModal = document.getElementById('new-order-modal');
            const closeNewOrderModal = document.getElementById('close-new-order-modal');
            const cancelNewOrder = document.getElementById('cancel-new-order');

            addOrderBtn?.addEventListener('click', () => {
                newOrderModal.classList.remove('hidden');
                this.initNewOrderForm();
            });

            closeNewOrderModal?.addEventListener('click', () => {
                newOrderModal.classList.add('hidden');
            });

            cancelNewOrder?.addEventListener('click', () => {
                newOrderModal.classList.add('hidden');
            });

            // Customer type toggle
            const customerType = document.getElementById('customer-type');
            customerType?.addEventListener('change', (e) => {
                this.toggleCustomerFields(e.target.value);
            });

            // Add product button
            const addProductBtn = document.getElementById('add-product-btn');
            addProductBtn?.addEventListener('click', () => {
                this.addProductRow();
            });

            // Form submission
            const newOrderForm = document.getElementById('new-order-form');
            newOrderForm?.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitNewOrder();
            });

            // Tax and shipping calculation
            const taxRate = document.getElementById('tax-rate');
            const shippingCost = document.getElementById('shipping-cost');
            
            taxRate?.addEventListener('input', () => this.calculateTotal());
            shippingCost?.addEventListener('input', () => this.calculateTotal());

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
        },

        initNewOrderForm() {
            // Reset form
            document.getElementById('new-order-form').reset();
            document.getElementById('order-items').innerHTML = '';
            this.toggleCustomerFields('existing');
            this.addProductRow();
            this.calculateTotal();
            
            // Load customers and products from API
            this.loadCustomersFromAPI();
            this.loadProductsFromAPI();
        },

        toggleCustomerFields(type) {
            const existingCustomer = document.getElementById('existing-customer');
            const newCustomer = document.getElementById('new-customer');

            if (type === 'new') {
                existingCustomer.classList.add('hidden');
                newCustomer.classList.remove('hidden');
            } else {
                existingCustomer.classList.remove('hidden');
                newCustomer.classList.add('hidden');
            }
        },

        addProductRow() {
            const orderItems = document.getElementById('order-items');
            const rowId = Date.now();
            
            const productRow = document.createElement('div');
            productRow.className = 'grid grid-cols-1 md:grid-cols-5 gap-4 p-4 border border-gray-200 rounded-lg';
            productRow.dataset.rowId = rowId;
            
            productRow.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                    <select class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 product-select">
                        <option value="">Choose a product...</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                    <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 product-price" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" min="1" value="1" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 product-quantity">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                    <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 product-total" placeholder="0.00" readonly>
                </div>
                <div class="flex items-end">
                    <button type="button" class="woodash-btn woodash-btn-secondary remove-product-btn" data-row="${rowId}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            `;

            orderItems.appendChild(productRow);

            // Add event listeners for this row
            const productSelect = productRow.querySelector('.product-select');
            const productPrice = productRow.querySelector('.product-price');
            const productQuantity = productRow.querySelector('.product-quantity');
            const removeBtn = productRow.querySelector('.remove-product-btn');

            productSelect.addEventListener('change', (e) => {
                const selectedOption = e.target.selectedOptions[0];
                const price = selectedOption?.dataset.price || 0;
                productPrice.value = price;
                this.calculateRowTotal(productRow);
            });

            productPrice.addEventListener('input', () => {
                this.calculateRowTotal(productRow);
            });

            productQuantity.addEventListener('input', () => {
                this.calculateRowTotal(productRow);
            });

            removeBtn.addEventListener('click', () => {
                productRow.remove();
                this.calculateTotal();
            });
            
            // Load products into this new row
            this.loadProductsForRow(productSelect);
        },

        loadProductsForRow(selectElement) {
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
                    selectElement.innerHTML = '<option value="">Choose a product...</option>';
                    data.data.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = `${product.name} - ${product.formatted_price}`;
                        option.dataset.price = product.price;
                        selectElement.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading products for row:', error);
            });
        },

        calculateRowTotal(row) {
            const price = parseFloat(row.querySelector('.product-price').value) || 0;
            const quantity = parseInt(row.querySelector('.product-quantity').value) || 0;
            const total = price * quantity;
            
            row.querySelector('.product-total').value = total.toFixed(2);
            this.calculateTotal();
        },

        calculateTotal() {
            const productRows = document.querySelectorAll('#order-items > div');
            let subtotal = 0;

            productRows.forEach(row => {
                const total = parseFloat(row.querySelector('.product-total').value) || 0;
                subtotal += total;
            });

            const taxRate = parseFloat(document.getElementById('tax-rate').value) || 0;
            const shippingCost = parseFloat(document.getElementById('shipping-cost').value) || 0;
            
            const tax = subtotal * (taxRate / 100);
            const total = subtotal + tax + shippingCost;

            document.getElementById('order-subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('order-tax').textContent = '$' + tax.toFixed(2);
            document.getElementById('order-shipping').textContent = '$' + shippingCost.toFixed(2);
            document.getElementById('order-total').textContent = '$' + total.toFixed(2);
        },

        submitNewOrder() {
            // Get form data
            const formData = this.getFormData();
            
            // Validate form
            if (!this.validateForm(formData)) {
                return;
            }

            // Show loading state
            const submitBtn = document.querySelector('#new-order-form button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Creating Order...';
            submitBtn.disabled = true;

            // Prepare API request
            const apiFormData = new FormData();
            apiFormData.append('action', 'woodash_create_order');
            apiFormData.append('nonce', woodash_ajax.nonce);
            apiFormData.append('customer_type', formData.customerType);
            
            if (formData.customerType === 'existing') {
                apiFormData.append('customer_id', formData.customerId);
            } else {
                apiFormData.append('first_name', formData.firstName);
                apiFormData.append('last_name', formData.lastName);
                apiFormData.append('customer_email', formData.customerEmail);
                apiFormData.append('customer_phone', formData.customerPhone);
            }
            
            // Billing address
            const billingData = {
                first_name: formData.firstName || formData.customerName.split(' ')[0],
                last_name: formData.lastName || formData.customerName.split(' ').slice(1).join(' '),
                address_1: document.getElementById('billing-address-1').value,
                address_2: document.getElementById('billing-address-2').value,
                city: document.getElementById('billing-city').value,
                state: document.getElementById('billing-state').value,
                postcode: document.getElementById('billing-zip').value,
                country: document.getElementById('billing-country').value,
                email: formData.customerEmail,
                phone: formData.customerPhone || ''
            };
            apiFormData.append('billing', JSON.stringify(billingData));
            
            // Order items
            apiFormData.append('items', JSON.stringify(formData.items.map(item => ({
                product_id: item.productId,
                quantity: item.qty,
                price: parseFloat(item.price.replace('$', ''))
            }))));
            
            // Order settings
            apiFormData.append('status', formData.status);
            apiFormData.append('payment_method', formData.paymentMethod);
            apiFormData.append('payment_status', formData.paymentStatus);
            apiFormData.append('notes', formData.notes);
            apiFormData.append('tax_rate', formData.taxRate);
            apiFormData.append('shipping_cost', formData.shippingCost);

            // Submit to backend
            fetch(woodash_ajax.ajax_url, {
                method: 'POST',
                body: apiFormData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add to orders list
                    this.allOrders.unshift(data.data.order);
                    this.renderOrdersTable(this.allOrders);
                    this.updateOrderStats();

                    // Close modal and show success message
                    document.getElementById('new-order-modal').classList.add('hidden');
                    this.showNotification('Order created successfully!', 'success');
                } else {
                    this.showNotification('Error creating order: ' + (data.data || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.showNotification('Network error creating order', 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        },

        getFormData() {
            const customerType = document.getElementById('customer-type').value;
            let customerName, customerEmail, customerId, firstName, lastName, customerPhone;

            if (customerType === 'existing') {
                const customerSelect = document.getElementById('customer-select');
                const selectedOption = customerSelect.selectedOptions[0];
                customerId = selectedOption?.value || '';
                customerName = selectedOption?.text.split(' (')[0] || '';
                customerEmail = selectedOption?.dataset.email || '';
                firstName = selectedOption?.dataset.firstName || '';
                lastName = selectedOption?.dataset.lastName || '';
                customerPhone = selectedOption?.dataset.phone || '';
            } else {
                firstName = document.getElementById('first-name').value;
                lastName = document.getElementById('last-name').value;
                customerName = `${firstName} ${lastName}`.trim();
                customerEmail = document.getElementById('customer-email').value;
                customerPhone = document.getElementById('customer-phone').value;
                customerId = 0;
            }

            // Get order items
            const items = [];
            const productRows = document.querySelectorAll('#order-items > div');
            
            productRows.forEach(row => {
                const productSelect = row.querySelector('.product-select');
                const selectedOption = productSelect.selectedOptions[0];
                const productId = selectedOption?.value || '';
                const productName = selectedOption?.text.split(' - $')[0] || '';
                const price = row.querySelector('.product-price').value;
                const quantity = row.querySelector('.product-quantity').value;
                
                if (productId && price && quantity) {
                    items.push({
                        productId: productId,
                        name: productName,
                        price: '$' + parseFloat(price).toFixed(2),
                        qty: parseInt(quantity)
                    });
                }
            });

            return {
                customerType,
                customerId,
                customerName,
                customerEmail,
                firstName,
                lastName,
                customerPhone,
                status: document.getElementById('order-status').value,
                paymentMethod: document.getElementById('payment-method').value,
                paymentStatus: document.getElementById('payment-status').value,
                total: document.getElementById('order-total').textContent,
                taxRate: parseFloat(document.getElementById('tax-rate').value) || 0,
                shippingCost: parseFloat(document.getElementById('shipping-cost').value) || 0,
                notes: document.getElementById('order-notes').value,
                items
            };
        },

        validateForm(formData) {
            if (!formData.customerName) {
                this.showNotification('Please select or enter customer information', 'error');
                return false;
            }

            if (!formData.customerEmail) {
                this.showNotification('Customer email is required', 'error');
                return false;
            }

            if (formData.items.length === 0) {
                this.showNotification('Please add at least one product to the order', 'error');
                return false;
            }

            return true;
        },

        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        },

        loadOrders() {
            // Load orders from backend API
            this.showLoading();
            
            const formData = new FormData();
            formData.append('action', 'woodash_get_orders');
            formData.append('nonce', woodash_ajax.nonce);
            formData.append('page', 1);
            formData.append('per_page', 50);
            
            fetch(woodash_ajax.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.allOrders = data.data.orders;
                    this.renderOrdersTable(this.allOrders);
                    this.updateOrderStats();
                } else {
                    this.showNotification('Error loading orders: ' + (data.data || 'Unknown error'), 'error');
                    // Fallback to sample data
                    this.loadSampleOrders();
                }
                this.hideLoading();
            })
            .catch(error => {
                console.error('Error:', error);
                this.showNotification('Network error loading orders', 'error');
                this.loadSampleOrders();
                this.hideLoading();
            });
        },

        loadSampleOrders() {
            // Fallback sample orders data
            const orders = [
                {
                    id: '#1234',
                    customer: 'John Smith',
                    email: 'john@example.com',
                    date: '2025-08-14',
                    status: 'completed',
                    total: '$125.50',
                    payment: 'Paid',
                    items: [
                        { name: 'Wireless Headphones', price: '$99.99', qty: 1 },
                        { name: 'Phone Case', price: '$25.51', qty: 1 }
                    ]
                },
                {
                    id: '#1235',
                    customer: 'Sarah Johnson',
                    email: 'sarah@example.com',
                    date: '2025-08-14',
                    status: 'processing',
                    total: '$78.99',
                    payment: 'Paid',
                    items: [
                        { name: 'Smart Watch', price: '$78.99', qty: 1 }
                    ]
                },
                {
                    id: '#1236',
                    customer: 'Mike Brown',
                    email: 'mike@example.com',
                    date: '2025-08-13',
                    status: 'pending',
                    total: '$199.99',
                    payment: 'Pending',
                    items: [
                        { name: 'Laptop Stand', price: '$199.99', qty: 1 }
                    ]
                }
            ];

            this.allOrders = orders;
            this.renderOrdersTable(orders);
        },

        loadCustomersFromAPI() {
            const formData = new FormData();
            formData.append('action', 'woodash_get_customers');
            formData.append('nonce', woodash_ajax.nonce);
            
            fetch(woodash_ajax.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.updateCustomerDropdown(data.data);
                } else {
                    console.error('Error loading customers:', data.data);
                }
            })
            .catch(error => {
                console.error('Network error loading customers:', error);
            });
        },

        loadProductsFromAPI() {
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
                    this.updateProductDropdowns(data.data);
                } else {
                    console.error('Error loading products:', data.data);
                }
            })
            .catch(error => {
                console.error('Network error loading products:', error);
            });
        },

        updateCustomerDropdown(customers) {
            const customerSelect = document.getElementById('customer-select');
            if (!customerSelect) return;
            
            // Clear existing options except the first one
            customerSelect.innerHTML = '<option value="">Choose a customer...</option>';
            
            customers.forEach(customer => {
                const option = document.createElement('option');
                option.value = customer.id;
                option.textContent = `${customer.name} (${customer.email})`;
                option.dataset.email = customer.email;
                option.dataset.firstName = customer.first_name;
                option.dataset.lastName = customer.last_name;
                option.dataset.phone = customer.phone;
                customerSelect.appendChild(option);
            });
        },

        updateProductDropdowns(products) {
            // Update all existing product dropdowns
            const productSelects = document.querySelectorAll('.product-select');
            
            productSelects.forEach(select => {
                const currentValue = select.value;
                select.innerHTML = '<option value="">Choose a product...</option>';
                
                products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = `${product.name} - ${product.formatted_price}`;
                    option.dataset.price = product.price;
                    option.dataset.stock = product.stock_status;
                    if (product.id == currentValue) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            });
        },

        showLoading() {
            const tbody = document.getElementById('orders-table-body');
            if (tbody) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8"><i class="fa-solid fa-spinner fa-spin"></i> Loading orders...</td></tr>';
            }
        },

        hideLoading() {
            // Loading will be hidden when table is rendered
        },

        updateOrderStats() {
            if (!this.allOrders) return;
            
            const stats = {
                total: this.allOrders.length,
                pending: this.allOrders.filter(o => o.status === 'pending').length,
                processing: this.allOrders.filter(o => o.status === 'processing').length,
                completed: this.allOrders.filter(o => o.status === 'completed').length,
                cancelled: this.allOrders.filter(o => o.status === 'cancelled').length,
            };
            
            // Update stats cards if they exist
            const statsElements = {
                'total': document.querySelector('[data-stat="total-orders"] .text-3xl'),
                'pending': document.querySelector('[data-stat="pending-orders"] .text-3xl'),
                'processing': document.querySelector('[data-stat="processing-orders"] .text-3xl'),
                'completed': document.querySelector('[data-stat="completed-orders"] .text-3xl'),
                'cancelled': document.querySelector('[data-stat="cancelled-orders"] .text-3xl'),
            };
            
            Object.keys(statsElements).forEach(key => {
                if (statsElements[key]) {
                    statsElements[key].textContent = stats[key] || 0;
                }
            });
        },

        renderOrdersTable(orders) {
            const tbody = document.getElementById('orders-table-body');
            if (!tbody) return;

            const html = orders.map(order => `
                <tr>
                    <td>
                        <span class="font-mono font-medium text-blue-600">${order.id}</span>
                    </td>
                    <td>
                        <div>
                            <div class="font-medium">${order.customer}</div>
                            <div class="text-sm text-gray-500">${order.email}</div>
                        </div>
                    </td>
                    <td>${order.date}</td>
                    <td>
                        <span class="woodash-badge ${this.getStatusBadgeClass(order.status)}">
                            ${this.capitalizeFirst(order.status)}
                        </span>
                    </td>
                    <td class="font-semibold">${order.total}</td>
                    <td>
                        <span class="woodash-badge ${order.payment === 'Paid' ? 'woodash-badge-success' : 'woodash-badge-warning'}">
                            ${order.payment}
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <button class="text-blue-600 hover:text-blue-800" title="View Order" onclick="OrdersManager.viewOrder('${order.id}')">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-800" title="Edit Order">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button class="text-purple-600 hover:text-purple-800" title="Print Invoice">
                                <i class="fa-solid fa-print"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');

            tbody.innerHTML = html;
        },

        getStatusBadgeClass(status) {
            switch (status) {
                case 'completed':
                    return 'woodash-badge-success';
                case 'processing':
                    return 'woodash-badge-blue';
                case 'pending':
                    return 'woodash-badge-warning';
                case 'cancelled':
                    return 'woodash-badge-danger';
                default:
                    return 'woodash-badge-blue';
            }
        },

        capitalizeFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        },

        filterOrders(searchTerm) {
            if (!this.allOrders) return;
            
            const filtered = this.allOrders.filter(order => 
                order.id.toLowerCase().includes(searchTerm.toLowerCase()) ||
                order.customer.toLowerCase().includes(searchTerm.toLowerCase()) ||
                order.email.toLowerCase().includes(searchTerm.toLowerCase())
            );
            
            this.renderOrdersTable(filtered);
        },

        filterByStatus(status) {
            if (!this.allOrders) return;
            
            const filtered = status ? 
                this.allOrders.filter(order => order.status === status) : 
                this.allOrders;
            
            this.renderOrdersTable(filtered);
        },

        viewOrder(orderId) {
            // Remove # from order ID for API call
            const cleanOrderId = orderId.replace('#', '');
            
            const formData = new FormData();
            formData.append('action', 'woodash_get_order_details');
            formData.append('nonce', woodash_ajax.nonce);
            formData.append('order_id', cleanOrderId);
            
            fetch(woodash_ajax.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showOrderDetails(data.data);
                } else {
                    // Fallback to local data
                    const order = this.allOrders.find(o => o.id === orderId);
                    if (order) {
                        this.showOrderDetails(order);
                    } else {
                        this.showNotification('Order not found', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error loading order details:', error);
                // Fallback to local data
                const order = this.allOrders.find(o => o.id === orderId);
                if (order) {
                    this.showOrderDetails(order);
                } else {
                    this.showNotification('Error loading order details', 'error');
                }
            });
        },
        
        showOrderDetails(order) {
            const modal = document.getElementById('order-details-modal');
            const title = document.getElementById('modal-order-title');
            const content = document.getElementById('order-details-content');

            title.textContent = `Order ${order.id} Details`;
            
            content.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-bold mb-3">Customer Information</h4>
                        <div class="space-y-2">
                            <p><strong>Name:</strong> ${order.customer}</p>
                            <p><strong>Email:</strong> ${order.email}</p>
                            <p><strong>Order Date:</strong> ${order.date}</p>
                        </div>
                        ${order.billing_address ? `
                            <h4 class="font-bold mb-3 mt-6">Billing Address</h4>
                            <div class="space-y-1 text-sm">
                                <p>${order.billing_address.first_name} ${order.billing_address.last_name}</p>
                                <p>${order.billing_address.address_1}</p>
                                ${order.billing_address.address_2 ? `<p>${order.billing_address.address_2}</p>` : ''}
                                <p>${order.billing_address.city}, ${order.billing_address.state} ${order.billing_address.postcode}</p>
                                <p>${order.billing_address.country}</p>
                            </div>
                        ` : ''}
                    </div>
                    <div>
                        <h4 class="font-bold mb-3">Order Summary</h4>
                        <div class="space-y-2">
                            <p><strong>Status:</strong> <span class="woodash-badge ${this.getStatusBadgeClass(order.status)}">${this.capitalizeFirst(order.status)}</span></p>
                            <p><strong>Total:</strong> ${order.formatted_total || order.total}</p>
                            <p><strong>Payment:</strong> <span class="woodash-badge ${order.payment === 'Paid' ? 'woodash-badge-success' : 'woodash-badge-warning'}">${order.payment}</span></p>
                            ${order.payment_method ? `<p><strong>Payment Method:</strong> ${order.payment_method}</p>` : ''}
                        </div>
                        ${order.subtotal ? `
                            <div class="mt-4 p-3 bg-gray-50 rounded">
                                <div class="flex justify-between"><span>Subtotal:</span><span>${order.subtotal}</span></div>
                                ${order.tax ? `<div class="flex justify-between"><span>Tax:</span><span>${order.tax}</span></div>` : ''}
                                ${order.shipping ? `<div class="flex justify-between"><span>Shipping:</span><span>${order.shipping}</span></div>` : ''}
                                <hr class="my-2">
                                <div class="flex justify-between font-bold"><span>Total:</span><span>${order.formatted_total || order.total}</span></div>
                            </div>
                        ` : ''}
                    </div>
                </div>
                <div class="mt-6">
                    <h4 class="font-bold mb-3">Order Items</h4>
                    <div class="overflow-x-auto">
                        <table class="woodash-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${order.items.map(item => `
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                ${item.image ? `<img src="${item.image}" alt="${item.name}" class="w-10 h-10 rounded object-cover">` : ''}
                                                <span>${item.name}</span>
                                            </div>
                                        </td>
                                        <td>${item.price}</td>
                                        <td>${item.qty || item.quantity}</td>
                                        <td>${item.total || item.price}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
                ${order.order_notes && order.order_notes.length > 0 ? `
                    <div class="mt-6">
                        <h4 class="font-bold mb-3">Order Notes</h4>
                        <div class="space-y-2">
                            ${order.order_notes.map(note => `
                                <div class="p-3 bg-gray-50 rounded">
                                    <div class="text-sm text-gray-500">${note.date}</div>
                                    <div>${note.note}</div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
            `;

            modal.classList.remove('hidden');
        }
    };

    // Initialize the orders manager
    OrdersManager.init();
});
</script>

</body>
</html>
