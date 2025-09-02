<?php
/**
 * Analytics class for WoodDash Pro
 *
 * @package WoodashPro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

class Woodash_Analytics {
    
    private $cache_duration = 1800; // 30 minutes
    
    public function __construct() {
        add_action('wp_ajax_woodash_get_processing_count', array($this, 'ajax_get_processing_count'));
        add_action('wp_ajax_woodash_export_products_csv', array($this, 'ajax_export_products_csv'));
        add_action('wp_ajax_woodash_export_customers_csv', array($this, 'ajax_export_customers_csv'));
    }
    
    /**
     * Get dashboard analytics data
     *
     * @param string $date_from
     * @param string $date_to
     * @param string $granularity
     * @return array
     */
    public function get_dashboard_data($date_from, $date_to, $granularity = 'daily') {
        $cache_key = "dashboard_data_{$date_from}_{$date_to}_{$granularity}";
        
        return Woodash_Cache::get($cache_key, function() use ($date_from, $date_to, $granularity) {
            return $this->calculate_dashboard_data($date_from, $date_to, $granularity);
        }, $this->cache_duration);
    }
    
    /**
     * Calculate dashboard analytics data
     *
     * @param string $date_from
     * @param string $date_to
     * @param string $granularity
     * @return array
     */
    private function calculate_dashboard_data($date_from, $date_to, $granularity) {
        Woodash_Logger::debug('Calculating dashboard data', array(
            'date_from' => $date_from,
            'date_to' => $date_to,
            'granularity' => $granularity
        ));
        
        $args = array(
            'status' => array('wc-completed'),
            'limit' => -1,
            'return' => 'ids'
        );
        
        if ($date_from) {
            $args['date_created']['after'] = $date_from . ' 00:00:00';
        }
        if ($date_to) {
            $args['date_created']['before'] = $date_to . ' 23:59:59';
        }
        
        $order_ids = wc_get_orders($args);
        $orders = array();
        $total_sales = 0;
        
        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);
            if ($order) {
                $total_sales += (float) $order->get_total();
                $orders[] = $order;
            }
        }
        
        $total_orders = count($orders);
        $aov = $total_orders > 0 ? round($total_sales / $total_orders, 2) : 0;
        
        // Calculate sales overview
        $sales_overview = $this->calculate_sales_overview($orders, $granularity);
        
        // Get top products
        $top_products = $this->get_top_products($orders, 5);
        
        // Get top customers
        $top_customers = $this->get_top_customers($orders, 5);
        
        $data = array(
            'total_sales' => wc_format_decimal($total_sales, 2),
            'total_orders' => $total_orders,
            'aov' => $aov,
            'top_products' => $top_products,
            'top_customers' => $top_customers,
            'sales_overview' => $sales_overview
        );
        
        Woodash_Logger::debug('Dashboard data calculated', array(
            'total_sales' => $total_sales,
            'total_orders' => $total_orders,
            'aov' => $aov
        ));
        
        return $data;
    }
    
    /**
     * Calculate sales overview by time period
     *
     * @param array $orders
     * @param string $granularity
     * @return array
     */
    private function calculate_sales_overview($orders, $granularity) {
        $grouped = array();
        
        foreach ($orders as $order) {
            $date = $order->get_date_created();
            if (!$date) continue;
            
            switch ($granularity) {
                case 'daily':
                    $key = $date->date_i18n('Y-m-d');
                    break;
                case 'weekly':
                    $key = $date->date_i18n('o-\WW');
                    break;
                case 'monthly':
                    $key = $date->date_i18n('Y-m');
                    break;
                default:
                    $key = $date->date_i18n('Y-m-d');
            }
            
            if (!isset($grouped[$key])) {
                $grouped[$key] = 0;
            }
            $grouped[$key] += (float) $order->get_total();
        }
        
        ksort($grouped);
        
        return array(
            'labels' => array_keys($grouped),
            'data' => array_values($grouped)
        );
    }
    
    /**
     * Get top selling products
     *
     * @param array $orders
     * @param int $limit
     * @return array
     */
    private function get_top_products($orders, $limit = 10) {
        $product_sales = array();
        
        foreach ($orders as $order) {
            foreach ($order->get_items() as $item) {
                $product_id = $item->get_product_id();
                if (!isset($product_sales[$product_id])) {
                    $product_sales[$product_id] = array(
                        'quantity' => 0,
                        'revenue' => 0
                    );
                }
                $product_sales[$product_id]['quantity'] += $item->get_quantity();
                $product_sales[$product_id]['revenue'] += $item->get_total();
            }
        }
        
        // Sort by quantity sold
        uasort($product_sales, function($a, $b) {
            return $b['quantity'] - $a['quantity'];
        });
        
        $top_products = array();
        $count = 0;
        
        foreach ($product_sales as $product_id => $data) {
            if ($count >= $limit) break;
            
            $product = wc_get_product($product_id);
            if ($product) {
                $top_products[] = array(
                    'id' => $product_id,
                    'name' => $product->get_name(),
                    'sales' => $data['quantity'],
                    'revenue' => wc_format_decimal($data['revenue'], 2),
                    'price' => $product->get_price()
                );
                $count++;
            }
        }
        
        return $top_products;
    }
    
    /**
     * Get top customers
     *
     * @param array $orders
     * @param int $limit
     * @return array
     */
    private function get_top_customers($orders, $limit = 10) {
        $customer_data = array();
        
        foreach ($orders as $order) {
            $customer_id = $order->get_customer_id();
            $customer_email = $order->get_billing_email();
            
            // Use email as fallback identifier for guest customers
            $identifier = $customer_id ?: $customer_email;
            
            if (!$identifier) continue;
            
            if (!isset($customer_data[$identifier])) {
                $customer_data[$identifier] = array(
                    'orders' => 0,
                    'total_spent' => 0,
                    'customer_id' => $customer_id,
                    'email' => $customer_email
                );
            }
            
            $customer_data[$identifier]['orders']++;
            $customer_data[$identifier]['total_spent'] += (float) $order->get_total();
        }
        
        // Sort by number of orders
        uasort($customer_data, function($a, $b) {
            return $b['orders'] - $a['orders'];
        });
        
        $top_customers = array();
        $count = 0;
        
        foreach ($customer_data as $identifier => $data) {
            if ($count >= $limit) break;
            
            $name = __('Guest', 'woodash-pro');
            if ($data['customer_id']) {
                $user = get_userdata($data['customer_id']);
                if ($user) {
                    $name = $user->display_name ?: $user->user_login;
                }
            } else {
                $name = $data['email'];
            }
            
            $top_customers[] = array(
                'id' => $data['customer_id'],
                'name' => $name,
                'email' => $data['email'],
                'orders' => $data['orders'],
                'total_spent' => wc_format_decimal($data['total_spent'], 2)
            );
            
            $count++;
        }
        
        return $top_customers;
    }
    
    /**
     * Get processing orders count
     *
     * @param string $date_from
     * @param string $date_to
     * @return int
     */
    public function get_processing_count($date_from, $date_to) {
        $args = array(
            'status' => array('wc-processing'),
            'limit' => -1,
            'return' => 'ids'
        );
        
        if ($date_from) {
            $args['date_created']['after'] = $date_from . ' 00:00:00';
        }
        if ($date_to) {
            $args['date_created']['before'] = $date_to . ' 23:59:59';
        }
        
        $order_ids = wc_get_orders($args);
        return count($order_ids);
    }
    
    /**
     * Get revenue comparison data
     *
     * @param string $current_from
     * @param string $current_to
     * @param string $previous_from
     * @param string $previous_to
     * @return array
     */
    public function get_revenue_comparison($current_from, $current_to, $previous_from, $previous_to) {
        $current_revenue = $this->get_period_revenue($current_from, $current_to);
        $previous_revenue = $this->get_period_revenue($previous_from, $previous_to);
        
        $change = woodash_get_percentage_change($current_revenue, $previous_revenue);
        
        return array(
            'current' => $current_revenue,
            'previous' => $previous_revenue,
            'change' => $change
        );
    }
    
    /**
     * Get revenue for a specific period
     *
     * @param string $date_from
     * @param string $date_to
     * @return float
     */
    private function get_period_revenue($date_from, $date_to) {
        $args = array(
            'status' => array('wc-completed'),
            'limit' => -1,
            'date_created' => $date_from . '...' . $date_to
        );
        
        $orders = wc_get_orders($args);
        $total = 0;
        
        foreach ($orders as $order) {
            $total += (float) $order->get_total();
        }
        
        return $total;
    }
    
    /**
     * AJAX handler for processing orders count
     */
    public function ajax_get_processing_count() {
        check_ajax_referer('woodash_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized', 'woodash-pro'), 403);
        }
        
        $after = sanitize_text_field($_POST['after'] ?? '');
        $before = sanitize_text_field($_POST['before'] ?? '');
        
        $after_date = $after ? substr($after, 0, 10) : current_time('Y-m-d');
        $before_date = $before ? substr($before, 0, 10) : current_time('Y-m-d');
        
        $count = $this->get_processing_count($after_date, $before_date);
        
        wp_send_json_success(array('processing' => $count));
    }
    
    /**
     * Export top products as CSV
     */
    public function ajax_export_products_csv() {
        check_ajax_referer('woodash_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized', 'woodash-pro'), 403);
        }
        
        $date_from = sanitize_text_field($_GET['date_from'] ?? '');
        $date_to = sanitize_text_field($_GET['date_to'] ?? '');
        
        $orders = $this->get_orders_for_period($date_from, $date_to);
        $products = $this->get_top_products($orders, 50); // Export top 50
        
        $filename = 'woodash-top-products-' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, array('Product Name', 'Sales', 'Revenue', 'Price'));
        
        foreach ($products as $product) {
            fputcsv($output, array(
                $product['name'],
                $product['sales'],
                $product['revenue'],
                $product['price']
            ));
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Export top customers as CSV
     */
    public function ajax_export_customers_csv() {
        check_ajax_referer('woodash_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized', 'woodash-pro'), 403);
        }
        
        $date_from = sanitize_text_field($_GET['date_from'] ?? '');
        $date_to = sanitize_text_field($_GET['date_to'] ?? '');
        
        $orders = $this->get_orders_for_period($date_from, $date_to);
        $customers = $this->get_top_customers($orders, 50); // Export top 50
        
        $filename = 'woodash-top-customers-' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, array('Customer Name', 'Email', 'Orders', 'Total Spent'));
        
        foreach ($customers as $customer) {
            fputcsv($output, array(
                $customer['name'],
                $customer['email'],
                $customer['orders'],
                $customer['total_spent']
            ));
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Get orders for a specific period
     *
     * @param string $date_from
     * @param string $date_to
     * @return array
     */
    private function get_orders_for_period($date_from, $date_to) {
        $args = array(
            'status' => array('wc-completed'),
            'limit' => -1
        );
        
        if ($date_from) {
            $args['date_created']['after'] = $date_from . ' 00:00:00';
        }
        if ($date_to) {
            $args['date_created']['before'] = $date_to . ' 23:59:59';
        }
        
        return wc_get_orders($args);
    }
    
    /**
     * Get conversion rate
     *
     * @param string $date_from
     * @param string $date_to
     * @return float
     */
    public function get_conversion_rate($date_from, $date_to) {
        // This would need integration with analytics tools or custom tracking
        // For now, return a placeholder
        return 3.2;
    }
    
    /**
     * Get average session duration
     *
     * @param string $date_from
     * @param string $date_to
     * @return string
     */
    public function get_avg_session_duration($date_from, $date_to) {
        // This would need integration with analytics tools
        // For now, return a placeholder
        return '2m 34s';
    }
    
    /**
     * Get bounce rate
     *
     * @param string $date_from
     * @param string $date_to
     * @return float
     */
    public function get_bounce_rate($date_from, $date_to) {
        // This would need integration with analytics tools
        // For now, return a placeholder
        return 42.3;
    }
}
