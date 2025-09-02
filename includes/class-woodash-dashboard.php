<?php
/**
 * Dashboard class for WoodDash Pro
 *
 * @package WoodashPro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

class Woodash_Dashboard {
    
    public function __construct() {
        add_action('admin_head', array($this, 'hide_admin_elements'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_toggle_scripts'));
    }
    
    /**
     * Hide admin elements when on plugin pages
     */
    public function hide_admin_elements() {
        if (!woodash_is_plugin_page()) {
            return;
        }
        
        if (isset($_GET['page']) && $_GET['page'] === 'woodash-pro') {
            add_filter('show_admin_bar', '__return_false');
            ?>
            <style>
                #wpadminbar { display: none !important; }
                html { margin-top: 0 !important; }
                #wpcontent { margin-left: 0 !important; padding-left: 0 !important; }
                #adminmenumain { display: none !important; }
                #wpfooter { display: none !important; }
            </style>
            <?php
        }
    }
    
    /**
     * Enqueue scripts for dashboard toggles
     */
    public function enqueue_toggle_scripts() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        wp_add_inline_script('jquery', $this->get_toggle_script());
    }
    
    /**
     * Get JavaScript for toggle functionality
     *
     * @return string
     */
    private function get_toggle_script() {
        $script = '
        jQuery(document).ready(function($) {
            // Toggle WooDash Pro dashboard
            $("#wp-admin-bar-woodash-toggle-dashboard").on("click", function(e) {
                e.preventDefault();
                
                var $this = $(this);
                var $icon = $this.find(".dashicons");
                var $label = $this.find(".ab-label");
                
                // Show loading state
                $icon.removeClass("dashicons-visibility dashicons-hidden").addClass("dashicons-update");
                $label.text("' . esc_js(__('Toggling...', 'woodash-pro')) . '");
                
                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: {
                        action: "woodash_toggle_dashboard",
                        nonce: "' . wp_create_nonce('woodash_nonce') . '"
                    },
                    success: function(response) {
                        if (response.success) {
                            var hidden = response.data.hidden;
                            if (hidden) {
                                $icon.removeClass("dashicons-update").addClass("dashicons-visibility");
                                $label.text("' . esc_js(__('Show WooDash Pro', 'woodash-pro')) . '");
                            } else {
                                $icon.removeClass("dashicons-update").addClass("dashicons-hidden");
                                $label.text("' . esc_js(__('Hide WooDash Pro', 'woodash-pro')) . '");
                            }
                            
                            // Reload page to reflect menu changes
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    },
                    error: function() {
                        $icon.removeClass("dashicons-update").addClass("dashicons-warning");
                        $label.text("' . esc_js(__('Error', 'woodash-pro')) . '");
                        
                        setTimeout(function() {
                            var hidden = ' . (woodash_get_option('hide_dashboard', false) ? 'true' : 'false') . ';
                            if (hidden) {
                                $icon.removeClass("dashicons-warning").addClass("dashicons-visibility");
                                $label.text("' . esc_js(__('Show WooDash Pro', 'woodash-pro')) . '");
                            } else {
                                $icon.removeClass("dashicons-warning").addClass("dashicons-hidden");
                                $label.text("' . esc_js(__('Hide WooDash Pro', 'woodash-pro')) . '");
                            }
                        }, 2000);
                    }
                });
            });
            
            // Toggle WordPress dashboard
            $("#wp-admin-bar-woodash-toggle-wp-dashboard").on("click", function(e) {
                e.preventDefault();
                
                var $this = $(this);
                var $icon = $this.find(".dashicons");
                var $label = $this.find(".ab-label");
                
                // Show loading state
                $icon.removeClass("dashicons-wordpress dashicons-wordpress-alt").addClass("dashicons-update");
                $label.text("' . esc_js(__('Toggling...', 'woodash-pro')) . '");
                
                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: {
                        action: "woodash_toggle_wp_dashboard",
                        nonce: "' . wp_create_nonce('woodash_nonce') . '"
                    },
                    success: function(response) {
                        if (response.success) {
                            var hidden = response.data.hidden;
                            if (hidden) {
                                $icon.removeClass("dashicons-update").addClass("dashicons-wordpress");
                                $label.text("' . esc_js(__('Show WordPress Menu', 'woodash-pro')) . '");
                            } else {
                                $icon.removeClass("dashicons-update").addClass("dashicons-wordpress-alt");
                                $label.text("' . esc_js(__('Hide WordPress Menu', 'woodash-pro')) . '");
                            }
                            
                            // Reload page to reflect menu changes
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    },
                    error: function() {
                        $icon.removeClass("dashicons-update").addClass("dashicons-warning");
                        $label.text("' . esc_js(__('Error', 'woodash-pro')) . '");
                        
                        setTimeout(function() {
                            var hidden = ' . (get_option('woodash_hide_wp_dashboard', false) ? 'true' : 'false') . ';
                            if (hidden) {
                                $icon.removeClass("dashicons-warning").addClass("dashicons-wordpress");
                                $label.text("' . esc_js(__('Show WordPress Menu', 'woodash-pro')) . '");
                            } else {
                                $icon.removeClass("dashicons-warning").addClass("dashicons-wordpress-alt");
                                $label.text("' . esc_js(__('Hide WordPress Menu', 'woodash-pro')) . '");
                            }
                        }, 2000);
                    }
                });
            });
        });
        ';
        
        return $script;
    }
    
    /**
     * Check if dashboard should be hidden
     *
     * @return bool
     */
    public function is_dashboard_hidden() {
        return woodash_get_option('hide_dashboard', false);
    }
    
    /**
     * Check if WordPress dashboard should be hidden
     *
     * @return bool
     */
    public function is_wp_dashboard_hidden() {
        return get_option('woodash_hide_wp_dashboard', false);
    }
}
