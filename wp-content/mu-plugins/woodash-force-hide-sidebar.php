<?php
/**
 * WooDash Pro - Force Hide WordPress Sidebar & Admin Bar
 * Must-Use Plugin to aggressively hide WordPress sidebar and admin bar on WooDash Pro pages only
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Check if we're on a WooDash Pro page
 */
function is_woodash_pro_page() {
    global $pagenow;
    
    // Check if we're on a WooDash Pro admin page
    if (is_admin()) {
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        return strpos($page, 'woodash-pro') !== false;
    }
    
    return false;
}

/**
 * NUCLEAR OPTION - Force hide WordPress sidebar and admin bar on WooDash Pro pages only
 */
function woodash_nuclear_hide_elements() {
    // Check if WooDash Pro is connected
    $is_connected = get_option('woodash_connected', false);
    if (!$is_connected) {
        return;
    }
    
    // Only hide on WooDash Pro pages or if explicitly requested
    if (!is_woodash_pro_page() && !is_admin()) {
        // On frontend, always hide admin bar if connected
        ?>
        <style type="text/css" id="woodash-frontend-hide">
            #wpadminbar {
                display: none !important;
                visibility: hidden !important;
            }
            html { padding-top: 0 !important; }
            body { margin-top: 0 !important; padding-top: 0 !important; }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var adminbar = document.getElementById('wpadminbar');
                if (adminbar) { adminbar.remove(); }
                document.body.classList.remove('admin-bar');
                document.documentElement.classList.remove('wp-toolbar');
            });
        </script>
        <?php
        return;
    }
    
    // Full nuclear option only on WooDash Pro pages
    if (!is_woodash_pro_page()) {
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
        
        /* NUCLEAR OPTION - Force hide WordPress admin bar */
        html body #wpadminbar,
        html body.wp-admin #wpadminbar,
        html body.admin-bar #wpadminbar,
        body #wpadminbar,
        #wpadminbar {
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
        
        /* Remove admin bar spacing from top */
        html.wp-toolbar,
        html body.wp-toolbar {
            padding-top: 0 !important;
        }
        
        body.admin-bar,
        html body.admin-bar {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Force full width layout */
        html body.wp-admin #wpcontent,
        html body #wpcontent,
        #wpcontent {
            margin-left: 0 !important;
            padding-left: 0 !important;
            padding-top: 0 !important;
        }
        
        html body.wp-admin #wpbody,
        html body #wpbody,
        #wpbody {
            margin-left: 0 !important;
            padding-left: 0 !important;
            padding-top: 0 !important;
        }
        
        html body.wp-admin #wpwrap,
        html body #wpwrap,
        #wpwrap {
            padding-left: 0 !important;
            margin-left: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Hide collapse menu button */
        #collapse-menu {
            display: none !important;
        }
        
        /* Hide menu separators */
        .wp-menu-separator {
            display: none !important;
        }
        
        /* Remove any admin bar related margins and padding */
        .admin-bar #wphead,
        .admin-bar #wpcontent,
        .admin-bar #wpbody {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
    </style>
    
    <script type="text/javascript">
        // IMMEDIATE sidebar and admin bar removal - runs on WooDash Pro pages only
        (function() {
            console.log('WooDash Pro: NUCLEAR hiding activated on WooDash Pro page');
            
            function nukeElements() {
                // Remove all sidebar and admin bar elements immediately
                var elementsToNuke = [
                    '#adminmenumain',
                    '#adminmenuback', 
                    '#adminmenuwrap',
                    '.wp-menu-separator',
                    '#collapse-menu',
                    '#wpadminbar'
                ];
                
                elementsToNuke.forEach(function(selector) {
                    var elements = document.querySelectorAll(selector);
                    elements.forEach(function(element) {
                        if (element) {
                            element.remove();
                            console.log('WooDash Pro: NUKED element:', selector);
                        }
                    });
                });
                
                // Force content to full width and remove top spacing
                var contentElements = ['#wpcontent', '#wpbody', '#wpwrap'];
                contentElements.forEach(function(selector) {
                    var element = document.querySelector(selector);
                    if (element) {
                        element.style.marginLeft = '0';
                        element.style.paddingLeft = '0';
                        element.style.paddingTop = '0';
                        element.style.marginTop = '0';
                    }
                });
                
                // Remove admin-bar class from body
                if (document.body) {
                    document.body.classList.remove('admin-bar');
                    document.body.style.marginTop = '0';
                    document.body.style.paddingTop = '0';
                }
                
                // Remove wp-toolbar class from html
                if (document.documentElement) {
                    document.documentElement.classList.remove('wp-toolbar');
                    document.documentElement.style.paddingTop = '0';
                }
            }
            
            // Run immediately if DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', nukeElements);
            } else {
                nukeElements();
            }
            
            // Run again after short delays
            setTimeout(nukeElements, 50);
            setTimeout(nukeElements, 100);
            setTimeout(nukeElements, 250);
            setTimeout(nukeElements, 500);
            setTimeout(nukeElements, 1000);
        })();
    </script>
    <?php
}

/**
 * Early hiding with CSS
 */
function woodash_early_hide_elements() {
    // Check if WooDash Pro is connected
    $is_connected = get_option('woodash_connected', false);
    if (!$is_connected) {
        return;
    }
    
    if (is_woodash_pro_page()) {
        // Full hiding on WooDash Pro pages
        echo '<style type="text/css" id="woodash-early-hide">
            #adminmenumain, #adminmenuback, #adminmenuwrap, #wpadminbar { display: none !important; visibility: hidden !important; }
            #wpcontent, #wpbody { margin-left: 0 !important; padding-left: 0 !important; padding-top: 0 !important; }
            body { margin-top: 0 !important; padding-top: 0 !important; }
            html { padding-top: 0 !important; }
        </style>';
    } else {
        // Only hide admin bar on other pages
        echo '<style type="text/css" id="woodash-adminbar-hide">
            #wpadminbar { display: none !important; visibility: hidden !important; }
            body { margin-top: 0 !important; padding-top: 0 !important; }
            html { padding-top: 0 !important; }
        </style>';
    }
}

/**
 * Final cleanup
 */
function woodash_final_cleanup() {
    // Check if WooDash Pro is connected
    $is_connected = get_option('woodash_connected', false);
    if (!$is_connected) {
        return;
    }
    
    ?>
    <script type="text/javascript">
        // Final cleanup
        (function() {
            var isWooDashPage = <?php echo is_woodash_pro_page() ? 'true' : 'false'; ?>;
            
            console.log('WooDash Pro: Final cleanup', isWooDashPage ? 'on WooDash Pro page' : 'on other page');
            
            function finalCleanup() {
                if (isWooDashPage) {
                    // Full cleanup on WooDash Pro pages
                    var remainingElements = document.querySelectorAll('#adminmenumain, #adminmenuback, #adminmenuwrap, .wp-menu-separator, #collapse-menu, #wpadminbar');
                    remainingElements.forEach(function(element) {
                        if (element) {
                            element.remove();
                            console.log('WooDash Pro: Final cleanup removed element');
                        }
                    });
                    
                    // Ensure content is full width
                    var contentArea = document.getElementById('wpcontent');
                    if (contentArea) {
                        contentArea.style.marginLeft = '0';
                        contentArea.style.paddingLeft = '0';
                        contentArea.style.paddingTop = '0';
                        contentArea.style.marginTop = '0';
                    }
                    
                    var wpBody = document.getElementById('wpbody');
                    if (wpBody) {
                        wpBody.style.marginLeft = '0';
                        wpBody.style.paddingLeft = '0';
                        wpBody.style.paddingTop = '0';
                        wpBody.style.marginTop = '0';
                    }
                } else {
                    // Only remove admin bar on other pages
                    var adminbar = document.getElementById('wpadminbar');
                    if (adminbar) {
                        adminbar.remove();
                    }
                }
                
                // Always remove admin-bar class
                if (document.body) {
                    document.body.classList.remove('admin-bar');
                    document.body.style.marginTop = '0';
                    document.body.style.paddingTop = '0';
                }
                
                if (document.documentElement) {
                    document.documentElement.classList.remove('wp-toolbar');
                    document.documentElement.style.paddingTop = '0';
                }
            }
            
            // Run cleanup
            finalCleanup();
            
            // Set up monitoring only on WooDash Pro pages
            if (isWooDashPage) {
                var cleanupInterval = setInterval(function() {
                    var sidebar = document.getElementById('adminmenumain');
                    var adminbar = document.getElementById('wpadminbar');
                    if (sidebar || adminbar) {
                        console.log('WooDash Pro: Elements detected, removing...');
                        finalCleanup();
                    }
                }, 1000);
                
                setTimeout(function() {
                    clearInterval(cleanupInterval);
                    console.log('WooDash Pro: Stopped monitoring');
                }, 30000);
            }
        })();
    </script>
    <?php
}

// Hook the functions appropriately
add_action('wp_head', 'woodash_nuclear_hide_elements', 1);
add_action('admin_head', 'woodash_nuclear_hide_elements', 1);
add_action('wp_print_styles', 'woodash_early_hide_elements', 1);
add_action('admin_print_styles', 'woodash_early_hide_elements', 1);
add_action('wp_footer', 'woodash_final_cleanup', 999);
add_action('admin_footer', 'woodash_final_cleanup', 999);

// Hook to init for early execution
add_action('init', function() {
    if (get_option('woodash_connected', false)) {
        add_action('wp_head', 'woodash_nuclear_hide_elements', 1);
        add_action('admin_head', 'woodash_nuclear_hide_elements', 1);
        add_action('wp_print_styles', 'woodash_early_hide_elements', 1);
        add_action('admin_print_styles', 'woodash_early_hide_elements', 1);
        add_action('wp_footer', 'woodash_final_cleanup', 999);
        add_action('admin_footer', 'woodash_final_cleanup', 999);
    }
});