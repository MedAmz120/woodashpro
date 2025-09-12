/**
 * WooDash Pro Frontend JavaScript
 * 
 * Handles frontend widget interactions and public-facing functionality
 */

(function($) {
    'use strict';

    // Global WooDash Frontend object
    window.WoodashFrontend = {
        // Configuration
        config: {
            ajaxurl: woodash_frontend_vars.ajaxurl || '',
            nonce: woodash_frontend_vars.nonce || '',
            debug: woodash_frontend_vars.debug || false
        },

        // Initialize frontend functionality
        init: function() {
            this.initWidgets();
            this.bindEvents();
            this.log('WooDash Frontend initialized');
        },

        // Initialize all widgets
        initWidgets: function() {
            this.initSalesStats();
            this.initProductShowcase();
            this.initOrderTracker();
            this.initNewsletterSignup();
            this.initTestimonials();
        },

        // Bind event listeners
        bindEvents: function() {
            var self = this;

            // Order tracker form
            $(document).on('submit', '.woodash-order-tracker form', function(e) {
                e.preventDefault();
                var orderNumber = $(this).find('input[type="text"]').val();
                self.trackOrder(orderNumber, $(this).closest('.woodash-order-tracker'));
            });

            // Newsletter signup form
            $(document).on('submit', '.woodash-newsletter-form', function(e) {
                e.preventDefault();
                var email = $(this).find('input[type="email"]').val();
                self.subscribeNewsletter(email, $(this));
            });

            // Product card clicks
            $(document).on('click', '.woodash-product-card', function(e) {
                var productUrl = $(this).data('url');
                if (productUrl) {
                    window.open(productUrl, '_blank');
                }
            });

            // Refresh widget data
            $(document).on('click', '[data-widget-refresh]', function(e) {
                e.preventDefault();
                var widgetType = $(this).data('widget-refresh');
                self.refreshWidget(widgetType);
            });
        },

        // Initialize sales stats widget
        initSalesStats: function() {
            var $widget = $('.woodash-sales-stats');
            if ($widget.length) {
                this.loadSalesStats($widget);
            }
        },

        // Load sales statistics
        loadSalesStats: function($widget) {
            this.showLoading($widget);

            $.post(this.config.ajaxurl, {
                action: 'woodash_get_sales_stats',
                nonce: this.config.nonce
            }, function(response) {
                if (response.success) {
                    this.updateSalesStats($widget, response.data);
                } else {
                    this.showError($widget, 'Failed to load sales data');
                }
                this.hideLoading($widget);
            }.bind(this)).fail(function() {
                this.showError($widget, 'Failed to load sales data');
                this.hideLoading($widget);
            }.bind(this));
        },

        // Update sales stats display
        updateSalesStats: function($widget, data) {
            $widget.find('[data-stat="total-sales"] .woodash-stat-value').text(this.formatCurrency(data.total_sales || 0));
            $widget.find('[data-stat="total-orders"] .woodash-stat-value').text(this.formatNumber(data.total_orders || 0));
            $widget.find('[data-stat="avg-order"] .woodash-stat-value').text(this.formatCurrency(data.avg_order_value || 0));
            $widget.find('[data-stat="customers"] .woodash-stat-value').text(this.formatNumber(data.total_customers || 0));
        },

        // Initialize product showcase
        initProductShowcase: function() {
            var $widget = $('.woodash-products-grid');
            if ($widget.length) {
                this.loadFeaturedProducts($widget);
            }
        },

        // Load featured products
        loadFeaturedProducts: function($widget) {
            this.showLoading($widget);

            $.post(this.config.ajaxurl, {
                action: 'woodash_get_featured_products',
                nonce: this.config.nonce,
                limit: $widget.data('limit') || 4
            }, function(response) {
                if (response.success) {
                    this.renderProducts($widget, response.data);
                } else {
                    this.showError($widget, 'Failed to load products');
                }
                this.hideLoading($widget);
            }.bind(this)).fail(function() {
                this.showError($widget, 'Failed to load products');
                this.hideLoading($widget);
            }.bind(this));
        },

        // Render products
        renderProducts: function($widget, products) {
            var html = '';
            
            products.forEach(function(product) {
                html += '<div class="woodash-product-card" data-url="' + product.url + '">';
                html += '<img src="' + product.image + '" alt="' + product.name + '" class="woodash-product-image">';
                html += '<div class="woodash-product-title">' + product.name + '</div>';
                html += '<div class="woodash-product-price">' + this.formatCurrency(product.price) + '</div>';
                html += '</div>';
            }.bind(this));

            $widget.html(html);
        },

        // Initialize order tracker
        initOrderTracker: function() {
            $('.woodash-order-tracker').each(function() {
                // Widget is ready for user interaction
            });
        },

        // Track order
        trackOrder: function(orderNumber, $widget) {
            if (!orderNumber.trim()) {
                this.showMessage($widget, 'Please enter an order number', 'error');
                return;
            }

            var $status = $widget.find('.woodash-order-status');
            this.showLoading($status);

            $.post(this.config.ajaxurl, {
                action: 'woodash_track_order',
                nonce: this.config.nonce,
                order_number: orderNumber
            }, function(response) {
                if (response.success) {
                    this.displayOrderStatus($status, response.data);
                } else {
                    this.showMessage($widget, response.data.message || 'Order not found', 'error');
                }
                this.hideLoading($status);
            }.bind(this)).fail(function() {
                this.showMessage($widget, 'Failed to track order', 'error');
                this.hideLoading($status);
            }.bind(this));
        },

        // Display order status
        displayOrderStatus: function($status, orderData) {
            var html = '<h4>Order #' + orderData.number + '</h4>';
            html += '<p><strong>Status:</strong> ' + orderData.status + '</p>';
            html += '<p><strong>Date:</strong> ' + orderData.date + '</p>';
            html += '<p><strong>Total:</strong> ' + this.formatCurrency(orderData.total) + '</p>';
            
            if (orderData.tracking_number) {
                html += '<p><strong>Tracking:</strong> ' + orderData.tracking_number + '</p>';
            }

            $status.html(html).addClass('show');
        },

        // Initialize newsletter signup
        initNewsletterSignup: function() {
            $('.woodash-newsletter').each(function() {
                // Widget is ready for user interaction
            });
        },

        // Subscribe to newsletter
        subscribeNewsletter: function(email, $form) {
            if (!this.isValidEmail(email)) {
                this.showMessage($form.closest('.woodash-newsletter'), 'Please enter a valid email address', 'error');
                return;
            }

            var $button = $form.find('button');
            var originalText = $button.text();
            
            $button.text('Subscribing...').prop('disabled', true);

            $.post(this.config.ajaxurl, {
                action: 'woodash_newsletter_subscribe',
                nonce: this.config.nonce,
                email: email
            }, function(response) {
                if (response.success) {
                    this.showMessage($form.closest('.woodash-newsletter'), 'Successfully subscribed!', 'success');
                    $form[0].reset();
                } else {
                    this.showMessage($form.closest('.woodash-newsletter'), response.data.message || 'Subscription failed', 'error');
                }
                
                $button.text(originalText).prop('disabled', false);
            }.bind(this)).fail(function() {
                this.showMessage($form.closest('.woodash-newsletter'), 'Subscription failed', 'error');
                $button.text(originalText).prop('disabled', false);
            }.bind(this));
        },

        // Initialize testimonials
        initTestimonials: function() {
            var $testimonials = $('.woodash-testimonials');
            if ($testimonials.length) {
                this.loadTestimonials($testimonials);
            }
        },

        // Load testimonials
        loadTestimonials: function($widget) {
            $.post(this.config.ajaxurl, {
                action: 'woodash_get_testimonials',
                nonce: this.config.nonce,
                limit: $widget.data('limit') || 3
            }, function(response) {
                if (response.success) {
                    this.renderTestimonials($widget, response.data);
                }
            }.bind(this));
        },

        // Render testimonials
        renderTestimonials: function($widget, testimonials) {
            var html = '';
            
            testimonials.forEach(function(testimonial) {
                html += '<div class="woodash-testimonial">';
                html += '<div class="woodash-testimonial-text">"' + testimonial.content + '"</div>';
                html += '<div class="woodash-testimonial-author">';
                html += testimonial.author;
                if (testimonial.rating) {
                    html += '<span class="woodash-testimonial-rating">' + this.renderStars(testimonial.rating) + '</span>';
                }
                html += '</div>';
                html += '</div>';
            }.bind(this));

            $widget.html(html);
        },

        // Render star rating
        renderStars: function(rating) {
            var stars = '';
            for (var i = 1; i <= 5; i++) {
                stars += i <= rating ? '★' : '☆';
            }
            return stars;
        },

        // Refresh widget data
        refreshWidget: function(widgetType) {
            switch(widgetType) {
                case 'sales-stats':
                    this.initSalesStats();
                    break;
                case 'products':
                    this.initProductShowcase();
                    break;
                case 'testimonials':
                    this.initTestimonials();
                    break;
            }
        },

        // Utility functions
        showLoading: function($element) {
            if (!$element.find('.woodash-spinner').length) {
                $element.prepend('<div class="woodash-loading"><span class="woodash-spinner"></span>Loading...</div>');
            }
        },

        hideLoading: function($element) {
            $element.find('.woodash-loading').remove();
        },

        showError: function($element, message) {
            $element.html('<div class="woodash-error">' + message + '</div>');
        },

        showMessage: function($element, message, type) {
            var className = 'woodash-' + (type || 'info');
            var $message = $('<div class="' + className + '">' + message + '</div>');
            
            $element.prepend($message);
            
            setTimeout(function() {
                $message.fadeOut(function() {
                    $message.remove();
                });
            }, 5000);
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

        isValidEmail: function(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },

        log: function(message, data) {
            if (this.config.debug && console) {
                console.log('[WooDash Frontend] ' + message, data || '');
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        WoodashFrontend.init();
    });

})(jQuery);
