<?php
/**
 * Plugin Activation Template
 * Displayed when WooCommerce is not installed or activated
 *
 * @package WoodashPro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

?>
<div class="wrap">
    <div style="max-width: 800px; margin: 50px auto; padding: 40px; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center;">
        
        <!-- Logo/Icon -->
        <div style="margin-bottom: 30px;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #00CC61, #00b357); border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                <svg width="40" height="40" fill="white" viewBox="0 0 24 24">
                    <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                </svg>
            </div>
        </div>

        <!-- Main Heading -->
        <h1 style="color: #1e293b; font-size: 32px; font-weight: 700; margin-bottom: 16px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
            Welcome to WooDash Pro!
        </h1>

        <p style="color: #64748b; font-size: 18px; margin-bottom: 40px; line-height: 1.6;">
            Your advanced WooCommerce analytics dashboard is almost ready.
        </p>

        <!-- Requirements Card -->
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 8px; padding: 30px; margin-bottom: 30px; text-align: left;">
            <h2 style="color: #1e293b; font-size: 20px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center;">
                <span style="width: 24px; height: 24px; background: #ef4444; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; margin-right: 12px; font-size: 14px; font-weight: bold;">!</span>
                Requirements Check
            </h2>
            
            <div style="space-y: 12px;">
                <!-- WordPress Check -->
                <div style="display: flex; align-items: center; margin-bottom: 12px;">
                    <span style="width: 20px; height: 20px; background: #10b981; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; margin-right: 12px; font-size: 12px;">✓</span>
                    <span style="color: #374151;">WordPress <?php echo get_bloginfo('version'); ?> (✓ Compatible)</span>
                </div>
                
                <!-- PHP Check -->
                <div style="display: flex; align-items: center; margin-bottom: 12px;">
                    <span style="width: 20px; height: 20px; background: #10b981; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; margin-right: 12px; font-size: 12px;">✓</span>
                    <span style="color: #374151;">PHP <?php echo PHP_VERSION; ?> (✓ Compatible)</span>
                </div>
                
                <!-- WooCommerce Check -->
                <?php if (!class_exists('WooCommerce')): ?>
                <div style="display: flex; align-items: center; margin-bottom: 12px;">
                    <span style="width: 20px; height: 20px; background: #ef4444; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; margin-right: 12px; font-size: 12px;">✗</span>
                    <span style="color: #374151;">WooCommerce (✗ Not installed or activated)</span>
                </div>
                <?php else: ?>
                <div style="display: flex; align-items: center; margin-bottom: 12px;">
                    <span style="width: 20px; height: 20px; background: #10b981; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; margin-right: 12px; font-size: 12px;">✓</span>
                    <span style="color: #374151;">WooCommerce <?php echo WC()->version; ?> (✓ Active)</span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!class_exists('WooCommerce')): ?>
        <!-- WooCommerce Installation Section -->
        <div style="background: #fef3c7; border: 2px solid #f59e0b; border-radius: 8px; padding: 30px; margin-bottom: 30px;">
            <h3 style="color: #92400e; font-size: 18px; font-weight: 600; margin-bottom: 16px;">
                WooCommerce Required
            </h3>
            <p style="color: #92400e; margin-bottom: 20px; line-height: 1.6;">
                WooDash Pro requires WooCommerce to be installed and activated. WooCommerce is the most popular eCommerce platform for WordPress.
            </p>
            
            <!-- Installation Options -->
            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <?php if (current_user_can('install_plugins')): ?>
                <a href="<?php echo admin_url('plugin-install.php?s=woocommerce&tab=search&type=term'); ?>" 
                   class="button button-primary" 
                   style="background: #00CC61; border-color: #00CC61; color: white; padding: 12px 24px; font-weight: 600; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center;">
                    <span style="margin-right: 8px;">📦</span>
                    Install WooCommerce
                </a>
                <?php endif; ?>
                
                <a href="https://woocommerce.com/" target="_blank" 
                   class="button button-secondary"
                   style="padding: 12px 24px; font-weight: 600; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center;">
                    <span style="margin-right: 8px;">🌐</span>
                    Learn More
                </a>
            </div>
        </div>
        <?php else: ?>
        <!-- Success Section -->
        <div style="background: #ecfdf5; border: 2px solid #10b981; border-radius: 8px; padding: 30px; margin-bottom: 30px;">
            <h3 style="color: #065f46; font-size: 18px; font-weight: 600; margin-bottom: 16px;">
                🎉 All Requirements Met!
            </h3>
            <p style="color: #065f46; margin-bottom: 20px; line-height: 1.6;">
                Great! All requirements are satisfied. You can now access your WooDash Pro dashboard.
            </p>
            
            <a href="<?php echo admin_url('admin.php?page=woodash-dashboard'); ?>" 
               class="button button-primary" 
               style="background: #00CC61; border-color: #00CC61; color: white; padding: 12px 24px; font-weight: 600; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center;">
                <span style="margin-right: 8px;">🚀</span>
                Go to Dashboard
            </a>
        </div>
        <?php endif; ?>

        <!-- Features Preview -->
        <div style="margin-top: 40px;">
            <h3 style="color: #1e293b; font-size: 20px; font-weight: 600; margin-bottom: 24px;">
                What's included in WooDash Pro
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; text-align: left;">
                <div style="background: #f8fafc; padding: 20px; border-radius: 8px;">
                    <div style="width: 40px; height: 40px; background: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <span style="color: white; font-size: 18px;">📊</span>
                    </div>
                    <h4 style="color: #1e293b; font-weight: 600; margin-bottom: 8px;">Real-time Analytics</h4>
                    <p style="color: #64748b; font-size: 14px; line-height: 1.5;">Live sales data, charts, and performance metrics</p>
                </div>
                
                <div style="background: #f8fafc; padding: 20px; border-radius: 8px;">
                    <div style="width: 40px; height: 40px; background: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <span style="color: white; font-size: 18px;">🔍</span>
                    </div>
                    <h4 style="color: #1e293b; font-weight: 600; margin-bottom: 8px;">Advanced Reports</h4>
                    <p style="color: #64748b; font-size: 14px; line-height: 1.5;">Detailed insights into products and customers</p>
                </div>
                
                <div style="background: #f8fafc; padding: 20px; border-radius: 8px;">
                    <div style="width: 40px; height: 40px; background: #f59e0b; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <span style="color: white; font-size: 18px;">📱</span>
                    </div>
                    <h4 style="color: #1e293b; font-weight: 600; margin-bottom: 8px;">Mobile Responsive</h4>
                    <p style="color: #64748b; font-size: 14px; line-height: 1.5;">Access your dashboard on any device</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <p style="color: #94a3b8; font-size: 14px;">
                Need help? Check out our <a href="#" style="color: #00CC61; text-decoration: none;">documentation</a> or 
                <a href="#" style="color: #00CC61; text-decoration: none;">contact support</a>.
            </p>
        </div>
    </div>
</div>

<style>
.button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.2s ease;
}

@media (max-width: 768px) {
    .wrap > div {
        margin: 20px 10px !important;
        padding: 20px !important;
    }
    
    .wrap h1 {
        font-size: 24px !important;
    }
    
    .wrap .button {
        width: 100%;
        justify-content: center;
        margin-bottom: 8px;
    }
}
</style>
