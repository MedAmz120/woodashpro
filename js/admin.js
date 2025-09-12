/**
 * WooDash Pro Admin JavaScript
 * 
 * Handles admin interface interactions, AJAX requests, and dashboard functionality
 */

(function($) {
    'use strict';

    // Global WooDash Admin object
    window.WoodashAdmin = {
        // Configuration
        config: {
            ajaxurl: woodash_admin_vars.ajaxurl || '',
            nonce: woodash_admin_vars.nonce || '',
            restUrl: woodash_admin_vars.restUrl || '',
            restNonce: woodash_admin_vars.restNonce || '',
            debug: woodash_admin_vars.debug || false
        },

        // Cache DOM elements
        cache: {
            $body: null,
            $pageContainer: null,
            $navLinks: null,
            $pages: null
        },

        // Current state
        state: {
            currentPage: 'dashboard',
            isLoading: false,
            refreshInterval: null
        },

        // Initialize the admin interface
        init: function() {
            this.cacheElements();
            this.bindEvents();
            this.initNavigation();
            this.initDashboard();
            this.log('WooDash Admin initialized');
        },

        // Cache frequently used DOM elements
        cacheElements: function() {
            this.cache.$body = $('body');
            this.cache.$pageContainer = $('.woodash-page-container');
            this.cache.$navLinks = $('.woodash-nav a');
            this.cache.$pages = $('.woodash-page');
        },

        // Bind event listeners
        bindEvents: function() {
            var self = this;

            // Navigation click handlers
            this.cache.$navLinks.on('click', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                if (page) {
                    self.navigateToPage(page);
                }
            });

            // Refresh button handlers
            $(document).on('click', '[data-action="refresh"]', function(e) {
                e.preventDefault();
                self.refreshCurrentPage();
            });

            // Export button handlers
            $(document).on('click', '[data-action="export"]', function(e) {
                e.preventDefault();
                var format = $(this).data('format') || 'csv';
                self.exportData(format);
            });

            // Settings form handler
            $(document).on('submit', '#woodash-settings-form', function(e) {
                e.preventDefault();
                self.saveSettings($(this));
            });

            // Connection form handler
            $(document).on('submit', '#woodash-connection-form', function(e) {
                e.preventDefault();
                self.handleConnection($(this));
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                if (e.ctrlKey || e.metaKey) {
                    switch(e.keyCode) {
                        case 49: // Ctrl+1 - Dashboard
                            e.preventDefault();
                            self.navigateToPage('dashboard');
                            break;
                        case 50: // Ctrl+2 - Analytics
                            e.preventDefault();
                            self.navigateToPage('analytics');
                            break;
                        case 82: // Ctrl+R - Refresh
                            e.preventDefault();
                            self.refreshCurrentPage();
                            break;
                    }
                }
            });
        },

        // Initialize navigation system
        initNavigation: function() {
            var hash = window.location.hash.substr(1);
            if (hash && this.cache.$pages.filter('[data-page="' + hash + '"]').length) {
                this.navigateToPage(hash);
            } else {
                this.navigateToPage('dashboard');
            }

            // Handle browser back/forward
            var self = this;
            $(window).on('hashchange', function() {
                var newHash = window.location.hash.substr(1);
                if (newHash !== self.state.currentPage) {
                    self.navigateToPage(newHash || 'dashboard');
                }
            });
        },

        // Navigate to a specific page
        navigateToPage: function(page) {
            if (this.state.isLoading) {
                return;
            }

            this.log('Navigating to page: ' + page);

            // Update active navigation
            this.cache.$navLinks.removeClass('active');
            this.cache.$navLinks.filter('[data-page="' + page + '"]').addClass('active');

            // Hide all pages and show target page
            this.cache.$pages.removeClass('active');
            var $targetPage = this.cache.$pages.filter('[data-page="' + page + '"]');
            
            if ($targetPage.length) {
                $targetPage.addClass('active');
                this.state.currentPage = page;
                window.location.hash = page;

                // Load page data if needed
                this.loadPageData(page);
            }
        },

        // Load data for specific page
        loadPageData: function(page) {
            switch(page) {
                case 'dashboard':
                    this.loadDashboardData();
                    break;
                case 'analytics':
                    this.loadAnalyticsData();
                    break;
                case 'products':
                    this.loadProductsData();
                    break;
                case 'orders':
                    this.loadOrdersData();
                    break;
                case 'customers':
                    this.loadCustomersData();
                    break;
                case 'inventory':
                    this.loadInventoryData();
                    break;
                case 'reports':
                    this.loadReportsData();
                    break;
            }
        },

        // Initialize dashboard
        initDashboard: function() {
            this.loadDashboardData();
            this.startAutoRefresh();
        },

        // Load dashboard data
        loadDashboardData: function() {
            this.showLoading('#dashboard-metrics');
            
            this.apiRequest('dashboard/metrics', {
                success: function(data) {
                    this.updateDashboardMetrics(data);
                }.bind(this),
                error: function() {
                    this.showError('#dashboard-metrics', 'Failed to load dashboard data');
                }.bind(this)
            });
        },

        // Update dashboard metrics
        updateDashboardMetrics: function(data) {
            $('#total-sales .metric-value').text(this.formatCurrency(data.total_sales || 0));
            $('#total-orders .metric-value').text(this.formatNumber(data.total_orders || 0));
            $('#avg-order-value .metric-value').text(this.formatCurrency(data.avg_order_value || 0));
            $('#total-customers .metric-value').text(this.formatNumber(data.total_customers || 0));

            // Update change indicators
            this.updateChangeIndicator('#total-sales .metric-change', data.sales_change);
            this.updateChangeIndicator('#total-orders .metric-change', data.orders_change);
            this.updateChangeIndicator('#avg-order-value .metric-change', data.aov_change);
            this.updateChangeIndicator('#total-customers .metric-change', data.customers_change);

            this.hideLoading('#dashboard-metrics');
        },

        // Load analytics data
        loadAnalyticsData: function() {
            if ($('#analytics-chart').length) {
                this.loadChart('analytics');
            }
        },

        // Load chart data
        loadChart: function(type) {
            var $chartContainer = $('#' + type + '-chart');
            this.showLoading($chartContainer.parent());

            this.apiRequest('analytics/chart', {
                data: { type: type },
                success: function(data) {
                    this.renderChart($chartContainer[0], data);
                    this.hideLoading($chartContainer.parent());
                }.bind(this),
                error: function() {
                    this.showError($chartContainer.parent(), 'Failed to load chart data');
                }.bind(this)
            });
        },

        // Render chart using Chart.js
        renderChart: function(canvas, data) {
            if (!window.Chart) {
                console.error('Chart.js not loaded');
                return;
            }

            var ctx = canvas.getContext('2d');
            
            // Destroy existing chart if it exists
            if (canvas.chart) {
                canvas.chart.destroy();
            }

            canvas.chart = new Chart(ctx, {
                type: data.type || 'line',
                data: data.data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return data.format === 'currency' ? 
                                        WoodashAdmin.formatCurrency(value) : 
                                        WoodashAdmin.formatNumber(value);
                                }
                            }
                        }
                    }
                }
            });
        },

        // Handle API requests
        apiRequest: function(endpoint, options) {
            var self = this;
            var settings = $.extend({
                method: 'GET',
                data: {},
                success: function() {},
                error: function() {}
            }, options);

            // Use REST API if available, fallback to AJAX
            if (this.config.restUrl) {
                $.ajax({
                    url: this.config.restUrl + endpoint,
                    method: settings.method,
                    data: settings.data,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', self.config.restNonce);
                    },
                    success: settings.success,
                    error: settings.error
                });
            } else {
                // Fallback to wp-admin AJAX
                $.post(this.config.ajaxurl, {
                    action: 'woodash_' + endpoint.replace('/', '_'),
                    nonce: this.config.nonce,
                    data: settings.data
                }, settings.success).fail(settings.error);
            }
        },

        // Export data
        exportData: function(format) {
            var page = this.state.currentPage;
            var filename = 'woodash-' + page + '-' + this.getCurrentDate() + '.' + format;

            this.apiRequest('export/' + page, {
                data: { format: format },
                success: function(data) {
                    this.downloadFile(data, filename, format);
                }.bind(this),
                error: function() {
                    this.showNotification('Export failed', 'error');
                }.bind(this)
            });
        },

        // Download file
        downloadFile: function(data, filename, format) {
            var blob = new Blob([data], { 
                type: format === 'csv' ? 'text/csv' : 'application/json' 
            });
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        },

        // Save settings
        saveSettings: function($form) {
            var formData = $form.serialize();
            
            this.showLoading($form);
            
            this.apiRequest('settings/save', {
                method: 'POST',
                data: formData,
                success: function() {
                    this.showNotification('Settings saved successfully', 'success');
                    this.hideLoading($form);
                }.bind(this),
                error: function() {
                    this.showNotification('Failed to save settings', 'error');
                    this.hideLoading($form);
                }.bind(this)
            });
        },

        // Handle connection
        handleConnection: function($form) {
            var formData = $form.serialize();
            
            this.showLoading($form);
            
            this.apiRequest('connection/connect', {
                method: 'POST',
                data: formData,
                success: function(data) {
                    if (data.success) {
                        this.showNotification('Connected successfully', 'success');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        this.showNotification(data.message || 'Connection failed', 'error');
                    }
                    this.hideLoading($form);
                }.bind(this),
                error: function() {
                    this.showNotification('Connection failed', 'error');
                    this.hideLoading($form);
                }.bind(this)
            });
        },

        // Auto refresh functionality
        startAutoRefresh: function() {
            if (this.state.refreshInterval) {
                clearInterval(this.state.refreshInterval);
            }

            this.state.refreshInterval = setInterval(function() {
                if (this.state.currentPage === 'dashboard') {
                    this.loadDashboardData();
                }
            }.bind(this), 60000); // Refresh every minute
        },

        // Refresh current page
        refreshCurrentPage: function() {
            this.loadPageData(this.state.currentPage);
            this.showNotification('Data refreshed', 'info');
        },

        // Utility functions
        showLoading: function(selector) {
            var $element = $(selector);
            if (!$element.find('.woodash-spinner').length) {
                $element.prepend('<div class="woodash-loading"><span class="woodash-spinner"></span>Loading...</div>');
            }
        },

        hideLoading: function(selector) {
            $(selector).find('.woodash-loading').remove();
        },

        showError: function(selector, message) {
            $(selector).html('<div class="woodash-alert woodash-alert-danger">' + message + '</div>');
        },

        showNotification: function(message, type) {
            var $notification = $('<div class="woodash-notification woodash-notification-' + type + '">' + message + '</div>');
            $('body').append($notification);
            
            setTimeout(function() {
                $notification.fadeOut(function() {
                    $notification.remove();
                });
            }, 3000);
        },

        updateChangeIndicator: function(selector, change) {
            var $element = $(selector);
            if (change > 0) {
                $element.removeClass('negative').addClass('positive').text('+' + change + '%');
            } else if (change < 0) {
                $element.removeClass('positive').addClass('negative').text(change + '%');
            } else {
                $element.removeClass('positive negative').text('0%');
            }
        },

        formatCurrency: function(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount);
        },

        formatNumber: function(number) {
            if (number >= 1000000) {
                return (number / 1000000).toFixed(1) + 'M';
            } else if (number >= 1000) {
                return (number / 1000).toFixed(1) + 'K';
            } else {
                return number.toString();
            }
        },

        getCurrentDate: function() {
            return new Date().toISOString().split('T')[0];
        },

        log: function(message, data) {
            if (this.config.debug && console) {
                console.log('[WooDash] ' + message, data || '');
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        WoodashAdmin.init();
    });

})(jQuery);
