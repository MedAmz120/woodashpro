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
    <title>Reports Dashboard</title>
    
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

        .woodash-btn-success {
            background: #10B981;
            color: white;
        }

        .woodash-btn-success:hover {
            background: #059669;
        }

        /* Report Card Styles */
        .report-card {
            border: 2px solid #E5E7EB;
            border-radius: 0.75rem;
            padding: 1.5rem;
            transition: var(--transition-base);
            cursor: pointer;
        }

        .report-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 204, 97, 0.1);
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
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-marketing')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-bullhorn w-5"></i>
                <span>Marketing</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-reports')); ?>" class="woodash-nav-link active">
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
                    <h1 class="text-2xl font-bold woodash-gradient-text">Reports</h1>
                    <p class="text-gray-500">Generate comprehensive reports and export data.</p>
                </div>
            </header>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Reports Generated</h3>
                            <div class="text-3xl font-bold text-gray-900">127</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Scheduled Reports</h3>
                            <div class="text-3xl font-bold text-gray-900">8</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Data Exported</h3>
                            <div class="text-3xl font-bold text-gray-900">2.4GB</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-download"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Report Views</h3>
                            <div class="text-3xl font-bold text-gray-900">1,845</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Categories -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Sales Reports -->
                <div class="report-card" onclick="ReportsManager.generateReport('sales')">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                            <i class="fa-solid fa-chart-line text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Sales Reports</h3>
                            <p class="text-gray-500 text-sm">Revenue, orders, and performance</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Daily Sales</span>
                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Monthly Revenue</span>
                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Order Analysis</span>
                    </div>
                </div>

                <!-- Product Reports -->
                <div class="report-card" onclick="ReportsManager.generateReport('products')">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-box text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Product Reports</h3>
                            <p class="text-gray-500 text-sm">Inventory and product performance</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">Top Products</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">Stock Levels</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">Category Performance</span>
                    </div>
                </div>

                <!-- Customer Reports -->
                <div class="report-card" onclick="ReportsManager.generateReport('customers')">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-users text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Customer Reports</h3>
                            <p class="text-gray-500 text-sm">Customer behavior and analytics</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded">Customer LTV</span>
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded">Retention</span>
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded">Segmentation</span>
                    </div>
                </div>

                <!-- Marketing Reports -->
                <div class="report-card" onclick="ReportsManager.generateReport('marketing')">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                            <i class="fa-solid fa-bullhorn text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Marketing Reports</h3>
                            <p class="text-gray-500 text-sm">Campaign performance and ROI</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded">Campaign ROI</span>
                        <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded">Email Analytics</span>
                        <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded">Social Media</span>
                    </div>
                </div>

                <!-- Financial Reports -->
                <div class="report-card" onclick="ReportsManager.generateReport('financial')">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center">
                            <i class="fa-solid fa-dollar-sign text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Financial Reports</h3>
                            <p class="text-gray-500 text-sm">Financial statements and tax</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">P&L Statement</span>
                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Tax Reports</span>
                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Profit Margins</span>
                    </div>
                </div>

                <!-- Inventory Reports -->
                <div class="report-card" onclick="ReportsManager.generateReport('inventory')">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center">
                            <i class="fa-solid fa-boxes-stacked text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Inventory Reports</h3>
                            <p class="text-gray-500 text-sm">Stock levels and turnover</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-1 bg-teal-100 text-teal-700 text-xs rounded">Stock Turnover</span>
                        <span class="px-2 py-1 bg-teal-100 text-teal-700 text-xs rounded">Low Stock Alert</span>
                        <span class="px-2 py-1 bg-teal-100 text-teal-700 text-xs rounded">ABC Analysis</span>
                    </div>
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="woodash-card p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold woodash-gradient-text">Recent Reports</h3>
                    <button class="woodash-btn woodash-btn-secondary text-sm">
                        View All
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-medium text-gray-600">Report Name</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-600">Type</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-600">Date Created</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-600">Status</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="recent-reports-table">
                            <!-- Reports data will be loaded here -->
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

<!-- Report Generation Modal -->
<div id="report-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-lg font-bold woodash-gradient-text" id="modal-report-title">Generate Report</h3>
            <button id="close-report-modal" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="report-form" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                        <input type="date" name="date_from" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                        <input type="date" name="date_to" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                    <select name="format" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">Select Format</option>
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>
                <div id="report-options">
                    <!-- Dynamic options based on report type -->
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 p-6 border-t">
            <button type="button" id="cancel-report-btn" class="woodash-btn woodash-btn-secondary">Cancel</button>
            <button type="submit" form="report-form" class="woodash-btn woodash-btn-success">
                <i class="fa-solid fa-file-export"></i>
                Generate Report
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
    const ReportsManager = {
        state: {
            currentReportType: null,
            reportData: {}
        },

        init() {
            this.loadReportsData();
            this.initEventListeners();
            this.initCharts();
        },

        async loadReportsData() {
            // Load different types of report data
            await this.loadSalesReport();
            await this.loadProductReport();
            await this.loadCustomerReport();
        },

        async loadSalesReport() {
            try {
                const formData = new FormData();
                formData.append('action', 'woodash_get_sales_report');
                formData.append('nonce', woodash_ajax.nonce);
                formData.append('date_from', this.getDateRange().from);
                formData.append('date_to', this.getDateRange().to);

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.state.reportData.sales = result.data;
                    this.updateSalesStats(result.data);
                    this.updateSalesChart(result.data.daily_sales);
                } else {
                    console.error('Failed to load sales report:', result.data);
                    this.loadDemoSalesData();
                }
            } catch (error) {
                console.error('Error loading sales report:', error);
                this.loadDemoSalesData();
            }
        },

        async loadProductReport() {
            try {
                const formData = new FormData();
                formData.append('action', 'woodash_get_product_report');
                formData.append('nonce', woodash_ajax.nonce);

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.state.reportData.products = result.data;
                    this.renderTopProductsTable(result.data.top_products);
                } else {
                    console.error('Failed to load product report:', result.data);
                    this.loadDemoProductData();
                }
            } catch (error) {
                console.error('Error loading product report:', error);
                this.loadDemoProductData();
            }
        },

        async loadCustomerReport() {
            try {
                const formData = new FormData();
                formData.append('action', 'woodash_get_customer_report');
                formData.append('nonce', woodash_ajax.nonce);

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.state.reportData.customers = result.data;
                    this.updateCustomerStats(result.data);
                } else {
                    console.error('Failed to load customer report:', result.data);
                    this.loadDemoCustomerData();
                }
            } catch (error) {
                console.error('Error loading customer report:', error);
                this.loadDemoCustomerData();
            }
        },

        updateSalesStats(salesData) {
            // Update sales statistics cards
            const elements = {
                totalRevenue: document.querySelector('[data-stat="total-revenue"]'),
                totalOrders: document.querySelector('[data-stat="total-orders"]'),
                averageOrderValue: document.querySelector('[data-stat="average-order-value"]')
            };

            if (elements.totalRevenue) elements.totalRevenue.textContent = '$' + (salesData.total_revenue || 0).toFixed(2);
            if (elements.totalOrders) elements.totalOrders.textContent = (salesData.total_orders || 0).toLocaleString();
            if (elements.averageOrderValue) elements.averageOrderValue.textContent = '$' + (salesData.average_order_value || 0).toFixed(2);
        },

        updateCustomerStats(customerData) {
            // Update customer statistics
            const elements = {
                totalCustomers: document.querySelector('[data-stat="total-customers"]'),
                newCustomers: document.querySelector('[data-stat="new-customers"]')
            };

            if (elements.totalCustomers) elements.totalCustomers.textContent = (customerData.total_customers || 0).toLocaleString();
            if (elements.newCustomers) elements.newCustomers.textContent = (customerData.new_customers || 0).toLocaleString();
        },

        renderTopProductsTable(topProducts) {
            const tbody = document.querySelector('#top-products-table tbody');
            if (!tbody || !topProducts) return;

            tbody.innerHTML = '';

            topProducts.forEach((product, index) => {
                const row = document.createElement('tr');
                row.className = 'border-b border-gray-200 hover:bg-gray-50 transition-colors';
                
                row.innerHTML = `
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">${index + 1}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${product.name}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${product.total_sold || 0}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">$${(product.total_revenue || 0).toFixed(2)}</td>
                `;
                
                tbody.appendChild(row);
            });
        },

        updateSalesChart(dailySales) {
            if (!dailySales) return;

            const labels = Object.keys(dailySales);
            const revenueData = Object.values(dailySales).map(day => day.revenue || 0);
            const ordersData = Object.values(dailySales).map(day => day.orders || 0);

            // Update charts with real data
            this.initSalesChart(labels, revenueData, ordersData);
        },

        initSalesChart(labels, revenueData, ordersData) {
            const ctx = document.getElementById('salesChart');
            if (!ctx) return;

            // Destroy existing chart if it exists
            if (window.salesChartInstance) {
                window.salesChartInstance.destroy();
            }

            window.salesChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [
                        {
                            label: 'Revenue',
                            data: revenueData || [1200, 1900, 3000, 5000, 2000, 3000, 4500],
                            borderColor: '#00CC61',
                            backgroundColor: 'rgba(0, 204, 97, 0.1)',
                            tension: 0.4,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Orders',
                            data: ordersData || [12, 19, 30, 50, 20, 30, 45],
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false,
                            },
                        }
                    }
                }
            });
        },

        getDateRange() {
            // Get current month date range
            const now = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            
            return {
                from: firstDay.toISOString().split('T')[0],
                to: lastDay.toISOString().split('T')[0]
            };
        },

        async generateCustomReport(reportType, dateFrom, dateTo) {
            let endpoint = '';
            switch (reportType) {
                case 'sales':
                    endpoint = 'woodash_get_sales_report';
                    break;
                case 'products':
                    endpoint = 'woodash_get_product_report';
                    break;
                case 'customers':
                    endpoint = 'woodash_get_customer_report';
                    break;
                default:
                    alert('Invalid report type');
                    return;
            }

            try {
                const formData = new FormData();
                formData.append('action', endpoint);
                formData.append('nonce', woodash_ajax.nonce);
                if (dateFrom) formData.append('date_from', dateFrom);
                if (dateTo) formData.append('date_to', dateTo);

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.displayReportResults(reportType, result.data);
                } else {
                    alert('Failed to generate report: ' + result.data);
                }
            } catch (error) {
                console.error('Error generating report:', error);
                alert('Error generating report');
            }
        },

        displayReportResults(reportType, data) {
            // Create a modal or update UI to display report results
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
            modal.innerHTML = `
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900">${reportType.charAt(0).toUpperCase() + reportType.slice(1)} Report</h3>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="report-content">
                            <pre class="bg-gray-100 p-4 rounded-lg overflow-auto">${JSON.stringify(data, null, 2)}</pre>
                        </div>
                        <div class="mt-4 text-right">
                            <button onclick="ReportsManager.exportReport('${reportType}', ${JSON.stringify(data)})" 
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                Export CSV
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        },

        exportReport(reportType, data) {
            // Simple CSV export functionality
            let csv = '';
            if (reportType === 'sales' && data.daily_sales) {
                csv = 'Date,Revenue,Orders\n';
                Object.entries(data.daily_sales).forEach(([date, values]) => {
                    csv += `${date},${values.revenue || 0},${values.orders || 0}\n`;
                });
            } else if (reportType === 'products' && data.top_products) {
                csv = 'Product Name,Total Sold,Total Revenue\n';
                data.top_products.forEach(product => {
                    csv += `"${product.name}",${product.total_sold || 0},${product.total_revenue || 0}\n`;
                });
            }

            if (csv) {
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `${reportType}-report-${new Date().toISOString().split('T')[0]}.csv`;
                a.click();
                window.URL.revokeObjectURL(url);
            }
        },

        loadDemoSalesData() {
            // Fallback demo data
            this.updateSalesStats({
                total_revenue: 45250.50,
                total_orders: 127,
                average_order_value: 356.30
            });
        },

        loadDemoProductData() {
            // Fallback demo data
            this.renderTopProductsTable([
                { name: 'Demo Product 1', total_sold: 45, total_revenue: 1250.50 },
                { name: 'Demo Product 2', total_sold: 32, total_revenue: 980.00 }
            ]);
        },

        loadDemoCustomerData() {
            // Fallback demo data
            this.updateCustomerStats({
                total_customers: 543,
                new_customers: 47
            });
        },

        initEventListeners() {
            // Report modal
            const reportModal = document.getElementById('report-modal');
            const closeReportModal = document.getElementById('close-report-modal');
            const cancelReportBtn = document.getElementById('cancel-report-btn');
            
            closeReportModal?.addEventListener('click', () => {
                reportModal.classList.add('hidden');
            });
            
            cancelReportBtn?.addEventListener('click', () => {
                reportModal.classList.add('hidden');
            });

            // Form submission
            const reportForm = document.getElementById('report-form');
            reportForm?.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleReportGeneration(reportForm);
            });

            // Mobile menu toggle
            const menuToggle = document.getElementById('woodash-menu-toggle');
            const sidebar = document.querySelector('.woodash-sidebar');

            menuToggle?.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });

            // Report generation buttons
            document.querySelectorAll('[data-report-type]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const reportType = e.target.getAttribute('data-report-type');
                    this.generateReport(reportType);
                });
            });
        },

        generateReport(type) {
            const modal = document.getElementById('report-modal');
            this.state.currentReportType = type;
            
            // Set modal title
            const title = document.getElementById('report-modal-title');
            if (title) {
                title.textContent = `Generate ${type.charAt(0).toUpperCase() + type.slice(1)} Report`;
            }
            
            modal.classList.remove('hidden');
        },

        async handleReportGeneration(form) {
            const formData = new FormData(form);
            const dateFrom = formData.get('date_from');
            const dateTo = formData.get('date_to');
            
            if (this.state.currentReportType) {
                await this.generateCustomReport(this.state.currentReportType, dateFrom, dateTo);
                document.getElementById('report-modal').classList.add('hidden');
            }
        },

        initCharts() {
            // Initialize with demo data, will be updated with real data
            this.initSalesChart();
        }
    };

    // Initialize the reports manager with backend integration
    ReportsManager.init();
});
</script>

</body>
</html>
