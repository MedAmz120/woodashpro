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
    <title>Reviews Dashboard</title>
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
            transition: transform 0.3s ease; /* Keep existing transition */
            z-index: 50; /* Keep existing z-index */
            flex-shrink: 0; /* Keep existing flex-shrink */
            position: fixed; /* Keep existing position */
            top: 0; /* Keep existing top */
            left: 0; /* Keep existing left */
            bottom: 0; /* Keep existing bottom */
            overflow-y: auto; /* Keep existing overflow-y */
            width: 16rem; /* Keep existing width */
            background: var(--card-background); /* Use variable */
            border-right: 1px solid var(--border-color); /* Use variable */
            padding: 1.5rem; /* Keep existing padding */
        }

        .woodash-nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: var(--secondary-color); /* Use variable */
            transition: var(--transition-base);
        }

        .woodash-nav-link:hover,
        .woodash-nav-link.active {
            background: rgba(0, 204, 97, 0.1); /* Keep as is or use variable */
            color: var(--primary-dark); /* Use variable */
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

        .woodash-badge-danger {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        .woodash-badge-warning {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .woodash-badge-blue {
            background-color: #DBEAFE;
            color: #1E40AF;
        }

        /* Modal Styles */
        .woodash-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
            display: none;
        }

        .woodash-modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
        }

        .woodash-form-group {
            margin-bottom: 1rem;
        }

        .woodash-form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .woodash-form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }

        .woodash-form-input:focus {
            border-color: #60A5FA;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        .woodash-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.95);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        /* Form Styles */
        .woodash-form-group {
            margin-bottom: 1rem;
        }

        .woodash-form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .woodash-form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }

        .woodash-form-input:focus {
            border-color: #60A5FA;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        .woodash-checkbox {
            margin-right: 0.5rem;
        }

        .woodash-checkbox:checked {
            background-color: #60A5FA;
            border-color: #60A5FA;
        }

        .woodash-checkbox:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        .woodash-checkbox-label {
            display: inline-block;
            margin-left: 0.5rem;
        }

        .woodash-checkbox-label:hover {
            color: #60A5FA;
        }

        .woodash-checkbox-label:active {
            color: #3B82F6;
        }

        .woodash-checkbox-label:focus {
            color: #3B82F6;
        }

        .woodash-checkbox-label:visited {
            color: #3B82F6;
        }

        .woodash-checkbox-label:link {
            color: #3B82F6;
        }

        .woodash-checkbox-label:focus-within {
            color: #3B82F6;
        }

        .woodash-checkbox-label:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:focus-within:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:focus-within:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
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
    <title>Reviews Dashboard</title>
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
            transition: transform 0.3s ease; /* Keep existing transition */
            z-index: 50; /* Keep existing z-index */
            flex-shrink: 0; /* Keep existing flex-shrink */
            position: fixed; /* Keep existing position */
            top: 0; /* Keep existing top */
            left: 0; /* Keep existing left */
            bottom: 0; /* Keep existing bottom */
            overflow-y: auto; /* Keep existing overflow-y */
            width: 16rem; /* Keep existing width */
            background: var(--card-background); /* Use variable */
            border-right: 1px solid var(--border-color); /* Use variable */
            padding: 1.5rem; /* Keep existing padding */
        }

        .woodash-nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: var(--secondary-color); /* Use variable */
            transition: var(--transition-base);
        }

        .woodash-nav-link:hover,
        .woodash-nav-link.active {
            background: rgba(0, 204, 97, 0.1); /* Keep as is or use variable */
            color: var(--primary-dark); /* Use variable */
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

        .woodash-badge-danger {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        .woodash-badge-warning {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .woodash-badge-blue {
            background-color: #DBEAFE;
            color: #1E40AF;
        }

        /* Modal Styles */
        .woodash-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
            display: none;
        }

        .woodash-modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
        }

        .woodash-form-group {
            margin-bottom: 1rem;
        }

        .woodash-form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .woodash-form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }

        .woodash-form-input:focus {
            border-color: #60A5FA;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        .woodash-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.95);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        /* Form Styles */
        .woodash-form-group {
            margin-bottom: 1rem;
        }

        .woodash-form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .woodash-form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }

        .woodash-form-input:focus {
            border-color: #60A5FA;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        .woodash-checkbox {
            margin-right: 0.5rem;
        }

        .woodash-checkbox:checked {
            background-color: #60A5FA;
            border-color: #60A5FA;
        }

        .woodash-checkbox:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        .woodash-checkbox-label {
            display: inline-block;
            margin-left: 0.5rem;
        }

        .woodash-checkbox-label:hover {
            color: #60A5FA;
        }

        .woodash-checkbox-label:active {
            color: #3B82F6;
        }

        .woodash-checkbox-label:focus {
            color: #3B82F6;
        }

        .woodash-checkbox-label:visited {
            color: #3B82F6;
        }

        .woodash-checkbox-label:link {
            color: #3B82F6;
        }

        .woodash-checkbox-label:focus-within {
            color: #3B82F6;
        }

        .woodash-checkbox-label:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:focus-within:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:focus-within:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:before {
            content: "\2713";
            color: #60A5FA;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .woodash-checkbox-label:checked:hover:after {
            content: "\2717";
            color: #991B1B;
            font-weight: bold;
    </style>
<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Check if user has required capabilities
if (!current_user_can('manage_woocommerce')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'woodashh'));
}

// Verify nonce for any form submissions
if (isset($_POST['woodashh_reviews_nonce'])) {
    if (!wp_verify_nonce($_POST['woodashh_reviews_nonce'], 'woodashh_reviews_action')) {
        wp_die(__('Security check failed. Please try again.', 'woodashh'));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews Dashboard</title>

    <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Lottie player -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        /* Enhanced Base Styles */
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
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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

        /* Sidebar Specific Styles */
        .woodash-sidebar {
            transition: transform 0.3s ease;
            z-index: 50;
            flex-shrink: 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
        }

        .woodash-nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: #6B7280;
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

        /* Mobile Menu Toggle */
        .woodash-menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 60;
        }

        @media (max-width: 768px) {
            .woodash-sidebar {
                transform: translateX(-100%);
            }

            .woodash-sidebar.active {
                transform: translateX(0);
            }

            .woodash-main {
                margin-left: 0;
            }

            .woodash-menu-toggle {
                display: flex;
            }
        }

        /* Enhanced Card Styles */
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

        /* Metric Card Styles */
        .woodash-metric-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            transition: var(--transition-base);
            box-shadow: var(--shadow-sm);
        }

        .woodash-metric-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .woodash-metric-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6B7280;
            margin-bottom: 0.25rem;
        }

        .woodash-metric-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1F2937;
        }

        .woodash-metric-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }

        .woodash-metric-green {
            background: linear-gradient(135deg, #00CC61, #00b357);
        }

        .woodash-metric-yellow {
            background: linear-gradient(135deg, #F59E0B, #D97706);
        }

        .woodash-metric-red {
            background: linear-gradient(135deg, #EF4444, #DC2626);
        }

        .woodash-metric-blue {
            background: linear-gradient(135deg, #3B82F6, #2563EB);
        }

        /* Enhanced Button Styles */
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

        /* Enhanced Table Styles */
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

        /* Enhanced Search Bar */
        .woodash-search-container {
            position: relative;
            transition: var(--transition-base);
            border-radius: 0.5rem;
            overflow: hidden;
            display: flex;
            align-items: center;
            border: 1px solid var(--border-color);
        }

        .woodash-search-container:focus-within {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-lg);
        }

        .woodash-search-icon {
            position: absolute;
            left: 0.75rem;
            color: #6B7280;
            font-size: 1rem;
        }

        .woodash-search-input {
            width: 100%;
            padding: 0.625rem 2.5rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            outline: none;
            font-size: 1rem;
            color: #1F2937;
        }

        .woodash-search-input::placeholder {
            color: #9CA3AF;
        }

        /* Enhanced Badges */
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

        .woodash-badge-danger {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        .woodash-badge-warning {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .woodash-badge-blue {
            background-color: #DBEAFE;
            color: #1E40AF;
        }

        /* Enhanced Notifications */
        .woodash-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            color: #1F2937;
            box-shadow: var(--shadow-lg);
            animation: slideIn 0.3s ease forwards;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            min-width: 250px;
        }

        .woodash-notification-success {
            border-left: 4px solid #00CC61;
        }

        .woodash-notification-danger {
            border-left: 4px solid #EF4444;
        }

        .woodash-notification-warning {
            border-left: 4px solid #F59E0B;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Enhanced Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .woodash-animate-in {
            animation: fadeInUp 0.5s ease forwards;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .woodash-slide-up {
            animation: slideUp 0.3s ease forwards;
        }

        /* Enhanced Scrollbar */
        .woodash-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) transparent;
        }

        .woodash-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .woodash-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .woodash-scrollbar::-webkit-scrollbar-thumb {
            background-color: var(--primary-color);
            border-radius: 3px;
        }
    </style>
</head>
<body>

<button id="woodash-menu-toggle" class="woodash-menu-toggle woodash-btn woodash-btn-secondary woodash-hover-card">
    <i class="fa-solid fa-bars"></i>
</button>

<div id="woodash-dashboard" class="woodash-fullscreen flex">
    <!-- Sidebar -->
    <aside class="woodash-sidebar w-64 bg-white/90 border-r border-gray-100 p-6 woodash-glass-effect">
        <div class="flex items-center gap-3 mb-8 woodash-fade-in">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center woodash-glow">
                <i class="fa-solid fa-star text-white text-xl"></i>
            </div>
            <h2 class="text-xl font-bold woodash-gradient-text">WooDash Pro</h2>
        </div>

        <nav class="space-y-1">
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-dashboard')); ?>"
               class="woodash-nav-link woodash-hover-card woodash-slide-up group"
               style="animation-delay: 0.1s">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-gauge text-white"></i>
                    </div>
                    <span>Dashboard</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-products')); ?>"
               class="woodash-nav-link woodash-hover-card woodash-slide-up group"
               style="animation-delay: 0.2s">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-box text-white"></i>
                    </div>
                    <span>Products</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-customers')); ?>"
            class="woodash-nav-link woodash-hover-card woodash-slide-up group"
            style="animation-delay: 0.4s">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <i class="fa-solid fa-users text-white"></i>
                </div>
                <span>Customers</span>
            </div>
            <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
        </a>
        
        <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-stock')); ?>"
        class="woodash-nav-link woodash-hover-card woodash-slide-up group"
        style="animation-delay: 0.5s">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                <i class="fa-solid fa-boxes-stacked text-white"></i>
            </div>
            <span>Stock</span>
        </div>
        <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
    </a>
    
    <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-reviews')); ?>"
       class="woodash-nav-link active woodash-hover-card woodash-slide-up group"
       style="animation-delay: 0.3s">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                <i class="fa-solid fa-star text-white"></i>
            </div>
            <span>Reviews</span>
        </div>
        <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
    </a>
    <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-coupons')); ?>"
               class="woodash-nav-link woodash-hover-card woodash-slide-up group"
               style="animation-delay: 0.6s">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-ticket text-white"></i>
                    </div>
                    <span>Coupons</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-settings')); ?>"
               class="woodash-nav-link woodash-hover-card woodash-slide-up group"
               style="animation-delay: 0.7s">
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
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 woodash-fade-in">
                <div>
                    <h1 class="text-2xl font-bold woodash-gradient-text">Reviews</h1>
                    <p class="text-gray-500">Manage and respond to customer reviews</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="woodash-search-container">
                        <i class="fa-solid fa-search woodash-search-icon"></i>
                        <input type="text" 
                               placeholder="Search reviews..." 
                               class="woodash-search-input"
                               id="review-search">
                    </div>
                    <div class="relative">
                        <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="filter-btn">
                            <i class="fa-solid fa-filter"></i>
                            <span>Filter</span>
                        </button>
                        <!-- Advanced Filter Dropdown -->
                        <div id="filter-dropdown" class="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-lg border border-gray-100 hidden z-50">
                            <div class="p-4">
                                <h3 class="text-sm font-semibold text-gray-700 mb-3">Advanced Filters</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Date Range</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <input type="date" class="woodash-search-input" id="date-from">
                                            <input type="date" class="woodash-search-input" id="date-to">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Product Category</label>
                                        <select class="woodash-search-input" id="category-filter">
                                            <option value="">All Categories</option>
                                            <option value="electronics">Electronics</option>
                                            <option value="clothing">Clothing</option>
                                            <option value="home">Home & Garden</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Response Status</label>
                                        <select class="woodash-search-input" id="response-filter">
                                            <option value="">All</option>
                                            <option value="responded">Responded</option>
                                            <option value="pending">Pending Response</option>
                                        </select>
                                    </div>
                                    <div class="flex justify-end gap-2 pt-2">
                                        <button class="woodash-btn woodash-btn-secondary" id="reset-filters">Reset</button>
                                        <button class="woodash-btn woodash-btn-primary" id="apply-filters">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="woodash-btn woodash-btn-primary" id="bulk-actions-btn">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                        <span>Bulk Actions</span>
                    </button>
                </div>
            </header>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="woodash-metric-card woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">Total Reviews</h3>
                            <div class="woodash-metric-value">1,248</div>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-sm text-green-600 flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-up text-xs"></i>
                                    <span>12.5%</span>
                                </span>
                                <span class="text-xs text-gray-500">vs last month</span>
                            </div>
                        </div>
                        <div class="woodash-metric-icon woodash-metric-green woodash-float">
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>

                <div class="woodash-metric-card woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">Average Rating</h3>
                            <div class="woodash-metric-value">4.8</div>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-sm text-green-600 flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-up text-xs"></i>
                                    <span>0.2</span>
                                </span>
                                <span class="text-xs text-gray-500">vs last month</span>
                            </div>
                        </div>
                        <div class="woodash-metric-icon woodash-metric-yellow woodash-float">
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>

                <div class="woodash-metric-card woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">Pending Reviews</h3>
                            <div class="woodash-metric-value">12</div>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-sm text-red-600 flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-up text-xs"></i>
                                    <span>3</span>
                                </span>
                                <span class="text-xs text-gray-500">vs last month</span>
                            </div>
                        </div>
                        <div class="woodash-metric-icon woodash-metric-red woodash-float">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="woodash-metric-card woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.4s">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">Response Rate</h3>
                            <div class="woodash-metric-value">98%</div>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-sm text-green-600 flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-up text-xs"></i>
                                    <span>2%</span>
                                </span>
                                <span class="text-xs text-gray-500">vs last month</span>
                            </div>
                        </div>
                        <div class="woodash-metric-icon woodash-metric-blue woodash-float">
                            <i class="fa-solid fa-reply"></i>
                        </div>
                    </div>
                </div>
            </div>


            <!-- AI Insights Panel -->
            <div class="woodash-card p-6 mb-8 woodash-animate-in" style="animation-delay: 0.4s">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">AI Insights</h3>
                    <button class="woodash-btn woodash-btn-secondary text-sm" id="refresh-insights">
                        <i class="fa-solid fa-rotate"></i>
                        <span>Refresh Insights</span>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg">
                        <h4 class="text-sm font-medium text-blue-700 mb-2">Top Keywords</h4>
                        <div class="flex flex-wrap gap-2" id="top-keywords">
                            <span class="px-2 py-1 bg-blue-200 text-blue-800 rounded-full text-xs">quality</span>
                            <span class="px-2 py-1 bg-blue-200 text-blue-800 rounded-full text-xs">fast delivery</span>
                            <span class="px-2 py-1 bg-blue-200 text-blue-800 rounded-full text-xs">value</span>
                            <span class="px-2 py-1 bg-blue-200 text-blue-800 rounded-full text-xs">durable</span>
                        </div>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg">
                        <h4 class="text-sm font-medium text-purple-700 mb-2">Common Complaints</h4>
                        <ul class="text-sm text-purple-800 space-y-1" id="common-complaints">
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation text-purple-500"></i>
                                <span>Shipping delays</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation text-purple-500"></i>
                                <span>Product sizing issues</span>
                            </li>
                        </ul>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg">
                        <h4 class="text-sm font-medium text-green-700 mb-2">Improvement Areas</h4>
                        <ul class="text-sm text-green-800 space-y-1" id="improvement-areas">
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-lightbulb text-green-500"></i>
                                <span>Enhance packaging</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-lightbulb text-green-500"></i>
                                <span>Improve size guide</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Analytics Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <!-- Rating Distribution Chart -->
                <div class="woodash-card p-6 woodash-animate-in" style="animation-delay: 0.5s">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Rating Distribution</h3>
                            <p class="text-sm text-gray-500">Distribution of ratings across all reviews</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="woodash-btn woodash-btn-secondary text-sm" id="chart-week">Week</button>
                            <button class="woodash-btn woodash-btn-secondary text-sm" id="chart-month">Month</button>
                            <button class="woodash-btn woodash-btn-secondary text-sm" id="chart-year">Year</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="text-sm text-gray-600">Average Rating</div>
                            <div class="text-2xl font-bold text-gray-900">4.7</div>
                            <div class="flex items-center text-sm text-green-600">
                                <i class="fa-solid fa-arrow-up mr-1"></i>
                                <span>0.2 from last period</span>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="text-sm text-gray-600">Total Reviews</div>
                            <div class="text-2xl font-bold text-gray-900">1,248</div>
                            <div class="flex items-center text-sm text-green-600">
                                <i class="fa-solid fa-arrow-up mr-1"></i>
                                <span>12.5% from last period</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-5 gap-2">
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-600">5 Stars</div>
                            <div class="text-lg font-bold text-gray-900">65%</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-600">4 Stars</div>
                            <div class="text-lg font-bold text-gray-900">20%</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-600">3 Stars</div>
                            <div class="text-lg font-bold text-gray-900">8%</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-600">2 Stars</div>
                            <div class="text-lg font-bold text-gray-900">4%</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-600">1 Star</div>
                            <div class="text-lg font-bold text-gray-900">3%</div>
                        </div>
                    </div>
                </div>

                <!-- Sentiment Analysis -->
                <div class="woodash-card p-6 woodash-animate-in" style="animation-delay: 0.6s">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Sentiment Analysis</h3>
                            <p class="text-sm text-gray-500">Customer sentiment across all reviews</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="woodash-btn woodash-btn-secondary text-sm" id="sentiment-details">
                                <i class="fa-solid fa-chart-line"></i>
                                <span>Details</span>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="p-4 bg-green-50 rounded-lg">
                            <div class="text-sm text-green-600">Positive</div>
                            <div class="text-2xl font-bold text-green-700">75%</div>
                            <div class="flex items-center text-sm text-green-600">
                                <i class="fa-solid fa-arrow-up mr-1"></i>
                                <span>5% from last period</span>
                            </div>
                        </div>
                        <div class="p-4 bg-yellow-50 rounded-lg">
                            <div class="text-sm text-yellow-600">Neutral</div>
                            <div class="text-2xl font-bold text-yellow-700">15%</div>
                            <div class="flex items-center text-sm text-yellow-600">
                                <i class="fa-solid fa-minus mr-1"></i>
                                <span>No change</span>
                            </div>
                        </div>
                        <div class="p-4 bg-red-50 rounded-lg">
                            <div class="text-sm text-red-600">Negative</div>
                            <div class="text-2xl font-bold text-red-700">10%</div>
                            <div class="flex items-center text-sm text-red-600">
                                <i class="fa-solid fa-arrow-down mr-1"></i>
                                <span>2% from last period</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-sm font-medium text-gray-600 mb-2">Top Sentiment Keywords</div>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">excellent</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">great</span>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">average</span>
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">disappointed</span>
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">poor</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Trends -->
            <div class="woodash-card p-6 mb-8 woodash-animate-in" style="animation-delay: 0.7s">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Review Trends</h3>
                        <p class="text-sm text-gray-500">Review activity over time</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="woodash-btn woodash-btn-secondary text-sm" id="trend-daily">Daily</button>
                        <button class="woodash-btn woodash-btn-secondary text-sm" id="trend-weekly">Weekly</button>
                        <button class="woodash-btn woodash-btn-secondary text-sm" id="trend-monthly">Monthly</button>
                        <button class="woodash-btn woodash-btn-secondary text-sm" id="trend-compare">
                            <i class="fa-solid fa-code-compare"></i>
                            <span>Compare</span>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="text-sm text-blue-600">Total Reviews</div>
                        <div class="text-2xl font-bold text-blue-700">1,248</div>
                        <div class="flex items-center text-sm text-blue-600">
                            <i class="fa-solid fa-arrow-up mr-1"></i>
                            <span>12.5% growth</span>
                        </div>
                    </div>
                    <div class="p-4 bg-purple-50 rounded-lg">
                        <div class="text-sm text-purple-600">Response Rate</div>
                        <div class="text-2xl font-bold text-purple-700">98%</div>
                        <div class="flex items-center text-sm text-purple-600">
                            <i class="fa-solid fa-arrow-up mr-1"></i>
                            <span>2% improvement</span>
                        </div>
                    </div>
                    <div class="p-4 bg-indigo-50 rounded-lg">
                        <div class="text-sm text-indigo-600">Avg. Response Time</div>
                        <div class="text-2xl font-bold text-indigo-700">2.5h</div>
                        <div class="flex items-center text-sm text-indigo-600">
                            <i class="fa-solid fa-arrow-down mr-1"></i>
                            <span>0.5h faster</span>
                        </div>
                    </div>
                    <div class="p-4 bg-pink-50 rounded-lg">
                        <div class="text-sm text-pink-600">Engagement Rate</div>
                        <div class="text-2xl font-bold text-pink-700">85%</div>
                        <div class="flex items-center text-sm text-pink-600">
                            <i class="fa-solid fa-arrow-up mr-1"></i>
                            <span>5% increase</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm font-medium text-gray-600 mb-2">Top Performing Products</div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm">Wireless Headphones Pro X1</span>
                                <span class="text-sm font-medium text-green-600">4.9 ★</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm">Smart Watch Series 5</span>
                                <span class="text-sm font-medium text-green-600">4.8 ★</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm">Bluetooth Speaker Mini</span>
                                <span class="text-sm font-medium text-green-600">4.7 ★</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-600 mb-2">Review Categories</div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm">Product Quality</span>
                                <span class="text-sm font-medium text-blue-600">45%</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm">Customer Service</span>
                                <span class="text-sm font-medium text-blue-600">30%</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm">Shipping & Delivery</span>
                                <span class="text-sm font-medium text-blue-600">25%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Filter Panel -->
            <div id="advanced-filter-panel" class="woodash-card p-6 mb-8 woodash-animate-in hidden" style="animation-delay: 0.8s">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Advanced Filters</h3>
                    <button class="woodash-btn woodash-btn-secondary" id="close-advanced-filter">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" class="woodash-search-input" id="date-from">
                            <input type="date" class="woodash-search-input" id="date-to">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Category</label>
                        <select class="woodash-search-input" id="category-filter">
                            <option value="">All Categories</option>
                            <option value="electronics">Electronics</option>
                            <option value="clothing">Clothing</option>
                            <option value="home">Home & Garden</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Response Status</label>
                        <select class="woodash-search-input" id="response-filter">
                            <option value="">All</option>
                            <option value="responded">Responded</option>
                            <option value="pending">Pending Response</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sentiment</label>
                        <select class="woodash-search-input" id="sentiment-filter">
                            <option value="">All</option>
                            <option value="positive">Positive</option>
                            <option value="neutral">Neutral</option>
                            <option value="negative">Negative</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keywords</label>
                        <input type="text" class="woodash-search-input" id="keyword-filter" placeholder="Enter keywords...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Verified Purchase</label>
                        <select class="woodash-search-input" id="verified-filter">
                            <option value="">All</option>
                            <option value="yes">Verified Only</option>
                            <option value="no">Non-verified Only</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button class="woodash-btn woodash-btn-secondary" id="reset-filters">Reset</button>
                    <button class="woodash-btn woodash-btn-primary" id="apply-filters">Apply Filters</button>
                </div>
            </div>

            <!-- Reviews Table -->
            <div class="woodash-card overflow-hidden woodash-animate-in" style="animation-delay: 0.9s">
                <div class="flex justify-between items-center p-4 border-b border-gray-100">
                    <div class="flex gap-2">
                        <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="advanced-filter-btn">
                            <i class="fa-solid fa-sliders"></i>
                            <span>Advanced Filter</span>
                        </button>
                        <select class="woodash-btn woodash-btn-secondary woodash-hover-card" id="rating-filter">
                            <option value="">All Ratings</option>
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="2">2 Stars</option>
                            <option value="1">1 Star</option>
                        </select>
                        <select class="woodash-btn woodash-btn-secondary woodash-hover-card" id="status-filter">
                            <option value="">All Status</option>
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                            <option value="spam">Spam</option>
                        </select>
                        <select class="woodash-btn woodash-btn-secondary woodash-hover-card" id="sort-filter">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="highest">Highest Rating</option>
                            <option value="lowest">Lowest Rating</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="export-btn">
                            <i class="fa-solid fa-file-export"></i>
                            <span>Export</span>
                        </button>
                        <button class="woodash-btn woodash-btn-primary" id="refresh-btn">
                            <i class="fa-solid fa-rotate"></i>
                            <span>Refresh</span>
                        </button>
                    </div>
                </div>

                <!-- Bulk Actions Dropdown -->
                <div id="bulk-actions-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 hidden z-50">
                    <div class="p-2">
                        <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md" id="bulk-approve">
                            <i class="fa-solid fa-check mr-2"></i>Approve Selected
                        </button>
                        <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md" id="bulk-spam">
                            <i class="fa-solid fa-ban mr-2"></i>Mark as Spam
                        </button>
                        <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md" id="bulk-delete">
                            <i class="fa-solid fa-trash mr-2"></i>Delete Selected
                        </button>
                        <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md" id="bulk-export">
                            <i class="fa-solid fa-file-export mr-2"></i>Export Selected
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto woodash-scrollbar">
                    <table class="woodash-table w-full" id="reviews-table">
                        <thead>
                            <tr>
                                <th class="w-12">
                                    <input type="checkbox" class="rounded border-gray-300 text-[#00CC61] focus:ring-[#00CC61]" id="select-all">
                                </th>
                                <th>Product</th>
                                <th>Review</th>
                                <th>Rating</th>
                                <th>Sentiment</th>
                                <th>Keywords</th>
                                <th>Author</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reviews-table-body">
                            <!-- Reviews will be loaded here by JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Enhanced Reply Modal -->
            <div id="reply-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
                    <div class="flex justify-between items-center p-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Reply to Review</h3>
                        <button class="text-gray-400 hover:text-gray-600" id="close-reply-modal">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="mb-4">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-star text-yellow-400"></i>
                                    <span class="ml-1 text-sm text-gray-600">4.5</span>
                                </div>
                                <span class="text-sm text-gray-500">•</span>
                                <span class="text-sm text-gray-600">John Doe</span>
                                <span class="text-sm text-gray-500">•</span>
                                <span class="text-sm text-gray-600">2 days ago</span>
                            </div>
                            <p class="text-sm text-gray-700 mb-4">"Excellent product! The quality is amazing and the service was great."</p>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Your Reply</label>
                            <textarea class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] h-32" placeholder="Type your reply here..."></textarea>
                        </div>
                        <div class="flex items-center gap-2 mb-4">
                            <label class="flex items-center gap-2 text-sm text-gray-600">
                                <input type="checkbox" class="rounded border-gray-300 text-[#00CC61] focus:ring-[#00CC61]">
                                Send email notification to customer
                            </label>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button class="woodash-btn woodash-btn-secondary" id="cancel-reply">Cancel</button>
                            <button class="woodash-btn woodash-btn-primary">Send Reply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ReviewsManager = {
        state: {
            currentPage: 1,
            itemsPerPage: 10,
            totalItems: 1248,
            selectedItems: new Set(),
            filters: {
                rating: '',
                status: '',
                dateFrom: '',
                dateTo: '',
                category: '',
                responseStatus: '',
                sentiment: '',
                keywords: '',
                verified: '',
                sort: 'newest',
                timeRange: 'month'
            },
            searchQuery: '',
            isLoading: false,
            charts: {},
            insights: {
                keywords: [],
                complaints: [],
                improvements: []
            }
        },

        // Fake Data Generator
        fakeData: {
            products: [
                'Wireless Headphones Pro X1',
                'Smart Watch Series 5',
                'Bluetooth Speaker Mini',
                'Ultra HD Webcam',
                'Wireless Charging Pad',
                'Mechanical Keyboard RGB',
                'Gaming Mouse Pro',
                'Noise Cancelling Earbuds',
                'Smart Home Hub',
                'Fitness Tracker Elite'
            ],
            categories: [
                'Electronics',
                'Audio',
                'Wearables',
                'Smart Home',
                'Gaming',
                'Accessories'
            ],
            names: [
                'John Smith',
                'Emma Johnson',
                'Michael Brown',
                'Sarah Davis',
                'David Wilson',
                'Lisa Anderson',
                'James Taylor',
                'Jennifer Martinez',
                'Robert Garcia',
                'Patricia Robinson'
            ],
            reviewTexts: {
                positive: [
                    'Excellent product! The quality is amazing and the service was great.',
                    'Very satisfied with my purchase. Everything works perfectly.',
                    'Best purchase I\'ve made this year. Highly recommended!',
                    'Great value for money. The features are exactly as described.',
                    'Outstanding product quality and customer service.'
                ],
                neutral: [
                    'Good product overall, but could use some improvements.',
                    'Decent quality, meets my basic needs.',
                    'Average product, nothing special but gets the job done.',
                    'Okay for the price, but don\'t expect premium quality.',
                    'Satisfactory purchase, but there\'s room for improvement.'
                ],
                negative: [
                    'Disappointed with the quality. Not worth the price.',
                    'Product arrived damaged and customer service was unhelpful.',
                    'Poor build quality and the features don\'t work as advertised.',
                    'Would not recommend. Many issues with this product.',
                    'Terrible experience. The product stopped working after a week.'
                ]
            },
            keywords: [
                'quality', 'fast delivery', 'value', 'durable', 'reliable',
                'easy to use', 'great design', 'battery life', 'sound quality',
                'comfortable', 'affordable', 'premium', 'versatile', 'innovative'
            ],
            complaints: [
                'shipping delays',
                'product sizing issues',
                'battery life concerns',
                'connectivity problems',
                'packaging damage',
                'missing accessories',
                'software bugs',
                'warranty issues'
            ],
            improvements: [
                'enhance packaging',
                'improve size guide',
                'extend battery life',
                'better customer support',
                'more color options',
                'additional features',
                'price adjustment',
                'faster shipping'
            ],

            generateReview() {
                const sentiment = Math.random() < 0.7 ? 'positive' : (Math.random() < 0.5 ? 'neutral' : 'negative');
                const rating = sentiment === 'positive' ? Math.floor(Math.random() * 2) + 4 : 
                             (sentiment === 'neutral' ? 3 : Math.floor(Math.random() * 2) + 1);
                
                return {
                    id: Math.random().toString(36).substr(2, 9),
                    product: this.products[Math.floor(Math.random() * this.products.length)],
                    category: this.categories[Math.floor(Math.random() * this.categories.length)],
                    review: this.reviewTexts[sentiment][Math.floor(Math.random() * this.reviewTexts[sentiment].length)],
                    rating: rating,
                    sentiment: sentiment,
                    keywords: this.getRandomKeywords(3),
                    author: this.names[Math.floor(Math.random() * this.names.length)],
                    date: this.getRandomDate(),
                    status: Math.random() < 0.8 ? 'approved' : (Math.random() < 0.5 ? 'pending' : 'spam'),
                    verified: Math.random() < 0.7,
                    response: Math.random() < 0.6 ? this.generateResponse() : null
                };
            },

            getRandomKeywords(count) {
                const shuffled = [...this.keywords].sort(() => 0.5 - Math.random());
                return shuffled.slice(0, count);
            },

            getRandomDate() {
                const start = new Date(2024, 0, 1);
                const end = new Date();
                const date = new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
                return date.toISOString().split('T')[0];
            },

            generateResponse() {
                const responses = [
                    'Thank you for your feedback! We\'re glad you enjoyed our product.',
                    'We appreciate your review and are happy to hear you\'re satisfied.',
                    'Thank you for bringing this to our attention. We\'ll work on improving this aspect.',
                    'We\'re sorry to hear about your experience. Please contact our support team for assistance.',
                    'Thank you for your detailed review. Your feedback helps us improve our products.'
                ];
                return responses[Math.floor(Math.random() * responses.length)];
            },

            generateChartData(timeRange) {
                const data = {
                    rating: [0, 0, 0, 0, 0],
                    sentiment: [0, 0, 0],
                    trend: []
                };

                // Generate rating distribution
                for (let i = 0; i < 100; i++) {
                    const rating = Math.floor(Math.random() * 5) + 1;
                    data.rating[rating - 1]++;
                }

                // Generate sentiment distribution
                for (let i = 0; i < 100; i++) {
                    const sentiment = Math.random() < 0.7 ? 0 : (Math.random() < 0.5 ? 1 : 2);
                    data.sentiment[sentiment]++;
                }

                // Generate trend data
                const points = timeRange === 'week' ? 7 : (timeRange === 'month' ? 30 : 12);
                for (let i = 0; i < points; i++) {
                    data.trend.push(Math.floor(Math.random() * 50) + 20);
                }

                return data;
            }
        },

        init() {
            this.initEventListeners();
            this.loadReviews();
            this.initFilters();
            this.initCharts();
            this.loadInsights();
        },

        loadInsights() {
            // Generate fake insights
            this.insights = {
                keywords: this.fakeData.getRandomKeywords(5),
                complaints: this.fakeData.complaints.slice(0, 3),
                improvements: this.fakeData.improvements.slice(0, 3)
            };
            this.updateInsightsUI();
        },

        updateInsightsUI() {
            // Update keywords
            const keywordsContainer = document.getElementById('top-keywords');
            if (keywordsContainer) {
                keywordsContainer.innerHTML = this.insights.keywords
                    .map(keyword => `<span class="px-2 py-1 bg-blue-200 text-blue-800 rounded-full text-xs">${keyword}</span>`)
                    .join('');
            }

            // Update complaints
            const complaintsContainer = document.getElementById('common-complaints');
            if (complaintsContainer) {
                complaintsContainer.innerHTML = this.insights.complaints
                    .map(complaint => `
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-purple-500"></i>
                            <span>${complaint}</span>
                        </li>
                    `).join('');
            }

            // Update improvements
            const improvementsContainer = document.getElementById('improvement-areas');
            if (improvementsContainer) {
                improvementsContainer.innerHTML = this.insights.improvements
                    .map(improvement => `
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-lightbulb text-green-500"></i>
                            <span>${improvement}</span>
                        </li>
                    `).join('');
            }
        },

        initCharts() {
            this.initRatingChart();
            this.initSentimentChart();
            this.initTrendChart();
        },

        initRatingChart() {
            const ctx = document.getElementById('ratingChart').getContext('2d');
            const data = this.fakeData.generateChartData(this.state.filters.timeRange);
            
            this.charts.rating = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star'],
                    datasets: [{
                        label: 'Number of Reviews',
                        data: data.rating,
                        backgroundColor: [
                            'rgba(0, 204, 97, 0.8)',
                            'rgba(0, 204, 97, 0.6)',
                            'rgba(0, 204, 97, 0.4)',
                            'rgba(0, 204, 97, 0.2)',
                            'rgba(0, 204, 97, 0.1)'
                        ],
                        borderColor: [
                            'rgba(0, 204, 97, 1)',
                            'rgba(0, 204, 97, 0.8)',
                            'rgba(0, 204, 97, 0.6)',
                            'rgba(0, 204, 97, 0.4)',
                            'rgba(0, 204, 97, 0.2)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${value} reviews (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        },

        initSentimentChart() {
            const ctx = document.getElementById('sentimentChart').getContext('2d');
            const data = this.fakeData.generateChartData(this.state.filters.timeRange);
            
            this.charts.sentiment = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Positive', 'Neutral', 'Negative'],
                    datasets: [{
                        data: data.sentiment,
                        backgroundColor: [
                            'rgba(0, 204, 97, 0.8)',
                            'rgba(156, 163, 175, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            'rgba(0, 204, 97, 1)',
                            'rgba(156, 163, 175, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        },

        initTrendChart() {
            const ctx = document.getElementById('trendChart').getContext('2d');
            const data = this.fakeData.generateChartData(this.state.filters.timeRange);
            
            this.charts.trend = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array.from({ length: data.trend.length }, (_, i) => 
                        this.state.filters.timeRange === 'week' ? `Day ${i + 1}` :
                        this.state.filters.timeRange === 'month' ? `Day ${i + 1}` :
                        `Month ${i + 1}`
                    ),
                    datasets: [{
                        label: 'Reviews',
                        data: data.trend,
                        borderColor: 'rgba(0, 204, 97, 1)',
                        backgroundColor: 'rgba(0, 204, 97, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.raw} reviews`;
                                }
                            }
                        }
                    }
                }
            });
        },

        initEventListeners() {
            // Filter button toggle
            const filterBtn = document.getElementById('filter-btn');
            const filterDropdown = document.getElementById('filter-dropdown');
            
            filterBtn?.addEventListener('click', () => {
                filterDropdown.classList.toggle('hidden');
            });

            // Close filter dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!filterBtn?.contains(e.target) && !filterDropdown?.contains(e.target)) {
                    filterDropdown?.classList.add('hidden');
                }
            });

            // Reset filters
            document.getElementById('reset-filters')?.addEventListener('click', () => {
                this.state.filters = {
                    rating: '',
                    status: '',
                    dateFrom: '',
                    dateTo: '',
                    category: '',
                    responseStatus: '',
                    sentiment: '',
                    keywords: '',
                    verified: '',
                    sort: 'newest',
                    timeRange: 'month'
                };
                this.loadReviews();
                filterDropdown?.classList.add('hidden');
            });

            // Apply filters
            document.getElementById('apply-filters')?.addEventListener('click', () => {
                this.state.filters.dateFrom = document.getElementById('date-from')?.value || '';
                this.state.filters.dateTo = document.getElementById('date-to')?.value || '';
                this.state.filters.category = document.getElementById('category-filter')?.value || '';
                this.state.filters.responseStatus = document.getElementById('response-filter')?.value || '';
                this.loadReviews();
                filterDropdown?.classList.add('hidden');
            });

            // Refresh button
            document.getElementById('refresh-btn')?.addEventListener('click', () => {
                this.loadReviews();
            });

            // Sort filter
            document.getElementById('sort-filter')?.addEventListener('change', (e) => {
                this.state.filters.sort = e.target.value;
                this.loadReviews();
            });

            // Select All
            const selectAll = document.getElementById('select-all');
            selectAll?.addEventListener('change', (e) => {
                const checkboxes = document.querySelectorAll('.review-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = e.target.checked;
                    const reviewId = checkbox.dataset.reviewId;
                    if (e.target.checked) {
                        this.state.selectedItems.add(reviewId);
                    } else {
                        this.state.selectedItems.delete(reviewId);
                    }
                });
            });

            // Individual Checkboxes
            document.querySelectorAll('.review-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', (e) => {
                    const reviewId = e.target.dataset.reviewId;
                    if (e.target.checked) {
                        this.state.selectedItems.add(reviewId);
                    } else {
                        this.state.selectedItems.delete(reviewId);
                    }
                });
            });

            // Pagination
            document.getElementById('prev-page')?.addEventListener('click', () => {
                if (this.state.currentPage > 1) {
                    this.state.currentPage--;
                    this.loadReviews();
                }
            });

            document.getElementById('next-page')?.addEventListener('click', () => {
                if (this.state.currentPage < Math.ceil(this.state.totalItems / this.state.itemsPerPage)) {
                    this.state.currentPage++;
                    this.loadReviews();
                }
            });

            // Reply Modal
            const replyButtons = document.querySelectorAll('[data-action="reply"]');
            const replyModal = document.getElementById('reply-modal');
            const closeReplyModal = document.getElementById('close-reply-modal');
            const cancelReply = document.getElementById('cancel-reply');

            replyButtons.forEach(button => {
                button.addEventListener('click', () => {
                    replyModal.classList.remove('hidden');
                });
            });

            closeReplyModal?.addEventListener('click', () => {
                replyModal.classList.add('hidden');
            });

            cancelReply?.addEventListener('click', () => {
                replyModal.classList.add('hidden');
            });

            // Close modal when clicking outside
            replyModal?.addEventListener('click', (e) => {
                if (e.target === replyModal) {
                    replyModal.classList.add('hidden');
                }
            });

            // Time range buttons for charts
            ['week', 'month', 'year'].forEach(range => {
                document.getElementById(`chart-${range}`)?.addEventListener('click', () => {
                    this.updateCharts(range);
                });
            });

            // Trend period buttons
            ['daily', 'weekly', 'monthly'].forEach(period => {
                document.getElementById(`trend-${period}`)?.addEventListener('click', () => {
                    this.updateTrendChart(period);
                });
            });

            // Bulk actions
            const bulkActionsBtn = document.getElementById('bulk-actions-btn');
            const bulkActionsDropdown = document.getElementById('bulk-actions-dropdown');

            bulkActionsBtn?.addEventListener('click', () => {
                bulkActionsDropdown.classList.toggle('hidden');
            });

            // Close bulk actions dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!bulkActionsBtn?.contains(e.target) && !bulkActionsDropdown?.contains(e.target)) {
                    bulkActionsDropdown?.classList.add('hidden');
                }
            });

            // Bulk action buttons
            document.getElementById('bulk-approve')?.addEventListener('click', () => {
                this.bulkAction('approve');
            });

            document.getElementById('bulk-spam')?.addEventListener('click', () => {
                this.bulkAction('spam');
            });

            document.getElementById('bulk-delete')?.addEventListener('click', () => {
                this.bulkAction('delete');
            });

            // Advanced filter panel
            const advancedFilterBtn = document.getElementById('advanced-filter-btn');
            const advancedFilterPanel = document.getElementById('advanced-filter-panel');
            const closeAdvancedFilter = document.getElementById('close-advanced-filter');

            advancedFilterBtn?.addEventListener('click', () => {
                advancedFilterPanel.classList.toggle('hidden');
            });

            closeAdvancedFilter?.addEventListener('click', () => {
                advancedFilterPanel.classList.add('hidden');
            });

            // Refresh insights
            document.getElementById('refresh-insights')?.addEventListener('click', () => {
                this.loadInsights();
            });

            // Sentiment details
            document.getElementById('sentiment-details')?.addEventListener('click', () => {
                this.showSentimentDetails();
            });

            // Trend comparison
            document.getElementById('trend-compare')?.addEventListener('click', () => {
                this.showTrendComparison();
            });
        },

        updateCharts(timeRange) {
            // Update chart data based on time range
            this.state.filters.timeRange = timeRange;
            // Simulate API call to get new data
            setTimeout(() => {
                // Update rating chart
                this.charts.rating.data.datasets[0].data = [650, 320, 150, 80, 48];
                this.charts.rating.update();

                // Update sentiment chart
                this.charts.sentiment.data.datasets[0].data = [75, 15, 10];
                this.charts.sentiment.update();
            }, 500);
        },

        updateTrendChart(period) {
            // Update trend chart data based on period
            // Simulate API call to get new data
            setTimeout(() => {
                this.charts.trend.data.datasets[0].data = [150, 180, 220, 190, 250, 280];
                this.charts.trend.update();
            }, 500);
        },

        bulkAction(action) {
            if (this.state.selectedItems.size === 0) {
                this.showNotification('Please select at least one review', 'warning');
                return;
            }

            const actionMap = {
                approve: 'Approved',
                spam: 'Marked as spam',
                delete: 'Deleted'
            };

            // Simulate API call
            setTimeout(() => {
                this.showNotification(`Successfully ${actionMap[action].toLowerCase()} ${this.state.selectedItems.size} reviews`, 'success');
                this.state.selectedItems.clear();
                this.loadReviews();
            }, 500);
        },

        showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `woodash-notification woodash-notification-${type}`;
            notification.innerHTML = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        },

        loadReviews() {
            this.state.isLoading = true;
            const tbody = document.getElementById('reviews-table-body');
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center py-8">
                            <div class="flex items-center justify-center">
                                <div class="woodash-loading-spinner"></div>
                                <span class="ml-3 text-gray-600">Loading reviews...</span>
                            </div>
                        </td>
                    </tr>
                `;
            }

            // Simulate API call with fake data
            setTimeout(() => {
                this.state.isLoading = false;
                this.renderTable();
                this.updatePagination();
            }, 500);
        },

        renderTable() {
            const tbody = document.getElementById('reviews-table-body');
            if (!tbody) return;

            // Generate fake reviews
            const reviews = Array.from({ length: this.state.itemsPerPage }, () => this.fakeData.generateReview());

            let html = '';
            reviews.forEach((review, index) => {
                const isSelected = this.state.selectedItems.has(review.id);
                const statusBadgeClass = review.status === 'approved' ? 'woodash-badge-success' : 
                                       (review.status === 'pending' ? 'woodash-badge-warning' : 'woodash-badge-danger');
                
                const sentimentClass = review.sentiment === 'positive' ? 'text-green-600' :
                                     (review.sentiment === 'neutral' ? 'text-yellow-600' : 'text-red-600');
                
                html += `
                    <tr class="woodash-fade-in hover:bg-gray-50 transition-colors duration-200" style="animation-delay: ${index * 0.05}s">
                        <td>
                            <input type="checkbox" class="rounded border-gray-300 text-[#00CC61] focus:ring-[#00CC61] review-checkbox" data-review-id="${review.id}" ${isSelected ? 'checked' : ''}>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-medium">${review.product}</span>
                                <span class="text-xs text-gray-500">${review.category}</span>
                            </div>
                        </td>
                        <td>
                            <div class="max-w-md">
                                <p class="text-sm text-gray-900">${review.review}</p>
                                ${review.response ? `
                                    <div class="mt-2 text-sm text-gray-600 bg-gray-50 p-2 rounded">
                                        <span class="font-medium">Response:</span> ${review.response}
                                    </div>
                                ` : ''}
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                ${Array(review.rating).fill('<i class="fa-solid fa-star text-yellow-400"></i>').join('')}
                                ${Array(5 - review.rating).fill('<i class="fa-regular fa-star text-yellow-400"></i>').join('')}
                            </div>
                        </td>
                        <td>
                            <span class="${sentimentClass} capitalize">${review.sentiment}</span>
                        </td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                ${review.keywords.map(keyword => `
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">${keyword}</span>
                                `).join('')}
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span>${review.author}</span>
                                ${review.verified ? `
                                    <span class="text-xs text-green-600">
                                        <i class="fa-solid fa-check-circle"></i> Verified Purchase
                                    </span>
                                ` : ''}
                            </div>
                        </td>
                        <td>${review.date}</td>
                        <td>
                            <span class="woodash-badge ${statusBadgeClass}">${review.status.charAt(0).toUpperCase() + review.status.slice(1)}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button class="text-gray-400 hover:text-[#00CC61] transition-colors duration-200" data-action="reply" title="Reply">
                                    <i class="fa-solid fa-reply"></i>
                                </button>
                                <button class="text-gray-400 hover:text-yellow-600 transition-colors duration-200" data-action="edit" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="text-gray-400 hover:text-red-600 transition-colors duration-200" data-action="delete" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        },

        updatePagination() {
            const start = (this.state.currentPage - 1) * this.state.itemsPerPage + 1;
            const end = Math.min(start + this.state.itemsPerPage - 1, this.state.totalItems);
            
            document.getElementById('pagination-start').textContent = start;
            document.getElementById('pagination-end').textContent = end;
            document.getElementById('pagination-total').textContent = this.state.totalItems;

            const totalPages = Math.ceil(this.state.totalItems / this.state.itemsPerPage);
            const paginationNumbersContainer = document.getElementById('pagination-numbers');
            if (paginationNumbersContainer) {
                let html = '';
                const maxVisiblePages = 5;
                let startPage = Math.max(1, this.state.currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                if (startPage > 1) {
                    html += `<button class="woodash-btn woodash-btn-secondary woodash-hover-card">1</button>`;
                    if (startPage > 2) {
                        html += '<span class="text-gray-500 px-2">...</span>';
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    html += `
                        <button class="woodash-btn ${i === this.state.currentPage ? 'woodash-btn-primary' : 'woodash-btn-secondary'} woodash-hover-card">
                            ${i}
                        </button>
                    `;
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        html += '<span class="text-gray-500 px-2">...</span>';
                    }
                    html += `<button class="woodash-btn woodash-btn-secondary woodash-hover-card">${totalPages}</button>`;
                }

                paginationNumbersContainer.innerHTML = html;
            }
        },

        showSentimentDetails() {
            // Implement sentiment details modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Sentiment Analysis Details</h3>
                        <button class="text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.parentElement.remove()">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Sentiment Distribution</h4>
                            <canvas id="sentimentDetailsChart" height="200"></canvas>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Trend Analysis</h4>
                            <canvas id="sentimentTrendChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            this.initSentimentDetailsCharts();
        },

        showTrendComparison() {
            // Implement trend comparison modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Review Trends Comparison</h3>
                        <button class="text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.parentElement.remove()">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Current Period</h4>
                            <canvas id="currentTrendChart" height="200"></canvas>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Previous Period</h4>
                            <canvas id="previousTrendChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            this.initTrendComparisonCharts();
        },

        initSentimentDetailsCharts() {
            // Implement sentiment details chart initialization
        },

        initTrendComparisonCharts() {
            // Implement trend comparison chart initialization
        }
    };

    // Initialize the reviews manager
    ReviewsManager.init();
});
</script>

</body>
</html>
