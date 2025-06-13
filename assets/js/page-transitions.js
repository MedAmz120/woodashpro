// First check if jQuery is available
if (typeof jQuery === 'undefined') {
    console.error('jQuery is not loaded!');
} else {
    console.log('jQuery is loaded, version:', jQuery.fn.jquery);
}

jQuery(document).ready(function($) {
    console.log('Document ready');
    
    // Test if we can find the links
    var links = $('.woodash-nav-link');
    console.log('Found links:', links.length);
    
    // Test if we can find the main content area
    var mainContent = $('.woodash-main');
    console.log('Main content area found:', mainContent.length > 0);
    
    // Test if woodashData is available
    console.log('woodashData available:', typeof woodashData !== 'undefined');
    if (typeof woodashData !== 'undefined') {
        console.log('woodashData:', woodashData);
    }
    
    // Test if ajaxurl is available
    console.log('ajaxurl available:', typeof ajaxurl !== 'undefined');
    if (typeof ajaxurl !== 'undefined') {
        console.log('ajaxurl:', ajaxurl);
    }
    
    // Add click handler directly to links
    $('.woodash-nav-link').on('click', function(e) {
        console.log('Link clicked');
        e.preventDefault();
        
        var href = $(this).attr('href');
        console.log('Link href:', href);
        
        // Show loading state
        $('body').addClass('loading');
        
        // Make the AJAX request
        $.post(ajaxurl, {
            action: 'woodash_load_page_content',
            page: href.split('page=')[1],
            nonce: woodashData.nonce
        }, function(response) {
            console.log('Response:', response);
            if (response.success) {
                // Update the main content area
                $('.woodash-main').html(response.data);
                window.history.pushState({}, '', href);
                
                // Update active state
                $('.woodash-nav-link').removeClass('active');
                $(this).addClass('active');

                // Trigger content updated event
                document.dispatchEvent(new Event('woodashContentUpdated'));
            }
        })
        .fail(function(xhr, status, error) {
            console.error('AJAX error:', error);
        })
        .always(function() {
            $('body').removeClass('loading');
        });
    });

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        var currentPage = window.location.href.split('page=')[1];
        if (currentPage) {
            $.post(ajaxurl, {
                action: 'woodash_load_page_content',
                page: currentPage,
                nonce: woodashData.nonce
            }, function(response) {
                if (response.success) {
                    $('.woodash-main').html(response.data);
                    // Trigger content updated event
                    document.dispatchEvent(new Event('woodashContentUpdated'));
                }
            });
        }
    });
}); 