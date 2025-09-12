<?php
/**
 * WooDash Pro Admin Class
 * 
 * Handles all admin-related functionality
 * 
 * @package WooDashPro
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class WooDash_Admin {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize admin hooks
     */
    private function init_hooks() {
        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Admin bar and menu customization
        add_action('init', array($this, 'maybe_hide_admin_bar'));
        
        // AJAX handlers for activation
        add_action('wp_ajax_woodashh_generate_keys', array($this, 'handle_generate_keys'));
        add_action('wp_ajax_woodashh_log', array($this, 'handle_debug_log'));
        add_action('wp_ajax_woodash_store_connection', array($this, 'handle_store_connection'));
        add_action('wp_ajax_woodash_logout', array($this, 'handle_logout'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        $is_connected = get_option('woodash_connected');
        
        if ($is_connected) {
            add_menu_page(
                __('WooDash Pro', 'woodashpro'),
                __('WooDash Pro', 'woodashpro'),
                'manage_options',
                'woodash-pro',
                array($this, 'dashboard_page'),
                'dashicons-chart-area',
                56
            );
        } else {
            add_menu_page(
                __('WooDash Pro', 'woodashpro'),
                __('WooDash Pro', 'woodashpro'),
                'manage_options',
                'woodash-pro-activate',
                array($this, 'activation_page'),
                'dashicons-chart-area',
                56
            );
        }
    }
    
    /**
     * Hide admin bar on plugin pages
     */
    public function maybe_hide_admin_bar() {
        if (isset($_GET['page']) && in_array($_GET['page'], array('woodash-pro', 'woodash-pro-activate'))) {
            add_filter('show_admin_bar', '__return_false');
            add_action('admin_head', array($this, 'hide_admin_elements'));
        }
    }
    
    /**
     * Hide admin elements on plugin pages
     */
    public function hide_admin_elements() {
        ?>
        <style>
            #wpadminbar { display: none !important; }
            html { margin-top: 0 !important; }
            #wpcontent { margin-left: 0 !important; padding-left: 0 !important; }
            #adminmenumain { display: none !important; }
            #wpfooter { display: none !important; }
            .woodash-fullscreen {
                position: fixed; 
                top: 0; 
                left: 0; 
                right: 0; 
                bottom: 0;
                z-index: 9999; 
                background: #F8FAFC;
            }
        </style>
        <?php
    }
    
    /**
     * Dashboard page callback
     */
    public function dashboard_page() {
        // Load the dashboard template
        $template_file = WOODASHPRO_TEMPLATES_DIR . 'dashboard.php';
        
        if (file_exists($template_file)) {
            include $template_file;
        } else {
            echo '<div class="notice notice-error"><p>' . 
                 esc_html__('Dashboard template not found.', 'woodashpro') . 
                 '</p></div>';
        }
    }
    
    /**
     * Activation page callback
     */
    public function activation_page() {
        // Load the activation template
        $template_file = WOODASHPRO_TEMPLATES_DIR . 'activation.php';
        
        if (file_exists($template_file)) {
            include $template_file;
        } else {
            $this->render_activation_page();
        }
    }
    
    /**
     * Render activation page (fallback)
     */
    private function render_activation_page() {
        ?>
        <div style="display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #f8f9fa;">
            <div style="max-width: 480px; background: #fff; border-radius: 16px; box-shadow: 0 4px 32px rgba(0,0,0,0.07); padding: 40px 32px; text-align: center; font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', sans-serif;">
                <div style="margin-bottom: 24px;">
                    <img src="https://saas.mohamedamzil.pw/wp-content/uploads/2023/09/WooDash-600-x-180-px-transp.png" alt="WooDash Logo" style="width: 180px; height: auto; margin-bottom: 16px;">
                    <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 8px; background: linear-gradient(90deg, #00CC61, #00b357); -webkit-background-clip: text; -webkit-text-fill-color: transparent; padding: 10px 0;">
                        <?php esc_html_e('Connect WooDash Pro', 'woodashpro'); ?>
                    </h2>
                    <p style="color: #555; font-size: 1.1rem; margin-bottom: 0;">
                        <?php esc_html_e('To use WooDash Pro, please connect your site to your account.', 'woodashpro'); ?><br>
                        <?php esc_html_e('Click the button below to get started.', 'woodashpro'); ?>
                    </p>
                </div>
                <a href="#" id="woodashh-connect-btn" style="display: inline-block; background: #19b677; color: #fff; font-weight: 600; font-size: 1.1rem; padding: 14px 36px; border-radius: 5px; box-shadow: 0 2px 8px rgba(25,182,119,0.08); text-decoration: none; transition: background 0.2s, box-shadow 0.2s; margin-top: 16px;">
                    <span class="dashicons dashicons-admin-links" style="vertical-align: middle; margin-right: 8px;"></span> 
                    <?php esc_html_e('Connect to SaaS', 'woodashpro'); ?>
                </a>
                <script>
                document.getElementById('woodashh-connect-btn').addEventListener('click', function(e) {
                    e.preventDefault();
                    this.innerHTML = '<span class="dashicons dashicons-update" style="animation: spin 1s linear infinite;"></span> <?php esc_html_e('Connecting...', 'woodashpro'); ?>';
                    this.style.pointerEvents = 'none';
                    
                    var siteUrl = '<?php echo esc_js(get_site_url()); ?>';
                    var platformUrl = 'https://saas2.mohamedamzil.pw/connect-woodash/?site_url=' + encodeURIComponent(siteUrl);
                    var popup = window.open(platformUrl, 'woodash_connect', 'width=800,height=600');
                    
                    window.addEventListener('message', function(event) {
                        if (event.origin !== 'https://saas2.mohamedamzil.pw') return;
                        
                        if (event.data.type === 'woodash_connect_success') {
                            jQuery.post(ajaxurl, { 
                                action: 'woodash_store_connection', 
                                store_id: event.data.store_id, 
                                api_key: event.data.api_key, 
                                nonce: '<?php echo wp_create_nonce('woodash_connect'); ?>' 
                            }, function(response) {
                                if (response.success) {
                                    window.location.href = response.data.redirect_url;
                                } else {
                                    alert('<?php esc_html_e('Failed to complete connection. Please try again.', 'woodashpro'); ?>');
                                    resetButton();
                                }
                            }).fail(function() {
                                alert('<?php esc_html_e('Connection failed. Please try again.', 'woodashpro'); ?>');
                                resetButton();
                            });
                        } else if (event.data.type === 'woodash_connect_error') {
                            alert(event.data.message || '<?php esc_html_e('Connection failed. Please try again.', 'woodashpro'); ?>');
                            resetButton();
                        }
                    });
                });
                
                function resetButton() {
                    var btn = document.getElementById('woodashh-connect-btn');
                    btn.innerHTML = '<span class="dashicons dashicons-admin-links" style="vertical-align: middle; margin-right: 8px;"></span> <?php esc_html_e('Connect to SaaS', 'woodashpro'); ?>';
                    btn.style.pointerEvents = 'auto';
                }
                </script>
                <style>@keyframes spin { 0% { transform: rotate(0deg);} 100% { transform: rotate(360deg);} }</style>
            </div>
        </div>
        <?php
    }
    
    /**
     * Handle generate keys AJAX request
     */
    public function handle_generate_keys() {
        check_ajax_referer('woodashh_generate_keys', 'nonce');
        
        try {
            $user = wp_get_current_user();
            $app_name = 'WooDash Pro - ' . get_bloginfo('name');
            
            $new_password = WP_Application_Passwords::create_new_application_password(
                $user->ID, 
                array('name' => $app_name)
            );
            
            if (is_wp_error($new_password)) {
                throw new Exception($new_password->get_error_message());
            }
            
            if (!is_array($new_password) || empty($new_password)) {
                throw new Exception(__('Failed to generate application password', 'woodashpro'));
            }
            
            $api_key = $new_password[0] ?? null;
            if (!$api_key) {
                throw new Exception(__('Failed to get application password', 'woodashpro'));
            }
            
            update_option('woodashh_api_credentials', array(
                'api_key' => $api_key,
                'site_url' => get_site_url(),
                'user_id' => $user->ID
            ));
            
            wp_send_json_success(array(
                'api_key' => $api_key,
                'site_url' => get_site_url()
            ));
            
        } catch (Exception $e) {
            wp_send_json_error(array('message' => $e->getMessage()));
        }
    }
    
    /**
     * Handle debug log AJAX request
     */
    public function handle_debug_log() {
        check_ajax_referer('woodashh_log', 'nonce');
        
        $message = sanitize_text_field($_POST['message']);
        $data = json_decode(stripslashes($_POST['data']), true);
        
        woodashpro_log($message, $data);
        wp_send_json_success();
    }
    
    /**
     * Handle store connection AJAX request
     */
    public function handle_store_connection() {
        check_ajax_referer('woodash_connect', 'nonce');
        
        $store_id = sanitize_text_field($_POST['store_id']);
        $api_key = sanitize_text_field($_POST['api_key']);
        
        update_option('woodash_connected', true);
        update_option('woodash_store_id', $store_id);
        update_option('woodash_api_key', $api_key);
        
        wp_send_json_success(array(
            'redirect_url' => admin_url('admin.php?page=woodash-pro')
        ));
    }
    
    /**
     * Handle logout AJAX request
     */
    public function handle_logout() {
        check_ajax_referer('woodash_nonce', 'nonce');
        
        delete_option('woodash_connected');
        delete_option('woodash_store_id');
        delete_option('woodash_api_key');
        
        wp_send_json_success(array(
            'redirect_url' => admin_url('admin.php?page=woodash-pro-activate')
        ));
    }
    
    /**
     * Get admin info
     */
    public function get_admin_info() {
        return array(
            'is_connected' => get_option('woodash_connected', false),
            'store_id' => get_option('woodash_store_id', ''),
            'current_page' => isset($_GET['page']) ? sanitize_text_field($_GET['page']) : ''
        );
    }
}
