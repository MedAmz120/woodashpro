<?php
/**
 * WooDash Pro Dashboard Template
 *
 * @package WoodashPro
 * @version 2.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}
?>
<!-- Google Fonts & Font Awesome for icons -->
<!-- Removed external Inter font; using system font stack -->
-<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
-<script src="https://cdn.tailwindcss.com"></script>
-<!-- Chart.js: Use a valid CDN and only load once -->
-<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
-<!-- Lottie player: Only load once -->
-<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
+                <!-- External libraries are enqueued via WordPress. Duplicate CDN tags removed. -->
                
                <!-- jQuery fallback - ensure jQuery is available -->
                <script>
                // Ensure jQuery is available for WoodDash Pro
                (function() {
                    if (typeof jQuery === 'undefined') {
                        console.warn('WoodDash: jQuery not loaded via WordPress, loading fallback');
                        const jQueryScript = document.createElement('script');
                        jQueryScript.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
                        jQueryScript.onload = function() {
                            console.log('WoodDash: jQuery loaded successfully');
                            window.$ = window.jQuery;
                        };
                        document.head.appendChild(jQueryScript);
                    } else {
                        // Ensure jQuery is available globally
                        window.$ = jQuery;
                        console.log('WoodDash: jQuery already available');
                    }
                })();
                </script>
     <style type="text/tailwindcss">
         @layer base {
             :root {
                 --woodash-primary: #00CC61;
                 --woodash-primary-hover: #00b357;
                 --woodash-bg: #F8FAFC;
                 --woodash-card: #FFFFFF;
                 --woodash-text: #1F2937;
                 --woodash-text-light: #6B7280;
                 --woodash-border: #E5E7EB;
             }
             
             [data-theme="dark"] {
                 --woodash-bg: #0F172A;
                 --woodash-card: #1E293B;
                 --woodash-text: #F1F5F9;
                 --woodash-text-light: #94A3B8;
                 --woodash-border: #334155;
             }
             
             * {
                 transition-property: background-color, border-color, color, fill, stroke;
                 transition-duration: 200ms;
                 transition-timing-function: ease-in-out;
             }
             
             body {
                 font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', sans-serif;
                 background-color: var(--woodash-bg);
                 color: var(--woodash-text);
                 transition: all 0.3s ease;
             }
             
             /* Smooth scrolling */
             html {
                 scroll-behavior: smooth;
             }
             
             /* Focus indicators for accessibility */
             *:focus {
                 outline: 2px solid var(--woodash-primary);
                 outline-offset: 2px;
             }
         }
         
         /* Enhanced responsive grid system */
         .woodash-responsive-grid {
             display: grid;
             gap: 1rem;
             grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
         }
         
         @media (min-width: 640px) {
             .woodash-responsive-grid {
                 gap: 1.5rem;
                 grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
             }
         }
         
         @media (min-width: 1024px) {
             .woodash-responsive-grid {
                 gap: 2rem;
                 grid-template-columns: repeat(4, 1fr);
             }
         }
         
         .woodash-card {
             @apply bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-md;
             background-color: var(--woodash-card);
             border-color: var(--woodash-border);
         }
         
         .woodash-metric-card {
             @apply woodash-card p-4 md:p-6 flex flex-col justify-between min-h-[200px] h-full relative overflow-hidden;
         }
         
         .woodash-metric-title {
            @apply text-gray-600 dark:text-gray-300 text-sm font-medium tracking-wide mb-2 flex flex-wrap items-center gap-2;
            color: var(--woodash-text-light);
        }
        
        .woodash-metric-value {
            @apply text-2xl md:text-3xl font-bold flex items-center;
            color: var(--woodash-text);
        }
        
        .woodash-metric-icon {
            @apply ml-2 text-xl md:text-2xl rounded-full p-2 bg-opacity-10 flex-shrink-0;
        }
    .woodash-metric-green {
        @apply bg-green-100 text-green-600;
    }
    .woodash-metric-blue {
        @apply bg-blue-100 text-blue-600;
    }
    .woodash-metric-purple {
        @apply bg-purple-100 text-purple-600;
    }
    .woodash-metric-orange {
        @apply bg-orange-100 text-orange-600;
    }
    .woodash-metric-red {
        @apply bg-red-100 text-red-600;
    }
    .woodash-btn {
        @apply px-4 py-2 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 active:scale-95;
    }
    .woodash-btn-primary {
        @apply bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 shadow-lg hover:shadow-xl border border-green-400;
    }
    .woodash-btn-secondary {
        @apply bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 hover:from-emerald-200 hover:to-green-200 border border-emerald-200;
    }
    .woodash-nav-link {
        @apply flex items-center px-4 py-3 text-gray-600 hover:text-[#00CC61] hover:bg-gray-50 rounded-lg transition-all duration-200;
    }
    .woodash-nav-link.active {
        @apply bg-[#00CC61] bg-opacity-10 text-[#00CC61] font-medium;
    }
    .woodash-table th {
        @apply px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100;
    }
    .woodash-table td {
        @apply px-4 py-3 text-sm text-gray-700 border-b border-gray-100;
    }
    .woodash-chart-container {
        @apply bg-white p-6 rounded-xl shadow-sm border border-gray-100;
    }
    .woodash-loading {
        @apply animate-pulse bg-gray-200 rounded;
    }
    .woodash-tooltip {
        @apply invisible absolute bg-gray-900 text-white px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-200;
    }
    .woodash-tooltip-trigger:hover .woodash-tooltip {
        @apply visible opacity-100;
    }
    @keyframes slideIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
     {
        animation: slideIn 0.3s ease-out forwards;
    }
    .woodash-badge {
        @apply px-2 py-1 rounded-full text-xs font-medium;
    }
    .woodash-badge-success {
        @apply bg-green-100 text-green-700;
    }
    .woodash-badge-warning {
        @apply bg-yellow-100 text-yellow-700;
    }
    .woodash-badge-danger {
        @apply bg-red-100 text-red-700;
    }
    .woodash-progress {
        @apply h-2 bg-gray-100 rounded-full overflow-hidden;
    }
    .woodash-progress-bar {
        @apply h-full bg-[#00CC61] rounded-full transition-all duration-300;
    }
    .woodash-dropdown {
        @apply absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50;
    }
    .woodash-dropdown-item {
        @apply px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2;
    }
    .woodash-notification {
        @apply fixed top-4 right-4 bg-white rounded-lg shadow-lg border border-gray-100 p-4 transform transition-all duration-300;
    }
    .woodash-notification-success {
        @apply border-l-4 border-green-500;
    }
    .woodash-notification-error {
        @apply border-l-4 border-red-500;
    }
    .woodash-glass-effect {
        @apply backdrop-blur-md bg-white/80 border border-white/20;
    }
    . {
        @apply transition-all duration-300 hover:transform hover:scale-105 hover:shadow-lg;
    }
    .woodash-gradient-text {
        @apply bg-clip-text text-transparent bg-gradient-to-r from-[#00CC61] to-[#00b357];
    }
    .woodash-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #E2E8F0 #F8FAFC;
    }
    .woodash-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .woodash-scrollbar::-webkit-scrollbar-track {
        background: #F8FAFC;
    }
    .woodash-scrollbar::-webkit-scrollbar-thumb {
        background-color: #E2E8F0;
        border-radius: 3px;
    }
    .woodash-bg-pattern {
        background-color: #f8fafc;
        background-image: 
            radial-gradient(at 40% 20%, hsla(156, 100%, 74%, 0.15) 0px, transparent 50%),
            radial-gradient(at 80% 0%, hsla(189, 100%, 56%, 0.15) 0px, transparent 50%),
            radial-gradient(at 0% 50%, hsla(355, 100%, 93%, 0.15) 0px, transparent 50%),
            radial-gradient(at 80% 50%, hsla(340, 100%, 76%, 0.15) 0px, transparent 50%),
            radial-gradient(at 0% 100%, hsla(269, 100%, 77%, 0.15) 0px, transparent 50%),
            radial-gradient(at 80% 100%, hsla(242, 100%, 70%, 0.15) 0px, transparent 50%),
            radial-gradient(at 0% 0%, hsla(343, 100%, 76%, 0.15) 0px, transparent 50%);
        position: relative;
        overflow: hidden;
    }
    .woodash-bg-animation {
        position: relative;
        overflow: hidden;
    }
    .woodash-bg-animation::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            linear-gradient(45deg, rgba(0, 204, 97, 0.05) 0%, rgba(0, 179, 87, 0.05) 100%),
            linear-gradient(135deg, rgba(0, 204, 97, 0.03) 0%, rgba(0, 179, 87, 0.03) 100%);        z-index: 0;
    }
    .woodash-bg-animation::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), 
            rgba(0, 204, 97, 0.05) 0%, 
            transparent 50%);
        opacity: 0;
        pointer-events: none;
        z-index: 1;
    }
    .woodash-bg-animation:hover::after {
        opacity: 1;
    }
    .woodash-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(40px);
        opacity: 0.5;
        cursor: pointer;
    }
    .woodash-orb:hover {
        filter: blur(30px);
        opacity: 0.7;
        transform: scale(1.1);
    }
    .woodash-orb-1 {
        width: 400px;
        height: 400px;
        background: radial-gradient(circle at center, rgba(0, 204, 97, 0.2), transparent 70%);
        top: -150px;
        left: -150px;
    }
    .woodash-orb-2 {
        width: 500px;
        height: 500px;
        background: radial-gradient(circle at center, rgba(0, 179, 87, 0.15), transparent 70%);
        bottom: -200px;
        right: -200px;
    }
    .woodash-orb-3 {
        width: 350px;
        height: 350px;
        background: radial-gradient(circle at center, rgba(0, 204, 97, 0.1), transparent 70%);
        top: 40%;
        left: 60%;
        transform: translate(-50%, -50%);
    }
    .woodash-line {
        position: absolute;
        background: linear-gradient(90deg, transparent, rgba(0, 204, 97, 0.1), transparent);
        height: 2px;
        width: 100%;
        transition: all 0.3s ease;
    }
    .woodash-line:hover {
        height: 3px;
        background: linear-gradient(90deg, transparent, rgba(0, 204, 97, 0.2), transparent);
    }
    .woodash-shimmer {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            45deg,
            transparent 0%,
            rgba(255, 255, 255, 0.15) 50%,
            transparent 100%
        );
        z-index: 2;
        transition: opacity 0.3s ease;
    }
    .woodash-spotlight {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), 
            rgba(0, 204, 97, 0.08) 0%, 
            transparent 50%);
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
        z-index: 3;
        mix-blend-mode: screen;
    }
    .woodash-particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: rgba(0, 204, 97, 0.3);
        border-radius: 50%;
        pointer-events: none;
        transition: all 0.3s ease;
    }
    @keyframes woodash-bg-shift {
        0% {
            transform: scale(1) rotate(0deg);
            background-position: 0% 0%;
            filter: hue-rotate(0deg);
        }
        25% {
            transform: scale(1.02) rotate(0.2deg);
            background-position: 25% 25%;
            filter: hue-rotate(5deg);
        }
        50% {
            transform: scale(1.05) rotate(0.5deg);
            background-position: 50% 50%;
            filter: hue-rotate(10deg);
        }
        75% {
            transform: scale(1.02) rotate(0.2deg);
            background-position: 75% 75%;
            filter: hue-rotate(5deg);
        }
        100% {
            transform: scale(1) rotate(0deg);
            background-position: 100% 100%;
            filter: hue-rotate(0deg);
        }
    }
    @keyframes woodash-orb-float {
        0%, 100% {
            transform: translate(0, 0) scale(1);
        }
        25% {
            transform: translate(20px, -20px) scale(1.1);
        }
        50% {
            transform: translate(0, -40px) scale(1);
        }
        75% {
            transform: translate(-20px, -20px) scale(0.9);
        }
    }
    @keyframes woodash-line-move {
        0% {
            transform: translateX(-100%);
            opacity: 0;
        }
        50% {
            opacity: 0.5;
        }
        100% {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    @keyframes woodash-shimmer-move {
        0% {
            transform: translateX(-100%) translateY(-100%);
        }
        100% {
            transform: translateX(100%) translateY(100%);
        }
    }
    .woodash-content {
        position: relative;
        z-index: 2;
    }
    .woodash-shimmer {
        position: relative;
        overflow: hidden;
    }
    .woodash-shimmer::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.2),
            transparent
        );
    }
    @keyframes woodash-shimmer {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }
    .woodash-float {
        animation: none;
    }
    @keyframes woodash-float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    .woodash-pulse {
        animation: woodash-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes woodash-pulse {
        0%, 100% {
            opacity: 0.5;
            transform: scale(1);
        }
        50% {
            opacity: 0.7;
            transform: scale(1.02);
        }
    }
    @keyframes woodash-subtle-glow {
        0%, 100% {
            box-shadow: 0 0 8px rgba(0, 204, 97, 0.3);
        }
        50% {
            box-shadow: 0 0 15px rgba(0, 204, 97, 0.5);
        }
    }
    .woodash-slide {
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.5s ease-in-out;
    }
    .woodash-slide.active {
        opacity: 1;
        transform: translateX(0);
    }
    .woodash-slide-dot.active {
        background-color: #00CC61;
    }
    .woodash-slide-control {
        opacity: 0;
        transition: all 0.3s ease;
    }
    .woodash-slideshow:hover .woodash-slide-control {
        opacity: 1;
    }
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-100%);
        }
        }
    .woodash-fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        background: #F8FAFC;
        overflow: hidden;
    }
    
    .woodash-fullscreen .woodash-content {
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    .woodash-fullscreen .woodash-sidebar {
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        z-index: 1000;
    }
    
    .woodash-fullscreen .woodash-main {
        margin-left: 16rem;
        width: calc(100% - 16rem);
    }
    
    @media (max-width: 768px) {
        .woodash-fullscreen .woodash-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .woodash-fullscreen .woodash-sidebar.active {
            transform: translateX(0);
        }
        
        .woodash-fullscreen .woodash-main {
            margin-left: 0;
            width: 100%;
        }
    }
    
    /* Add these new search styles */
    .woodash-search-container {
        position: relative;
        min-width: 300px;
        @apply hover:shadow-md transition-shadow duration-200;
        display: flex; /* Use flexbox to align input and button */
        align-items: center;
        border: 1px solid #a4a4a4; /* Add grey border to the container */
        border-radius: 999px; /* Add rounded corners to the container */
        overflow: hidden; /* Hide overflow for rounded corners */
        background-color: white; /* Ensure white background */
    }
    
    .woodash-search-input {
        @apply pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#00CC61] focus:border-transparent transition-all duration-300;
        @apply pr-8;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        @apply hover:border-gray-300;
        border: none; /* Remove individual border, container has the border */
        box-shadow: none; /* Remove individual shadow */
        flex-grow: 1; /* Allow input to take up available space */
        padding-left: 3em; /* Adjust padding for icon */
        padding-right: 1em; /* Adjust padding */
        border-radius: 999px 0 0 999px; /* Rounded left corners, square right */
        background: transparent; /* Make input background transparent */
        color: #333; /* Darken text color */
    }
    
    .woodash-search-input:focus {
        @apply shadow-lg;
        /* Remove focus styles on input itself */
        transform: none;
        box-shadow: none;
        @apply ring-0;
    }
    
    .woodash-search-icon {
        @apply absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 transition-colors duration-200;
        font-size: 1.1rem;
        left: 1em; /* Position icon inside the rounded corner */
        z-index: 10; /* Ensure icon is above the input */
    }
    
    .woodash-search-input:focus + .woodash-search-icon {
        @apply text-[#00CC61];
        transform: translateY(-50%) scale(1); /* Prevent icon scaling on input focus */
    }
    
    /* Style for the search button */
    .woodash-search-button {
        @apply flex items-center justify-center bg-transparent text-gray-600 hover:text-[#00CC61] transition-colors duration-200;
        border: none; /* Remove default button border */
        padding: 0 1em; /* Adjust padding */
        cursor: pointer;
        border-radius: 0 999px 999px 0; /* Square left, rounded right corners */
        height: 100%; /* Match input height */
    }
    
    .woodash-search-results {
        @apply absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-200;
        max-height: 400px;
        overflow-y: auto;
        backdrop-filter: blur(8px);
        background: rgba(255, 255, 255, 0.95);
    }
    
    .woodash-search-results.active {
        @apply scale-100 opacity-100;
    }
    
    @keyframes woodash-search-slide {
        from {
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .woodash-search-item {
        @apply flex items-center gap-3 p-3 hover:bg-gray-50 transition-colors duration-200 cursor-pointer;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .woodash-search-item:last-child {
        border-bottom: none;
    }
    
    .woodash-search-item:hover {
        background: rgba(0, 204, 97, 0.05);
    }
    
    .woodash-search-item-icon {
        @apply w-10 h-10 rounded-lg flex items-center justify-center text-white transition-transform duration-200;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .woodash-search-item:hover .woodash-search-item-icon {
        transform: scale(1.1);
    }
    
    .woodash-search-item-content {
        @apply flex-1;
    }
    
    .woodash-search-item-title {
        @apply font-medium text-gray-900;
    }
    
    .woodash-search-item-subtitle {
        @apply text-sm text-gray-500;
    }
    
    .woodash-search-empty {
        @apply p-6 text-center text-gray-500;
    }
    
    .woodash-search-loading {
        @apply p-6 text-center;
    }
    
    .woodash-search-loading-spinner {
        @apply w-6 h-6 border-2 border-gray-200 border-t-[#00CC61] rounded-full animate-spin mx-auto;
    }
    
    .woodash-search-category {
        @apply px-3 py-2 text-xs font-medium text-gray-500 bg-gray-50;
    }
    
    .woodash-search-shortcut {
        @apply ml-2 px-2 py-0.5 text-xs font-medium text-gray-400 bg-gray-100 rounded;
    }
    
    /* Enhanced animations */
    @keyframes woodash-shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    
    @keyframes woodash-fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes woodash-pulse-ring {
        0% {
            transform: scale(0.33);
            opacity: 1;
        }
        80%, 100% {
            transform: scale(2.33);
            opacity: 0;
        }
    }
    
    /* Improved focus styles for accessibility */
    .woodash-focus:focus-visible {
        outline: 2px solid var(--woodash-primary);
        outline-offset: 2px;
        border-radius: 0.375rem;
    }
    
    /* Enhanced responsive design */
    @media (max-width: 640px) {
        .woodash-metric-card {
            @apply p-4 min-h-[180px];
        }
        
        .woodash-metric-value {
            @apply text-xl;
        }
        
        .woodash-search-container {
            @apply w-full;
        }
    }
    
    /* Notification Toast Container */
    .woodash-notification-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        max-width: 400px;
        pointer-events: none;
    }
    
    .woodash-notification {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        margin-bottom: 12px;
        transform: translateX(100%);
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        pointer-events: auto;
        border-left: 4px solid;
        overflow: hidden;
        max-width: 400px;
    }
    
    .woodash-notification.show {
        transform: translateX(0);
    }
    
    .woodash-notification-success {
        border-left-color: #10B981;
    }
    
    .woodash-notification-error {
        border-left-color: #EF4444;
    }
    
    .woodash-notification-warning {
        border-left-color: #F59E0B;
    }
    
    .woodash-notification-info {
        border-left-color: #3B82F6;
    }
    
    .woodash-notification-header {
        padding: 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    
    .woodash-notification-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .woodash-notification-icon.success {
        background: #DCFCE7;
        color: #059669;
    }
    
    .woodash-notification-icon.error {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .woodash-notification-icon.warning {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .woodash-notification-icon.info {
        background: #DBEAFE;
        color: #2563EB;
    }
    
    .woodash-notification-content {
        flex: 1;
        min-width: 0;
    }
    
    .woodash-notification-title {
        font-weight: 600;
        color: #111827;
        margin: 0 0 4px 0;
        font-size: 14px;
    }
    
    .woodash-notification-message {
        color: #6B7280;
        margin: 0 0 12px 0;
        font-size: 13px;
        line-height: 1.4;
    }
    
    .woodash-notification-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }
    
    .woodash-notification-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .woodash-notification-btn-primary {
        background: #3B82F6;
        color: white;
    }
    
    .woodash-notification-btn-primary:hover {
        background: #2563EB;
    }
    
    .woodash-notification-btn-secondary {
        background: #F3F4F6;
        color: #374151;
    }
    
    .woodash-notification-btn-secondary:hover {
        background: #E5E7EB;
    }
    
    .woodash-notification-close {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        border: none;
        background: #F3F4F6;
        color: #6B7280;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s;
    }
    
    .woodash-notification-close:hover {
        background: #E5E7EB;
        color: #374151;
    }
    
    .woodash-notification-progress {
        height: 3px;
        background: linear-gradient(90deg, #3B82F6, #10B981);
        transition: width 0.1s linear;
        width: 100%;
    }
    
    /* Toggle Switch Styles */
    .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
        background: #E5E7EB;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        appearance: none;
    }
    
    .toggle-switch:checked {
        background: #3B82F6;
    }
    
    .toggle-switch::before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 50%;
        transition: transform 0.3s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .toggle-switch:checked::before {
        transform: translateX(20px);
    }
    
    /* Dark mode for notifications */
    .dark .woodash-notification {
        background: #1F2937;
        color: white;
    }
    
    .dark .woodash-notification-title {
        color: white;
    }
    
    .dark .woodash-notification-message {
        color: #D1D5DB;
    }
    
    .dark .woodash-notification-close {
        background: #374151;
        color: #9CA3AF;
    }
    
    .dark .woodash-notification-close:hover {
        background: #4B5563;
        color: #F3F4F6;
    }
    
    .dark .woodash-notification-btn-secondary {
        background: #374151;
        color: #D1D5DB;
    }
    
    .dark .woodash-notification-btn-secondary:hover {
        background: #4B5563;
    }
    
    .dark .toggle-switch {
        background: #4B5563;
    }
    
    .dark .toggle-switch:checked {
        background: #3B82F6;
    }
    
    /* Responsive notifications */
    @media (max-width: 640px) {
        .woodash-notification-container {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
        }
        
        .woodash-notification {
            max-width: none;
        }
    }
    
    /* Better scrollbar styling */
    .woodash-scrollbar-modern {
        scrollbar-width: thin;
        scrollbar-color: #CBD5E0 transparent;
    }
    
    .woodash-scrollbar-modern::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .woodash-scrollbar-modern::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 4px;
    }
    
    .woodash-scrollbar-modern::-webkit-scrollbar-thumb {
        background: #CBD5E0;
        border-radius: 4px;
        border: 2px solid transparent;
        background-clip: content-box;
    }
    
    .woodash-scrollbar-modern::-webkit-scrollbar-thumb:hover {
        background: #9CA3AF;
        background-clip: content-box;
    }
    
    /* Enhanced Notification System Styles */
    .woodash-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border-left: 4px solid #00CC61;
        z-index: 9999;
        min-width: 320px;
        max-width: 400px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateX(100%);
        opacity: 0;
    }
    
    .woodash-notification.show {
        transform: translateX(0);
        opacity: 1;
    }
    
    .woodash-notification-success {
        border-left-color: #10B981;
        background: linear-gradient(135deg, #ECFDF5 0%, #F0FDF4 100%);
    }
    
    .woodash-notification-error {
        border-left-color: #EF4444;
        background: linear-gradient(135deg, #FEF2F2 0%, #FEF2F2 100%);
    }
    
    .woodash-notification-warning {
        border-left-color: #F59E0B;
        background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
    }
    
    .woodash-notification-info {
        border-left-color: #3B82F6;
        background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    }
    
    .woodash-notification-header {
        display: flex;
        align-items: center;
        justify-content: between;
        margin-bottom: 8px;
    }
    
    .woodash-notification-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 12px;
        color: white;
    }
    
    .woodash-notification-icon.success { background: #10B981; }
    .woodash-notification-icon.error { background: #EF4444; }
    .woodash-notification-icon.warning { background: #F59E0B; }
    .woodash-notification-icon.info { background: #3B82F6; }
    
    .woodash-notification-content {
        flex: 1;
    }
    
    .woodash-notification-title {
        font-weight: 600;
        font-size: 14px;
        color: #1F2937;
        margin-bottom: 4px;
    }
    
    .woodash-notification-message {
        font-size: 13px;
        color: #6B7280;
        line-height: 1.4;
    }
    
    .woodash-notification-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }
    
    .woodash-notification-btn {
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }
    
    .woodash-notification-btn-primary {
        background: #00CC61;
        color: white;
    }
    
    .woodash-notification-btn-primary:hover {
        background: #00b357;
    }
    
    .woodash-notification-btn-secondary {
        background: #F3F4F6;
        color: #6B7280;
    }
    
    .woodash-notification-btn-secondary:hover {
        background: #E5E7EB;
    }
    
    .woodash-notification-close {
        position: absolute;
        top: 8px;
        right: 8px;
        background: none;
        border: none;
        color: #9CA3AF;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: color 0.2s;
    }
    
    .woodash-notification-close:hover {
        color: #6B7280;
    }
    
    .woodash-notification-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: #00CC61;
        border-radius: 0 0 12px 0;
        transition: width 0.1s linear;
    }
    
    /* Notification Container */
    .woodash-notification-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        pointer-events: none;
    }
    
    .woodash-notification-container .woodash-notification {
        position: relative;
        top: auto;
        right: auto;
        margin-bottom: 12px;
        pointer-events: all;
    }
    
    /* Enhanced Notification Dropdown */
    .woodash-notifications-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        width: 380px;
        max-height: 500px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: 1px solid #E5E7EB;
        overflow: hidden;
        z-index: 1000;
        transform: translateY(-10px);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .woodash-notifications-dropdown.show {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
    }
    
    .woodash-notifications-header {
        padding: 16px 20px;
        border-bottom: 1px solid #E5E7EB;
        background: #F9FAFB;
    }
    
    .woodash-notifications-title {
        font-weight: 600;
        font-size: 16px;
        color: #1F2937;
        margin: 0;
    }
    
    .woodash-notifications-subtitle {
        font-size: 13px;
        color: #6B7280;
        margin-top: 2px;
    }
    
    .woodash-notifications-list {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .woodash-notification-item {
        padding: 16px 20px;
        border-bottom: 1px solid #F3F4F6;
        transition: background-color 0.2s;
        cursor: pointer;
        position: relative;
    }
    
    .woodash-notification-item:hover {
        background: #F9FAFB;
    }
    
    .woodash-notification-item.unread {
        background: #EFF6FF;
        border-left: 3px solid #3B82F6;
    }
    
    .woodash-notification-item.unread::before {
        content: '';
        position: absolute;
        top: 20px;
        right: 20px;
        width: 8px;
        height: 8px;
        background: #3B82F6;
        border-radius: 50%;
    }
    
    .woodash-notification-item-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 12px;
        flex-shrink: 0;
    }
    
    .woodash-notification-item-content {
        flex: 1;
        min-width: 0;
    }
    
    .woodash-notification-item-title {
        font-weight: 500;
        font-size: 14px;
        color: #1F2937;
        margin-bottom: 4px;
    }
    
    .woodash-notification-item-message {
        font-size: 13px;
        color: #6B7280;
        line-height: 1.4;
        margin-bottom: 6px;
    }
    
    .woodash-notification-item-time {
        font-size: 12px;
        color: #9CA3AF;
    }
    
    .woodash-notifications-footer {
        padding: 12px 20px;
        border-top: 1px solid #E5E7EB;
        background: #F9FAFB;
        display: flex;
        justify-content: between;
        gap: 12px;
    }
    
    .woodash-notifications-footer button {
        flex: 1;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .woodash-notifications-mark-read {
        background: #F3F4F6;
        color: #6B7280;
    }
    
    .woodash-notifications-mark-read:hover {
        background: #E5E7EB;
    }
    
    .woodash-notifications-clear {
        background: #EF4444;
        color: white;
    }
    
    .woodash-notifications-clear:hover {
        background: #DC2626;
    }
    
    /* Notification Badge Animation */
    .woodash-notification-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #EF4444;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 11px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    @keyframes notification-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    /* Sound Wave Animation */
    .sound-wave {
        display: inline-block;
        width: 4px;
        height: 4px;
        background: #00CC61;
        margin: 0 1px;
        border-radius: 50%;
    }
    
    @keyframes sound-wave {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.5); }
    }
    
    /* Context Menu Styles */
    .woodash-context-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        border: 1px solid #E5E7EB;
        z-index: 1000;
        min-width: 150px;
    }
    
    .woodash-context-menu .woodash-dropdown-item {
        @apply px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-3 cursor-pointer;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .woodash-context-menu .woodash-dropdown-item:last-child {
        border-bottom: none;
    }
    
    
    @keyframes pulse {
        from { opacity: 0.6; }
        to { opacity: 1; }
    }
    
    /* Enhanced Analytics Styles */
    
    .ai-insight-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .performance-metric {
        position: relative;
        overflow: hidden;
    }
    
    .performance-metric::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    }
    
    .performance-metric:hover::before {
        left: 100%;
    }
    
    .performance-metric:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    
    .journey-stage {
        position: relative;
        overflow: hidden;
    }
    
    .journey-stage::after {
        content: '';
        position: absolute;
        top: 50%;
        right: -20px;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 15px solid currentColor;
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
        opacity: 0.7;
    }
    
    .journey-stage:last-child::after {
        display: none;
    }
    
    
    @keyframes pulseGlow {
        0%, 100% {
            box-shadow: 0 0 5px rgba(59, 130, 246, 0.3);
        }
        50% {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.6);
        }
    }
    
    .woodash-btn-icon {
        @apply w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200;
        @apply bg-green-100 dark:bg-green-700 text-green-600 dark:text-green-300;
        @apply hover:bg-green-200 dark:hover:bg-green-600 hover:scale-110;
    }
    
    /* Responsive enhancements for analytics */
    @media (max-width: 768px) {
        .ai-insight-item {
            margin-bottom: 1rem;
        }
        
        .performance-metric {
            padding: 0.75rem !important;
        }
        
        .journey-stage {
            margin-bottom: 1rem;
        }
        
        .journey-stage::after {
            display: none;
        }
    }
    
    /* Dark mode enhancements */
    @media (prefers-color-scheme: dark) {
        .ai-insight-item {
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .performance-metric {
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .journey-stage {
            border-color: rgba(255, 255, 255, 0.1);
        }
    }
    
    /* Loading states */
    .analytics-loading {
        position: relative;
        overflow: hidden;
    }
    
    .analytics-loading::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(0, 204, 97, 0.1), transparent);
    }
    
    @keyframes loading-shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    </style>
    
<div id="woodash-dashboard" class="woodash-fullscreen woodash-bg-pattern">
    <div class="flex woodash-content">
        <!-- Sidebar -->
        <aside class="woodash-sidebar w-64 bg-white/90 border-r border-gray-100 p-6 woodash-glass-effect">
            <div class="flex items-center gap-3 mb-8 woodash-fade-in">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center woodash-glow">
                    <i class="fa-solid fa-chart-line text-white text-xl"></i>
                </div>
                <h2 class="text-xl font-bold woodash-gradient-text">WooDash Pro</h2>
            </div>
            <nav class="space-y-1">
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro')); ?>" class="woodash-nav-link active  woodash-slide-up">
                    <i class="fa-solid fa-gauge w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-orders')); ?>" class="woodash-nav-link  woodash-slide-up">
                    <i class="fa-solid fa-shopping-cart w-5"></i>
                    <span>Orders</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-products')); ?>" class="woodash-nav-link  woodash-slide-up">
                    <i class="fa-solid fa-box w-5"></i>
                    <span>Products</span>
                    <span class="woodash-badge woodash-badge-success ml-auto woodash-pulse">New</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-customers')); ?>" class="woodash-nav-link  woodash-slide-up">
                    <i class="fa-solid fa-users w-5"></i>
                    <span>Customers</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-stock')); ?>" class="woodash-nav-link  woodash-slide-up">
                    <i class="fa-solid fa-boxes-stacked w-5"></i>
                    <span>Stock</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-reviews')); ?>" class="woodash-nav-link  woodash-slide-up">
                    <i class="fa-solid fa-star w-5"></i>
                    <span>Reviews</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-marketing')); ?>" class="woodash-nav-link  woodash-slide-up">
                    <i class="fa-solid fa-bullhorn w-5"></i>
                    <span>Marketing</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-reports')); ?>" class="woodash-nav-link  woodash-slide-up"">
                    <i class="fa-solid fa-file-chart-line w-5"></i>
                    <span>Reports</span>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-settings')); ?>" class="woodash-nav-link  woodash-slide-up">
                    <i class="fa-solid fa-gear w-5"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="woodash-main flex-1 p-6 md:p-8">
            <div class="max-w-7xl mx-auto">
                <!-- Enhanced Header with Better Mobile Support -->
                <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 woodash-fade-in">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl md:text-3xl font-bold woodash-gradient-text truncate">
                            Dashboard 
                            <span class="inline-block animate-bounce">👋</span>
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400 mt-1">
                            Welcome back, John! Here's what's happening with your store.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                        
                        <!-- Enhanced Search with Voice -->
                        <div class="woodash-search-container flex-1 sm:flex-initial min-w-0" role="search">
                            <input type="text" 
                                   id="woodash-global-search"
                                   class="woodash-search-input w-full"
                                   placeholder="Search orders, products, customers..." 
                                   aria-label="Global search"
                                   autocomplete="off"
                                   style="border:none;"
                            >
                            <button id="woodash-clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-600 focus:outline-none hidden text-base transition-colors duration-200">
                                <i class="fa-solid fa-times"></i>
                            </button>
                            
                            <!-- Search Results Dropdown -->
                            <div class="woodash-search-results" id="woodash-search-results">
                                <!-- Results will be populated here -->
                            </div>
                            <!-- Add search button inside the container -->
                            <button class="woodash-search-button">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                        <div class="relative">
                            <button 
                                id="notifications-btn" 
                                class="woodash-btn woodash-btn-primary woodash-glow relative bg-green-500 active" 
                                aria-label="Notifications"
                                aria-expanded="true"
                                aria-haspopup="true"
                                style="color: white;"
                            >
                                <i class="fa-solid fa-bell" aria-hidden="true"></i>
                                <span id="notification-badge" class="woodash-notification-badge" aria-live="polite" style="display: inline-flex;">3</span>
                            </button>
                            
                            <!-- Enhanced Notifications Dropdown -->
                            <div id="notifications-dropdown" class="woodash-notifications-dropdown" role="menu">
                                <div class="woodash-notifications-header">
                                    <h3 class="woodash-notifications-title">Notifications</h3>
                                    <p class="woodash-notifications-subtitle">Stay updated with your store activity</p>
                                </div>
                                
                                <div class="woodash-notifications-list" id="notifications-list">
                                    <!-- Dynamic notifications will be loaded here -->
                                    <div class="p-8 text-center text-gray-500">
                                        <i class="fa-solid fa-bell-slash text-2xl mb-2 block"></i>
                                        <p>No notifications yet</p>
                                    </div>
                                </div>
                                
                                <div class="woodash-notifications-footer">
                                    <button id="mark-all-read" class="woodash-notifications-mark-read">
                                        <i class="fa-solid fa-check mr-1"></i>
                                        Mark All Read
                                    </button>
                                    <button id="clear-notifications" class="woodash-notifications-clear">
                                        <i class="fa-solid fa-trash mr-1"></i>
                                        Clear All
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Date Range & Export -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div class="flex gap-2">
                        <select id="date-filter" class="woodash-btn woodash-btn-secondary  woodash-fade-in">
                            <option value="today">Today</option>
                            <option value="last7days">Last 7 Days</option>
                            <option value="custom">Custom Date</option>
                        </select>
                        <input type="date" id="custom-date-from" class="hidden" placeholder="From">
                        <input type="date" id="custom-date-to" class="hidden" placeholder="To">
                        <button class="woodash-btn woodash-btn-secondary  woodash-fade-in" id="apply-custom-date">Apply</button>
                    </div>
                </div>

                <!-- Stat Cards - Enhanced Mobile Responsive -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6 mb-8">
                    <!-- Total Sales Card - Enhanced -->
                    <div class="woodash-metric-card  woodash-glow"role="region" aria-labelledby="sales-title">
                            <div class="flex-1 min-w-0">
                                <h3 id="sales-title" class="woodash-metric-title">
                                    <span>Total Sales</span> 
                                    <span class="woodash-badge woodash-badge-success text-xs ml-2">
                                        <span class="animate-pulse">●</span> Live
                                    </span>
                                </h3>
                                <div class="woodash-metric-value" id="total-sales" aria-live="polite">
                                    <div class="woodash-loading h-8 w-24 rounded" id="sales-loading"></div>
                                </div>
                                <div class="flex items-center gap-1 mt-2">
                                    <span class="text-sm text-green-600 flex items-center gap-1" id="sales-change">
                                        <i class="fa-solid fa-arrow-up text-xs" aria-hidden="true"></i>
                                        <span>12.5%</span>
                                    </span>
                                    <span class="text-xs text-gray-500">vs last month</span>
                                </div>
                            </div>
                    </div>


                    <!-- Total Orders Card - Enhanced -->
                    <div class="woodash-metric-card  woodash-glow" role="region" aria-labelledby="orders-title">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <h3 id="orders-title" class="woodash-metric-title">
                                    <span>Total Orders</span>
                                    <span class="woodash-badge woodash-badge-warning text-xs ml-2" id="pending-orders" aria-live="polite">Processing: 
                                        <span class="woodash-loading inline-block w-6 h-3 rounded" id="pending-loading"></span>
                                    </span>
                                </h3>
                                <div class="woodash-metric-value" id="total-orders" aria-live="polite">
                                    <div class="woodash-loading h-8 w-16 rounded" id="orders-loading"></div>
                                </div>
                                <div class="flex items-center gap-1 mt-2">
                                    <span class="text-sm text-green-600 flex items-center gap-1" id="orders-change">
                                        <i class="fa-solid fa-arrow-up text-xs" aria-hidden="true"></i>
                                        <span>8.2%</span>
                                    </span>
                                    <span class="text-xs text-gray-500">vs last month</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Average Order Value Card - Enhanced -->
                    <div class="woodash-metric-card  woodash-glow" role="region" aria-labelledby="aov-title">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <h3 id="aov-title" class="woodash-metric-title">
                                    <span>Average Order Value</span>
                                    <span class="woodash-badge woodash-badge-danger text-xs ml-2">-3.1%</span>
                                </h3>
                                <div class="woodash-metric-value" id="aov" aria-live="polite">
                                    <div class="woodash-loading h-8 w-20 rounded" id="aov-loading"></div>
                                </div>
                                <div class="flex items-center gap-1 mt-2">
                                    <span class="text-sm text-red-600 flex items-center gap-1" id="aov-change">
                                        <i class="fa-solid fa-arrow-down text-xs" aria-hidden="true"></i>
                                        <span>3.1%</span>
                                    </span>
                                    <span class="text-xs text-gray-500">vs last month</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <div class="relative h-10 overflow-hidden rounded">
                                <canvas id="mini-trend-aov" height="40" aria-label="Average order value trend chart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- New Customers Card - Enhanced -->
                    <div class="woodash-metric-card  woodash-glow" role="region" aria-labelledby="customers-title">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <h3 id="customers-title" class="woodash-metric-title flex items-center gap-2">
                                    <span>New Customers</span>
                                    <span class="woodash-badge woodash-badge-success text-xs">+15.3%</span>
                                </h3>
                                <div class="woodash-metric-value" id="new-customers" aria-live="polite">
                                    <div class="woodash-loading h-8 w-12 rounded" id="customers-loading"></div>
                                </div>
                                <div class="flex items-center gap-1 mt-2">
                                    <span class="text-sm text-green-600 flex items-center gap-1" id="customers-change">
                                        <i class="fa-solid fa-arrow-up text-xs" aria-hidden="true"></i>
                                        <span>15.3%</span>
                                    </span>
                                    <span class="text-xs text-gray-500">vs last month</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <div class="relative h-10 overflow-hidden rounded">
                                <canvas id="mini-trend-customers" height="40" aria-label="New customers trend chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Overview -->
                <div class="woodash-chart-container mb-8  woodash-glow">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <div>
                            <h2 class="text-lg font-bold woodash-gradient-text">Sales Overview</h2>
                            <p class="text-gray-500 text-sm">Track your sales performance over time</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="woodash-btn woodash-btn-primary " data-range="daily">Daily</button>
                            <button class="woodash-btn woodash-btn-secondary " data-range="weekly">Weekly</button>
                            <button class="woodash-btn woodash-btn-secondary " data-range="monthly">Monthly</button>
                        </div>
                    </div>
                    <div class="h-[400px]">
                        <canvas id="sales-chart"></canvas>
                    </div>
                </div>
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="woodash-card p-4  woodash-fade-in">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Today's Orders</p>
                                <p class="text-xl font-bold text-gray-900">12</p>
                            </div>
                            <div class="woodash-progress w-16">
                                <div class="woodash-progress-bar woodash-shimmer" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="woodash-card p-4  woodash-fade-in">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Pending Orders</p>
                                <p class="text-xl font-bold text-gray-900">5</p>
                            </div>
                            <div class="woodash-progress w-16">
                                <div class="woodash-progress-bar bg-yellow-500 woodash-shimmer" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="woodash-card p-4 woodash-fade-in">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Low Stock Items</p>
                                <p class="text-xl font-bold text-gray-900">3</p>
                            </div>
                            <div class="woodash-progress w-16">
                                <div class="woodash-progress-bar bg-red-500 woodash-shimmer" style="width: 30%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="woodash-card p-4 woodash-fade-in">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">New Customers</p>
                                <p class="text-xl font-bold text-gray-900">8</p>
                            </div>
                            <div class="woodash-progress w-16">
                                <div class="woodash-progress-bar bg-blue-500 woodash-shimmer" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Analytics Section with AI Insights -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- AI Insights Panel -->
                    <div class="lg:col-span-1">
                        <div class="woodash-chart-container woodash-glow relative overflow-hidden">
                            <!-- Animated Background Pattern -->
                            <div class="absolute inset-0 opacity-5">
                                <div class="absolute top-0 left-0 w-full h-full" style="backgroundColor: none !important;"></div>
                                <div class="absolute top-0 left-0 w-full h-full">
                                    <div class="absolute top-4 left-4 w-8 h-8 bg-purple-400 rounded-full animate-pulse"></div>
                                    <div class="absolute top-12 right-8 w-4 h-4 bg-blue-400 rounded-full animate-bounce"></div>
                                    <div class="absolute bottom-8 left-8 w-6 h-6 bg-indigo-400 rounded-full animate-pulse"></div>
                                </div>
                            </div>
                            
                            <div class="relative z-10 flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-lg font-bold flex items-center gap-3">
                                        <div class="relative">
                                            <i class="fa-solid fa-brain text-2xl bg-gradient-to-r from-purple-500 to-blue-500 bg-clip-text text-transparent"></i>
                                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full animate-ping"></div>
                                        </div>
                                        <span class="bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                                            Performance Analytics, AI Insights
                                        </span>
                                    </h3>
                                    <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                                        <span class="inline-block w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                        Intelligent business recommendations powered by AI
                                    </p>
                                </div>
                                <button class="woodash-btn-icon relative group absolute -inset-1 bg-gradient-to-r from-green-500 to-green-600 rounded-lg opacity-0" onclick="refreshAIInsights()" title="Refresh Insights">
                                    <i class="fa-solid fa-refresh group-hover:animate-spin transition-transform duration-300"></i>
        
                                </button>
                            </div>
                            <div id="ai-insights-container" class="space-y-4">
                                <!-- AI insights will be loaded here -->
                                <div class="ai-insight-item p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg border border-green-100 dark:border-green-800">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-trending-up text-green-600 dark:text-green-400"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">Revenue Prediction</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Based on current trends, you're likely to reach $15,420 this month (+12% vs last month)</p>
                                            <span class="inline-block px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs rounded mt-2">High Confidence</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ai-insight-item p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-users text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">Customer Behavior</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Your customers are most active on weekends. Consider running weekend promotions.</p>
                                            <button class="text-blue-600 dark:text-blue-400 text-xs hover:underline mt-2" onclick="showCustomerAnalytics()">View Details</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ai-insight-item p-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-lg border border-yellow-100 dark:border-yellow-800">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">Inventory Alert</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">3 products may run out of stock within 7 days based on sales velocity</p>
                                            <button class="text-yellow-600 dark:text-yellow-400 text-xs hover:underline mt-2" onclick="showInventoryForecast()">Manage Stock</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Advanced Performance Metrics -->
                    <div class="lg:col-span-2">
                        <div class="woodash-chart-container woodash-glow">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-lg font-bold woodash-gradient-text flex items-center gap-2">
                                        <i class="fa-solid fa-chart-line text-blue-500"></i>
                                        Performance Analytics
                                    </h3>
                                    <p class="text-gray-500 text-sm">Advanced store performance metrics</p>
                                </div>
                                <div class="flex gap-2">
                                    <select id="performance-timeframe" class="woodash-btn woodash-btn-secondary text-sm">
                                        <option value="7days">Last 7 Days</option>
                                        <option value="30days" selected>Last 30 Days</option>
                                        <option value="90days">Last 90 Days</option>
                                        <option value="1year">Last Year</option>
                                    </select>
                                    <button class="woodash-btn-icon" onclick="exportPerformanceReport()" title="Export Report">
                                        <i class="fa-solid fa-download"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                <div class="performance-metric text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg">
                                    <div class="flex items-center justify-center mb-2">
                                        <i class="fa-solid fa-percentage text-green-500 mr-2"></i>
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Conversion Rate</span>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">3.24%</div>
                                    <div class="flex items-center justify-center text-sm mt-1">
                                        <i class="fa-solid fa-arrow-up text-green-500 mr-1"></i>
                                        <span class="text-green-600 dark:text-green-400">+0.8%</span>
                                    </div>
                                </div>
                                
                                <div class="performance-metric text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg">
                                    <div class="flex items-center justify-center mb-2">
                                        <i class="fa-solid fa-dollar-sign text-blue-500 mr-2"></i>
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Order Value</span>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">$87.50</div>
                                    <div class="flex items-center justify-center text-sm mt-1">
                                        <i class="fa-solid fa-arrow-up text-green-500 mr-1"></i>
                                        <span class="text-green-600 dark:text-green-400">+$5.20</span>
                                    </div>
                                </div>
                                
                                <div class="performance-metric text-center p-4 bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 rounded-lg">
                                    <div class="flex items-center justify-center mb-2">
                                        <i class="fa-solid fa-user-plus text-purple-500 mr-2"></i>
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Customer LTV</span>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">$245</div>
                                    <div class="flex items-center justify-center text-sm mt-1">
                                        <i class="fa-solid fa-arrow-up text-green-500 mr-1"></i>
                                        <span class="text-green-600 dark:text-green-400">+12%</span>
                                    </div>
                                </div>
                                
                                <div class="performance-metric text-center p-4 bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-lg">
                                    <div class="flex items-center justify-center mb-2">
                                        <i class="fa-solid fa-undo text-orange-500 mr-2"></i>
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Return Rate</span>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">2.1%</div>
                                    <div class="flex items-center justify-center text-sm mt-1">
                                        <i class="fa-solid fa-arrow-down text-green-500 mr-1"></i>
                                        <span class="text-green-600 dark:text-green-400">-0.3%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Advanced Charts Container -->
                            <div class="h-64">
                                <canvas id="performance-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Customer Journey Tracking -->
                <div class="woodash-chart-container woodash-glow mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold woodash-gradient-text flex items-center gap-2">
                                <i class="fa-solid fa-route text-indigo-500"></i>
                                Customer Journey Analytics
                            </h3>
                            <p class="text-gray-500 text-sm">Track how customers navigate your store</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="woodash-btn woodash-btn-secondary" onclick="toggleJourneyView('funnel')">
                                <i class="fa-solid fa-filter mr-1"></i>
                                Funnel View
                            </button>
                            <button class="woodash-btn woodash-btn-secondary" onclick="toggleJourneyView('path')">
                                <i class="fa-solid fa-project-diagram mr-1"></i>
                                Path Analysis
                            </button>
                        </div>
                    </div>
                    
                    <!-- Journey Funnel -->
                    <div id="journey-funnel" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6 ">
                        <div class="journey-stage text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <i class="fa-solid fa-eye text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Page Views</h4>
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">2,456</div>
                            <div class="text-sm text-gray-500">100%</div>
                        </div>
                        
                        <div class="journey-stage text-center p-4 bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 rounded-lg border border-purple-100 dark:border-purple-800">
                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <i class="fa-solid fa-mouse-pointer text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Product Views</h4>
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">1,234</div>
                            <div class="text-sm text-gray-500">50.2%</div>
                        </div>
                        
                        <div class="journey-stage text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg border border-green-100 dark:border-green-800">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <i class="fa-solid fa-shopping-cart text-green-600 dark:text-green-400"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Add to Cart</h4>
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">456</div>
                            <div class="text-sm text-gray-500">18.6%</div>
                        </div>
                        
                        <div class="journey-stage text-center p-4 bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-lg border border-yellow-100 dark:border-yellow-800">
                            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <i class="fa-solid fa-credit-card text-yellow-600 dark:text-yellow-400"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Checkout</h4>
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">123</div>
                            <div class="text-sm text-gray-500">5.0%</div>
                        </div>
                        
                        <div class="journey-stage text-center p-4 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-lg border border-emerald-100 dark:border-emerald-800">
                            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <i class="fa-solid fa-check-circle text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Purchase</h4>
                            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">87</div>
                            <div class="text-sm text-gray-500">3.5%</div>
                        </div>
                    </div>
                    
                    <!-- Journey Insights -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 rounded-lg border border-red-100 dark:border-red-800">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-2">Biggest Drop-off</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">31.6% of users leave at product view stage</p>
                            <button class="text-red-600 dark:text-red-400 text-xs hover:underline mt-2" onclick="analyzeDropoff('product-view')">Analyze</button>
                        </div>
                        
                        <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg border border-green-100 dark:border-green-800">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-2">Best Converter</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Mobile users have 12% higher conversion rate</p>
                            <button class="text-green-600 dark:text-green-400 text-xs hover:underline mt-2" onclick="viewConverterDetails()">View Details</button>
                        </div>
                        
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-2">Average Journey Time</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Users spend 8.5 minutes before purchasing</p>
                            <button class="text-blue-600 dark:text-blue-400 text-xs hover:underline mt-2" onclick="viewTimeAnalysis()">Time Analysis</button>
                        </div>
                    </div>
                </div>
                
                <!-- Real-time Activity Feed -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Activity Feed -->
                    <div class="lg:col-span-2">
                        <div class="woodash-chart-container woodash-glow">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-lg font-bold woodash-gradient-text flex items-center gap-2">
                                        <i class="fa-solid fa-activity text-red-500"></i>
                                        Real-time Activity
                                        <span class="inline-block w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                    </h3>
                                    <p class="text-gray-500 text-sm">Live store activity and events</p>
                                </div>
                                <div class="flex gap-2">
                                    <button class="woodash-btn woodash-btn-secondary text-sm" onclick="toggleActivityFilter('all')">
                                        All Activity
                                    </button>
                                    <button class="woodash-btn-icon" onclick="pauseActivityFeed()" title="Pause Feed" id="pause-activity">
                                        <i class="fa-solid fa-pause"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div id="activity-feed" class="space-y-4 max-h-96 overflow-y-auto woodash-scrollbar">
                                <!-- Activity items will be dynamically added here -->
                                <div class="activity-item flex items-start gap-3 p-3 rounded-lg hover:bg-green-50 dark:hover:bg-green-800 transition-colors">
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                        <i class="fa-solid fa-shopping-cart text-green-600 dark:text-green-400 text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">New order #1234</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Customer: John Doe • $87.50</p>
                                        <p class="text-xs text-gray-400 mt-1">2 minutes ago</p>
                                    </div>
                                    <span class="text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded">Order</span>
                                </div>
                                
                                <div class="activity-item flex items-start gap-3 p-3 rounded-lg hover:bg-green-50 dark:hover:bg-green-800 transition-colors">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <i class="fa-solid fa-user-plus text-blue-600 dark:text-blue-400 text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">New customer registration</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">jane.smith@example.com</p>
                                        <p class="text-xs text-gray-400 mt-1">5 minutes ago</p>
                                    </div>
                                    <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded">User</span>
                                </div>
                                
                                <div class="activity-item flex items-start gap-3 p-3 rounded-lg hover:bg-green-50 dark:hover:bg-green-800 transition-colors">
                                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                        <i class="fa-solid fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Low stock alert</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Product: "Premium Headphones" • 3 items left</p>
                                        <p class="text-xs text-gray-400 mt-1">8 minutes ago</p>
                                    </div>
                                    <span class="text-xs bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 px-2 py-1 rounded">Stock</span>
                                </div>
                                
                                <div class="activity-item flex items-start gap-3 p-3 rounded-lg hover:bg-green-50 dark:hover:bg-green-800 transition-colors">
                                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                                        <i class="fa-solid fa-star text-purple-600 dark:text-purple-400 text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">New product review</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">5 stars for "Wireless Mouse" by Mike J.</p>
                                        <p class="text-xs text-gray-400 mt-1">12 minutes ago</p>
                                    </div>
                                    <span class="text-xs bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 px-2 py-1 rounded">Review</span>
                                </div>
                                
                                <div class="activity-item flex items-start gap-3 p-3 rounded-lg hover:bg-green-50 dark:hover:bg-green-800 transition-colors">
                                    <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                        <i class="fa-solid fa-chart-line text-indigo-600 dark:text-indigo-400 text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Sales milestone reached</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Monthly sales target exceeded by 12%</p>
                                        <p class="text-xs text-gray-400 mt-1">15 minutes ago</p>
                                    </div>
                                    <span class="text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-2 py-1 rounded">Milestone</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions & System Status -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Quick Actions -->
                        <div class="woodash-chart-container woodash-glow">
                            <div class="mb-4">
                                <h3 class="text-lg font-bold woodash-gradient-text flex items-center gap-2">
                                    <i class="fa-solid fa-bolt text-yellow-500"></i>
                                    Quick Actions
                                </h3>
                                <p class="text-gray-500 text-sm">One-click operations</p>
                            </div>
                            
                            <div class="space-y-3">
                                <button id="quick-add-btn" class="w-full woodash-btn woodash-btn-primary text-left flex items-center gap-3">
                                    <i class="fa-solid fa-plus-circle"></i>
                                    <span>Add Product</span>
                                </button>
                                
                                <button class="w-full woodash-btn woodash-btn-secondary text-left flex items-center gap-3" onclick="processOrders()">
                                    <i class="fa-solid fa-cog"></i>
                                    <span>Process Orders</span>
                                </button>
                                
                                <button class="w-full woodash-btn woodash-btn-secondary text-left flex items-center gap-3" onclick="generateReport()">
                                    <i class="fa-solid fa-file-alt"></i>
                                    <span>Generate Report</span>
                                </button>
                                
                                <button class="w-full woodash-btn woodash-btn-secondary text-left flex items-center gap-3" onclick="backupData()">
                                    <i class="fa-solid fa-cloud-upload-alt"></i>
                                    <span>Backup Data</span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- System Status -->
                        <div class="woodash-chart-container woodash-glow">
                            <div class="mb-4">
                                <h3 class="text-lg font-bold woodash-gradient-text flex items-center gap-2">
                                    <i class="fa-solid fa-server text-green-500"></i>
                                    System Status
                                </h3>
                                <p class="text-gray-500 text-sm">All systems operational</p>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium">Database</span>
                                    </div>
                                    <span class="text-xs text-green-600 dark:text-green-400">Online</span>
                                </div>
                                
                                <div class="flex items-center justify-between p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium">Payment Gateway</span>
                                    </div>
                                    <span class="text-xs text-green-600 dark:text-green-400">Active</span>
                                </div>
                                
                                <div class="flex items-center justify-between p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium">Email Service</span>
                                    </div>
                                    <span class="text-xs text-green-600 dark:text-green-400">Running</span>
                                </div>
                                
                                <div class="flex items-center justify-between p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium">Cache</span>
                                    </div>
                                    <span class="text-xs text-yellow-600 dark:text-yellow-400">Optimizing</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Products & Customers -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Top Products -->
                    <div class="woodash-chart-container woodash-glow">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Top Products</h2>
                                <p class="text-gray-500 text-sm">Best performing products</p>
                            </div>
                            <button class="woodash-btn woodash-btn-secondary">
                                <i class="fa-solid fa-file-csv"></i>
                                <span>Export</span>
                            </button>
                        </div>
                        <div class="overflow-x-auto woodash-scrollbar">
                            <table class="woodash-table w-full">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Sales</th>
                                    <th>Revenue</th>
                                        <th>Trend</th>
                                </tr>
                            </thead>
                            <tbody id="top-products">
                                    <!-- Products will be loaded here -->
                            </tbody>
                        </table>
                        </div>
                    </div>

                    <!-- Top Customers -->
                    <div class="woodash-chart-container woodash-glow">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Top Customers</h2>
                                <p class="text-gray-500 text-sm">Most valuable customers</p>
                            </div>
                            <button class="woodash-btn woodash-btn-secondary">
                                <i class="fa-solid fa-file-csv"></i>
                                <span>Export</span>
                            </button>
                        </div>
                        <div class="overflow-x-auto woodash-scrollbar">
                            <table class="woodash-table w-full">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Orders</th>
                                        <th>Total Spent</th>
                                        <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="top-customers">
                                    <!-- Customers will be loaded here -->
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

                <!-- Additional Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Recent Activity -->
                    <div class="woodash-chart-container woodash-glow">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Recent Activity</h2>
                                <p class="text-gray-500 text-sm">Latest store activities and updates</p>
                            </div>
                            <button class="woodash-btn woodash-btn-secondary ">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                        </div>
                        <div class="space-y-4 woodash-scrollbar" style="max-height: 400px;">
                            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 woodash-fade-in">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 woodash-float">
                                    <i class="fa-solid fa-shopping-cart"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-gray-900">New Order #1234</p>
                                        <span class="text-sm text-gray-500">2m ago</span>
                                    </div>
                                    <p class="text-sm text-gray-500">John Doe placed an order worth $299.99</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 woodash-fade-in">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 woodash-float">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-gray-900">New Customer</p>
                                        <span class="text-sm text-gray-500">15m ago</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Alice Smith registered a new account</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 woodash-fade-in">
                                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 woodash-float">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-gray-900">Low Stock Alert</p>
                                        <span class="text-sm text-gray-500">1h ago</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Product "Wireless Headphones" is running low on stock</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 woodash-fade-in">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 woodash-float">
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-gray-900">New Review</p>
                                        <span class="text-sm text-gray-500">2h ago</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Mark Brown left a 5-star review for "Yoga Mat Pro"</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Store Performance -->
                    <div class="woodash-chart-container  woodash-glow">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Store Performance</h2>
                                <p class="text-gray-500 text-sm">Key performance indicators</p>
                            </div>
                            <button class="woodash-btn woodash-btn-secondary ">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-medium text-gray-700">Conversion Rate</p>
                                    <span class="text-sm font-medium text-green-600">+2.4%</span>
                                </div>
                                <div class="woodash-progress">
                                    <div class="woodash-progress-bar woodash-shimmer" style="width: 65%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">65% of visitors converted to customers</p>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-medium text-gray-700">Customer Satisfaction</p>
                                    <span class="text-sm font-medium text-green-600">4.8/5</span>
                                </div>
                                <div class="woodash-progress">
                                    <div class="woodash-progress-bar bg-yellow-500 woodash-shimmer" style="width: 96%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Based on 128 customer reviews</p>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-medium text-gray-700">Return Rate</p>
                                    <span class="text-sm font-medium text-red-600">3.2%</span>
                                </div>
                                <div class="woodash-progress">
                                    <div class="woodash-progress-bar bg-red-500 woodash-shimmer" style="width: 3.2%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Lower than industry average</p>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-medium text-gray-700">Inventory Turnover</p>
                                    <span class="text-sm font-medium text-green-600">4.5x</span>
                                </div>
                                <div class="woodash-progress">
                                    <div class="woodash-progress-bar bg-blue-500 woodash-shimmer" style="width: 75%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Inventory sold and replaced 4.5 times this year</p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Thank You Message -->
                <div class="mt-8" style="padding-bottom: 2em;">
                    <div class="woodash-card p-6 text-center">
                        <h2 class="text-xl font-bold woodash-gradient-text mb-4">Welcome to WooDash Pro!</h2>
                        <div class="prose prose-sm max-w-none text-gray-600">
                            <p class="mb-4">
                                Thank you for joining our dashboard. We're excited to help you manage your store better!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Footer Section -->
<footer class="bg-white border-t border-gray-100 py-8 mt-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center">
                        <i class="fa-solid fa-chart-line text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold woodash-gradient-text">WooDash Pro</h3>
                </div>
                <p class="text-gray-600 text-sm">Your all-in-one dashboard for managing your WooCommerce store. Track sales, monitor performance, and grow your business.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-400 hover:text-[#00CC61] transition-colors duration-200">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-[#00CC61] transition-colors duration-200">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-[#00CC61] transition-colors duration-200">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-[#00CC61] transition-colors duration-200">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-[#00CC61] transition-colors duration-200">Dashboard</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#00CC61] transition-colors duration-200">Products</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#00CC61] transition-colors duration-200">Orders</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#00CC61] transition-colors duration-200">Customers</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#00CC61] transition-colors duration-200">Reports</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Support</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-[#00CC61] transition-colors duration-200">Documentation</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#00CC61] transition-colors duration-200">Help Center</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#00CC61] transition-colors duration-200">API Reference</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#00CC61] transition-colors duration-200">Community</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#00CC61] transition-colors duration-200">Contact Us</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Stay Updated</h4>
                <p class="text-gray-600 text-sm mb-4">Subscribe to our newsletter for the latest updates and features.</p>
                <form class="space-y-3">
                    <div class="flex gap-2">
                        <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#00CC61] focus:border-transparent">
                        <button type="submit" class="woodash-btn woodash-btn-primary">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-100 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-600 text-sm">© 2024 WooDash Pro. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="text-gray-600 hover:text-[#00CC61] text-sm transition-colors duration-200">Privacy Policy</a>
                <a href="#" class="text-gray-600 hover:text-[#00CC61] text-sm transition-colors duration-200">Terms of Service</a>
                <a href="#" class="text-gray-600 hover:text-[#00CC61] text-sm transition-colors duration-200">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

<!-- Add mobile menu toggle button -->
<button id="woodash-menu-toggle" class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-white shadow-lg woodash-glass-effect">
    <i class="fa-solid fa-bars text-gray-600"></i>
</button>

<script>
// Make AJAX URL and nonce available for backend integration
window.woodash_ajax = {
    ajax_url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
    nonce: '<?php echo wp_create_nonce('woodash_nonce'); ?>'
};

// Enhanced dashboard with backend integration
const WoodashDashboard = {
    charts: {},
    realDataLoaded: false,
    
    init() {
        this.loadRealDashboardData();
        this.initializeCharts();
        this.setupEventListeners();
    },
    
    loadRealDashboardData() {
        // Load real dashboard statistics from backend
        const formData = new FormData();
        formData.append('action', 'woodash_get_dashboard_stats');
        formData.append('nonce', woodash_ajax.nonce);
        
        fetch(woodash_ajax.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateDashboardWithRealData(data.data);
                this.realDataLoaded = true;
            } else {
                console.log('Using fallback demo data');
                this.loadFallbackData();
            }
        })
        .catch(error => {
            console.log('Backend unavailable, using demo data');
            this.loadFallbackData();
        });
    },
    
    updateDashboardWithRealData(stats) {
        // Update metric cards with real data
        this.updateMetricCard('total-sales', stats.total_revenue || 0, 'currency');
        this.updateMetricCard('total-orders', stats.total_orders || 0, 'number');
        this.updateMetricCard('average-order', stats.total_orders > 0 ? (stats.total_revenue / stats.total_orders) : 0, 'currency');
        this.updateMetricCard('new-customers', stats.total_orders || 0, 'number'); // Approximate
        this.updateMetricCard('monthly-profit', stats.monthly_revenue || 0, 'currency');
        
        // Update order status cards
        this.updateOrderStatusCard('pending', stats.pending_orders || 0);
        this.updateOrderStatusCard('processing', stats.processing_orders || 0);
        this.updateOrderStatusCard('completed', stats.completed_orders || 0);
        this.updateOrderStatusCard('cancelled', stats.cancelled_orders || 0);
        
        // Update charts with real data
        if (stats.sales_chart_data) {
            this.updateChartsWithRealData(stats.sales_chart_data);
        }
        
        // Update recent orders
        if (stats.recent_orders) {
            this.updateRecentOrders(stats.recent_orders);
        }
        
        // Update top products
        if (stats.top_products) {
            this.updateTopProducts(stats.top_products);
        }
    },
    
    updateMetricCard(cardId, value, type) {
        const card = document.querySelector(`[data-metric="${cardId}"]`);
        if (!card) return;
        
        const valueElement = card.querySelector('.woodash-metric-value');
        if (valueElement) {
            let formattedValue;
            if (type === 'currency') {
                formattedValue = '$' + value.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            } else {
                formattedValue = value.toLocaleString();
            }
            valueElement.textContent = formattedValue;
        }
    },
    
    updateOrderStatusCard(status, count) {
        const card = document.querySelector(`[data-status="${status}"] .text-2xl`);
        if (card) {
            card.textContent = count.toLocaleString();
        }
    },
    
    updateChartsWithRealData(chartData) {
        // Update sales trend chart
        const salesChart = this.charts.salesTrend;
        if (salesChart && chartData.length > 0) {
            salesChart.data.labels = chartData.map(d => new Date(d.date).toLocaleDateString());
            salesChart.data.datasets[0].data = chartData.map(d => d.revenue);
            salesChart.update();
        }
        
        // Update orders chart
        const ordersChart = this.charts.ordersTrend;
        if (ordersChart && chartData.length > 0) {
            ordersChart.data.labels = chartData.map(d => new Date(d.date).toLocaleDateString());
            ordersChart.data.datasets[0].data = chartData.map(d => d.orders);
            ordersChart.update();
        }
    },
    
    updateRecentOrders(orders) {
        const container = document.querySelector('#recent-orders-list');
        if (!container || !orders.length) return;
        
        container.innerHTML = orders.slice(0, 5).map(order => `
            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <div class="font-medium text-sm">${order.id}</div>
                        <div class="text-xs text-gray-500">${order.customer}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-medium text-sm">${order.formatted_total || order.total}</div>
                    <div class="text-xs text-gray-500">${order.date}</div>
                </div>
            </div>
        `).join('');
    },
    
    updateTopProducts(products) {
        const container = document.querySelector('#top-products-list');
        if (!container || !products.length) return;
        
        container.innerHTML = products.slice(0, 5).map(product => `
            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="flex items-center space-x-3">
                    ${product.image ? 
                        `<img src="${product.image}" alt="${product.name}" class="w-8 h-8 rounded object-cover">` :
                        `<div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                            <i class="fas fa-box text-gray-400 text-sm"></i>
                        </div>`
                    }
                    <div>
                        <div class="font-medium text-sm">${product.name}</div>
                        <div class="text-xs text-gray-500">${product.total_sales} sold</div>
                    </div>
                </div>
                <div class="font-medium text-sm">${product.formatted_revenue}</div>
            </div>
        `).join('');
    },
    
    loadFallbackData() {
        // Keep existing demo data if backend is not available
        console.log('Using demo data for dashboard');
    },
    
    initializeCharts() {
        // Initialize charts (keep existing chart initialization code)
        this.initSalesTrendChart();
        this.initOrdersTrendChart();
    },
    
    initSalesTrendChart() {
        const ctx = document.getElementById('salesTrendChart');
        if (!ctx) return;
        
        this.charts.salesTrend = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Revenue',
                    data: [],
                    borderColor: '#00CC61',
                    backgroundColor: 'rgba(0, 204, 97, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    },
    
    initOrdersTrendChart() {
        const ctx = document.getElementById('ordersTrendChart');
        if (!ctx) return;
        
        this.charts.ordersTrend = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Orders',
                    data: [],
                    backgroundColor: 'rgba(0, 204, 97, 0.7)',
                    borderColor: '#00CC61',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    },
    
    setupEventListeners() {
        // Add refresh button functionality
        const refreshBtn = document.querySelector('#refresh-dashboard');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                this.loadRealDashboardData();
            });
        }
    }
};

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    WoodashDashboard.init();
});

// Keep existing chart functions for backward compatibility
// Enhanced Dashboard Functionality with Performance Optimizations
document.addEventListener('DOMContentLoaded', function() {
    // Performance monitoring
    const performanceMetrics = {
        startTime: performance.now(),
        marks: {},
        measures: {}
    };

    function mark(name) {
        performanceMetrics.marks[name] = performance.now();
    }

    function measure(name, startMark, endMark) {
        performanceMetrics.measures[name] = performanceMetrics.marks[endMark] - performanceMetrics.marks[startMark];
    }

    mark('init-start');

    // Dark Mode Implementation
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
    
    // Initialize dark mode
    function initDarkMode() {
        const savedTheme = localStorage.getItem('woodash-theme');
        const systemTheme = prefersDark.matches ? 'dark' : 'light';
        const currentTheme = savedTheme || systemTheme;
        
        document.documentElement.setAttribute('data-theme', currentTheme);
        if (currentTheme === 'dark') {
            document.documentElement.classList.add('dark');
        }
        updateDarkModeIcon(currentTheme);
    }
    
    function updateDarkModeIcon(theme) {
        const moonIcon = darkModeToggle?.querySelector('.fa-moon');
        const sunIcon = darkModeToggle?.querySelector('.fa-sun');
        
        if (theme === 'dark') {
            moonIcon?.classList.add('hidden');
            sunIcon?.classList.remove('hidden');
        } else {
            moonIcon?.classList.remove('hidden');
            sunIcon?.classList.add('hidden');
        }
    }
    
    darkModeToggle?.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('woodash-theme', newTheme);
        
        if (newTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        updateDarkModeIcon(newTheme);
        
        // Animate the transition
        document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
        setTimeout(() => {
            document.body.style.transition = '';
        }, 300);
    });
    
    // Listen for system theme changes
    prefersDark.addEventListener('change', (e) => {
        if (!localStorage.getItem('woodash-theme')) {
            const newTheme = e.matches ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', newTheme);
            if (newTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            updateDarkModeIcon(newTheme);
        }
    });

    // Enhanced Loading States with Skeleton Animation
    function showLoadingState() {
        const loadingElements = document.querySelectorAll('.woodash-loading');
        loadingElements.forEach(el => {
            el.style.background = 'linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%)';
            el.style.backgroundSize = '200% 100%';
        });
    }
    
    function hideLoadingState() {
        setTimeout(() => {
            const loadingElements = document.querySelectorAll('.woodash-loading');
            loadingElements.forEach(el => {
                el.style.display = 'none';
                const parent = el.parentElement;
                if (parent) {
                    parent.style.opacity = '1';
                }
            });
        }, Math.random() * 1000 + 500); // Simulate varying load times
    }

    // Enhanced Keyboard Shortcuts
    function initKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            
            // Ctrl+K for Search
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                document.getElementById('woodash-global-search')?.focus();
            }
            
            // Ctrl+D for Dark Mode
            if (e.ctrlKey && e.key === 'd') {
                e.preventDefault();
                darkModeToggle?.click();
            }
            
            // Escape to close modals
            if (e.key === 'Escape') {
                const modal = document.querySelector('#quick-add-modal:not(.hidden)');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = '';
                }
            }
        });
    }

    // Enhanced Notifications System with Backend Integration
    function initNotifications() {
        const notificationsBtn = document.getElementById('notifications-btn');
        const notificationsDropdown = document.getElementById('notifications-dropdown');
        const notificationBadge = document.getElementById('notification-badge');
        const soundIndicator = document.getElementById('notification-sound-indicator');
        const markAllReadBtn = document.getElementById('mark-all-read');
        const clearNotificationsBtn = document.getElementById('clear-notifications');
        
        // Notification cache and settings
        let notifications = [];
        let notificationSettings = {
            soundEnabled: true,
            desktopEnabled: true,
            emailDigest: true,
            categories: {
                order: true,
                alert: true,
                review: true,
                system: true,
                marketing: false
            }
        };
        
        // Backend API functions
        function fetchNotifications(options = {}) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: woodashData.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'woodash_get_notifications',
                        nonce: woodashData.nonce,
                        limit: options.limit || 50,
                        offset: options.offset || 0,
                        unread_only: options.unreadOnly || false
                    },
                    success: function(response) {
                        if (response.success) {
                            resolve(response.data);
                        } else {
                            reject(response.data || 'Failed to fetch notifications');
                        }
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        }
        
        function markNotificationRead(notificationId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: woodashData.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'woodash_mark_notification_read',
                        nonce: woodashData.nonce,
                        notification_id: notificationId
                    },
                    success: function(response) {
                        if (response.success) {
                            resolve(response.data);
                        } else {
                            reject(response.data || 'Failed to mark notification as read');
                        }
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        }
        
        function markAllNotificationsRead() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: woodashData.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'woodash_mark_all_notifications_read',
                        nonce: woodashData.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            resolve(response.data);
                        } else {
                            reject(response.data || 'Failed to mark all notifications as read');
                        }
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        }
        
        function clearAllNotifications() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: woodashData.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'woodash_clear_all_notifications',
                        nonce: woodashData.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            resolve(response.data);
                        } else {
                            reject(response.data || 'Failed to clear notifications');
                        }
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        }
        
        function saveNotificationSettings(settings) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: woodashData.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'woodash_save_notification_settings',
                        nonce: woodashData.nonce,
                        settings: settings
                    },
                    success: function(response) {
                        if (response.success) {
                            resolve(response.data);
                        } else {
                            reject(response.data || 'Failed to save settings');
                        }
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        }
        
        function fetchNotificationSettings() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: woodashData.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'woodash_get_notification_settings',
                        nonce: woodashData.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            resolve(response.data);
                        } else {
                            reject(response.data || 'Failed to fetch settings');
                        }
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        }
        
        // Enhanced notification storage with persistence - load saved notifications
        notifications = JSON.parse(localStorage.getItem('woodash_notifications')) || [
            {
                id: 1,
                type: 'order',
                priority: 'high',
                title: 'New Order Received',
                message: 'Order #WD-1234 from John Doe - $299.99',
                time: new Date(Date.now() - 2 * 60 * 1000).toISOString(), // 2 minutes ago
                unread: true,
                icon: 'fa-shopping-cart',
                color: '#10B981',
                bgColor: '#10B981',
                actions: [
                    { label: 'View Order', action: 'viewOrder', primary: true },
                    { label: 'Dismiss', action: 'dismiss', primary: false }
                ],
                sound: true
            },
            {
                id: 2,
                type: 'alert',
                priority: 'medium',
                title: 'Low Stock Alert',
                message: 'Wireless Headphones Pro - Only 3 items left',
                time: new Date(Date.now() - 15 * 60 * 1000).toISOString(), // 15 minutes ago
                unread: true,
                icon: 'fa-exclamation-triangle',
                color: '#F59E0B',
                bgColor: '#F59E0B',
                actions: [
                    { label: 'Restock', action: 'restock', primary: true },
                    { label: 'View Product', action: 'viewProduct', primary: false }
                ],
                sound: true
            },
            {
                id: 3,
                type: 'review',
                priority: 'low',
                title: 'New Customer Review',
                message: '⭐⭐⭐⭐⭐ "Amazing product quality!" - Sarah Johnson',
                time: new Date(Date.now() - 60 * 60 * 1000).toISOString(), // 1 hour ago
                unread: false,
                icon: 'fa-star',
                color: '#3B82F6',
                bgColor: '#3B82F6',
                actions: [
                    { label: 'View Review', action: 'viewReview', primary: true },
                    { label: 'Reply', action: 'reply', primary: false }
                ],
                sound: false
            },
            {
                id: 4,
                type: 'system',
                priority: 'high',
                title: 'Security Alert',
                message: 'New login from unusual location detected',
                time: new Date(Date.now() - 5 * 60 * 1000).toISOString(), // 5 minutes ago
                unread: true,
                icon: 'fa-shield-alt',
                color: '#EF4444',
                bgColor: '#EF4444',
                actions: [
                    { label: 'Secure Account', action: 'secureAccount', primary: true },
                    { label: 'View Details', action: 'viewDetails', primary: false }
                ],
                sound: true
            },
            {
                id: 5,
                type: 'marketing',
                priority: 'low',
                title: 'Campaign Performance',
                message: 'Summer Sale campaign exceeded target by 150%',
                time: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(), // 2 hours ago
                unread: false,
                icon: 'fa-chart-line',
                color: '#8B5CF6',
                bgColor: '#8B5CF6',
                actions: [
                    { label: 'View Analytics', action: 'viewAnalytics', primary: true },
                    { label: 'Share Report', action: 'shareReport', primary: false }
                ],
                sound: false
            }
        ];
        
        // Notification sounds with different tones for different priorities
        const notificationSounds = {
            high: new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmkcFj2N0vDUgCwKKHPG8t2QQAoSWrDm7LNcGAw+jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk='),
            medium: new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmkcFj2N0vDUgCwKKHPG8t2QQAoSWrDm7LNcGAw+jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk='),
            low: new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmkcFj2N0vDUgCwKKHPG8t2QQAoSWrDm7LNcGAw+jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk3jdXux2gfCz2N0/DSgC0JIGu56LNiHAk=')
        };
        
        // User preferences for notifications - load saved settings
        notificationSettings = JSON.parse(localStorage.getItem('woodash_notification_settings')) || {
            soundEnabled: true,
            desktopEnabled: true,
            emailDigest: true,
            categories: {
                order: true,
                alert: true,
                review: true,
                system: true,
                marketing: false
            }
        };
        
        // Utility functions
        function formatTimeAgo(timestamp) {
            const now = new Date();
            const time = new Date(timestamp);
            const diffInSeconds = Math.floor((now - time) / 1000);
            
            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
            if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
            if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)}d ago`;
            return time.toLocaleDateString();
        }
        
        function saveNotifications() {
            localStorage.setItem('woodash_notifications', JSON.stringify(notifications));
        }
        
        function saveSettings() {
            localStorage.setItem('woodash_notification_settings', JSON.stringify(notificationSettings));
        }
        
        // Create notification container for toast notifications
        function createNotificationContainer() {
            let container = document.getElementById('woodash-notification-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'woodash-notification-container';
                container.className = 'woodash-notification-container';
                document.body.appendChild(container);
            }
            return container;
        }
        
        // Enhanced notification display function using backend data
        async function renderNotifications() {
        try {
        console.log('Fetching notifications from database...');
        
        // Use jQuery AJAX for better compatibility
        const response = await new Promise((resolve, reject) => {
        jQuery.ajax({
        url: woodashData.ajaxurl,
        type: 'POST',
        data: {
        action: 'woodash_get_notifications',
        nonce: woodashData.nonce,
        limit: 50,
        offset: 0
        },
        success: function(data) {
        console.log('Notifications AJAX response:', data);
        resolve(data);
        },
        error: function(xhr, status, error) {
        console.error('Notifications AJAX error:', error);
        reject(error);
        }
        });
        });
        
        if (response.success) {
        notifications = response.data.notifications || [];
        const unreadCount = response.data.unread_count || 0;
        
        console.log(`Loaded ${notifications.length} notifications, ${unreadCount} unread`);
        
        // Update badge
        if (unreadCount > 0) {
        notificationBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
        notificationBadge.classList.remove('hidden');
        notificationBadge.style.display = 'inline-flex';
        } else {
        notificationBadge.classList.add('hidden');
        notificationBadge.style.display = 'none';
        }
        
        // Render notification list
        const notificationsList = document.getElementById('notifications-list');
        const filteredNotifications = notifications.filter(n => notificationSettings.categories[n.type] !== false);
        
        if (filteredNotifications.length === 0) {
        // If no notifications exist, create some test ones
        if (notifications.length === 0) {
        console.log('No notifications found, creating test notifications...');
        await createTestNotifications();
        // Retry after creating test notifications
        setTimeout(() => renderNotifications(), 1000);
        return;
        }
        
        notificationsList.innerHTML = `
        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
        <i class="fa-solid fa-bell-slash text-2xl mb-2 block"></i>
        <p>No notifications match your filters</p>
        <p class="text-xs mt-1">Adjust your notification settings to see more</p>
        </div>
        `;
        return;
        }
        
        notificationsList.innerHTML = filteredNotifications.map(notification => `
        <div class="woodash-notification-item ${notification.unread ? 'unread' : ''}" onclick="markNotificationRead(${notification.id})"
                         data-id="${notification.id}" 
                         onclick="handleNotificationClick(${notification.id})">
                        <div class="flex items-start gap-3">
                            <div class="woodash-notification-item-icon" style="background: ${notification.bgColor}">
                                <i class="fa-solid ${notification.icon}"></i>
                            </div>
                            <div class="woodash-notification-item-content">
                                <h4 class="woodash-notification-item-title">${notification.title}</h4>
                                <p class="woodash-notification-item-message">${notification.message}</p>
                                <div class="flex items-center justify-between">
                                    <span class="woodash-notification-item-time">${formatTimeAgo(notification.time)}</span>
                                    <div class="flex gap-2">
                                        ${notification.actions.map(action => `
                                            <button class="woodash-notification-btn ${action.primary ? 'woodash-notification-btn-primary' : 'woodash-notification-btn-secondary'}" 
                                                    onclick="event.stopPropagation(); handleNotificationAction('${action.action}', ${notification.id})">
                                                ${action.label}
                                            </button>
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
                
            } catch (error) {
                console.error('Failed to load notifications:', error);
                showNotification('Error', 'Failed to load notifications', 'error');
            }
        }
        
        // Show toast notification
        window.showNotification = function(title, message, type = 'success', options = {}) {
            const container = createNotificationContainer();
            const notification = document.createElement('div');
            const id = Date.now();
            
            const config = {
                duration: options.duration || 5000,
                closable: options.closable !== false,
                actions: options.actions || [],
                sound: options.sound !== false,
                priority: options.priority || 'medium',
                ...options
            };
            
            const iconMap = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            
            notification.className = `woodash-notification woodash-notification-${type}`;
            notification.innerHTML = `
                <div class="woodash-notification-header">
                    <div class="woodash-notification-icon ${type}">
                        <i class="fa-solid ${iconMap[type]}"></i>
                    </div>
                    <div class="woodash-notification-content">
                        <h4 class="woodash-notification-title">${title}</h4>
                        <p class="woodash-notification-message">${message}</p>
                        ${config.actions.length > 0 ? `
                            <div class="woodash-notification-actions">
                                ${config.actions.map(action => `
                                    <button class="woodash-notification-btn ${action.primary ? 'woodash-notification-btn-primary' : 'woodash-notification-btn-secondary'}" 
                                            onclick="handleToastAction('${action.action}', ${id})">
                                        ${action.label}
                                    </button>
                                `).join('')}
                            </div>
                        ` : ''}
                    </div>
                    ${config.closable ? `
                        <button class="woodash-notification-close" onclick="closeToastNotification(${id})">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    ` : ''}
                </div>
                ${config.duration > 0 ? `<div class="woodash-notification-progress" id="progress-${id}"></div>` : ''}
            `;
            
            notification.dataset.id = id;
            container.appendChild(notification);
            
            // Animate in
            setTimeout(() => notification.classList.add('show'), 100);
            
            // Play sound
            if (config.sound && notificationSettings.soundEnabled) {
                playNotificationSound(config.priority);
            }
            
            // Auto remove
            if (config.duration > 0) {
                const progressBar = document.getElementById(`progress-${id}`);
                let progress = 100;
                const interval = setInterval(() => {
                    progress -= (100 / (config.duration / 100));
                    if (progressBar) progressBar.style.width = progress + '%';
                    if (progress <= 0) {
                        clearInterval(interval);
                        closeToastNotification(id);
                    }
                }, 100);
            }
            
            return id;
        };
        
        // Close toast notification
        window.closeToastNotification = function(id) {
            const notification = document.querySelector(`[data-id="${id}"]`);
            if (notification) {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 400);
            }
        };
        
        // Handle toast actions
        window.handleToastAction = function(action, notificationId) {
            console.log('Toast action:', action, notificationId);
            closeToastNotification(notificationId);
        };
        
        // Play notification sound
        function playNotificationSound(priority = 'medium') {
            if (notificationSettings.soundEnabled && notificationSounds[priority]) {
                try {
                    notificationSounds[priority].volume = 0.5;
                    notificationSounds[priority].play().catch(e => console.log('Sound play failed:', e));
                    
                    // Show sound indicator
                    soundIndicator.classList.remove('hidden');
                    setTimeout(() => soundIndicator.classList.add('hidden'), 2000);
                } catch (error) {
                    console.log('Sound error:', error);
                }
            }
        }
        
        // Add new notification
        window.addNotification = function(notification) {
            const newNotification = {
                id: Date.now(),
                time: new Date().toISOString(),
                unread: true,
                sound: true,
                ...notification
            };
            
            notifications.unshift(newNotification);
            saveNotifications();
            renderNotifications();
            
            // Show toast if enabled
            if (notificationSettings.categories[newNotification.type]) {
                showNotification(newNotification.title, newNotification.message, 
                    getPriorityType(newNotification.priority), {
                        actions: newNotification.actions,
                        priority: newNotification.priority
                    });
            }
            
            // Desktop notification
            if (notificationSettings.desktopEnabled && 'Notification' in window) {
                if (Notification.permission === 'granted') {
                    new Notification(newNotification.title, {
                        body: newNotification.message,
                        icon: '/wp-content/plugins/WoodDash Pro/assets/icon.png'
                    });
                } else if (Notification.permission !== 'denied') {
                    Notification.requestPermission().then(permission => {
                        if (permission === 'granted') {
                            new Notification(newNotification.title, {
                                body: newNotification.message,
                                icon: '/wp-content/plugins/WoodDash Pro/assets/icon.png'
                            });
                        }
                    });
                }
            }
        };
        
        function getPriorityType(priority) {
            const map = { high: 'error', medium: 'warning', low: 'info' };
            return map[priority] || 'info';
        }
        
        // Handle notification click with backend integration
        window.handleNotificationClick = async function(id) {
            const notification = notifications.find(n => n.id === id);
            if (notification && notification.unread) {
                try {
                    await markNotificationRead(id);
                    notification.unread = false;
                    renderNotifications();
                } catch (error) {
                    console.error('Failed to mark notification as read:', error);
                }
            }
        };
        
        // Handle notification actions with backend integration
        window.handleNotificationAction = async function(action, id) {
            const notification = notifications.find(n => n.id === id);
            if (!notification) return;
            
            // Mark as read first
            if (notification.unread) {
                try {
                    await markNotificationRead(id);
                    notification.unread = false;
                    renderNotifications();
                } catch (error) {
                    console.error('Failed to mark notification as read:', error);
                }
            }
            
            // Handle specific actions
            switch (action) {
                case 'viewOrder':
                    if (notification.metadata && notification.metadata.order_id) {
                        showNotification('Order Details', `Opening order #${notification.metadata.order_id}...`, 'info');
                        // Redirect to WooCommerce order page
                        window.open(`${woodashData.adminUrl}post.php?post=${notification.metadata.order_id}&action=edit`, '_blank');
                    }
                    break;
                case 'processOrder':
                    showNotification('Processing Order', 'Redirecting to order processing...', 'success');
                    break;
                case 'restock':
                    if (notification.metadata && notification.metadata.product_id) {
                        showNotification('Restock Product', `Opening product #${notification.metadata.product_id} for restocking...`, 'info');
                        window.open(`${woodashData.adminUrl}post.php?post=${notification.metadata.product_id}&action=edit`, '_blank');
                    }
                    break;
                case 'viewProduct':
                    if (notification.metadata && notification.metadata.product_id) {
                        showNotification('Product Details', `Opening product #${notification.metadata.product_id}...`, 'info');
                        window.open(`${woodashData.adminUrl}post.php?post=${notification.metadata.product_id}&action=edit`, '_blank');
                    }
                    break;
                case 'viewReview':
                    if (notification.metadata && notification.metadata.comment_id) {
                        showNotification('Review Details', `Opening review #${notification.metadata.comment_id}...`, 'info');
                        window.open(`${woodashData.adminUrl}comment.php?action=editcomment&c=${notification.metadata.comment_id}`, '_blank');
                    }
                    break;
                case 'replyReview':
                    showNotification('Reply to Review', 'Opening review reply form...', 'info');
                    break;
                case 'secureAccount':
                    showNotification('Security Settings', 'Opening security settings...', 'warning', { duration: 3000 });
                    window.open(`${woodashData.adminUrl}profile.php`, '_blank');
                    break;
                case 'reviewSecurity':
                    showNotification('Security Review', 'Opening security log...', 'info');
                    break;
                case 'reviewActivity':
                    showNotification('Activity Review', 'Opening activity log...', 'info');
                    break;
                case 'blockIP':
                    if (notification.metadata && notification.metadata.ip) {
                        showNotification('IP Blocked', `IP ${notification.metadata.ip} has been blocked`, 'success');
                    }
                    break;
                case 'viewAnalytics':
                    showNotification('Analytics', 'Opening campaign analytics...', 'info');
                    break;
                case 'shareReport':
                    showNotification('Share Report', 'Preparing report for sharing...', 'info');
                    break;
                case 'dismiss':
                    // Already handled by marking as read
                    break;
                default:
                    console.log('Unknown action:', action);
            }
        };
        
        // Event listeners with backend integration
        notificationsBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = notificationsDropdown.classList.contains('show');
            
            if (isOpen) {
                notificationsDropdown.classList.remove('show');
                notificationsBtn.setAttribute('aria-expanded', 'false');
            } else {
                notificationsDropdown.classList.add('show');
                notificationsBtn.setAttribute('aria-expanded', 'true');
                renderNotifications(); // This will fetch from backend
            }
        });
        
        // Mark all as read with backend integration
        markAllReadBtn?.addEventListener('click', async () => {
            try {
                await markAllNotificationsRead();
                await renderNotifications(); // Refresh from backend
                showNotification('Notifications', 'All notifications marked as read', 'success', { duration: 2000 });
            } catch (error) {
                console.error('Failed to mark all notifications as read:', error);
                showNotification('Error', 'Failed to mark notifications as read', 'error');
            }
        });
        
        // Clear all notifications with backend integration
        clearNotificationsBtn?.addEventListener('click', async () => {
            if (confirm('Are you sure you want to clear all notifications?')) {
                try {
                    await clearAllNotifications();
                    await renderNotifications(); // Refresh from backend
                    showNotification('Notifications', 'All notifications cleared', 'info', { duration: 2000 });
                } catch (error) {
                    console.error('Failed to clear notifications:', error);
                    showNotification('Error', 'Failed to clear notifications', 'error');
                }
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!notificationsBtn?.contains(e.target) && !notificationsDropdown?.contains(e.target)) {
                notificationsDropdown?.classList.remove('show');
                notificationsBtn?.setAttribute('aria-expanded', 'false');
            }
        });
        
        // Real-time notification simulation
        function simulateRealTimeNotifications() {
            const mockNotifications = [
                {
                    type: 'order',
                    priority: 'high',
                    title: 'Flash Sale Order',
                    message: 'New order #WD-5678 - Flash Sale item purchased!',
                    icon: 'fa-bolt',
                    color: '#F59E0B',
                    bgColor: '#F59E0B',
                    actions: [
                        { label: 'View Order', action: 'viewOrder', primary: true },
                        { label: 'Process', action: 'process', primary: false }
                    ]
                },
                {
                    type: 'alert',
                    priority: 'medium',
                    title: 'Inventory Update',
                    message: 'Smart Watch Pro restocked - 25 units added',
                    icon: 'fa-box',
                    color: '#10B981',
                    bgColor: '#10B981',
                    actions: [
                        { label: 'View Inventory', action: 'viewInventory', primary: true }
                    ]
                }
            ];
            
            // Simulate new notifications every 30 seconds
            setInterval(() => {
                if (Math.random() > 0.7) { // 30% chance
                    const notification = mockNotifications[Math.floor(Math.random() * mockNotifications.length)];
                    addNotification(notification);
                }
            }, 30000);
        }
        
        // Notification Settings Modal
        window.showNotificationSettings = function() {
            // Ensure notificationSettings is properly initialized
            if (!notificationSettings || !notificationSettings.categories) {
                notificationSettings = {
                    soundEnabled: true,
                    desktopEnabled: true,
                    emailDigest: true,
                    categories: {
                        order: true,
                        alert: true,
                        review: true,
                        system: true,
                        marketing: true
                    }
                };
            }
            
            // Helper function for safe property access
            const getChecked = (path) => {
                try {
                    const keys = path.split('.');
                    let value = notificationSettings;
                    for (const key of keys) {
                        value = value[key];
                    }
                    return value ? 'checked' : '';
                } catch (e) {
                    return '';
                }
            };
            
            const modal = document.createElement('div');
            modal.className = 'woodash-modal-overlay';
            modal.innerHTML = `
                <div class="woodash-modal woodash-modal-md">
                    <div class="woodash-modal-header">
                        <h3 class="woodash-modal-title">
                            <i class="fa-solid fa-cog mr-2"></i>
                            Notification Settings
                        </h3>
                        <button class="woodash-modal-close" onclick="closeModal(this)">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <div class="woodash-modal-body">
                        <div class="space-y-6">
                            <!-- General Settings -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">General Settings</h4>
                                <div class="space-y-4">
                                    <label class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">Sound Notifications</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Play sound when new notifications arrive</div>
                                        </div>
                                        <input type="checkbox" id="sound-toggle" class="toggle-switch" ` + getChecked('soundEnabled') + `>
                                    </label>
                                    
                                    <label class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">Desktop Notifications</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Show browser notifications</div>
                                        </div>
                                        <input type="checkbox" id="desktop-toggle" class="toggle-switch" ` + getChecked('desktopEnabled') + `>
                                    </label>
                                    
                                    <label class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">Email Digest</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Receive daily email summary</div>
                                        </div>
                                        <input type="checkbox" id="email-toggle" class="toggle-switch" ` + getChecked('emailDigest') + `>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Category Settings -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Notification Categories</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                                <i class="fa-solid fa-shopping-cart text-green-600 dark:text-green-400"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">Orders</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">New orders and order updates</div>
                                            </div>
                                        </div>
                                        <input type="checkbox" id="order-toggle" class="toggle-switch" ` + getChecked('categories.order') + `>
                                    </label>
                                    
                                    <label class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                                <i class="fa-solid fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">Alerts</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">Stock alerts and system warnings</div>
                                            </div>
                                        </div>
                                        <input type="checkbox" id="alert-toggle" class="toggle-switch" ` + getChecked('categories.alert') + `>
                                    </label>
                                    
                                    <label class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                                <i class="fa-solid fa-star text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">Reviews</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">Customer reviews and ratings</div>
                                            </div>
                                        </div>
                                        <input type="checkbox" id="review-toggle" class="toggle-switch" ` + getChecked('categories.review') + `>
                                    </label>
                                    
                                    <label class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                                <i class="fa-solid fa-shield-alt text-red-600 dark:text-red-400"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">System</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">Security and system notifications</div>
                                            </div>
                                        </div>
                                        <input type="checkbox" id="system-toggle" class="toggle-switch" ` + getChecked('categories.system') + `>
                                    </label>
                                    
                                    <label class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                                <i class="fa-solid fa-chart-line text-purple-600 dark:text-purple-400"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">Marketing</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">Campaign updates and promotions</div>
                                            </div>
                                        </div>
                                        <input type="checkbox" id="marketing-toggle" class="toggle-switch" ` + getChecked('categories.marketing') + `>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Test Notifications -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Test Notifications</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm" 
                                            onclick="testNotification('success')">
                                        <i class="fa-solid fa-check mr-2"></i>Success
                                    </button>
                                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm" 
                                            onclick="testNotification('error')">
                                        <i class="fa-solid fa-times mr-2"></i>Error
                                    </button>
                                    <button class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm" 
                                            onclick="testNotification('warning')">
                                        <i class="fa-solid fa-warning mr-2"></i>Warning
                                    </button>
                                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm" 
                                            onclick="testNotification('info')">
                                        <i class="fa-solid fa-info mr-2"></i>Info
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="woodash-modal-footer">
                        <button class="woodash-btn-secondary" onclick="closeModal(this)">Cancel</button>
                        <button class="woodash-btn-primary" onclick="saveNotificationSettings()">
                            <i class="fa-solid fa-save mr-2"></i>Save Settings
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            setTimeout(() => modal.classList.add('show'), 100);
        };
        
        // Save notification settings with backend integration
        window.saveNotificationSettings = async function() {
            const settings = {
                soundEnabled: document.getElementById('sound-toggle').checked,
                desktopEnabled: document.getElementById('desktop-toggle').checked,
                emailDigest: document.getElementById('email-toggle').checked,
                categories: {
                    order: document.getElementById('order-toggle').checked,
                    alert: document.getElementById('alert-toggle').checked,
                    review: document.getElementById('review-toggle').checked,
                    system: document.getElementById('system-toggle').checked,
                    marketing: document.getElementById('marketing-toggle').checked
                }
            };
            
            try {
                await saveNotificationSettings(settings);
                notificationSettings = settings;
                await renderNotifications(); // Refresh with new settings
                showNotification('Settings Saved', 'Notification preferences updated successfully', 'success', { duration: 3000 });
                closeModal(document.querySelector('.woodash-modal-overlay'));
            } catch (error) {
                console.error('Failed to save notification settings:', error);
                showNotification('Error', 'Failed to save notification settings', 'error');
            }
        };
        
        // Test notification function
        window.testNotification = function(type) {
            const messages = {
                success: { title: 'Success Test', message: 'This is a test success notification' },
                error: { title: 'Error Test', message: 'This is a test error notification' },
                warning: { title: 'Warning Test', message: 'This is a test warning notification' },
                info: { title: 'Info Test', message: 'This is a test info notification' }
            };
            
            const msg = messages[type];
            showNotification(msg.title, msg.message, type, {
                actions: [
                    { label: 'Action', action: 'test', primary: true },
                    { label: 'Dismiss', action: 'dismiss', primary: false }
                ]
            });
        };
        
        // Initialize notification system with backend integration
        async function initializeNotificationSystem() {
            try {
                // Load notification settings from backend
                const settings = await fetchNotificationSettings();
                notificationSettings = settings;
                
                // Load initial notifications
                await renderNotifications();
                
                // Request desktop notification permission
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }
                
                // Start real-time polling for new notifications
                startNotificationPolling();
                
            } catch (error) {
                console.error('Failed to initialize notification system:', error);
                // Fallback to local storage if backend fails
                const localSettings = JSON.parse(localStorage.getItem('woodash_notification_settings'));
                if (localSettings) {
                    notificationSettings = localSettings;
                }
            }
        }
        
        // Real-time notification polling
        function startNotificationPolling() {
            setInterval(async () => {
                try {
                    const response = await fetchNotifications({ unreadOnly: true });
                    const newNotifications = response.notifications || [];
                    
                    // Check for new notifications
                    newNotifications.forEach(notification => {
                        const exists = notifications.find(n => n.id === notification.id);
                        if (!exists && notificationSettings.categories[notification.type]) {
                            // Show toast for new notification
                            showNotification(notification.title, notification.message, 
                                getPriorityType(notification.priority), {
                                    actions: notification.actions,
                                    priority: notification.priority
                                });
                            
                            // Play sound if enabled
                            if (notification.sound && notificationSettings.soundEnabled) {
                                playNotificationSound(notification.priority);
                            }
                            
                            // Show desktop notification
                            if (notificationSettings.desktopEnabled && 'Notification' in window && Notification.permission === 'granted') {
                                new Notification(notification.title, {
                                    body: notification.message,
                                    icon: '/wp-content/plugins/WoodDash Pro/assets/icon.png'
                                });
                            }
                        }
                    });
                    
                    // Update badge count
                    const unreadCount = response.unread_count || 0;
                    if (unreadCount > 0) {
                        notificationBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                        notificationBadge.classList.remove('hidden');
                    } else {
                        notificationBadge.classList.add('hidden');
                    }
                    
                } catch (error) {
                    console.error('Failed to poll for new notifications:', error);
                }
            }, 30000); // Poll every 30 seconds
        }
        
        // Initialize the notification system
        initializeNotificationSystem();
    }

    // Export Chart Functionality
    window.exportChart = function(chartType) {
        const canvas = document.getElementById(`mini-trend-${chartType}`);
        if (canvas) {
            const link = document.createElement('a');
            link.download = `${chartType}-chart-${new Date().toISOString().split('T')[0]}.png`;
            link.href = canvas.toDataURL();
            link.click();
            
            showNotification('Success', `${chartType.charAt(0).toUpperCase() + chartType.slice(1)} chart exported successfully`, 'success');
        }
    };

    // Enhanced Search with Debouncing
    function initEnhancedSearch() {
        const searchInput = document.getElementById('woodash-global-search');
        const searchResults = document.querySelector('.woodash-search-results');
        
        if (!searchInput) return;
        
        let searchTimeout;
        
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            
            if (query.length < 2) {
                searchResults?.classList.remove('active');
                return;
            }
            
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });
        
        function performSearch(query) {
            // Mock search results
            const mockResults = [
                { type: 'product', title: 'Wireless Headphones', subtitle: 'Electronics', icon: 'fa-headphones', color: 'bg-blue-500' },
                { type: 'customer', title: 'John Doe', subtitle: 'john@example.com', icon: 'fa-user', color: 'bg-green-500' },
                { type: 'order', title: 'Order #1234', subtitle: '$299.99 - Completed', icon: 'fa-shopping-cart', color: 'bg-purple-500' }
            ].filter(item => 
                item.title.toLowerCase().includes(query.toLowerCase()) ||
                item.subtitle.toLowerCase().includes(query.toLowerCase())
            );
            
            if (searchResults) {
                searchResults.innerHTML = mockResults.map(result => `
                    <div class="woodash-search-item" role="option">
                        <div class="woodash-search-item-icon ${result.color}">
                            <i class="fa-solid ${result.icon}"></i>
                        </div>
                        <div class="woodash-search-item-content">
                            <div class="woodash-search-item-title">${result.title}</div>
                            <div class="woodash-search-item-subtitle">${result.subtitle}</div>
                        </div>
                    </div>
                `).join('') || '<div class="woodash-search-empty">No results found</div>';
                
                searchResults.classList.add('active');
            }
        }
    }

    // Initialize all features
    initDarkMode();
    initKeyboardShortcuts();
    initNotifications();
    initEnhancedSearch();
    initButtonFunctionality();
    showLoadingState();
    hideLoadingState();
    
    mark('init-end');
    measure('total-init', 'init-start', 'init-end');
    
    console.log('Dashboard initialized in', performanceMetrics.measures['total-init'], 'ms');

    // Comprehensive Button Functionality
    function initButtonFunctionality() {
        
        // 1. Sales Overview Chart Range Buttons
        const rangeButtons = document.querySelectorAll('[data-range]');
        rangeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active state
                rangeButtons.forEach(b => {
                    b.classList.remove('woodash-btn-primary');
                    b.classList.add('woodash-btn-secondary');
                });
                this.classList.remove('woodash-btn-secondary');
                this.classList.add('woodash-btn-primary');
                
                const range = this.dataset.range;
                updateSalesChart(range);
                showNotification('Chart Updated', `Showing ${range} data`, 'success');
            });
        });
        
        // 2. Date Filter Functionality
        const dateFilter = document.getElementById('date-filter');
        const customDateFrom = document.getElementById('custom-date-from');
        const customDateTo = document.getElementById('custom-date-to');
        const applyCustomDate = document.getElementById('apply-custom-date');
        
        dateFilter?.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateFrom.classList.remove('hidden');
                customDateTo.classList.remove('hidden');
                applyCustomDate.classList.remove('hidden');
            } else {
                customDateFrom.classList.add('hidden');
                customDateTo.classList.add('hidden');
                applyCustomDate.classList.add('hidden');
                updateDashboardData(this.value);
            }
        });
        
        applyCustomDate?.addEventListener('click', function() {
            const fromDate = customDateFrom.value;
            const toDate = customDateTo.value;
            
            if (fromDate && toDate) {
                updateDashboardData('custom', fromDate, toDate);
                showNotification('Date Range Applied', `From ${fromDate} to ${toDate}`, 'success');
            } else {
                showNotification('Error', 'Please select both start and end dates', 'error');
            }
        });
        
        // 3. Export CSV Buttons
        const exportButtons = document.querySelectorAll('.woodash-btn:has(.fa-file-csv)');
        exportButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const section = this.closest('.woodash-chart-container').querySelector('h2').textContent;
                exportToCSV(section);
            });
        });
        
        // 4. Slideshow Controls
        initSlideshowControls();
        
        // 5. Menu Toggle for Mobile
        const menuToggle = document.getElementById('woodash-menu-toggle');
        const sidebar = document.querySelector('.woodash-sidebar');
        
        menuToggle?.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            const icon = this.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.className = 'fa-solid fa-times text-gray-600';
            } else {
                icon.className = 'fa-solid fa-bars text-gray-600';
            }
        });
        
        // 6. Ellipsis Menu Buttons
        const ellipsisButtons = document.querySelectorAll('.fa-ellipsis').map(icon => icon.closest('button'));
        ellipsisButtons.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    showContextMenu(this);
                });
            }
        });
        
        // 7. Social Media Links
        const socialLinks = document.querySelectorAll('.fab');
        socialLinks.forEach(link => {
            const btn = link.closest('a');
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const platform = this.querySelector('i').className.split(' ')[1].replace('fa-', '');
                    openSocialMedia(platform);
                });
            }
        });
        
        // 8. Search Button and Clear
        const searchButton = document.querySelector('.woodash-search-button');
        const clearSearchButton = document.getElementById('woodash-clear-search');
        const searchInput = document.getElementById('woodash-global-search');
        
        searchButton?.addEventListener('click', function() {
            const query = searchInput.value.trim();
            if (query) {
                performAdvancedSearch(query);
            }
        });
        
        searchInput?.addEventListener('input', function() {
            if (this.value.length > 0) {
                clearSearchButton.classList.remove('hidden');
            } else {
                clearSearchButton.classList.add('hidden');
            }
        });
        
        clearSearchButton?.addEventListener('click', function() {
            searchInput.value = '';
            this.classList.add('hidden');
            document.querySelector('.woodash-search-results')?.classList.remove('active');
        });
        
        // 9. Slide Navigation
        const slideDots = document.querySelectorAll('.woodash-slide-dot');
        const slideControls = document.querySelectorAll('.woodash-slide-control');
        
        slideDots.forEach(dot => {
            dot.addEventListener('click', function() {
                const slideIndex = parseInt(this.dataset.slide);
                goToSlide(slideIndex);
            });
        });
        
        slideControls.forEach(control => {
            control.addEventListener('click', function() {
                if (this.classList.contains('woodash-slide-prev')) {
                    previousSlide();
                } else {
                    nextSlide();
                }
            });
        });
        
        // 10. Footer Links
        const footerLinks = document.querySelectorAll('footer a[href="#"]');
        footerLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const linkText = this.textContent.trim();
                handleFooterLink(linkText);
            });
        });
        
        // 11. Sidebar Navigation Links
        const navLinks = document.querySelectorAll('.woodash-nav-link');
        navLinks.forEach(link => {
            if (link.getAttribute('href') === '#') {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const pageName = this.querySelector('span').textContent;
                    navigateToPage(pageName);
                });
            }
        });
        
        // 12. Logout Button
        const logoutBtn = document.querySelector('.woodash-logout-btn');
        logoutBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            handleLogout();
        });
        
        // 13. Voice Search Button
        const voiceSearchBtn = document.getElementById('voice-search-btn');
        voiceSearchBtn?.addEventListener('click', function() {
            startVoiceSearch();
        });
    }
    
    // Slideshow Functions
    function initSlideshowControls() {
        let currentSlide = 0;
        const slides = document.querySelectorAll('.woodash-slide');
        const totalSlides = slides.length;
        
        window.goToSlide = function(index) {
            slides.forEach((slide, i) => {
                slide.style.opacity = i === index ? '1' : '0';
                slide.style.zIndex = i === index ? '10' : '1';
            });
            
            document.querySelectorAll('.woodash-slide-dot').forEach((dot, i) => {
                dot.classList.toggle('bg-[#00CC61]', i === index);
                dot.classList.toggle('bg-gray-300', i !== index);
            });
            
            currentSlide = index;
        };
        
        window.nextSlide = function() {
            const nextIndex = (currentSlide + 1) % totalSlides;
            goToSlide(nextIndex);
        };
        
        window.previousSlide = function() {
            const prevIndex = currentSlide === 0 ? totalSlides - 1 : currentSlide - 1;
            goToSlide(prevIndex);
        };
        
        // Auto-advance slideshow
        setInterval(nextSlide, 5000);
    }
    
    // Chart Update Function
    function updateSalesChart(range) {
        const canvas = document.getElementById('sales-chart');
        if (canvas && window.salesChart) {
            // Enhanced mock data for different ranges with more realistic values
            const mockData = {
                daily: {
                    labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
                    data: [450, 320, 890, 1250, 980, 670],
                    backgroundColor: 'rgba(0, 204, 97, 0.2)',
                    borderColor: '#00CC61'
                },
                weekly: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    data: [4200, 5800, 5100, 6900, 8100, 9500, 7200],
                    backgroundColor: 'rgba(0, 204, 97, 0.2)',
                    borderColor: '#00CC61'
                },
                monthly: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    data: [45000, 52000, 48000, 61000, 58000, 67000, 55000, 63000, 59000, 71000, 68000, 75000],
                    backgroundColor: 'rgba(0, 204, 97, 0.2)',
                    borderColor: '#00CC61'
                }
            };
            
            const data = mockData[range];
            window.salesChart.data.labels = data.labels;
            window.salesChart.data.datasets[0].data = data.data;
            window.salesChart.data.datasets[0].backgroundColor = data.backgroundColor;
            window.salesChart.data.datasets[0].borderColor = data.borderColor;
            
            // Add smooth animation
            window.salesChart.update('active');
        }
    }
    
    // Initialize Sales Chart Range Buttons
    function initSalesChartButtons() {
        const buttons = document.querySelectorAll('[data-range]');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const range = this.getAttribute('data-range');
                
                // Update active button styling
                buttons.forEach(btn => {
                    if (btn.getAttribute('data-range') === range) {
                        btn.classList.remove('woodash-btn-secondary');
                        btn.classList.add('woodash-btn-primary');
                    } else {
                        btn.classList.remove('woodash-btn-primary');
                        btn.classList.add('woodash-btn-secondary');
                    }
                });
                
                // Update chart
                updateSalesChart(range);
            });
        });
        
        // Set initial active state (Monthly by default)
        const monthlyBtn = document.querySelector('[data-range="monthly"]');
        if (monthlyBtn) {
            monthlyBtn.classList.remove('woodash-btn-secondary');
            monthlyBtn.classList.add('woodash-btn-primary');
        }
    }
    
    // Dashboard Data Update
    function updateDashboardData(period, fromDate = null, toDate = null) {
        // Show loading states
        document.querySelectorAll('.woodash-loading').forEach(el => {
            el.style.display = 'block';
        });
        
        // Simulate API call
        setTimeout(() => {
            // Mock updated data
            const mockData = {
                today: { sales: 2450, orders: 12, aov: 204, customers: 8 },
                last7days: { sales: 18420, orders: 89, aov: 207, customers: 34 },
                custom: { sales: 5670, orders: 23, aov: 246, customers: 12 }
            };
            
            const data = mockData[period] || mockData.custom;
            
            // Update metrics
            document.getElementById('total-sales').textContent = '$' + data.sales.toLocaleString();
            document.getElementById('total-orders').textContent = data.orders.toLocaleString();
            document.getElementById('aov').textContent = '$' + data.aov.toLocaleString();
            document.getElementById('new-customers').textContent = data.customers.toLocaleString();
            
            // Hide loading states
            document.querySelectorAll('.woodash-loading').forEach(el => {
                el.style.display = 'none';
            });
            
        }, 1000);
    }
    
    // Export to CSV Function
    function exportToCSV(sectionName) {
        const csvData = [
            ['Date', 'Product', 'Sales', 'Revenue'],
            ['2024-08-18', 'Wireless Headphones', '15', '$1,485'],
            ['2024-08-18', 'Smartphone Case', '8', '$159'],
            ['2024-08-18', 'Bluetooth Speaker', '12', '$959'],
            ['2024-08-18', 'Fitness Tracker', '6', '$899']
        ];
        
        const csvContent = csvData.map(row => row.join(',')).join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `${sectionName.toLowerCase().replace(/\s+/g, '-')}-${new Date().toISOString().split('T')[0]}.csv`;
        link.click();
        window.URL.revokeObjectURL(url);
        
        showNotification('Export Complete', `${sectionName} data exported successfully`, 'success');
    }
    
    // Context Menu Function
    function showContextMenu(button) {
        const contextMenu = document.createElement('div');
        contextMenu.className = 'woodash-context-menu';
        contextMenu.innerHTML = `
            <div class="woodash-dropdown-item" onclick="refreshSection()">
                <i class="fa-solid fa-refresh text-blue-500"></i>
                <span>Refresh</span>
            </div>
            <div class="woodash-dropdown-item" onclick="exportSection()">
                <i class="fa-solid fa-download text-green-500"></i>
                <span>Export</span>
            </div>
            <div class="woodash-dropdown-item" onclick="configureSection()">
                <i class="fa-solid fa-cog text-gray-500"></i>
                <span>Configure</span>
            </div>
        `;
        
        button.parentElement.style.position = 'relative';
        button.parentElement.appendChild(contextMenu);
        
        // Remove menu when clicking outside
        setTimeout(() => {
            document.addEventListener('click', function removeMenu(e) {
                if (!contextMenu.contains(e.target)) {
                    contextMenu.remove();
                    document.removeEventListener('click', removeMenu);
                }
            });
        }, 100);
    }
    
    // Social Media Functions
    function openSocialMedia(platform) {
        const urls = {
            'facebook-f': 'https://facebook.com',
            'twitter': 'https://twitter.com',
            'linkedin-in': 'https://linkedin.com',
            'instagram': 'https://instagram.com'
        };
        
        if (urls[platform]) {
            window.open(urls[platform], '_blank');
        }
    }
    
    // Advanced Search Function
    function performAdvancedSearch(query) {
        showNotification('Searching...', `Looking for "${query}"`, 'info');
        
        // Simulate search
        setTimeout(() => {
            showNotification('Search Complete', `Found results for "${query}"`, 'success');
        }, 1000);
    }
    
    // Navigation Function
    function navigateToPage(pageName) {
        // Update active state
        document.querySelectorAll('.woodash-nav-link').forEach(link => {
            link.classList.remove('active');
        });
        
        event.target.closest('.woodash-nav-link').classList.add('active');
        showNotification('Navigation', `Navigating to ${pageName}`, 'info');
    }
    
    // Logout Function
    function handleLogout() {
        if (confirm('Are you sure you want to logout?')) {
            showNotification('Logging out...', 'Please wait', 'info');
            setTimeout(() => {
                window.location.href = '/wp-admin/';
            }, 1500);
        }
    }
    
    // Voice Search Function
    function startVoiceSearch() {
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';
            
            recognition.onstart = function() {
                document.getElementById('voice-search-btn').innerHTML = '<i class="fa-solid fa-microphone-slash text-red-500"></i>';
                showNotification('Voice Search', 'Listening...', 'info');
            };
            
            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                document.getElementById('woodash-global-search').value = transcript;
                performAdvancedSearch(transcript);
            };
            
            recognition.onend = function() {
                document.getElementById('voice-search-btn').innerHTML = '<i class="fa-solid fa-microphone"></i>';
            };
            
            recognition.onerror = function(event) {
                showNotification('Voice Search Error', 'Could not recognize speech', 'error');
                document.getElementById('voice-search-btn').innerHTML = '<i class="fa-solid fa-microphone"></i>';
            };
            
            recognition.start();
        } else {
            showNotification('Not Supported', 'Voice search is not supported in this browser', 'error');
        }
    }
    
    // Footer Link Handler
    function handleFooterLink(linkText) {
        const pages = {
            'Privacy Policy': 'Displaying Privacy Policy',
            'Terms of Service': 'Displaying Terms of Service',
            'Cookie Policy': 'Displaying Cookie Policy',
            'Help Center': 'Opening Help Center',
            'Documentation': 'Opening Documentation',
            'API Reference': 'Opening API Documentation'
        };
        
        if (pages[linkText]) {
            showNotification('Page Load', pages[linkText], 'info');
        }
    }
    
    // Helper Functions for Context Menu
    window.refreshSection = function() {
        showNotification('Refreshing...', 'Updating section data', 'info');
        setTimeout(() => {
            showNotification('Refreshed', 'Section data updated', 'success');
        }, 1000);
    };
    
    window.exportSection = function() {
        exportToCSV('Current Section');
    };
    
    window.configureSection = function() {
        showNotification('Configuration', 'Opening section settings', 'info');
    };
});

// Optimize asset loading
function loadAssets() {
    const assets = [
        // Assets are enqueued via WordPress; avoid dynamic reloading here to prevent duplicates
    ];

    return Promise.all(assets.map(asset => {
        return new Promise((resolve, reject) => {
            if (asset.type === 'style') {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = asset.url;
                link.onload = resolve;
                link.onerror = reject;
                document.head.appendChild(link);
            } else {
                const script = document.createElement('script');
                script.src = asset.url;
                script.async = true;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            }
        });
    }));
}

// Virtual scrolling implementation
class VirtualScroller {
    constructor(container, items, itemHeight, buffer = 5) {
        this.container = container;
        this.items = items;
        this.itemHeight = itemHeight;
        this.buffer = buffer;
        this.visibleItems = Math.ceil(container.clientHeight / itemHeight) + buffer * 2;
        this.startIndex = 0;
        this.endIndex = this.visibleItems;
        this.scrollTop = 0;

        this.init();
    }

    init() {
        this.container.style.position = 'relative';
        this.container.style.overflow = 'auto';
        this.container.style.height = `${this.items.length * this.itemHeight}px`;

        this.render();
        this.container.addEventListener('scroll', this.onScroll.bind(this));
    }

    onScroll() {
        const newScrollTop = this.container.scrollTop;
        const newStartIndex = Math.floor(newScrollTop / this.itemHeight) - this.buffer;
        const newEndIndex = newStartIndex + this.visibleItems;

        if (newStartIndex !== this.startIndex || newEndIndex !== this.endIndex) {
            this.startIndex = Math.max(0, newStartIndex);
            this.endIndex = Math.min(this.items.length, newEndIndex);
            this.render();
        }
    }

    render() {
        const fragment = document.createDocumentFragment();
        const startY = this.startIndex * this.itemHeight;

        for (let i = this.startIndex; i < this.endIndex; i++) {
            const item = this.items[i];
            const element = document.createElement('div');
            element.style.position = 'absolute';
            element.style.top = `${startY + (i - this.startIndex) * this.itemHeight}px`;
            element.style.width = '100%';
            element.style.height = `${this.itemHeight}px`;
            element.innerHTML = item;
            fragment.appendChild(element);
        }

        this.container.innerHTML = '';
        this.container.appendChild(fragment);
    }
}

// Optimize data fetching with caching
const dataCache = new Map();
const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes

async function fetchData(endpoint, forceRefresh = false) {
    const cached = dataCache.get(endpoint);
    if (!forceRefresh && cached && Date.now() - cached.timestamp < CACHE_DURATION) {
        return cached.data;
    }

    try {
        const response = await fetch(endpoint);
        const data = await response.json();
        dataCache.set(endpoint, {
            data,
            timestamp: Date.now()
        });
        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
        return null;
    }
}

// Optimize chart rendering
function createOptimizedChart(ctx, config) {
    const chart = new Chart(ctx, {
        ...config,
        options: {
            ...config.options,
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

// Optimize event handling with passive listeners
function addPassiveEventListener(element, event, handler) {
    element.addEventListener(event, handler, { passive: true });
}

// Optimize DOM updates with requestAnimationFrame
function batchDOMUpdates(updates) {
    requestAnimationFrame(() => {
        updates.forEach(update => update());
    });
}

// Optimize memory usage
function cleanupResources() {
    // Clear data cache
    dataCache.clear();
    
    // Remove event listeners
    document.removeEventListener('scroll', handleScroll);
    document.removeEventListener('resize', handleResize);
    
    // Clear intervals
    clearInterval(slideInterval);
    
    // Cancel animation frames
    if (rafId) {
        cancelAnimationFrame(rafId);
    }
    // Clear slideshow interval if present
    if (window.__woodashSlideInterval) {
        clearInterval(window.__woodashSlideInterval);
        window.__woodashSlideInterval = null;
    }
}

// Initialize with performance monitoring
async function initializeDashboard() {
    mark('assets-start');
    await loadAssets();
    mark('assets-end');
    measure('assets-loading', 'assets-start', 'assets-end');

    mark('charts-start');
    initCharts();
    mark('charts-end');
    measure('charts-initialization', 'charts-start', 'charts-end');

    mark('ui-start');
    initializeUI();
    mark('ui-end');
    measure('ui-initialization', 'ui-start', 'ui-end');

    // Log performance metrics
    console.log('Performance Metrics:', performanceMetrics);
}

// Initialize UI components
function initializeUI() {
    // Initialize virtual scrollers for long lists
    const activityList = document.querySelector('.woodash-scrollbar');
    if (activityList) {
        const items = Array.from(activityList.children).map(item => item.outerHTML);
        new VirtualScroller(activityList, items, 80);
    }

    // Add passive event listeners
    const scrollableElements = document.querySelectorAll('.woodash-scrollbar');
    scrollableElements.forEach(element => {
        addPassiveEventListener(element, 'scroll', () => {
            requestAnimationFrame(() => {
                // Handle scroll updates
            });
        });
    });

    // Optimize resize handling
    const handleResize = debounce(() => {
        requestAnimationFrame(() => {
            // Handle resize updates
        });
    }, 100);

    window.addEventListener('resize', handleResize, { passive: true });

    // Cleanup on page unload
    window.addEventListener('unload', cleanupResources);

    // Initialize slideshow
    const slideshow = document.querySelector('.woodash-slideshow');
    if (slideshow) {
        const slides = Array.from(slideshow.querySelectorAll('.woodash-slide'));
        const dots = Array.from(document.querySelectorAll('.woodash-slide-dot'));
        const prevBtn = document.querySelector('.woodash-slide-prev');
        const nextBtn = document.querySelector('.woodash-slide-next');
        let currentSlideIndex = 0;
        let slideIntervalLocal;

        function setActiveSlide(index) {
            slides.forEach((slide, i) => slide.classList.toggle('active', i === index));
            dots.forEach((dot, i) => dot.classList.toggle('active', i === index));
        }

        function goToSlide(index) {
            if (slides.length === 0) return;
            currentSlideIndex = (index + slides.length) % slides.length;
            setActiveSlide(currentSlideIndex);
        }

        function startAutoRotate() {
            stopAutoRotate();
            slideIntervalLocal = setInterval(() => goToSlide(currentSlideIndex + 1), 5000);
            window.__woodashSlideInterval = slideIntervalLocal;
        }

        function stopAutoRotate() {
            if (slideIntervalLocal) {
                clearInterval(slideIntervalLocal);
                slideIntervalLocal = null;
                window.__woodashSlideInterval = null;
            }
        }

        // Initial state
        setActiveSlide(0);
        startAutoRotate();

        // Controls
        nextBtn?.addEventListener('click', () => { goToSlide(currentSlideIndex + 1); startAutoRotate(); });
        prevBtn?.addEventListener('click', () => { goToSlide(currentSlideIndex - 1); startAutoRotate(); });
        dots.forEach((dot, i) => dot.addEventListener('click', () => { goToSlide(i); startAutoRotate(); }));

        // Pause on hover
        slideshow.addEventListener('mouseenter', stopAutoRotate);
        slideshow.addEventListener('mouseleave', startAutoRotate);
    }
}

// Initialize dashboard when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeDashboard);
} else {
    initializeDashboard();
}

// Performance optimized version
document.addEventListener('DOMContentLoaded', function() {
    // Debounce function for performance
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

    // Optimize menu toggle
    const menuToggle = document.getElementById('woodash-menu-toggle');
    const sidebar = document.querySelector('.woodash-sidebar');
    
    menuToggle?.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });
    
    // Optimize click outside handler
    const handleClickOutside = debounce((e) => {
        if (window.innerWidth <= 768 && 
            !sidebar.contains(e.target) && 
            !menuToggle.contains(e.target) && 
            sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }
    }, 100);

    document.addEventListener('click', handleClickOutside);

    // Optimize slideshow toggle

    const slideshowSection = document.getElementById('slideshow-section');
    let isSlideshowVisible = true;

    toggleButton?.addEventListener('click', () => {
        isSlideshowVisible = !isSlideshowVisible;
        slideshowSection.style.display = isSlideshowVisible ? 'block' : 'none';
        toggleButton.innerHTML = isSlideshowVisible ? 
            '<i class="fa-solid fa-eye"></i><span>Hide Slideshow</span>' : 
            '<i class="fa-solid fa-eye-slash"></i><span>Show Slideshow</span>';
    });

    // Optimize loading states
    const loadingElements = document.querySelectorAll('.woodash-metric-value, .woodash-table tbody');
    loadingElements.forEach(el => {
        el.classList.add('woodash-loading');
        el.style.height = '24px';
    });

    // Use requestAnimationFrame for smoother animations
    requestAnimationFrame(() => {
        setTimeout(() => {
            loadingElements.forEach(el => {
                el.classList.remove('woodash-loading');
                el.style.height = '';
            });
        }, 1000);
    });

    // Optimize notifications
    const notificationsBtn = document.getElementById('notifications-btn');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    
    notificationsBtn?.addEventListener('click', () => {
        notificationsDropdown.classList.toggle('hidden');
    });

    // Optimize card hover effects using CSS transforms
    const cards = document.querySelectorAll('.woodash-card, .woodash-metric-card, .woodash-chart-container');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            requestAnimationFrame(() => {
                card.style.transform = 'translateY(-5px)';
            });
        });
        card.addEventListener('mouseleave', () => {
            requestAnimationFrame(() => {
                card.style.transform = 'translateY(0)';
            });
        });
    });

    // Optimize mouse movement effect
    const dashboard = document.getElementById('woodash-dashboard');
    const handleMouseMove = debounce((e) => {
        const { clientX, clientY } = e;
        const { left, top, width, height } = dashboard.getBoundingClientRect();
        const x = (clientX - left) / width;
        const y = (clientY - top) / height;
        
        requestAnimationFrame(() => {
            dashboard.style.setProperty('--mouse-x', x);
            dashboard.style.setProperty('--mouse-y', y);
        });
    }, 16); // 60fps

    dashboard?.addEventListener('mousemove', handleMouseMove);

    // Initialize Enhanced Slideshow
    initEnhancedSlideshow();
});

// Enhanced Slideshow Functionality
function initEnhancedSlideshow() {
    let currentSlide = 0;
    let isAutoPlaying = true;
    let autoPlayInterval;
    const slides = document.querySelectorAll('.woodash-slide');
    const dots = document.querySelectorAll('.woodash-slide-dot');
    const progressBar = document.getElementById('slideshow-progress');
    const currentSlideSpan = document.getElementById('current-slide');
    const totalSlidesSpan = document.getElementById('total-slides');

    // Set total slides
    if (totalSlidesSpan) {
        totalSlidesSpan.textContent = slides.length;
    }

    // Show slide function
    function showSlide(index) {
        // Hide all slides
        slides.forEach((slide, i) => {
            slide.style.opacity = '0';
            slide.style.visibility = 'hidden';
            slide.style.transform = i < index ? 'translateX(-100%)' : i > index ? 'translateX(100%)' : 'translateX(0)';
        });

        // Show current slide
        if (slides[index]) {
            slides[index].style.opacity = '1';
            slides[index].style.visibility = 'visible';
            slides[index].style.transform = 'translateX(0)';
        }

        // Update dots
        dots.forEach((dot, i) => {
            // Remove all active classes and styles
            dot.classList.remove('active', 'bg-gradient-to-r', 'from-[#00CC61]', 'to-green-500', 'ring-2', 'ring-[#00CC61]/30');
            dot.classList.remove('from-blue-500', 'to-purple-500', 'ring-blue-500/30');
            dot.classList.remove('from-orange-500', 'to-red-500', 'ring-orange-500/30');
            dot.classList.remove('bg-white/60');

            if (i === index) {
                dot.classList.add('active');
                if (i === 0) {
                    dot.classList.add('bg-gradient-to-r', 'from-[#00CC61]', 'to-green-500', 'ring-2', 'ring-[#00CC61]/30');
                } else if (i === 1) {
                    dot.classList.add('bg-gradient-to-r', 'from-blue-500', 'to-purple-500', 'ring-2', 'ring-blue-500/30');
                } else if (i === 2) {
                    dot.classList.add('bg-gradient-to-r', 'from-orange-500', 'to-red-500', 'ring-2', 'ring-orange-500/30');
                }
            } else {
                dot.classList.add('bg-white/60');
            }
        });

        // Update progress bar
        if (progressBar) {
            const progress = ((index + 1) / slides.length) * 100;
            progressBar.style.width = `${progress}%`;
        }

        // Update counter
        if (currentSlideSpan) {
            currentSlideSpan.textContent = index + 1;
        }

        currentSlide = index;
    }

    // Next slide
    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }

    // Previous slide
    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }

    // Auto play
    function startAutoPlay() {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(nextSlide, 5000);
    }

    function stopAutoPlay() {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
    }

    // Event listeners
    document.querySelector('.woodash-slide-next')?.addEventListener('click', () => {
        nextSlide();
        if (isAutoPlaying) {
            stopAutoPlay();
            startAutoPlay(); // Restart timer
        }
    });

    document.querySelector('.woodash-slide-prev')?.addEventListener('click', () => {
        prevSlide();
        if (isAutoPlaying) {
            stopAutoPlay();
            startAutoPlay(); // Restart timer
        }
    });

    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
            if (isAutoPlaying) {
                stopAutoPlay();
                startAutoPlay(); // Restart timer
            }
        });
    });

    // Auto-play toggle
    document.getElementById('slideshow-autoplay-toggle')?.addEventListener('click', function() {
        isAutoPlaying = !isAutoPlaying;
        const icon = this.querySelector('i');
        const text = this.querySelector('span');

        if (isAutoPlaying) {
            startAutoPlay();
            icon.className = 'fa-solid fa-pause text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300';
            text.textContent = 'Auto-play';
            this.querySelector('.w-2').classList.add('animate-pulse');
        } else {
            stopAutoPlay();
            icon.className = 'fa-solid fa-play text-xs opacity-100 transition-opacity duration-300';
            text.textContent = 'Paused';
            this.querySelector('.w-2').classList.remove('animate-pulse');
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            prevSlide();
            if (isAutoPlaying) {
                stopAutoPlay();
                startAutoPlay();
            }
        } else if (e.key === 'ArrowRight') {
            nextSlide();
            if (isAutoPlaying) {
                stopAutoPlay();
                startAutoPlay();
            }
        }
    });

    // Fullscreen toggle
    document.getElementById('slideshow-fullscreen')?.addEventListener('click', function() {
        const slideshowSection = document.getElementById('slideshow-section');
        const icon = this.querySelector('i');

        if (!document.fullscreenElement) {
            slideshowSection.requestFullscreen().then(() => {
                icon.className = 'fa-solid fa-compress text-sm group-hover:scale-110 transition-transform duration-300';
                slideshowSection.classList.add('fixed', 'inset-0', 'z-50', 'bg-black');
            });
        } else {
            document.exitFullscreen().then(() => {
                icon.className = 'fa-solid fa-expand text-sm group-hover:scale-110 transition-transform duration-300';
                slideshowSection.classList.remove('fixed', 'inset-0', 'z-50', 'bg-black');
            });
        }
    });

    // Minimize slideshow
    document.getElementById('slideshow-minimize')?.addEventListener('click', function() {
        const slideshowSection = document.getElementById('slideshow-section');
        const icon = this.querySelector('i');

        if (slideshowSection.style.height !== '100px') {
            slideshowSection.style.height = '100px';
            slideshowSection.style.overflow = 'hidden';
            icon.className = 'fa-solid fa-plus text-sm group-hover:scale-110 transition-transform duration-300';
            stopAutoPlay();
        } else {
            slideshowSection.style.height = '';
            slideshowSection.style.overflow = '';
            icon.className = 'fa-solid fa-minus text-sm group-hover:scale-110 transition-transform duration-300';
            if (isAutoPlaying) startAutoPlay();
        }
    });

    // Initialize
    showSlide(0);
    if (isAutoPlaying) startAutoPlay();

    // Pause on hover
    const slideshowSection = document.getElementById('slideshow-section');
    slideshowSection?.addEventListener('mouseenter', () => {
        if (isAutoPlaying) stopAutoPlay();
    });

    slideshowSection?.addEventListener('mouseleave', () => {
        if (isAutoPlaying) startAutoPlay();
    });
}

// Optimize charts initialization
function initCharts() {
    // Lazy load charts when they come into view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const chartId = entry.target.id;
                if (chartId === 'sales-chart') {
                    initSalesChart();
                } else if (chartId.startsWith('mini-trend-')) {
                    initMiniChart(chartId);
                }
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    // Observe chart elements
    document.querySelectorAll('#sales-chart, [id^="mini-trend-"]').forEach(chart => {
        observer.observe(chart);
    });
}

// Separate chart initialization functions
function initSalesChart() {
    const salesCtx = document.getElementById('sales-chart')?.getContext('2d');
    if (!salesCtx) return;

    window.salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Sales',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: '#00CC61',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(0, 204, 97, 0.1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { family: "ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', sans-serif", size: 14 },
                    bodyFont: { family: "ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', sans-serif", size: 13 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.1)' }
                }
            }
        }
    });
    
    // Initialize the range buttons after chart is created
    initSalesChartButtons();
}

// Initialize mini charts
async function initMiniChart(chartId) {
    const ctx = document.getElementById(chartId)?.getContext('2d');
    if (!ctx) return;

    // Use local fallback mini data (no external calls)
    const res = { data: woodashGenerateFallbackSeries(chartId), labels: [] };
    // res can be null/undefined or unexpected; use fallback when needed

    // Determine base color per metric
    const baseColor = chartId === 'mini-trend-orders' ? '#00B357'
                     : chartId === 'mini-trend-aov' ? '#00CC61'
                     : chartId === 'mini-trend-customers' ? '#00B357'
                     : chartId === 'mini-trend-profit' ? '#00CC61'
                     : '#00CC61';

    // Subtle vertical gradient fill
    const gradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
    gradient.addColorStop(0, 'rgba(0, 204, 97, 0.18)');
    gradient.addColorStop(1, 'rgba(0, 204, 97, 0)');

    // Normalize data structure
    const series = Array.isArray(res?.data) ? res.data : (Array.isArray(res) ? res : []);
    const labels = Array.isArray(res?.labels) && res.labels.length === series.length
        ? res.labels
        : series.map((_, i) => i + 1);

    const chartConfig = {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: chartId.replace('mini-trend-', ''),
                data: series,
                borderColor: baseColor,
                borderWidth: 2,
                tension: 0.35,
                fill: true,
                backgroundColor: gradient,
                pointRadius: 0,
                pointHoverRadius: 2,
                pointHitRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: { top: 2, bottom: 2 }
            },
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            },
            scales: {
                x: { display: false },
                y: { display: false }
            },
            elements: {
                line: { capBezierPoints: true },
                point: { radius: 0 }
            }
        }
    };

    createOptimizedChart(ctx, chartConfig);
}

// Initialize top products chart
function initTopProductsChart() {
    const ctx = document.getElementById('top-products-chart')?.getContext('2d');
    if (!ctx) return;

    const data = fetchData('/api/top-products');
    if (!data) return;

    const chartConfig = {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Sales',
                data: data.data,
                backgroundColor: '#00CC61',
                borderColor: '#00CC61',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { family: "ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', sans-serif", size: 14 },
                    bodyFont: { family: "ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', sans-serif", size: 13 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.1)' }
                }
            }
        }
    };

    createOptimizedChart(ctx, chartConfig);
}

// Initialize top customers chart
function initTopCustomersChart() {
    const ctx = document.getElementById('top-customers-chart')?.getContext('2d');
    if (!ctx) return;

    const data = fetchData('/api/top-customers');
    if (!data) return;

    const chartConfig = {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Total Spent',
                data: data.data,
                backgroundColor: '#00CC61',
                borderColor: '#00CC61',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { family: "ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', sans-serif", size: 14 },
                    bodyFont: { family: "ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', sans-serif", size: 13 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.1)' }
                }
            }
        }
    };

    createOptimizedChart(ctx, chartConfig);
}

// Fallback mini-chart data generator
function woodashGenerateFallbackSeries(chartId, length = 12) {
    const seeds = {
        'mini-trend-sales': 120,
        'mini-trend-orders': 40,
        'mini-trend-aov': 65,
        'mini-trend-customers': 20,
        'mini-trend-profit': 80
    };
    const base = seeds[chartId] ?? 50;
    const series = [];
    let value = base;
    for (let i = 0; i < length; i++) {
        const drift = (Math.sin(i / 2) + Math.cos(i / 3)) * 2;
        const noise = (Math.random() - 0.5) * 3;
        value = Math.max(0, value + drift + noise);
        series.push(Math.round(value * 100) / 100);
    }
    return series;
}
</script>

<!-- Quick Add Modal -->
<div id="quick-add-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Quick Add</h2>
                <button id="close-quick-add" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <p class="text-gray-500 mt-2">Choose what you'd like to add quickly</p>
        </div>
        
        <div class="p-6">
            <!-- Quick Add Tabs -->
            <div class="flex space-x-1 bg-gray-100 rounded-lg p-1 mb-6">
                <button class="quick-add-tab active flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors bg-white text-[#00CC61]" data-tab="product">
                    <i class="fa-solid fa-box mr-2"></i>Product
                </button>
                <button class="quick-add-tab flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-gray-600" data-tab="customer">
                    <i class="fa-solid fa-user mr-2"></i>Customer
                </button>
                <button class="quick-add-tab flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-gray-600" data-tab="order">
                    <i class="fa-solid fa-shopping-cart mr-2"></i>Order
                </button>
                <button class="quick-add-tab flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-gray-600" data-tab="coupon">
                    <i class="fa-solid fa-ticket mr-2"></i>Coupon
                </button>
            </div>
            
            <!-- Product Form -->
            <div id="product-form" class="quick-add-form">
                <form class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                            <input type="text" name="product_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="Enter product name" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                            <input type="text" name="product_sku" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="Enter SKU">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Regular Price</label>
                            <input type="number" name="regular_price" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="0.00" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                            <input type="number" name="sale_price" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="0.00">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                        <textarea name="short_description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="Enter short description"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                            <input type="number" name="stock_quantity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Weight</label>
                            <input type="number" name="weight" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="product_category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent">
                                <option value="">Select Category</option>
                                <option value="electronics">Electronics</option>
                                <option value="clothing">Clothing</option>
                                <option value="books">Books</option>
                                <option value="home">Home & Garden</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="manage_stock" class="rounded text-[#00CC61] focus:ring-[#00CC61]">
                            <span class="ml-2 text-sm text-gray-700">Manage stock</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="virtual" class="rounded text-[#00CC61] focus:ring-[#00CC61]">
                            <span class="ml-2 text-sm text-gray-700">Virtual product</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="downloadable" class="rounded text-[#00CC61] focus:ring-[#00CC61]">
                            <span class="ml-2 text-sm text-gray-700">Downloadable</span>
                        </label>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" class="cancel-btn px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-[#00CC61] text-white rounded-lg hover:bg-[#00b357] transition-colors">
                            <i class="fa-solid fa-plus mr-2"></i>Add Product
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Customer Form -->
            <div id="customer-form" class="quick-add-form hidden">
                <form class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" name="first_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="John" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" name="last_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="Doe" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="john@example.com" required>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="+1 (555) 123-4567">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" class="cancel-btn px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-[#00CC61] text-white rounded-lg hover:bg-[#00b357] transition-colors">
                            <i class="fa-solid fa-plus mr-2"></i>Add Customer
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Order Form -->
            <div id="order-form" class="quick-add-form hidden">
                <form class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                            <select name="customer_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" required>
                                <option value="">Select Customer</option>
                                <option value="guest">Guest Customer</option>
                                <option value="1">John Doe (john@example.com)</option>
                                <option value="2">Jane Smith (jane@example.com)</option>
                                <option value="3">Mike Johnson (mike@example.com)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                            <select name="order_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent">
                                <option value="pending">Pending Payment</option>
                                <option value="processing">Processing</option>
                                <option value="on-hold">On Hold</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Items</label>
                        <div id="order-items" class="space-y-2">
                            <div class="flex gap-2 items-end">
                                <div class="flex-1">
                                    <select name="products[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent">
                                        <option value="">Select Product</option>
                                        <option value="1">Wireless Headphones - $99.99</option>
                                        <option value="2">Smartphone Case - $19.99</option>
                                        <option value="3">Bluetooth Speaker - $79.99</option>
                                        <option value="4">Fitness Tracker - $149.99</option>
                                    </select>
                                </div>
                                <div class="w-24">
                                    <input type="number" name="quantities[]" min="1" value="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="Qty">
                                </div>
                                <button type="button" class="add-order-item px-3 py-2 bg-[#00CC61] text-white rounded-lg hover:bg-[#00b357] transition-colors">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" class="cancel-btn px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-[#00CC61] text-white rounded-lg hover:bg-[#00b357] transition-colors">
                            <i class="fa-solid fa-plus mr-2"></i>Create Order
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Coupon Form -->
            <div id="coupon-form" class="quick-add-form hidden">
                <form class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Coupon Code</label>
                            <input type="text" name="coupon_code" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="SAVE20" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Type</label>
                            <select name="discount_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent">
                                <option value="percent">Percentage Discount</option>
                                <option value="fixed_cart">Fixed Cart Discount</option>
                                <option value="fixed_product">Fixed Product Discount</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Coupon Amount</label>
                            <input type="number" name="coupon_amount" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="20.00" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Usage Limit</label>
                            <input type="number" name="usage_limit" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="100">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" placeholder="20% off on all products"></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" class="cancel-btn px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-[#00CC61] text-white rounded-lg hover:bg-[#00b357] transition-colors">
                            <i class="fa-solid fa-plus mr-2"></i>Create Coupon
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Quick Add Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    const quickAddModal = document.getElementById('quick-add-modal');
    const closeQuickAdd = document.getElementById('close-quick-add');
    const quickAddTabs = document.querySelectorAll('.quick-add-tab');
    const quickAddForms = document.querySelectorAll('.quick-add-form');
    
    // Define quickAddBtn - select the button that should open the modal
    const quickAddBtn = document.getElementById('quick-add-btn');

    // Open modal
    quickAddBtn?.addEventListener('click', () => {
        quickAddModal.classList.remove('hidden');
        quickAddModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    });

    // Close modal
    const closeModal = () => {
        quickAddModal.classList.add('hidden');
        quickAddModal.classList.remove('flex');
        document.body.style.overflow = '';
    };

    // Make closeQuickAdd available globally for onclick handlers
    window.closeQuickAdd = closeModal;

    closeQuickAdd?.addEventListener('click', closeModal);
    quickAddModal?.addEventListener('click', (e) => {
        if (e.target === quickAddModal) closeModal();
    });

    // Cancel buttons
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('cancel-btn')) {
            closeModal();
        }
    });

    // Tab switching
    quickAddTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetTab = tab.dataset.tab;
            
            // Update active tab
            quickAddTabs.forEach(t => {
                t.classList.remove('active', 'bg-white', 'text-[#00CC61]');
                t.classList.add('text-gray-600');
            });
            tab.classList.add('active', 'bg-white', 'text-[#00CC61]');
            tab.classList.remove('text-gray-600');
            
            // Show target form
            quickAddForms.forEach(form => {
                form.classList.add('hidden');
            });
            document.getElementById(`${targetTab}-form`).classList.remove('hidden');
        });
    });

    // Add order item functionality
    document.addEventListener('click', (e) => {
        if (e.target.closest('.add-order-item')) {
            const orderItems = document.getElementById('order-items');
            const newItem = orderItems.querySelector('.flex').cloneNode(true);
            
            // Clear inputs in new item
            newItem.querySelectorAll('select, input').forEach(input => {
                if (input.type === 'number') {
                    input.value = '1';
                } else {
                    input.value = '';
                }
            });
            
            // Change button to remove
            const button = newItem.querySelector('button');
            button.innerHTML = '<i class="fa-solid fa-minus"></i>';
            button.classList.remove('add-order-item', 'bg-[#00CC61]', 'hover:bg-[#00b357]');
            button.classList.add('remove-order-item', 'bg-red-500', 'hover:bg-red-600');
            
            orderItems.appendChild(newItem);
        }
        
        if (e.target.closest('.remove-order-item')) {
            e.target.closest('.flex').remove();
        }
    });

    // Form submissions
    quickAddForms.forEach(form => {
        const formElement = form.querySelector('form');
        formElement?.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const formData = new FormData(formElement);
            const formType = form.id.replace('-form', '');
            
            // Add action for backend
            formData.append('action', `woodash_add_${formType}`);
            formData.append('nonce', window.woodash_ajax?.nonce || '');
            
            // Show loading state
            const submitBtn = formElement.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Adding...';
            submitBtn.disabled = true;
            
            // Submit to backend
            fetch(window.woodash_ajax?.ajax_url || '', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification('Success!', `${formType.charAt(0).toUpperCase() + formType.slice(1)} added successfully`, 'success');
                    formElement.reset();
                    closeModal();
                } else {
                    showNotification('Error', data.data || 'Failed to add item', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error', 'Something went wrong. Please try again.', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    });

// Notification system
function showNotification(title, message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `woodash-notification woodash-notification-${type} opacity-0 translate-x-full`;
    notification.innerHTML = `
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} text-${type === 'success' ? 'green' : 'red'}-500"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-medium text-gray-900">${title}</h4>
                <p class="text-sm text-gray-600">${message}</p>
            </div>
            <button class="flex-shrink-0 text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.remove()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('opacity-0', 'translate-x-full');
    }, 100);
    
    // Auto remove
    setTimeout(() => {
        notification.classList.add('opacity-0', 'translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Advanced Analytics Functions
function refreshAIInsights() {
    const container = document.getElementById('ai-insights-container');
    const loadingHTML = `
        <div class="flex items-center justify-center p-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
            <span class="ml-2 text-gray-600">Generating AI insights...</span>
        </div>
    `;
    
    container.innerHTML = loadingHTML;
    
    // Simulate AI processing
    setTimeout(() => {
        // In a real implementation, this would fetch from your AI service
        const insights = generateRandomInsights();
        renderAIInsights(insights);
    }, 2000);
}

function generateRandomInsights() {
    const insights = [
        {
            type: 'revenue',
            icon: 'trending-up',
            color: 'green',
            title: 'Revenue Prediction',
            description: 'Based on current trends, you\'re likely to reach $15,420 this month (+12% vs last month)',
            confidence: 'High',
            action: null
        },
        {
            type: 'customer',
            icon: 'users',
            color: 'blue',
            title: 'Customer Behavior',
            description: 'Your customers are most active on weekends. Consider running weekend promotions.',
            confidence: null,
            action: 'showCustomerAnalytics'
        },
        {
            type: 'inventory',
            icon: 'exclamation-triangle',
            color: 'yellow',
            title: 'Inventory Alert',
            description: '3 products may run out of stock within 7 days based on sales velocity',
            confidence: null,
            action: 'showInventoryForecast'
        }
    ];
    
    return insights;
}

function renderAIInsights(insights) {
    const container = document.getElementById('ai-insights-container');
    
    container.innerHTML = insights.map(insight => {
        const colorClasses = getColorClasses(insight.color);
        return `
            <div class="ai-insight-item p-4 ${colorClasses.background} rounded-lg ${colorClasses.border}">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 ${colorClasses.iconBg} rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-${insight.icon} ${colorClasses.iconText}"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-white">${insight.title}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">${insight.description}</p>
                        ${insight.confidence ? `<span class="inline-block px-2 py-1 ${colorClasses.badgeBg} ${colorClasses.badgeText} text-xs rounded mt-2">${insight.confidence} Confidence</span>` : ''}
                        ${insight.action ? `<button class="${colorClasses.linkText} text-xs hover:underline mt-2 block" onclick="${insight.action}()">View Details</button>` : ''}
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function getColorClasses(color) {
    const colorMap = {
        'green': {
            background: 'bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20',
            border: 'border border-green-100 dark:border-green-800',
            iconBg: 'bg-green-100 dark:bg-green-900',
            iconText: 'text-green-600 dark:text-green-400',
            badgeBg: 'bg-green-100 dark:bg-green-900',
            badgeText: 'text-green-800 dark:text-green-200',
            linkText: 'text-green-600 dark:text-green-400'
        },
        'blue': {
            background: 'bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20',
            border: 'border border-blue-100 dark:border-blue-800',
            iconBg: 'bg-blue-100 dark:bg-blue-900',
            iconText: 'text-blue-600 dark:text-blue-400',
            badgeBg: 'bg-blue-100 dark:bg-blue-900',
            badgeText: 'text-blue-800 dark:text-blue-200',
            linkText: 'text-blue-600 dark:text-blue-400'
        },
        'yellow': {
            background: 'bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20',
            border: 'border border-yellow-100 dark:border-yellow-800',
            iconBg: 'bg-yellow-100 dark:bg-yellow-900',
            iconText: 'text-yellow-600 dark:text-yellow-400',
            badgeBg: 'bg-yellow-100 dark:bg-yellow-900',
            badgeText: 'text-yellow-800 dark:text-yellow-200',
            linkText: 'text-yellow-600 dark:text-yellow-400'
        },
        'purple': {
            background: 'bg-gradient-to-r from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20',
            border: 'border border-purple-100 dark:border-purple-800',
            iconBg: 'bg-purple-100 dark:bg-purple-900',
            iconText: 'text-purple-600 dark:text-purple-400',
            badgeBg: 'bg-purple-100 dark:bg-purple-900',
            badgeText: 'text-purple-800 dark:text-purple-200',
            linkText: 'text-purple-600 dark:text-purple-400'
        }
    };
    
    return colorMap[color] || colorMap['blue']; // Default to blue if color not found
}

function showCustomerAnalytics() {
    showNotification('Customer Analytics', 'Opening detailed customer behavior analysis...', 'success');
    // In a real implementation, this would open a detailed analytics modal
}

function showInventoryForecast() {
    showNotification('Inventory Forecast', 'Opening inventory management dashboard...', 'success');
    // In a real implementation, this would open inventory management
}

function exportPerformanceReport() {
    const timeframe = document.getElementById('performance-timeframe').value;
    showNotification('Export Started', `Generating performance report for ${timeframe}...`, 'success');
    
    // Simulate export process
    setTimeout(() => {
        showNotification('Export Complete', 'Performance report has been downloaded', 'success');
    }, 2000);
}

function toggleJourneyView(viewType) {
    const funnelView = document.getElementById('journey-funnel');
    
    if (viewType === 'funnel') {
        funnelView.style.display = 'grid';
        showNotification('View Changed', 'Showing funnel analysis view', 'success');
    } else if (viewType === 'path') {
        funnelView.style.display = 'grid'; // For demo, keeping same view
        showNotification('View Changed', 'Showing path analysis view', 'success');
    }
}

function analyzeDropoff(stage) {
    showNotification('Analysis Started', `Analyzing drop-off at ${stage} stage...`, 'success');
    // In a real implementation, this would show detailed drop-off analysis
}

function viewConverterDetails() {
    showNotification('Converter Analysis', 'Opening detailed converter analysis...', 'success');
    // In a real implementation, this would show converter details
}

function viewTimeAnalysis() {
    showNotification('Time Analysis', 'Opening journey time analysis...', 'success');
    // In a real implementation, this would show time analysis
}

// Initialize Performance Chart
function initPerformanceChart() {
    const ctx = document.getElementById('performance-chart');
    if (!ctx) return;
    
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js not loaded, skipping chart initialization');
        ctx.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Chart.js not loaded</div>';
        return;
    }
    
    try {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [
                    {
                        label: 'Conversion Rate (%)',
                        data: [2.8, 3.1, 3.0, 3.2],
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Avg Order Value ($)',
                        data: [82, 85, 84, 87],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Time Period'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Conversion Rate (%)'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Order Value ($)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error initializing performance chart:', error);
        ctx.innerHTML = '<div class="flex items-center justify-center h-full text-red-500">Error loading chart</div>';
    }
}

// Initialize dashboard when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Debug information
    if (typeof woodashData !== 'undefined' && woodashData.debug) {
        console.log('WoodDash Debug Info:', {
            'jQuery Available': typeof jQuery !== 'undefined',
            'Chart.js Available': typeof Chart !== 'undefined',
            'WoodashData Available': typeof woodashData !== 'undefined'
        });
    }
    
    // Add error boundary for the entire dashboard
    window.addEventListener('error', function(event) {
        console.error('Dashboard Error:', event.error);
        showNotification('System Error', 'An error occurred. Please refresh the page.', 'error');
    });
    
    // Add unhandled promise rejection handler
    window.addEventListener('unhandledrejection', function(event) {
        console.error('Unhandled Promise Rejection:', event.reason);
        showNotification('Connection Error', 'Failed to load data. Please check your connection.', 'warning');
    });
    
    // Initialize with proper error handling
    try {
        // Start performance monitoring
        monitorPerformance();
        
        // Perform health check
        const healthStatus = performHealthCheck();
        
        if (!healthStatus) {
            console.warn('Dashboard health check failed - some features may not work properly');
        }
        
        // Initialize performance chart after a delay to ensure Chart.js is loaded
        setTimeout(() => {
            try {
                initPerformanceChart();
            } catch (error) {
                console.error('Failed to initialize performance chart:', error);
            }
        }, 1000);
        
        // Initialize AI insights
        setTimeout(() => {
            try {
                refreshAIInsights();
            } catch (error) {
                console.error('Failed to initialize AI insights:', error);
                document.getElementById('ai-insights-container').innerHTML = 
                    '<div class="text-center text-gray-500 p-4">Failed to load AI insights</div>';
            }
        }, 2000);
        
        // Start real-time activity feed
        try {
            startActivityFeed();
        } catch (error) {
            console.error('Failed to start activity feed:', error);
        }
        
        // Show welcome message
        setTimeout(() => {
            if (healthStatus) {
                showNotification('Welcome!', 'Dashboard loaded successfully. All systems operational.', 'success');
            } else {
                showNotification('Dashboard Loaded', 'Dashboard loaded with some limitations. Check console for details.', 'warning');
            }
        }, 3000);
        
    } catch (error) {
        console.error('Critical dashboard initialization error:', error);
        showNotification('Critical Error', 'Dashboard failed to initialize. Please refresh the page.', 'error');
    }
});

// Real-time Activity Feed Functions
let activityFeedInterval;
let activityPaused = false;

function startActivityFeed() {
    activityFeedInterval = setInterval(() => {
        if (!activityPaused) {
            addRandomActivity();
        }
    }, 30000); // Add new activity every 30 seconds
}

function addRandomActivity() {
    const activities = [
        {
            type: 'order',
            icon: 'shopping-cart',
            color: 'green',
            title: 'New order #' + Math.floor(Math.random() * 9999),
            subtitle: 'Customer: ' + generateRandomName() + ' • $' + (Math.random() * 200 + 20).toFixed(2),
            time: 'Just now',
            badge: 'Order'
        },
        {
            type: 'user',
            icon: 'user-plus',
            color: 'blue',
            title: 'New customer registration',
            subtitle: generateRandomEmail(),
            time: 'Just now',
            badge: 'User'
        },
        {
            type: 'review',
            icon: 'star',
            color: 'purple',
            title: 'New product review',
            subtitle: (Math.floor(Math.random() * 2) + 4) + ' stars for "' + generateRandomProduct() + '"',
            time: 'Just now',
            badge: 'Review'
        },
        {
            type: 'stock',
            icon: 'exclamation-triangle',
            color: 'yellow',
            title: 'Low stock alert',
            subtitle: 'Product: "' + generateRandomProduct() + '" • ' + Math.floor(Math.random() * 5 + 1) + ' items left',
            time: 'Just now',
            badge: 'Stock'
        }
    ];
    
    const activity = activities[Math.floor(Math.random() * activities.length)];
    const activityFeed = document.getElementById('activity-feed');
    const colorClasses = getColorClasses(activity.color);
    
    const activityElement = document.createElement('div');
    activityElement.className = 'activity-item flex items-start gap-3 p-3 rounded-lg hover:bg-green-50 dark:hover:bg-green-800 transition-colors opacity-0 transform translate-y-4';
    activityElement.innerHTML = `
        <div class="w-8 h-8 ${colorClasses.iconBg} rounded-full flex items-center justify-center">
            <i class="fa-solid fa-${activity.icon} ${colorClasses.iconText} text-sm"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 dark:text-white">${activity.title}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">${activity.subtitle}</p>
            <p class="text-xs text-gray-400 mt-1">${activity.time}</p>
        </div>
        <span class="text-xs ${colorClasses.badgeBg} ${colorClasses.badgeText} px-2 py-1 rounded">${activity.badge}</span>
    `;
    
    // Insert at the beginning
    activityFeed.insertBefore(activityElement, activityFeed.firstChild);
    
    // Animate in
    setTimeout(() => {
        activityElement.classList.remove('opacity-0', 'transform', 'translate-y-4');
    }, 100);
    
    // Remove old activities (keep only last 10)
    const activities_list = activityFeed.querySelectorAll('.activity-item');
    if (activities_list.length > 10) {
        activities_list[activities_list.length - 1].remove();
    }
}

function generateRandomName() {
    const names = ['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Wilson', 'David Brown', 'Emily Davis', 'Chris Miller', 'Lisa Garcia'];
    return names[Math.floor(Math.random() * names.length)];
}

function generateRandomEmail() {
    const domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];
    const names = ['john', 'jane', 'mike', 'sarah', 'david', 'emily', 'chris', 'lisa'];
    return names[Math.floor(Math.random() * names.length)] + Math.floor(Math.random() * 999) + '@' + domains[Math.floor(Math.random() * domains.length)];
}

function generateRandomProduct() {
    const productCategories = {
        electronics: {
            name: 'Electronics',
            icon: 'fa-solid fa-microchip',
            color: '#3B82F6',
            products: [
                {
                    name: 'Wireless Bluetooth Headphones',
                    price: 89.99,
                    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop&crop=center',
                    rating: 4.5,
                    stock: 45,
                    sku: 'WBH-001'
                },
                {
                    name: 'Smart Fitness Watch',
                    price: 199.99,
                    image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=300&h=300&fit=crop&crop=center',
                    rating: 4.7,
                    stock: 23,
                    sku: 'SFW-002'
                },
                {
                    name: 'Portable Bluetooth Speaker',
                    price: 59.99,
                    image: 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=300&h=300&fit=crop&crop=center',
                    rating: 4.3,
                    stock: 67,
                    sku: 'PBS-003'
                },
                {
                    name: 'Mechanical Gaming Keyboard',
                    price: 129.99,
                    image: 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=300&h=300&fit=crop&crop=center',
                    rating: 4.8,
                    stock: 34,
                    sku: 'MGK-004'
                },
                {
                    name: 'Wireless Gaming Mouse',
                    price: 79.99,
                    image: 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=300&h=300&fit=crop&crop=center',
                    rating: 4.6,
                    stock: 56,
                    sku: 'WGM-005'
                }
            ]
        },
        accessories: {
            name: 'Accessories',
            icon: 'fa-solid fa-mobile-screen',
            color: '#10B981',
            products: [
                {
                    name: 'Premium Phone Case',
                    price: 24.99,
                    image: 'https://images.unsplash.com/photo-1556656793-08538906a9f8?w=300&h=300&fit=crop&crop=center',
                    rating: 4.4,
                    stock: 89,
                    sku: 'PPC-006'
                },
                {
                    name: 'Adjustable Laptop Stand',
                    price: 49.99,
                    image: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=300&h=300&fit=crop&crop=center',
                    rating: 4.5,
                    stock: 42,
                    sku: 'ALS-007'
                },
                {
                    name: 'USB-C Charging Cable',
                    price: 19.99,
                    image: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&h=300&fit=crop&crop=center',
                    rating: 4.2,
                    stock: 156,
                    sku: 'UCC-008'
                },
                {
                    name: 'Wireless Charging Pad',
                    price: 34.99,
                    image: 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=300&h=300&fit=crop&crop=center',
                    rating: 4.3,
                    stock: 73,
                    sku: 'WCP-009'
                }
            ]
        },
        home: {
            name: 'Home & Office',
            icon: 'fa-solid fa-house',
            color: '#F59E0B',
            products: [
                {
                    name: 'Smart LED Desk Lamp',
                    price: 69.99,
                    image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop&crop=center',
                    rating: 4.6,
                    stock: 38,
                    sku: 'SLD-010'
                },
                {
                    name: 'Ergonomic Office Chair',
                    price: 299.99,
                    image: 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=300&h=300&fit=crop&crop=center',
                    rating: 4.7,
                    stock: 15,
                    sku: 'EOC-011'
                },
                {
                    name: 'Bamboo Desk Organizer',
                    price: 39.99,
                    image: 'https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?w=300&h=300&fit=crop&crop=center',
                    rating: 4.4,
                    stock: 62,
                    sku: 'BDO-012'
                }
            ]
        },
        fashion: {
            name: 'Fashion',
            icon: 'fa-solid fa-shirt',
            color: '#8B5CF6',
            products: [
                {
                    name: 'Classic Cotton T-Shirt',
                    price: 29.99,
                    image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=300&h=300&fit=crop&crop=center',
                    rating: 4.3,
                    stock: 124,
                    sku: 'CCT-013'
                },
                {
                    name: 'Denim Jacket',
                    price: 89.99,
                    image: 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=300&h=300&fit=crop&crop=center',
                    rating: 4.5,
                    stock: 47,
                    sku: 'DJ-014'
                },
                {
                    name: 'Leather Wallet',
                    price: 59.99,
                    image: 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=300&h=300&fit=crop&crop=center',
                    rating: 4.6,
                    stock: 83,
                    sku: 'LW-015'
                }
            ]
        }
    };

    // Get random category
    const categoryKeys = Object.keys(productCategories);
    const randomCategoryKey = categoryKeys[Math.floor(Math.random() * categoryKeys.length)];
    const category = productCategories[randomCategoryKey];

    // Get random product from category
    const randomProduct = category.products[Math.floor(Math.random() * category.products.length)];

    // Add category info to product
    return {
        ...randomProduct,
        category: {
            key: randomCategoryKey,
            name: category.name,
            icon: category.icon,
            color: category.color
        },
        // Add some dynamic properties
        isNew: Math.random() > 0.7,
        isFeatured: Math.random() > 0.8,
        discount: Math.random() > 0.6 ? Math.floor(Math.random() * 30) + 5 : 0,
        views: Math.floor(Math.random() * 1000) + 50,
        sales: Math.floor(Math.random() * 200) + 10
    };
}

// Enhanced function to generate product with specific category
function generateProductByCategory(categoryKey) {
    const productCategories = {
        electronics: {
            name: 'Electronics',
            icon: 'fa-solid fa-microchip',
            color: '#3B82F6',
            products: [
                {
                    name: 'Wireless Bluetooth Headphones',
                    price: 89.99,
                    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop&crop=center',
                    rating: 4.5,
                    stock: 45,
                    sku: 'WBH-001'
                },
                {
                    name: 'Smart Fitness Watch',
                    price: 199.99,
                    image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=300&h=300&fit=crop&crop=center',
                    rating: 4.7,
                    stock: 23,
                    sku: 'SFW-002'
                }
            ]
        }
        // Add other categories as needed
    };

    if (!productCategories[categoryKey]) {
        return generateRandomProduct();
    }

    const category = productCategories[categoryKey];
    const randomProduct = category.products[Math.floor(Math.random() * category.products.length)];

    return {
        ...randomProduct,
        category: {
            key: categoryKey,
            name: category.name,
            icon: category.icon,
            color: category.color
        },
        isNew: Math.random() > 0.7,
        isFeatured: Math.random() > 0.8,
        discount: Math.random() > 0.6 ? Math.floor(Math.random() * 30) + 5 : 0,
        views: Math.floor(Math.random() * 1000) + 50,
        sales: Math.floor(Math.random() * 200) + 10
    };
}

function toggleActivityFilter(filter) {
    showNotification('Filter Changed', `Showing ${filter} activity`, 'success');
}

function pauseActivityFeed() {
    const pauseBtn = document.getElementById('pause-activity');
    const icon = pauseBtn.querySelector('i');
    
    activityPaused = !activityPaused;
    
    if (activityPaused) {
        icon.className = 'fa-solid fa-play';
        pauseBtn.title = 'Resume Feed';
        showNotification('Activity Feed', 'Activity feed paused', 'info');
    } else {
        icon.className = 'fa-solid fa-pause';
        pauseBtn.title = 'Pause Feed';
        showNotification('Activity Feed', 'Activity feed resumed', 'success');
    }
}

// Quick Action Functions
function quickAddProduct() {
    const quickAddModal = document.getElementById('quick-add-modal');
    if (quickAddModal) {
        quickAddModal.classList.remove('hidden');
        quickAddModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        showNotification('Quick Add', 'Opening product creation form...', 'success');
    }
}

function processOrders() {
    showNotification('Processing', 'Processing pending orders...', 'success');
    // Simulate processing
    setTimeout(() => {
        showNotification('Complete', '5 orders processed successfully', 'success');
    }, 2000);
}

function generateReport() {
    showNotification('Report Generation', 'Generating comprehensive report...', 'success');
    // Simulate report generation
    setTimeout(() => {
        showNotification('Report Ready', 'Report has been generated and downloaded', 'success');
    }, 3000);
}

function backupData() {
    showNotification('Backup Started', 'Creating data backup...', 'success');
    // Simulate backup
    setTimeout(() => {
        showNotification('Backup Complete', 'Data backup completed successfully', 'success');
    }, 4000);
}

// Dashboard Health Check Function
function performHealthCheck() {
    const healthChecks = [
        {
            name: 'DOM Elements',
            check: () => {
                const requiredElements = [
                    'woodash-dashboard',
                    'ai-insights-container',
                    'performance-chart',
                    'activity-feed',
                    'notifications-dropdown'
                ];
                return requiredElements.every(id => document.getElementById(id) !== null);
            }
        },
        {
            name: 'External Libraries',
            check: () => {
                return typeof Chart !== 'undefined' && typeof jQuery !== 'undefined';
            }
        },
        {
            name: 'AJAX Configuration',
            check: () => {
                return typeof woodashData !== 'undefined' && 
                       woodashData.ajaxurl && 
                       woodashData.nonce;
            }
        },
        {
            name: 'CSS Frameworks',
            check: () => {
                // Check if Tailwind classes are working
                const testElement = document.createElement('div');
                testElement.className = 'bg-red-500 text-white';
                document.body.appendChild(testElement);
                const styles = window.getComputedStyle(testElement);
                const hasStyles = styles.backgroundColor !== 'rgba(0, 0, 0, 0)' && 
                                styles.backgroundColor !== 'transparent';
                document.body.removeChild(testElement);
                return hasStyles;
            }
        }
    ];
    
    const results = healthChecks.map(check => ({
        name: check.name,
        passed: check.check()
    }));
    
    const allPassed = results.every(result => result.passed);
    
    console.log('Dashboard Health Check Results:', results);
    
    if (allPassed) {
        console.log('✅ All health checks passed - Dashboard is fully operational');
        return true;
    } else {
        const failed = results.filter(result => !result.passed);
        console.warn('⚠️ Some health checks failed:', failed);
        showNotification('System Warning', `${failed.length} system check(s) failed. See console for details.`, 'warning');
        return false;
    }
}

// Performance Monitoring
function monitorPerformance() {
    if (typeof performance !== 'undefined' && performance.mark) {
        performance.mark('dashboard-init-start');
        
        setTimeout(() => {
            performance.mark('dashboard-init-end');
            performance.measure('dashboard-initialization', 'dashboard-init-start', 'dashboard-init-end');
            
            const measures = performance.getEntriesByType('measure');
            const initMeasure = measures.find(m => m.name === 'dashboard-initialization');
            
            if (initMeasure) {
                console.log(`Dashboard initialization took: ${initMeasure.duration.toFixed(2)}ms`);
                
                if (initMeasure.duration > 5000) {
                    showNotification('Performance Warning', 'Dashboard took longer than expected to load.', 'warning');
                }
            }
        }, 100);
    }
}

// Add immediate dependency check
console.log('WoodDash Dependencies Check:', {
    'jQuery': typeof jQuery !== 'undefined' ? 'Available' : 'Missing',
    'Chart.js': typeof Chart !== 'undefined' ? 'Available' : 'Missing', 
    'WoodashData': typeof woodashData !== 'undefined' ? 'Available' : 'Missing'
});

// Show user-friendly error if jQuery is missing
if (typeof jQuery === 'undefined') {
    document.addEventListener('DOMContentLoaded', function() {
        const errorMessage = document.createElement('div');
        errorMessage.innerHTML = `
            <div style="position: fixed; top: 20px; right: 20px; background: #dc2626; color: white; padding: 16px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 10000; max-width: 400px;">
                <h4 style="margin: 0 0 8px 0; font-weight: 600;">WoodDash Pro - jQuery Missing</h4>
                <p style="margin: 0; font-size: 14px;">jQuery is required but not loaded. Please refresh the page or check your WordPress jQuery installation.</p>
                <button onclick="location.reload()" style="margin-top: 8px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Refresh Page</button>
            </div>
        `;
        document.body.appendChild(errorMessage);
    });
}
</script>

<script>
// Ensure all global functions are available
window.closeQuickAdd = window.closeQuickAdd || function() {
    const modal = document.getElementById('quick-add-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
};

// Add other global function safety checks
window.quickAddProduct = window.quickAddProduct || function() {
    const modal = document.getElementById('quick-add-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
};
</script>