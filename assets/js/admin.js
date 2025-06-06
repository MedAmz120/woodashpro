jQuery(document).ready(function($) {
    'use strict';

    // Handle form submissions
    $('.woodash-form').on('submit', function(e) {
        var $form = $(this);
        var $submitButton = $form.find('button[type="submit"]');
        
        // Add loading state
        $submitButton.addClass('woodash-loading');
        
        // You can add AJAX form submission here if needed
    });

    // Handle password visibility toggle
    $('.woodash-toggle-password').on('click', function(e) {
        e.preventDefault();
        var $input = $(this).prev('input');
        var $icon = $(this).find('i');
        
        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $input.attr('type', 'password');
            $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Handle notice dismissal
    $('.woodash-notice .notice-dismiss').on('click', function() {
        var $notice = $(this).closest('.woodash-notice');
        $notice.fadeOut(300, function() {
            $(this).remove();
        });
    });

    // Auto-dismiss notices after 5 seconds
    setTimeout(function() {
        $('.woodash-notice').fadeOut(300, function() {
            $(this).remove();
        });
    }, 5000);

    // Handle tab switching
    $('.woodash-tab-link').on('click', function(e) {
        e.preventDefault();
        var target = $(this).data('tab');
        
        // Update active states
        $('.woodash-tab-link').removeClass('active');
        $(this).addClass('active');
        
        // Show target content
        $('.woodash-tab-content').hide();
        $('#' + target).show();
    });

    // Initialize tooltips
    $('[data-tooltip]').each(function() {
        var $element = $(this);
        var tooltip = $element.data('tooltip');
        
        $element.attr('title', tooltip);
    });

    // Handle responsive menu toggle
    $('.woodash-menu-toggle').on('click', function() {
        $('.woodash-sidebar').toggleClass('active');
    });

    // Close sidebar when clicking outside on mobile
    $(document).on('click', function(e) {
        if ($(window).width() < 768) {
            if (!$(e.target).closest('.woodash-sidebar, .woodash-menu-toggle').length) {
                $('.woodash-sidebar').removeClass('active');
            }
        }
    });

    // Product search functionality
    $('.woodash-search-input').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('.woodash-product-card').each(function() {
            const productName = $(this).find('.woodash-product-name').text().toLowerCase();
            const productCategory = $(this).find('.woodash-product-category').text().toLowerCase();
            
            if (productName.includes(searchTerm) || productCategory.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Filter functionality
    $('.woodash-filter-select').on('change', function() {
        const category = $('#category-filter').val();
        const status = $('#status-filter').val();
        const stock = $('#stock-filter').val();

        $('.woodash-product-card').each(function() {
            const productCategory = $(this).data('category');
            const productStatus = $(this).data('status');
            const productStock = $(this).data('stock');

            const categoryMatch = category === 'all' || productCategory === category;
            const statusMatch = status === 'all' || productStatus === status;
            const stockMatch = stock === 'all' || productStock === stock;

            if (categoryMatch && statusMatch && stockMatch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Product card hover effects
    $('.woodash-product-card').hover(
        function() {
            $(this).find('.woodash-product-actions').slideDown(200);
        },
        function() {
            $(this).find('.woodash-product-actions').slideUp(200);
        }
    );

    // Quick stats animation
    function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const value = Math.floor(progress * (end - start) + start);
            $(element).text(value);
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    // Animate stats when they come into view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const endValue = parseInt($(element).data('value'));
                animateValue(element, 0, endValue, 1000);
                observer.unobserve(element);
            }
        });
    });

    $('.woodash-stat-value').each(function() {
        observer.observe(this);
    });

    // Pagination
    $('.woodash-pagination-link').on('click', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        // Here you would typically make an AJAX call to load the next page
        // For now, we'll just update the active state
        $('.woodash-pagination-link').removeClass('active');
        $(this).addClass('active');
    });

    // Add to cart functionality
    $('.woodash-add-to-cart').on('click', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        // Here you would typically make an AJAX call to add the product to cart
        // For now, we'll just show a success message
        const toast = $('<div class="woodash-toast">Product added to cart!</div>');
        $('body').append(toast);
        setTimeout(() => toast.remove(), 3000);
    });

    // Toast notification system
    function showToast(message, type = 'success') {
        const toast = $(`
            <div class="woodash-toast woodash-toast-${type}">
                ${message}
            </div>
        `);
        $('body').append(toast);
        setTimeout(() => {
            toast.addClass('woodash-toast-hide');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Export functionality
    $('.woodash-export-btn').on('click', function() {
        const format = $(this).data('format');
        // Here you would typically make an AJAX call to export the data
        showToast(`Exporting data in ${format} format...`);
    });

    // Bulk actions
    $('.woodash-bulk-action').on('change', function() {
        const action = $(this).val();
        if (action) {
            const selectedProducts = $('.woodash-product-checkbox:checked').length;
            if (selectedProducts > 0) {
                // Here you would typically make an AJAX call to perform the bulk action
                showToast(`Performing ${action} on ${selectedProducts} products...`);
            } else {
                showToast('Please select at least one product', 'warning');
            }
        }
    });
}); 