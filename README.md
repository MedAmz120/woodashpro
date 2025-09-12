# WooDash Pro - WordPress Plugin

A comprehensive WooCommerce analytics dashboard plugin that provides advanced insights and reporting capabilities for your online store.

## Features

- **Advanced Analytics Dashboard**: Real-time sales, orders, and customer analytics
- **Multi-Page Navigation**: Seamless single-page application experience
- **REST API Integration**: Modern API endpoints for data retrieval
- **Export Functionality**: Export data in CSV and JSON formats
- **Responsive Design**: Mobile-friendly interface
- **Chart Visualizations**: Interactive charts using Chart.js
- **Customer Insights**: Detailed customer behavior analysis
- **Product Performance**: Track top-performing products
- **Inventory Management**: Monitor stock levels and alerts
- **Custom Reports**: Generate detailed business reports

## Requirements

- WordPress 5.0 or higher
- WooCommerce 3.0 or higher  
- PHP 7.4 or higher
- MySQL 5.6 or higher

## Installation

1. Upload the plugin files to `/wp-content/plugins/woodashpro7/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to 'WooDash Pro' in the admin menu
4. Follow the setup wizard to connect your store

## File Structure

```
woodashpro7/
├── woodashpro.php              # Main plugin file
├── uninstall.php               # Uninstall cleanup script
├── readme.txt                  # WordPress repository readme
├── includes/                   # Core plugin classes
│   ├── class-woodash-loader.php
│   ├── class-woodash-admin.php
│   ├── class-woodash-frontend.php
│   ├── class-woodash-api.php
│   └── helpers.php
├── templates/                  # Template files
│   └── dashboard.php
├── css/                       # Stylesheets
│   ├── admin.css
│   └── frontend.css
├── js/                        # JavaScript files
│   ├── admin.js
│   └── frontend.js
└── languages/                 # Translation files
    └── woodashpro.pot
```

## Configuration

### Basic Setup

1. Install and activate the plugin
2. Navigate to **WooDash Pro > Activate**
3. Enter your store details:
   - Store Name
   - Email Address
   - License Key (if applicable)
4. Click "Connect Now"

### Dashboard Navigation

The dashboard includes the following sections:

- **Dashboard**: Overview metrics and quick stats
- **Analytics**: Detailed sales and performance charts
- **Products**: Product performance and inventory
- **Orders**: Order management and tracking
- **Customers**: Customer insights and behavior
- **Inventory**: Stock levels and alerts
- **Reports**: Custom report generation
- **Settings**: Plugin configuration options

### Keyboard Shortcuts

- `Ctrl/Cmd + 1`: Navigate to Dashboard
- `Ctrl/Cmd + 2`: Navigate to Analytics  
- `Ctrl/Cmd + R`: Refresh current page data

## API Endpoints

The plugin provides REST API endpoints for data access:

### Analytics
- `GET /wp-json/woodash/v1/analytics/overview`
- `GET /wp-json/woodash/v1/analytics/sales`
- `GET /wp-json/woodash/v1/analytics/products`

### Data Export
- `GET /wp-json/woodash/v1/export/sales`
- `GET /wp-json/woodash/v1/export/products`
- `GET /wp-json/woodash/v1/export/customers`

### System
- `GET /wp-json/woodash/v1/system/status`
- `POST /wp-json/woodash/v1/system/connect`

## Hooks and Filters

### Actions

```php
// Before dashboard loads
do_action('woodash_before_dashboard');

// After dashboard loads  
do_action('woodash_after_dashboard');

// Before data export
do_action('woodash_before_export', $type, $format);

// After successful connection
do_action('woodash_connected', $connection_data);
```

### Filters

```php
// Modify dashboard metrics
apply_filters('woodash_dashboard_metrics', $metrics);

// Customize export data
apply_filters('woodash_export_data', $data, $type);

// Modify API response
apply_filters('woodash_api_response', $response, $endpoint);
```

## Customization

### Adding Custom Metrics

```php
function custom_woodash_metrics($metrics) {
    $metrics['custom_metric'] = array(
        'label' => 'Custom Metric',
        'value' => get_custom_value(),
        'format' => 'currency'
    );
    return $metrics;
}
add_filter('woodash_dashboard_metrics', 'custom_woodash_metrics');
```

### Custom Export Formats

```php
function custom_export_format($data, $type) {
    if ($type === 'custom') {
        // Process custom export data
        return process_custom_data($data);
    }
    return $data;
}
add_filter('woodash_export_data', 'custom_export_format', 10, 2);
```

## Development

### Local Development Setup

1. Clone the repository
2. Install WordPress and WooCommerce
3. Symlink the plugin directory to `wp-content/plugins/`
4. Activate the plugin
5. Install development dependencies

### CSS/JS Development

- CSS files are in `/css/` directory
- JavaScript files are in `/js/` directory  
- Use WordPress enqueue system for loading assets
- Follow WordPress coding standards

### Database Schema

The plugin uses WordPress native tables and options:

- `wp_options`: Plugin settings and cache
- `wp_usermeta`: User preferences  
- `wp_posts`: WooCommerce orders and products
- `wp_postmeta`: Order and product metadata

## Troubleshooting

### Common Issues

1. **Plugin not loading**: Check WooCommerce is installed and activated
2. **Missing data**: Verify WooCommerce orders exist
3. **Permission errors**: Ensure user has `manage_woocommerce` capability
4. **Charts not displaying**: Check Chart.js library is loaded

### Debug Mode

Enable debug mode by adding to `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

Check logs at `/wp-content/woodash-debug.log`

### System Requirements Check

The plugin includes built-in system checks:

- WordPress version compatibility
- WooCommerce presence and version
- PHP version and extensions
- File permissions

## Security

- All AJAX requests use WordPress nonces
- REST API endpoints require proper authentication
- User capabilities are checked for all operations
- Data is sanitized and validated on input
- Output is escaped for XSS protection

## Performance

- Data caching with WordPress transients
- Lazy loading for large datasets
- Optimized database queries
- Asset minification in production
- Background processing for heavy operations

## Changelog

### Version 1.0.0
- Initial release
- Dashboard with analytics overview
- Multi-page navigation system
- REST API endpoints
- Export functionality
- Mobile-responsive design

## Support

For support and documentation:
- Plugin documentation: Available in WordPress admin
- Support forum: WordPress.org plugin page
- Bug reports: GitHub issues (if applicable)

## License

This plugin is licensed under the GPL v2 or later.

## Credits

- Chart.js for data visualizations
- WordPress and WooCommerce communities
- Contributors and testers
