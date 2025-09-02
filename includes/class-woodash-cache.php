<?php
/**
 * Cache class for WoodDash Pro
 *
 * @package WoodashPro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

class Woodash_Cache {
    
    private static $cache_prefix = 'woodash_';
    private static $default_expiration = 3600; // 1 hour
    
    /**
     * Get cached data
     *
     * @param string $key
     * @param callable $callback
     * @param int $expiration
     * @return mixed
     */
    public static function get($key, $callback = null, $expiration = null) {
        $cache_key = self::get_cache_key($key);
        $cached_data = get_transient($cache_key);
        
        if ($cached_data !== false) {
            Woodash_Logger::debug('Cache hit', array('key' => $key));
            return $cached_data;
        }
        
        if ($callback && is_callable($callback)) {
            Woodash_Logger::debug('Cache miss, executing callback', array('key' => $key));
            $data = $callback();
            self::set($key, $data, $expiration);
            return $data;
        }
        
        return false;
    }
    
    /**
     * Set cached data
     *
     * @param string $key
     * @param mixed $data
     * @param int $expiration
     * @return bool
     */
    public static function set($key, $data, $expiration = null) {
        if ($expiration === null) {
            $expiration = self::$default_expiration;
        }
        
        $cache_key = self::get_cache_key($key);
        $result = set_transient($cache_key, $data, $expiration);
        
        if ($result) {
            Woodash_Logger::debug('Cache set', array(
                'key' => $key,
                'expiration' => $expiration,
                'data_size' => strlen(serialize($data))
            ));
        }
        
        return $result;
    }
    
    /**
     * Delete cached data
     *
     * @param string $key
     * @return bool
     */
    public static function delete($key) {
        $cache_key = self::get_cache_key($key);
        $result = delete_transient($cache_key);
        
        if ($result) {
            Woodash_Logger::debug('Cache deleted', array('key' => $key));
        }
        
        return $result;
    }
    
    /**
     * Clear all plugin cache
     */
    public static function clear_all() {
        global $wpdb;
        
        $prefix = $wpdb->esc_like('_transient_' . self::$cache_prefix) . '%';
        $timeout_prefix = $wpdb->esc_like('_transient_timeout_' . self::$cache_prefix) . '%';
        
        $query = $wpdb->prepare(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
            $prefix,
            $timeout_prefix
        );
        
        $result = $wpdb->query($query);
        
        Woodash_Logger::info('Cache cleared', array('deleted_entries' => $result));
        
        return $result;
    }
    
    /**
     * Get analytics data with caching
     *
     * @param string $date_from
     * @param string $date_to
     * @param string $type
     * @return mixed
     */
    public static function get_analytics_data($date_from, $date_to, $type = 'overview') {
        $cache_key = "analytics_{$type}_{$date_from}_{$date_to}";
        
        return self::get($cache_key, function() use ($date_from, $date_to, $type) {
            // This would be implemented in the analytics class
            if (class_exists('Woodash_Analytics')) {
                $analytics = new Woodash_Analytics();
                return $analytics->get_data($date_from, $date_to, $type);
            }
            return array();
        }, 1800); // 30 minutes cache
    }
    
    /**
     * Get WooCommerce orders with caching
     *
     * @param array $args
     * @return array
     */
    public static function get_orders($args = array()) {
        $cache_key = 'orders_' . md5(serialize($args));
        
        return self::get($cache_key, function() use ($args) {
            return wc_get_orders($args);
        }, 900); // 15 minutes cache
    }
    
    /**
     * Get WooCommerce products with caching
     *
     * @param array $args
     * @return array
     */
    public static function get_products($args = array()) {
        $cache_key = 'products_' . md5(serialize($args));
        
        return self::get($cache_key, function() use ($args) {
            $query = new WC_Product_Query($args);
            return $query->get_products();
        }, 1800); // 30 minutes cache
    }
    
    /**
     * Get top selling products with caching
     *
     * @param string $date_from
     * @param string $date_to
     * @param int $limit
     * @return array
     */
    public static function get_top_products($date_from, $date_to, $limit = 10) {
        $cache_key = "top_products_{$date_from}_{$date_to}_{$limit}";
        
        return self::get($cache_key, function() use ($date_from, $date_to, $limit) {
            global $wpdb;
            
            $query = $wpdb->prepare("
                SELECT 
                    oi.product_id,
                    SUM(oi.product_qty) as total_sales,
                    SUM(oi.product_net_revenue) as total_revenue
                FROM {$wpdb->prefix}wc_order_product_lookup oi
                JOIN {$wpdb->prefix}wc_order_stats os ON oi.order_id = os.order_id
                WHERE os.date_created >= %s 
                AND os.date_created <= %s
                AND os.status IN ('wc-completed', 'wc-processing')
                GROUP BY oi.product_id
                ORDER BY total_sales DESC
                LIMIT %d
            ", $date_from, $date_to, $limit);
            
            $results = $wpdb->get_results($query);
            $products = array();
            
            foreach ($results as $result) {
                $product = wc_get_product($result->product_id);
                if ($product) {
                    $products[] = array(
                        'id' => $result->product_id,
                        'name' => $product->get_name(),
                        'sales' => $result->total_sales,
                        'revenue' => $result->total_revenue,
                        'price' => $product->get_price()
                    );
                }
            }
            
            return $products;
        }, 1800); // 30 minutes cache
    }
    
    /**
     * Get cache statistics
     *
     * @return array
     */
    public static function get_cache_stats() {
        global $wpdb;
        
        $prefix = $wpdb->esc_like('_transient_' . self::$cache_prefix) . '%';
        
        $query = $wpdb->prepare(
            "SELECT COUNT(*) as count, SUM(LENGTH(option_value)) as size 
             FROM {$wpdb->options} 
             WHERE option_name LIKE %s",
            $prefix
        );
        
        $result = $wpdb->get_row($query);
        
        return array(
            'entries' => (int) $result->count,
            'size' => (int) $result->size,
            'size_formatted' => size_format((int) $result->size)
        );
    }
    
    /**
     * Warm up cache for common queries
     */
    public static function warm_up() {
        $date_ranges = array(
            'today' => woodash_get_date_range('today'),
            'last7days' => woodash_get_date_range('last7days'),
            'last30days' => woodash_get_date_range('last30days')
        );
        
        foreach ($date_ranges as $range_name => $range) {
            // Warm up analytics data
            self::get_analytics_data($range['start'], $range['end'], 'overview');
            
            // Warm up top products
            self::get_top_products($range['start'], $range['end'], 10);
            
            Woodash_Logger::debug('Cache warmed up', array('range' => $range_name));
        }
    }
    
    /**
     * Schedule cache cleanup
     */
    public static function schedule_cleanup() {
        if (!wp_next_scheduled('woodash_cache_cleanup')) {
            wp_schedule_event(time(), 'daily', 'woodash_cache_cleanup');
        }
    }
    
    /**
     * Cleanup expired cache entries
     */
    public static function cleanup_expired() {
        global $wpdb;
        
        // WordPress automatically handles transient cleanup, but we can force it
        $expired_query = $wpdb->prepare("
            DELETE a, b FROM {$wpdb->options} a, {$wpdb->options} b
            WHERE a.option_name LIKE %s
            AND a.option_name = REPLACE(b.option_name, '_transient_timeout_', '_transient_')
            AND b.option_value < %d
        ", 
        $wpdb->esc_like('_transient_timeout_' . self::$cache_prefix) . '%',
        time()
        );
        
        $deleted = $wpdb->query($expired_query);
        
        Woodash_Logger::info('Expired cache cleaned up', array('deleted_entries' => $deleted));
        
        return $deleted;
    }
    
    /**
     * Get cache key with prefix
     *
     * @param string $key
     * @return string
     */
    private static function get_cache_key($key) {
        return self::$cache_prefix . md5($key);
    }
    
    /**
     * Check if cache is enabled
     *
     * @return bool
     */
    public static function is_enabled() {
        return get_option('woodash_cache_enabled', true);
    }
    
    /**
     * Enable/disable cache
     *
     * @param bool $enabled
     */
    public static function set_enabled($enabled) {
        update_option('woodash_cache_enabled', (bool) $enabled);
        
        if (!$enabled) {
            self::clear_all();
        }
    }
}
