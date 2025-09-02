/**
 * WoodDash Pro Dashboard JavaScript
 * 
 * @package WoodashPro
 * @version 2.0.0
 */

class WoodashDashboard {
    constructor() {
        // Initialize properties first
        this.salesChart = null;
        this.miniCharts = new Map();
        this.cache = new Map();
        this.refreshInterval = null;
        
        // Then initialize the dashboard
        this.init();
    }
    
    init() {
        // Check for required dependencies
        if (typeof jQuery === 'undefined') {
            console.error('WoodDash: jQuery is not loaded');
            this.showCriticalError('jQuery is not available. Please ensure jQuery is loaded before the dashboard script.');
            return;
        }
        
        if (typeof woodashData === 'undefined') {
            console.error('WoodDash: woodashData not found');
            this.showCriticalError('Dashboard configuration is missing. Please refresh the page.');
            return;
        }
        
        // Set up global jQuery reference for consistent usage throughout the class
        const $ = jQuery.noConflict();
        this.$ = $;
        window.$ = $;
        
        // Ensure jQuery is available globally for other scripts
        if (typeof window.jQuery === 'undefined') {
            window.jQuery = $;
        }
        
        this.bindEvents();
        this.loadInitialData();
        this.initializeCharts();
        this.setupAutoRefresh();
        this.setupSearch();
        this.setupNotifications();
    }
    
    showCriticalError(message) {
        // Use native JavaScript to show error if jQuery is not available
        const errorDiv = document.createElement('div');
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc2626;
            color: white;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 10000;
            max-width: 400px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        `;
        errorDiv.innerHTML = `
            <h4 style="margin: 0 0 8px 0; font-weight: 600;">WoodDash Pro Error</h4>
            <p style="margin: 0; font-size: 14px;">${message}</p>
        `;
        document.body.appendChild(errorDiv);
        
        // Auto remove after 10 seconds
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.parentNode.removeChild(errorDiv);
            }
        }, 10000);
    }
    
    bindEvents() {
        // Use jQuery with noConflict to avoid conflicts
        const $ = jQuery.noConflict();
        
        if (!$ || typeof $ !== 'function') {
            console.error('WoodDash: jQuery is not available in bindEvents');
            this.showCriticalError('jQuery is required but not available. Please ensure jQuery is loaded before the dashboard script.');
            return;
        }
        
        // Set up global jQuery reference for consistent usage throughout the class
        window.$ = $;
        this.$ = $;
        
        // Date filter change
        $(document).on('change', '#date-filter', this.handleDateFilterChange.bind(this));
        $(document).on('click', '#apply-custom-date', this.handleCustomDateApply.bind(this));
        
        // Chart granularity buttons
        $(document).on('click', '[data-range]', this.handleRangeChange.bind(this));
        
        // Export buttons
        $(document).on('click', '.export-products', this.exportProducts.bind(this));
        $(document).on('click', '.export-customers', this.exportCustomers.bind(this));
        
        // Slideshow controls
        $(document).on('click', '#toggle-slideshow', this.toggleSlideshow.bind(this));
        
        // Logout
        $(document).on('click', '.woodash-logout-btn', this.handleLogout.bind(this));
        
        // Refresh button
        $(document).on('click', '.refresh-data', this.refreshData.bind(this));
        
        // Settings button
        $(document).on('submit', '.woodash-settings-form', this.saveSettings.bind(this));
    }
    
    async loadInitialData() {
        this.showLoading();
        
        try {
            const dateRange = this.getCurrentDateRange();
            
            // Validate dateRange
            if (!dateRange || !dateRange.from || !dateRange.to) {
                throw new Error('Invalid date range');
            }
            
            const data = await this.fetchAnalyticsData(dateRange);
            
            // Validate response data
            if (!data) {
                throw new Error('No data received from server');
            }
            
            this.updateDashboard(data);
        } catch (error) {
            console.error('Error loading initial data:', error);
            this.showError(`Failed to load dashboard data: ${error.message}`);
        } finally {
            this.hideLoading();
        }
    }
    
    async fetchAnalyticsData(dateRange, granularity = 'daily') {
        // Validate input parameters
        if (!dateRange || !dateRange.from || !dateRange.to) {
            throw new Error('Invalid date range provided');
        }
        
        const cacheKey = `analytics_${dateRange.from}_${dateRange.to}_${granularity}`;
        
        // Initialize cache if not exists
        if (!this.cache) {
            this.cache = new Map();
        }
        
        // Check cache first
        if (this.cache.has(cacheKey)) {
            return this.cache.get(cacheKey);
        }
        
        // Validate woodashData availability
        if (typeof woodashData === 'undefined' || !woodashData.ajaxurl || !woodashData.nonce) {
            throw new Error('WoodDash configuration data is missing');
        }
        
        const formData = new FormData();
        formData.append('action', 'woodash_get_dashboard_data');
        formData.append('nonce', woodashData.nonce);
        formData.append('date_from', dateRange.from);
        formData.append('date_to', dateRange.to);
        formData.append('granularity', granularity);
        
        const response = await fetch(woodashData.ajaxurl, {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        // Validate response data
        if (!data) {
            throw new Error('Invalid response data received');
        }
        
        // Cache the result for 5 minutes
        this.cache.set(cacheKey, data);
        setTimeout(() => {
            if (this.cache && this.cache.has(cacheKey)) {
                this.cache.delete(cacheKey);
            }
        }, 5 * 60 * 1000);
        
        return data;
    }
    
    updateDashboard(data) {
        // Update metrics cards
        this.updateMetricCard('total-sales', this.formatCurrency(data.total_sales));
        this.updateMetricCard('total-orders', this.formatNumber(data.total_orders));
        this.updateMetricCard('aov', this.formatCurrency(data.aov));
        this.updateMetricCard('new-customers', this.formatNumber(data.new_customers || 0));
        
        // Update processing orders
        this.updateProcessingOrders(data.processing_orders || 0);
        
        // Update charts
        this.updateSalesChart(data.sales_overview);
        this.updateMiniCharts(data);
        
        // Update tables
        this.updateTopProducts(data.top_products || []);
        this.updateTopCustomers(data.top_customers || []);
        
        // Update last refresh time
        this.updateLastRefreshTime();
    }
    
    updateMetricCard(id, value) {
        const element = document.getElementById(id);
        if (element) {
            // Add animation
            element.style.opacity = '0.5';
            setTimeout(() => {
                element.textContent = value;
                element.style.opacity = '1';
            }, 150);
        }
    }
    
    async updateProcessingOrders(count) {
        const element = document.getElementById('pending-orders');
        if (element) {
            element.textContent = `Processing: ${this.formatNumber(count)}`;
        }
    }
    
    updateSalesChart(salesData) {
        if (!salesData || !salesData.labels || !salesData.data) {
            return;
        }
        
        const ctx = document.getElementById('sales-chart');
        if (!ctx) return;
        
        // Destroy existing chart
        if (this.salesChart) {
            this.salesChart.destroy();
        }
        
        this.salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesData.labels,
                datasets: [{
                    label: 'Sales',
                    data: salesData.data,
                    borderColor: '#00CC61',
                    backgroundColor: 'rgba(0, 204, 97, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#00CC61',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: (context) => {
                                return this.formatCurrency(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => this.formatCurrency(value)
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                }
            }
        });
    }
    
    updateMiniCharts(data) {
        const chartConfigs = [
            { id: 'mini-trend-sales', data: this.generateTrendData(data.sales_trend) },
            { id: 'mini-trend-orders', data: this.generateTrendData(data.orders_trend) },
            { id: 'mini-trend-aov', data: this.generateTrendData(data.aov_trend) },
            { id: 'mini-trend-customers', data: this.generateTrendData(data.customers_trend) }
        ];
        
        chartConfigs.forEach(config => {
            this.createMiniChart(config.id, config.data);
        });
    }
    
    createMiniChart(chartId, data) {
        const ctx = document.getElementById(chartId);
        if (!ctx) return;
        
        // Destroy existing chart
        if (this.miniCharts.has(chartId)) {
            this.miniCharts.get(chartId).destroy();
        }
        
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    borderColor: '#00CC61',
                    backgroundColor: 'rgba(0, 204, 97, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                },
                scales: {
                    x: { display: false },
                    y: { display: false }
                },
                elements: {
                    point: { radius: 0 }
                }
            }
        });
        
        this.miniCharts.set(chartId, chart);
    }
    
    updateTopProducts(products) {
        const tbody = document.getElementById('top-products');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        products.forEach((product, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="woodash-table-cell">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        <span class="font-medium">${this.escapeHtml(product.name)}</span>
                    </div>
                </td>
                <td class="woodash-table-cell">${this.formatNumber(product.sales)}</td>
                <td class="woodash-table-cell">${this.formatCurrency(product.revenue || 0)}</td>
                <td class="woodash-table-cell">
                    <div class="flex items-center gap-1">
                        <i class="fas fa-arrow-up text-green-500 text-sm"></i>
                        <span class="text-green-600">+${Math.floor(Math.random() * 20)}%</span>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }
    
    updateTopCustomers(customers) {
        const tbody = document.getElementById('top-customers');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        customers.forEach((customer, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="woodash-table-cell">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white text-sm font-semibold">
                            ${this.getInitials(customer.name)}
                        </div>
                        <span class="font-medium">${this.escapeHtml(customer.name)}</span>
                    </div>
                </td>
                <td class="woodash-table-cell">${this.formatNumber(customer.orders)}</td>
                <td class="woodash-table-cell">${this.formatCurrency(customer.total_spent || 0)}</td>
                <td class="woodash-table-cell">
                    <span class="woodash-badge woodash-badge-success">Active</span>
                </td>
            `;
            tbody.appendChild(row);
        });
    }
    
    handleDateFilterChange(event) {
        const range = event.target.value;
        
        if (range === 'custom') {
            document.getElementById('custom-date-from').classList.remove('hidden');
            document.getElementById('custom-date-to').classList.remove('hidden');
            return;
        }
        
        document.getElementById('custom-date-from').classList.add('hidden');
        document.getElementById('custom-date-to').classList.add('hidden');
        
        this.loadDataForRange(range);
    }
    
    handleCustomDateApply() {
        const fromDate = document.getElementById('custom-date-from').value;
        const toDate = document.getElementById('custom-date-to').value;
        
        if (fromDate && toDate) {
            this.loadDataForRange({ from: fromDate, to: toDate });
        }
    }
    
    handleRangeChange(event) {
        const range = event.target.dataset.range;
        
        // Update button states
        document.querySelectorAll('[data-range]').forEach(btn => {
            btn.classList.remove('woodash-btn-primary');
            btn.classList.add('woodash-btn-secondary');
        });
        
        event.target.classList.remove('woodash-btn-secondary');
        event.target.classList.add('woodash-btn-primary');
        
        // Reload chart with new granularity
        const dateRange = this.getCurrentDateRange();
        this.loadDataForRange(dateRange, range);
    }
    
    async loadDataForRange(range, granularity = 'daily') {
        this.showLoading();
        
        try {
            const dateRange = typeof range === 'string' ? this.getDateRangeForPeriod(range) : range;
            const data = await this.fetchAnalyticsData(dateRange, granularity);
            this.updateDashboard(data);
        } catch (error) {
            console.error('Error loading data for range:', error);
            this.showError('Failed to load data for selected range');
        } finally {
            this.hideLoading();
        }
    }
    
    getCurrentDateRange() {
        const filter = document.getElementById('date-filter');
        if (!filter) return this.getDateRangeForPeriod('today');
        
        const value = filter.value;
        if (value === 'custom') {
            const fromDate = document.getElementById('custom-date-from').value;
            const toDate = document.getElementById('custom-date-to').value;
            return { from: fromDate, to: toDate };
        }
        
        return this.getDateRangeForPeriod(value);
    }
    
    getDateRangeForPeriod(period) {
        const today = new Date();
        const from = new Date(today);
        const to = new Date(today);
        
        switch (period) {
            case 'yesterday':
                from.setDate(from.getDate() - 1);
                to.setDate(to.getDate() - 1);
                break;
            case 'last7days':
                from.setDate(from.getDate() - 6);
                break;
            case 'last30days':
                from.setDate(from.getDate() - 29);
                break;
            case 'thismonth':
                from.setDate(1);
                break;
            case 'lastmonth':
                from.setMonth(from.getMonth() - 1, 1);
                to.setDate(0); // Last day of previous month
                break;
            default: // today
                break;
        }
        
        return {
            from: from.toISOString().split('T')[0],
            to: to.toISOString().split('T')[0]
        };
    }
    
    setupAutoRefresh() {
        const interval = parseInt(woodashData.autoRefresh || 30) * 1000;
        
        this.refreshInterval = setInterval(() => {
            this.refreshData();
        }, interval);
    }
    
    async refreshData() {
        const dateRange = this.getCurrentDateRange();
        
        try {
            // Clear cache for current range
            const cacheKey = `analytics_${dateRange.from}_${dateRange.to}_daily`;
            this.cache.delete(cacheKey);
            
            const data = await this.fetchAnalyticsData(dateRange);
            this.updateDashboard(data);
            
            this.showNotification('Data refreshed successfully', 'success');
        } catch (error) {
            console.error('Error refreshing data:', error);
            this.showNotification('Failed to refresh data', 'error');
        }
    }
    
    setupSearch() {
        const searchInput = document.getElementById('woodash-global-search');
        if (!searchInput) return;
        
        const resultsContainer = document.getElementById('woodash-search-results');
        let searchTimeout;
        
        searchInput.addEventListener('input', (event) => {
            clearTimeout(searchTimeout);
            const query = event.target.value.trim();
            
            if (query.length < 2) {
                resultsContainer.classList.remove('active');
                return;
            }
            
            searchTimeout = setTimeout(() => {
                this.performSearch(query);
            }, 300);
        });
        
        // Hide results when clicking outside
        document.addEventListener('click', (event) => {
            if (!event.target.closest('.woodash-search-container')) {
                resultsContainer.classList.remove('active');
            }
        });
    }
    
    async performSearch(query) {
        const resultsContainer = document.getElementById('woodash-search-results');
        if (!resultsContainer) return;
        
        resultsContainer.innerHTML = '<div class="woodash-search-loading"><div class="woodash-search-loading-spinner"></div><p>Searching...</p></div>';
        resultsContainer.classList.add('active');
        
        try {
            // Search in products, orders, and customers
            const [products, orders, customers] = await Promise.all([
                this.searchProducts(query),
                this.searchOrders(query),
                this.searchCustomers(query)
            ]);
            
            this.displaySearchResults({ products, orders, customers });
        } catch (error) {
            console.error('Search error:', error);
            resultsContainer.innerHTML = '<div class="woodash-search-empty"><p>Search failed. Please try again.</p></div>';
        }
    }
    
    async searchProducts(query) {
        // Implement product search via REST API
        const response = await fetch(`${woodashData.restUrl}products?search=${encodeURIComponent(query)}&per_page=5`, {
            headers: {
                'X-WP-Nonce': woodashData.restNonce
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            return data.products || [];
        }
        
        return [];
    }
    
    async searchOrders(query) {
        // Implement order search
        // For now, return empty array
        return [];
    }
    
    async searchCustomers(query) {
        // Implement customer search
        // For now, return empty array
        return [];
    }
    
    displaySearchResults(results) {
        const resultsContainer = document.getElementById('woodash-search-results');
        if (!resultsContainer) return;
        
        let html = '';
        
        if (results.products.length > 0) {
            html += '<div class="woodash-search-category">Products</div>';
            results.products.forEach(product => {
                html += `
                    <div class="woodash-search-item" data-type="product" data-id="${product.id}">
                        <div class="woodash-search-item-icon" style="background: #00CC61;">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="woodash-search-item-content">
                            <div class="woodash-search-item-title">${this.escapeHtml(product.name)}</div>
                            <div class="woodash-search-item-subtitle">SKU: ${product.sku || 'N/A'} | ${this.formatCurrency(product.price)}</div>
                        </div>
                    </div>
                `;
            });
        }
        
        if (results.orders.length > 0) {
            html += '<div class="woodash-search-category">Orders</div>';
            results.orders.forEach(order => {
                html += `
                    <div class="woodash-search-item" data-type="order" data-id="${order.id}">
                        <div class="woodash-search-item-icon" style="background: #3b82f6;">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="woodash-search-item-content">
                            <div class="woodash-search-item-title">Order #${order.number}</div>
                            <div class="woodash-search-item-subtitle">${order.customer_name} | ${this.formatCurrency(order.total)}</div>
                        </div>
                    </div>
                `;
            });
        }
        
        if (results.customers.length > 0) {
            html += '<div class="woodash-search-category">Customers</div>';
            results.customers.forEach(customer => {
                html += `
                    <div class="woodash-search-item" data-type="customer" data-id="${customer.id}">
                        <div class="woodash-search-item-icon" style="background: #8b5cf6;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="woodash-search-item-content">
                            <div class="woodash-search-item-title">${this.escapeHtml(customer.display_name)}</div>
                            <div class="woodash-search-item-subtitle">${customer.email} | ${customer.orders_count} orders</div>
                        </div>
                    </div>
                `;
            });
        }
        
        if (html === '') {
            html = '<div class="woodash-search-empty"><p>No results found</p></div>';
        }
        
        resultsContainer.innerHTML = html;
    }
    
    setupNotifications() {
        // Setup notification system
        this.notificationContainer = document.createElement('div');
        this.notificationContainer.id = 'woodash-notifications';
        this.notificationContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            pointer-events: none;
        `;
        document.body.appendChild(this.notificationContainer);
    }
    
    showNotification(message, type = 'info', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `woodash-notification woodash-notification-${type}`;
        notification.style.cssText = `
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-left: 4px solid ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
            padding: 16px;
            margin-bottom: 8px;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
            pointer-events: auto;
        `;
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}" 
                   style="color: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};"></i>
                <span>${this.escapeHtml(message)}</span>
            </div>
        `;
        
        this.notificationContainer.appendChild(notification);
        
        // Trigger animation
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Auto remove
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, duration);
    }
    
    showLoading() {
        document.querySelectorAll('.woodash-metric-value').forEach(el => {
            el.style.opacity = '0.5';
        });
    }
    
    hideLoading() {
        document.querySelectorAll('.woodash-metric-value').forEach(el => {
            el.style.opacity = '1';
        });
    }
    
    showError(message) {
        this.showNotification(message, 'error', 5000);
    }
    
    toggleSlideshow() {
        const slideshow = document.getElementById('slideshow-section');
        const button = document.getElementById('toggle-slideshow');
        
        if (slideshow && button) {
            const isVisible = slideshow.style.display !== 'none';
            slideshow.style.display = isVisible ? 'none' : 'block';
            button.innerHTML = isVisible ? 
                '<i class="fa-solid fa-eye-slash"></i><span>Show Slideshow</span>' : 
                '<i class="fa-solid fa-eye"></i><span>Hide Slideshow</span>';
        }
    }
    
    async handleLogout(event) {
        event.preventDefault();
        
        if (!confirm('Are you sure you want to logout?')) {
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('action', 'woodash_logout');
            formData.append('nonce', woodashData.nonce);
            
            const response = await fetch(woodashData.ajaxurl, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success && data.data.redirect_url) {
                window.location.href = data.data.redirect_url;
            }
        } catch (error) {
            console.error('Logout error:', error);
            this.showError('Logout failed. Please try again.');
        }
    }
    
    async exportProducts(event) {
        event.preventDefault();
        
        const dateRange = this.getCurrentDateRange();
        const url = new URL(woodashData.ajaxurl);
        url.searchParams.set('action', 'woodash_export_products_csv');
        url.searchParams.set('nonce', woodashData.nonce);
        url.searchParams.set('date_from', dateRange.from);
        url.searchParams.set('date_to', dateRange.to);
        
        window.open(url.toString(), '_blank');
    }
    
    async exportCustomers(event) {
        event.preventDefault();
        
        const dateRange = this.getCurrentDateRange();
        const url = new URL(woodashData.ajaxurl);
        url.searchParams.set('action', 'woodash_export_customers_csv');
        url.searchParams.set('nonce', woodashData.nonce);
        url.searchParams.set('date_from', dateRange.from);
        url.searchParams.set('date_to', dateRange.to);
        
        window.open(url.toString(), '_blank');
    }
    
    async saveSettings(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        formData.append('action', 'woodash_save_settings');
        formData.append('nonce', woodashData.nonce);
        
        try {
            const response = await fetch(woodashData.ajaxurl, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Settings saved successfully', 'success');
            } else {
                this.showError(data.data || 'Failed to save settings');
            }
        } catch (error) {
            console.error('Settings save error:', error);
            this.showError('Failed to save settings');
        }
    }
    
    updateLastRefreshTime() {
        const element = document.getElementById('last-refresh-time');
        if (element) {
            element.textContent = new Date().toLocaleTimeString();
        }
    }
    
    initializeCharts() {
        // Initialize empty charts
        this.updateSalesChart({ labels: [], data: [] });
    }
    
    generateTrendData(trendData) {
        if (trendData && trendData.labels && trendData.values) {
            return trendData;
        }
        
        // Generate sample trend data
        const labels = [];
        const values = [];
        const baseValue = Math.random() * 100 + 50;
        
        for (let i = 0; i < 12; i++) {
            labels.push(i);
            values.push(baseValue + Math.sin(i / 2) * 20 + Math.random() * 10);
        }
        
        return { labels, values };
    }
    
    // Utility methods
    formatCurrency(amount) {
        if (typeof amount === 'string') {
            amount = parseFloat(amount) || 0;
        }
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    }
    
    formatNumber(number) {
        if (typeof number === 'string') {
            number = parseInt(number) || 0;
        }
        return new Intl.NumberFormat('en-US').format(number);
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    getInitials(name) {
        return name.split(' ').map(n => n[0]).join('').toUpperCase();
    }
    
    destroy() {
        // Cleanup
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
        
        if (this.salesChart) {
            this.salesChart.destroy();
        }
        
        this.miniCharts.forEach(chart => chart.destroy());
        this.miniCharts.clear();
        this.cache.clear();
    }
}

// Initialize dashboard when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Ensure jQuery is available
    if (typeof jQuery !== 'undefined') {
        window.woodashDashboard = new WoodashDashboard();
    } else {
        console.error('WoodDash Dashboard: jQuery is not loaded');
        // Try to wait for jQuery to load
        let attempts = 0;
        const maxAttempts = 10;
        const checkForjQuery = setInterval(() => {
            attempts++;
            if (typeof jQuery !== 'undefined') {
                clearInterval(checkForjQuery);
                window.woodashDashboard = new WoodashDashboard();
            } else if (attempts >= maxAttempts) {
                clearInterval(checkForjQuery);
                console.error('WoodDash Dashboard: jQuery failed to load after 10 attempts');
            }
        }, 500);
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (window.woodashDashboard) {
        window.woodashDashboard.destroy();
    }
});
