
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
    <title>Stock Dashboard</title>
    <script>
    window.woodash_ajax = {
        ajax_url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
        nonce: '<?php echo wp_create_nonce('woodash_nonce'); ?>'
    };
    </script>
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
            overflow: hidden;
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

        .woodash-table thead th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            background: #F9FAFB;
            border-bottom: 1px solid #E5E7EB;
        }

        .woodash-table tbody tr {
            border-bottom: 1px solid #F3F4F6;
        }

        .woodash-table tbody tr:last-child {
            border-bottom: none;
        }

        .woodash-table tbody td {
            padding: 0.75rem 1rem;
            color: #6B7280;
        }

        .woodash-table tbody tr:hover {
            background: #F9FAFB;
        }

        .woodash-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .woodash-search-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .woodash-search-input {
            padding: 0.5rem 1rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            background: white;
            color: #374151;
        }

        .woodash-search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 204, 97, 0.1);
        }

        .woodash-search-icon {
            font-size: 1rem;
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
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-stock')); ?>" class="woodash-nav-link active">
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
        <div class="max-w-7xl mx-auto woodash-fade-in">
            <!-- Header -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold woodash-gradient-text">Stock Management</h1>
                    <p class="text-gray-500">Monitor your inventory levels and manage stock.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="woodash-btn woodash-btn-primary" id="test-show-modal">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add Stock</span>
                    </button>
                </div>
            </header>

            <!-- Stock Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="woodash-metric-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">Total Items</h3>
                            <div class="woodash-metric-value font-bold text-gray-900">1,247</div>
                        </div>
                        <div class="woodash-metric-icon woodash-metric-green">
                            <i class="fa-solid fa-boxes-stacked"></i>
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
                            <div class="woodash-metric-value font-bold text-gray-900">8</div>
                        </div>
                        <div class="woodash-metric-icon woodash-metric-red">
                            <i class="fa-solid fa-times-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-metric-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">Total Value</h3>
                            <div class="woodash-metric-value font-bold text-gray-900">$84.2K</div>
                        </div>
                        <div class="woodash-metric-icon bg-purple-100 text-purple-600">
                            <i class="fa-solid fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Table -->
            <div class="woodash-card p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <h2 class="text-lg font-bold woodash-gradient-text">Stock Levels</h2>
                    <div class="flex items-center gap-3">
                        <div class="woodash-search-container">
                            <input type="text" placeholder="Search stock..." class="woodash-search-input">
                            <i class="fa-solid fa-search woodash-search-icon absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <select class="woodash-btn woodash-btn-secondary">
                            <option>All Items</option>
                            <option>Low Stock</option>
                            <option>Out of Stock</option>
                            <option>Overstocked</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="woodash-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Current Stock</th>
                                <th>Min Stock</th>
                                <th>Max Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="stock-table-body">
                            <!-- Stock data will be loaded here -->
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

<!-- Add Stock Item Modal -->
<div id="add-stock-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-lg font-bold woodash-gradient-text">Adjust Stock Level</h3>
            <button id="close-stock-modal" class="text-gray-400 hover:text-gray-600" aria-label="Close modal">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="add-stock-form" class="space-y-6" autocomplete="off">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="product_id">Product <span class="text-red-500">*</span></label>
                        <input type="text" name="product_id" id="product_id" class="woodash-search-input w-full" required aria-required="true" placeholder="Type product name or SKU">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="current_stock">Current Stock</label>
                        <input type="number" name="current_stock" id="current_stock" class="woodash-search-input w-full bg-gray-100" readonly tabindex="-1" aria-readonly="true">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center" for="adjustment">
                            Adjustment <span class="text-red-500 ml-1">*</span>
                            <span class="ml-2 text-xs text-gray-400" title="Enter a positive or negative number to increase or decrease stock."><i class="fa-solid fa-circle-info"></i></span>
                        </label>
                        <input type="number" name="adjustment" id="adjustment" class="woodash-search-input w-full" required aria-required="true" min="-9999" max="9999" step="1" placeholder="e.g. 10 or -5">
                        <span id="adjustment-error" class="text-xs text-red-500 hidden">Please enter a valid adjustment.</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="reason">Reason <span class="text-red-500">*</span></label>
                        <select name="reason" id="reason" class="woodash-search-input w-full" required aria-required="true">
                            <option value="">Select Reason</option>
                            <option value="purchase">New Purchase</option>
                            <option value="return">Customer Return</option>
                            <option value="damage">Damaged Items</option>
                            <option value="lost">Lost Items</option>
                            <option value="correction">Stock Correction</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="notes">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="woodash-search-input w-full" placeholder="Optional notes..."></textarea>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 p-6 border-t">
            <button type="button" id="cancel-stock-btn" class="woodash-btn woodash-btn-secondary">Cancel</button>
            <button type="submit" form="add-stock-form" class="woodash-btn woodash-btn-primary">Update Stock</button>
        </div>
    </div>
</div>

<script>


// Enhanced modal form interactivity
document.addEventListener('DOMContentLoaded', function() {
    // --- AJAX Product Dropdown & Stock Update Integration ---
    const productSelect = document.getElementById('product_id');
    const productImg = document.getElementById('product-img-preview');
    const currentStockInput = document.getElementById('current_stock');
    const adjustmentInput = document.getElementById('adjustment');
    const adjustmentError = document.getElementById('adjustment-error');
    const addStockForm = document.getElementById('add-stock-form');
    const addStockModal = document.getElementById('add-stock-modal');
    const closeStockModal = document.getElementById('close-stock-modal');
    const cancelStockBtn = document.getElementById('cancel-stock-btn');

    // Load products into dropdown
    async function loadProductsDropdown() {
        const formData = new FormData();
        formData.append('action', 'woodash_get_stock_data');
        formData.append('nonce', window.woodash_ajax.nonce);
        try {
            const res = await fetch(window.woodash_ajax.ajax_url, { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success && Array.isArray(data.data.products)) {
                productSelect.innerHTML = '<option value="">Select Product</option>';
                data.data.products.forEach(product => {
                    const opt = document.createElement('option');
                    opt.value = product.id;
                    opt.textContent = product.name + (product.sku ? ` (SKU: ${product.sku})` : '');
                    opt.setAttribute('data-sku', product.sku || '');
                    opt.setAttribute('data-img', 'https://dummyimage.com/40x40/00cc61/fff&text=' + encodeURIComponent(product.name.charAt(0)));
                    opt.setAttribute('data-stock', product.stock_quantity ?? '');
                    productSelect.appendChild(opt);
                });
            }
        } catch (e) {
            productSelect.innerHTML = '<option value="">Select Product</option>';
        }
    }
    loadProductsDropdown();

    // Product image preview and current stock
    productSelect?.addEventListener('change', function() {
        const selected = productSelect.options[productSelect.selectedIndex];
        const img = selected.getAttribute('data-img') || 'https://dummyimage.com/40x40/eee/aaa&text=?';
        productImg.src = img;
        const stock = selected.getAttribute('data-stock');
        currentStockInput.value = stock ?? '';
    });

    // Modal close buttons
    closeStockModal?.addEventListener('click', function() {
        addStockModal?.classList.add('hidden');
    });
    cancelStockBtn?.addEventListener('click', function() {
        addStockModal?.classList.add('hidden');
    });

    // Adjustment validation
    adjustmentInput?.addEventListener('input', function() {
        const val = parseInt(adjustmentInput.value, 10);
        if (isNaN(val) || val === 0) {
            adjustmentError.classList.remove('hidden');
        } else {
            adjustmentError.classList.add('hidden');
        }
    });

    // Form submit: AJAX update stock
    addStockForm?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const val = parseInt(adjustmentInput.value, 10);
        if (isNaN(val) || val === 0) {
            adjustmentError.classList.remove('hidden');
            adjustmentInput.focus();
            return;
        }
        const productId = productSelect.value;
        if (!productId) return;
        const selected = productSelect.options[productSelect.selectedIndex];
        const currentStock = parseInt(selected.getAttribute('data-stock') || '0', 10);
        const newStock = currentStock + val;
        // AJAX update
        const formData = new FormData();
        formData.append('action', 'woodash_update_stock');
        formData.append('nonce', window.woodash_ajax.nonce);
        formData.append('product_id', productId);
        formData.append('stock_quantity', newStock);
        try {
            const res = await fetch(window.woodash_ajax.ajax_url, { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                alert('Stock updated successfully');
                addStockForm.reset();
                addStockModal.classList.add('hidden');
                // Reload dropdown and table
                await loadProductsDropdown();
                if (window.StockManager && typeof window.StockManager.loadRealStockData === 'function') {
                    window.StockManager.loadRealStockData();
                }
            } else {
                alert('Failed to update stock: ' + (data.data || 'Unknown error'));
            }
        } catch (err) {
            alert('Error updating stock');
        }
    });
});
</script>

<!-- Admin User section (Moved to bottom left) -->
<div class="fixed bottom-0 left-0 p-4 bg-white/90 border-t border-r border-gray-100 rounded-tr-lg shadow-lg woodash-glass-effect">
    <div class="flex items-center gap-3 px-2">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center">
            <span class="text-white font-semibold">JD</span>
        </div>
        <div class="flex-1 min-w-0">
            <div class="font-medium text-gray-900">John Doe</div>
            <a href="#" class="text-sm text-[#00CC61] hover:underline woodash-logout-btn">Logout</a>
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
    // Stock Management with real backend integration
    const StockManager = {
        state: {
            currentPage: 1,
            itemsPerPage: 20,
            searchTimeout: null,
            stockData: []
        },

        init() {
            this.loadRealStockData();
            this.initEventListeners();
            this.initCharts();
        },

        async loadRealStockData() {
            try {
                const formData = new FormData();
                formData.append('action', 'woodash_get_stock_data');
                formData.append('nonce', window.woodash_ajax.nonce);

                const response = await fetch(window.woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.state.stockData = result.data.products;
                    this.updateStockStats(result.data);
                    this.renderStockTable();
                } else {
                    console.error('Failed to load stock data:', result.data);
                    this.loadDemoStockData(); // Fallback to demo data
                }
            } catch (error) {
                console.error('Error loading stock data:', error);
                this.loadDemoStockData(); // Fallback to demo data
            }
        },

        renderStockTable() {
            const tbody = document.getElementById('stock-table-body');
            if (!tbody) return;

            tbody.innerHTML = '';

            this.state.stockData.forEach(product => {
                const row = document.createElement('tr');
                row.className = 'border-b border-gray-200 hover:bg-gray-50 transition-colors';
                
                const stockStatus = this.getStockStatus(product);
                const statusClass = stockStatus.class;
                
                row.innerHTML = `
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">${product.name}</div>
                            <div class="text-sm text-gray-500 mt-1">SKU: ${product.sku || 'N/A'}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">${product.stock_quantity || 0}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                            ${stockStatus.text}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">${product.low_stock_amount || 5}</td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <button onclick="StockManager.adjustStock(${product.id}, '${product.name}')" 
                                class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="StockManager.viewMovements(${product.id})" 
                                class="text-green-600 hover:text-green-900">
                            <i class="fas fa-history"></i>
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        },

        getStockStatus(product) {
            if (product.stock_status === 'outofstock') {
                return { text: 'Out of Stock', class: 'bg-red-100 text-red-800' };
            } else if (product.is_low_stock) {
                return { text: 'Low Stock', class: 'bg-yellow-100 text-yellow-800' };
            } else if (product.stock_quantity > 0) {
                return { text: 'In Stock', class: 'bg-green-100 text-green-800' };
            } else {
                return { text: 'No Stock Info', class: 'bg-gray-100 text-gray-800' };
            }
        },

        updateStockStats(data) {
            // Update stats cards
            const elements = {
                totalProducts: document.querySelector('[data-stat="total-products"]'),
                lowStock: document.querySelector('[data-stat="low-stock"]'),
                outOfStock: document.querySelector('[data-stat="out-of-stock"]'),
                inStock: document.querySelector('[data-stat="in-stock"]')
            };

            if (elements.totalProducts) elements.totalProducts.textContent = data.total_products || 0;
            if (elements.lowStock) elements.lowStock.textContent = data.low_stock_count || 0;
            if (elements.outOfStock) elements.outOfStock.textContent = data.out_of_stock_count || 0;
            if (elements.inStock) {
                const inStockCount = (data.total_products || 0) - (data.low_stock_count || 0) - (data.out_of_stock_count || 0);
                elements.inStock.textContent = Math.max(0, inStockCount);
            }
        },

        async adjustStock(productId, productName) {
            const newQuantity = prompt(`Enter new stock quantity for "${productName}":`);
            if (newQuantity === null || newQuantity === '') return;

            const quantity = parseInt(newQuantity);
            if (isNaN(quantity) || quantity < 0) {
                alert('Please enter a valid quantity (0 or greater)');
                return;
            }

            try {
                const formData = new FormData();
                formData.append('action', 'woodash_update_stock');
                formData.append('nonce', window.woodash_ajax.nonce);
                formData.append('product_id', productId);
                formData.append('stock_quantity', quantity);

                const response = await fetch(window.woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    alert('Stock updated successfully');
                    this.loadRealStockData(); // Reload stock data
                } else {
                    alert('Failed to update stock: ' + result.data);
                }
            } catch (error) {
                console.error('Error updating stock:', error);
                alert('Error updating stock');
            }
        },

        viewMovements(productId) {
            // Placeholder for stock movements view
            alert('Stock movements for Product ID: ' + productId + '\n\nThis feature will show stock movement history.');
        },

        searchStock(query) {
            if (!query.trim()) {
                this.loadRealStockData();
                return;
            }

            const filteredData = this.state.stockData.filter(product => 
                product.name.toLowerCase().includes(query.toLowerCase()) ||
                (product.sku && product.sku.toLowerCase().includes(query.toLowerCase()))
            );

            this.state.stockData = filteredData;
            this.renderStockTable();
        },

        loadDemoStockData() {
            // Fallback demo data
            this.state.stockData = [
                {
                    id: 1,
                    name: 'Demo Product 1',
                    sku: 'DEMO001',
                    stock_quantity: 25,
                    stock_status: 'instock',
                    low_stock_amount: 10,
                    is_low_stock: false
                },
                {
                    id: 2,
                    name: 'Demo Product 2',
                    sku: 'DEMO002',
                    stock_quantity: 3,
                    stock_status: 'instock',
                    low_stock_amount: 5,
                    is_low_stock: true
                },
                {
                    id: 3,
                    name: 'Demo Product 3',
                    sku: 'DEMO003',
                    stock_quantity: 0,
                    stock_status: 'outofstock',
                    low_stock_amount: 5,
                    is_low_stock: false
                }
            ];
            
            this.renderStockTable();
            this.updateStockStats({
                total_products: 3,
                low_stock_count: 1,
                out_of_stock_count: 1
            });
        },

        initEventListeners() {
            // Add stock modal
            const addStockBtn = document.getElementById('add-stock-btn');
            const addStockModal = document.getElementById('add-stock-modal');
            const closeStockModal = document.getElementById('close-stock-modal');
            const cancelStockBtn = document.getElementById('cancel-stock-btn');
            
            addStockBtn?.addEventListener('click', () => {
                addStockModal.classList.remove('hidden');
            });
            
            closeStockModal?.addEventListener('click', () => {
                addStockModal.classList.add('hidden');
            });
            
            cancelStockBtn?.addEventListener('click', () => {
                addStockModal.classList.add('hidden');
            });

            // Form submission
            const addStockForm = document.getElementById('add-stock-form');
            addStockForm?.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleStockAdjustment(addStockForm);
            });

            // Search functionality
            const searchInput = document.querySelector('.woodash-search-input');
            searchInput?.addEventListener('input', (e) => {
                clearTimeout(this.state.searchTimeout);
                this.state.searchTimeout = setTimeout(() => {
                    this.searchStock(e.target.value);
                }, 300);
            });

            // Mobile menu toggle
            const menuToggle = document.getElementById('woodash-menu-toggle');
            const sidebar = document.querySelector('.woodash-sidebar');

            menuToggle?.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        },

        handleStockAdjustment(form) {
            const formData = new FormData(form);
            const productId = formData.get('product_id');
            const adjustment = formData.get('stock_adjustment');
            
            if (productId && adjustment) {
                alert(`Stock adjustment: Product ${productId}, Adjustment: ${adjustment}`);
                form.reset();
                document.getElementById('add-stock-modal').classList.add('hidden');
            }
        },
        },

        loadStockData() {
            // Sample stock data
            const fakeStockItems = [
                {
                    id: 1,
                    name: 'Wireless Headphones',
                    sku: 'WH-001',
                    currentStock: 45,
                    minStock: 10,
                    maxStock: 100,
                    status: 'In Stock'
                },
                {
                    id: 2,
                    name: 'Cotton T-Shirt',
                    sku: 'CT-002',
                    currentStock: 8,
                    minStock: 10,
                    maxStock: 50,
                    status: 'Low Stock'
                },
                {
                    id: 3,
                    name: 'Programming Book',
                    sku: 'PB-003',
                    currentStock: 0,
                    minStock: 5,
                    maxStock: 30,
                    status: 'Out of Stock'
                },
                {
                    id: 4,
                    name: 'Bluetooth Speaker',
                    sku: 'BS-004',
                    currentStock: 120,
                    minStock: 15,
                    maxStock: 80,
                    status: 'Overstocked'
                }
            ];

            this.renderStockTable(fakeStockItems);
        },

        renderStockTable(items) {
            const tbody = document.getElementById('stock-table-body');
            if (!tbody) return;

            const html = items.map(item => `
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center text-white font-semibold">
                                ${item.name.charAt(0)}
                            </div>
                            <span class="font-medium">${item.name}</span>
                        </div>
                    </td>
                    <td class="font-mono text-sm">${item.sku}</td>
                    <td>
                        <span class="font-medium ${this.getStockColorClass(item.currentStock, item.minStock)}">
                            ${item.currentStock}
                        </span>
                    </td>
                    <td>${item.minStock}</td>
                    <td>${item.maxStock}</td>
                    <td>
                        <span class="woodash-badge ${this.getStatusBadgeClass(item.status)}">
                            ${item.status}
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <button class="text-blue-600 hover:text-blue-800" title="Adjust Stock" onclick="StockManager.adjustStock(${item.id})">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-800" title="Stock History">
                                <i class="fa-solid fa-history"></i>
                            </button>
                            <button class="text-yellow-600 hover:text-yellow-800" title="Set Alert">
                                <i class="fa-solid fa-bell"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');

            tbody.innerHTML = html;
        },

        getStockColorClass(current, min) {
            if (current === 0) return 'text-red-600';
            if (current < min) return 'text-yellow-600';
            return 'text-green-600';
        },

        getStatusBadgeClass(status) {
            switch (status) {
                case 'In Stock':
                    return 'bg-green-100 text-green-700';
                case 'Low Stock':
                    return 'bg-yellow-100 text-yellow-700';
                case 'Out of Stock':
                    return 'bg-red-100 text-red-700';
                case 'Overstocked':
                    return 'bg-blue-100 text-blue-700';
                default:
                    return 'bg-gray-100 text-gray-700';
            }
        },

        adjustStock(itemId) {
            // Open modal with pre-filled data for the selected item
            const modal = document.getElementById('add-stock-modal');
            modal.classList.remove('hidden');
            // Here you would populate the form with the current item data
        },

        handleStockAdjustment(form) {
            const formData = new FormData(form);
            console.log('Adjusting stock:', Object.fromEntries(formData));
            
            // Close modal and refresh data
            document.getElementById('add-stock-modal').classList.add('hidden');
            form.reset();
            this.loadStockData();
        },

        searchStock(query) {
            console.log('Searching stock:', query);
            // Implement search functionality
        },

        initCharts() {
            // Initialize stock level charts if needed
            // Could show trends, alerts, etc.
        }
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

    // Make StockManager globally accessible
    window.StockManager = StockManager;
});
</script>

</body>
<footer>
    <p>&copy; <?php echo date('Y'); ?> WooDash Pro. All rights reserved.</p>
</footer>

<script>
document.getElementById('test-show-modal').onclick = function() {
    var modal = document.getElementById('add-stock-modal');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Test button: Modal should now be visible.');
    } else {
        console.log('Test button: Modal element not found.');
    }
};
</script>

</html>
