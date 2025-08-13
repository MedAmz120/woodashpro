<!-- Google Fonts & Font Awesome for icons -->
<!-- Removed external Inter font; using system font stack -->
-<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
-<script src="https://cdn.tailwindcss.com"></script>
-<!-- Chart.js: Use a valid CDN and only load once -->
-<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
-<!-- Lottie player: Only load once -->
-<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
+<!-- External libraries are enqueued via WordPress. Duplicate CDN tags removed. -->
     <style type="text/tailwindcss">
         @layer base {
             body {
             font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', sans-serif;
             background-color: #F8FAFC;
             transition: all 0.3s ease;
         }
         }
         .woodash-card {
         @apply bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md;
     }
     .woodash-metric-card {
         @apply woodash-card p-6 flex flex-col justify-between min-h-[160px] h-full relative overflow-hidden;
     }
     .woodash-metric-title {
        @apply text-gray-600 text-sm font-medium tracking-wide mb-2;
    }
    .woodash-metric-value {
        @apply text-3xl font-bold text-gray-900 flex items-center;
    }
    .woodash-metric-icon {
        @apply ml-2 text-2xl rounded-full p-2 bg-opacity-10;
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
        @apply bg-[#00CC61] text-white hover:bg-[#00b357] shadow-sm hover:shadow-md;
    }
    .woodash-btn-secondary {
        @apply bg-gray-100 text-gray-700 hover:bg-gray-200;
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
    .woodash-animate-in {
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
    .woodash-hover-card {
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
            linear-gradient(135deg, rgba(0, 204, 97, 0.03) 0%, rgba(0, 179, 87, 0.03) 100%);
        animation: woodash-bg-shift 20s ease-in-out infinite alternate;
        z-index: 0;
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
        transition: opacity 0.3s ease;
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
        animation: woodash-orb-float 15s ease-in-out infinite;
        transition: all 0.5s ease;
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
        animation-delay: 0s;
    }
    .woodash-orb-2 {
        width: 500px;
        height: 500px;
        background: radial-gradient(circle at center, rgba(0, 179, 87, 0.15), transparent 70%);
        bottom: -200px;
        right: -200px;
        animation-delay: -5s;
    }
    .woodash-orb-3 {
        width: 350px;
        height: 350px;
        background: radial-gradient(circle at center, rgba(0, 204, 97, 0.1), transparent 70%);
        top: 40%;
        left: 60%;
        transform: translate(-50%, -50%);
        animation-delay: -10s;
    }
    .woodash-line {
        position: absolute;
        background: linear-gradient(90deg, transparent, rgba(0, 204, 97, 0.1), transparent);
        height: 2px;
        width: 100%;
        animation: woodash-line-move 8s linear infinite;
        transition: all 0.3s ease;
    }
    .woodash-line:hover {
        height: 3px;
        background: linear-gradient(90deg, transparent, rgba(0, 204, 97, 0.2), transparent);
    }
    .woodash-line-1 { top: 15%; animation-delay: 0s; }
    .woodash-line-2 { top: 35%; animation-delay: -2s; }
    .woodash-line-3 { top: 55%; animation-delay: -4s; }
    .woodash-line-4 { top: 75%; animation-delay: -6s; }
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
        animation: woodash-shimmer-move 3s linear infinite;
        z-index: 2;
        transition: opacity 0.3s ease;
    }
    .woodash-bg-animation:hover .woodash-shimmer {
        opacity: 0.8;
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
        animation: woodash-shimmer 2s infinite;
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
    .woodash-spin {
        animation: woodash-spin 1s linear infinite;
    }
    .woodash-bounce {
        animation: none;
    }
    .woodash-fade-in {
        animation: woodash-fade-in 0.5s ease-out;
    }
    .woodash-slide-up {
        animation: woodash-slide-up 0.5s ease-out;
    }
    .woodash-rotate {
        animation: woodash-rotate 1s linear infinite;
    }
    .woodash-scale {
        animation: woodash-scale 1s ease-in-out infinite;
    }
    .woodash-glow {
        animation: none;
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
        &:focus-within {
            animation: woodash-subtle-glow 2s ease-in-out infinite alternate;
        }
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
        animation: woodash-search-slide 0.3s ease-out;
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
    </style>
    
<div id="woodash-dashboard" class="woodash-fullscreen woodash-bg-pattern woodash-bg-animation">
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
                <a href="#" class="woodash-nav-link active woodash-hover-card woodash-slide-up" style="animation-delay: 0.1s">
                    <i class="fa-solid fa-gauge w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.2s">
                    <i class="fa-solid fa-box w-5"></i>
                    <span>Products</span>
                    <span class="woodash-badge woodash-badge-success ml-auto woodash-pulse">New</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.3s">
                    <i class="fa-solid fa-users w-5"></i>
                    <span>Customers</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.4s">
                    <i class="fa-solid fa-boxes-stacked w-5"></i>
                    <span>Stock</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.5s">
                    <i class="fa-solid fa-star w-5"></i>
                    <span>Reviews</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.6s">
                    <i class="fa-solid fa-ticket w-5"></i>
                    <span>Coupons</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.7s">
                    <i class="fa-solid fa-gear w-5"></i>
                    <span>Settings</span>
                </a>
            </nav>
            <div class="absolute bottom-6 left-6 right-6">
                <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 woodash-hover-card woodash-fade-in">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center font-semibold text-white woodash-glow">JD</div>
                <div>
                        <div class="font-medium text-gray-900">John Doe</div>
                        <a href="#" class="text-sm text-[#00CC61] hover:underline woodash-logout-btn">Logout</a>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="woodash-main flex-1 p-6 md:p-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 woodash-fade-in">
                    <div>
                        <h1 class="text-2xl font-bold woodash-gradient-text">Dashboard</h1>
                        <p class="text-gray-500">Welcome back, John! Here's what's happening with your store.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button id="toggle-slideshow" class="woodash-btn woodash-btn-secondary woodash-hover-card">
                            <i class="fa-solid fa-eye"></i>
                            <span>Toggle Slideshow</span>
                        </button>
                        <div class="woodash-search-container">
                            <input type="text"  style="border:none;"
                                   placeholder="Search orders, products, customers..." 
                                   class="woodash-search-input w-full"
                                   id="woodash-search">
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
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card woodash-glow" id="notifications-btn">
                                <i class="fa-solid fa-bell"></i>
                                <span class="woodash-badge woodash-badge-danger absolute -top-1 -right-1 woodash-bounce">3</span>
                            </button>
                            <div class="woodash-dropdown hidden" id="notifications-dropdown">
                                <div class="woodash-dropdown-item woodash-fade-in" style="animation-delay: 0.1s">
                                    <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                                    <span>New order #1234</span>
                                </div>
                                <div class="woodash-dropdown-item woodash-fade-in" style="animation-delay: 0.2s">
                                    <i class="fa-solid fa-circle-exclamation text-yellow-500"></i>
                                    <span>Low stock alert</span>
                                </div>
                                <div class="woodash-dropdown-item woodash-fade-in" style="animation-delay: 0.3s">
                                    <i class="fa-solid fa-circle-exclamation text-green-500"></i>
                                    <span>New customer registered</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Slideshow Section -->
                <div id="slideshow-section" class="mb-8 relative overflow-hidden rounded-xl woodash-glass-effect">
                    <div class="woodash-slideshow relative h-[300px]">
                        <!-- Slide 1 -->
                        <div class="woodash-slide absolute inset-0 p-8 flex items-center woodash-fade-in" style="animation-delay: 0.1s">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div>
                                    <h2 class="text-3xl font-bold woodash-gradient-text mb-4">Welcome to WooDash Pro</h2>
                                    <p class="text-gray-600 mb-6">Your all-in-one dashboard for managing your WooCommerce store. Track sales, monitor performance, and grow your business.</p>
                                    <button class="woodash-btn woodash-btn-primary woodash-hover-card">
                                        <i class="fa-solid fa-rocket mr-2"></i>
                                        Get Started
                                    </button>
                                </div>
                                <div class="hidden md:block">
                                    <lottie-player src="https://assets2.lottiefiles.com/packages/lf20_49rdyysj.json" background="transparent" speed="1" style="width: 100%; height: 200px;" loop autoplay></lottie-player>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="woodash-slide absolute inset-0 p-8 flex items-center woodash-fade-in" style="animation-delay: 0.1s">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div>
                                    <h2 class="text-3xl font-bold woodash-gradient-text mb-4">Track Your Performance</h2>
                                    <p class="text-gray-600 mb-6">Monitor your store's performance with real-time analytics and detailed reports. Make data-driven decisions to grow your business.</p>
                                    <button class="woodash-btn woodash-btn-primary woodash-hover-card">
                                        <i class="fa-solid fa-chart-line mr-2"></i>
                                        View Analytics
                                    </button>
                                </div>
                                <div class="hidden md:block">
                                    <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_ydo1amjm.json" background="transparent" speed="1" style="width: 100%; height: 200px;" loop autoplay></lottie-player>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="woodash-slide absolute inset-0 p-8 flex items-center woodash-fade-in" style="animation-delay: 0.1s">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div>
                                    <h2 class="text-3xl font-bold woodash-gradient-text mb-4">Manage Your Products</h2>
                                    <p class="text-gray-600 mb-6">Easily manage your product inventory, track stock levels, and monitor top-performing products.</p>
                                    <button class="woodash-btn woodash-btn-primary woodash-hover-card">
                                        <i class="fa-solid fa-box mr-2"></i>
                                        Manage Products
                                    </button>
                                </div>
                                <div class="hidden md:block">
                                    <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_kkflmtur.json" background="transparent" speed="1" style="width: 100%; height: 200px;" loop autoplay></lottie-player>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide Navigation -->
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                        <button class="woodash-slide-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-[#00CC61] transition-colors duration-200" data-slide="0"></button>
                        <button class="woodash-slide-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-[#00CC61] transition-colors duration-200" data-slide="1"></button>
                        <button class="woodash-slide-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-[#00CC61] transition-colors duration-200" data-slide="2"></button>
                    </div>

                    <!-- Slide Controls -->
                    <button class="woodash-slide-control woodash-slide-prev absolute left-4 top-1/2 transform -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white shadow-lg flex items-center justify-center text-gray-600 hover:text-[#00CC61] transition-all duration-200">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button class="woodash-slide-control woodash-slide-next absolute right-4 top-1/2 transform -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white shadow-lg flex items-center justify-center text-gray-600 hover:text-[#00CC61] transition-all duration-200">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Date Range & Export -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div class="flex gap-2">
                        <select id="date-filter" class="woodash-btn woodash-btn-secondary woodash-hover-card woodash-fade-in">
                            <option value="today">Today</option>
                            <option value="last7days">Last 7 Days</option>
                            <option value="custom">Custom Date</option>
                        </select>
                        <input type="date" id="custom-date-from" class="hidden" placeholder="From">
                        <input type="date" id="custom-date-to" class="hidden" placeholder="To">
                        <button class="woodash-btn woodash-btn-secondary woodash-hover-card woodash-fade-in" id="apply-custom-date">Apply</button>
                    </div>
                </div>

                <!-- Stat Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 items-stretch">
                    <!-- Total Sales Card -->
                    <div class="woodash-metric-card woodash-animate-in woodash-hover-card woodash-glow h-full" style="animation-delay: 0.1s">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="woodash-metric-title">
                                    <span>Total Sales</span> <span class="woodash-badge woodash-badge-success text-xs">Live</span>
                                </h3>
                                <div class="woodash-metric-value" id="total-sales">$0</div>
                                <div class="flex items-center gap-1 mt-1">
                                    <span class="text-sm text-green-600 flex items-center gap-1">
                                        <i class="fa-solid fa-arrow-up text-xs"></i>
                                        <span>12.5%</span>
                                    </span>
                                    <span class="text-xs text-gray-500">vs last month</span>
                                </div>
                            </div>
                            <div class="woodash-metric-icon woodash-metric-green">
                                <i class="fa-solid fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <canvas id="mini-trend-sales" height="40"></canvas>
                        </div>
                    </div>


                    <!-- Total Orders Card -->
                    <div class="woodash-metric-card woodash-animate-in woodash-hover-card woodash-glow h-full" style="animation-delay: 0.2s">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="woodash-metric-title">
                                    <span>Total Orders</span>
                                    <span class="woodash-badge woodash-badge-warning text-xs" id="pending-orders">Processing : 0</span>
                                </h3>
                                <div class="woodash-metric-value" id="total-orders">0</div>
                                <div class="flex items-center gap-1 mt-1">
                                     <span class="text-sm text-green-600 flex items-center gap-1">
                                        <i class="fa-solid fa-arrow-up text-xs"></i>
                                        <span>8.2%</span>
                                    </span>
                                    <span class="text-xs text-gray-500">vs last month</span>
                                </div>
                            </div>
                            <div class="woodash-metric-icon woodash-metric-blue">
                                <i class="fa-solid fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <canvas id="mini-trend-orders" height="40"></canvas>
                        </div>
                    </div>

                    <!-- Average Order Value Card -->
                    <div class="woodash-metric-card woodash-animate-in woodash-hover-card woodash-glow h-full" style="animation-delay: 0.3s">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="woodash-metric-title">
                                    <span>Average Order Value</span>
                                    <span class="woodash-badge woodash-badge-danger text-xs">-3.1%</span>
                                </h3>
                                <div class="woodash-metric-value" id="aov">$0</div>
                                <div class="flex items-center gap-1 mt-1">
                                    <span class="text-sm text-red-600 flex items-center gap-1">
                                        <i class="fa-solid fa-arrow-down text-xs"></i>
                                        <span>3.1%</span>
                                    </span>
                                    <span class="text-xs text-gray-500">vs last month</span>
                                </div>
                            </div>
                            <div class="woodash-metric-icon woodash-metric-purple">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <canvas id="mini-trend-aov" height="40"></canvas>
                        </div>
                    </div>

                    <!-- New Customers Card -->
                    <div class="woodash-metric-card woodash-animate-in woodash-hover-card woodash-glow h-full" style="animation-delay: 0.4s">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="woodash-metric-title flex items-center gap-2">
                                    <span>New Customers</span>
                                    <span class="woodash-badge woodash-badge-success text-xs">+15.3%</span>
                                </h3>
                                <div class="woodash-metric-value" id="new-customers">0</div>
                                <div class="flex items-center gap-1 mt-1">
                                    <span class="text-sm text-green-600 flex items-center gap-1">
                                        <i class="fa-solid fa-arrow-up text-xs"></i>
                                        <span>15.3%</span>
                                    </span>
                                    <span class="text-xs text-gray-500">vs last month</span>
                                </div>
                            </div>
                            <div class="woodash-metric-icon woodash-metric-orange">
                                <i class="fa-solid fa-users"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <canvas id="mini-trend-customers" height="40"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Sales Overview -->
                <div class="woodash-chart-container mb-8 woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.4s">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <div>
                            <h2 class="text-lg font-bold woodash-gradient-text">Sales Overview</h2>
                            <p class="text-gray-500 text-sm">Track your sales performance over time</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="woodash-btn woodash-btn-primary woodash-hover-card" data-range="daily">Daily</button>
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card" data-range="weekly">Weekly</button>
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card" data-range="monthly">Monthly</button>
                        </div>
                    </div>
                    <div class="h-[400px]">
                        <canvas id="sales-chart"></canvas>
                    </div>
                </div>
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="woodash-card p-4 woodash-hover-card woodash-fade-in" style="animation-delay: 0.1s">
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
                    <div class="woodash-card p-4 woodash-hover-card woodash-fade-in" style="animation-delay: 0.2s">
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
                    <div class="woodash-card p-4 woodash-hover-card woodash-fade-in" style="animation-delay: 0.3s">
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
                    <div class="woodash-card p-4 woodash-hover-card woodash-fade-in" style="animation-delay: 0.4s">
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
                <!-- Top Products & Customers -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Top Products -->
                    <div class="woodash-chart-container woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.5s">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Top Products</h2>
                                <p class="text-gray-500 text-sm">Best performing products</p>
                            </div>
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card">
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
                    <div class="woodash-chart-container woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.6s">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Top Customers</h2>
                                <p class="text-gray-500 text-sm">Most valuable customers</p>
                            </div>
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card">
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
                    <div class="woodash-chart-container woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.7s">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Recent Activity</h2>
                                <p class="text-gray-500 text-sm">Latest store activities and updates</p>
                            </div>
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                        </div>
                        <div class="space-y-4 woodash-scrollbar" style="max-height: 400px;">
                            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 woodash-fade-in" style="animation-delay: 0.1s">
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
                            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 woodash-fade-in" style="animation-delay: 0.2s">
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
                            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 woodash-fade-in" style="animation-delay: 0.3s">
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
                            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 woodash-fade-in" style="animation-delay: 0.4s">
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
                    <div class="woodash-chart-container woodash-animate-in woodash-hover-card woodash-glow" style="animation-delay: 0.8s">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Store Performance</h2>
                                <p class="text-gray-500 text-sm">Key performance indicators</p>
                            </div>
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card">
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
                <div class="mt-8 woodash-animate-in" style="padding-bottom: 2em;">
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
// Add performance monitoring
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

mark('init');

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
    const toggleButton = document.getElementById('toggle-slideshow');
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
});

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

    new Chart(salesCtx, {
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