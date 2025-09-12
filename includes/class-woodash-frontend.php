<?php
/**
 * WooDash Pro Frontend Class
 * 
 * Handles all frontend-related functionality
 * 
 * @package WooDashPro
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class WooDash_Frontend {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize frontend hooks
     */
    private function init_hooks() {
        // Frontend shortcodes
        add_action('init', array($this, 'register_shortcodes'));
        
        // Frontend AJAX handlers
        add_action('wp_ajax_nopriv_woodash_public_data', array($this, 'handle_public_data'));
        add_action('wp_ajax_woodash_public_data', array($this, 'handle_public_data'));
    }
    
    /**
     * Register shortcodes
     */
    public function register_shortcodes() {
        add_shortcode('woodash_stats', array($this, 'stats_shortcode'));
        add_shortcode('woodash_chart', array($this, 'chart_shortcode'));
    }
    
    /**
     * Stats shortcode
     * 
     * Usage: [woodash_stats type="sales" period="30"]
     */
    public function stats_shortcode($atts) {
        $atts = shortcode_atts(array(
            'type' => 'sales',
            'period' => '30',
            'format' => 'number'
        ), $atts, 'woodash_stats');
        
        // Check if user has permission to view stats
        if (!current_user_can('view_woocommerce_reports')) {
            return '<p>' . esc_html__('You do not have permission to view these statistics.', 'woodashpro') . '</p>';
        }
        
        $value = $this->get_stat_value($atts['type'], $atts['period']);
        
        if ($atts['format'] === 'currency') {
            $value = wc_price($value);
        } else {
            $value = number_format($value);
        }
        
        return '<span class="woodash-stat woodash-stat-' . esc_attr($atts['type']) . '">' . $value . '</span>';
    }
    
    /**
     * Chart shortcode
     * 
     * Usage: [woodash_chart type="sales" period="30" height="400"]
     */
    public function chart_shortcode($atts) {
        $atts = shortcode_atts(array(
            'type' => 'sales',
            'period' => '30',
            'height' => '400',
            'width' => '100%'
        ), $atts, 'woodash_chart');
        
        // Check if user has permission to view charts
        if (!current_user_can('view_woocommerce_reports')) {
            return '<p>' . esc_html__('You do not have permission to view this chart.', 'woodashpro') . '</p>';
        }
        
        // Generate unique ID for chart
        $chart_id = 'woodash-chart-' . uniqid();
        
        // Enqueue Chart.js if not already enqueued
        wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true);
        
        $chart_data = $this->get_chart_data($atts['type'], $atts['period']);
        
        ob_start();
        ?>
        <div class="woodash-chart-container" style="width: <?php echo esc_attr($atts['width']); ?>; height: <?php echo esc_attr($atts['height']); ?>px;">
            <canvas id="<?php echo esc_attr($chart_id); ?>" width="100%" height="<?php echo esc_attr($atts['height']); ?>"></canvas>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('<?php echo esc_js($chart_id); ?>').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: <?php echo json_encode($chart_data); ?>,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get statistical value
     */
    private function get_stat_value($type, $period) {
        $end_date = current_time('Y-m-d');
        $start_date = date('Y-m-d', strtotime("-{$period} days"));
        
        $args = array(
            'status' => array('wc-completed'),
            'limit' => -1,
            'date_created' => array(
                'after' => $start_date . ' 00:00:00',
                'before' => $end_date . ' 23:59:59'
            )
        );
        
        $orders = wc_get_orders($args);
        
        switch ($type) {
            case 'sales':
                $total = 0;
                foreach ($orders as $order) {
                    $total += (float) $order->get_total();
                }
                return $total;
                
            case 'orders':
                return count($orders);
                
            case 'customers':
                $customer_ids = array();
                foreach ($orders as $order) {
                    $customer_id = $order->get_customer_id();
                    if ($customer_id && !in_array($customer_id, $customer_ids)) {
                        $customer_ids[] = $customer_id;
                    }
                }
                return count($customer_ids);
                
            default:
                return 0;
        }
    }
    
    /**
     * Get chart data
     */
    private function get_chart_data($type, $period) {
        $days = intval($period);
        $labels = array();
        $data = array();
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $labels[] = date('M d', strtotime($date));
            
            $args = array(
                'status' => array('wc-completed'),
                'limit' => -1,
                'date_created' => array(
                    'after' => $date . ' 00:00:00',
                    'before' => $date . ' 23:59:59'
                )
            );
            
            $orders = wc_get_orders($args);
            
            switch ($type) {
                case 'sales':
                    $total = 0;
                    foreach ($orders as $order) {
                        $total += (float) $order->get_total();
                    }
                    $data[] = $total;
                    break;
                    
                case 'orders':
                    $data[] = count($orders);
                    break;
                    
                default:
                    $data[] = 0;
            }
        }
        
        return array(
            'labels' => $labels,
            'datasets' => array(
                array(
                    'label' => ucfirst($type),
                    'data' => $data,
                    'borderColor' => '#00CC61',
                    'backgroundColor' => 'rgba(0, 204, 97, 0.1)',
                    'tension' => 0.4,
                    'fill' => true
                )
            )
        );
    }
    
    /**
     * Handle public data AJAX request
     */
    public function handle_public_data() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'woodash_public_nonce')) {
            wp_send_json_error('Invalid nonce');
        }
        
        // Check permissions
        if (!current_user_can('view_woocommerce_reports')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $type = sanitize_text_field($_POST['type']);
        $period = intval($_POST['period']);
        
        $value = $this->get_stat_value($type, $period);
        
        wp_send_json_success(array(
            'type' => $type,
            'period' => $period,
            'value' => $value
        ));
    }
    
    /**
     * Get frontend info
     */
    public function get_frontend_info() {
        return array(
            'shortcodes_registered' => true,
            'ajax_handlers_registered' => true,
            'user_can_view_reports' => current_user_can('view_woocommerce_reports')
        );
    }
}
