<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Check if user has required capabilities (e.g., manage options)
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'woodashh'));
}

// Handle form submission
if (isset($_POST['submit']) && isset($_POST['woodashh_settings_nonce'])) {
    if (!wp_verify_nonce($_POST['woodashh_settings_nonce'], 'woodashh_settings_action')) {
        wp_die(__('Security check failed. Please try again.', 'woodashh'));
    }

    // Get current user
    $current_user = wp_get_current_user();
    
    // Profile settings
    $user_display_name = isset($_POST['woodashh_display_name']) ? sanitize_text_field($_POST['woodashh_display_name']) : '';
    $user_email = isset($_POST['woodashh_email']) ? sanitize_email($_POST['woodashh_email']) : '';
    $user_phone = isset($_POST['woodashh_phone']) ? sanitize_text_field($_POST['woodashh_phone']) : '';
    $user_company = isset($_POST['woodashh_company']) ? sanitize_text_field($_POST['woodashh_company']) : '';
    $user_position = isset($_POST['woodashh_position']) ? sanitize_text_field($_POST['woodashh_position']) : '';
    $user_address = isset($_POST['woodashh_address']) ? sanitize_text_field($_POST['woodashh_address']) : '';
    $user_city = isset($_POST['woodashh_city']) ? sanitize_text_field($_POST['woodashh_city']) : '';
    $user_country = isset($_POST['woodashh_country']) ? sanitize_text_field($_POST['woodashh_country']) : '';
    $user_timezone = isset($_POST['woodashh_timezone']) ? sanitize_text_field($_POST['woodashh_timezone']) : '';
    $user_language = isset($_POST['woodashh_language']) ? sanitize_text_field($_POST['woodashh_language']) : '';
    $user_notifications = isset($_POST['woodashh_notifications']) ? array_map('sanitize_text_field', $_POST['woodashh_notifications']) : array('email' => 1, 'sms' => 0);

    // API settings
    $woodashh_api_key = isset($_POST['woodashh_api_key']) ? sanitize_text_field($_POST['woodashh_api_key']) : '';
    $woodashh_api_endpoint = isset($_POST['woodashh_api_endpoint']) ? esc_url_raw($_POST['woodashh_api_endpoint']) : '';

    // Enhanced Validation
    $errors = [];
    
    // Profile validation
    if (empty($user_display_name)) {
        $errors[] = __('Display name cannot be empty.', 'woodashh');
    }
    
    if (empty($user_email)) {
        $errors[] = __('Email cannot be empty.', 'woodashh');
    } elseif (!is_email($user_email)) {
        $errors[] = __('Please enter a valid email address.', 'woodashh');
    }
    
    if (!empty($user_phone) && !preg_match('/^[0-9\-\+\(\)\s]*$/', $user_phone)) {
        $errors[] = __('Please enter a valid phone number.', 'woodashh');
    }
    
    // API validation
    if (empty($woodashh_api_key)) {
        $errors[] = __('API Key cannot be empty.', 'woodashh');
    } elseif (strlen($woodashh_api_key) < 32) {
        $errors[] = __('API Key must be at least 32 characters long.', 'woodashh');
    }
    
    if (!empty($woodashh_api_endpoint)) {
        if (!filter_var($woodashh_api_endpoint, FILTER_VALIDATE_URL)) {
            $errors[] = __('API Endpoint must be a valid URL.', 'woodashh');
        } elseif (!preg_match('/^https?:\/\//', $woodashh_api_endpoint)) {
            $errors[] = __('API Endpoint must start with http:// or https://', 'woodashh');
        }
    }

    if (empty($errors)) {
        // Update user profile
        $user_data = array(
            'ID' => $current_user->ID,
            'display_name' => $user_display_name,
            'user_email' => $user_email
        );
        
        $user_update = wp_update_user($user_data);
        
        if (is_wp_error($user_update)) {
            $errors[] = $user_update->get_error_message();
        } else {
            // Update user meta
            update_user_meta($current_user->ID, 'woodashh_phone', $user_phone);
            update_user_meta($current_user->ID, 'woodashh_company', $user_company);
            update_user_meta($current_user->ID, 'woodashh_position', $user_position);
            update_user_meta($current_user->ID, 'woodashh_address', $user_address);
            update_user_meta($current_user->ID, 'woodashh_city', $user_city);
            update_user_meta($current_user->ID, 'woodashh_country', $user_country);
            update_user_meta($current_user->ID, 'woodashh_timezone', $user_timezone);
            update_user_meta($current_user->ID, 'woodashh_language', $user_language);
            update_user_meta($current_user->ID, 'woodashh_notifications', $user_notifications);
            
            // Save API settings
            $update_results = array(
                'woodashh_api_key' => update_option('woodashh_api_key', $woodashh_api_key),
                'woodashh_api_endpoint' => update_option('woodashh_api_endpoint', $woodashh_api_endpoint)
            );

            if (in_array(false, $update_results, true)) {
                $errors[] = __('Some settings could not be saved. Please try again.', 'woodashh');
            } else {
                // Log successful update
                error_log(sprintf('[WooDash Pro] Settings updated by user %s', $current_user->user_login));
                
                // Redirect to prevent form resubmission and show success message
                $redirect_url = add_query_arg(['settings_updated' => 'true'], admin_url('admin.php?page=woodashh-settings'));
                wp_redirect($redirect_url);
                exit;
            }
        }
    }
    
    // Display errors if any
    if (!empty($errors)) {
        echo '<div class="notice notice-error is-dismissible">';
        foreach ($errors as $error) {
            echo '<p>' . esc_html($error) . '</p>';
        }
        echo '</div>';
    }
}

// Display settings updated message with enhanced styling
if (isset($_GET['settings_updated']) && $_GET['settings_updated'] === 'true') {
    echo '<div class="notice notice-success is-dismissible woodash-notice">';
    echo '<p><i class="fas fa-check-circle"></i> ' . __('Settings saved successfully.', 'woodashh') . '</p>';
    echo '</div>';
}

// Get current user data
$current_user = wp_get_current_user();
$current_display_name = $current_user->display_name;
$current_email = $current_user->user_email;
$current_phone = get_user_meta($current_user->ID, 'woodashh_phone', true);
$current_company = get_user_meta($current_user->ID, 'woodashh_company', true);
$current_position = get_user_meta($current_user->ID, 'woodashh_position', true);
$current_address = get_user_meta($current_user->ID, 'woodashh_address', true);
$current_city = get_user_meta($current_user->ID, 'woodashh_city', true);
$current_country = get_user_meta($current_user->ID, 'woodashh_country', true);
$current_timezone = get_user_meta($current_user->ID, 'woodashh_timezone', true);
$current_language = get_user_meta($current_user->ID, 'woodashh_language', true);
$current_notifications = get_user_meta($current_user->ID, 'woodashh_notifications', true) ?: array('email' => 1, 'sms' => 0);

// Get API settings
$current_api_key = get_option('woodashh_api_key', '');
$current_api_endpoint = get_option('woodashh_api_endpoint', '');

// Get timezone list
$timezones = DateTimeZone::listIdentifiers();

// Get available languages
$languages = array(
    'en_US' => 'English (US)',
    'es_ES' => 'Español',
    'fr_FR' => 'Français',
    'de_DE' => 'Deutsch',
    'it_IT' => 'Italiano',
    'pt_BR' => 'Português',
    'ru_RU' => 'Русский',
    'zh_CN' => '中文',
    'ja_JP' => '日本語',
    'ko_KR' => '한국어'
);

// Determine the body class based on color mode setting
$body_class = $current_color_mode === 'dark' ? 'woodash-dark-mode' : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings Dashboard</title>
    <!-- Google Fonts & Font Awesome for icons -->
    <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js (Included as it's a common resource in the dashboard style) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Base Styles */
    :root {
        --primary-color: #00CC61;
        --primary-dark: #00b357;
        --secondary-color: #6B7280;
        --background-color: #F8FAFC;
        --card-background: rgba(255, 255, 255, 0.9);
        --border-color: rgba(0, 0, 0, 0.1);
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --transition-base: all 0.3s ease;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--background-color);
        color: #374151;
        line-height: 1.5;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Dark Mode Styles */
    .woodash-dark-mode {
        --background-color: #1F2937;
        --card-background: rgba(55, 65, 81, 0.9);
        --border-color: rgba(255, 255, 255, 0.1);
        color: #E5E7EB;
    }

    .woodash-dark-mode .woodash-gradient-text {
         background: linear-gradient(45deg, #3B82F6, #60A5FA);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .woodash-dark-mode .woodash-sidebar {
         background: rgba(31, 41, 55, 0.95);
         border-color: rgba(255, 255, 255, 0.05);
    }

    .woodash-dark-mode .woodash-nav-link {
         color: #D1D5DB;
    }

     .woodash-dark-mode .woodash-nav-link:hover,
    .woodash-dark-mode .woodash-nav-link.active {
        background: rgba(59, 130, 246, 0.15);
        color: #93C5FD;
    }

    .woodash-dark-mode .woodash-nav-link i.fa-chevron-right {
         color: #6B7280;
    }

    .woodash-dark-mode .woodash-card {
         background: var(--card-background);
         border-color: var(--border-color);
         box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
    }

     .woodash-dark-mode .woodash-btn-secondary {
         background: rgba(55, 65, 81, 0.8);
         color: #E5E7EB;
         border-color: rgba(255, 255, 255, 0.15);
     }

     .woodash-dark-mode .woodash-btn-secondary:hover {
         background: rgba(55, 65, 81, 0.95);
         border-color: #93C5FD;
         color: #93C5FD;
     }

    .woodash-dark-mode .woodash-table th {
         background: rgba(55, 65, 81, 0.9);
         border-color: rgba(255, 255, 255, 0.1);
         color: #9CA3AF;
    }

    .woodash-dark-mode .woodash-table td {
         border-color: rgba(255, 255, 255, 0.1);
    }

     .woodash-dark-mode .woodash-table tr:hover {
        background: rgba(59, 130, 246, 0.1);
    }

    .woodash-dark-mode .woodash-form-label {
         color: #D1D5DB;
    }

    .woodash-dark-mode .woodash-form-input {
         background: #374151;
         color: #E5E7EB;
         border-color: rgba(255, 255, 255, 0.1);
    }

    .woodash-dark-mode .woodash-form-input:focus {
         border-color: #60A5FA;
         box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }

     .woodash-dark-mode input[type="checkbox"].text-[#00CC61]:checked {
          background-color: #3B82F6;
          border-color: #3B82F6;
     }

     .woodash-dark-mode input[type="checkbox"].text-[#00CC61]:focus {
          box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
     }

    /* Style for the mode buttons */
    .woodash-mode-button {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        border: 1px solid var(--border-color);
        background-color: var(--card-background);
        color: var(--secondary-color);
        transition: all 0.2s ease;
    }

    .woodash-mode-button.active {
         background-color: var(--primary-color);
         color: white;
         border-color: var(--primary-color);
    }

     .woodash-dark-mode .woodash-mode-button {
         background-color: rgba(55, 65, 81, 0.8);
         color: #D1D5DB;
         border-color: rgba(255, 255, 255, 0.15);
     }

     .woodash-dark-mode .woodash-mode-button.active {
          background-color: #3B82F6;
          border-color: #3B82F6;
          color: white;
     }

    /* Hide WordPress admin bar and menu */
    #wpadminbar,
    #adminmenumain,
    #adminmenuwrap {
        display: none !important;
    }

    /* Utility Classes */
    .woodash-fullscreen {
        min-height: 100vh;
        width: 100%;
    }

    .woodash-glass-effect {
        background: var(--card-background);
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
    }

    .woodash-gradient-text {
        background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .woodash-glow {
        box-shadow: 0 0 15px rgba(0, 204, 97, 0.3);
    }

    .woodash-float {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    /* Sidebar Styles */
    .woodash-sidebar {
        transition: transform 0.3s ease;
        z-index: 50;
        flex-shrink: 0;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        overflow-y: auto;
        width: 16rem;
        background: var(--card-background);
        border-right: 1px solid var(--border-color);
        padding: 1.5rem;
    }

    .woodash-nav-link {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        color: var(--secondary-color);
        transition: var(--transition-base);
    }

    .woodash-nav-link:hover,
    .woodash-nav-link.active {
        background: rgba(0, 204, 97, 0.1);
        color: var(--primary-dark);
    }

    /* Main Content Area */
    .woodash-main {
        margin-left: 16rem;
        transition: margin-left 0.3s ease;
        flex-grow: 1;
        overflow-y: auto;
    }

    /* Card Styles */
    .woodash-card {
        background: var(--card-background);
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        transition: var(--transition-base);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .woodash-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* Button Styles */
    .woodash-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-size: 1rem;
        font-weight: 500;
        transition: var(--transition-base);
        cursor: pointer;
        gap: 0.5rem;
    }

    .woodash-btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border: none;
        box-shadow: var(--shadow-md);
    }

    .woodash-btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    }

    .woodash-btn-secondary {
        background: rgba(255, 255, 255, 0.8);
        color: #374151;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
    }

    .woodash-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.95);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    /* Table Styles */
    .woodash-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .woodash-table th,
    .woodash-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    .woodash-table th {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        position: sticky;
        top: 0;
        z-index: 10;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #6B7280;
    }

    .woodash-table tbody tr:last-child td {
        border-bottom: none;
    }

    .woodash-table tr {
        transition: var(--transition-base);
    }

    .woodash-table tr:hover {
        background: rgba(0, 204, 97, 0.05);
    }

    /* Badge Styles */
    .woodash-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .woodash-badge-success {
        background-color: #D1FAE5;
        color: #065F46;
    }

    .woodash-badge-warning {
        background-color: #FEF3C7;
        color: #92400E;
    }

    .woodash-badge-danger {
        background-color: #FEE2E2;
        color: #991B1B;
    }

    /* Modal Styles */
    .woodash-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
    }

    .woodash-modal-content {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        max-width: 32rem;
        width: 100%;
        margin: 1rem;
    }

    /* Form Styles */
    .woodash-form-group {
        margin-bottom: 1rem;
    }

    .woodash-form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .woodash-form-input {
        width: 100%;
        padding: 0.625rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: var(--transition-base);
    }

    .woodash-form-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 204, 97, 0.1);
    }

    .woodash-form-input.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
    }

    .woodash-notice {
        transition: opacity 0.3s ease;
    }

    .loading .button-text {
        display: none;
    }

    .loading .loading-spinner {
        display: inline-block;
    }

    .woodash-btn.loading {
        opacity: 0.8;
        cursor: not-allowed;
    }

    /* Enhanced dark mode styles */
    .woodash-dark-mode .woodash-form-input.error {
        border-color: #f87171;
        box-shadow: 0 0 0 2px rgba(248, 113, 113, 0.2);
    }

    .woodash-dark-mode .woodash-notice {
        background-color: rgba(31, 41, 55, 0.9);
        border-color: rgba(255, 255, 255, 0.1);
    }
</style>
</head>
<body class="<?php echo esc_attr($body_class); ?>">

<div id="woodash-dashboard" class="woodash-fullscreen flex">
    <!-- Sidebar -->
    <aside class="woodash-sidebar woodash-glass-effect">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center woodash-glow">
                <i class="fa-solid fa-gear text-white"></i>
            </div>
            <h2 class="text-xl font-bold woodash-gradient-text">WooDash Pro</h2>
        </div>

        <nav class="space-y-1">
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-dashboard')); ?>"
               class="woodash-nav-link woodash-hover-card group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-gauge text-white"></i>
                    </div>
                    <span>Dashboard</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-products')); ?>"
               class="woodash-nav-link woodash-hover-card group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-box text-white"></i>
                    </div>
                    <span>Products</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-customers')); ?>"
               class="woodash-nav-link woodash-hover-card group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-users text-white"></i>
                    </div>
                    <span>Customers</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-stock')); ?>"
               class="woodash-nav-link woodash-hover-card group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-boxes-stacked text-white"></i>
                    </div>
                    <span>Stock</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-reviews')); ?>"
               class="woodash-nav-link woodash-hover-card group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-star text-white"></i>
                    </div>
                    <span>Reviews</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-coupons')); ?>"
               class="woodash-nav-link woodash-hover-card group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-ticket text-white"></i>
                    </div>
                    <span>Coupons</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-settings')); ?>"
               class="woodash-nav-link active woodash-hover-card group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-gear text-white"></i>
                    </div>
                    <span>Settings</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>
            <!-- User Profile -->
                <div class="fixed bottom-0 left-0 p-4 bg-white/90 border-t border-r border-gray-100 rounded-tr-lg shadow-lg woodash-glass-effect" style="z-index:1000;">
                    <div class="flex items-center gap-3 px-2">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center">
                            <i class="fa-solid fa-user text-white"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">Admin User</p>
                            <p class="text-xs text-gray-500 truncate">admin@example.com</p>
                        </div>
                        <button class="p-1 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <i class="fa-solid fa-ellipsis-vertical text-gray-400"></i>
                        </button>
                    </div>
                </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="woodash-main flex-1 p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold woodash-gradient-text">Settings</h1>
                    <p class="text-gray-500">Configure your WooDash Pro settings</p>
                </div>
            </header>

            <form action="" method="post">
                <?php wp_nonce_field('woodashh_settings_action', 'woodashh_settings_nonce'); ?>

                <!-- Profile Settings -->
                <div class="woodash-card p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h3>
                    <p class="text-gray-600 mb-6">Update your personal information and preferences.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="col-span-2">
                            <h4 class="text-md font-medium text-gray-700 mb-4">Basic Information</h4>
                        </div>

                        <!-- Display Name -->
                        <div class="woodash-form-group">
                            <label for="woodashh_display_name" class="woodash-form-label">Display Name</label>
                            <input type="text" id="woodashh_display_name" name="woodashh_display_name" 
                                   class="woodash-form-input" 
                                   placeholder="Enter your display name" 
                                   value="<?php echo esc_attr($current_display_name); ?>"
                                   required>
                        </div>

                        <!-- Email -->
                        <div class="woodash-form-group">
                            <label for="woodashh_email" class="woodash-form-label">Email Address</label>
                            <input type="email" id="woodashh_email" name="woodashh_email" 
                                   class="woodash-form-input" 
                                   placeholder="Enter your email" 
                                   value="<?php echo esc_attr($current_email); ?>"
                                   required>
                        </div>

                        <!-- Phone -->
                        <div class="woodash-form-group">
                            <label for="woodashh_phone" class="woodash-form-label">Phone Number</label>
                            <input type="tel" id="woodashh_phone" name="woodashh_phone" 
                                   class="woodash-form-input" 
                                   placeholder="Enter your phone number" 
                                   value="<?php echo esc_attr($current_phone); ?>"
                                   pattern="[0-9\-\+\(\)\s]*">
                            <p class="text-gray-500 text-sm mt-1">Optional: Include your country code</p>
                        </div>

                        <!-- Position -->
                        <div class="woodash-form-group">
                            <label for="woodashh_position" class="woodash-form-label">Position</label>
                            <input type="text" id="woodashh_position" name="woodashh_position" 
                                   class="woodash-form-input" 
                                   placeholder="Enter your position" 
                                   value="<?php echo esc_attr($current_position); ?>">
                            <p class="text-gray-500 text-sm mt-1">Your job title or position in the company</p>
                        </div>

                        <!-- Company Information -->
                        <div class="col-span-2 mt-4">
                            <h4 class="text-md font-medium text-gray-700 mb-4">Company Information</h4>
                        </div>

                        <!-- Company -->
                        <div class="woodash-form-group">
                            <label for="woodashh_company" class="woodash-form-label">Company Name</label>
                            <input type="text" id="woodashh_company" name="woodashh_company" 
                                   class="woodash-form-input" 
                                   placeholder="Enter your company name" 
                                   value="<?php echo esc_attr($current_company); ?>">
                        </div>

                        <!-- Address -->
                        <div class="woodash-form-group">
                            <label for="woodashh_address" class="woodash-form-label">Address</label>
                            <input type="text" id="woodashh_address" name="woodashh_address" 
                                   class="woodash-form-input" 
                                   placeholder="Enter your address" 
                                   value="<?php echo esc_attr($current_address); ?>">
                        </div>

                        <!-- City -->
                        <div class="woodash-form-group">
                            <label for="woodashh_city" class="woodash-form-label">City</label>
                            <input type="text" id="woodashh_city" name="woodashh_city" 
                                   class="woodash-form-input" 
                                   placeholder="Enter your city" 
                                   value="<?php echo esc_attr($current_city); ?>">
                        </div>

                        <!-- Country -->
                        <div class="woodash-form-group">
                            <label for="woodashh_country" class="woodash-form-label">Country</label>
                            <select id="woodashh_country" name="woodashh_country" class="woodash-form-input">
                                <option value="">Select your country</option>
                                <?php
                                $countries = array(
                                    'US' => 'United States',
                                    'GB' => 'United Kingdom',
                                    'CA' => 'Canada',
                                    'AU' => 'Australia',
                                    'DE' => 'Germany',
                                    'FR' => 'France',
                                    'IT' => 'Italy',
                                    'ES' => 'Spain',
                                    'JP' => 'Japan',
                                    'CN' => 'China',
                                    // Add more countries as needed
                                );
                                foreach ($countries as $code => $name) {
                                    printf(
                                        '<option value="%s" %s>%s</option>',
                                        esc_attr($code),
                                        selected($current_country, $code, false),
                                        esc_html($name)
                                    );
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Preferences -->
                        <div class="col-span-2 mt-4">
                            <h4 class="text-md font-medium text-gray-700 mb-4">Preferences</h4>
                        </div>

                        <!-- Timezone -->
                        <div class="woodash-form-group">
                            <label for="woodashh_timezone" class="woodash-form-label">Timezone</label>
                            <select id="woodashh_timezone" name="woodashh_timezone" class="woodash-form-input">
                                <?php
                                foreach ($timezones as $timezone) {
                                    printf(
                                        '<option value="%s" %s>%s</option>',
                                        esc_attr($timezone),
                                        selected($current_timezone, $timezone, false),
                                        esc_html($timezone)
                                    );
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Language -->
                        <div class="woodash-form-group">
                            <label for="woodashh_language" class="woodash-form-label">Language</label>
                            <select id="woodashh_language" name="woodashh_language" class="woodash-form-input">
                                <?php
                                foreach ($languages as $code => $name) {
                                    printf(
                                        '<option value="%s" %s>%s</option>',
                                        esc_attr($code),
                                        selected($current_language, $code, false),
                                        esc_html($name)
                                    );
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Notification Preferences -->
                        <div class="col-span-2 woodash-form-group">
                            <label class="woodash-form-label">Notification Preferences</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="notify_email" name="woodashh_notifications[email]" 
                                           value="1" <?php checked(isset($current_notifications['email']) && $current_notifications['email']); ?>
                                           class="rounded border-gray-300 text-[#00CC61] focus:ring-[#00CC61]">
                                    <label for="notify_email" class="ml-2 text-sm text-gray-700">Email Notifications</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="notify_sms" name="woodashh_notifications[sms]" 
                                           value="1" <?php checked(isset($current_notifications['sms']) && $current_notifications['sms']); ?>
                                           class="rounded border-gray-300 text-[#00CC61] focus:ring-[#00CC61]">
                                    <label for="notify_sms" class="ml-2 text-sm text-gray-700">SMS Notifications</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- API Configuration -->
                <div class="woodash-card p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">API Configuration</h3>
                    <p class="text-gray-600 mb-6">Configure your API settings for WooDash Pro integration.</p>

                    <!-- API Key Setting with enhanced security -->
                    <div class="woodash-form-group">
                        <label for="woodashh_api_key" class="woodash-form-label">API Key</label>
                        <div class="relative">
                            <input type="password" id="woodashh_api_key" name="woodashh_api_key" 
                                   class="woodash-form-input pr-10" 
                                   placeholder="Enter your API key" 
                                   value="<?php echo esc_attr($current_api_key); ?>"
                                   minlength="32"
                                   pattern="[A-Za-z0-9-_=]+"
                                   title="API Key must be at least 32 characters long and contain only letters, numbers, hyphens, and underscores">
                            <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700" 
                                    onclick="togglePasswordVisibility('woodashh_api_key')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">Your API key for WooDash Pro services. Must be at least 32 characters long.</p>
                    </div>

                    <!-- API Endpoint Setting with enhanced validation -->
                    <div class="woodash-form-group">
                        <label for="woodashh_api_endpoint" class="woodash-form-label">API Endpoint</label>
                        <input type="url" id="woodashh_api_endpoint" name="woodashh_api_endpoint" 
                               class="woodash-form-input" 
                               placeholder="https://api.woodashpro.com/v1" 
                               value="<?php echo esc_attr($current_api_endpoint); ?>"
                               pattern="https?://.+"
                               title="API Endpoint must be a valid URL starting with http:// or https://">
                        <p class="text-gray-500 text-sm mt-1">The endpoint URL for WooDash Pro API services. Must start with http:// or https://</p>
                    </div>
                </div>

                <!-- Save Changes Button with loading state -->
                <button type="submit" name="submit" id="submit" class="woodash-btn woodash-btn-primary mt-6" onclick="this.classList.add('loading')">
                    <span class="button-text">Save Changes</span>
                    <span class="loading-spinner hidden">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>
            </form>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const body = document.body;
    const colorModeInput = document.getElementById('woodashh_color_mode');
    const modeButtons = document.querySelectorAll('.woodash-mode-button');

    // Function to apply/remove dark mode class and update hidden input
    function setColorMode(mode) {
        if (mode === 'dark') {
            body.classList.add('woodash-dark-mode');
            colorModeInput.value = 'dark';
        } else {
            body.classList.remove('woodash-dark-mode');
            colorModeInput.value = 'light';
        }
        // Update active button class
        modeButtons.forEach(button => {
            if (button.dataset.mode === mode) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });
    }

    // Apply initial color mode based on hidden input value
    setColorMode(colorModeInput?.value || 'light'); // Default to light if value is empty

    // Add event listeners to mode buttons
    modeButtons.forEach(button => {
        button.addEventListener('click', function() {
            setColorMode(this.dataset.mode);
        });
    });

    // Password visibility toggle
    window.togglePasswordVisibility = function(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    };
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const apiKey = document.getElementById('woodashh_api_key');
        const apiEndpoint = document.getElementById('woodashh_api_endpoint');
        let isValid = true;
        
        // Validate API Key
        if (apiKey.value.length < 32) {
            apiKey.classList.add('error');
            isValid = false;
        } else {
            apiKey.classList.remove('error');
        }
        
        // Validate API Endpoint
        if (apiEndpoint.value && !apiEndpoint.value.match(/^https?:\/\//)) {
            apiEndpoint.classList.add('error');
            isValid = false;
        } else {
            apiEndpoint.classList.remove('error');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Please check the form for errors before submitting.');
        }
    });
    
    // Auto-dismiss notices after 5 seconds
    const notices = document.querySelectorAll('.woodash-notice');
    notices.forEach(notice => {
        setTimeout(() => {
            notice.style.opacity = '0';
            setTimeout(() => notice.remove(), 300);
        }, 5000);
    });
});
</script>

</body>
</html>
