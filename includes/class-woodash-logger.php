<?php
/**
 * Logger class for WoodDash Pro
 *
 * @package WoodashPro
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

class Woodash_Logger {
    
    private static $log_file = null;
    private static $max_log_size = 10485760; // 10MB
    private static $max_log_files = 5;
    
    /**
     * Initialize the logger
     */
    public static function init() {
        self::$log_file = WP_CONTENT_DIR . '/woodash-debug.log';
        
        // Create logs directory if it doesn't exist
        $log_dir = dirname(self::$log_file);
        if (!file_exists($log_dir)) {
            wp_mkdir_p($log_dir);
        }
    }
    
    /**
     * Log a message
     *
     * @param string $message
     * @param array $context
     * @param string $level
     */
    public static function log($message, $context = array(), $level = 'info') {
        if (!self::should_log($level)) {
            return;
        }
        
        $timestamp = current_time('Y-m-d H:i:s');
        $formatted_message = self::format_message($timestamp, $level, $message, $context);
        
        self::write_to_file($formatted_message);
        self::rotate_logs_if_needed();
    }
    
    /**
     * Log info message
     *
     * @param string $message
     * @param array $context
     */
    public static function info($message, $context = array()) {
        self::log($message, $context, 'info');
    }
    
    /**
     * Log warning message
     *
     * @param string $message
     * @param array $context
     */
    public static function warning($message, $context = array()) {
        self::log($message, $context, 'warning');
    }
    
    /**
     * Log error message
     *
     * @param string $message
     * @param array $context
     */
    public static function error($message, $context = array()) {
        self::log($message, $context, 'error');
    }
    
    /**
     * Log debug message
     *
     * @param string $message
     * @param array $context
     */
    public static function debug($message, $context = array()) {
        self::log($message, $context, 'debug');
    }
    
    /**
     * Check if we should log at this level
     *
     * @param string $level
     * @return bool
     */
    private static function should_log($level) {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return false;
        }
        
        $min_level = get_option('woodash_log_level', 'info');
        $levels = array('debug' => 0, 'info' => 1, 'warning' => 2, 'error' => 3);
        
        return isset($levels[$level]) && isset($levels[$min_level]) 
               && $levels[$level] >= $levels[$min_level];
    }
    
    /**
     * Format log message
     *
     * @param string $timestamp
     * @param string $level
     * @param string $message
     * @param array $context
     * @return string
     */
    private static function format_message($timestamp, $level, $message, $context) {
        $formatted = "[{$timestamp}] [" . strtoupper($level) . "] [WoodDash Pro] {$message}";
        
        if (!empty($context)) {
            $formatted .= "\nContext: " . wp_json_encode($context, JSON_PRETTY_PRINT);
        }
        
        // Add memory usage and execution time
        $formatted .= sprintf(
            "\nMemory: %s | Peak: %s | Time: %s",
            size_format(memory_get_usage()),
            size_format(memory_get_peak_usage()),
            timer_stop()
        );
        
        $formatted .= "\n" . str_repeat('-', 80) . "\n";
        
        return $formatted;
    }
    
    /**
     * Write message to log file
     *
     * @param string $message
     */
    private static function write_to_file($message) {
        if (!self::$log_file) {
            self::init();
        }
        
        // Use WordPress filesystem if available
        if (function_exists('WP_Filesystem')) {
            global $wp_filesystem;
            if (WP_Filesystem()) {
                $existing_content = '';
                if ($wp_filesystem->exists(self::$log_file)) {
                    $existing_content = $wp_filesystem->get_contents(self::$log_file);
                }
                $wp_filesystem->put_contents(self::$log_file, $existing_content . $message, FS_CHMOD_FILE);
                return;
            }
        }
        
        // Fallback to file_put_contents
        if (is_writable(dirname(self::$log_file))) {
            file_put_contents(self::$log_file, $message, FILE_APPEND | LOCK_EX);
        }
    }
    
    /**
     * Rotate logs if file is too large
     */
    private static function rotate_logs_if_needed() {
        if (!file_exists(self::$log_file)) {
            return;
        }
        
        if (filesize(self::$log_file) > self::$max_log_size) {
            self::rotate_logs();
        }
    }
    
    /**
     * Rotate log files
     */
    private static function rotate_logs() {
        $base_path = pathinfo(self::$log_file, PATHINFO_DIRNAME) . '/' . pathinfo(self::$log_file, PATHINFO_FILENAME);
        $extension = pathinfo(self::$log_file, PATHINFO_EXTENSION);
        
        // Remove oldest log file
        $oldest_log = $base_path . '-' . self::$max_log_files . '.' . $extension;
        if (file_exists($oldest_log)) {
            unlink($oldest_log);
        }
        
        // Rotate existing log files
        for ($i = self::$max_log_files - 1; $i >= 1; $i--) {
            $old_file = $base_path . '-' . $i . '.' . $extension;
            $new_file = $base_path . '-' . ($i + 1) . '.' . $extension;
            
            if (file_exists($old_file)) {
                rename($old_file, $new_file);
            }
        }
        
        // Move current log to .1
        $new_current = $base_path . '-1.' . $extension;
        rename(self::$log_file, $new_current);
    }
    
    /**
     * Get log entries
     *
     * @param int $lines
     * @param string $level_filter
     * @return array
     */
    public static function get_log_entries($lines = 100, $level_filter = null) {
        if (!file_exists(self::$log_file)) {
            return array();
        }
        
        $content = file_get_contents(self::$log_file);
        $entries = explode(str_repeat('-', 80), $content);
        $entries = array_filter(array_map('trim', $entries));
        
        // Filter by level if specified
        if ($level_filter) {
            $entries = array_filter($entries, function($entry) use ($level_filter) {
                return strpos($entry, '[' . strtoupper($level_filter) . ']') !== false;
            });
        }
        
        // Get last N entries
        $entries = array_slice(array_reverse($entries), 0, $lines);
        
        return array_map(function($entry) {
            return self::parse_log_entry($entry);
        }, $entries);
    }
    
    /**
     * Parse a single log entry
     *
     * @param string $entry
     * @return array
     */
    private static function parse_log_entry($entry) {
        $lines = explode("\n", $entry);
        $header = $lines[0] ?? '';
        
        // Extract timestamp, level, and message from header
        if (preg_match('/\[(.*?)\]\s*\[(.*?)\]\s*\[WoodDash Pro\]\s*(.*)/', $header, $matches)) {
            return array(
                'timestamp' => $matches[1],
                'level' => strtolower($matches[2]),
                'message' => $matches[3],
                'full_entry' => $entry
            );
        }
        
        return array(
            'timestamp' => '',
            'level' => 'unknown',
            'message' => $entry,
            'full_entry' => $entry
        );
    }
    
    /**
     * Clear log file
     */
    public static function clear_logs() {
        if (file_exists(self::$log_file)) {
            file_put_contents(self::$log_file, '');
        }
        
        // Clear rotated logs too
        $base_path = pathinfo(self::$log_file, PATHINFO_DIRNAME) . '/' . pathinfo(self::$log_file, PATHINFO_FILENAME);
        $extension = pathinfo(self::$log_file, PATHINFO_EXTENSION);
        
        for ($i = 1; $i <= self::$max_log_files; $i++) {
            $log_file = $base_path . '-' . $i . '.' . $extension;
            if (file_exists($log_file)) {
                unlink($log_file);
            }
        }
    }
    
    /**
     * Get log file size
     *
     * @return string
     */
    public static function get_log_size() {
        if (!file_exists(self::$log_file)) {
            return '0 B';
        }
        
        return size_format(filesize(self::$log_file));
    }
    
    /**
     * Check if logging is enabled
     *
     * @return bool
     */
    public static function is_logging_enabled() {
        return defined('WP_DEBUG') && WP_DEBUG;
    }
}
