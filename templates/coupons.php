<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Check if user has required capabilities
if (!current_user_can('manage_woocommerce')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'woodashh'));
}

// Retrieve current color mode setting
$current_color_mode = get_option('woodashh_color_mode', 'light'); // Default to light

// Determine the body class based on color mode setting
$body_class = $current_color_mode === 'dark' ? 'woodash-dark-mode' : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupons Dashboard</title>
    <!-- Google Fonts & Font Awesome for icons -->
    <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js -->
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
        background: rgba(0, 0, 0, 0.6); /* Slightly darker overlay */
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
        padding: 1rem; /* Add padding for smaller screens */
        overflow-y: auto; /* Allow scrolling if content overflows */
    }

    .woodash-modal-content {
        background: var(--card-background); /* Use card background variable */
        border-radius: 1rem;
        padding: 1.5rem 2rem; /* Adjust padding */
        max-width: 40rem; /* Increase max width */
        width: 100%;
        /* margin: 1rem; */ /* Remove fixed margin, use modal padding instead */
        box-shadow: var(--shadow-lg); /* Use larger shadow */
        position: relative; /* Needed for close button absolute positioning */
    }

    .woodash-modal-content .flex.justify-between.items-center.mb-4 {
        margin-bottom: 1.5rem; /* Increase space below header */
    }

    .woodash-modal-content .text-lg.font-semibold.text-gray-900 {
        color: #1F2937; /* Darker text for header */
    }

     .woodash-dark-mode .woodash-modal-content .text-lg.font-semibold.text-gray-900 {
         color: #F3F4F6; /* Lighter text in dark mode */
     }

    .woodash-modal-content #close-coupon-modal {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        font-size: 1.25rem;
        color: #6B7280;
        transition: color 0.2s ease;
    }

    .woodash-modal-content #close-coupon-modal:hover {
        color: #374151;
    }

     .woodash-dark-mode .woodash-modal-content #close-coupon-modal {
         color: #9CA3AF;
     }

     .woodash-dark-mode .woodash-modal-content #close-coupon-modal:hover {
         color: #D1D5DB;
     }

    /* Form Styles (Adjusted) */
    .woodash-form-group {
        margin-bottom: 1.25rem; /* Increase space between form groups */
    }

    .woodash-form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600; /* Make label bolder */
        color: #374151;
        margin-bottom: 0.75rem; /* Increase space below label */
    }

     .woodash-dark-mode .woodash-form-label {
         color: #D1D5DB;
     }

    .woodash-form-input {
        width: 100%;
        padding: 0.75rem 1rem; /* Increase padding */
        border: 1px solid var(--border-color); /* Use border variable */
        border-radius: 0.5rem;
        font-size: 1rem; /* Increase font size */
        transition: var(--transition-base);
        background-color: white; /* Ensure white background in light mode */
        color: #374151; /* Ensure dark text in light mode */
    }

    .woodash-dark-mode .woodash-form-input {
         background-color: #374151; /* Dark background in dark mode */
         color: #E5E7EB; /* Light text in dark mode */
         border-color: rgba(255, 255, 255, 0.1); /* Light border in dark mode */
    }

    .woodash-form-input:focus {
        outline: none;
        border-color: var(--primary-color); /* Primary color border on focus */
        box-shadow: 0 0 0 3px rgba(0, 204, 97, 0.2); /* Primary color glow */
    }

    .woodash-dark-mode .woodash-form-input:focus {
         border-color: #60A5FA; /* Blue border on focus in dark mode */
         box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3); /* Blue glow in dark mode */
    }

    .woodash-form-input.invalid {
        border-color: #EF4444; /* Red border for invalid */
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.3); /* Red glow for invalid */
    }

    .woodash-form-input.invalid:focus {
         border-color: #EF4444;
         box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.3);
    }

    .woodash-form-group .text-red-600 {
        font-size: 0.875rem; /* Increase font size for error messages */
        margin-top: 0.5rem; /* Increase space above error message */
        color: #EF4444; /* Ensure red color */
    }

    /* Style for multi-selects (basic styling) */
    .woodash-form-input[multiple] {
        min-height: 8rem; /* Give multi-selects a minimum height */
        padding: 0.75rem 1rem; /* Adjust padding */
    }

    .woodash-form-checkbox {
        width: 1.25rem; /* Increase checkbox size */
        height: 1.25rem; /* Increase checkbox size */
        border-radius: 0.25rem;
        border: 1px solid var(--border-color);
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: white;
        cursor: pointer;
        position: relative;
        transition: var(--transition-base);
        flex-shrink: 0; /* Prevent checkbox from shrinking */
    }

    .woodash-dark-mode .woodash-form-checkbox {
         background-color: #374151;
         border-color: rgba(255, 255, 255, 0.1);
    }

    .woodash-form-checkbox:checked {
        background-color: var(--primary-color); /* Primary color when checked */
        border-color: var(--primary-color);
    }

    .woodash-dark-mode .woodash-form-checkbox:checked {
         background-color: #3B82F6; /* Blue when checked in dark mode */
         border-color: #3B82F6;
    }

    .woodash-form-checkbox:checked::after {
        content: '';
        position: absolute;
        left: 0.25rem;
        top: 0.125rem;
        width: 0.3rem; /* Adjust checkmark size */
        height: 0.6rem; /* Adjust checkmark size */
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .woodash-form-checkbox:focus {
        outline: none;
        border-color: var(--primary-color); /* Primary color border on focus */
        box-shadow: 0 0 0 3px rgba(0, 204, 97, 0.2); /* Primary color glow */
    }

    .woodash-dark-mode .woodash-form-checkbox:focus {
         border-color: #60A5FA; /* Blue border on focus in dark mode */
         box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3); /* Blue glow in dark mode */
    }

    /* Styles for checkbox labels */
    .woodash-form-group .flex.items-center.mt-2 span {
         font-size: 0.875rem; /* Match label font size */
         color: #4B5563; /* Darker text color */
    }

     .woodash-dark-mode .woodash-form-group .flex.items-center.mt-2 span {
         color: #BDC4CD; /* Lighter text color in dark mode */
     }

    /* Style for button group at the bottom of the form */
    .woodash-modal-content .flex.justify-end.gap-2.mt-6 {
        margin-top: 2rem; /* Increase space above buttons */
        padding-top: 1.5rem; /* Add padding above buttons */
        border-top: 1px solid var(--border-color); /* Add a subtle border */
    }

    /* Status badge styles */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-badge.active {
        background-color: #D1FAE5;
        color: #065F46;
    }

    .status-badge.inactive {
        background-color: #FEE2E2;
        color: #991B1B;
    }

    .woodash-dark-mode .status-badge.active {
        background-color: rgba(16, 185, 129, 0.2);
        color: #34D399;
    }

    .woodash-dark-mode .status-badge.inactive {
        background-color: rgba(239, 68, 68, 0.2);
        color: #F87171;
    }

    /* Table improvements */
    .woodash-table th {
        white-space: nowrap;
        padding: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #6B7280;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .woodash-table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .woodash-table tr {
        transition: var(--transition-base);
    }

    .woodash-table tr:hover {
        background: rgba(0, 204, 97, 0.05);
    }

    .woodash-dark-mode .woodash-table th {
        background: rgba(55, 65, 81, 0.9);
        color: #9CA3AF;
    }

    .woodash-dark-mode .woodash-table tr:hover {
        background: rgba(59, 130, 246, 0.1);
    }

    /* Bulk actions styles */
    .bulk-actions-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .bulk-actions-select {
        min-width: 150px;
    }

    /* Tooltip styles */
    [data-tooltip] {
        position: relative;
    }

    [data-tooltip]:before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 0.5rem;
        background: #1F2937;
        color: white;
        font-size: 0.75rem;
        border-radius: 0.25rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition-base);
        z-index: 50;
    }

    [data-tooltip]:hover:before {
        opacity: 1;
        visibility: visible;
    }

    .woodash-dark-mode [data-tooltip]:before {
        background: #374151;
        color: #E5E7EB;
    }

    /* Notification Styles */
    .woodash-notification {
        position: relative;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        color: white;
        opacity: 0;
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        min-width: 200px;
        max-width: 300px;
        word-break: break-word;
        font-size: 0.9rem;
    }

    .woodash-notification-success {
        background-color: #10B981; /* Green */
    }

    .woodash-notification-error {
        background-color: #EF4444; /* Red */
    }

    .woodash-notification-warning {
        background-color: #F59E0B; /* Yellow/Orange */
    }

    .woodash-notification-info {
        background-color: #3B82F6; /* Blue */
    }

    /* Form validation styles */
    .woodash-form-input.invalid {
        border-color: #EF4444; /* Red */
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.3);
    }

    .woodash-form-input.invalid:focus {
         border-color: #EF4444;
         box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.3);
    }

    .woodash-form-group .text-red-600 {
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    /* Filter panel transition */
    #filters-panel {
        transition: all 0.3s ease-in-out;
    }

    /* Styles for the Coupon Modal to match Customer Modal */
    #coupon-modal .woodash-modal-content {
        max-width: 40rem; /* Match customer modal width */
        padding: 1.5rem 2rem; /* Match customer modal padding */
        /* Keep existing background, border-radius, box-shadow, position relative */
    }

    #coupon-modal .woodash-modal-content .flex.justify-between.items-center.mb-4 {
        border-bottom: 1px solid var(--border-color); /* Add border */
        padding-bottom: 1rem; /* Add padding below border */
        margin-bottom: 1.5rem; /* Adjust margin */
    }

    #coupon-modal .woodash-modal-content .text-lg.font-semibold.text-gray-900 {
        font-size: 1.25rem; /* Larger font size */
        font-weight: 700; /* Bolder font weight */
        /* Use existing color styles */
    }

    #coupon-modal .woodash-modal-content #close-coupon-modal {
        top: 1.5rem; /* Adjust position */
        right: 1.5rem; /* Adjust position */
        font-size: 1.5rem; /* Larger icon size */
        color: #6B7280; /* Match color */
        /* Keep transition and hover styles */
    }

    /* Adjust form group spacing */
    #coupon-modal .woodash-form-group {
        margin-bottom: 1.25rem; /* Match customer form spacing */
    }

    /* Adjust label styles */
    #coupon-modal .woodash-form-label {
        font-size: 0.875rem;
        font-weight: 600; /* Bolder font weight */
        margin-bottom: 0.5rem; /* Match customer form spacing */
        /* Keep existing color styles */
    }

    /* Adjust input/select/textarea styles */
    #coupon-modal .woodash-form-input {
        padding: 0.625rem 0.75rem; /* Match customer form padding */
        border-color: #D1D5DB; /* Match customer form border color */
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* Match customer form shadow */
        /* Keep existing border-radius, font-size, transition, background, color */
    }

    /* Adjust focus styles for inputs */
    #coupon-modal .woodash-form-input:focus {
        border-color: var(--primary-color); /* Use primary color */
        box-shadow: 0 0 0 1px rgba(0, 204, 97, 0.5); /* Adjust glow */
        /* Keep outline: none */
    }

    /* Adjust dark mode focus styles */
    .woodash-dark-mode #coupon-modal .woodash-form-input:focus {
        border-color: #60A5FA; /* Use blue */
        box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.5); /* Adjust glow */
    }

    /* Adjust checkbox styles */
    #coupon-modal .woodash-form-checkbox {
        width: 1rem; /* Match customer form size */
        height: 1rem; /* Match customer form size */
        /* Keep existing border-radius, border, appearance, background, cursor, position, transition, flex-shrink */
    }

    /* Adjust checkbox checked styles */
    #coupon-modal .woodash-form-checkbox:checked {
        background-color: var(--primary-color); /* Use primary color */
        border-color: var(--primary-color);
        /* Keep existing dark mode checked styles */
    }

    /* Adjust checkbox checkmark */
    #coupon-modal .woodash-form-checkbox:checked::after {
        left: 0.25rem; /* Adjust position */
        top: 0.125rem; /* Adjust position */
        width: 0.25rem; /* Match customer form size */
        height: 0.5rem; /* Match customer form size */
        /* Keep existing border, border-width, transform */
    }

    /* Adjust checkbox focus styles */
    #coupon-modal .woodash-form-checkbox:focus {
        border-color: var(--primary-color); /* Use primary color */
        box-shadow: 0 0 0 3px rgba(0, 204, 97, 0.2); /* Adjust glow */
        /* Keep outline: none */
    }

    /* Adjust dark mode checkbox focus styles */
    .woodash-dark-mode #coupon-modal .woodash-form-checkbox:focus {
        border-color: #60A5FA; /* Use blue */
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3); /* Adjust glow */
    }

    /* Adjust styles for checkbox labels */
    #coupon-modal .woodash-form-group .flex.items-center.mt-2 span {
        font-size: 0.875rem; /* Match customer form size */
        color: #4B5563; /* Match customer form color */
        /* Keep existing dark mode color */
    }

    /* Adjust style for button group at the bottom of the form */
    #coupon-modal .flex.justify-end.gap-2.mt-6 {
        margin-top: 2rem; /* Match customer form spacing */
        padding-top: 1rem; /* Match customer form padding */
        border-top: 1px solid var(--border-color); /* Add border */
        gap: 0.75rem; /* Adjust gap between buttons */
    }
</style>
</head>
<body class="<?php echo esc_attr($body_class); ?>">

<div id="woodash-dashboard" class="woodash-fullscreen flex">
    <!-- Sidebar -->
    <aside class="woodash-sidebar woodash-glass-effect">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center woodash-glow">
                <i class="fa-solid fa-ticket text-white text-xl"></i>
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
               class="woodash-nav-link active woodash-hover-card group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-ticket text-white"></i>
                    </div>
                    <span>Coupons</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-settings')); ?>"
               class="woodash-nav-link woodash-hover-card group">
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
                    <h1 class="text-2xl font-bold woodash-gradient-text">Coupons</h1>
                    <p class="text-gray-500">Manage and track your discount coupons</p>
                </div>

            </header>

            <!-- Coupon Usage Analytics -->
            <div class="space-y-6 mb-8">
                <!-- Analytics Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Coupons -->
                    <div class="woodash-card p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Coupons</p>
                                <h3 class="text-2xl font-bold text-gray-900" id="total-coupons">0</h3>
                            </div>
                            <div class="p-3 bg-red-50 rounded-full">
                                <i class="fas fa-ticket-alt text-red-500 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm">
                                <span class="text-green-500 mr-2">
                                    <i class="fas fa-arrow-up"></i> 12%
                                </span>
                                <span class="text-gray-500">vs last month</span>
                            </div>
                            <div class="mt-2 h-1 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-red-500 rounded-full" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Coupons -->
                    <div class="woodash-card p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Active Coupons</p>
                                <h3 class="text-2xl font-bold text-gray-900" id="active-coupons">0</h3>
                            </div>
                            <div class="p-3 bg-green-50 rounded-full">
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm">
                                <span class="text-green-500 mr-2">
                                    <i class="fas fa-arrow-up"></i> 8%
                                </span>
                                <span class="text-gray-500">vs last month</span>
                            </div>
                            <div class="mt-2 h-1 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Usage -->
                    <div class="woodash-card p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Usage</p>
                                <h3 class="text-2xl font-bold text-gray-900" id="total-coupon-usage">0</h3>
                            </div>
                            <div class="p-3 bg-blue-50 rounded-full">
                                <i class="fas fa-chart-line text-blue-500 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm">
                                <span class="text-green-500 mr-2">
                                    <i class="fas fa-arrow-up"></i> 15%
                                </span>
                                <span class="text-gray-500">vs last month</span>
                            </div>
                            <div class="mt-2 h-1 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Average Discount -->
                    <div class="woodash-card p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Avg. Discount</p>
                                <h3 class="text-2xl font-bold text-gray-900" id="avg-discount">$0</h3>
                            </div>
                            <div class="p-3 bg-purple-50 rounded-full">
                                <i class="fas fa-percentage text-purple-500 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm">
                                <span class="text-red-500 mr-2">
                                    <i class="fas fa-arrow-down"></i> 3%
                                </span>
                                <span class="text-gray-500">vs last month</span>
                            </div>
                            <div class="mt-2 h-1 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-purple-500 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Coupon Usage Trend -->
                    <div class="woodash-card p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Coupon Usage Trend</h3>
                                <p class="text-sm text-gray-500">Track coupon usage over time</p>
                            </div>
                            <div class="flex gap-2" id="coupon-trend-period-buttons">
                                <button class="woodash-btn woodash-btn-secondary text-sm" data-period="week">Week</button>
                                <button class="woodash-btn woodash-btn-secondary text-sm" data-period="month">Month</button>
                                <button class="woodash-btn woodash-btn-secondary text-sm" data-period="year">Year</button>
                            </div>
                        </div>
                        <div class="h-80">
                            <canvas id="couponUsageChart"></canvas>
                        </div>
                        <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-500">Peak Usage</p>
                                <p class="text-lg font-semibold text-gray-900" id="peak-usage">0</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-500">Avg. Daily Usage</p>
                                <p class="text-lg font-semibold text-gray-900" id="avg-daily-usage">0</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-500">Growth Rate</p>
                                <p class="text-lg font-semibold text-green-500" id="growth-rate">+0%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Top Coupons by Usage -->
                    <div class="woodash-card p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Top Performing Coupons</h3>
                                <p class="text-sm text-gray-500">Most frequently used coupons</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <select class="woodash-select text-sm" id="coupon-sort">
                                    <option value="usage">By Usage</option>
                                    <option value="discount">By Discount</option>
                                </select>
                                <button class="woodash-btn woodash-btn-secondary text-sm" id="export-top-coupons">
                                    <i class="fas fa-download mr-2"></i>Export
                                </button>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div id="top-coupons-list" class="space-y-3">
                                <!-- Top coupons will be loaded here by JS -->
                                <div class="animate-pulse">
                                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Conversion Rate -->
                    <div class="woodash-card p-6 hover:shadow-lg transition-shadow duration-300">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Conversion Rate</h3>
                        <div class="h-64">
                            <canvas id="conversionRateChart"></canvas>
                        </div>
                    </div>

                    <!-- Revenue Impact -->
                    <div class="woodash-card p-6 hover:shadow-lg transition-shadow duration-300">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Impact</h3>
                        <div class="h-64">
                            <canvas id="revenueImpactChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add New Item Section -->
            <div class="woodash-card p-6 hover:shadow-lg transition-shadow duration-300 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Add New Item</h3>
                        <p class="text-sm text-gray-500">Create a new product or service</p>
                    </div>
                </div>
                
                <form id="add-item-form" class="space-y-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                            <input type="text" name="item_name" class="woodash-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                            <input type="text" name="sku" class="woodash-input w-full" required>
                        </div>
                    </div>

                    <!-- Price Information -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price</label>
                            <input type="number" name="regular_price" class="woodash-input w-full" step="0.01" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                            <input type="number" name="sale_price" class="woodash-input w-full" step="0.01">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                            <input type="number" name="stock_quantity" class="woodash-input w-full" required>
                        </div>
                    </div>

                    <!-- Categories and Tags -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                            <select name="categories[]" class="woodash-select w-full" multiple>
                                <?php
                                $categories = get_terms('product_cat', array('hide_empty' => false));
                                foreach ($categories as $category) {
                                    echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                            <input type="text" name="tags" class="woodash-input w-full" placeholder="Separate tags with commas">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" class="woodash-input w-full" rows="4"></textarea>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="file-upload" name="product_image" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="woodash-select w-full">
                                <option value="publish">Published</option>
                                <option value="draft">Draft</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Visibility</label>
                            <select name="visibility" class="woodash-select w-full">
                                <option value="visible">Catalog & Search</option>
                                <option value="catalog">Catalog Only</option>
                                <option value="search">Search Only</option>
                                <option value="hidden">Hidden</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="woodash-btn woodash-btn-primary">
                            <i class="fas fa-plus mr-2"></i>Add Item
                        </button>
                    </div>
                </form>
            </div>

            <?php include(plugin_dir_path(__FILE__) . 'add-item-form.php'); ?>

            <!-- Coupon Management Section -->
            <div class="woodash-card p-6 hover:shadow-lg transition-shadow duration-300 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-4 border-b border-gray-100 gap-4">
                    <h3 class="text-lg font-semibold text-gray-900">Coupon List</h3>
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input type="text" class="woodash-form-input pl-8" placeholder="Search coupons..." id="search-coupons">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="woodash-btn woodash-btn-secondary" id="filter-toggle">
                                <i class="fa-solid fa-filter"></i>
                                <span>Filters</span>
                            </button>
                            <!-- <select id="bulk-action" class="woodash-form-input">
                                <option value="">Bulk Actions</option>
                                <option value="delete">Delete</option>
                                <option value="enable">Enable</option>
                                <option value="disable">Disable</option>
                                <option value="export">Export Selected</option>
                            </select> -->
                            <button class="woodash-btn woodash-btn-secondary" id="apply-bulk-action">Apply</button>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="woodash-btn woodash-btn-secondary" id="import-coupons">
                                <i class="fa-solid fa-file-import"></i>
                                <span>Import</span>
                            </button>
                            <button class="woodash-btn woodash-btn-secondary" id="export-all-coupons">
                                <i class="fa-solid fa-file-export"></i>
                                <span>Export All</span>
                            </button>
                            <button type="button" id="add-new-item-btn" class="woodash-btn woodash-btn-primary flex items-center gap-2 hover:bg-indigo-700 transition-colors duration-200">
                                <i class="fa-solid fa-plus"></i>
                                <span>Add New Item</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Advanced Filters Panel -->
                <div id="filters-panel" class="hidden p-4 border-b border-gray-100 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="woodash-form-group">
                            <label class="woodash-form-label">Discount Type</label>
                            <select id="filter-discount-type" class="woodash-form-input">
                                <option value="">All Types</option>
                                <option value="percent">Percentage</option>
                                <option value="fixed_cart">Fixed Cart</option>
                                <option value="fixed_product">Fixed Product</option>
                            </select>
                        </div>
                        <div class="woodash-form-group">
                            <label class="woodash-form-label">Status</label>
                            <select id="filter-status" class="woodash-form-input">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                        <div class="woodash-form-group">
                            <label class="woodash-form-label">Date Range</label>
                            <div class="flex gap-2">
                                <input type="date" id="filter-date-from" class="woodash-form-input" placeholder="From">
                                <input type="date" id="filter-date-to" class="woodash-form-input" placeholder="To">
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button class="woodash-btn woodash-btn-secondary" id="reset-filters">Reset Filters</button>
                        <button class="woodash-btn woodash-btn-primary" id="apply-filters">Apply Filters</button>
                    </div>
                </div>

                <!-- Coupon Templates -->
                <div class="p-4 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-medium text-gray-700">Quick Templates</h4>
                        <button class="woodash-btn woodash-btn-secondary text-sm" id="save-as-template">
                            <i class="fa-solid fa-save"></i>
                            <span>Save Current as Template</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4" id="coupon-templates">
                        <!-- Templates will be loaded here -->
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="woodash-table">
                        <thead>
                            <tr>
                                <th class="w-8">
                                    <input type="checkbox" id="select-all-coupons" class="woodash-form-checkbox">
                                </th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Discount Type</th>
                                <th>Amount</th>
                                <th>Usage</th>
                                <th>Limits</th>
                                <th>Expiry</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="coupons-table-body">
                            <!-- Coupons will be loaded here by JS -->
                            <tr id="loading-row">
                                <td colspan="10" class="text-center py-8">
                                    <div class="flex items-center justify-center text-blue-600">
                                        <svg class="animate-spin h-8 w-8 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l2-2.647zm9.707-4.207A7.963 7.963 0 0116 12h4c0 3.042-1.135 5.824-3 7.938l-2-2.647z"></path>
                                        </svg>
                                        Loading coupons...
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex justify-between items-center p-4">
                    <div>
                        Showing <span id="coupon-pagination-start">1</span> to <span id="coupon-pagination-end">10</span> of <span id="coupon-pagination-total">50</span> coupons
                    </div>
                    <div class="flex gap-2">
                        <button class="woodash-btn woodash-btn-secondary" id="coupon-prev-page"><i class="fa-solid fa-chevron-left"></i> Previous</button>
                        <button class="woodash-btn woodash-btn-secondary" id="coupon-next-page">Next <i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<!-- Add/Edit Coupon Modal -->
<div id="coupon-modal" class="woodash-modal hidden">
    <div class="woodash-modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900" id="coupon-modal-title">Add New Coupon</h3>
            <button class="text-gray-400 hover:text-gray-600" id="close-coupon-modal">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <form id="coupon-form">
            <input type="hidden" id="coupon-id" name="coupon_id" value="">
            <div class="woodash-form-group">
                <label for="coupon-code" class="woodash-form-label">Coupon Code</label>
                <div class="flex gap-2">
                    <input type="text" id="coupon-code" name="coupon_code" class="woodash-form-input flex-1" required>
                    <button type="button" class="woodash-btn woodash-btn-secondary" id="generate-code">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        Generate
                    </button>
                </div>
            </div>
            <div class="woodash-form-group">
                <label for="coupon-description" class="woodash-form-label">Description</label>
                <textarea id="coupon-description" name="coupon_description" class="woodash-form-input" rows="2"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="woodash-form-group">
                    <label for="discount-type" class="woodash-form-label">Discount Type</label>
                    <select id="discount-type" name="discount_type" class="woodash-form-input" required>
                        <option value="percent">Percentage Discount</option>
                        <option value="fixed_cart">Fixed Cart Discount</option>
                        <option value="fixed_product">Fixed Product Discount</option>
                    </select>
                </div>
                <div class="woodash-form-group">
                    <label for="coupon-amount" class="woodash-form-label">Coupon Amount</label>
                    <input type="number" id="coupon-amount" name="coupon_amount" class="woodash-form-input" step="0.01" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="woodash-form-group">
                    <label for="minimum-spend" class="woodash-form-label">Minimum Spend</label>
                    <input type="number" id="minimum-spend" name="minimum_spend" class="woodash-form-input" step="0.01" min="0">
                </div>
                <div class="woodash-form-group">
                    <label for="maximum-spend" class="woodash-form-label">Maximum Spend</label>
                    <input type="number" id="maximum-spend" name="maximum_spend" class="woodash-form-input" step="0.01" min="0">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="woodash-form-group">
                    <label for="usage-limit" class="woodash-form-label">Usage Limit</label>
                    <input type="number" id="usage-limit" name="usage_limit" class="woodash-form-input" min="0">
                </div>
                <div class="woodash-form-group">
                    <label for="usage-limit-per-user" class="woodash-form-label">Usage Limit Per User</label>
                    <input type="number" id="usage-limit-per-user" name="usage_limit_per_user" class="woodash-form-input" min="0">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="woodash-form-group">
                    <label for="expiry-date" class="woodash-form-label">Expiry Date</label>
                    <input type="date" id="expiry-date" name="expiry_date" class="woodash-form-input">
                </div>
                <div class="woodash-form-group">
                    <label for="individual-use" class="woodash-form-label">Individual Use Only</label>
                    <div class="flex items-center mt-2">
                        <input type="checkbox" id="individual-use" name="individual_use" class="woodash-form-checkbox">
                        <span class="ml-2 text-sm text-gray-600">Prevent other coupons from being used with this coupon</span>
                    </div>
                </div>
            </div>
            <div class="woodash-form-group">
                <label for="exclude-sale-items" class="woodash-form-label">Exclude Sale Items</label>
                <div class="flex items-center mt-2">
                    <input type="checkbox" id="exclude-sale-items" name="exclude_sale_items" class="woodash-form-checkbox">
                    <span class="ml-2 text-sm text-gray-600">Check this box if the coupon should not apply to items on sale</span>
                </div>
            </div>
            <div class="woodash-form-group">
                <label for="free-shipping" class="woodash-form-label">Free Shipping</label>
                <div class="flex items-center mt-2">
                    <input type="checkbox" id="free-shipping" name="free_shipping" class="woodash-form-checkbox">
                    <span class="ml-2 text-sm text-gray-600">Check this box if the coupon grants free shipping</span>
                </div>
            </div>

             <!-- Product and Category Restrictions -->
             <div class="woodash-form-group">
                 <label for="allowed-products" class="woodash-form-label">Allowed Products</label>
                 <select id="allowed-products" name="allowed_products[]" class="woodash-form-input" multiple>
                     <!-- Options will be populated by JS (or backend in real app) -->
                     <option value="1">Product A</option>
                     <option value="2">Product B</option>
                     <option value="3">Product C</option>
                 </select>
                 <p class="text-xs text-gray-500 mt-1">Select products that the coupon will apply to. Leave empty for all products.</p>
             </div>

             <div class="woodash-form-group">
                 <label for="excluded-products" class="woodash-form-label">Excluded Products</label>
                 <select id="excluded-products" name="excluded_products[]" class="woodash-form-input" multiple>
                     <!-- Options will be populated by JS (or backend in real app) -->
                     <option value="4">Product D</option>
                     <option value="5">Product E</option>
                 </select>
                 <p class="text-xs text-gray-500 mt-1">Select products that the coupon will NOT apply to.</p>
             </div>

             <div class="woodash-form-group">
                 <label for="allowed-categories" class="woodash-form-label">Allowed Categories</label>
                 <select id="allowed-categories" name="allowed_categories[]" class="woodash-form-input" multiple>
                      <!-- Options will be populated by JS (or backend in real app) -->
                      <option value="10">Category X</option>
                      <option value="11">Category Y</option>
                 </select>
                  <p class="text-xs text-gray-500 mt-1">Select categories that the coupon will apply to. Leave empty for all categories.</p>
             </div>

             <div class="woodash-form-group">
                 <label for="excluded-categories" class="woodash-form-label">Excluded Categories</label>
                 <select id="excluded-categories" name="excluded_categories[]" class="woodash-form-input" multiple>
                     <!-- Options will be populated by JS (or backend in real app) -->
                     <option value="12">Category Z</option>
                 </select>
                 <p class="text-xs text-gray-500 mt-1">Select categories that the coupon will NOT apply to.</p>
             </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" class="woodash-btn woodash-btn-secondary" id="cancel-coupon">Cancel</button>
                <button type="submit" class="woodash-btn woodash-btn-primary">Save Coupon</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const CouponsManager = {
        state: {
            currentPage: 1,
            itemsPerPage: 10,
            totalCoupons: 0,
            fakeCoupons: [], // Store fake coupons here
            filteredCoupons: null,
            templates: []
        },

        init() {
            console.log('CouponsManager initializing...');
            this.generateInitialFakeCoupons(30); // Generate initial batch of fake coupons
            this.initEventListeners();
            this.loadCoupons();
            this.initCharts();
            this.setupSearchDebounce();
            this.applyInitialColorMode(); // Apply color mode on load
            this.initTemplates(); // Initialize templates
            this.initSampleRestrictions(); // Initialize sample restriction data
        },

        initSampleRestrictions() {
            // Simulate fetching sample products and categories
            this.state.sampleProducts = [
                { id: '1', name: 'Product A' },
                { id: '2', name: 'Product B' },
                { id: '3', name: 'Product C' },
                { id: '4', name: 'Product D' },
                { id: '5', name: 'Product E' },
            ];
            this.state.sampleCategories = [
                { id: '10', name: 'Category X' },
                { id: '11', name: 'Category Y' },
                { id: '12', name: 'Category Z' },
            ];
            this.populateRestrictionSelects();
        },

        populateRestrictionSelects() {
            const allowedProductsSelect = document.getElementById('allowed-products');
            const excludedProductsSelect = document.getElementById('excluded-products');
            const allowedCategoriesSelect = document.getElementById('allowed-categories');
            const excludedCategoriesSelect = document.getElementById('excluded-categories');

            this.populateSelectOptions(allowedProductsSelect, this.state.sampleProducts);
            this.populateSelectOptions(excludedProductsSelect, this.state.sampleProducts);
            this.populateSelectOptions(allowedCategoriesSelect, this.state.sampleCategories);
            this.populateSelectOptions(excludedCategoriesSelect, this.state.sampleCategories);
        },

        populateSelectOptions(selectElement, options) {
            if (!selectElement || !options) return;
            // Clear existing options except for the first (if it's a placeholder)
            while (selectElement.options.length > 0) {
                 selectElement.remove(0);
            }
            options.forEach(option => {
                 const optionElement = document.createElement('option');
                 optionElement.value = option.id;
                 optionElement.textContent = option.name;
                 selectElement.appendChild(optionElement);
            });
       },

        initTemplates() {
            if (!this.state.templates) {
                this.state.templates = [
                    {
                        name: 'Summer Sale',
                        data: {
                            discount_type: 'percent',
                            coupon_amount: '20',
                            minimum_spend: '50',
                            maximum_spend: '200',
                            usage_limit: '100',
                            usage_limit_per_user: '1',
                            individual_use: true,
                            exclude_sale_items: false,
                            free_shipping: false
                        }
                    },
                    {
                        name: 'Free Shipping',
                        data: {
                            discount_type: 'fixed_cart',
                            coupon_amount: '0',
                            minimum_spend: '100',
                            maximum_spend: '',
                            usage_limit: '',
                            usage_limit_per_user: '',
                            individual_use: false,
                            exclude_sale_items: false,
                            free_shipping: true
                        }
                    }
                ];
            }
            this.renderTemplates();
        },

        applyInitialColorMode() {
             const body = document.body;
             // In a real scenario, you'd read this from a WordPress option
             const savedColorMode = '<?php echo esc_attr($current_color_mode); ?>'; // Get mode from PHP
             if (savedColorMode === 'dark') {
                 body.classList.add('woodash-dark-mode');
             } else {
                  body.classList.remove('woodash-dark-mode');
             }
              this.updateModeButtonState(savedColorMode);
        },

        updateModeButtonState(mode) {
             const modeButtons = document.querySelectorAll('.woodash-mode-button');
             modeButtons.forEach(button => {
                 if (button.dataset.mode === mode) {
                     button.classList.add('active');
                 } else {
                     button.classList.remove('active');
                 }
             });
        },

        setupSearchDebounce() {
            let searchTimeout;
            const searchInput = document.getElementById('search-coupons');

            searchInput?.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    // Implement search filtering on fakeCoupons
                    this.loadCoupons(); // Reload coupons after search delay
                }, 300);
            });
        },

        generateInitialFakeCoupons(count) {
            const discountTypes = ['percent', 'fixed_cart', 'fixed_product'];
            const codes = ['SAVE10', 'FREESHIP', 'OFF20', 'WELCOME15', 'SUMMER', 'FLASHDEAL'];
            const descriptions = ['10% off your order', 'Free shipping', '20% off selected items', 'Welcome discount', 'Summer sale coupon', 'Limited time offer'];

            for (let i = 0; i < count; i++) {
                 const type = discountTypes[Math.floor(Math.random() * discountTypes.length)];
                 let amount;
                 if (type === 'percent') {
                     amount = Math.floor(Math.random() * 20) + 5; // 5-25%
                 } else {
                     amount = Math.floor(Math.random() * 50) + 5; // $5-$55
                 }

                 const usageCount = Math.floor(Math.random() * 200);

                 const expiryDate = Math.random() > 0.3 ? new Date(Date.now() + Math.random() * 90 * 24 * 60 * 60 * 1000).toISOString().split('T')[0] : ''; // Expires within 90 days or no expiry

                 const coupon = {
                      id: `coupon_${i + 1}`,
                      code: codes[Math.floor(Math.random() * codes.length)] + i,
                      description: descriptions[Math.floor(Math.random() * descriptions.length)],
                      discount_type: type,
                      coupon_amount: amount.toFixed(2),
                      usage_count: usageCount,
                      expiry_date: expiryDate,
                      actions: '', // Actions column handled in render
                 };
                 this.state.fakeCoupons.push(coupon);
            }
            this.state.totalCoupons = this.state.fakeCoupons.length; // Set initial total
            console.log('Generated initial fake coupons:', this.state.fakeCoupons);
        },

        generateCouponUsageTrendData(period) {
             // This is a simplified example. Real trend data would require simulating usage over time.
             // For now, let's generate some random-ish data based on the period.

             const data = [];
             let count = period === 'week' ? 7 : (period === 'month' ? 12 : 5);
             const now = new Date();
             now.setHours(0, 0, 0, 0);

             const addDays = (date, days) => {
                  const result = new Date(date);
                  result.setDate(result.getDate() + days);
                  return result;
             };

              const labels = [];

             for (let i = 0; i < count; i++) {
                 // Generate some plausible (but fake) usage count for the period
                 const usage = Math.floor(Math.random() * 50) + (period === 'year' ? 100 : (period === 'month' ? 20 : 5));
                 data.push(usage);

                 // Generate labels
                 if (period === 'week') {
                     const date = addDays(now, -(i * 1));
                     labels.push(date.toLocaleDateString('en-US', { weekday: 'short' }));
                 } else if (period === 'month') {
                     const date = new Date(now);
                     date.setMonth(now.getMonth() - i);
                     labels.push(date.toLocaleDateString('en-US', { month: 'short', year: (i === 0 || date.getMonth() === 0) ? '2-digit' : undefined }));
                 } else if (period === 'year') {
                     const date = new Date(now);
                     date.setFullYear(now.getFullYear() - i);
                     labels.push(date.toLocaleDateString('en-US', { year: 'numeric' }));
                 }
             }
             data.reverse();
              labels.reverse();
              console.log(`Generated Coupon Usage Trend Data for ${period}:`, data);
              console.log(`Generated Coupon Usage Trend Labels for ${period}:`, labels);
             return { labels, data };
        },

        generateTopCouponsData() {
             // Sort fake coupons by usage count descending
             const sortedCoupons = [...this.state.fakeCoupons].sort((a, b) => b.usage_count - a.usage_count);
             // Get top 5
             const top5 = sortedCoupons.slice(0, 5);
              console.log('Generated Top Coupons Data:', top5);
             return top5;
        },

        initCharts() {
            try {
                // Destroy existing chart if it exists
                if (this.state.charts && this.state.charts.couponUsage) {
                    this.state.charts.couponUsage.destroy();
                }
                 // Ensure charts object exists
                 if (!this.state.charts) {
                     this.state.charts = {};
                 }

                // Coupon Usage Chart
                const couponUsageCtx = document.getElementById('couponUsageChart')?.getContext('2d');
                if (couponUsageCtx) {
                     console.log('Initializing Coupon Usage Chart...');
                     const trendData = this.generateCouponUsageTrendData('month'); // Default to month
                    this.state.charts.couponUsage = new Chart(couponUsageCtx, {
                        type: 'line',
                        data: {
                            labels: trendData.labels,
                            datasets: [{
                                label: 'Coupons Used',
                                data: trendData.data,
                                borderColor: '#DC2626', // Red color for coupons
                                backgroundColor: 'rgba(220, 38, 38, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                             interaction: {
                                 mode: 'index',
                                 intersect: false
                             },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += context.raw.toLocaleString();
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                     ticks: {
                                         callback: function(value) {
                                             if (Number.isInteger(value)) {
                                                 return value;
                                             }
                                         }
                                     }
                                },
                                 x: {
                                     grid: {
                                         display: false
                                     }
                                 }
                            }
                        }
                    });
                } else {
                    console.error('Could not get context for Coupon Usage Chart.', document.getElementById('couponUsageChart'));
                }

                 this.renderTopCoupons();

            } catch (error) {
                console.error('Error initializing charts:', error);
                // this.showNotification('Error initializing charts: ' + error.message, 'error'); // Assuming showNotification exists
            }
        },

         updateCouponUsageTrend(period) {
             if (!this.state.charts.couponUsage) {
                 console.error('updateCouponUsageTrend: Coupon Usage chart not initialized.');
                 return;
             }

              console.log(`Updating Coupon Usage Trend for period: ${period}`);
              const trendData = this.generateCouponUsageTrendData(period);

              this.state.charts.couponUsage.data.labels = trendData.labels;
              this.state.charts.couponUsage.data.datasets[0].data = trendData.data;
              this.state.charts.couponUsage.update();
              console.log('Coupon Usage chart updated.');

              // Update active state for period buttons
              document.querySelectorAll('#coupon-trend-period-buttons [data-period]').forEach(btn => {
                  btn.classList.remove('bg-[#DC2626]', 'text-white', 'woodash-btn-primary'); // Remove active red styles
                   btn.classList.add('woodash-btn-secondary'); // Add default secondary styles
                  if (btn.dataset.period === period) {
                       btn.classList.remove('woodash-btn-secondary');
                       btn.classList.add('bg-[#DC2626]', 'text-white', 'woodash-btn-primary'); // Add active red styles
                  }
              });
         },

        renderTopCoupons() {
             const topCouponsList = document.getElementById('top-coupons-list');
             if (!topCouponsList) return;

             const topCoupons = this.generateTopCouponsData();
             topCouponsList.innerHTML = ''; // Clear existing list

             if (topCoupons.length === 0) {
                  topCouponsList.innerHTML = '<li>No coupons found.</li>';
                  return;
             }

             topCoupons.forEach(coupon => {
                  const listItem = document.createElement('li');
                  listItem.classList.add('flex', 'justify-between', 'items-center', 'py-2', 'border-b', 'border-gray-100');
                  listItem.innerHTML = `
                      <span>${coupon.code}</span>
                      <span class="font-semibold text-gray-900">${coupon.usage_count.toLocaleString()} uses</span>
                  `;
                  topCouponsList.appendChild(listItem);
             });
        },

        initEventListeners() {
            console.log('Initializing event listeners...');
            const addCouponBtn = document.getElementById('add-coupon-btn');
            const couponModal = document.getElementById('coupon-modal');
            const closeCouponModal = document.getElementById('close-coupon-modal');
            const cancelCoupon = document.getElementById('cancel-coupon');
            const couponForm = document.getElementById('coupon-form');
            const couponTableBody = document.getElementById('coupons-table-body');
            const searchInput = document.getElementById('search-coupons');
            const generateCodeBtn = document.getElementById('generate-code');
            const discountTypeSelect = document.getElementById('discount-type');
            const filterToggle = document.getElementById('filter-toggle');
            const filtersPanel = document.getElementById('filters-panel');
            const importBtn = document.getElementById('import-coupons');
            const exportAllBtn = document.getElementById('export-all-coupons');
            const saveTemplateBtn = document.getElementById('save-as-template');
            const couponTrendPeriodButtons = document.querySelectorAll('#coupon-trend-period-buttons [data-period]');
            const nextPaginationBtn = document.getElementById('coupon-next-page');
            const prevPaginationBtn = document.getElementById('coupon-prev-page');
            const modeButtons = document.querySelectorAll('.woodash-mode-button');

            // Filter Toggle
            filterToggle?.addEventListener('click', () => {
                filtersPanel?.classList.toggle('hidden');
            });

            // Import/Export
            importBtn?.addEventListener('click', () => {
                this.showImportModal();
            });

            exportAllBtn?.addEventListener('click', () => {
                this.exportAllCoupons();
            });

            // Save Template
            saveTemplateBtn?.addEventListener('click', () => {
                this.showSaveTemplateModal();
            });

            // Apply Filters
            document.getElementById('apply-filters')?.addEventListener('click', () => {
                this.applyFilters();
            });

            document.getElementById('reset-filters')?.addEventListener('click', () => {
                this.resetFilters();
            });

            // Generate Coupon Code
            generateCodeBtn?.addEventListener('click', () => {
                const code = this.generateRandomCouponCode();
                document.getElementById('coupon-code').value = code;
            });

            // Handle discount type change
            discountTypeSelect?.addEventListener('change', (e) => {
                const amountInput = document.getElementById('coupon-amount');
                if (e.target.value === 'percent') {
                    amountInput.max = 100;
                    amountInput.placeholder = 'Enter percentage (0-100)';
                } else {
                    amountInput.max = '';
                    amountInput.placeholder = 'Enter amount';
                }
            });

            // Add Coupon Button
            addCouponBtn?.addEventListener('click', () => {
                console.log('Add New Coupon button clicked.');
                this.resetCouponForm();
                this.clearAllValidationErrors(); // Clear errors on opening new form
                document.getElementById('coupon-modal-title').textContent = 'Add New Coupon';
                couponModal?.classList.remove('hidden');
                console.log('Coupon modal should now be visible.');
            });

            // Coupon Modal
            closeCouponModal?.addEventListener('click', () => {
                console.log('Close Coupon Modal button clicked.');
                couponModal?.classList.add('hidden');
                this.clearAllValidationErrors(); // Clear errors on closing modal
                console.log('Coupon modal should now be hidden.');
            });

            cancelCoupon?.addEventListener('click', () => {
                console.log('Cancel Coupon button clicked.');
                couponModal?.classList.add('hidden');
                this.clearAllValidationErrors(); // Clear errors on canceling
                console.log('Coupon modal should now be hidden.');
            });

            couponForm?.addEventListener('submit', (e) => {
                console.log('Coupon form submitted.');
                e.preventDefault();
                if (this.validateCouponForm()) {
                    this.submitCouponForm(new FormData(couponForm));
                } else {
                    this.showNotification('Please fix the errors in the form.', 'error');
                }
                console.log('Coupon form submission handler finished.');
            });

            // Event delegation for table actions (edit, delete)
            couponTableBody?.addEventListener('click', (e) => {
                const target = e.target.closest('button');
                if (!target) return;

                const couponId = target.dataset.couponId;
                const action = target.dataset.action;

                if (couponId && action) {
                    switch(action) {
                        case 'edit':
                            console.log(`Edit button clicked for coupon ID: ${couponId}`);
                            this.clearAllValidationErrors(); // Clear errors before editing
                            this.editCoupon(couponId);
                            break;
                        case 'delete':
                            console.log(`Delete button clicked for coupon ID: ${couponId}`);
                            if (confirm('Are you sure you want to delete this coupon?')) {
                                this.deleteCoupon(couponId);
                            }
                            break;
                    }
                }
            });

            // Coupon Trend Period Buttons
             couponTrendPeriodButtons.forEach(button => {
                 button.addEventListener('click', function() {
                      const period = this.dataset.period;
                      CouponsManager.updateCouponUsageTrend(period);
                 });
             });

             // Pagination Buttons
             nextPaginationBtn?.addEventListener('click', () => {
                  if (this.state.currentPage * this.state.itemsPerPage < this.state.totalCoupons) {
                      this.state.currentPage++;
                      this.loadCoupons();
                  }
             });

             prevPaginationBtn?.addEventListener('click', () => {
                  if (this.state.currentPage > 1) {
                      this.state.currentPage--;
                      this.loadCoupons();
                  }
             });

            // Bulk Actions
            const applyBulkActionBtn = document.getElementById('apply-bulk-action');
            const bulkActionSelect = document.getElementById('bulk-action');

            applyBulkActionBtn?.addEventListener('click', () => {
                const action = bulkActionSelect?.value;
                if (!action) {
                    alert('Please select a bulk action');
                    return;
                }

                const selectedCoupons = Array.from(document.querySelectorAll('.coupon-checkbox:checked'))
                    .map(checkbox => checkbox.dataset.couponId);

                if (selectedCoupons.length === 0) {
                    alert('Please select at least one coupon');
                    return;
                }

                switch(action) {
                    case 'delete':
                        if (confirm(`Are you sure you want to delete ${selectedCoupons.length} coupon(s)?`)) {
                            selectedCoupons.forEach(id => this.deleteCoupon(id));
                        }
                        break;
                    case 'enable':
                        selectedCoupons.forEach(id => this.updateCouponStatus(id, 'active'));
                        break;
                    case 'disable':
                        selectedCoupons.forEach(id => this.updateCouponStatus(id, 'inactive'));
                        break;
                }

                // Reset bulk action select
                bulkActionSelect.value = '';
            });

            // Dark/Light Mode Buttons
              modeButtons.forEach(button => {
                  button.addEventListener('click', function() {
                      const mode = this.dataset.mode;
                      // In a real scenario, you'd send an AJAX request here to save the preference
                      console.log(`Setting color mode to: ${mode}`);
                      // Simulate saving (update the body class directly for visual feedback)
                      CouponsManager.setColorMode(mode);
                       // TODO: Send AJAX request to save 'woodashh_color_mode' option
                  });
              });

              // Bulk Actions Event Listener
              this.initBulkActionEventListener();

              // Input event listeners to clear validation errors on user typing
              couponForm.querySelectorAll('.woodash-form-input').forEach(input => {
                   input.addEventListener('input', () => this.clearValidationError(input));
              });

              // Add listeners for restriction selects if needed (for clearing validation, etc.)
              document.getElementById('allowed-products')?.addEventListener('change', () => this.clearValidationError(document.getElementById('allowed-products')));
              document.getElementById('excluded-products')?.addEventListener('change', () => this.clearValidationError(document.getElementById('excluded-products')));
              document.getElementById('allowed-categories')?.addEventListener('change', () => this.clearValidationError(document.getElementById('allowed-categories')));
              document.getElementById('excluded-categories')?.addEventListener('change', () => this.clearValidationError(document.getElementById('excluded-categories')));
              
              console.log('All event listeners initialized.');
        },

         setColorMode(mode) {
             const body = document.body;
             const colorModeInput = document.getElementById('woodashh_color_mode'); // Assuming a hidden input exists for saving
             if (mode === 'dark') {
                 body.classList.add('woodash-dark-mode');
                  if (colorModeInput) colorModeInput.value = 'dark';
             } else {
                 body.classList.remove('woodash-dark-mode');
                  if (colorModeInput) colorModeInput.value = 'light';
             }
             this.updateModeButtonState(mode);
             // Note: Charts might need to be redrawn in dark mode for text/grid color updates
             this.initCharts(); // Re-initialize charts to apply potential dark mode styles
         },

        loadCoupons() {
            console.log('Loading coupons...');
            // Simulate fetching data with filters and pagination
            let filteredCoupons = this.state.fakeCoupons;

            const searchInput = document.getElementById('search-coupons');
            const searchTerm = searchInput?.value.toLowerCase() || '';

            if (searchTerm) {
                 filteredCoupons = filteredCoupons.filter(coupon =>
                      coupon.code.toLowerCase().includes(searchTerm) ||
                      coupon.description.toLowerCase().includes(searchTerm)
                 );
            }

            this.state.totalCoupons = filteredCoupons.length;

            const startIndex = (this.state.currentPage - 1) * this.state.itemsPerPage;
            const endIndex = startIndex + this.state.itemsPerPage;
            const couponsToDisplay = filteredCoupons.slice(startIndex, endIndex);

            this.renderCouponsTable(couponsToDisplay);
            this.updatePaginationInfo();
             // this.updateCharts(); // Update charts if they depend on filtered data
        },

        renderCouponsTable(coupons) {
            const tableBody = document.getElementById('coupons-table-body');
            if (!tableBody) return;

            tableBody.innerHTML = ''; // Clear existing rows

            if (coupons.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="10" class="text-center py-8 text-gray-600">No coupons found.</td>
                    </tr>
                `;
                return;
            }

            coupons.forEach(coupon => {
                const row = document.createElement('tr');
                
                const amountDisplay = coupon.discount_type === 'percent' ? `${coupon.coupon_amount}%` : `$${coupon.coupon_amount}`;
                const expiryDisplay = coupon.expiry_date ? new Date(coupon.expiry_date).toLocaleDateString() : 'N/A';
                const usageLimitDisplay = coupon.usage_limit ? `${coupon.usage_count}/${coupon.usage_limit}` : coupon.usage_count;
                const statusClass = coupon.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                const statusText = coupon.status === 'active' ? 'Active' : 'Inactive';

                row.innerHTML = `
                    <td>
                        <input type="checkbox" class="woodash-form-checkbox coupon-checkbox" data-coupon-id="${coupon.id}">
                    </td>
                    <td class="font-semibold text-gray-900">${coupon.code}</td>
                    <td>${coupon.description}</td>
                    <td>${coupon.discount_type.replace('_', ' ').replace(/b[a-z]/g, letter => letter.toUpperCase())}</td>
                    <td>${amountDisplay}</td>
                    <td>${usageLimitDisplay}</td>
                    <td>
                        ${coupon.minimum_spend ? `<div>Min: $${coupon.minimum_spend}</div>` : ''}
                        ${coupon.maximum_spend ? `<div>Max: $${coupon.maximum_spend}</div>` : ''}
                        ${coupon.usage_limit_per_user ? `<div>Per User: ${coupon.usage_limit_per_user}</div>` : ''}
                    </td>
                    <td>${expiryDisplay}</td>
                    <td>
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass}">${statusText}</span>
                    </td>
                    <td class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800" data-coupon-id="${coupon.id}" data-action="edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800" data-coupon-id="${coupon.id}" data-action="delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Initialize select all checkbox
            const selectAllCheckbox = document.getElementById('select-all-coupons');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', (e) => {
                    const checkboxes = document.querySelectorAll('.coupon-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = e.target.checked;
                    });
                });
            }
        },

        updatePaginationInfo() {
            const startSpan = document.getElementById('coupon-pagination-start');
            const endSpan = document.getElementById('coupon-pagination-end');
            const totalSpan = document.getElementById('coupon-pagination-total');
            const nextBtn = document.getElementById('coupon-next-page');
            const prevBtn = document.getElementById('coupon-prev-page');

            if (startSpan && endSpan && totalSpan && nextBtn && prevBtn) {
                const startIndex = (this.state.currentPage - 1) * this.state.itemsPerPage;
                const endIndex = Math.min(startIndex + this.state.itemsPerPage, this.state.totalCoupons);

                if (this.state.totalCoupons === 0) {
                    startSpan.textContent = '0';
                    endSpan.textContent = '0';
                } else {
                    startSpan.textContent = (startIndex + 1).toLocaleString();
                    endSpan.textContent = endIndex.toLocaleString();
                }
                totalSpan.textContent = this.state.totalCoupons.toLocaleString();

                prevBtn.disabled = this.state.currentPage === 1;
                nextBtn.disabled = endIndex >= this.state.totalCoupons;

                 prevBtn.classList.toggle('opacity-50', prevBtn.disabled);
                 prevBtn.classList.toggle('cursor-not-allowed', prevBtn.disabled);
                 nextBtn.classList.toggle('opacity-50', nextBtn.disabled);
                 nextBtn.classList.toggle('cursor-not-allowed', nextBtn.disabled);
            }
        },

        resetCouponForm() {
            const form = document.getElementById('coupon-form');
            if (form) {
                form.reset();
                 document.getElementById('coupon-id').value = ''; // Clear hidden ID
            }
        },

        submitCouponForm(formData) {
            if (!this.validateCouponForm()) {
                this.showNotification('Please fix the errors in the form.', 'error');
                return;
            }

            const couponId = formData.get('coupon_id');
            const couponData = {
                 id: couponId || `coupon_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`,
                 code: formData.get('coupon_code'),
                 description: formData.get('coupon_description'),
                 discount_type: formData.get('discount_type'),
                 coupon_amount: parseFloat(formData.get('coupon_amount')).toFixed(2),
                 minimum_spend: formData.get('minimum_spend') ? parseFloat(formData.get('minimum_spend')).toFixed(2) : '',
                 maximum_spend: formData.get('maximum_spend') ? parseFloat(formData.get('maximum_spend')).toFixed(2) : '',
                 usage_limit: formData.get('usage_limit') ? parseInt(formData.get('usage_limit')) : '',
                 usage_limit_per_user: formData.get('usage_limit_per_user') ? parseInt(formData.get('usage_limit_per_user')) : '',
                 expiry_date: formData.get('expiry_date'),
                 individual_use: formData.get('individual_use') === 'on',
                 exclude_sale_items: formData.get('exclude_sale_items') === 'on',
                 free_shipping: formData.get('free_shipping') === 'on',
                 allowed_products: formData.getAll('allowed_products[]'),
                 excluded_products: formData.getAll('excluded_products[]'),
                 allowed_categories: formData.getAll('allowed_categories[]'),
                 excluded_categories: formData.getAll('excluded_categories[]'),
                 usage_count: couponId ? this.state.fakeCoupons.find(c => c.id === couponId)?.usage_count || 0 : 0,
                 status: couponId ? this.state.fakeCoupons.find(c => c.id === couponId)?.status : 'active', // Maintain status on edit, default to active on new
                 date_created: couponId ? this.state.fakeCoupons.find(c => c.id === couponId)?.date_created : new Date().toISOString().split('T')[0] // Add creation date
            };

            console.log('Submitting coupon data:', couponData);

            if (couponId) {
                 // Simulate update
                 const index = this.state.fakeCoupons.findIndex(c => c.id === couponId);
                 if (index !== -1) {
                      this.state.fakeCoupons[index] = couponData;
                      console.log('Coupon updated:', couponData);
                      this.showNotification('Coupon updated successfully!', 'success');
                 }
            } else {
                 // Simulate add new
                 this.state.fakeCoupons.push(couponData);
                 this.state.totalCoupons++; // Increment total count
                 console.log('Coupon added:', couponData);
                 this.showNotification('Coupon added successfully!', 'success');
            }

            // After saving, reload the list and close the modal
            this.loadCoupons();
            this.initCharts(); // Update analytics charts
            document.getElementById('coupon-modal').classList.add('hidden');
        },

        editCoupon(couponId) {
             console.log(`Editing coupon with ID: ${couponId}`);
             const coupon = this.state.fakeCoupons.find(c => c.id === couponId);
             if (!coupon) {
                 console.error(`Coupon with ID ${couponId} not found for editing.`);
                 this.showNotification('Coupon not found.', 'error');
                 return;
             }

             // Populate the form fields
             document.getElementById('coupon-modal-title').textContent = 'Edit Coupon';
             document.getElementById('coupon-id').value = coupon.id || '';
             document.getElementById('coupon-code').value = coupon.code || '';
             document.getElementById('coupon-description').value = coupon.description || '';
             document.getElementById('discount-type').value = coupon.discount_type || 'percent';
             document.getElementById('coupon-amount').value = parseFloat(coupon.coupon_amount) || 0;
             document.getElementById('minimum-spend').value = coupon.minimum_spend || '';
             document.getElementById('maximum-spend').value = coupon.maximum_spend || '';
             document.getElementById('usage-limit').value = coupon.usage_limit || '';
             document.getElementById('usage-limit-per-user').value = coupon.usage_limit_per_user || '';
             document.getElementById('expiry-date').value = coupon.expiry_date || '';
             document.getElementById('individual-use').checked = coupon.individual_use || false;
             document.getElementById('exclude-sale-items').checked = coupon.exclude_sale_items || false;
             document.getElementById('free-shipping').checked = coupon.free_shipping || false;

             // Populate restriction fields with selected values
             this.populateSelect(document.getElementById('allowed-products'), coupon.allowed_products || []);
             this.populateSelect(document.getElementById('excluded-products'), coupon.excluded_products || []);
             this.populateSelect(document.getElementById('allowed-categories'), coupon.allowed_categories || []);
             this.populateSelect(document.getElementById('excluded-categories'), coupon.excluded_categories || []);

             // Clear any previous validation errors
             this.clearAllValidationErrors();

             // Show the modal
             document.getElementById('coupon-modal').classList.remove('hidden');
        },

        // Helper function to populate a select element with options and set selected values
        populateSelect(selectElement, selectedValues) {
            if (!selectElement) return;

            // Clear existing options before populating (if needed, or assume static for now)
            // In a real app, you might fetch and populate options based on available products/categories

            // For demonstration, we assume options are already in the HTML
            // We just need to set the 'selected' property based on selectedValues
            Array.from(selectElement.options).forEach(option => {
                option.selected = selectedValues.includes(option.value);
            });
        },

        deleteCoupon(couponId) {
             // Simulate deletion
             this.state.fakeCoupons = this.state.fakeCoupons.filter(coupon => coupon.id !== couponId);
             this.state.totalCoupons--;
             console.log(`Coupon ${couponId} deleted.`);

             // After deleting, reload the list and update analytics
             this.loadCoupons();
             this.initCharts(); // Update analytics charts

             // Assuming showNotification exists
             this.showNotification('Coupon deleted successfully.', 'success');
        },

        generateRandomCouponCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            const codeLength = 8;
            let code = '';
            
            // Generate first 4 characters as letters
            for (let i = 0; i < 4; i++) {
                code += chars.charAt(Math.floor(Math.random() * 26));
            }
            
            // Add a hyphen
            code += '-';
            
            // Generate last 3 characters as numbers
            for (let i = 0; i < 3; i++) {
                code += chars.charAt(Math.floor(Math.random() * 10) + 26);
            }
            
            return code;
        },

        validateCouponForm() {
            const form = document.getElementById('coupon-form');
            if (!form) return false;

            // Clear previous errors
            this.clearAllValidationErrors();

            const amountInput = document.getElementById('coupon-amount');
            const amount = parseFloat(amountInput.value);
            const discountType = document.getElementById('discount-type').value;
            const minSpendInput = document.getElementById('minimum-spend');
            const minSpend = parseFloat(minSpendInput.value);
            const maxSpendInput = document.getElementById('maximum-spend');
            const maxSpend = parseFloat(maxSpendInput.value);
            const usageLimitInput = document.getElementById('usage-limit');
            const usageLimit = parseInt(usageLimitInput.value);
            const usageLimitPerUserInput = document.getElementById('usage-limit-per-user');
            const usageLimitPerUser = parseInt(usageLimitPerUserInput.value);

            let isValid = true;

            // Validate amount based on discount type
            if (discountType === 'percent') {
                if (isNaN(amount) || amount < 0 || amount > 100) {
                   this.showValidationError(amountInput, 'Percentage discount must be between 0 and 100.');
                   isValid = false;
                }
            } else { // fixed_cart or fixed_product
                if (isNaN(amount) || amount < 0) {
                    this.showValidationError(amountInput, 'Amount must be a non-negative number.');
                    isValid = false;
                }
            }

            // Validate minimum and maximum spend
            if (minSpendInput.value !== '' && (isNaN(minSpend) || minSpend < 0)) {
               this.showValidationError(minSpendInput, 'Minimum spend must be a non-negative number.');
                isValid = false;
            }
            if (maxSpendInput.value !== '' && (isNaN(maxSpend) || maxSpend < 0)) {
                this.showValidationError(maxSpendInput, 'Maximum spend must be a non-negative number.');
                isValid = false;
            }
            if (minSpendInput.value !== '' && maxSpendInput.value !== '' && minSpend > maxSpend) {
                this.showValidationError(minSpendInput, 'Min spend cannot be greater than max spend.');
                 this.showValidationError(maxSpendInput, 'Max spend cannot be less than min spend.');
                isValid = false;
            }

            // Validate usage limits
            if (usageLimitInput.value !== '' && (isNaN(usageLimit) || usageLimit < 0)) {
               this.showValidationError(usageLimitInput, 'Usage limit must be a non-negative integer.');
               isValid = false;
            }
            if (usageLimitPerUserInput.value !== '' && (isNaN(usageLimitPerUser) || usageLimitPerUser < 0)) {
                this.showValidationError(usageLimitPerUserInput, 'Usage limit per user must be a non-negative integer.');
               isValid = false;
            }
            if (usageLimitInput.value !== '' && usageLimitPerUserInput.value !== '' && usageLimitPerUser > usageLimit) {
                this.showValidationError(usageLimitPerUserInput, 'Per user limit cannot exceed total limit.');
                this.showValidationError(usageLimitInput, 'Total limit cannot be less than per user limit.');
                isValid = false;
            }

            // Add validation for coupon code being empty
            const codeInput = document.getElementById('coupon-code');
            if (codeInput.value.trim() === '') {
                 this.showValidationError(codeInput, 'Coupon code is required.');
                 isValid = false;
            }

            return isValid;
        },

        updateCouponStatus(couponId, status) {
            const index = this.state.fakeCoupons.findIndex(c => c.id === couponId);
            if (index !== -1) {
                this.state.fakeCoupons[index].status = status;
                this.loadCoupons();
                this.showNotification(`Coupon status updated to ${status}.`, 'success');
            }
        },

        applyFilters() {
            const discountType = document.getElementById('filter-discount-type')?.value;
            const status = document.getElementById('filter-status')?.value;
            const dateFrom = document.getElementById('filter-date-from')?.value;
            const dateTo = document.getElementById('filter-date-to')?.value;

            let filteredCoupons = [...this.state.fakeCoupons];

            if (discountType) {
                filteredCoupons = filteredCoupons.filter(coupon => coupon.discount_type === discountType);
            }

            if (status) {
                filteredCoupons = filteredCoupons.filter(coupon => {
                    if (status === 'expired') {
                        return coupon.expiry_date && new Date(coupon.expiry_date) < new Date();
                    }
                    return coupon.status === status;
                });
            }

            if (dateFrom) {
                filteredCoupons = filteredCoupons.filter(coupon => 
                    new Date(coupon.date_created) >= new Date(dateFrom)
                );
            }

            if (dateTo) {
                filteredCoupons = filteredCoupons.filter(coupon => 
                    new Date(coupon.date_created) <= new Date(dateTo)
                );
            }

            this.state.filteredCoupons = filteredCoupons;
            this.loadCoupons();
        },

        resetFilters() {
            document.getElementById('filter-discount-type').value = '';
            document.getElementById('filter-status').value = '';
            document.getElementById('filter-date-from').value = '';
            document.getElementById('filter-date-to').value = '';
            this.state.filteredCoupons = null;
            this.loadCoupons();
        },

        showImportModal() {
            const modal = document.createElement('div');
            modal.className = 'woodash-modal';
            modal.innerHTML = `
                <div class="woodash-modal-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Import Coupons</h3>
                        <button class="text-gray-400 hover:text-gray-600" id="close-import-modal">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <form id="import-form">
                        <div class="woodash-form-group">
                            <label class="woodash-form-label">Choose CSV File</label>
                            <input type="file" accept=".csv" class="woodash-form-input" required>
                        </div>
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" class="woodash-btn woodash-btn-secondary" id="cancel-import">Cancel</button>
                            <button type="submit" class="woodash-btn woodash-btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            `;
            document.body.appendChild(modal);

            const closeBtn = modal.querySelector('#close-import-modal');
            const cancelBtn = modal.querySelector('#cancel-import');
            const form = modal.querySelector('#import-form');

            closeBtn?.addEventListener('click', () => modal.remove());
            cancelBtn?.addEventListener('click', () => modal.remove());
            form?.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleImport(e.target);
                modal.remove();
            });
        },

        handleImport(form) {
            const file = form.querySelector('input[type="file"]').files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                const csv = e.target.result;
                const coupons = this.parseCSV(csv);
                this.importCoupons(coupons);
            };
            reader.readAsText(file);
        },

        parseCSV(csv) {
            const lines = csv.split('\n');
            const headers = lines[0].split(',');
            return lines.slice(1).map(line => {
                const values = line.split(',');
                return headers.reduce((obj, header, index) => {
                    obj[header.trim()] = values[index]?.trim();
                    return obj;
                }, {});
            });
        },

        importCoupons(coupons) {
            coupons.forEach(coupon => {
                if (!this.state.fakeCoupons.find(c => c.code === coupon.code)) {
                    this.state.fakeCoupons.push({
                        ...coupon,
                        id: `coupon_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`,
                        status: 'active',
                        usage_count: 0
                    });
                }
            });
            this.loadCoupons();
        },

        exportAllCoupons() {
            const csv = this.convertToCSV(this.state.fakeCoupons);
            this.downloadCSV(csv, 'all-coupons.csv');
        },

        convertToCSV(coupons) {
            const headers = ['code', 'description', 'discount_type', 'coupon_amount', 'minimum_spend', 
                           'maximum_spend', 'usage_limit', 'usage_limit_per_user', 'expiry_date', 
                           'individual_use', 'exclude_sale_items', 'free_shipping'];
            
            const csvRows = [
                headers.join(','),
                ...coupons.map(coupon => 
                    headers.map(header => coupon[header] || '').join(',')
                )
            ];
            
            return csvRows.join('\n');
        },

        downloadCSV(csv, filename) {
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            link.click();
        },

        showSaveTemplateModal() {
            const modal = document.createElement('div');
            modal.className = 'woodash-modal';
            modal.innerHTML = `
                <div class="woodash-modal-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Save as Template</h3>
                        <button class="text-gray-400 hover:text-gray-600" id="close-template-modal">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <form id="template-form">
                        <div class="woodash-form-group">
                            <label class="woodash-form-label">Template Name</label>
                            <input type="text" class="woodash-form-input" required>
                        </div>
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" class="woodash-btn woodash-btn-secondary" id="cancel-template">Cancel</button>
                            <button type="submit" class="woodash-btn woodash-btn-primary">Save Template</button>
                        </div>
                    </form>
                </div>
            `;
            document.body.appendChild(modal);

            const closeBtn = modal.querySelector('#close-template-modal');
            const cancelBtn = modal.querySelector('#cancel-template');
            const form = modal.querySelector('#template-form');

            closeBtn?.addEventListener('click', () => modal.remove());
            cancelBtn?.addEventListener('click', () => modal.remove());
            form?.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveTemplate(e.target);
                modal.remove();
            });
        },

        saveTemplate(form) {
            const name = form.querySelector('input[type="text"]').value;
            const currentCoupon = this.getCurrentCouponData();
            
            if (!this.state.templates) {
                this.state.templates = [];
            }
            
            this.state.templates.push({
                name,
                data: currentCoupon
            });
            
            this.renderTemplates();
        },

        getCurrentCouponData() {
            return {
                discount_type: document.getElementById('discount-type').value,
                coupon_amount: document.getElementById('coupon-amount').value,
                minimum_spend: document.getElementById('minimum-spend').value,
                maximum_spend: document.getElementById('maximum-spend').value,
                usage_limit: document.getElementById('usage-limit').value,
                usage_limit_per_user: document.getElementById('usage-limit-per-user').value,
                individual_use: document.getElementById('individual-use').checked,
                exclude_sale_items: document.getElementById('exclude-sale-items').checked,
                free_shipping: document.getElementById('free-shipping').checked
            };
        },

        renderTemplates() {
            const templatesContainer = document.getElementById('coupon-templates');
            if (!templatesContainer || !this.state.templates) return;

            templatesContainer.innerHTML = this.state.templates.map(template => `
                <div class="woodash-card p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h5 class="font-medium text-gray-900">${template.name}</h5>
                        <button class="text-red-600 hover:text-red-800" data-template-name="${template.name}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                    <div class="text-sm text-gray-600 mb-3">
                        ${template.data.discount_type === 'percent' ? 
                            `${template.data.coupon_amount}% off` : 
                            `$${template.data.coupon_amount} off`}
                    </div>
                    <button class="woodash-btn woodash-btn-secondary w-full" data-template-name="${template.name}">
                        Use Template
                    </button>
                </div>
            `).join('');

            // Add event listeners for template actions
            templatesContainer.querySelectorAll('button').forEach(button => {
                const templateName = button.dataset.templateName;
                if (button.classList.contains('woodash-btn-secondary')) {
                    button.addEventListener('click', () => this.useTemplate(templateName));
                } else {
                    button.addEventListener('click', () => this.deleteTemplate(templateName));
                }
            });
        },

        useTemplate(templateName) {
            const template = this.state.templates.find(t => t.name === templateName);
            if (!template) return;

            Object.entries(template.data).forEach(([key, value]) => {
                const element = document.getElementById(key);
                if (element) {
                    if (element.type === 'checkbox') {
                        element.checked = value;
                    } else {
                        element.value = value;
                    }
                }
            });
        },

        deleteTemplate(templateName) {
            this.state.templates = this.state.templates.filter(t => t.name !== templateName);
            this.renderTemplates();
        },

        showNotification(message, type = 'info', duration = 3000) {
            const container = document.getElementById('notification-container');
            if (!container) return;

            const notification = document.createElement('div');
            notification.classList.add('woodash-notification', `woodash-notification-${type}`);
            notification.textContent = message;
            notification.style.padding = '0.75rem 1.5rem';
            notification.style.borderRadius = '0.5rem';
            notification.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
            notification.style.color = 'white';
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s ease-in-out';
            notification.style.minWidth = '200px';
            notification.style.maxWidth = '300px';
            notification.style.wordBreak = 'break-word';

            switch(type) {
                case 'success':
                    notification.style.backgroundColor = '#10B981'; // Green
                    break;
                case 'error':
                    notification.style.backgroundColor = '#EF4444'; // Red
                    break;
                case 'warning':
                    notification.style.backgroundColor = '#F59E0B'; // Yellow/Orange
                    break;
                case 'info':
                default:
                    notification.style.backgroundColor = '#3B82F6'; // Blue
            }

            container.appendChild(notification);

            // Fade in
            setTimeout(() => {
                notification.style.opacity = '1';
            }, 10);

            // Fade out and remove
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.addEventListener('transitionend', () => notification.remove());
            }, duration);
        },

        // Function to show validation errors
        showValidationError(inputElement, message) {
            if (!inputElement) return;

            const formGroup = inputElement.closest('.woodash-form-group');
            if (!formGroup) return;

            // Add invalid class to input
            inputElement.classList.add('invalid');

            // Check if an error message already exists for this input
            let errorMessage = formGroup.querySelector('.text-red-600');
            if (!errorMessage) {
                errorMessage = document.createElement('p');
                errorMessage.classList.add('text-red-600');
                formGroup.appendChild(errorMessage);
            }

            errorMessage.textContent = message;
        },

        // Function to clear validation errors
        clearValidationError(inputElement) {
             if (!inputElement) return;

             inputElement.classList.remove('invalid');

             const formGroup = inputElement.closest('.woodash-form-group');
             if (!formGroup) return;

             const errorMessage = formGroup.querySelector('.text-red-600');
             if (errorMessage) {
                 errorMessage.remove();
             }
        },

        // Clear all validation errors in the form
        clearAllValidationErrors() {
             document.querySelectorAll('#coupon-form .invalid').forEach(input => {
                 this.clearValidationError(input);
             });
        },

        // Bulk Actions Handler
        handleBulkActions(action, couponIds) {
            if (!action || couponIds.length === 0) return;

            switch(action) {
                case 'delete':
                    if (confirm(`Are you sure you want to delete ${couponIds.length} coupon(s)?`)) {
                        couponIds.forEach(id => this.deleteCoupon(id)); // Reuse deleteCoupon for individual deletion
                         this.showNotification(`${couponIds.length} coupon(s) deleted.`, 'success');
                    }
                    break;
                case 'enable':
                    couponIds.forEach(id => this.updateCouponStatus(id, 'active'));
                     this.showNotification(`${couponIds.length} coupon(s) enabled.`, 'success');
                    break;
                case 'disable':
                    couponIds.forEach(id => this.updateCouponStatus(id, 'inactive'));
                    this.showNotification(`${couponIds.length} coupon(s) disabled.`, 'success');
                    break;
                case 'export':
                     const selectedCoupons = this.state.fakeCoupons.filter(coupon => couponIds.includes(coupon.id));
                    const csv = this.convertToCSV(selectedCoupons);
                    this.downloadCSV(csv, 'selected-coupons.csv');
                     this.showNotification(`${couponIds.length} coupon(s) exported.`, 'success');
                    break;
            }

            // Deselect checkboxes and reset bulk action select after action
            document.querySelectorAll('.coupon-checkbox:checked').forEach(checkbox => checkbox.checked = false);
            document.getElementById('select-all-coupons').checked = false;
            document.getElementById('bulk-action').value = '';
        },

        // Event listener for bulk action button
        initBulkActionEventListener() {
            const applyBulkActionBtn = document.getElementById('apply-bulk-action');
            const bulkActionSelect = document.getElementById('bulk-action');

            applyBulkActionBtn?.addEventListener('click', () => {
                const action = bulkActionSelect?.value;
                if (!action) {
                    this.showNotification('Please select a bulk action.', 'warning');
                    return;
                }

                const selectedCoupons = Array.from(document.querySelectorAll('.coupon-checkbox:checked'))
                    .map(checkbox => checkbox.dataset.couponId);

                if (selectedCoupons.length === 0) {
                    this.showNotification('Please select at least one coupon.', 'warning');
                    return;
                }

                this.handleBulkActions(action, selectedCoupons);
            });
        }
    };

    // Add a container for notifications at the end of the body
    const notificationContainer = document.createElement('div');
    notificationContainer.id = 'notification-container';
    notificationContainer.style.position = 'fixed';
    notificationContainer.style.top = '1rem';
    notificationContainer.style.right = '1rem';
    notificationContainer.style.zIndex = '1000';
    notificationContainer.style.display = 'flex';
    notificationContainer.style.flexDirection = 'column';
    notificationContainer.style.gap = '0.5rem';
    document.body.appendChild(notificationContainer);

    CouponsManager.init();
});
</script>

    <!-- Add Customer Modal -->
    <div id="add-customer-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden woodash-fade-in">
    <div class="woodash-card w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 woodash-glass-effect relative">
        <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-4">
            <h3 class="text-xl font-bold woodash-gradient-text">Add New Customer</h3>
            <button class="text-gray-400 hover:text-red-600 transition-colors" id="close-add-customer-modal">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <form id="add-customer-form" class="space-y-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer-first-name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                    <input type="text" id="customer-first-name" name="customer-first-name" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                </div>
                <div>
                    <label for="customer-last-name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                    <input type="text" id="customer-last-name" name="customer-last-name" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                </div>
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer-email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" id="customer-email" name="customer-email" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                </div>
                <div>
                    <label for="customer-phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="tel" id="customer-phone" name="customer-phone"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="customer-address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea id="customer-address" name="customer-address" rows="3"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2"></textarea>
            </div>

            <!-- Customer Role -->
            <div>
                <label for="customer-role" class="block text-sm font-medium text-gray-700 mb-1">Customer Role</label>
                <select id="customer-role" name="customer-role"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                    <option value="customer">Regular Customer</option>
                    <option value="wholesale">Wholesale Customer</option>
                    <option value="vip">VIP Customer</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" class="woodash-btn woodash-btn-secondary" id="cancel-add-customer-btn">Cancel</button>
                <button type="submit" class="woodash-btn woodash-btn-primary" id="save-customer-btn">
                    <i class="fa-solid fa-save mr-2"></i>
                    Save Customer
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

<!-- Add this before the closing </body> tag -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the Add New Item button
    const addNewItemBtn = document.getElementById('add-new-item-btn');
    if (addNewItemBtn) {
        addNewItemBtn.addEventListener('click', function() {
            if (typeof showNewCouponModal === 'function') {
                showNewCouponModal();
            } else {
                console.error('showNewCouponModal function is not defined');
            }
        });
    }
});
</script>
