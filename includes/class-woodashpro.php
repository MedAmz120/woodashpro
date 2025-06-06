<?php

class WoodashPro {
    public function enqueue_scripts() {
        // Enqueue existing scripts
        wp_enqueue_script('woodashpro-dashboard', plugin_dir_url(__FILE__) . '../assets/js/dashboard.js', array('jquery'), $this->version, true);
        wp_enqueue_script('woodashpro-coupon-analytics', plugin_dir_url(__FILE__) . '../assets/js/coupon-analytics.js', array('jquery'), $this->version, true);
        
        // Localize script with necessary data
        wp_localize_script('woodashpro-coupon-analytics', 'woodash_analytics', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('woodash_analytics_nonce')
        ));
    }
} 