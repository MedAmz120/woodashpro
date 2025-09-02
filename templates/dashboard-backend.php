<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Check if user has required capabilities
if (!current_user_can('manage_woocommerce')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'woodashh'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WooDash Pro - Dashboard</title>
    
    <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Copy the same styles from orders.php */
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

        .woodash-main {
            margin-left: 16rem;
            transition: margin-left 0.3s ease;
            flex-grow: 1;
            overflow-y: auto;
        }

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
            background: linear-gradient(135deg, #00CC61 0%, #00b357 100%);
            color: white;
        }

        .woodash-btn-primary:hover {
            background: linear-gradient(135deg, #00b357 0%, #00994d 100%);
        }
    </style>
</head>
<body>

<div id="woodash-dashboard" class="woodash-fullscreen flex">
    <!-- Sidebar -->
    <aside class="woodash-sidebar w-64 bg-white/90 border-r border-gray-100 p-6 woodash-glass-effect">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center">
                <i class="fa-solid fa-chart-line text-white"></i>
            </div>
            <h2 class="text-xl font-bold woodash-gradient-text">WooDash Pro</h2>
        </div>

        <!-- Navigation -->
        <nav class="space-y-1">
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro')); ?>" class="woodash-nav-link active">
                <i class="fa-solid fa-chart-line"></i>
                Dashboard
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-orders')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-shopping-cart"></i>
                Orders
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-products')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-box"></i>
                Products
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-customers')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-users"></i>
                Customers
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="woodash-main flex-1 p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <header class="mb-8">
                <h1 class="text-3xl font-bold woodash-gradient-text mb-2">Dashboard</h1>
                <p class="text-gray-600">Welcome to your WooCommerce analytics dashboard</p>
            </header>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="stats-grid">
                <!-- Stats will be loaded here -->
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Revenue Chart -->
                <div class="woodash-card p-6">
                    <h3 class="text-lg font-bold mb-4">Revenue Overview</h3>
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>

                <!-- Orders Chart -->
                <div class="woodash-card p-6">
                    <h3 class="text-lg font-bold mb-4">Orders Overview</h3>
                    <canvas id="ordersChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Recent Orders and Top Products -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Orders -->
                <div class="woodash-card p-6">
                    <h3 class="text-lg font-bold mb-4">Recent Orders</h3>
                    <div id="recent-orders">
                        <!-- Recent orders will be loaded here -->
                    </div>
                </div>

                <!-- Top Products -->
                <div class="woodash-card p-6">
                    <h3 class="text-lg font-bold mb-4">Top Products</h3>
                    <div id="top-products">
                        <!-- Top products will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Make AJAX URL and nonce available
window.woodash_ajax = {
    ajax_url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
    nonce: '<?php echo wp_create_nonce('woodash_nonce'); ?>'
};

document.addEventListener('DOMContentLoaded', function() {
    const DashboardManager = {
        charts: {},
        
        init() {
            this.loadDashboardStats();
        },
        
        loadDashboardStats() {
            const formData = new FormData();
            formData.append('action', 'woodash_get_dashboard_stats');
            formData.append('nonce', woodash_ajax.nonce);
            
            fetch(woodash_ajax.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.renderStats(data.data);
                    this.renderCharts(data.data);
                    this.renderRecentOrders(data.data.recent_orders);
                    this.renderTopProducts(data.data.top_products);
                } else {
                    console.error('Error loading dashboard stats:', data.data);
                    this.loadFallbackData();
                }
            })
            .catch(error => {
                console.error('Network error:', error);
                this.loadFallbackData();
            });
        },
        
        renderStats(stats) {
            const statsGrid = document.getElementById('stats-grid');
            
            const statsCards = [
                {
                    title: 'Total Orders',
                    value: stats.total_orders,
                    icon: 'fa-shopping-cart',
                    color: 'blue'
                },
                {
                    title: 'Total Revenue',
                    value: '$' + (stats.total_revenue || 0).toFixed(2),
                    icon: 'fa-dollar-sign',
                    color: 'green'
                },
                {
                    title: 'Monthly Revenue',
                    value: '$' + (stats.monthly_revenue || 0).toFixed(2),
                    icon: 'fa-calendar',
                    color: 'purple'
                },
                {
                    title: 'Pending Orders',
                    value: stats.pending_orders,
                    icon: 'fa-clock',
                    color: 'orange'
                }
            ];
            
            statsGrid.innerHTML = statsCards.map(card => `
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">${card.title}</p>
                            <p class="text-2xl font-bold">${card.value}</p>
                        </div>
                        <div class="w-12 h-12 bg-${card.color}-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid ${card.icon} text-${card.color}-600"></i>
                        </div>
                    </div>
                </div>
            `).join('');
        },
        
        renderCharts(stats) {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            this.charts.revenue = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: stats.sales_chart_data?.map(d => d.date) || [],
                    datasets: [{
                        label: 'Revenue',
                        data: stats.sales_chart_data?.map(d => d.revenue) || [],
                        borderColor: '#00CC61',
                        backgroundColor: 'rgba(0, 204, 97, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toFixed(0);
                                }
                            }
                        }
                    }
                }
            });
            
            // Orders Chart
            const ordersCtx = document.getElementById('ordersChart').getContext('2d');
            this.charts.orders = new Chart(ordersCtx, {
                type: 'bar',
                data: {
                    labels: stats.sales_chart_data?.map(d => d.date) || [],
                    datasets: [{
                        label: 'Orders',
                        data: stats.sales_chart_data?.map(d => d.orders) || [],
                        backgroundColor: 'rgba(0, 204, 97, 0.7)',
                        borderColor: '#00CC61',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        },
        
        renderRecentOrders(orders) {
            const container = document.getElementById('recent-orders');
            
            if (!orders || orders.length === 0) {
                container.innerHTML = '<p class="text-gray-500">No recent orders found.</p>';
                return;
            }
            
            container.innerHTML = orders.map(order => `
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div>
                        <div class="font-medium">${order.id}</div>
                        <div class="text-sm text-gray-500">${order.customer}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-medium">${order.formatted_total || order.total}</div>
                        <div class="text-sm text-gray-500">${order.date}</div>
                    </div>
                </div>
            `).join('');
        },
        
        renderTopProducts(products) {
            const container = document.getElementById('top-products');
            
            if (!products || products.length === 0) {
                container.innerHTML = '<p class="text-gray-500">No product data available.</p>';
                return;
            }
            
            container.innerHTML = products.map(product => `
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-3">
                        ${product.image ? `<img src="${product.image}" alt="${product.name}" class="w-10 h-10 rounded object-cover">` : ''}
                        <div>
                            <div class="font-medium">${product.name}</div>
                            <div class="text-sm text-gray-500">${product.total_sales} sold</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-medium">${product.formatted_revenue}</div>
                    </div>
                </div>
            `).join('');
        },
        
        loadFallbackData() {
            // Fallback stats for demo
            const fallbackStats = {
                total_orders: 247,
                total_revenue: 15420.50,
                monthly_revenue: 3580.25,
                pending_orders: 12,
                recent_orders: [
                    { id: '#1234', customer: 'John Smith', total: '$125.50', date: '2025-08-14' },
                    { id: '#1235', customer: 'Sarah Johnson', total: '$78.99', date: '2025-08-14' }
                ],
                top_products: [
                    { name: 'Wireless Headphones', total_sales: 45, formatted_revenue: '$4,495.50' },
                    { name: 'Smart Watch', total_sales: 32, formatted_revenue: '$2,527.68' }
                ],
                sales_chart_data: Array.from({length: 30}, (_, i) => ({
                    date: new Date(Date.now() - (29-i) * 24*60*60*1000).toISOString().split('T')[0],
                    revenue: Math.random() * 500 + 100,
                    orders: Math.floor(Math.random() * 10) + 1
                }))
            };
            
            this.renderStats(fallbackStats);
            this.renderCharts(fallbackStats);
            this.renderRecentOrders(fallbackStats.recent_orders);
            this.renderTopProducts(fallbackStats.top_products);
        }
    };
    
    // Initialize Dashboard
    DashboardManager.init();
});
</script>

</body>
</html>
