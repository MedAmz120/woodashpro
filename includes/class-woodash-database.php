<?php
/**
 * WooDash Pro Database Manager
 * Handles all database operations for WooDash Pro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

class WoodashDatabase {
    private static $instance = null;
    private $wpdb;
    private $tables = array();
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->init_tables();
    }
    
    /**
     * Initialize table names
     */
    private function init_tables() {
        $this->tables = array(
            'notifications' => $this->wpdb->prefix . 'woodash_notifications',
            'campaigns' => $this->wpdb->prefix . 'woodash_campaigns',
            'analytics' => $this->wpdb->prefix . 'woodash_analytics',
            'settings' => $this->wpdb->prefix . 'woodash_settings',
            'logs' => $this->wpdb->prefix . 'woodash_logs',
            'cache' => $this->wpdb->prefix . 'woodash_cache'
        );
    }
    
    /**
     * Create all WooDash Pro database tables
     */
    public function create_tables() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $charset_collate = $this->wpdb->get_charset_collate();
        
        // Create notifications table
        $this->create_notifications_table($charset_collate);
        
        // Create campaigns table
        $this->create_campaigns_table($charset_collate);
        
        // Create analytics table
        $this->create_analytics_table($charset_collate);
        
        // Create settings table
        $this->create_settings_table($charset_collate);
        
        // Create logs table
        $this->create_logs_table($charset_collate);
        
        // Create cache table
        $this->create_cache_table($charset_collate);
        
        // Set database version
        update_option('woodash_db_version', WOODASH_PRO_VERSION);
        
        return true;
    }
    
    /**
     * Create notifications table
     */
    private function create_notifications_table($charset_collate) {
        $table_name = $this->tables['notifications'];
        
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            type varchar(50) NOT NULL,
            priority enum('low', 'medium', 'high') DEFAULT 'medium',
            title varchar(255) NOT NULL,
            message text NOT NULL,
            icon varchar(100) DEFAULT 'fa-bell',
            color varchar(50) DEFAULT '#3B82F6',
            bg_color varchar(50) DEFAULT '#3B82F6',
            actions text DEFAULT NULL,
            metadata text DEFAULT NULL,
            is_read tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            read_at datetime DEFAULT NULL,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY type (type),
            KEY is_read (is_read),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        dbDelta($sql);
    }
    
    /**
     * Create campaigns table
     */
    private function create_campaigns_table($charset_collate) {
        $table_name = $this->tables['campaigns'];
        
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            type varchar(50) NOT NULL DEFAULT 'email',
            status varchar(20) NOT NULL DEFAULT 'draft',
            subject varchar(255),
            content longtext,
            target_audience text,
            settings text,
            clicks int(11) DEFAULT 0,
            opens int(11) DEFAULT 0,
            conversions int(11) DEFAULT 0,
            revenue decimal(10,2) DEFAULT 0.00,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            started_at datetime DEFAULT NULL,
            ended_at datetime DEFAULT NULL,
            PRIMARY KEY (id),
            KEY type (type),
            KEY status (status),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        dbDelta($sql);
    }
    
    /**
     * Create analytics table
     */
    private function create_analytics_table($charset_collate) {
        $table_name = $this->tables['analytics'];
        
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            date date NOT NULL,
            metric_type varchar(50) NOT NULL,
            metric_key varchar(100) NOT NULL,
            metric_value decimal(15,4) DEFAULT 0,
            metadata text DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_metric (date, metric_type, metric_key),
            KEY date (date),
            KEY metric_type (metric_type),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        dbDelta($sql);
    }
    
    /**
     * Create settings table
     */
    private function create_settings_table($charset_collate) {
        $table_name = $this->tables['settings'];
        
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) DEFAULT NULL,
            setting_key varchar(100) NOT NULL,
            setting_value longtext,
            setting_type varchar(20) DEFAULT 'string',
            is_global tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_setting (user_id, setting_key),
            KEY setting_key (setting_key),
            KEY is_global (is_global)
        ) $charset_collate;";
        
        dbDelta($sql);
    }
    
    /**
     * Create logs table
     */
    private function create_logs_table($charset_collate) {
        $table_name = $this->tables['logs'];
        
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) DEFAULT NULL,
            level varchar(20) NOT NULL DEFAULT 'info',
            message text NOT NULL,
            context text DEFAULT NULL,
            source varchar(100) DEFAULT NULL,
            ip_address varchar(45) DEFAULT NULL,
            user_agent text DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY level (level),
            KEY source (source),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        dbDelta($sql);
    }
    
    /**
     * Create cache table
     */
    private function create_cache_table($charset_collate) {
        $table_name = $this->tables['cache'];
        
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            cache_key varchar(255) NOT NULL,
            cache_value longtext,
            expires_at datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY cache_key (cache_key),
            KEY expires_at (expires_at)
        ) $charset_collate;";
        
        dbDelta($sql);
    }
    
    /**
     * Get table name
     */
    public function get_table($table_key) {
        return isset($this->tables[$table_key]) ? $this->tables[$table_key] : false;
    }
    
    /**
     * Insert data into any table
     */
    public function insert($table_key, $data, $format = null) {
        $table_name = $this->get_table($table_key);
        if (!$table_name) {
            return false;
        }
        
        $result = $this->wpdb->insert($table_name, $data, $format);
        
        if ($result === false) {
            $this->log_error('Database insert failed', array(
                'table' => $table_key,
                'data' => $data,
                'error' => $this->wpdb->last_error
            ));
            return false;
        }
        
        return $this->wpdb->insert_id;
    }
    
    /**
     * Update data in any table
     */
    public function update($table_key, $data, $where, $format = null, $where_format = null) {
        $table_name = $this->get_table($table_key);
        if (!$table_name) {
            return false;
        }
        
        $result = $this->wpdb->update($table_name, $data, $where, $format, $where_format);
        
        if ($result === false) {
            $this->log_error('Database update failed', array(
                'table' => $table_key,
                'data' => $data,
                'where' => $where,
                'error' => $this->wpdb->last_error
            ));
        }
        
        return $result;
    }
    
    /**
     * Delete data from any table
     */
    public function delete($table_key, $where, $where_format = null) {
        $table_name = $this->get_table($table_key);
        if (!$table_name) {
            return false;
        }
        
        $result = $this->wpdb->delete($table_name, $where, $where_format);
        
        if ($result === false) {
            $this->log_error('Database delete failed', array(
                'table' => $table_key,
                'where' => $where,
                'error' => $this->wpdb->last_error
            ));
        }
        
        return $result;
    }
    
    /**
     * Get data from any table
     */
    public function get_results($table_key, $where = '', $order_by = '', $limit = '') {
        $table_name = $this->get_table($table_key);
        if (!$table_name) {
            return array();
        }
        
        $sql = "SELECT * FROM $table_name";
        
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        
        if (!empty($order_by)) {
            $sql .= " ORDER BY $order_by";
        }
        
        if (!empty($limit)) {
            $sql .= " LIMIT $limit";
        }
        
        $results = $this->wpdb->get_results($sql);
        
        if ($this->wpdb->last_error) {
            $this->log_error('Database select failed', array(
                'table' => $table_key,
                'sql' => $sql,
                'error' => $this->wpdb->last_error
            ));
            return array();
        }
        
        return $results ?: array();
    }
    
    /**
     * Get single row from any table
     */
    public function get_row($table_key, $where = '', $order_by = '') {
        $table_name = $this->get_table($table_key);
        if (!$table_name) {
            return null;
        }
        
        $sql = "SELECT * FROM $table_name";
        
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        
        if (!empty($order_by)) {
            $sql .= " ORDER BY $order_by";
        }
        
        $sql .= " LIMIT 1";
        
        $result = $this->wpdb->get_row($sql);
        
        if ($this->wpdb->last_error) {
            $this->log_error('Database select row failed', array(
                'table' => $table_key,
                'sql' => $sql,
                'error' => $this->wpdb->last_error
            ));
            return null;
        }
        
        return $result;
    }
    
    /**
     * Get count from any table
     */
    public function get_count($table_key, $where = '') {
        $table_name = $this->get_table($table_key);
        if (!$table_name) {
            return 0;
        }
        
        $sql = "SELECT COUNT(*) FROM $table_name";
        
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        
        $count = $this->wpdb->get_var($sql);
        
        if ($this->wpdb->last_error) {
            $this->log_error('Database count failed', array(
                'table' => $table_key,
                'sql' => $sql,
                'error' => $this->wpdb->last_error
            ));
            return 0;
        }
        
        return intval($count);
    }
    
    /**
     * Execute custom query
     */
    public function query($sql) {
        $result = $this->wpdb->query($sql);
        
        if ($result === false) {
            $this->log_error('Custom query failed', array(
                'sql' => $sql,
                'error' => $this->wpdb->last_error
            ));
        }
        
        return $result;
    }
    
    /**
     * Get custom results
     */
    public function get_custom_results($sql) {
        $results = $this->wpdb->get_results($sql);
        
        if ($this->wpdb->last_error) {
            $this->log_error('Custom select failed', array(
                'sql' => $sql,
                'error' => $this->wpdb->last_error
            ));
            return array();
        }
        
        return $results ?: array();
    }
    
    /**
     * Log database errors
     */
    private function log_error($message, $context = array()) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('WooDash Pro DB Error: ' . $message . ' - ' . json_encode($context));
        }
        
        // Also log to our logs table if it exists
        if (isset($this->tables['logs'])) {
            $this->wpdb->insert(
                $this->tables['logs'],
                array(
                    'level' => 'error',
                    'message' => $message,
                    'context' => json_encode($context),
                    'source' => 'database',
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                    'created_at' => current_time('mysql')
                )
            );
        }
    }
    
    /**
     * Clean up expired cache entries
     */
    public function cleanup_cache() {
        $table_name = $this->get_table('cache');
        if (!$table_name) {
            return false;
        }
        
        $sql = "DELETE FROM $table_name WHERE expires_at IS NOT NULL AND expires_at < NOW()";
        return $this->wpdb->query($sql);
    }
    
    /**
     * Get database statistics
     */
    public function get_stats() {
        $stats = array();
        
        foreach ($this->tables as $key => $table_name) {
            $count = $this->wpdb->get_var("SELECT COUNT(*) FROM $table_name");
            $stats[$key] = intval($count);
        }
        
        return $stats;
    }
    
    /**
     * Check if tables exist
     */
    public function tables_exist() {
        foreach ($this->tables as $key => $table_name) {
            $exists = $this->wpdb->get_var("SHOW TABLES LIKE '$table_name'");
            if (!$exists) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Drop all WooDash Pro tables (for uninstall)
     */
    public function drop_tables() {
        foreach ($this->tables as $table_name) {
            $this->wpdb->query("DROP TABLE IF EXISTS $table_name");
        }
        
        delete_option('woodash_db_version');
        return true;
    }
}