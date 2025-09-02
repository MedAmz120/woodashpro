<?php
/**
 * Force Hide WordPress Sidebar - Nuclear Option
 * This file contains aggressive sidebar hiding functionality
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * NUCLEAR OPTION - Force hide WordPress sidebar on ALL admin pages
 */
function woodash_nuclear_hide_sidebar() {
    // Check if we're on any admin page
    if (!is_admin()) {
        return;
    }
    
    // Check if WooDash Pro is connected
    $is_connected = get_option('woodash_connected', false);
    if (!$is_connected) {
        return;
    }
    
    ?>
    <style type="text/css" id="woodash-nuclear-hide">
        /* NUCLEAR OPTION - Force hide WordPress sidebar with maximum specificity */
        html body.wp-admin #adminmenumain,
        html body.wp-admin #adminmenuback,
        html body.wp-admin #adminmenuwrap,
        html body #adminmenumain,
        html body #adminmenuback,
        html body #adminmenuwrap,
        #adminmenumain,
        #adminmenuback,
        #adminmenuwrap {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            width: 0 !important;
            height: 0 !important;
            overflow: hidden !important;
            position: absolute !important;
            left: -99999px !important;
            top: -99999px !important;
            z-index: -1 !important;
        }
        
        /* Force full width layout */
        html body.wp-admin #wpcontent,
        html body #wpcontent,
        #wpcontent {
            margin-left: 0 !important;
            padding-left: 0 !important;
        }
        
        html body.wp-admin #wpbody,
        html body #wpbody,
        #wpbody {
            margin-left: 0 !important;
            padding-left: 0 !important;
        }
        
        html body.wp-admin #wpwrap,
        html body #wpwrap,
        #wpwrap {
            padding-left: 0 !important;
            margin-left: 0 !important;
        }
        
        /* Hide collapse menu button */
        #collapse-menu {
            display: none !important;
        }
        
        /* Hide menu separators */
        .wp-menu-separator {
            display: none !important;
        }
    </style>
    
    <script type="text/javascript">
        // IMMEDIATE sidebar removal - runs as soon as possible
        (function() {
            console.log('WooDash Pro: NUCLEAR sidebar hiding activated');
            
            function nukeSidebar() {
                // Remove all sidebar elements immediately
                var sidebarSelectors = [
                    '#adminmenumain',
                    '#adminmenuback', 
                    '#adminmenuwrap',
                    '.wp-menu-separator',
                    '#collapse-menu'
                ];
                
                sidebarSelectors.forEach(function(selector) {
                    var elements = document.querySelectorAll(selector);
                    elements.forEach(function(element) {
                        if (element) {
                            element.remove();
                            console.log('WooDash Pro: NUKED element:', selector);
                        }
                    });
                });
                
                // Force content to full width
                var contentElements = ['#wpcontent', '#wpbody', '#wpwrap'];
                contentElements.forEach(function(selector) {
                    var element = document.querySelector(selector);
                    if (element) {
                        element.style.marginLeft = '0';
                        element.style.paddingLeft = '0';
                    }
                });
            }
            
            // Run immediately if DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', nukeSidebar);
            } else {
                nukeSidebar();
            }
            
            // Run again after short delays
            setTimeout(nukeSidebar, 50);
            setTimeout(nukeSidebar, 100);
            setTimeout(nukeSidebar, 250);
            setTimeout(nukeSidebar, 500);
            setTimeout(nukeSidebar, 1000);
        })();
    </script>
    <?php
}

/**
 * Early sidebar hiding with CSS
 */
function woodash_early_hide_sidebar() {
    // Check if we're on any admin page
    if (!is_admin()) {
        return;
    }
    
    // Check if WooDash Pro is connected
    $is_connected = get_option('woodash_connected', false);
    if (!$is_connected) {
        return;
    }
    
    // Add inline styles with maximum priority
    echo '<style type="text/css" id="woodash-early-hide">
        #adminmenumain, #adminmenuback, #adminmenuwrap { display: none !important; visibility: hidden !important; }
        #wpcontent, #wpbody { margin-left: 0 !important; padding-left: 0 !important; }
    </style>';
}

/**
 * Final cleanup in footer
 */
function woodash_final_sidebar_cleanup() {
    // Check if we're on any admin page
    if (!is_admin()) {
        return;
    }
    
    // Check if WooDash Pro is connected
    $is_connected = get_option('woodash_connected', false);
    if (!$is_connected) {
        return;
    }
    
    ?>
    <script type="text/javascript">
        // Final cleanup - remove any remaining sidebar elements
        (function() {
            console.log('WooDash Pro: Final sidebar cleanup');
            
            function finalCleanup() {
                // Remove any remaining sidebar elements
                var remainingSidebars = document.querySelectorAll('#adminmenumain, #adminmenuback, #adminmenuwrap, .wp-menu-separator, #collapse-menu');
                remainingSidebars.forEach(function(element) {
                    if (element) {
                        element.remove();
                        console.log('WooDash Pro: Final cleanup removed element');
                    }
                });
                
                // Ensure content is still full width
                var contentArea = document.getElementById('wpcontent');
                if (contentArea) {
                    contentArea.style.marginLeft = '0';
                    contentArea.style.paddingLeft = '0';
                }
                
                var wpBody = document.getElementById('wpbody');
                if (wpBody) {
                    wpBody.style.marginLeft = '0';
                    wpBody.style.paddingLeft = '0';
                }
            }
            
            // Run cleanup
            finalCleanup();
            
            // Set up continuous monitoring
            var cleanupInterval = setInterval(function() {
                var sidebar = document.getElementById('adminmenumain');
                if (sidebar) {
                    console.log('WooDash Pro: Sidebar detected, removing...');
                    finalCleanup();
                }
            }, 1000); // Check every second
            
            // Stop monitoring after 30 seconds
            setTimeout(function() {
                clearInterval(cleanupInterval);
                console.log('WooDash Pro: Stopped sidebar monitoring');
            }, 30000);
        })();
    </script>
    <?php
}

// Hook the functions with maximum priority
add_action('admin_head', 'woodash_nuclear_hide_sidebar', 1);
add_action('admin_print_styles', 'woodash_early_hide_sidebar', 1);
add_action('admin_footer', 'woodash_final_sidebar_cleanup', 999);

// Also hook to init for early execution
add_action('init', function() {
    if (is_admin() && get_option('woodash_connected', false)) {
        add_action('admin_head', 'woodash_nuclear_hide_sidebar', 1);
        add_action('admin_print_styles', 'woodash_early_hide_sidebar', 1);
        add_action('admin_footer', 'woodash_final_sidebar_cleanup', 999);
    }
});