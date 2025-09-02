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
if (isset($_POST['woodash_customers_nonce'])) {
    if (!wp_verify_nonce($_POST['woodash_customers_nonce'], 'woodash_customers_action')) {
        wp_die(__('Security check failed. Please try again.', 'woodash-pro'));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WooDash Pro - Customers Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-color: #00CC61;
            --primary-dark: #00b357;
            --secondary-color: #6B7280;
            --background-color: #F8FAFC;
            --card-background: rgba(255, 255, 255, 0.95);
            --border-color: rgba(0, 0, 0, 0.1);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --transition-base: all 0.3s ease;
        }

        body {
            font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #374151;
            line-height: 1.6;
        }

        .woodash-fullscreen {
            min-height: 100vh;
            width: 100%;
        }

        .woodash-glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: var(--shadow-md);
        }

        .woodash-gradient-text {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .woodash-glow {
            box-shadow: 0 0 20px rgba(0, 204, 97, 0.4);
        }

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
            border-radius: 0.75rem;
            color: #6B7280;
            transition: var(--transition-base);
            text-decoration: none;
        }

        .woodash-nav-link:hover,
        .woodash-nav-link.active {
            background: rgba(0, 204, 97, 0.1);
            color: var(--primary-dark);
            transform: translateX(4px);
        }

        .woodash-main {
            margin-left: 16rem;
            transition: margin-left 0.3s ease;
            flex-grow: 1;
            overflow-y: auto;
        }

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

        .woodash-card {
            background: var(--card-background);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            transition: var(--transition-base);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .woodash-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .woodash-metric-card {
            background: var(--card-background);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            transition: var(--transition-base);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .woodash-metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .woodash-metric-card:hover::before {
            left: 100%;
        }

        .woodash-metric-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: var(--shadow-lg);
        }

        .woodash-metric-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6B7280;
            margin-bottom: 0.5rem;
        }

        .woodash-metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1F2937;
        }

        .woodash-metric-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .woodash-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: var(--transition-base);
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .woodash-btn-primary {
            background: linear-gradient(135deg, #00CC61 0%, #00b357 100%);
            color: white;
            box-shadow: 0 4px 14px 0 rgba(0, 204, 97, 0.25);
        }

        .woodash-btn-primary:hover {
            background: linear-gradient(135deg, #00b357 0%, #00994d 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px 0 rgba(0, 204, 97, 0.35);
        }

        .woodash-btn-secondary {
            background: #F3F4F6;
            color: #374151;
            border: 1px solid #E5E7EB;
        }

        .woodash-btn-secondary:hover {
            background: #E5E7EB;
            transform: translateY(-1px);
        }

        .woodash-table {
            width: 100%;
            border-collapse: collapse;
        }

        .woodash-table thead th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            background: #F9FAFB;
            border-bottom: 2px solid #E5E7EB;
        }

        .woodash-table tbody tr {
            border-bottom: 1px solid #F3F4F6;
            transition: background-color 0.2s;
        }

        .woodash-table tbody tr:hover {
            background: #F9FAFB;
        }

        .woodash-table tbody td {
            padding: 1rem;
            color: #6B7280;
        }

        .woodash-search-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .woodash-search-input {
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid #E5E7EB;
            border-radius: 0.75rem;
            background: white;
            color: #374151;
            transition: var(--transition-base);
        }

        .woodash-search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 204, 97, 0.1);
        }

        .woodash-search-icon {
            position: absolute;
            left: 1rem;
            color: #9CA3AF;
        }

        .woodash-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
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
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .woodash-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .woodash-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .woodash-badge-success {
            background: #DCFCE7;
            color: #166534;
        }

        .woodash-badge-warning {
            background: #FEF3C7;
            color: #92400E;
        }

        .woodash-badge-info {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .customer-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .action-btn {
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: var(--transition-base);
            margin: 0 0.25rem;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        .action-btn-view {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .action-btn-edit {
            background: #D1FAE5;
            color: #065F46;
        }

        .action-btn-delete {
            background: #FEE2E2;
            color: #991B1B;
        }
    </style>
</head>

<body>
    <button id="woodash-menu-toggle" class="woodash-menu-toggle woodash-btn woodash-btn-secondary">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div id="woodash-dashboard" class="woodash-fullscreen flex">
        <!-- Sidebar -->
        <aside class="woodash-sidebar w-64 bg-white/90 border-r border-gray-100 p-6 woodash-glass-effect">
            <div class="flex items-center gap-3 mb-8 woodash-fade-in">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center woodash-glow">
                    <i class="fa-solid fa-chart-line text-white text-xl"></i>
                </div>
                <h2 class="text-xl font-bold woodash-gradient-text">WooDash Pro</h2>
            </div>

            <nav class="space-y-2">
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
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-customers')); ?>" class="woodash-nav-link active">
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
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-settings')); ?>" class="woodash-nav-link">
                    <i class="fa-solid fa-gear w-5"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <main class="woodash-main flex-1 p-6 md:p-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 woodash-fade-in">
                    <div>
                        <h1 class="text-3xl font-bold woodash-gradient-text mb-2">Customer Management</h1>
                        <p class="text-gray-600">Manage your customer base and track their activity</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="woodash-btn woodash-btn-secondary">
                            <i class="fa-solid fa-download"></i>
                            <span>Export</span>
                        </button>
                        <button class="woodash-btn woodash-btn-primary" id="add-customer-btn">
                            <i class="fa-solid fa-plus"></i>
                            <span>Add Customer</span>
                        </button>
                    </div>
                </header>

                <!-- Customer Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="woodash-metric-card woodash-animate-in" style="animation-delay: 0.1s">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="woodash-metric-title flex items-center gap-2">
                                    Total Customers
                                    <span class="inline-block w-2 h-2 bg-green-400 rounded-full woodash-pulse"></span>
                                </h3>
                                <div class="woodash-metric-value" id="total-customers-count">1,247</div>
                                <div class="text-sm text-green-600 mt-2">
                                    <i class="fa-solid fa-arrow-up text-xs"></i>
                                    <span>+12.5% from last month</span>
                                </div>
                            </div>
                            <div class="woodash-metric-icon" style="background: linear-gradient(135deg, #00CC61, #00b357);">
                                <i class="fa-solid fa-users"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="woodash-metric-card woodash-animate-in" style="animation-delay: 0.2s">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="woodash-metric-title">New This Month</h3>
                                <div class="woodash-metric-value" id="new-customers-count">47</div>
                                <div class="text-sm text-blue-600 mt-2">
                                    <i class="fa-solid fa-arrow-up text-xs"></i>
                                    <span>+8.3% from last month</span>
                                </div>
                            </div>
                            <div class="woodash-metric-icon" style="background: linear-gradient(135deg, #3B82F6, #1E40AF);">
                                <i class="fa-solid fa-user-plus"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="woodash-metric-card woodash-animate-in" style="animation-delay: 0.3s">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="woodash-metric-title">Repeat Customers</h3>
                                <div class="woodash-metric-value" id="repeat-customers-percent">68%</div>
                                <div class="text-sm text-purple-600 mt-2">
                                    <i class="fa-solid fa-arrow-up text-xs"></i>
                                    <span>+5.2% from last month</span>
                                </div>
                            </div>
                            <div class="woodash-metric-icon" style="background: linear-gradient(135deg, #8B5CF6, #7C3AED);">
                                <i class="fa-solid fa-repeat"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="woodash-metric-card woodash-animate-in" style="animation-delay: 0.4s">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="woodash-metric-title">Avg. Order Value</h3>
                                <div class="woodash-metric-value" id="avg-order-value">$127</div>
                                <div class="text-sm text-orange-600 mt-2">
                                    <i class="fa-solid fa-arrow-up text-xs"></i>
                                    <span>+3.7% from last month</span>
                                </div>
                            </div>
                            <div class="woodash-metric-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                                <i class="fa-solid fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Analytics Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="woodash-card p-6 woodash-animate-in" style="animation-delay: 0.5s">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold woodash-gradient-text">Customer Growth</h3>
                            <div class="flex gap-2">
                                <button class="text-xs px-3 py-1 bg-green-100 text-green-700 rounded-full font-medium">30D</button>
                                <button class="text-xs px-3 py-1 bg-gray-100 text-gray-700 rounded-full font-medium">90D</button>
                                <button class="text-xs px-3 py-1 bg-gray-100 text-gray-700 rounded-full font-medium">1Y</button>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="customer-growth-chart"></canvas>
                        </div>
                    </div>
                    
                    <div class="woodash-card p-6 woodash-animate-in" style="animation-delay: 0.6s">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold woodash-gradient-text">Top Customers</h3>
                            <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All</button>
                        </div>
                        <div id="top-customers-list" class="space-y-4">
                            <!-- Top customers will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Customer Table -->
                <div class="woodash-card p-6 woodash-animate-in" style="animation-delay: 0.7s">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <h2 class="text-xl font-bold woodash-gradient-text">Customer List</h2>
                        <div class="flex items-center gap-3">
                            <div class="woodash-search-container">
                                <i class="fa-solid fa-search woodash-search-icon"></i>
                                <input type="text" placeholder="Search customers..." class="woodash-search-input" id="customer-search">
                            </div>
                            <select class="woodash-btn woodash-btn-secondary" id="customer-filter">
                                <option>All Customers</option>
                                <option>VIP Customers</option>
                                <option>New Customers</option>
                                <option>Inactive Customers</option>
                            </select>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="woodash-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Orders</th>
                                    <th>Total Spent</th>
                                    <th>Status</th>
                                    <th>Last Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="customers-table-body">
                                <!-- Customer data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Customer Modal -->
    <div id="add-customer-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="woodash-card w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 m-4">
            <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-6">
                <h3 class="text-xl font-bold woodash-gradient-text">Add New Customer</h3>
                <button id="close-customer-modal" class="text-gray-400 hover:text-gray-600 text-xl">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <form id="add-customer-form" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" class="woodash-search-input w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" class="woodash-search-input w-full" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" class="woodash-search-input w-full" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone" class="woodash-search-input w-full">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                        <input type="text" name="company" class="woodash-search-input w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type</label>
                        <select name="customer_type" class="woodash-search-input w-full">
                            <option value="regular">Regular</option>
                            <option value="vip">VIP</option>
                            <option value="wholesale">Wholesale</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" id="cancel-customer-btn" class="woodash-btn woodash-btn-secondary">Cancel</button>
                    <button type="submit" class="woodash-btn woodash-btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Add Customer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // WordPress AJAX Configuration
        window.woodash_ajax = {
            ajax_url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
            nonce: '<?php echo wp_create_nonce('woodash_nonce'); ?>'
        };

        document.addEventListener('DOMContentLoaded', function() {
            const CustomerManager = {
                state: {
                    searchTimeout: null,
                    currentPage: 1,
                    customersPerPage: 20,
                    totalCustomers: 0,
                    customers: [],
                    filteredCustomers: []
                },

                init() {
                    this.loadCustomers();
                    this.initEventListeners();
                    this.initCharts();
                    this.loadTopCustomers();
                    this.animateStats();
                },

                async loadCustomers() {
                    try {
                        // Try to load from WordPress backend first
                        const formData = new FormData();
                        formData.append('action', 'woodash_get_customers');
                        formData.append('nonce', woodash_ajax.nonce);
                        formData.append('page', this.state.currentPage);
                        formData.append('per_page', this.state.customersPerPage);

                        const response = await fetch(woodash_ajax.ajax_url, {
                            method: 'POST',
                            body: formData
                        });

                        const result = await response.json();
                        
                        if (result.success) {
                            this.state.customers = result.data.customers;
                            this.state.totalCustomers = result.data.total;
                        } else {
                            throw new Error('Backend not available');
                        }
                    } catch (error) {
                        console.log('Loading demo data...');
                        this.loadDemoCustomers();
                    }
                    
                    this.renderCustomersTable();
                },

                loadDemoCustomers() {
                    this.state.customers = [
                        {
                            id: 1,
                            first_name: 'John',
                            last_name: 'Smith',
                            email: 'john.smith@example.com',
                            phone: '+1-555-0101',
                            total_orders: 12,
                            total_spent: 2450.00,
                            status: 'VIP',
                            last_order: '2024-01-15',
                            avatar: 'JS'
                        },
                        {
                            id: 2,
                            first_name: 'Sarah',
                            last_name: 'Johnson',
                            email: 'sarah.johnson@example.com',
                            phone: '+1-555-0102',
                            total_orders: 8,
                            total_spent: 1890.50,
                            status: 'Regular',
                            last_order: '2024-01-10',
                            avatar: 'SJ'
                        },
                        {
                            id: 3,
                            first_name: 'Mike',
                            last_name: 'Davis',
                            email: 'mike.davis@example.com',
                            phone: '+1-555-0103',
                            total_orders: 6,
                            total_spent: 1650.75,
                            status: 'Regular',
                            last_order: '2024-01-08',
                            avatar: 'MD'
                        },
                        {
                            id: 4,
                            first_name: 'Emily',
                            last_name: 'Brown',
                            email: 'emily.brown@example.com',
                            phone: '+1-555-0104',
                            total_orders: 9,
                            total_spent: 1420.25,
                            status: 'VIP',
                            last_order: '2024-01-12',
                            avatar: 'EB'
                        },
                        {
                            id: 5,
                            first_name: 'David',
                            last_name: 'Wilson',
                            email: 'david.wilson@example.com',
                            phone: '+1-555-0105',
                            total_orders: 5,
                            total_spent: 1180.00,
                            status: 'Regular',
                            last_order: '2024-01-05',
                            avatar: 'DW'
                        }
                    ];
                    this.state.totalCustomers = this.state.customers.length;
                },

                renderCustomersTable() {
                    const tbody = document.getElementById('customers-table-body');
                    if (!tbody) return;

                    const customersToShow = this.state.filteredCustomers.length > 0 ? 
                        this.state.filteredCustomers : this.state.customers;

                    tbody.innerHTML = customersToShow.map(customer => `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="customer-avatar">
                                        ${customer.avatar || (customer.first_name[0] + customer.last_name[0])}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">
                                            ${customer.first_name} ${customer.last_name}
                                        </div>
                                        <div class="text-sm text-gray-500">${customer.phone || 'No phone'}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-gray-900">${customer.email}</div>
                            </td>
                            <td>
                                <span class="woodash-badge woodash-badge-info">${customer.total_orders}</span>
                            </td>
                            <td>
                                <div class="font-semibold text-gray-900">$${customer.total_spent.toFixed(2)}</div>
                            </td>
                            <td>
                                <span class="woodash-badge ${customer.status === 'VIP' ? 'woodash-badge-warning' : 'woodash-badge-success'}">
                                    ${customer.status}
                                </span>
                            </td>
                            <td>
                                <div class="text-sm text-gray-500">${this.formatDate(customer.last_order)}</div>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <button class="action-btn action-btn-view" onclick="CustomerManager.viewCustomer(${customer.id})" title="View Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button class="action-btn action-btn-edit" onclick="CustomerManager.editCustomer(${customer.id})" title="Edit Customer">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                    <button class="action-btn action-btn-delete" onclick="CustomerManager.deleteCustomer(${customer.id})" title="Delete Customer">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                },

                loadTopCustomers() {
                    const container = document.getElementById('top-customers-list');
                    if (!container) return;

                    const topCustomers = this.state.customers
                        .sort((a, b) => b.total_spent - a.total_spent)
                        .slice(0, 5);

                    container.innerHTML = topCustomers.map((customer, index) => `
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 cursor-pointer">
                            <div class="customer-avatar">
                                ${customer.avatar || (customer.first_name[0] + customer.last_name[0])}
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">${customer.first_name} ${customer.last_name}</div>
                                <div class="text-sm text-gray-500">${customer.total_orders} orders</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-green-600">$${customer.total_spent.toFixed(2)}</div>
                                <div class="text-xs text-gray-500">#${index + 1} Customer</div>
                            </div>
                        </div>
                    `).join('');
                },

                initCharts() {
                    const ctx = document.getElementById('customer-growth-chart');
                    if (!ctx) return;

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                            datasets: [{
                                label: 'New Customers',
                                data: [45, 52, 38, 67, 73, 89, 95, 102, 87, 94, 108, 125],
                                borderColor: '#00CC61',
                                backgroundColor: 'rgba(0, 204, 97, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#00CC61',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 5
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
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    },
                                    ticks: {
                                        color: '#6B7280'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#6B7280'
                                    }
                                }
                            },
                            elements: {
                                point: {
                                    hoverRadius: 8
                                }
                            }
                        }
                    });
                },

                animateStats() {
                    // Animate the stat numbers
                    this.animateNumber('total-customers-count', 1247);
                    this.animateNumber('new-customers-count', 47);
                    this.animateNumber('repeat-customers-percent', 68, '%');
                    this.animateNumber('avg-order-value', 127, '$');
                },

                animateNumber(elementId, targetValue, prefix = '') {
                    const element = document.getElementById(elementId);
                    if (!element) return;

                    const startValue = 0;
                    const duration = 2000;
                    const startTime = performance.now();

                    const animate = (currentTime) => {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        
                        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                        const currentValue = Math.floor(startValue + (targetValue - startValue) * easeOutQuart);
                        
                        if (prefix === '$') {
                            element.textContent = `$${currentValue.toLocaleString()}`;
                        } else if (prefix === '%') {
                            element.textContent = `${currentValue}%`;
                        } else {
                            element.textContent = currentValue.toLocaleString();
                        }

                        if (progress < 1) {
                            requestAnimationFrame(animate);
                        }
                    };

                    requestAnimationFrame(animate);
                },

                initEventListeners() {
                    // Mobile menu toggle
                    const menuToggle = document.getElementById('woodash-menu-toggle');
                    const sidebar = document.querySelector('.woodash-sidebar');
                    
                    menuToggle?.addEventListener('click', () => {
                        sidebar.classList.toggle('active');
                    });

                    // Add customer modal
                    const addCustomerBtn = document.getElementById('add-customer-btn');
                    const addCustomerModal = document.getElementById('add-customer-modal');
                    const closeCustomerModal = document.getElementById('close-customer-modal');
                    const cancelCustomerBtn = document.getElementById('cancel-customer-btn');
                    
                    addCustomerBtn?.addEventListener('click', () => {
                        addCustomerModal.classList.remove('hidden');
                    });
                    
                    closeCustomerModal?.addEventListener('click', () => {
                        addCustomerModal.classList.add('hidden');
                    });
                    
                    cancelCustomerBtn?.addEventListener('click', () => {
                        addCustomerModal.classList.add('hidden');
                    });

                    // Form submission
                    const addCustomerForm = document.getElementById('add-customer-form');
                    addCustomerForm?.addEventListener('submit', (e) => {
                        e.preventDefault();
                        this.handleAddCustomer(addCustomerForm);
                    });

                    // Search functionality
                    const searchInput = document.getElementById('customer-search');
                    searchInput?.addEventListener('input', (e) => {
                        clearTimeout(this.state.searchTimeout);
                        this.state.searchTimeout = setTimeout(() => {
                            this.searchCustomers(e.target.value);
                        }, 300);
                    });

                    // Filter functionality
                    const filterSelect = document.getElementById('customer-filter');
                    filterSelect?.addEventListener('change', (e) => {
                        this.filterCustomers(e.target.value);
                    });
                },

                searchCustomers(query) {
                    if (!query.trim()) {
                        this.state.filteredCustomers = [];
                        this.renderCustomersTable();
                        return;
                    }

                    this.state.filteredCustomers = this.state.customers.filter(customer => 
                        customer.first_name.toLowerCase().includes(query.toLowerCase()) ||
                        customer.last_name.toLowerCase().includes(query.toLowerCase()) ||
                        customer.email.toLowerCase().includes(query.toLowerCase())
                    );

                    this.renderCustomersTable();
                },

                filterCustomers(filter) {
                    if (filter === 'All Customers') {
                        this.state.filteredCustomers = [];
                    } else if (filter === 'VIP Customers') {
                        this.state.filteredCustomers = this.state.customers.filter(c => c.status === 'VIP');
                    } else if (filter === 'New Customers') {
                        this.state.filteredCustomers = this.state.customers.filter(c => c.total_orders <= 2);
                    } else if (filter === 'Inactive Customers') {
                        this.state.filteredCustomers = this.state.customers.filter(c => {
                            const lastOrder = new Date(c.last_order);
                            const thirtyDaysAgo = new Date();
                            thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
                            return lastOrder < thirtyDaysAgo;
                        });
                    }

                    this.renderCustomersTable();
                },

                async handleAddCustomer(form) {
                    const formData = new FormData(form);
                    
                    try {
                        formData.append('action', 'woodash_create_customer');
                        formData.append('nonce', woodash_ajax.nonce);

                        const response = await fetch(woodash_ajax.ajax_url, {
                            method: 'POST',
                            body: formData
                        });

                        const result = await response.json();
                        
                        if (result.success) {
                            this.showNotification('Customer created successfully!', 'success');
                            form.reset();
                            document.getElementById('add-customer-modal').classList.add('hidden');
                            this.loadCustomers();
                        } else {
                            throw new Error(result.data || 'Failed to create customer');
                        }
                    } catch (error) {
                        console.error('Error creating customer:', error);
                        
                        // Demo mode - add to local array
                        const newCustomer = {
                            id: this.state.customers.length + 1,
                            first_name: formData.get('first_name'),
                            last_name: formData.get('last_name'),
                            email: formData.get('email'),
                            phone: formData.get('phone'),
                            total_orders: 0,
                            total_spent: 0,
                            status: 'Regular',
                            last_order: new Date().toISOString().split('T')[0],
                            avatar: formData.get('first_name')[0] + formData.get('last_name')[0]
                        };
                        
                        this.state.customers.unshift(newCustomer);
                        this.renderCustomersTable();
                        this.loadTopCustomers();
                        
                        this.showNotification('Customer created successfully! (Demo Mode)', 'success');
                        form.reset();
                        document.getElementById('add-customer-modal').classList.add('hidden');
                    }
                },

                viewCustomer(customerId) {
                    const customer = this.state.customers.find(c => c.id === customerId);
                    if (!customer) return;

                    this.showNotification(`Viewing ${customer.first_name} ${customer.last_name}`, 'info');
                },

                editCustomer(customerId) {
                    const customer = this.state.customers.find(c => c.id === customerId);
                    if (!customer) return;

                    this.showNotification(`Edit functionality for ${customer.first_name} ${customer.last_name}`, 'info');
                },

                deleteCustomer(customerId) {
                    const customer = this.state.customers.find(c => c.id === customerId);
                    if (!customer) return;

                    if (confirm(`Are you sure you want to delete ${customer.first_name} ${customer.last_name}?`)) {
                        this.state.customers = this.state.customers.filter(c => c.id !== customerId);
                        this.renderCustomersTable();
                        this.loadTopCustomers();
                        this.showNotification('Customer deleted successfully!', 'success');
                    }
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                },

                showNotification(message, type = 'info') {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full`;
                    
                    const colors = {
                        success: 'bg-green-500 text-white',
                        error: 'bg-red-500 text-white',
                        info: 'bg-blue-500 text-white',
                        warning: 'bg-yellow-500 text-black'
                    };
                    
                    notification.className += ` ${colors[type]}`;
                    notification.textContent = message;
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.classList.remove('translate-x-full');
                    }, 100);
                    
                    setTimeout(() => {
                        notification.classList.add('translate-x-full');
                        setTimeout(() => {
                            document.body.removeChild(notification);
                        }, 300);
                    }, 3000);
                }
            };

            // Initialize the customer manager
            CustomerManager.init();
        });
    </script>
</body>
</html>