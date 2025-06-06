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
if (isset($_POST['woodashh_products_nonce'])) {
    if (!wp_verify_nonce($_POST['woodashh_products_nonce'], 'woodashh_products_action')) {
        wp_die(__('Security check failed. Please try again.', 'woodashh'));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Dashboard</title>
    <!-- Google Fonts & Font Awesome for icons -->
    <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js: Use a valid CDN and only load once -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Lottie player: Only load once -->
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
            margin-left: 16rem; /* Equivalent to w-64 */
            transition: margin-left 0.3s ease;
            flex-grow: 1;
            overflow-y: auto; /* Add this line */
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
            overflow: hidden; /* Ensure child elements don't overflow rounded corners */
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
            font-size: 0.875rem; /* sm */
            font-weight: 500; /* medium */
            color: #6B7280; /* gray-500 */
            margin-bottom: 0.25rem;
        }

        .woodash-metric-value {
            font-size: 1.875rem; /* 3xl */
            font-weight: 700; /* bold */
            color: #1F2937; /* gray-900 */
        }

        .woodash-metric-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem; /* xl */
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
            padding: 0.625rem 1.25rem; /* px-5 py-2.5 */
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
            font-size: 0.75rem; /* text-xs */
            font-weight: 600; /* font-semibold */
            text-transform: uppercase;
            color: #6B7280; /* text-gray-500 */
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
            color: #6B7280; /* gray-500 */
            font-size: 1rem;
        }

        .woodash-search-input {
            width: 100%;
            padding: 0.625rem 2.5rem 0.625rem 2.5rem; /* py-2.5 px-10 */
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            outline: none;
            font-size: 1rem;
            color: #1F2937;
        }

         .woodash-search-input::placeholder {
            color: #9CA3AF; /* gray-400 */
        }

        .woodash-search-button {
             padding: 0.625rem 1.25rem; /* px-5 py-2.5 */
             background: var(--primary-color);
             color: white;
             border: none;
             cursor: pointer;
             transition: background-color 0.2s ease;
        }

        .woodash-search-button:hover {
             background: var(--primary-dark);
        }

        .woodash-clear-product-search {
            position: absolute;
            right: 4rem; /* Adjust based on search button width */
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF; /* gray-400 */
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .woodash-clear-product-search:hover {
            color: #EF4444; /* red-600 */
        }

        .woodash-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 20;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            margin-top: 0.5rem;
            box-shadow: var(--shadow-lg);
            max-height: 200px;
            overflow-y: auto;
            padding: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
        }

        .woodash-search-results.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .woodash-search-result-item {
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
            font-size: 0.875rem;
            color: #1F2937;
        }

        .woodash-search-result-item:hover {
            background-color: rgba(0, 204, 97, 0.1);
        }

        /* Enhanced Loading States */
        .woodash-loading {
            position: relative;
            overflow: hidden;
            background-color: #E5E7EB; /* gray-200 */
        }

        .woodash-loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.5),
                transparent
            );
            animation: loading-shimmer 1.5s infinite;
        }

        @keyframes loading-shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .woodash-loading-spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: var(--primary-color);
            border-radius: 50%;
            width: 1.5rem;
            height: 1.5rem;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .woodash-bounce {
            animation: bounce 1s ease-in-out infinite;
        }

        /* Enhanced Background Effects */
         .woodash-bg-animation {
             position: absolute;
             top: 0;
             left: 0;
             width: 100%;
             height: 100%;
             overflow: hidden;
             z-index: -1;
         }

         .woodash-orb {
             position: absolute;
             width: 150px;
             height: 150px;
             background: rgba(0, 204, 97, 0.15);
             border-radius: 50%;
             filter: blur(50px);
             opacity: 0.7;
             animation: floatOrb 15s ease-in-out infinite;
             pointer-events: none;
         }

         .woodash-orb-1 {
             top: 10%;
             left: 5%;
             width: 200px;
             height: 200px;
             animation-delay: 1s;
         }

         .woodash-orb-2 {
             top: 50%;
             left: 60%;
             background: rgba(0, 179, 87, 0.15);
             animation-delay: 4s;
         }

         .woodash-orb-3 {
             top: 80%;
             left: 20%;
             width: 180px;
             height: 180px;
             animation-delay: 7s;
         }

         @keyframes floatOrb {
             0%, 100% { transform: translate(var(--mouse-x, 0%) - 50%, var(--mouse-y, 0%) - 50%) translateY(0); }
             50% { transform: translate(var(--mouse-x, 0%) - 50%, var(--mouse-y, 0%) - 50%) translateY(-20px); }
         }

         .woodash-line {
             position: absolute;
             background: rgba(0, 204, 97, 0.05);
             pointer-events: none;
         }

         .woodash-line-1 {
             top: 20%;
             left: 0;
             width: 100%;
             height: 1px;
             animation: drawLine 5s ease-in-out infinite;
         }

         .woodash-line-2 {
             top: 70%;
             left: 0;
             width: 100%;
             height: 1px;
             animation: drawLine 6s ease-in-out infinite;
             animation-delay: 1s;
         }

         .woodash-line-3 {
             top: 0;
             left: 30%;
             width: 1px;
             height: 100%;
             animation: drawLineVertical 7s ease-in-out infinite;
             animation-delay: 2s;
         }

          .woodash-line-4 {
             top: 0;
             left: 80%;
             width: 1px;
             height: 100%;
             animation: drawLineVertical 8s ease-in-out infinite;
             animation-delay: 3s;
         }

         @keyframes drawLine {
             0% { transform: scaleX(0); transform-origin: left; }
             50% { transform: scaleX(1); transform-origin: left; }
             100% { transform: scaleX(0); transform-origin: right; }
         }

          @keyframes drawLineVertical {
             0% { transform: scaleY(0); transform-origin: top; }
             50% { transform: scaleY(1); transform-origin: top; }
             100% { transform: scaleY(0); transform-origin: bottom; }
         }

         .woodash-shimmer {
             position: absolute;
             top: 0;
             left: 0;
             width: 100%;
             height: 100%;
             background: linear-gradient(45deg, transparent 0%, rgba(0, 204, 97, 0.05) 50%, transparent 100%);
             animation: backgroundShimmer 10s ease-in-out infinite;
             pointer-events: none;
         }

         @keyframes backgroundShimmer {
             0% { transform: translateX(-100%) translateY(-100%); }
             100% { transform: translateX(100%) translateY(100%); }
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

        /* Enhanced Dropdowns */
        .woodash-dropdown {
            position: absolute;
            right: 0;
            mt-2: 0.5rem; /* Translate mt-2 */
            width: 12rem; /* w-48 */
            z-index: 10;
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            box-shadow: var(--shadow-lg);
            animation: fadeIn 0.2s ease forwards;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .woodash-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
            font-size: 0.875rem;
            color: #1F2937;
        }

        .woodash-dropdown-item:hover {
            background-color: rgba(0, 204, 97, 0.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Enhanced Progress Bars */
        .woodash-progress {
            height: 0.5rem;
            background-color: #E5E7EB; /* gray-200 */
            border-radius: 9999px; /* rounded-full */
            overflow: hidden;
        }

        .woodash-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            transition: width 0.3s ease;
        }

        .woodash-progress-bar-red {
             background: linear-gradient(90deg, #EF4444, #DC2626);
        }

        /* Enhanced Badges */
        .woodash-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px; /* rounded-full */
            font-size: 0.75rem; /* text-xs */
            font-weight: 500; /* font-medium */
        }

        .woodash-badge-success {
            background-color: #D1FAE5; /* green-100 */
            color: #065F46; /* green-900 */
        }

        .woodash-badge-danger {
            background-color: #FEE2E2; /* red-100 */
            color: #991B1B; /* red-900 */
        }

        .woodash-badge-warning {
            background-color: #FEF3C7; /* yellow-100 */
            color: #92400E; /* yellow-900 */
        }

        .woodash-badge-blue {
            background-color: #DBEAFE; /* blue-100 */
            color: #1E40AF; /* blue-900 */
        }

        /* Enhanced Charts */
        .woodash-chart-container {
            position: relative;
            overflow: hidden;
             background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            transition: var(--transition-base);
            box-shadow: var(--shadow-sm);
        }

        .woodash-chart-container:hover {
             transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Enhanced Footer */
        footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            text-align: center;
            font-size: 0.875rem; /* sm */
            color: #6B7280; /* gray-500 */
        }

        /* Hover Card Effect */
        .woodash-hover-card {
             transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .woodash-hover-card:hover {
             transform: translateY(-2px);
             box-shadow: var(--shadow-lg);
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .loading-spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: var(--primary-color);
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            margin-top: 16px;
            font-size: 16px;
            color: #333;
        }

        .chart-card {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .chart-loading {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        .chart-loading-spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: var(--primary-color);
            border-radius: 50%;
            width: 1.5rem;
            height: 1.5rem;
            animation: spin 1s linear infinite;
        }

        .chart-loading-text {
            margin-left: 0.5rem;
            font-size: 0.875rem;
            color: #6B7280;
        }

        .chart-container {
            height: 200px;
        }

        .page-transition {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .page-transition.active {
            opacity: 1;
            pointer-events: auto;
        }

        .page-transition-spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: var(--primary-color);
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        .skeleton-text {
            height: 1rem;
            background-color: #f0f0f0;
            margin-bottom: 0.5rem;
        }

        .skeleton-chart {
            height: 100px;
            background-color: #e0e0e0;
            border-radius: 4px;
        }

    </style>
</head>
<body>

<button id="woodash-menu-toggle" class="woodash-menu-toggle woodash-btn woodash-btn-secondary woodash-hover-card">
    <i class="fa-solid fa-bars"></i>
</button>

<div id="woodash-dashboard" class="woodash-fullscreen flex">
    <!-- Background Orbs -->
    <div class="woodash-bg-animation">
        <div class="woodash-orb woodash-orb-1"></div>
        <div class="woodash-orb woodash-orb-2"></div>
        <div class="woodash-orb woodash-orb-3"></div>

        <!-- Background Lines -->
        <div class="woodash-line woodash-line-1"></div>
        <div class="woodash-line woodash-line-2"></div>
        <div class="woodash-line woodash-line-3"></div>
        <div class="woodash-line woodash-line-4"></div>

        <!-- Background Shimmer -->
        <div class="woodash-shimmer"></div>
    </div>

    <!-- Sidebar -->
    <aside class="woodash-sidebar w-64 bg-white/90 border-r border-gray-100 p-6 woodash-glass-effect">
        <div class="flex items-center gap-3 mb-8 woodash-fade-in">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center woodash-glow">
                <i class="fa-solid fa-boxes-stacked text-white text-xl"></i>
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
               class="woodash-nav-link active woodash-hover-card woodash-slide-up group"
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
               style="animation-delay: 0.3s">
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
               style="animation-delay: 0.4s">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fa-solid fa-boxes-stacked text-white"></i>
                    </div>
                    <span>Stock</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-[#00CC61] transition-colors duration-200"></i>
            </a>

             <a href="<?php echo esc_url(admin_url('admin.php?page=woodashh-reviews')); ?>"
               class="woodash-nav-link woodash-hover-card woodash-slide-up group"
               style="animation-delay: 0.5s">
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
                    <h1 class="text-2xl font-bold woodash-gradient-text">Products</h1>
                    <p class="text-gray-500">Welcome back, John! Here's your product management dashboard.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="woodash-btn woodash-btn-primary woodash-hover-card" id="add-product-btn">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add New Product</span>
                    </button>
                     <div class="relative">
                        <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="quick-actions-btn">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                            <span>Quick Actions</span>
                        </button>
                        <div class="woodash-dropdown hidden absolute right-0 mt-2 w-48 z-10" id="quick-actions-dropdown">
                            <div class="woodash-dropdown-item woodash-fade-in" style="animation-delay: 0.1s">
                                <i class="fa-solid fa-file-import text-blue-500"></i>
                                <span>Import Products</span>
                            </div>
                            <div class="woodash-dropdown-item woodash-fade-in" style="animation-delay: 0.2s">
                                <i class="fa-solid fa-file-export text-green-500"></i>
                                <span>Export Products</span>
                            </div>
                            <div class="woodash-dropdown-item woodash-fade-in" style="animation-delay: 0.3s">
                                <i class="fa-solid fa-tags text-purple-500"></i>
                                <span>Manage Categories</span>
                            </div>
                            <div class="woodash-dropdown-item woodash-fade-in" style="animation-delay: 0.4s">
                                <i class="fa-solid fa-boxes-stacked text-orange-500"></i>
                                <span>Bulk Edit</span>
                            </div>
                        </div>
                    </div>
                    <div class="woodash-search-container">
                        <i class="fa-solid fa-search woodash-search-icon"></i>
                        <input type="text" 
                               placeholder="Search products..." 
                               class="woodash-search-input" style="border:none;"
                               id="product-search">
                        <button id="woodash-clear-product-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-600 focus:outline-none hidden text-base transition-colors duration-200">
                            <i class="fa-solid fa-times"></i>
                        </button>
                        
                        <div class="woodash-search-results" id="product-search-results">
                            <!-- Results will be populated here -->
                        </div>
                         <button class="woodash-search-button">
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                     <div class="relative">
                        <button class="woodash-btn woodash-btn-secondary woodash-hover-card woodash-glow" id="notifications-btn">
                            <i class="fa-solid fa-bell"></i>
                            <span class="woodash-badge woodash-badge-danger absolute -top-1 -right-1 woodash-bounce">3</span>
                        </button>
                        <div class="woodash-dropdown hidden" id="notifications-dropdown">
                            <div class="woodash-dropdown-item woodash-fade-in" style="animation-delay: 0.1s">
                                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                                <span>Low stock alert: Wireless Headphones</span>
                            </div>
                            <div class="woodash-dropdown-item woodash-fade-in" style="animation-delay: 0.2s">
                                <i class="fa-solid fa-circle-exclamation text-yellow-500"></i>
                                <span>New product review</span>
                            </div>
                            <div class="woodash-dropdown-item woodash-fade-in" style="animation-delay: 0.3s">
                                <i class="fa-solid fa-circle-exclamation text-green-500"></i>
                                <span>Product updated successfully</span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="woodash-metric-card woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">Total Products</h3>
                            <div class="woodash-metric-value">248</div>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-sm text-green-600 flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-up text-xs"></i>
                                    <span>12.5%</span>
                                </span>
                                <span class="text-xs text-gray-500">vs last month</span>
                            </div>
                        </div>
                        <div class="woodash-metric-icon woodash-metric-green woodash-float">
                            <i class="fa-solid fa-box"></i>
                        </div>
                    </div>
                    <div class="mt-4 relative">
                         <canvas id="mini-trend-products" height="40"></canvas>
                    </div>
                </div>

                <div class="woodash-metric-card woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">Low Stock Items</h3>
                            <div class="woodash-metric-value">12</div>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-sm text-red-600 flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-up text-xs"></i>
                                    <span>8.2%</span>
                                </span>
                                <span class="text-xs text-gray-500">vs last month</span>
                            </div>
                        </div>
                        <div class="woodash-metric-icon woodash-metric-yellow woodash-float">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="mt-4 relative">
                         <canvas id="mini-trend-lowstock" height="40"></canvas>
                    </div>
                </div>

                <div class="woodash-metric-card woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">Out of Stock</h3>
                            <div class="woodash-metric-value">5</div>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-sm text-red-600 flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-down text-xs"></i>
                                    <span>3.1%</span>
                                </span>
                                <span class="text-xs text-gray-500">vs last month</span>
                            </div>
                        </div>
                        <div class="woodash-metric-icon woodash-metric-red woodash-float">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>
                     <div class="mt-4 relative">
                         <canvas id="mini-trend-outofstock" height="40"></canvas>
                    </div>
                </div>

                <div class="woodash-metric-card woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.4s">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="woodash-metric-title">Categories</h3>
                            <div class="woodash-metric-value">8</div>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-sm text-green-600 flex items-center gap-1">
                                    <i class="fa-solid fa-arrow-up text-xs"></i>
                                    <span>15.3%</span>
                                </span>
                                <span class="text-xs text-gray-500">vs last month</span>
                            </div>
                        </div>
                        <div class="woodash-metric-icon woodash-metric-blue woodash-float">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                    </div>
                    <div class="mt-4 relative">
                        <canvas id="mini-trend-categories" height="40"></canvas>
                    </div>
                </div>
            </div>

            <!-- Products Overview Chart -->
            <div class="woodash-chart-container mb-8 woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.4s">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-lg font-bold woodash-gradient-text">Products Overview</h2>
                        <p class="text-gray-500 text-sm">Track your product performance over time</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="woodash-btn woodash-btn-primary woodash-hover-card" data-range="daily">Daily</button>
                        <button class="woodash-btn woodash-btn-secondary woodash-hover-card" data-range="weekly">Weekly</button>
                        <button class="woodash-btn woodash-btn-secondary woodash-hover-card" data-range="monthly">Monthly</button>
                    </div>
                </div>
                <div class="h-[400px]">
                    <canvas id="products-chart"></canvas>
                </div>
            </div>

            <!-- Products Table -->
            <div class="woodash-card overflow-hidden woodash-animate-in" style="animation-delay: 0.5s">
                <div class="flex justify-between items-center p-4 border-b border-gray-100">
                    <div class="flex gap-2">
                        <select class="woodash-btn woodash-btn-secondary woodash-hover-card" id="category-filter">
                            <option value="">All Categories</option>
                            <option value="electronics">Electronics</option>
                            <option value="clothing">Clothing</option>
                            <option value="books">Books</option>
                        </select>
                        <select class="woodash-btn woodash-btn-secondary woodash-hover-card" id="status-filter">
                            <option value="">All Status</option>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="private">Private</option>
                        </select>
                        <select class="woodash-btn woodash-btn-secondary woodash-hover-card" id="stock-filter">
                            <option value="">Stock Status</option>
                            <option value="instock">In Stock</option>
                            <option value="lowstock">Low Stock</option>
                            <option value="outofstock">Out of Stock</option>
                        </select>
                    </div>
                     <div class="flex gap-2">
                         <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="export-btn">
                            <i class="fa-solid fa-file-csv"></i>
                            <span>Export</span>
                        </button>
                        <div class="relative">
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="bulk-actions-btn" style="display: none;">
                                <i class="fa-solid fa-list-check"></i>
                                <span>Bulk Actions (<span id="selected-count">0</span>)</span>
                            </button>
                            <div class="woodash-dropdown hidden absolute right-0 mt-2 w-48 z-10" id="bulk-actions-dropdown">
                                <!-- Dropdown items populated by JS -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto woodash-scrollbar">
                    <table class="woodash-table w-full" id="products-table">
                        <thead>
                            <tr>
                                <th class="w-12">
                                    <input type="checkbox" class="rounded border-gray-300 text-[#00CC61] focus:ring-[#00CC61]" id="select-all">
                                </th>
                                <th class="cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="name">
                                    Product <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th class="cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="category">
                                    Category <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th class="cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="price">
                                    Price <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th class="cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="stock">
                                    Stock <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th class="cursor-pointer hover:text-[#00CC61] transition-colors duration-200" data-sort="status">
                                    Status <i class="fa-solid fa-sort ml-1"></i>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="products-table-body">
                            <!-- Products will be loaded here by JS -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between p-4 border-t border-gray-100">
                    <div class="text-sm text-gray-500">
                        Showing <span id="pagination-start">1</span> to <span id="pagination-end">10</span> of <span id="pagination-total">248</span> products
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="prev-page">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <div class="flex items-center gap-1" id="pagination-numbers">
                            <!-- Pagination numbers populated by JS -->
                        </div>
                        <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="next-page">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Additional Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <!-- Top Performing Products -->
                <div class="woodash-chart-container woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.6s">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-lg font-bold woodash-gradient-text">Top Performing Products</h2>
                            <p class="text-gray-500 text-sm">Best selling products this month</p>
                        </div>
                         <button class="woodash-btn woodash-btn-secondary woodash-hover-card woodash-top-products-options">
                            <i class="fa-solid fa-ellipsis"></i>
                        </button>
                    </div>
                    <div class="space-y-4" id="top-performing-products">
                        <!-- Top products populated by JS -->
                    </div>
                </div>

                <!-- Low Stock Alerts -->
                <div class="woodash-chart-container woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.7s">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-lg font-bold woodash-gradient-text">Low Stock Alerts</h2>
                            <p class="text-gray-500 text-sm">Products that need attention</p>
                        </div>
                         <button class="woodash-btn woodash-btn-secondary woodash-hover-card woodash-low-stock-options">
                            <i class="fa-solid fa-ellipsis"></i>
                        </button>
                    </div>
                    <div class="space-y-4" id="low-stock-alerts">
                        <!-- Low stock items populated by JS -->
                    </div>
                </div>
            </div>
        </div>
        <footer>
    <div class="max-w-7xl mx-auto text-center py-4 text-gray-600 text-sm">
        <p>&copy; <?php echo date('Y'); ?> WooDash Pro. All rights reserved.</p>
    </div>
</footer>
    </main>
</div>

<!-- Add Product Modal -->
<div id="add-product-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden woodash-fade-in">
    <div class="woodash-card w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 woodash-glass-effect relative">
        <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-4">
            <h3 class="text-xl font-bold woodash-gradient-text">Add New Product</h3>
            <button class="text-gray-400 hover:text-red-600 transition-colors" id="close-add-product-modal">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <form id="add-product-form" class="space-y-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="product-name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                    <input type="text" id="product-name" name="product-name" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                </div>
                <div>
                    <label for="product-sku" class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                    <input type="text" id="product-sku" name="product-sku" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="product-description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="product-description" name="product-description" rows="4"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2"></textarea>
            </div>

            <!-- Pricing -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="product-regular-price" class="block text-sm font-medium text-gray-700 mb-1">Regular Price *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                        <input type="number" id="product-regular-price" name="product-regular-price" required step="0.01" min="0"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2 pl-7">
                    </div>
                </div>
                <div>
                    <label for="product-sale-price" class="block text-sm font-medium text-gray-700 mb-1">Sale Price</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                        <input type="number" id="product-sale-price" name="product-sale-price" step="0.01" min="0"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2 pl-7">
                    </div>
                </div>
                <div>
                    <label for="product-tax-status" class="block text-sm font-medium text-gray-700 mb-1">Tax Status</label>
                    <select id="product-tax-status" name="product-tax-status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                        <option value="taxable">Taxable</option>
                        <option value="shipping">Shipping only</option>
                        <option value="none">None</option>
                    </select>
                </div>
            </div>

            <!-- Inventory -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="product-stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                    <input type="number" id="product-stock" name="product-stock" required min="0"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                </div>
                <div>
                    <label for="product-stock-status" class="block text-sm font-medium text-gray-700 mb-1">Stock Status</label>
                    <select id="product-stock-status" name="product-stock-status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                        <option value="instock">In Stock</option>
                        <option value="outofstock">Out of Stock</option>
                        <option value="onbackorder">On Backorder</option>
                    </select>
                </div>
                <div>
                    <label for="product-low-stock" class="block text-sm font-medium text-gray-700 mb-1">Low Stock Threshold</label>
                    <input type="number" id="product-low-stock" name="product-low-stock" min="0"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                </div>
            </div>

            <!-- Categories and Tags -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="product-category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select id="product-category" name="product-category" required multiple
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                        <option value="electronics">Electronics</option>
                        <option value="clothing">Clothing</option>
                        <option value="books">Books</option>
                        <option value="accessories">Accessories</option>
                        <option value="home">Home & Garden</option>
                    </select>
                </div>
                <div>
                    <label for="product-tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                    <input type="text" id="product-tags" name="product-tags" placeholder="Separate tags with commas"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                </div>
            </div>

            <!-- Product Images -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Images</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-3xl mb-2"></i>
                        <div class="flex text-sm text-gray-600">
                            <label for="product-images" class="relative cursor-pointer bg-white rounded-md font-medium text-[#00CC61] hover:text-[#00b357] focus-within:outline-none">
                                <span>Upload images</span>
                                <input id="product-images" name="product-images" type="file" class="sr-only" multiple accept="image/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                    </div>
                </div>
                <div id="image-preview" class="mt-4 grid grid-cols-4 gap-4"></div>
            </div>

            <!-- Product Status -->
            <div>
                <label for="product-status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="product-status" name="product-status"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#00CC61] focus:ring-[#00CC61] sm:text-sm p-2">
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="private">Private</option>
                </select>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" class="woodash-btn woodash-btn-secondary" id="cancel-add-product-btn">Cancel</button>
                <button type="submit" class="woodash-btn woodash-btn-primary" id="save-product-btn">
                    <i class="fa-solid fa-save mr-2"></i>
                    Save Product
                </button>
            </div>
        </form>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date('Y'); ?> WooDash Pro. All rights reserved.</p>
</footer>


<button id="woodash-scroll-to-top" class="fixed bottom-6 right-6 woodash-btn woodash-btn-primary rounded-full w-12 h-12 flex items-center justify-center woodash-hover-card woodash-glow" style="display: none;">
    <i class="fa-solid fa-arrow-up"></i>
</button>

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

<div class="page-transition">
    <div class="page-transition-spinner"></div>
</div>

<script>
    // Helper function for debouncing
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Optimize chart rendering (using the helper function)
    function createOptimizedChart(ctx, config) {
        const chart = new Chart(ctx, {
            ...config,
            options: {
                ...config.options,
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                },
                responsiveAnimationDuration: 0,
                maintainAspectRatio: false,
                plugins: {
                    ...config.options?.plugins,
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Optimize chart updates
        let updateTimeout;
        chart.update = function(animation = true) {
            if (updateTimeout) {
                clearTimeout(updateTimeout);
            }
            updateTimeout = setTimeout(() => {
                Chart.prototype.update.call(this, animation);
            }, 16);
        };

        return chart;
    }

    // Optimize notification system
    const notificationQueue = [];
    let isProcessingNotifications = false;

    function showNotification(message, type = 'success') {
        notificationQueue.push({ message, type });
        if (!isProcessingNotifications) {
            processNotificationQueue();
        }
    }

    function processNotificationQueue() {
        if (notificationQueue.length === 0) {
            isProcessingNotifications = false;
            return;
        }

        isProcessingNotifications = true;
        const { message, type } = notificationQueue.shift();
        
        const notification = document.createElement('div');
        notification.className = `woodash-notification woodash-notification-${type} woodash-fade-in`;
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fa-solid ${type === 'success' ? 'fa-circle-check text-green-500' : 'fa-circle-exclamation text-red-500'}"></i>
                <p>${message}</p>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Position the notification
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        
        // Animate in
        requestAnimationFrame(() => {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        });

        // Remove after delay
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize product management functionality
        const ProductManager = {
            state: {
                currentPage: 1,
                itemsPerPage: 10,
                totalItems: 248,
                sortColumn: 'name',
                sortDirection: 'asc',
                selectedItems: new Set(),
                filters: {
                    category: '',
                    status: '',
                    stock: ''
                },
                searchQuery: '',
                isLoading: false
            },

            init() {
                this.initEventListeners();
                this.initCharts();
                this.initSearch();
                this.initFilters();
                this.initBulkActions();
                this.loadProducts();
                this.initAnimations();
            },

            initEventListeners() {
                // Quick Actions
                const quickActionsBtn = document.getElementById('quick-actions-btn');
                const quickActionsDropdown = document.getElementById('quick-actions-dropdown');
                
                quickActionsBtn?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleDropdown(quickActionsDropdown);
                });

                // Add Product Button
                const addProductBtn = document.getElementById('add-product-btn');
                const addProductModal = document.getElementById('add-product-modal');
                const closeAddProductModalBtn = document.getElementById('close-add-product-modal');
                const cancelAddProductBtn = document.getElementById('cancel-add-product-btn');
                const addProductForm = document.getElementById('add-product-form');

                addProductBtn?.addEventListener('click', () => {
                    console.log('Add Product button clicked');
                    if (addProductModal) {
                        console.log('Modal found, removing hidden class');
                        addProductModal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden'; // Prevent background scrolling
                    } else {
                        console.log('Modal not found');
                    }
                });

                closeAddProductModalBtn?.addEventListener('click', () => {
                    if (addProductModal) {
                        addProductModal.classList.add('hidden');
                        addProductForm?.reset();
                    }
                });

                cancelAddProductBtn?.addEventListener('click', () => {
                    if (addProductModal) {
                        addProductModal.classList.add('hidden');
                        addProductForm?.reset();
                    }
                });

                // Close modal when clicking outside
                addProductModal?.addEventListener('click', (e) => {
                    if (e.target === addProductModal) {
                        addProductModal.classList.add('hidden');
                        addProductForm?.reset();
                    }
                });

                // Handle form submission
                addProductForm?.addEventListener('submit', (e) => {
                    e.preventDefault();
                    // Implement form submission logic here
                    showNotification('Product saved successfully', 'success');
                    addProductModal?.classList.add('hidden');
                    addProductForm?.reset();
                    // Refresh the products list
                    this.loadProducts();
                });

                // Bulk Actions
                const bulkActionsBtn = document.getElementById('bulk-actions-btn');
                const bulkActionsDropdown = document.getElementById('bulk-actions-dropdown');
                
                bulkActionsBtn?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleDropdown(bulkActionsDropdown);
                });

                // Select All
                const selectAll = document.getElementById('select-all');
                selectAll?.addEventListener('change', (e) => {
                    const checkboxes = document.querySelectorAll('.product-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = e.target.checked;
                        const productId = checkbox.dataset.productId;
                        if (e.target.checked) {
                            this.state.selectedItems.add(productId);
                        } else {
                            this.state.selectedItems.delete(productId);
                        }
                    });
                    this.updateBulkActionsVisibility();
                });

                // Individual Checkboxes
                document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', (e) => {
                        const productId = e.target.dataset.productId;
                        if (e.target.checked) {
                            this.state.selectedItems.add(productId);
                        } else {
                            this.state.selectedItems.delete(productId);
                        }
                        this.updateBulkActionsVisibility();
                    });
                });

                // Pagination
                document.getElementById('prev-page')?.addEventListener('click', () => {
                    if (this.state.currentPage > 1) {
                        this.state.currentPage--;
                        this.loadProducts();
                    }
                });

                document.getElementById('next-page')?.addEventListener('click', () => {
                    if (this.state.currentPage < Math.ceil(this.state.totalItems / this.state.itemsPerPage)) {
                        this.state.currentPage++;
                        this.loadProducts();
                    }
                });

                 // Pagination number clicks using delegation
                const paginationNumbersContainer = document.getElementById('pagination-numbers');
                paginationNumbersContainer?.addEventListener('click', (e) => {
                    if (e.target.tagName === 'BUTTON') {
                        const page = parseInt(e.target.textContent);
                        if (!isNaN(page) && page > 0 && page <= Math.ceil(this.state.totalItems / this.state.itemsPerPage) && page !== this.state.currentPage) {
                            this.state.currentPage = page;
                            this.loadProducts();
                        }
                    }
                });

                // Close dropdowns when clicking outside
                document.addEventListener('click', (e) => {
                    this.closeAllDropdowns();
                });

                // Export Button
                document.getElementById('export-btn')?.addEventListener('click', () => {
                    this.exportProducts();
                });

                 // Ellipsis button listeners for additional cards
                document.querySelectorAll('.woodash-top-products-options').forEach(button => {
                    button.addEventListener('click', () => {
                        showNotification('Top Products Options clicked');
                        // Implement options logic
                    });
                });

                document.querySelectorAll('.woodash-low-stock-options').forEach(button => {
                    button.addEventListener('click', () => {
                        showNotification('Low Stock Options clicked');
                        // Implement options logic
                    });
                });

            },

            initCharts() {
                // Products Overview Chart
                const productsCtx = document.getElementById('products-chart')?.getContext('2d');
                if (productsCtx) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                this.createProductsChart(productsCtx);
                                observer.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.1 });

                    observer.observe(productsCtx.canvas);
                }

                // Mini Charts
                const miniCharts = ['products', 'lowstock', 'outofstock', 'categories'];
                miniCharts.forEach(chartId => {
                    const ctx = document.getElementById(`mini-trend-${chartId}`);
                    if (ctx) {
                        const observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    this.createMiniChart(ctx.getContext('2d'), chartId);
                                    observer.unobserve(entry.target);
                                }
                            });
                        }, { threshold: 0.1 });

                        observer.observe(ctx);
                    }
                });
            },

            createProductsChart(ctx) {
                createOptimizedChart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Total Products',
                            data: [180, 200, 220, 240, 230, 248],
                            borderColor: '#00CC61',
                            tension: 0.4,
                            fill: true,
                            backgroundColor: 'rgba(0, 204, 97, 0.1)'
                        }]
                    },
                    options: {
                        plugins: {
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: { family: 'Inter', size: 14 },
                                bodyFont: { family: 'Inter', size: 13 }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                                ticks: { font: { family: 'Inter' } }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Inter' } }
                            }
                        }
                    }
                });
            },

            createMiniChart(ctx, type) {
                const colors = {
                    products: '#00CC61',
                    lowstock: '#F59E0B',
                    outofstock: '#EF4444',
                    categories: '#3B82F6'
                };

                createOptimizedChart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['', '', '', '', '', ''],
                        datasets: [{
                            data: [4, 3, 5, 2, 4, 3],
                            borderColor: colors[type],
                            tension: 0.4,
                            pointRadius: 0
                        }]
                    },
                    options: {
                        scales: {
                            x: { display: false },
                            y: { display: false }
                        }
                    }
                });
            },

            initSearch() {
                const searchInput = document.getElementById('product-search');
                const searchResults = document.getElementById('product-search-results');
                const clearButton = document.getElementById('woodash-clear-product-search');

                if (searchInput && searchResults && clearButton) {
                    const handleSearch = debounce((query) => {
                        this.state.searchQuery = query;
                        if (query.length < 2) {
                            this.hideSearchResults();
                             clearButton.classList.add('hidden');
                            return;
                        }
                        clearButton.classList.remove('hidden');
                        this.showSearchResults();
                        this.performSearch(query);
                    }, 300);

                    searchInput.addEventListener('input', (e) => {
                        handleSearch(e.target.value.trim());
                    });

                    clearButton.addEventListener('click', () => {
                        searchInput.value = '';
                        this.state.searchQuery = '';
                        this.hideSearchResults();
                        clearButton.classList.add('hidden');
                        searchInput.focus();
                    });

                    // Close search results on click outside or focus out
                    document.addEventListener('click', (e) => {
                        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                            this.hideSearchResults();
                        }
                    });

                    searchInput.addEventListener('focus', () => {
                         if (searchInput.value.length >= 2) {
                             this.showSearchResults();
                         }
                    });

                     // Populate with sample data for demonstration
                    this.populateSearchResults(['Wireless Headphones', 'Smart Watch', 'Bluetooth Speaker', 'Gaming Mouse', 'USB-C Hub']);
                }
            },

            populateSearchResults(results) {
                 const searchResults = document.getElementById('product-search-results');
                 if (searchResults) {
                     let html = '';
                     if (results.length > 0) {
                        results.forEach(item => {
                            html += `<div class="woodash-search-result-item">${item}</div>`;
                        });
                     } else {
                          html = '<div class="woodash-search-result-item text-gray-500">No results found</div>';
                     }
                    searchResults.innerHTML = html;
                 }
            },

            initFilters() {
                const filters = ['category', 'status', 'stock'];
                filters.forEach(filter => {
                    const element = document.getElementById(`${filter}-filter`);
                    if (element) {
                        element.addEventListener('change', () => {
                            this.state.filters[filter] = element.value;
                            this.state.currentPage = 1;
                            this.loadProducts();
                        });
                    }
                });
            },

            initBulkActions() {
                const bulkActionsDropdown = document.getElementById('bulk-actions-dropdown');
                if (bulkActionsDropdown) {
                    const bulkActions = [
                        { text: 'Publish Selected', icon: 'fa-check text-green-500' },
                        { text: 'Draft Selected', icon: 'fa-eye-slash text-gray-500' },
                        { text: 'Delete Selected', icon: 'fa-trash text-red-500' },
                        { text: 'Edit Categories', icon: 'fa-tags text-purple-500' }
                    ];

                    let html = '';
                    bulkActions.forEach((action, index) => {
                        html += `
                            <div class="woodash-dropdown-item woodash-fade-in" style="animation-delay: ${index * 0.1}s" data-action="${action.text}">
                                <i class="fa-solid ${action.icon}"></i>
                                <span>${action.text}</span>
                            </div>
                        `;
                    });
                    bulkActionsDropdown.innerHTML = html;

                    bulkActionsDropdown.addEventListener('click', (e) => {
                        const actionItem = e.target.closest('.woodash-dropdown-item');
                        if (actionItem) {
                            const action = actionItem.dataset.action;
                            this.handleBulkAction(action);
                        }
                    });
                }
            },

            initAnimations() {
                // Initialize background animations
                const bgAnimation = document.querySelector('.woodash-bg-animation');
                if (bgAnimation) {
                    let mouseX = 0;
                    let mouseY = 0;
                    let targetX = 0;
                    let targetY = 0;
                    let rafId = null;

                    const handleMouseMove = debounce((e) => {
                        const rect = e.target.getBoundingClientRect();
                        targetX = ((e.clientX - rect.left) / rect.width) * 100;
                        targetY = ((e.clientY - rect.top) / rect.height) * 100;
                    }, 16);

                    bgAnimation.addEventListener('mousemove', handleMouseMove);

                    function updateMousePosition() {
                        mouseX += (targetX - mouseX) * 0.1;
                        mouseY += (targetY - mouseY) * 0.1;
                        bgAnimation.style.setProperty('--mouse-x', mouseX + '%');
                        bgAnimation.style.setProperty('--mouse-y', mouseY + '%');
                        rafId = requestAnimationFrame(updateMousePosition);
                    }
                    updateMousePosition();

                    window.addEventListener('unload', () => {
                        if (rafId) {
                            cancelAnimationFrame(rafId);
                        }
                    });
                }

                // Initialize mobile menu
                const menuToggle = document.getElementById('woodash-menu-toggle');
                const sidebar = document.querySelector('.woodash-sidebar');
                
                menuToggle?.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                });

                // Initialize scroll to top
                const scrollTopButton = document.getElementById('woodash-scroll-to-top');
                if (scrollTopButton) {
                    window.addEventListener('scroll', () => {
                        scrollTopButton.style.display = window.scrollY > 300 ? 'flex' : 'none';
                    });

                    scrollTopButton.addEventListener('click', () => {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    });
                }
            },

            toggleDropdown(dropdown) {
                if (dropdown) {
                    dropdown.classList.toggle('hidden');
                }
            },

            closeAllDropdowns() {
                const dropdowns = document.querySelectorAll('.woodash-dropdown:not(.hidden)');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            },

            updateBulkActionsVisibility() {
                const bulkActionsBtn = document.getElementById('bulk-actions-btn');
                const selectedCountSpan = document.getElementById('selected-count');
                if (bulkActionsBtn && selectedCountSpan) {
                    const selectedCount = this.state.selectedItems.size;
                    selectedCountSpan.textContent = selectedCount;
                    bulkActionsBtn.style.display = selectedCount > 0 ? 'flex' : 'none';
                }
            },

            handleBulkAction(action) {
                if (this.state.selectedItems.size === 0) return;

                const confirmMessage = {
                    'Publish Selected': 'Are you sure you want to publish the selected products?',
                    'Draft Selected': 'Are you sure you want to move the selected products to draft?',
                    'Delete Selected': 'Are you sure you want to delete the selected products? This action cannot be undone.',
                    'Edit Categories': 'Edit categories for selected products'
                }[action];

                if (confirmMessage && confirm(confirmMessage)) {
                    this.showNotification(`${action} completed successfully`, 'success');
                    // Implement actual bulk action logic here
                    console.log(`${action} for products:`, Array.from(this.state.selectedItems));
                    // Clear selection after action
                    this.state.selectedItems.clear();
                    document.getElementById('select-all').checked = false;
                    document.querySelectorAll('.product-checkbox').forEach(checkbox => checkbox.checked = false);
                    this.updateBulkActionsVisibility();
                }
            },

            loadProducts() {
                this.state.isLoading = true;
                const tbody = document.getElementById('products-table-body');
                if (tbody) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-8">
                                <div class="flex items-center justify-center">
                                    <div class="woodash-loading-spinner"></div>
                                    <span class="ml-3 text-gray-600">Loading products...</span>
                                </div>
                            </td>
                        </tr>
                    `;
                }

                // Simulate API call
                setTimeout(() => {
                    this.state.isLoading = false;
                    this.renderTable();
                    this.updatePagination();
                     this.updateBulkActionsVisibility();
                }, 500);
            },

            renderTable() {
                const tbody = document.getElementById('products-table-body');
                if (!tbody) return;

                // Sample data (replace with actual data fetching)
                const products = [
                    {
                        id: '1',
                        image: 'https://via.placeholder.com/40',
                        name: 'Wireless Headphones',
                        sku: 'WH-001',
                        category: 'Electronics',
                        price: '$199.99',
                        regularPrice: '$249.99',
                        stockStatus: 'instock',
                        stockQuantity: 45,
                        status: 'published'
                    },
                    {
                         id: '2',
                        image: 'https://via.placeholder.com/40',
                        name: 'Smart Watch',
                        sku: 'SW-002',
                        category: 'Electronics',
                        price: '$299.99',
                        regularPrice: '$349.99',
                        stockStatus: 'lowstock',
                        stockQuantity: 5,
                        status: 'published'
                    },
                     {
                         id: '3',
                        image: 'https://via.placeholder.com/40',
                        name: 'Bluetooth Speaker',
                        sku: 'BS-003',
                        category: 'Electronics',
                        price: '$79.99',
                        regularPrice: '',
                        stockStatus: 'instock',
                        stockQuantity: 120,
                        status: 'published'
                    },
                     {
                         id: '4',
                        image: 'https://via.placeholder.com/40',
                        name: 'Gaming Mouse',
                        sku: 'GM-004',
                        category: 'Accessories',
                        price: '$49.99',
                        regularPrice: '',
                        stockStatus: 'outofstock',
                        stockQuantity: 0,
                        status: 'draft'
                    },
                    {
                         id: '5',
                        image: 'https://via.placeholder.com/40',
                        name: 'USB-C Hub',
                        sku: 'UH-005',
                        category: 'Accessories',
                        price: '$29.99',
                        regularPrice: '',
                        stockStatus: 'instock',
                        stockQuantity: 88,
                        status: 'published'
                    }
                    // Add more sample products here
                ];

                let html = '';
                products.forEach((product, index) => {
                    const isSelected = this.state.selectedItems.has(product.id);
                    const stockBadgeClass = product.stockStatus === 'instock' ? 'woodash-badge-success' : (product.stockStatus === 'lowstock' ? 'woodash-badge-warning' : 'woodash-badge-danger');
                    const statusBadgeClass = product.status === 'published' ? 'woodash-badge-success' : (product.status === 'draft' ? 'woodash-badge-secondary' : 'woodash-badge-danger');
                    
                    html += `
                        <tr class="woodash-fade-in hover:bg-gray-50 transition-colors duration-200" style="animation-delay: ${index * 0.05}s">
                            <td>
                                <input type="checkbox" class="rounded border-gray-300 text-[#00CC61] focus:ring-[#00CC61] product-checkbox" data-product-id="${product.id}" ${isSelected ? 'checked' : ''}>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="${product.image}" alt="Product" class="w-10 h-10 rounded-lg object-cover">
                                    <div>
                                        <p class="font-medium text-gray-900">${product.name}</p>
                                        <p class="text-sm text-gray-500">SKU: ${product.sku}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="woodash-badge woodash-badge-blue">${product.category}</span>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900">${product.price}</span>
                                    ${product.regularPrice ? `<span class="text-sm text-gray-500 line-through">${product.regularPrice}</span>` : ''}
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="woodash-badge ${stockBadgeClass}">${product.stockStatus.replace('stock', ' Stock').replace('low', 'Low ').replace('out', 'Out of ')}</span>
                                    <span class="text-sm text-gray-500">${product.stockQuantity} units</span>
                                </div>
                            </td>
                            <td>
                                <span class="woodash-badge ${statusBadgeClass}">${product.status.charAt(0).toUpperCase() + product.status.slice(1)}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <button class="text-gray-400 hover:text-[#00CC61] transition-colors duration-200" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-red-600 transition-colors duration-200" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-blue-600 transition-colors duration-200" title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-purple-600 transition-colors duration-200" title="More">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                tbody.innerHTML = html;
                
                // Re-attach event listeners to new checkboxes
                document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', (e) => {
                        const productId = e.target.dataset.productId;
                        if (e.target.checked) {
                            this.state.selectedItems.add(productId);
                        } else {
                            this.state.selectedItems.delete(productId);
                        }
                        this.updateBulkActionsVisibility();
                    });
                });

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
                    // Simplified pagination display
                    const maxVisiblePages = 5; // Show a limited number of pages
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

            showNotification(message, type = 'success') {
                 const notification = document.createElement('div');
                notification.className = `woodash-notification woodash-notification-${type} woodash-fade-in`;
                notification.innerHTML = `
                    <div class="flex items-center gap-3">
                        <i class="fa-solid ${type === 'success' ? 'fa-circle-check text-green-500' : 'fa-circle-exclamation text-red-500'}"></i>
                        <p>${message}</p>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.transform = 'translateX(100%)';
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            },

            exportProducts() {
                // Implement export logic
                 this.showNotification('Exporting products...');
            },

            performSearch(query) {
                // Simulate search results
                 const allProducts = ['Wireless Headphones', 'Smart Watch', 'Bluetooth Speaker', 'Gaming Mouse', 'USB-C Hub', 'Mechanical Keyboard', 'Webcam', 'Monitor'];
                const filteredResults = allProducts.filter(product => product.toLowerCase().includes(query.toLowerCase()));
                this.populateSearchResults(filteredResults);
            },

            showSearchResults() {
                const searchResults = document.getElementById('product-search-results');
                if (searchResults) {
                    searchResults.classList.add('active');
                }
            },

            hideSearchResults() {
                const searchResults = document.getElementById('product-search-results');
                if (searchResults) {
                    searchResults.classList.remove('active');
                }
            },

             // Populate additional cards with sample data
            populateAdditionalCards() {
                const topProductsContainer = document.getElementById('top-performing-products');
                const lowStockContainer = document.getElementById('low-stock-alerts');

                if (topProductsContainer) {
                    const topProducts = [
                         {
                            image: 'https://via.placeholder.com/48',
                            name: 'Wireless Headphones',
                            sales: 245,
                            increase: '15.3%'
                        },
                        {
                            image: 'https://via.placeholder.com/48',
                            name: 'Bluetooth Speaker',
                            sales: 180,
                            increase: '10.1%'
                        },
                         {
                            image: 'https://via.placeholder.com/48',
                            name: 'Smart Watch',
                            sales: 150,
                            increase: '8.5%'
                        },
                    ];

                    let html = '';
                    topProducts.forEach((product, index) => {
                        html += `
                            <div class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 woodash-fade-in" style="animation-delay: ${index * 0.08}s">
                                <img src="${product.image}" alt="Product" class="w-12 h-12 rounded-lg object-cover">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-gray-900">${product.name}</p>
                                        <span class="text-sm font-medium text-green-600">+${product.increase}</span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="woodash-progress flex-1">
                                            <div class="woodash-progress-bar" style="width: ${(product.sales / 300) * 100}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-500">${product.sales} sales</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                     if (topProducts.length === 0) {
                         html = '<div class="text-gray-500 text-center py-4">No top performing products found.</div>';
                     }
                    topProductsContainer.innerHTML = html;
                }

                if (lowStockContainer) {
                    const lowStockItems = [
                        {
                            image: 'https://via.placeholder.com/48',
                            name: 'Smart Watch',
                            stock: 5,
                        },
                        {
                            image: 'https://via.placeholder.com/48',
                            name: 'Gaming Mouse',
                            stock: 0,
                        },
                         {
                            image: 'https://via.placeholder.com/48',
                            name: 'Webcam',
                            stock: 8,
                        },
                    ];

                    let html = '';
                    lowStockItems.forEach((item, index) => {
                         const stockText = item.stock === 0 ? 'Out of Stock' : `${item.stock} left`;
                        const badgeClass = item.stock <= 5 ? 'woodash-badge-danger' : 'woodash-badge-warning';
                        const actionText = item.stock === 0 ? 'Reorder now' : 'Order soon';
                        const progressBarClass = item.stock <= 5 ? 'woodash-progress-bar-red' : '';

                        html += `
                            <div class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 woodash-fade-in" style="animation-delay: ${index * 0.08}s">
                                <img src="${item.image}" alt="Product" class="w-12 h-12 rounded-lg object-cover">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-gray-900">${item.name}</p>
                                        <span class="woodash-badge ${badgeClass}">${stockText}</span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="woodash-progress flex-1">
                                            <div class="woodash-progress-bar ${progressBarClass}" style="width: ${((10 - item.stock) / 10) * 100}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-500">${actionText}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                     if (lowStockItems.length === 0) {
                         html = '<div class="text-gray-500 text-center py-4">No low stock alerts.</div>';
                     }
                    lowStockContainer.innerHTML = html;
                }
            }
        };

        // Initialize the product manager when the DOM is loaded
        ProductManager.init();
        ProductManager.populateAdditionalCards();

        // Modal Elements
        const addProductBtn = document.getElementById('add-product-btn');
        const addProductModal = document.getElementById('add-product-modal');
        const closeAddProductModalBtn = document.getElementById('close-add-product-modal');
        const cancelAddProductBtn = document.getElementById('cancel-add-product-btn');
        const addProductForm = document.getElementById('add-product-form');

        // Initialize modal
        if (addProductModal) {
            console.log('Modal initialized');
            addProductModal.classList.add('hidden');
        }

        // Show Modal
        if (addProductBtn) {
            console.log('Add Product button found');
            addProductBtn.addEventListener('click', () => {
                console.log('Add Product button clicked');
                if (addProductModal) {
                    console.log('Modal found, removing hidden class');
                    addProductModal.classList.remove('hidden');
                    addProductModal.classList.add('woodash-fade-in');
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                } else {
                    console.log('Modal not found');
                }
            });
        } else {
            console.log('Add Product button not found');
        }

        // Close Modal Functions
        function closeModal() {
            if (addProductModal) {
                addProductModal.classList.add('hidden');
                addProductModal.classList.remove('woodash-fade-in');
                document.body.style.overflow = ''; // Restore scrolling
                if (addProductForm) {
                    addProductForm.reset(); // Reset form
                }
            }
        }

        // Close Modal Events
        closeAddProductModalBtn?.addEventListener('click', closeModal);
        cancelAddProductBtn?.addEventListener('click', closeModal);

        // Close modal when clicking outside
        addProductModal?.addEventListener('click', (e) => {
            if (e.target === addProductModal) {
                closeModal();
            }
        });

        // Handle Image Upload Preview
        const productImages = document.getElementById('product-images');
        const imagePreview = document.getElementById('image-preview');

        productImages?.addEventListener('change', function(e) {
            imagePreview.innerHTML = ''; // Clear previous previews
            
            [...e.target.files].forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                            <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        `;
                        imagePreview.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });

        // Handle Form Submission
        addProductForm?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic form validation
            const requiredFields = addProductForm.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                alert('Please fill in all required fields');
                return;
            }

            // Here you would typically send the form data to your server
            // For now, we'll just show a success message
            alert('Product added successfully!');
            closeModal();
        });

        // Handle drag and drop for images
        const dropZone = document.querySelector('.border-dashed');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone?.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone?.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone?.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-[#00CC61]');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-[#00CC61]');
        }

        dropZone?.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            productImages.files = files;
            
            // Trigger change event to show previews
            const event = new Event('change');
            productImages.dispatchEvent(event);
        }
    });

    // Show loading state
    function showLoading() {
        document.querySelector('.chart-container').classList.add('loading');
    }

    // Hide loading state
    function hideLoading() {
        document.querySelector('.chart-container').classList.remove('loading');
    }

    // Show page transition
    function showPageTransition() {
        document.querySelector('.page-transition').classList.add('active');
    }

    // Hide page transition
    function hidePageTransition() {
        document.querySelector('.page-transition').classList.remove('active');
    }
</script>

</body>
</html>