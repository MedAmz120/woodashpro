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
             background-color: #F5F5FF;
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
    .woodash-metric-purple {
        @apply bg-purple-100 text-purple-600;
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
        @apply bg-[#824DEB] text-white hover:bg-[#814deb] shadow-sm hover:shadow-md;
    }
    .woodash-btn-secondary {
        @apply bg-gray-100 text-gray-700 hover:bg-gray-200;
    }
    .woodash-nav-link {
        @apply flex items-center px-4 py-3 text-gray-600 hover:text-[#824DEB] hover:bg-gray-50 rounded-lg transition-all duration-200;
    }
    .woodash-nav-link.active {
        @apply bg-[#824DEB] bg-opacity-10 text-[#824DEB] font-medium;
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
        @apply bg-purple-100 text-purple-700;
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
        @apply h-full bg-[#824DEB] rounded-full transition-all duration-300;
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
        @apply border-l-4 border-purple-500;
    }
    .woodash-notification-error {
        @apply border-l-4 border-red-500;
    }
    .woodash-glass-effect {
        @apply backdrop-blur-md bg-white/80 border border-white/20;
    }
    .woodash-hover-card {
        @apply transition-all duration-300 hover:transform hover:scale-105 hover:shadow-lg;
        transform-origin: center;
    }
    
    /* Advanced Animations */
    .woodash-card-entrance {
        animation: woodashCardEntrance 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    
    .woodash-card-entrance:nth-child(1) { animation-delay: 0.1s; }
    .woodash-card-entrance:nth-child(2) { animation-delay: 0.2s; }
    .woodash-card-entrance:nth-child(3) { animation-delay: 0.3s; }
    .woodash-card-entrance:nth-child(4) { animation-delay: 0.4s; }
    .woodash-card-entrance:nth-child(5) { animation-delay: 0.5s; }
    .woodash-card-entrance:nth-child(6) { animation-delay: 0.6s; }
    
    @keyframes woodashCardEntrance {
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .woodash-pulse-glow {
        animation: woodashPulseGlow 2s ease-in-out infinite;
    }
    
    @keyframes woodashPulseGlow {
        0%, 100% {
            box-shadow: 0 0 20px rgba(130, 77, 235, 0.3);
        }
        50% {
            box-shadow: 0 0 40px rgba(130, 77, 235, 0.6);
        }
    }
    
    .woodash-shimmer {
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        background-size: 200% 100%;
        animation: woodashShimmer 2s infinite;
    }
    
    @keyframes woodashShimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    
    .woodash-bounce-in {
        animation: woodashBounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    @keyframes woodashBounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .woodash-slide-up {
        animation: woodashSlideUp 0.5s ease-out forwards;
        opacity: 0;
        transform: translateY(30px);
    }
    
    @keyframes woodashSlideUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .woodash-rotate-in {
        animation: woodashRotateIn 0.6s ease-out forwards;
        opacity: 0;
        transform: rotate(-10deg) scale(0.8);
    }
    
    @keyframes woodashRotateIn {
        to {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }
    }
    
    /* Micro-interactions */
    .woodash-micro-interaction {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .woodash-micro-interaction:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .woodash-micro-interaction:active {
        transform: translateY(0);
        transition: all 0.1s;
    }
    
    /* Loading states */
    .woodash-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: woodashSkeleton 1.5s infinite;
    }
    
    @keyframes woodashSkeleton {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .dark-theme .woodash-skeleton {
        background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
        background-size: 200% 100%;
    }
    .woodash-gradient-text {
        @apply bg-clip-text text-transparent bg-gradient-to-r from-[#824DEB] to-[#814deb];
    }
    
    /* Dark Theme Styles */
    .dark-theme {
        @apply bg-gray-900 text-gray-100;
    }
    .dark-theme .woodash-main,
    .dark-theme .woodash-slideshow,
    .dark-theme .woodash-content,
    .dark-theme .woodash-card,
    .dark-theme .woodash-metric-card,
    .dark-theme .woodash-chart-container,
    .dark-theme .woodash-hover-card
    {
        @apply bg-gray-800 border-purple-700;
    }
    .dark-theme .darkmode-toggle4
    {
        @apply bg-gradient-to-r from-gray-800 to-gray-800;
    }
    .dark-theme .woodash-slideshow,
    .dark-theme .woodash-sidebar {
        @apply bg-gray-800 border-none;
    }
    
    .dark-theme .woodash-card:hover,
    .dark-theme .woodash-metric-card:hover,
    .dark-theme .woodash-chart-container:hover,
    .dark-theme .woodash-hover-card:hover {
        @apply bg-gray-700 border-purple-600;
    }
    
    .dark-theme .woodash-nav-link {
        @apply text-gray-300 hover:text-white hover:bg-gray-800;
    }
    
    .dark-theme .woodash-nav-link.active {
        @apply bg-purple-900 text-purple-300;
    }
    
    .dark-theme .woodash-btn-primary {
        @apply bg-purple-600 hover:bg-purple-700 text-white;
    }
    
    .dark-theme .woodash-btn-secondary {
        @apply bg-gray-700 text-gray-200 hover:bg-gray-600;
    }
    
    .dark-theme .woodash-table {
        @apply bg-gray-800 border-purple-700;
    }
    
    .dark-theme .woodash-table th {
        @apply bg-gray-700 text-gray-200 border-gray-600;
    }
    
    .dark-theme .woodash-table td {
        @apply border-gray-600 text-gray-300;
    }
    
    .dark-theme .woodash-table tr:hover {
        @apply bg-purple-700;
    }
    
    .dark-theme .woodash-dropdown {
        @apply bg-gray-800 border-gray-700 shadow-2xl;
    }
    
    .dark-theme .woodash-dropdown-item {
        @apply text-gray-200 hover:bg-gray-700;
    }
    
    .dark-theme .woodash-dropdown-item:hover {
        @apply text-white;
    }
    
    .dark-theme .woodash-modal {
        @apply bg-gray-800 border-gray-700;
    }
    
    .dark-theme .woodash-modal-overlay {
        @apply bg-black bg-opacity-70;
    }
    
    .dark-theme input,
    .dark-theme textarea,
    .dark-theme select {
        @apply bg-gray-700 border-gray-600 text-gray-200;
    }
    
    .dark-theme input:focus,
    .dark-theme textarea:focus,
    .dark-theme select:focus {
        @apply border-purple-500 ring-purple-500;
    }
    
    .dark-theme .woodash-ai-chat {
        @apply bg-gray-800 border-gray-700;
    }
    
    .dark-theme .woodash-ai-message {
        @apply bg-gray-700 text-gray-200;
    }
    
    .dark-theme .woodash-ai-message.user {
        @apply bg-purple-600 text-white;
    }
    
    .dark-theme .woodash-search-input {
        @apply bg-gray-700 border-gray-600 text-gray-200;
    }
    
    .dark-theme .woodash-search-results {
        @apply bg-gray-800 border-gray-700;
    }
    
    .dark-theme .woodash-search-result {
        @apply text-gray-200 hover:bg-gray-700;
    }
    
    .dark-theme .text-gray-500 {
        @apply text-gray-400;
    }
    
    .dark-theme .text-gray-600 {
        @apply text-gray-300;
    }
    
    .dark-theme .text-gray-700 {
        @apply text-gray-200;
    }
    
    .dark-theme .text-gray-800 {
        @apply text-gray-100;
    }
    
    .dark-theme .text-gray-900 {
        @apply text-white;
    }
    
    .dark-theme .bg-gray-50 {
        @apply bg-gray-800;
    }
    
    .dark-theme .bg-gray-100 {
        @apply bg-gray-700;
    }
    
    .dark-theme .bg-white {
        @apply bg-gray-800;
    }
    
    .dark-theme .border-gray-200 {
        @apply border-gray-700;
    }
    
    .dark-theme .border-gray-300 {
        @apply border-gray-600;
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
    
    /* AI Assistant Styles */
    .woodash-ai-container {
        @apply fixed bottom-6 right-6 z-50;
    }
    
    .woodash-ai-toggle {
        @apply w-14 h-14 bg-gradient-to-br from-[#824DEB] to-[#814deb] rounded-full flex items-center justify-center text-white shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer;
        animation: woodash-ai-pulse 2s infinite;
    }
    
    .woodash-ai-toggle:hover {
        @apply transform scale-110;
    }
    
    .woodash-ai-chat {
        @apply absolute bottom-16 right-0 w-80 h-96 bg-white rounded-2xl shadow-2xl border border-gray-100 flex flex-col overflow-hidden transform scale-0 opacity-0 transition-all duration-300;
        transform-origin: bottom right;
    }
    
    .woodash-ai-chat.active {
        @apply scale-100 opacity-100;
    }
    
    .woodash-ai-header {
        @apply bg-gradient-to-r from-[#824DEB] to-[#814deb] p-4 text-white flex items-center justify-between;
    }
    
    .woodash-ai-status {
        @apply flex items-center gap-2;
    }
    
    .woodash-ai-status-dot {
        @apply w-2 h-2 bg-purple-300 rounded-full animate-pulse;
    }
    
    .woodash-ai-messages {
        @apply flex-1 p-4 overflow-y-auto space-y-3;
        scrollbar-width: thin;
        scrollbar-color: #E2E8F0 #F8FAFC;
    }
    
    .woodash-ai-message {
        @apply flex gap-3 max-w-full;
    }
    
    .woodash-ai-message.user {
        @apply flex-row-reverse;
    }
    
    .woodash-ai-message-avatar {
        @apply w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0;
    }
    
    .woodash-ai-message.ai .woodash-ai-message-avatar {
        @apply bg-gradient-to-br from-[#824DEB] to-[#814deb] text-white;
    }
    
    .woodash-ai-message.user .woodash-ai-message-avatar {
        @apply bg-gray-100 text-gray-600;
    }
    
    .woodash-ai-message-content {
        @apply max-w-xs p-3 rounded-2xl text-sm;
    }
    
    .woodash-ai-message.ai .woodash-ai-message-content {
        @apply bg-gray-100 text-gray-800;
    }
    
    .woodash-ai-message.user .woodash-ai-message-content {
        @apply bg-[#824DEB] text-white;
    }
    
    .woodash-ai-input-container {
        @apply p-4 border-t border-gray-100 flex gap-2;
    }
    
    .woodash-ai-input {
        @apply flex-1 px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#824DEB] focus:border-transparent text-sm;
    }
    
    .woodash-ai-send {
        @apply w-8 h-8 bg-[#824DEB] text-white rounded-xl flex items-center justify-center hover:bg-[#814deb] transition-colors duration-200 cursor-pointer;
    }
    
    .woodash-ai-suggestions {
        @apply p-4 border-t border-gray-100 space-y-2;
    }
    
    .woodash-ai-suggestion {
        @apply text-xs text-[#824DEB] bg-[#824DEB] bg-opacity-10 px-3 py-2 rounded-lg cursor-pointer hover:bg-opacity-20 transition-all duration-200;
    }
    
    .woodash-ai-typing {
        @apply flex items-center gap-1 text-gray-500 text-xs p-3;
    }
    
    @keyframes woodash-ai-pulse {
        0%, 100% {
            box-shadow: 0 0 20px rgba(130, 77, 235, 0.3);
        }
        50% {
            box-shadow: 0 0 30px rgba(130, 77, 235, 0.5);
        }
    }
    
    .woodash-ai-insights {
        @apply bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 mb-6;
    }
    
    .woodash-ai-insight-item {
        @apply flex items-start gap-3 p-3 bg-white rounded-lg mb-3 last:mb-0 shadow-sm hover:shadow-md transition-shadow duration-200;
    }
    
    .woodash-ai-insight-icon {
        @apply w-8 h-8 rounded-lg flex items-center justify-center text-white flex-shrink-0;
    }
    
    .woodash-ai-insight-content {
        @apply flex-1;
    }
    
    .woodash-ai-insight-title {
        @apply font-medium text-gray-900 text-sm mb-1;
    }
    
    .woodash-ai-insight-description {
        @apply text-gray-600 text-xs;
    }
    
    .woodash-ai-quick-actions {
        @apply grid grid-cols-2 gap-3 p-4 bg-gray-50 rounded-xl;
    }
    
    .woodash-ai-quick-action {
        @apply flex flex-col items-center gap-2 p-3 bg-white rounded-lg border border-gray-200 hover:border-[#824DEB] transition-all duration-200 cursor-pointer hover:transform hover:scale-105;
    }
    
    .woodash-ai-quick-action-icon {
        @apply w-8 h-8 rounded-lg bg-gradient-to-br from-[#824DEB] to-[#814deb] text-white flex items-center justify-center;
    }
    
    .woodash-ai-quick-action-label {
        @apply text-xs font-medium text-gray-700 text-center;
    }
    
    .woodash-bg-pattern {
        background-color: #F5F5FF;
        background-image: 
            radial-gradient(at 40% 20%, hsla(250, 76%, 74%, 0.15) 0px, transparent 50%),
            radial-gradient(at 80% 0%, hsla(264, 100%, 56%, 0.15) 0px, transparent 50%),
            radial-gradient(at 0% 50%, hsla(270, 100%, 93%, 0.15) 0px, transparent 50%),
            radial-gradient(at 80% 50%, hsla(260, 100%, 76%, 0.15) 0px, transparent 50%),
            radial-gradient(at 0% 100%, hsla(252, 100%, 77%, 0.15) 0px, transparent 50%),
            radial-gradient(at 80% 100%, hsla(255, 100%, 70%, 0.15) 0px, transparent 50%),
            radial-gradient(at 0% 0%, hsla(248, 100%, 76%, 0.15) 0px, transparent 50%);
        position: relative;
        overflow: hidden;
    }
    .woodash-orb:hover {
        filter: blur(30px);
        opacity: 0.7;
        transform: scale(1.1);
    }
    .woodash-orb-1 {
        width: 400px;
        height: 400px;
        background: radial-gradient(circle at center, rgba(130, 77, 235, 0.2), transparent 70%);
        top: -150px;
        left: -150px;
    }
    .woodash-orb-2 {
        width: 500px;
        height: 500px;
        background: radial-gradient(circle at center, rgba(130, 77, 235, 0.15), transparent 70%);
        bottom: -200px;
        right: -200px;
    }
    .woodash-orb-3 {
        width: 350px;
        height: 350px;
        background: radial-gradient(circle at center, rgba(130, 77, 235, 0.1), transparent 70%);
        top: 40%;
        left: 60%;
        transform: translate(-50%, -50%);
    }
    .woodash-line {
        position: absolute;
        background: linear-gradient(90deg, transparent, rgba(130, 77, 235, 0.1), transparent);
        height: 2px;
        width: 100%;
        transition: all 0.3s ease;
    }
    .woodash-line:hover {
        height: 3px;
        background: linear-gradient(90deg, transparent, rgba(130, 77, 235, 0.2), transparent);
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
            rgba(130, 77, 235, 0.08) 0%, 
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
        background: rgba(130, 77, 235, 0.3);
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
    @keyframes woodash-float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
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
            box-shadow: 0 0 8px rgba(130, 77, 235, 0.3);
        }
        50% {
            box-shadow: 0 0 15px rgba(130, 77, 235, 0.5);
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
        background-color: #814ce4;
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
        @apply pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#814ce4] focus:border-transparent transition-all duration-300;
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
        @apply text-[#814ce4];
        transform: translateY(-50%) scale(1); /* Prevent icon scaling on input focus */
    }
    
    /* Style for the search button */
    .woodash-search-button {
        @apply flex items-center justify-center bg-transparent text-gray-600 hover:text-[#814ce4] transition-colors duration-200;
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
        background: rgba(130, 77, 235, 0.05);
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
        @apply w-6 h-6 border-2 border-gray-200 border-t-[#814ce4] rounded-full animate-spin mx-auto;
    }
    
    .woodash-search-category {
        @apply px-3 py-2 text-xs font-medium text-gray-500 bg-gray-50;
    }
    
    .woodash-search-shortcut {
        @apply ml-2 px-2 py-0.5 text-xs font-medium text-gray-400 bg-gray-100 rounded;
    }
    
    /* New feature styles */
    .woodash-weather-widget {
        @apply bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200;
    }
    
    .woodash-task-item {
        @apply flex items-center gap-3 p-3 bg-gray-50 rounded-lg transition-all duration-200;
    }
    
    .woodash-task-item:hover {
        @apply bg-gray-100;
    }
    
    .woodash-alert-critical {
        @apply bg-red-50 border-l-4 border-red-500;
    }
    
    .woodash-alert-warning {
        @apply bg-yellow-50 border-l-4 border-yellow-500;
    }
    
    .woodash-alert-info {
        @apply bg-blue-50 border-l-4 border-blue-500;
    }
    
    .woodash-live-indicator {
        @apply w-2 h-2 bg-purple-500 rounded-full animate-pulse;
    }
    
    .woodash-quick-action {
        @apply flex flex-col items-center gap-2 p-4 bg-white rounded-lg border border-gray-200 hover:border-[#814ce4] transition-all duration-200 cursor-pointer;
    }
    
    .woodash-quick-action:hover {
        @apply transform scale-105 shadow-md;
    }
    
    .woodash-metric-trend-up {
        @apply text-purple-600;
    }
    
    .woodash-metric-trend-down {
        @apply text-red-600;
    }
    
    .woodash-metric-trend-neutral {
        @apply text-gray-500;
    }
    
    /* Enhanced animations */
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
    
    @keyframes woodash-number-counter {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    /* Responsive improvements */
    @media (max-width: 640px) {
        .woodash-metric-card {
            @apply min-h-[140px];
        }
        
        .woodash-metric-value {
            @apply text-2xl;
        }
        
        .woodash-quick-action {
            @apply p-3;
        }
    }

    /* Modal Styles */
    .woodash-modal {
        @apply fixed inset-0 z-50 flex items-center justify-center p-4;
    }
    
    .woodash-modal.hidden {
        @apply invisible opacity-0;
    }
    
    .woodash-modal-overlay {
        @apply absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm;
        animation: fadeIn 0.3s ease-out;
    }
    
    .woodash-modal-content {
        @apply relative bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300;
        animation: modalSlideIn 0.3s ease-out;
    }
    
    .woodash-modal-header {
        @apply flex items-center justify-between p-6 border-b border-gray-100;
    }
    
    .woodash-modal-close {
        @apply p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200 text-gray-500 hover:text-gray-700;
    }
    
    .woodash-modal-footer {
        @apply flex items-center justify-end gap-3 p-6 border-t border-gray-100 bg-gray-50 rounded-b-xl;
    }
    
    .woodash-form-input {
        @apply block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#814ce4] focus:border-transparent transition-all duration-200;
    }
    
    .woodash-form-input:focus {
        box-shadow: 0 0 0 3px rgba(130, 77, 235, 0.1);
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes modalSlideIn {
        from { 
            opacity: 0; 
            transform: translateY(-20px) scale(0.95); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0) scale(1); 
        }
    }
    
    .woodash-modal:not(.hidden) .woodash-modal-content {
        animation: modalSlideIn 0.3s ease-out;
    }
    
    /* Page Transition Styles */
    .woodash-page-content {
        opacity: 0;
        transform: translateX(20px);
        transition: all 0.3s ease-in-out;
        display: none;
    }
    
    .woodash-page-content.active {
        opacity: 1;
        transform: translateX(0);
        display: block;
        animation: woodash-fade-in-up 0.5s ease-out;
    }
    
    .woodash-page-content.hidden {
        display: none;
    }
    
    /* Page fade animation */
    @keyframes woodash-page-fade-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .woodash-page-content.active > * {
        animation: woodash-page-fade-in 0.6s ease-out;
    }
    </style>
    
<div id="woodash-dashboard" class="woodash-fullscreen woodash-bg-pattern woodash-bg-animation">
    <div class="flex woodash-content">
        <!-- Sidebar -->
  <aside class="woodash-sidebar w-64 bg-white/90 border-r border-gray-100 p-6 woodash-glass-effect">
            <div class="flex items-center gap-3 mb-8 woodash-fade-in">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#814ce4] to-[#2d0873] flex items-center justify-center woodash-glow">
                    <i class="fa-solid fa-chart-line text-white text-xl"></i>
                </div>
                <h2 class="text-xl font-bold woodash-gradient-text">WooDash Pro</h2>
            </div>
            <nav class="space-y-1">
                <a href="#" class="woodash-nav-link active woodash-hover-card woodash-slide-up" style="animation-delay: 0.1s" data-page="dashboard">
                    <i class="fa-solid fa-gauge w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.2s" data-page="analytics">
                    <i class="fa-solid fa-chart-line w-5"></i>
                    <span>Analytics</span>
                    <span class="woodash-badge woodash-badge-success ml-auto woodash-pulse">New</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.3s" data-page="products">
                    <i class="fa-solid fa-box w-5"></i>
                    <span>Products</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.4s" data-page="orders">
                    <i class="fa-solid fa-shopping-cart w-5"></i>
                    <span>Orders</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.5s" data-page="customers">
                    <i class="fa-solid fa-users w-5"></i>
                    <span>Customers</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.6s" data-page="coupons">
                    <i class="fa-solid fa-ticket w-5"></i>
                    <span>Coupon</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.7s" data-page="inventory">
                    <i class="fa-solid fa-boxes-stacked w-5"></i>
                    <span>Inventory</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.6s" data-page="reviews">
                    <i class="fa-solid fa-star w-5"></i>
                    <span>Reviews</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.8s" data-page="reports">
                    <i class="fa-solid fa-file-chart-line w-5"></i>
                    <span>Reports</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 1.0s" data-page="integrations">
                    <i class="fa-solid fa-bolt w-5"></i>
                    <span>Integrations</span>
                </a>
                <a href="#" class="woodash-nav-link woodash-hover-card woodash-slide-up" style="animation-delay: 0.9s" data-page="settings">
                    <i class="fa-solid fa-gear w-5"></i>
                    <span>Settings</span>
                </a>
            </nav>
            <div class="absolute bottom-6 left-6 right-6">
                <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 woodash-hover-card woodash-fade-in">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#814ce4] to-[#2d0873] flex items-center justify-center font-semibold text-white woodash-glow">JD</div>
                <div>
                        <div class="font-medium text-gray-900">John Doe</div>
                        <a href="#" class="text-sm text-[#814ce4] hover:underline woodash-logout-btn">Logout</a>
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
                        <h1 class="text-2xl font-bold woodash-gradient-text" id="page-title" data-searchable="dashboard">Dashboard</h1>
                        <p class="text-gray-500" id="page-description" data-searchable="dashboard">Welcome back, John! Here's what's happening with your store.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button id="toggle-slideshow" class="woodash-btn woodash-btn-secondary woodash-hover-card">
                            <i class="fa-solid fa-eye"></i>
                            <span>Toggle Slideshow</span>
                        </button>
                        
                        <!-- Export Button -->
                        <div class="relative">
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="export-btn">
                                <i class="fa-solid fa-download"></i>
                                <span>Export</span>
                            </button>
                            <div class="woodash-dropdown hidden" id="export-dropdown">
                                <div class="woodash-dropdown-item" data-export="pdf">
                                    <i class="fa-solid fa-file-pdf text-red-500"></i>
                                    <span>Export as PDF</span>
                                </div>
                                <div class="woodash-dropdown-item" data-export="excel">
                                    <i class="fa-solid fa-file-excel text-purple-500"></i>
                                    <span>Export as Excel</span>
                                </div>
                                <div class="woodash-dropdown-item" data-export="csv">
                                    <i class="fa-solid fa-file-csv text-blue-500"></i>
                                    <span>Export as CSV</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Settings Button -->
                        <div class="relative">
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="settings-btn">
                                <i class="fa-solid fa-cog"></i>
                                <span>Settings</span>
                            </button>
                            <div class="woodash-dropdown hidden" id="settings-dropdown">
                                <div class="woodash-dropdown-item" id="theme-toggle">
                                    <i class="fa-solid fa-palette text-purple-500"></i>
                                    <span>Toggle Theme</span>
                                </div>
                                <div class="woodash-dropdown-item" id="refresh-data">
                                    <i class="fa-solid fa-refresh text-blue-500"></i>
                                    <span>Refresh Data</span>
                                </div>
                                <div class="woodash-dropdown-item" id="fullscreen-toggle">
                                    <i class="fa-solid fa-expand text-purple-500"></i>
                                    <span>Fullscreen</span>
                                </div>
                            </div>
                        </div>
                        
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
                                    <i class="fa-solid fa-circle-exclamation text-purple-500"></i>
                                    <span>New customer registered</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content Container -->
                <div id="page-content">
                    <!-- Dashboard Page -->
                    <div id="dashboard-page" class="woodash-page-content active">

                <!-- Slideshow Section -->
                <div id="slideshow-section" class="mb-8 relative overflow-hidden rounded-xl woodash-glass-effect">
                    <div class="woodash-slideshow relative h-[300px]" style="border:none !important">
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
                        <button class="woodash-slide-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-[#814ce4] transition-colors duration-200" data-slide="0"></button>
                        <button class="woodash-slide-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-[#814ce4] transition-colors duration-200" data-slide="1"></button>
                        <button class="woodash-slide-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-[#814ce4] transition-colors duration-200" data-slide="2"></button>
                    </div>

                    <!-- Slide Controls -->
                    <button class="woodash-slide-control woodash-slide-prev absolute left-4 top-1/2 transform -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white shadow-lg flex items-center justify-center text-gray-600 hover:text-[#814ce4] transition-all duration-200">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button class="woodash-slide-control woodash-slide-next absolute right-4 top-1/2 transform -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white shadow-lg flex items-center justify-center text-gray-600 hover:text-[#814ce4] transition-all duration-200">
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

                <!-- Extended Stat Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8 items-stretch">
                    <!-- Total Sales Card -->
                    <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="woodash-metric-title">
                                    <span>Total Sales</span> <span class="woodash-badge woodash-badge-success text-xs">Live</span>
                                </h3>
                                <div class="woodash-metric-value" id="total-sales">$0</div>
                                <div class="flex items-center gap-1 mt-1">
                                    <span class="text-sm text-purple-600 flex items-center gap-1">
                                        <i class="fa-solid fa-arrow-up text-xs"></i>
                                        <span>12.5%</span>
                                    </span>
                                    <span class="text-xs text-gray-500">vs last month</span>
                                </div>
                            </div>
                            <div class="woodash-metric-icon woodash-metric-purple">
                                <i class="fa-solid fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <canvas id="mini-trend-sales" height="40"></canvas>
                        </div>
                    </div>

                    <!-- Total Orders Card -->
                    <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="woodash-metric-title">
                                    <span>Total Orders</span>
                                    <span class="woodash-badge woodash-badge-warning text-xs" id="pending-orders">Processing : 0</span>
                                </h3>
                                <div class="woodash-metric-value" id="total-orders">0</div>
                                <div class="flex items-center gap-1 mt-1">
                                     <span class="text-sm text-purple-600 flex items-center gap-1">
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
                    <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
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
                    <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="woodash-metric-title flex items-center gap-2">
                                    <span>New Customers</span>
                                    <span class="woodash-badge woodash-badge-success text-xs">+15.3%</span>
                                </h3>
                                <div class="woodash-metric-value" id="new-customers">0</div>
                                <div class="flex items-center gap-1 mt-1">
                                    <span class="text-sm text-purple-600 flex items-center gap-1">
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

                    <!-- Total Products Card -->
                    <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="woodash-metric-title">
                                    <span>Total Products</span>
                                    <span class="woodash-badge woodash-badge-success text-xs" >+5</span>
                                </h3>
                                <div class="woodash-metric-value" id="total-products">245</div>
                                <div class="flex items-center gap-1 mt-1">
                                    <span class="text-sm text-blue-600 flex items-center gap-1">
                                        <i class="fa-solid fa-box text-xs"></i>
                                        <span>18 low stock</span>
                                    </span>
                                </div>
                            </div>
                            <div class="woodash-metric-icon woodash-metric-blue">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <canvas id="mini-trend-products" height="40"></canvas>
                        </div>
                    </div>

                </div>

                <!-- Sales Overview -->
                <div class="woodash-chart-container mb-12 woodash-hover-card woodash-glow h-[550px]">
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
                <!-- Top Products & Customers -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Top Products -->
                    <div class="woodash-chart-container woodash-hover-card woodash-glow" style="animation-delay: 0.5s, 
                    ">
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
                    <div class="woodash-chart-container woodash-hover-card woodash-glow" style="animation-delay: 0.6s">
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

            <!-- AI Insights Section -->
                <div class="darkmode-toggle4 woodash-ai-insights woodash-fade-in-up mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-brain text-blue-600"></i>
                            AI Insights & Recommendations
                        </h3>
                        <button class="woodash-btn woodash-btn-secondary text-xs" onclick="refreshAIInsights()">
                            <i class="fa-solid fa-refresh mr-1"></i>
                            Refresh
                        </button>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="woodash-ai-insight-item">
                            <div class="woodash-ai-insight-icon bg-gradient-to-br from-purple-500 to-purple-600">
                                <i class="fa-solid fa-chart-line text-sm"></i>
                            </div>
                            <div class="woodash-ai-insight-content">
                                <div class="woodash-ai-insight-title">Sales Performance Boost</div>
                                <div class="woodash-ai-insight-description">Your sales are up 12.5% this month. Consider running a flash sale on your top-performing electronics category to maximize momentum.</div>
                            </div>
                        </div>
                        
                        <div class="woodash-ai-insight-item">
                            <div class="woodash-ai-insight-icon bg-gradient-to-br from-orange-500 to-orange-600">
                                <i class="fa-solid fa-exclamation-triangle text-sm"></i>
                            </div>
                            <div class="woodash-ai-insight-content">
                                <div class="woodash-ai-insight-title">Inventory Alert</div>
                                <div class="woodash-ai-insight-description">3 products are running low on stock. Reorder "Wireless Headphones" and "Smart Watch" to avoid stockouts.</div>
                            </div>
                        </div>
                        
                        <div class="woodash-ai-insight-item">
                            <div class="woodash-ai-insight-icon bg-gradient-to-br from-blue-500 to-blue-600">
                                <i class="fa-solid fa-users text-sm"></i>
                            </div>
                            <div class="woodash-ai-insight-content">
                                <div class="woodash-ai-insight-title">Customer Behavior</div>
                                <div class="woodash-ai-insight-description">Mobile users show 23% higher conversion. Optimize your mobile checkout flow for better results.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Analytics & Goal Tracking -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Revenue by Category -->
                    <div class="woodash-chart-container woodash-hover-card woodash-glow h-[350x]">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Revenue by Category</h2>
                                <p class="text-gray-500 text-sm">Category performance breakdown</p>
                            </div>
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card text-xs">
                                <i class="fa-solid fa-chart-pie"></i>
                            </button>
                        </div>
                        <div class="h-[200px]">
                            <canvas id="revenue-category-chart"></canvas>
                        </div>
                    </div>

                    <!-- Goal Progress -->
                    <div class="woodash-chart-container woodash-hover-card woodash-glow h-[350x]">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Monthly Goal</h2>
                                <p class="text-gray-500 text-sm">Progress towards target</p>
                            </div>
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card text-xs">
                                <i class="fa-solid fa-target"></i>
                            </button>
                        </div>
                        <div class="text-center">
                            <div class="relative w-32 h-32 mx-auto mb-4">
                                <canvas id="goal-progress-chart" width="128" height="128"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold woodash-gradient-text">72%</div>
                                        <div class="text-xs text-gray-500">Complete</div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Current</span>
                                    <span class="font-medium">$72,450</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Target</span>
                                    <span class="font-medium">$100,000</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Real-time Metrics -->
                    <div class="woodash-chart-container woodash-hover-card woodash-glow h-[350x]">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Live Metrics</h2>
                                <p class="text-gray-500 text-sm">Real-time store data</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></div>
                                <span class="text-xs text-gray-500">Live</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="darkmode-toggle4 flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-users text-purple-600"></i>
                                    <span class="text-sm font-medium">Online Visitors</span>
                                </div>
                                <span class="text-lg font-bold text-purple-600" id="live-visitors">24</span>
                            </div>
                            <div class="darkmode-toggle4 flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-shopping-cart text-blue-600"></i>
                                    <span class="text-sm font-medium">Cart Additions</span>
                                </div>
                                <span class="text-lg font-bold text-blue-600" id="cart-additions">8</span>
                            </div>
                            <div class="darkmode-toggle4 flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-eye text-purple-600"></i>
                                    <span class="text-sm font-medium">Page Views</span>
                                </div>
                                <span class="text-lg font-bold text-purple-600" id="page-views">156</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Weather & Time Widget -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                    <div class="woodash-chart-container woodash-hover-card woodash-glow col-span-1 h-[200px]">
                        <div class="text-center">
                            <div class="flex items-center justify-center mb-4">
                                <i class="fa-solid fa-sun text-4xl text-yellow-500"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-1">22°C</div>
                            <div class="text-sm text-gray-500 mb-2">Sunny</div>
                            <div class="text-xs text-gray-400">New York</div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="woodash-chart-container woodash-hover-card woodash-glow col-span-3 h-[200px]">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Quick Actions</h2>
                                <p class="text-gray-500 text-sm">Frequently used tasks</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card flex flex-col items-center gap-2 p-4">
                                <i class="fa-solid fa-plus text-xl"></i>
                                <span class="text-sm">Add Product</span>
                            </button>
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card flex flex-col items-center gap-2 p-4">
                                <i class="fa-solid fa-file-invoice text-xl"></i>
                                <span class="text-sm">Create Order</span>
                            </button>
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card flex flex-col items-center gap-2 p-4">
                                <i class="fa-solid fa-user-plus text-xl"></i>
                                <span class="text-sm">Add Customer</span>
                            </button>
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card flex flex-col items-center gap-2 p-4">
                                <i class="fa-solid fa-percentage text-xl"></i>
                                <span class="text-sm">Create Coupon</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Inventory Alerts & Tasks -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 h-[350px]">
                    <!-- Inventory Alerts -->
                    <div class="woodash-chart-container woodash-hover-card woodash-glow">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Inventory Alerts</h2>
                                <p class="text-gray-500 text-sm">Items requiring attention</p>
                            </div>
                            <button class="woodash-btn woodash-btn-secondary woodash-hover-card text-xs">
                                <i class="fa-solid fa-warehouse"></i>
                            </button>
                        </div>
                        <div class="space-y-3">
                            <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-exclamation-triangle text-red-500"></i>
                                    <div>
                                        <p class="font-medium text-red-800">Wireless Headphones</p>
                                        <p class="text-sm text-red-600">Only 2 left in stock</p>
                                    </div>
                                </div>
                                <button class="woodash-btn woodash-btn-primary text-xs px-3 py-1">Restock</button>
                            </div>
                            <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-exclamation-triangle text-yellow-500"></i>
                                    <div>
                                        <p class="font-medium text-yellow-800">Gaming Mouse</p>
                                        <p class="text-sm text-yellow-600">5 left in stock</p>
                                    </div>
                                </div>
                                <button class="woodash-btn woodash-btn-primary text-xs px-3 py-1">Restock</button>
                            </div>
                            <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-orange-50 border-l-4 border-orange-500 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-box text-orange-500"></i>
                                    <div>
                                        <p class="font-medium text-orange-800">USB Cable</p>
                                        <p class="text-sm text-orange-600">Expected delivery: 2 days</p>
                                    </div>
                                </div>
                                <button class="woodash-btn woodash-btn-secondary text-xs px-3 py-1">Track</button>
                            </div>
                        </div>
                    </div>

                    <!-- Task Manager -->
                    <div class="woodash-chart-container woodash-hover-card woodash-glow h-[350px]">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-bold woodash-gradient-text">Today's Tasks</h2>
                                <p class="text-gray-500 text-sm">Things to complete today</p>
                            </div>
                            <button class="woodash-btn woodash-btn-primary text-xs" id="add-task-btn">
                                <i class="fa-solid fa-plus"></i>
                                Add Task
                            </button>
                        </div>
                        <div class="space-y-3" id="task-list">
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <input type="checkbox" class="w-4 h-4 text-[#814ce4] rounded focus:ring-[#814ce4]">
                                <span class="flex-1 text-sm">Update product descriptions</span>
                                <span class="text-xs text-gray-500">High</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <input type="checkbox" class="w-4 h-4 text-[#814ce4] rounded focus:ring-[#814ce4]" checked>
                                <span class="flex-1 text-sm line-through text-gray-500">Process pending orders</span>
                                <span class="text-xs text-gray-500">Medium</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <input type="checkbox" class="w-4 h-4 text-[#814ce4] rounded focus:ring-[#814ce4]">
                                <span class="flex-1 text-sm">Reply to customer emails</span>
                                <span class="text-xs text-gray-500">Low</span>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-500">1 of 3 tasks completed</p>
                            <div class="woodash-progress mt-2">
                                <div class="woodash-progress-bar" style="width: 33.33%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Recent Activity -->
                    <div class="woodash-chart-container woodash-hover-card woodash-glow h-[450px]">
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
                            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-purple-600 transition-colors duration-200 woodash-fade-in">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 woodash-float">
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
                            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-purple-600 transition-colors duration-200 woodash-fade-in">
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
                            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-purple-600 transition-colors duration-200 woodash-fade-in">
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
                            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-purple-600 transition-colors duration-200 woodash-fade-in">
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
                    <div class="woodash-chart-container woodash-hover-card woodash-glow h-[450px]">
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
                                    <span class="text-sm font-medium text-purple-600">+2.4%</span>
                                </div>
                                <div class="woodash-progress">
                                    <div class="woodash-progress-bar woodash-shimmer" style="width: 65%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">65% of visitors converted to customers</p>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-medium text-gray-700">Customer Satisfaction</p>
                                    <span class="text-sm font-medium text-purple-600">4.8/5</span>
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
                                    <span class="text-sm font-medium text-purple-600">4.5x</span>
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
                    <!-- End Dashboard Page -->

                    <!-- Analytics Page -->
                    <div id="analytics-page" class="woodash-page-content hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 h-[550px]">
                            <!-- Advanced Analytics Chart -->
                            <div class="woodash-chart-container woodash-hover-card woodash-glow lg:col-span-2">
                                <div class="flex justify-between items-center mb-6">
                                    <div>
                                        <h2 class="text-lg font-bold woodash-gradient-text">Advanced Analytics</h2>
                                        <p class="text-gray-500 text-sm">Detailed performance metrics and trends</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="woodash-btn woodash-btn-primary" data-range="today">Today</button>
                                        <button class="woodash-btn woodash-btn-secondary" data-range="week">Week</button>
                                        <button class="woodash-btn woodash-btn-secondary" data-range="month">Month</button>
                                        <button class="woodash-btn woodash-btn-secondary" data-range="year">Year</button>
                                    </div>
                                </div>
                                <div class="h-[400px]">
                                    <canvas id="advanced-analytics-chart"></canvas>
                                </div>
                            </div>

                            <!-- Revenue Breakdown -->
                            <div class="woodash-chart-container woodash-hover-card woodash-glow">
                                <div class="flex justify-between items-center mb-6">
                                    <div>
                                        <h2 class="text-lg font-bold woodash-gradient-text">Revenue Breakdown</h2>
                                        <p class="text-gray-500 text-sm">Revenue by source</p>
                                    </div>
                                </div>
                                <div class="h-[300px]">
                                    <canvas id="revenue-breakdown-chart"></canvas>
                                </div>
                            </div>

                            <!-- Conversion Funnel -->
                            <div class="woodash-chart-container woodash-hover-card woodash-glow">
                                <div class="flex justify-between items-center mb-6">
                                    <div>
                                        <h2 class="text-lg font-bold woodash-gradient-text">Conversion Funnel</h2>
                                        <p class="text-gray-500 text-sm">Customer journey analysis</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="darkmode-toggle4 flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                                        <span class="font-medium">Visitors</span>
                                        <span class="text-xl font-bold">12,456</span>
                                    </div>
                                    <div class="darkmode-toggle4 flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                                        <span class="font-medium">Product Views</span>
                                        <span class="text-xl font-bold">8,234</span>
                                    </div>
                                    <div class="darkmode-toggle4 flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                                        <span class="font-medium">Add to Cart</span>
                                        <span class="text-xl font-bold">2,156</span>
                                    </div>
                                    <div class="darkmode-toggle4 flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                                        <span class="font-medium">Checkout</span>
                                        <span class="text-xl font-bold">1,234</span>
                                    </div>
                                    <div class="darkmode-toggle4 flex items-center justify-between p-4 bg-purple-50 rounded-lg flex items-center justify-between p-4 bg-red-50 rounded-lg">
                                        <span class="font-medium">Purchase</span>
                                        <span class="text-xl font-bold">856</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Analytics Page -->

                    <!-- Products Page -->
                    <div id="products-page" class="woodash-page-content hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                            <!-- Product Stats -->
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">Total Products</h3>
                                        <div class="woodash-metric-value">245</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-blue">
                                        <i class="fa-solid fa-box"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">Low Stock</h3>
                                        <div class="woodash-metric-value text-orange-600">18</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-orange">
                                        <i class="fa-solid fa-exclamation-triangle"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">Categories</h3>
                                        <div class="woodash-metric-value">12</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-purple">
                                        <i class="fa-solid fa-tags"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Management -->
                        <div class="woodash-chart-container woodash-hover-card woodash-glow">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                            <h2 class="text-lg font-bold woodash-gradient-text" data-searchable="product">Product Management</h2>
                            <p class="text-gray-500 text-sm" data-searchable="product">Manage your products and inventory</p>
                                </div>
                                <button id="openProductModal" class="woodash-btn woodash-btn-primary" onclick="document.getElementById('addProductModal').style.display = 'flex';">
                                    <i class="fa-solid fa-plus mr-2"></i>
                                    Add Product
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="woodash-table w-full">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th>Category</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-gray-200 rounded-lg"></div>
                                                    <span class="font-medium">Wireless Headphones</span>
                                                </div>
                                            </td>
                                            <td>WH-001</td>
                                            <td>Electronics</td>
                                            <td><span class="woodash-badge woodash-badge-danger">2</span></td>
                                            <td>$89.99</td>
                                            <td><span class="woodash-badge woodash-badge-success">Active</span></td>
                                            <td>
                                                <div class="flex gap-2">
                                                    <button class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-edit"></i></button>
                                                    <button class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-gray-200 rounded-lg"></div>
                                                    <span class="font-medium">Smart Watch</span>
                                                </div>
                                            </td>
                                            <td>SW-002</td>
                                            <td>Electronics</td>
                                            <td><span class="woodash-badge woodash-badge-success">45</span></td>
                                            <td>$199.99</td>
                                            <td><span class="woodash-badge woodash-badge-success">Active</span></td>
                                            <td>
                                                <div class="flex gap-2">
                                                    <button class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-edit"></i></button>
                                                    <button class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Products Page -->

                    <!-- Orders Page -->
                    <div id="orders-page" class="woodash-page-content hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                            <!-- Order Stats -->
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">Total Orders</h3>
                                        <div class="woodash-metric-value">1,234</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-blue">
                                        <i class="fa-solid fa-shopping-cart"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">Pending</h3>
                                        <div class="woodash-metric-value text-yellow-600">23</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-orange">
                                        <i class="fa-solid fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">Processing</h3>
                                        <div class="woodash-metric-value text-blue-600">45</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-blue">
                                        <i class="fa-solid fa-cog"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">Completed</h3>
                                        <div class="woodash-metric-value text-purple-600">1,166</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-purple">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Orders Management -->
                        <div class="woodash-chart-container woodash-hover-card woodash-glow">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h2 class="text-lg font-bold woodash-gradient-text">Recent Orders</h2>
                                    <p class="text-gray-500 text-sm">Manage and track your orders</p>
                                </div>
                                <div class="flex gap-2">
                                    <select class="woodash-btn woodash-btn-secondary">
                                        <option>All Orders</option>
                                        <option>Pending</option>
                                        <option>Processing</option>
                                        <option>Completed</option>
                                    </select>
                                    <button class="woodash-btn woodash-btn-primary">
                                        <i class="fa-solid fa-plus mr-2"></i>
                                        New Order
                                    </button>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="woodash-table w-full">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#12345</td>
                                            <td>John Doe</td>
                                            <td>2024-01-15</td>
                                            <td>$299.99</td>
                                            <td><span class="woodash-badge woodash-badge-warning">Processing</span></td>
                                            <td>
                                                <div class="flex gap-2">
                                                    <button class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-eye"></i></button>
                                                    <button class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-edit"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#12344</td>
                                            <td>Jane Smith</td>
                                            <td>2024-01-14</td>
                                            <td>$150.50</td>
                                            <td><span class="woodash-badge woodash-badge-success">Completed</span></td>
                                            <td>
                                                <div class="flex gap-2">
                                                    <button class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-eye"></i></button>
                                                    <button class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-edit"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Orders Page -->

                    <!-- Customers Page -->
                    <div id="customers-page" class="woodash-page-content hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                            <!-- Customer Stats -->
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">Total Customers</h3>
                                        <div class="woodash-metric-value">2,547</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-blue">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">New This Month</h3>
                                        <div class="woodash-metric-value text-purple-600">42</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-purple">
                                        <i class="fa-solid fa-user-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">VIP Customers</h3>
                                        <div class="woodash-metric-value text-purple-600">156</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-purple">
                                        <i class="fa-solid fa-crown"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">Retention Rate</h3>
                                        <div class="woodash-metric-value text-blue-600">78%</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-blue">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Management -->
                        <div class="woodash-chart-container woodash-hover-card woodash-glow">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h2 class="text-lg font-bold woodash-gradient-text">Customer Management</h2>
                                    <p class="text-gray-500 text-sm">View and manage your customers</p>
                                </div>
                                <button class="woodash-btn woodash-btn-primary">
                                    <i class="fa-solid fa-user-plus mr-2"></i>
                                    Add Customer
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="woodash-table w-full">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Email</th>
                                            <th>Orders</th>
                                            <th>Total Spent</th>
                                            <th>Last Order</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">JD</div>
                                                    <span class="font-medium">John Doe</span>
                                                </div>
                                            </td>
                                            <td>john@example.com</td>
                                            <td>12</td>
                                            <td>$1,299.99</td>
                                            <td>2024-01-15</td>
                                            <td><span class="woodash-badge woodash-badge-success">Active</span></td>
                                            <td>
                                                <div class="flex gap-2">
                                                    <button class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-eye"></i></button>
                                                    <button class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-edit"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-pink-500 rounded-full flex items-center justify-center text-white font-semibold">JS</div>
                                                    <span class="font-medium">Jane Smith</span>
                                                </div>
                                            </td>
                                            <td>jane@example.com</td>
                                            <td>8</td>
                                            <td>$890.50</td>
                                            <td>2024-01-10</td>
                                            <td><span class="woodash-badge woodash-badge-success">Active</span></td>
                                            <td>
                                                <div class="flex gap-2">
                                                    <button class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-eye"></i></button>
                                                    <button class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-edit"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Customers Page -->

                    <!-- Inventory Page -->
                    <div id="inventory-page" class="woodash-page-content hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                            <!-- Inventory Stats -->
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">Total SKUs</h3>
                                        <div class="woodash-metric-value">1,456</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-blue">
                                        <i class="fa-solid fa-boxes-stacked"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">Low Stock</h3>
                                        <div class="woodash-metric-value text-red-600">18</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-red">
                                        <i class="fa-solid fa-exclamation-triangle"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">Out of Stock</h3>
                                        <div class="woodash-metric-value text-red-600">5</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-red">
                                        <i class="fa-solid fa-ban"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="woodash-metric-title">Overstock</h3>
                                        <div class="woodash-metric-value text-orange-600">23</div>
                                    </div>
                                    <div class="woodash-metric-icon woodash-metric-orange">
                                        <i class="fa-solid fa-warehouse"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Management -->
                        <div class="woodash-chart-container woodash-hover-card woodash-glow">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h2 class="text-lg font-bold woodash-gradient-text">Inventory Management</h2>
                                    <p class="text-gray-500 text-sm">Monitor and manage your inventory levels</p>
                                </div>
                                <div class="flex gap-2">
                                    <button class="woodash-btn woodash-btn-secondary">
                                        <i class="fa-solid fa-download mr-2"></i>
                                        Export
                                    </button>
                                    <button class="woodash-btn woodash-btn-primary">
                                        <i class="fa-solid fa-plus mr-2"></i>
                                        Stock Adjustment
                                    </button>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="woodash-table w-full">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th>Current Stock</th>
                                            <th>Reserved</th>
                                            <th>Available</th>
                                            <th>Reorder Level</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-gray-200 rounded-lg"></div>
                                                    <span class="font-medium">Wireless Headphones</span>
                                                </div>
                                            </td>
                                            <td>WH-001</td>
                                            <td>2</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>10</td>
                                            <td><span class="woodash-badge woodash-badge-danger">Low Stock</span></td>
                                            <td>
                                                <div class="flex gap-2">
                                                    <button class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-plus"></i></button>
                                                    <button class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-edit"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-gray-200 rounded-lg"></div>
                                                    <span class="font-medium">Smart Watch</span>
                                                </div>
                                            </td>
                                            <td>SW-002</td>
                                            <td>45</td>
                                            <td>8</td>
                                            <td>37</td>
                                            <td>5</td>
                                            <td><span class="woodash-badge woodash-badge-success">In Stock</span></td>
                                            <td>
                                                <div class="flex gap-2">
                                                    <button class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-plus"></i></button>
                                                    <button class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-edit"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Inventory Page -->
                    <!-- Coupons Page -->
<div id="coupons-page" class="woodash-page-content hidden">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <!-- Coupon Stats -->
        <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="woodash-metric-title">Total Coupons</h3>
                    <div class="woodash-metric-value">47</div>
                </div>
                <div class="woodash-metric-icon woodash-metric-blue">
                    <i class="fa-solid fa-ticket"></i>
                </div>
            </div>
        </div>
        <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="woodash-metric-title">Active Coupons</h3>
                    <div class="woodash-metric-value text-green-600">32</div>
                </div>
                <div class="woodash-metric-icon woodash-metric-green">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="woodash-metric-title">Used This Month</h3>
                    <div class="woodash-metric-value text-purple-600">156</div>
                </div>
                <div class="woodash-metric-icon woodash-metric-purple">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
        </div>
        <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="woodash-metric-title">Total Savings</h3>
                    <div class="woodash-metric-value text-orange-600">$2,847</div>
                </div>
                <div class="woodash-metric-icon woodash-metric-orange">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Coupon Performance Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Coupon Usage Chart -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow h-[350px]">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Coupon Usage Trends</h2>
                    <p class="text-gray-500 text-sm">Monthly coupon redemption statistics</p>
                </div>
                <button class="woodash-btn woodash-btn-secondary woodash-hover-card text-xs">
                    <i class="fa-solid fa-chart-bar"></i>
                </button>
            </div>
            <div class="h-[250px]">
                <canvas id="coupon-usage-chart" ></canvas>
            </div>
        </div>

        <!-- Top Performing Coupons -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow h-[350px]">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Top Performing Coupons</h2>
                    <p class="text-gray-500 text-sm">Most used coupons this month</p>
                </div>
                <button class="woodash-btn woodash-btn-secondary woodash-hover-card text-xs">
                    <i class="fa-solid fa-trophy"></i>
                </button>
            </div>
            <div class="space-y-3">
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg border-l-4 border-purple-500">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold">1</div>
                        <div>
                            <p class="font-medium text-gray-900">SAVE20</p>
                            <p class="text-sm text-gray-600">20% off orders over $100</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-purple-600">89 uses</p>
                        <p class="text-sm text-gray-500">$1,245 saved</p>
                    </div>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border-l-4 border-blue-500">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">2</div>
                        <div>
                            <p class="font-medium text-gray-900">WELCOME10</p>
                            <p class="text-sm text-gray-600">$10 off first order</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-blue-600">67 uses</p>
                        <p class="text-sm text-gray-500">$670 saved</p>
                    </div>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-lg border-l-4 border-orange-500">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white text-sm font-bold">3</div>
                        <div>
                            <p class="font-medium text-gray-900">FREESHIP</p>
                            <p class="text-sm text-gray-600">Free shipping on all orders</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-orange-600">45 uses</p>
                        <p class="text-sm text-gray-500">$450 saved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Coupon Management -->
    <div class="woodash-chart-container hover:bg-purple-600 woodash-glow">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-lg font-bold woodash-gradient-text">Coupon Management</h2>
                <p class="text-gray-500 text-sm">Create, edit, and manage your discount coupons</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <select class="woodash-btn woodash-btn-secondary text-sm">
                    <option>All Coupons</option>
                    <option>Active</option>
                    <option>Expired</option>
                    <option>Scheduled</option>
                </select>
                <button class="woodash-btn woodash-btn-secondary text-sm">
                    <i class="fa-solid fa-download mr-2"></i>
                    Export
                </button>
                <button class="woodash-btn woodash-btn-primary" id="create-coupon-btn">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Create Coupon
                </button>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="darkmode-toggle4 grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 p-4 bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg">
            <button class="woodash-btn woodash-btn-secondary woodash-hover-card flex flex-col items-center gap-2 p-4">
                <i class="fa-solid fa-percentage text-xl text-purple-600"></i>
                <span class="text-sm">Percentage Discount</span>
            </button>
            <button class="woodash-btn woodash-btn-secondary woodash-hover-card flex flex-col items-center gap-2 p-4">
                <i class="fa-solid fa-dollar-sign text-xl text-green-600"></i>
                <span class="text-sm">Fixed Amount</span>
            </button>
            <button class="woodash-btn woodash-btn-secondary woodash-hover-card flex flex-col items-center gap-2 p-4">
                <i class="fa-solid fa-truck text-xl text-blue-600"></i>
                <span class="text-sm">Free Shipping</span>
            </button>
            <button class="woodash-btn woodash-btn-secondary woodash-hover-card flex flex-col items-center gap-2 p-4">
                <i class="fa-solid fa-gift text-xl text-orange-600"></i>
                <span class="text-sm">BOGO Deal</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="woodash-table w-full">
                <thead>
                    <tr>
                        <th>Coupon Code</th>
                        <th>Type</th>
                        <th>Discount</th>
                        <th>Usage</th>
                        <th>Valid Until</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-ticket text-purple-600"></i>
                                </div>
                                <div>
                                    <span class="font-medium">SAVE20</span>
                                    <p class="text-xs text-gray-500">20% off orders over $100</p>
                                </div>
                            </div>
                        </td>
                        <td><span class="woodash-badge woodash-badge-purple">Percentage</span></td>
                        <td>20%</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium">89/100</span>
                                <div class="woodash-progress w-16">
                                    <div class="woodash-progress-bar" style="width: 89%"></div>
                                </div>
                            </div>
                        </td>
                        <td>2024-02-28</td>
                        <td><span class="woodash-badge woodash-badge-success">Active</span></td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fa-solid fa-edit"></i></button>
                                <button class="text-purple-600 hover:text-purple-800" title="Duplicate"><i class="fa-solid fa-copy"></i></button>
                                <button class="text-orange-600 hover:text-orange-800" title="Pause"><i class="fa-solid fa-pause"></i></button>
                                <button class="text-red-600 hover:text-red-800" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-dollar-sign text-green-600"></i>
                                </div>
                                <div>
                                    <span class="font-medium">WELCOME10</span>
                                    <p class="text-xs text-gray-500">$10 off first order</p>
                                </div>
                            </div>
                        </td>
                        <td><span class="woodash-badge woodash-badge-green">Fixed Amount</span></td>
                        <td>$10</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium">67/∞</span>
                                <div class="woodash-progress w-16">
                                    <div class="woodash-progress-bar bg-green-500" style="width: 100%"></div>
                                </div>
                            </div>
                        </td>
                        <td>2024-12-31</td>
                        <td><span class="woodash-badge woodash-badge-success">Active</span></td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fa-solid fa-edit"></i></button>
                                <button class="text-purple-600 hover:text-purple-800" title="Duplicate"><i class="fa-solid fa-copy"></i></button>
                                <button class="text-orange-600 hover:text-orange-800" title="Pause"><i class="fa-solid fa-pause"></i></button>
                                <button class="text-red-600 hover:text-red-800" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-truck text-blue-600"></i>
                                </div>
                                <div>
                                    <span class="font-medium">FREESHIP</span>
                                    <p class="text-xs text-gray-500">Free shipping on all orders</p>
                                </div>
                            </div>
                        </td>
                        <td><span class="woodash-badge woodash-badge-blue">Free Shipping</span></td>
                        <td>100%</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium">45/50</span>
                                <div class="woodash-progress w-16">
                                    <div class="woodash-progress-bar bg-blue-500" style="width: 90%"></div>
                                </div>
                            </div>
                        </td>
                        <td>2024-03-15</td>
                        <td><span class="woodash-badge woodash-badge-success">Active</span></td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fa-solid fa-edit"></i></button>
                                <button class="text-purple-600 hover:text-purple-800" title="Duplicate"><i class="fa-solid fa-copy"></i></button>
                                <button class="text-orange-600 hover:text-orange-800" title="Pause"><i class="fa-solid fa-pause"></i></button>
                                <button class="text-red-600 hover:text-red-800" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-gift text-orange-600"></i>
                                </div>
                                <div>
                                    <span class="font-medium">BOGO50</span>
                                    <p class="text-xs text-gray-500">Buy one get one 50% off</p>
                                </div>
                            </div>
                        </td>
                        <td><span class="woodash-badge woodash-badge-orange">BOGO</span></td>
                        <td>50%</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium">23/25</span>
                                <div class="woodash-progress w-16">
                                    <div class="woodash-progress-bar bg-orange-500" style="width: 92%"></div>
                                </div>
                            </div>
                        </td>
                        <td>2024-01-31</td>
                        <td><span class="woodash-badge woodash-badge-danger">Expired</span></td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fa-solid fa-edit"></i></button>
                                <button class="text-purple-600 hover:text-purple-800" title="Duplicate"><i class="fa-solid fa-copy"></i></button>
                                <button class="text-green-600 hover:text-green-800" title="Renew"><i class="fa-solid fa-refresh"></i></button>
                                <button class="text-red-600 hover:text-red-800" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-calendar text-yellow-600"></i>
                                </div>
                                <div>
                                    <span class="font-medium">SUMMER25</span>
                                    <p class="text-xs text-gray-500">25% off summer collection</p>
                                </div>
                            </div>
                        </td>
                        <td><span class="woodash-badge woodash-badge-purple">Percentage</span></td>
                        <td>25%</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium">0/100</span>
                                <div class="woodash-progress w-16">
                                    <div class="woodash-progress-bar bg-gray-300" style="width: 0%"></div>
                                </div>
                            </div>
                        </td>
                        <td>2024-06-30</td>
                        <td><span class="woodash-badge woodash-badge-warning">Scheduled</span></td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fa-solid fa-edit"></i></button>
                                <button class="text-purple-600 hover:text-purple-800" title="Duplicate"><i class="fa-solid fa-copy"></i></button>
                                <button class="text-green-600 hover:text-green-800" title="Activate Now"><i class="fa-solid fa-play"></i></button>
                                <button class="text-red-600 hover:text-red-800" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
            <div class="text-sm text-gray-500">
                Showing 1 to 5 of 47 coupons
            </div>
            <div class="flex gap-2">
                <button class="woodash-btn woodash-btn-secondary text-sm" disabled>
                    <i class="fa-solid fa-chevron-left mr-1"></i>
                    Previous
                </button>
                <button class="woodash-btn woodash-btn-primary text-sm">1</button>
                <button class="woodash-btn woodash-btn-secondary text-sm">2</button>
                <button class="woodash-btn woodash-btn-secondary text-sm">3</button>
                <button class="woodash-btn woodash-btn-secondary text-sm">
                    Next
                    <i class="fa-solid fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Coupon Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- Coupon Conversion Rates -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow h-[400px]">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Conversion Rates</h2>
                    <p class="text-gray-500 text-sm">How coupons affect purchase decisions</p>
                </div>
            </div>
            <div class="space-y-4">
                <div class="darkmode-toggle4 flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-eye text-purple-600"></i>
                        <span class="font-medium">Coupon Views</span>
                    </div>
                    <span class="text-xl font-bold text-purple-600">1,234</span>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-mouse-pointer text-blue-600"></i>
                        <span class="font-medium">Coupon Clicks</span>
                    </div>
                    <span class="text-xl font-bold text-blue-600">856</span>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-shopping-cart text-green-600"></i>
                        <span class="font-medium">Coupon Uses</span>
                    </div>
                    <span class="text-xl font-bold text-green-600">234</span>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-4 bg-orange-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-percentage text-orange-600"></i>
                        <span class="font-medium">Conversion Rate</span>
                    </div>
                    <span class="text-xl font-bold text-orange-600">27.3%</span>
                </div>
            </div>
        </div>

        <!-- Recent Coupon Activity -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow h-[400px]">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Recent Activity</h2>
                    <p class="text-gray-500 text-sm">Latest coupon usage and events</p>
                </div>
            </div>
            <div class="space-y-3 woodash-scrollbar" style="max-height: 320px; overflow-y: auto;">
                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-purple-600 transition-colors duration-200">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-gray-900">SAVE20 used</p>
                            <span class="text-sm text-gray-500">2m ago</span>
                        </div>
                        <p class="text-sm text-gray-500">John Doe saved $25.99 on order #1234</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-purple-600 transition-colors duration-200">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-gray-900">New coupon created</p>
                            <span class="text-sm text-gray-500">15m ago</span>
                        </div>
                        <p class="text-sm text-gray-500">FLASH30 - 30% off flash sale items</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-purple-600 transition-colors duration-200">
                    <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-gray-900">Coupon limit reached</p>
                            <span class="text-sm text-gray-500">1h ago</span>
                        </div>
                        <p class="text-sm text-gray-500">FREESHIP has reached its usage limit</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-purple-600 transition-colors duration-200">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-gray-900">Coupon expired</p>
                            <span class="text-sm text-gray-500">2h ago</span>
                        </div>
                        <p class="text-sm text-gray-500">BOGO50 has expired and is now inactive</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Coupons Page -->

                    <!-- Reports Page -->
                    <div id="reports-page" class="woodash-page-content hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                            <!-- Quick Report Cards -->
                            <div class="woodash-chart-container woodash-hover-card woodash-glow cursor-pointer h-[220px]">
                                <div class="text-center">
                                    <i class="fa-solid fa-chart-line text-4xl text-purple-600 mb-4"></i>
                                    <h3 class="text-lg font-bold mb-2">Sales Report</h3>
                                    <p class="text-gray-500 text-sm mb-4">Comprehensive sales analysis</p>
                                    <button class="woodash-btn woodash-btn-primary">Generate Report</button>
                                </div>
                            </div>
                            <div class="woodash-chart-container woodash-hover-card woodash-glow cursor-pointer h-[220px]">
                                <div class="text-center">
                                    <i class="fa-solid fa-users text-4xl text-purple-600 mb-4"></i>
                                    <h3 class="text-lg font-bold mb-2">Customer Report</h3>
                                    <p class="text-gray-500 text-sm mb-4">Customer behavior analysis</p>
                                    <button class="woodash-btn woodash-btn-primary">Generate Report</button>
                                </div>
                            </div>
                            <div class="woodash-chart-container woodash-hover-card woodash-glow cursor-pointer h-[220px]">
                                <div class="text-center">
                                    <i class="fa-solid fa-box text-4xl text-purple-600 mb-4"></i>
                                    <h3 class="text-lg font-bold mb-2">Inventory Report</h3>
                                    <p class="text-gray-500 text-sm mb-4">Stock and inventory analysis</p>
                                    <button class="woodash-btn woodash-btn-primary">Generate Report</button>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Reports -->
                        <div class="woodash-chart-container woodash-hover-card woodash-glow">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h2 class="text-lg font-bold woodash-gradient-text">Recent Reports</h2>
                                    <p class="text-gray-500 text-sm">Your generated reports and analytics</p>
                                </div>
                                <button class="woodash-btn woodash-btn-primary">
                                    <i class="fa-solid fa-plus mr-2"></i>
                                    New Report
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-file-pdf text-blue-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium">Monthly Sales Report</h4>
                                            <p class="text-sm text-gray-500">Generated on Jan 15, 2024</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="woodash-btn woodash-btn-secondary text-sm">
                                            <i class="fa-solid fa-download mr-1"></i>
                                            Download
                                        </button>
                                        <button class="woodash-btn woodash-btn-secondary text-sm">
                                            <i class="fa-solid fa-eye mr-1"></i>
                                            View
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-file-excel text-purple-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium">Customer Analytics</h4>
                                            <p class="text-sm text-gray-500">Generated on Jan 10, 2024</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="woodash-btn woodash-btn-secondary text-sm">
                                            <i class="fa-solid fa-download mr-1"></i>
                                            Download
                                        </button>
                                        <button class="woodash-btn woodash-btn-secondary text-sm">
                                            <i class="fa-solid fa-eye mr-1"></i>
                                            View
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Reports Page -->

<!-- Add this page content after the coupons page (around line 1800) -->
<!-- Customer Reviews Page -->
<div id="reviews-page" class="woodash-page-content hidden">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <!-- Review Stats -->
        <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="woodash-metric-title">Total Reviews</h3>
                    <div class="woodash-metric-value">1,247</div>
                </div>
                <div class="woodash-metric-icon woodash-metric-blue">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>
        </div>
        <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="woodash-metric-title">Average Rating</h3>
                    <div class="woodash-metric-value text-yellow-600">4.6</div>
                </div>
                <div class="woodash-metric-icon woodash-metric-orange">
                    <i class="fa-solid fa-star-half-stroke"></i>
                </div>
            </div>
        </div>
        <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="woodash-metric-title">This Month</h3>
                    <div class="woodash-metric-value text-green-600">89</div>
                </div>
                <div class="woodash-metric-icon woodash-metric-green">
                    <i class="fa-solid fa-calendar-plus"></i>
                </div>
            </div>
        </div>
        <div class="woodash-metric-card woodash-hover-card woodash-glow h-[50px]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="woodash-metric-title">Response Rate</h3>
                    <div class="woodash-metric-value text-purple-600">92%</div>
                </div>
                <div class="woodash-metric-icon woodash-metric-purple">
                    <i class="fa-solid fa-reply"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Rating Distribution -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow h-[450px]">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Rating Distribution</h2>
                    <p class="text-gray-500 text-sm">Breakdown of customer ratings</p>
                </div>
                <button class="woodash-btn woodash-btn-secondary woodash-hover-card text-xs">
                    <i class="fa-solid fa-chart-bar"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1 w-16">
                        <span class="text-sm font-medium">5</span>
                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="woodash-progress">
                            <div class="woodash-progress-bar bg-yellow-500" style="width: 68%"></div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-600 w-12">847</span>
                    <span class="text-sm text-gray-500 w-12">68%</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1 w-16">
                        <span class="text-sm font-medium">4</span>
                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="woodash-progress">
                            <div class="woodash-progress-bar bg-yellow-400" style="width: 22%"></div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-600 w-12">274</span>
                    <span class="text-sm text-gray-500 w-12">22%</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1 w-16">
                        <span class="text-sm font-medium">3</span>
                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="woodash-progress">
                            <div class="woodash-progress-bar bg-yellow-300" style="width: 7%"></div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-600 w-12">87</span>
                    <span class="text-sm text-gray-500 w-12">7%</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1 w-16">
                        <span class="text-sm font-medium">2</span>
                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="woodash-progress">
                            <div class="woodash-progress-bar bg-orange-400" style="width: 2%"></div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-600 w-12">25</span>
                    <span class="text-sm text-gray-500 w-12">2%</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1 w-16">
                        <span class="text-sm font-medium">1</span>
                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="woodash-progress">
                            <div class="woodash-progress-bar bg-red-500" style="width: 1%"></div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-600 w-12">14</span>
                    <span class="text-sm text-gray-500 w-12">1%</span>
                </div>
            </div>
            
            <!-- Review Sentiment Analysis -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-md font-semibold text-gray-900 mb-4">Sentiment Analysis</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="darkmode-toggle4 text-center p-3 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">78%</div>
                        <div class="text-sm text-green-700">Positive</div>
                    </div>
                    <div class="darkmode-toggle4 text-center p-3 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">18%</div>
                        <div class="text-sm text-yellow-700">Neutral</div>
                    </div>
                    <div class="darkmode-toggle4 text-center p-3 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">4%</div>
                        <div class="text-sm text-red-700">Negative</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Trends -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow h-[450px]">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Review Trends</h2>
                    <p class="text-gray-500 text-sm">Monthly review volume and ratings</p>
                </div>
                <div class="flex gap-2">
                    <button class="woodash-btn woodash-btn-primary text-xs" data-range="6months">6M</button>
                    <button class="woodash-btn woodash-btn-secondary text-xs" data-range="1year">1Y</button>
                </div>
            </div>
            <div class="h-[300px]">
                <canvas id="review-trends-chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Reviewed Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Most Reviewed Products -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow h-[450px]">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Most Reviewed Products</h2>
                    <p class="text-gray-500 text-sm">Products with highest review volume</p>
                </div>
                <button class="woodash-btn woodash-btn-secondary woodash-hover-card text-xs">
                    <i class="fa-solid fa-trophy"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div class="darkmode-toggle4 flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border-l-4 border-yellow-500">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-headphones text-gray-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Wireless Headphones Pro</p>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1">
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    <span class="text-sm font-medium">4.8</span>
                                </div>
                                <span class="text-sm text-gray-500">•</span>
                                <span class="text-sm text-gray-600">234 reviews</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-yellow-600">234</div>
                        <div class="text-sm text-gray-500">reviews</div>
                    </div>
                </div>
                
                                <div class="darkmode-toggle4 flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border-l-4 border-yellow-500">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-headphones text-gray-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Wireless Headphones Pro</p>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1">
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    <span class="text-sm font-medium">4.8</span>
                                </div>
                                <span class="text-sm text-gray-500">•</span>
                                <span class="text-sm text-gray-600">234 reviews</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-yellow-600">234</div>
                        <div class="text-sm text-gray-500">reviews</div>
                    </div>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-teal-50 rounded-lg border-l-4 border-green-500">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-laptop text-gray-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Gaming Laptop Ultra</p>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1">
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    <span class="text-sm font-medium">4.7</span>
                                </div>
                                <span class="text-sm text-gray-500">•</span>
                                <span class="text-sm text-gray-600">156 reviews</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-green-600">156</div>
                        <div class="text-sm text-gray-500">reviews</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow h-[450px]">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Recent Reviews</h2>
                    <p class="text-gray-500 text-sm">Latest customer feedback</p>
                </div>
                <button class="woodash-btn woodash-btn-secondary woodash-hover-card text-xs">
                    <i class="fa-solid fa-refresh"></i>
                </button>
            </div>
            <div class="space-y-4 woodash-scrollbar" style="max-height: 320px; overflow-y: auto;">
                <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">JD</div>
                            <div>
                                <p class="font-medium text-sm">John Doe</p>
                                <div class="flex items-center gap-1">
                                    <div class="flex">
                                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    </div>
                                    <span class="text-xs text-gray-500">5.0</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">2 hours ago</span>
                    </div>
                    <p class="text-sm text-gray-700 mb-2">"Excellent product! The sound quality is amazing and the battery life exceeds expectations. Highly recommended!"</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Wireless Headphones Pro</span>
                        <div class="flex gap-2">
                            <button class="text-blue-600 hover:text-blue-800 text-xs">
                                <i class="fa-solid fa-reply"></i> Reply
                            </button>
                            <button class="text-green-600 hover:text-green-800 text-xs">
                                <i class="fa-solid fa-check"></i> Approve
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-pink-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">AS</div>
                            <div>
                                <p class="font-medium text-sm">Alice Smith</p>
                                <div class="flex items-center gap-1">
                                    <div class="flex">
                                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                        <i class="fa-regular fa-star text-gray-300 text-xs"></i>
                                    </div>
                                    <span class="text-xs text-gray-500">4.0</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">5 hours ago</span>
                    </div>
                    <p class="text-sm text-gray-700 mb-2">"Good watch overall, but the battery could be better. The fitness tracking features are great though."</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Smart Watch Series X</span>
                        <div class="flex gap-2">
                            <button class="text-blue-600 hover:text-blue-800 text-xs">
                                <i class="fa-solid fa-reply"></i> Reply
                            </button>
                            <button class="text-green-600 hover:text-green-800 text-xs">
                                <i class="fa-solid fa-check"></i> Approve
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="darkmode-toggle4 p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">MB</div>
                            <div>
                                <p class="font-medium text-sm">Mike Brown</p>
                                <div class="flex items-center gap-1">
                                    <div class="flex">
                                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                        <i class="fa-regular fa-star text-gray-300 text-xs"></i>
                                        <i class="fa-regular fa-star text-gray-300 text-xs"></i>
                                        <i class="fa-regular fa-star text-gray-300 text-xs"></i>
                                    </div>
                                    <span class="text-xs text-gray-500">2.0</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">1 day ago</span>
                    </div>
                    <p class="text-sm text-gray-700 mb-2">"Product arrived damaged and customer service was slow to respond. Not satisfied with this purchase."</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Gaming Laptop Ultra</span>
                        <div class="flex gap-2">
                            <button class="text-blue-600 hover:text-blue-800 text-xs">
                                <i class="fa-solid fa-reply"></i> Reply
                            </button>
                            <button class="text-red-600 hover:text-red-800 text-xs">
                                <i class="fa-solid fa-exclamation-triangle"></i> Priority
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;" onclick="if(event.target === this) this.style.display = 'none';">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900">Add New Product</h3>
                    <button id="closeProductModal" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="document.getElementById('addProductModal').style.display = 'none';">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="addProductForm" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div class="md:col-span-2">
                        <label for="productName" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                        <input type="text" id="productName" name="productName" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    </div>
                    
                    <!-- SKU -->
                    <div>
                        <label for="productSku" class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                        <input type="text" id="productSku" name="productSku" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <label for="productCategory" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select id="productCategory" name="productCategory" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            <option value="">Select Category</option>
                            <option value="electronics">Electronics</option>
                            <option value="clothing">Clothing</option>
                            <option value="home">Home & Garden</option>
                            <option value="sports">Sports</option>
                            <option value="books">Books</option>
                            <option value="beauty">Beauty</option>
                            <option value="toys">Toys</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <!-- Price -->
                    <div>
                        <label for="productPrice" class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" id="productPrice" name="productPrice" step="0.01" min="0" required
                                   class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        </div>
                    </div>
                    
                    <!-- Stock Quantity -->
                    <div>
                        <label for="productStock" class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                        <input type="number" id="productStock" name="productStock" min="0" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    </div>
                    
                    <!-- Weight -->
                    <div>
                        <label for="productWeight" class="block text-sm font-medium text-gray-700 mb-2">Weight (lbs)</label>
                        <input type="number" id="productWeight" name="productWeight" step="0.1" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    </div>
                    
                    <!-- Dimensions -->
                    <div>
                        <label for="productDimensions" class="block text-sm font-medium text-gray-700 mb-2">Dimensions (L×W×H)</label>
                        <input type="text" id="productDimensions" name="productDimensions" placeholder="e.g., 10×8×5"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>
                
                <!-- Description -->
                <div>
                    <label for="productDescription" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="productDescription" name="productDescription" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 resize-none"
                              placeholder="Enter product description..."></textarea>
                </div>
                
                <!-- Image Upload -->
                <div>
                    <label for="productImage" class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors">
                        <input type="file" id="productImage" name="productImage" accept="image/*" class="hidden">
                        <div id="imageUploadArea" class="cursor-pointer">
                            <i class="fa-solid fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-600">Click to upload image or drag and drop</p>
                            <p class="text-sm text-gray-400 mt-1">PNG, JPG, GIF up to 10MB</p>
                        </div>
                        <div id="imagePreview" class="hidden mt-4">
                            <img id="previewImg" class="max-w-full h-32 object-cover rounded-lg mx-auto">
                        </div>
                    </div>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="productStatus" value="active" checked
                                   class="text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-gray-700">Active</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="productStatus" value="inactive"
                                   class="text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-gray-700">Inactive</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="productStatus" value="draft"
                                   class="text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-gray-700">Draft</span>
                        </label>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <button type="button" id="cancelProductForm" class="woodash-btn woodash-btn-secondary" onclick="document.getElementById('addProductModal').style.display = 'none';">
                        Cancel
                    </button>
                    <button type="submit" class="woodash-btn woodash-btn-primary">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Review Management -->
    <div class="woodash-chart-container woodash-hover-card woodash-glow">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="text-lg font-bold woodash-gradient-text">Review Management</h2>
                <p class="text-gray-500 text-sm">Manage and respond to customer reviews</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <select class="woodash-btn woodash-btn-secondary text-sm">
                    <option>All Reviews</option>
                    <option>5 Stars</option>
                    <option>4 Stars</option>
                    <option>3 Stars</option>
                    <option>2 Stars</option>
                    <option>1 Star</option>
                    <option>Pending</option>
                    <option>Replied</option>
                </select>
                <select class="woodash-btn woodash-btn-secondary text-sm">
                    <option>All Products</option>
                    <option>Wireless Headphones Pro</option>
                    <option>Smart Watch Series X</option>
                    <option>Gaming Laptop Ultra</option>
                </select>
                <button class="woodash-btn woodash-btn-secondary text-sm">
                    <i class="fa-solid fa-download mr-2"></i>
                    Export
                </button>
                <button class="woodash-btn woodash-btn-primary" id="bulk-actions-btn">
                    <i class="fa-solid fa-tasks mr-2"></i>
                    Bulk Actions
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="woodash-table w-full">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" class="w-4 h-4 text-[#814ce4] rounded" id="select-all-reviews">
                        </th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="checkbox" class="w-4 h-4 text-[#814ce4] rounded review-checkbox">
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">JD</div>
                                <div>
                                    <span class="font-medium">John Doe</span>
                                    <p class="text-xs text-gray-500">john@example.com</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-headphones text-gray-600"></i>
                                </div>
                                <span class="font-medium">Wireless Headphones Pro</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <div class="flex">
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                </div>
                                <span class="text-sm font-medium ml-1">5.0</span>
                            </div>
                        </td>
                        <td>
                            <div class="max-w-xs">
                                <p class="text-sm text-gray-700 truncate">Excellent product! The sound quality is amazing and the battery life exceeds expectations...</p>
                            </div>
                        </td>
                        <td>2024-01-15</td>
                        <td><span class="woodash-badge woodash-badge-success">Approved</span></td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="View Full Review">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Reply">
                                    <i class="fa-solid fa-reply"></i>
                                </button>
                                <button class="text-orange-600 hover:text-orange-800" title="Feature">
                                    <i class="fa-solid fa-star"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="w-4 h-4 text-[#814ce4] rounded review-checkbox">
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-pink-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">AS</div>
                                <div>
                                    <span class="font-medium">Alice Smith</span>
                                    <p class="text-xs text-gray-500">alice@example.com</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-mobile-screen text-gray-600"></i>
                                </div>
                                <span class="font-medium">Smart Watch Series X</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <div class="flex">
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    <i class="fa-regular fa-star text-gray-300 text-xs"></i>
                                </div>
                                <span class="text-sm font-medium ml-1">4.0</span>
                            </div>
                        </td>
                        <td>
                            <div class="max-w-xs">
                                <p class="text-sm text-gray-700 truncate">Good watch overall, but the battery could be better. The fitness tracking features are great...</p>
                            </div>
                        </td>
                        <td>2024-01-14</td>
                        <td><span class="woodash-badge woodash-badge-warning">Pending</span></td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="View Full Review">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Approve">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                                <button class="text-orange-600 hover:text-orange-800" title="Reply">
                                    <i class="fa-solid fa-reply"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="bg-red-50">
                        <td>
                            <input type="checkbox" class="w-4 h-4 text-[#814ce4] rounded review-checkbox">
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">MB</div>
                                <div>
                                    <span class="font-medium">Mike Brown</span>
                                    <p class="text-xs text-gray-500">mike@example.com</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-laptop text-gray-600"></i>
                                </div>
                                <span class="font-medium">Gaming Laptop Ultra</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <div class="flex">
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
                                    <i class="fa-regular fa-star text-gray-300 text-xs"></i>
                                    <i class="fa-regular fa-star text-gray-300 text-xs"></i>
                                    <i class="fa-regular fa-star text-gray-300 text-xs"></i>
                                </div>
                                <span class="text-sm font-medium ml-1">2.0</span>
                            </div>
                        </td>
                        <td>
                            <div class="max-w-xs">
                                <p class="text-sm text-gray-700 truncate">Product arrived damaged and customer service was slow to respond. Not satisfied...</p>
                            </div>
                        </td>
                        <td>2024-01-13</td>
                        <td><span class="woodash-badge woodash-badge-danger">Priority</span></td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="View Full Review">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Reply">
                                    <i class="fa-solid fa-reply"></i>
                                </button>
                                <button class="text-purple-600 hover:text-purple-800" title="Contact Customer">
                                    <i class="fa-solid fa-phone"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Escalate">
                                    <i class="fa-solid fa-exclamation-triangle"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
            <div class="text-sm text-gray-500">
                Showing 1 to 3 of 1,247 reviews
            </div>
            <div class="flex gap-2">
                <button class="woodash-btn woodash-btn-secondary text-sm" disabled>
                    <i class="fa-solid fa-chevron-left mr-1"></i>
                    Previous
                </button>
                <button class="woodash-btn woodash-btn-primary text-sm">1</button>
                <button class="woodash-btn woodash-btn-secondary text-sm">2</button>
                <button class="woodash-btn woodash-btn-secondary text-sm">3</button>
                <button class="woodash-btn woodash-btn-secondary text-sm">
                    Next
                    <i class="fa-solid fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Review Insights -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- Common Keywords -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow h-[450px]">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Common Keywords</h2>
                    <p class="text-gray-500 text-sm">Most mentioned words in reviews</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <span class="font-medium text-green-800">Quality</span>
                    <div class="flex items-center gap-2">
                        <div class="woodash-progress w-24">
                            <div class="woodash-progress-bar bg-green-500" style="width: 85%"></div>
                        </div>
                        <span class="text-sm text-green-600">342 mentions</span>
                    </div>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <span class="font-medium text-blue-800">Fast</span>
                    <div class="flex items-center gap-2">
                        <div class="woodash-progress w-24">
                            <div class="woodash-progress-bar bg-blue-500" style="width: 72%"></div>
                        </div>
                        <span class="text-sm text-blue-600">289 mentions</span>
                    </div>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                    <span class="font-medium text-purple-800">Excellent</span>
                    <div class="flex items-center gap-2">
                        <div class="woodash-progress w-24">
                            <div class="woodash-progress-bar bg-purple-500" style="width: 68%"></div>
                        </div>
                        <span class="text-sm text-purple-600">267 mentions</span>
                    </div>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <span class="font-medium text-yellow-800">Recommend</span>
                    <div class="flex items-center gap-2">
                        <div class="woodash-progress w-24">
                            <div class="woodash-progress-bar bg-yellow-500" style="width: 58%"></div>
                        </div>
                        <span class="text-sm text-yellow-600">234 mentions</span>
                    </div>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-red-50 rounded-lg">
                    <span class="font-medium text-red-800">Problem</span>
                    <div class="flex items-center gap-2">
                        <div class="woodash-progress w-24">
                            <div class="woodash-progress-bar bg-red-500" style="width: 12%"></div>
                        </div>
                        <span class="text-sm text-red-600">48 mentions</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Response Time -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow h-[450px]">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Response Performance</h2>
                    <p class="text-gray-500 text-sm">Review response metrics</p>
                </div>
            </div>
            <div class="space-y-6">
                <div class="darkmode-toggle4 text-center p-6 bg-gradient-to-br from-purple-50 to-blue-50 rounded-lg">
                    <div class="text-3xl font-bold text-purple-600 mb-2">2.4 hrs</div>
                    <div class="text-sm text-gray-600">Average Response Time</div>
                    <div class="text-xs text-green-600 mt-1">
                        <i class="fa-solid fa-arrow-down"></i> 18% faster than last month
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="darkmode-toggle4 text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">92%</div>
                        <div class="text-sm text-green-700">Response Rate</div>
                    </div>
                    <div class="darkmode-toggle4 text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">4.8</div>
                        <div class="text-sm text-blue-700">Avg. Helpfulness</div>
                    </div>
                </div>
                
                <div class="darkmode-toggle4 p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-lightbulb text-yellow-600"></i>
                        <span class="font-medium text-yellow-800">Tip</span>
                    </div>
                    <p class="text-sm text-yellow-700">Responding to reviews within 24 hours increases customer satisfaction by 23%.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Customer Reviews Page -->

<!-- Improved Updates & New Features Page -->
<div id="integrations-page" class="woodash-page-content hidden">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Payment Gateways -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Payment Gateways</h2>
                    <p class="text-gray-500 text-sm">Manage payment processors</p>
                </div>
                <button class="woodash-btn woodash-btn-secondary woodash-hover-card">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-green-50 border-l-4 border-green-500 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-brands fa-paypal text-green-600 text-xl"></i>
                        <div>
                            <p class="font-medium text-green-800">PayPal</p>
                            <p class="text-sm text-green-600">Connected</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-green-600 hover:text-green-800" title="Configure">
                            <i class="fa-solid fa-cog"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-700" title="Disconnect">
                            <i class="fa-solid fa-unlink"></i>
                        </button>
                    </div>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-brands fa-stripe text-blue-600 text-xl"></i>
                        <div>
                            <p class="font-medium text-blue-800">Stripe</p>
                            <p class="text-sm text-blue-600">Connected</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800" title="Configure">
                            <i class="fa-solid fa-cog"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-700" title="Disconnect">
                            <i class="fa-solid fa-unlink"></i>
                        </button>
                    </div>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-gray-50 border-l-4 border-gray-500 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-credit-card text-gray-600 text-xl"></i>
                        <div>
                            <p class="font-medium text-gray-800">Square</p>
                            <p class="text-sm text-gray-600">Not Connected</p>
                        </div>
                    </div>
                    <button class="woodash-btn woodash-btn-primary text-xs px-3 py-1">
                        Connect
                    </button>
                </div>
            </div>
        </div>

        <!-- Shipping Providers -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Shipping Providers</h2>
                    <p class="text-gray-500 text-sm">Manage shipping integrations</p>
                </div>
                <button class="woodash-btn woodash-btn-secondary woodash-hover-card">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-orange-50 border-l-4 border-orange-500 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-truck text-orange-600 text-xl"></i>
                        <div>
                            <p class="font-medium text-orange-800">FedEx</p>
                            <p class="text-sm text-orange-600">Connected</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-orange-600 hover:text-orange-800" title="Configure">
                            <i class="fa-solid fa-cog"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-700" title="Disconnect">
                            <i class="fa-solid fa-unlink"></i>
                        </button>
                    </div>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-purple-50 border-l-4 border-purple-500 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-box text-purple-600 text-xl"></i>
                        <div>
                            <p class="font-medium text-purple-800">UPS</p>
                            <p class="text-sm text-purple-600">Connected</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-purple-600 hover:text-purple-800" title="Configure">
                            <i class="fa-solid fa-cog"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-700" title="Disconnect">
                            <i class="fa-solid fa-unlink"></i>
                        </button>
                    </div>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-gray-50 border-l-4 border-gray-500 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-shipping-fast text-gray-600 text-xl"></i>
                        <div>
                            <p class="font-medium text-gray-800">DHL</p>
                            <p class="text-sm text-gray-600">Not Connected</p>
                        </div>
                    </div>
                    <button class="woodash-btn woodash-btn-primary text-xs px-3 py-1">
                        Connect
                    </button>
                </div>
            </div>
        </div>

        <!-- Marketing Tools -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Marketing Tools</h2>
                    <p class="text-gray-500 text-sm">Email & marketing integrations</p>
                </div>
                <button class="woodash-btn woodash-btn-secondary woodash-hover-card">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-brands fa-mailchimp text-red-600 text-xl"></i>
                        <div>
                            <p class="font-medium text-red-800">Mailchimp</p>
                            <p class="text-sm text-red-600">Connected</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-red-600 hover:text-red-800" title="Configure">
                            <i class="fa-solid fa-cog"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-700" title="Disconnect">
                            <i class="fa-solid fa-unlink"></i>
                        </button>
                    </div>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-brands fa-google text-blue-600 text-xl"></i>
                        <div>
                            <p class="font-medium text-blue-800">Google Analytics</p>
                            <p class="text-sm text-blue-600">Connected</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800" title="Configure">
                            <i class="fa-solid fa-cog"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-700" title="Disconnect">
                            <i class="fa-solid fa-unlink"></i>
                        </button>
                    </div>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-gray-50 border-l-4 border-gray-500 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-brands fa-facebook text-gray-600 text-xl"></i>
                        <div>
                            <p class="font-medium text-gray-800">Facebook Pixel</p>
                            <p class="text-sm text-gray-600">Not Connected</p>
                        </div>
                    </div>
                    <button class="woodash-btn woodash-btn-primary text-xs px-3 py-1">
                        Connect
                    </button>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-purple-50 border-l-4 border-purple-500 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-brands fa-slack text-purple-600 text-xl"></i>
                        <div>
                            <p class="font-medium text-purple-800">Slack</p>
                            <p class="text-sm text-purple-600">Not Connected</p>
                        </div>
                    </div>
                    <button class="woodash-btn woodash-btn-primary text-xs px-3 py-1">
                        Connect
                    </button>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-indigo-50 border-l-4 border-indigo-500 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-brands fa-discord text-indigo-600 text-xl"></i>
                        <div>
                            <p class="font-medium text-indigo-800">Discord</p>
                            <p class="text-sm text-indigo-600">Not Connected</p>
                        </div>
                    </div>
                    <button class="woodash-btn woodash-btn-primary text-xs px-3 py-1">
                        Connect
                    </button>
                </div>
                <div class="darkmode-toggle4 flex items-center justify-between p-3 bg-pink-50 border-l-4 border-pink-500 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-brands fa-instagram text-pink-600 text-xl"></i>
                        <div>
                            <p class="font-medium text-pink-800">Instagram</p>
                            <p class="text-sm text-pink-600">Not Connected</p>
                        </div>
                    </div>
                    <button class="woodash-btn woodash-btn-primary text-xs px-3 py-1">
                        Connect
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Integration Status Overview -->
    <div class="woodash-chart-container woodash-hover-card woodash-glow mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg font-bold woodash-gradient-text">Integration Status Overview</h2>
                <p class="text-gray-500 text-sm">Monitor your connected services</p>
            </div>
            <div class="flex gap-2">
                <button class="woodash-btn woodash-btn-secondary woodash-hover-card" id="refresh-integrations">
                    <i class="fa-solid fa-refresh"></i>
                    <span>Refresh</span>
                </button>
                <button class="woodash-btn woodash-btn-primary woodash-hover-card" id="add-integration">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Integration</span>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="darkmode-toggle4 text-center p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600" id="connected-count">8</div>
                <div class="text-sm text-green-800">Connected</div>
            </div>
            <div class="darkmode-toggle4 text-center p-4 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600" id="pending-count">3</div>
                <div class="text-sm text-yellow-800">Pending</div>
            </div>
            <div class="darkmode-toggle4 text-center p-4 bg-red-50 rounded-lg">
                <div class="text-2xl font-bold text-red-600" id="failed-count">1</div>
                <div class="text-sm text-red-800">Failed</div>
            </div>
            <div class="darkmode-toggle4 text-center p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-gray-600" id="available-count">18</div>
                <div class="text-sm text-gray-800">Available</div>
            </div>
        </div>
        <div class="overflow-x-auto woodash-scrollbar">
            <table class="woodash-table w-full">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Last Sync</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="integrations-table">
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-paypal text-green-600"></i>
                                <span>PayPal</span>
                            </div>
                        </td>
                        <td>Payment</td>
                        <td><span class="woodash-badge woodash-badge-success">Connected</span></td>
                        <td>2 minutes ago</td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Configure">
                                    <i class="fa-solid fa-cog"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Test">
                                    <i class="fa-solid fa-play"></i>
                                </button>
                                <button class="text-red-500 hover:text-red-700" title="Disconnect">
                                    <i class="fa-solid fa-unlink"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-stripe text-blue-600"></i>
                                <span>Stripe</span>
                            </div>
                        </td>
                        <td>Payment</td>
                        <td><span class="woodash-badge woodash-badge-success">Connected</span></td>
                        <td>5 minutes ago</td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Configure">
                                    <i class="fa-solid fa-cog"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Test">
                                    <i class="fa-solid fa-play"></i>
                                </button>
                                <button class="text-red-500 hover:text-red-700" title="Disconnect">
                                    <i class="fa-solid fa-unlink"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-truck text-orange-600"></i>
                                <span>FedEx</span>
                            </div>
                        </td>
                        <td>Shipping</td>
                        <td><span class="woodash-badge woodash-badge-success">Connected</span></td>
                        <td>1 hour ago</td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Configure">
                                    <i class="fa-solid fa-cog"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Test">
                                    <i class="fa-solid fa-play"></i>
                                </button>
                                <button class="text-red-500 hover:text-red-700" title="Disconnect">
                                    <i class="fa-solid fa-unlink"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-mailchimp text-red-600"></i>
                                <span>Mailchimp</span>
                            </div>
                        </td>
                        <td>Marketing</td>
                        <td><span class="woodash-badge woodash-badge-warning">Pending</span></td>
                        <td>Never</td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Configure">
                                    <i class="fa-solid fa-cog"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Retry">
                                    <i class="fa-solid fa-refresh"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-google text-blue-600"></i>
                                <span>Google Analytics</span>
                            </div>
                        </td>
                        <td>Analytics</td>
                        <td><span class="woodash-badge woodash-badge-danger">Failed</span></td>
                        <td>3 hours ago</td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Configure">
                                    <i class="fa-solid fa-cog"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Retry">
                                    <i class="fa-solid fa-refresh"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-slack text-purple-600"></i>
                                <span>Slack</span>
                            </div>
                        </td>
                        <td>Communication</td>
                        <td><span class="woodash-badge woodash-badge-warning">Pending</span></td>
                        <td>Never</td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Configure">
                                    <i class="fa-solid fa-cog"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Connect">
                                    <i class="fa-solid fa-link"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-discord text-indigo-600"></i>
                                <span>Discord</span>
                            </div>
                        </td>
                        <td>Communication</td>
                        <td><span class="woodash-badge woodash-badge-warning">Pending</span></td>
                        <td>Never</td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Configure">
                                    <i class="fa-solid fa-cog"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Connect">
                                    <i class="fa-solid fa-link"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-instagram text-pink-600"></i>
                                <span>Instagram</span>
                            </div>
                        </td>
                        <td>Social Media</td>
                        <td><span class="woodash-badge woodash-badge-warning">Pending</span></td>
                        <td>Never</td>
                        <td>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Configure">
                                    <i class="fa-solid fa-cog"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800" title="Connect">
                                    <i class="fa-solid fa-link"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Popular Integrations -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Popular Payment Integrations -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Popular Payment Integrations</h2>
                    <p class="text-gray-500 text-sm">Most used payment gateways</p>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <div class="flex items-center gap-4">
                        <i class="fa-brands fa-paypal text-2xl text-blue-600"></i>
                        <div>
                            <h3 class="font-medium">PayPal</h3>
                            <p class="text-sm text-gray-500">Accept payments worldwide</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-green-600">Free</div>
                        <button class="woodash-btn woodash-btn-primary text-xs mt-1">Install</button>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <div class="flex items-center gap-4">
                        <i class="fa-brands fa-stripe text-2xl text-purple-600"></i>
                        <div>
                            <h3 class="font-medium">Stripe</h3>
                            <p class="text-sm text-gray-500">Modern payment processing</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-green-600">Free</div>
                        <button class="woodash-btn woodash-btn-primary text-xs mt-1">Install</button>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <div class="flex items-center gap-4">
                        <i class="fa-brands fa-amazon text-2xl text-orange-600"></i>
                        <div>
                            <h3 class="font-medium">Amazon Pay</h3>
                            <p class="text-sm text-gray-500">Leverage Amazon's network</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-blue-600">$29/mo</div>
                        <button class="woodash-btn woodash-btn-primary text-xs mt-1">Install</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Marketing Integrations -->
        <div class="woodash-chart-container woodash-hover-card woodash-glow">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-bold woodash-gradient-text">Popular Marketing Integrations</h2>
                    <p class="text-gray-500 text-sm">Grow your customer base</p>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <div class="flex items-center gap-4">
                        <i class="fa-brands fa-mailchimp text-2xl text-red-600"></i>
                        <div>
                            <h3 class="font-medium">Mailchimp</h3>
                            <p class="text-sm text-gray-500">Email marketing automation</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-green-600">Free</div>
                        <button class="woodash-btn woodash-btn-primary text-xs mt-1">Install</button>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <div class="flex items-center gap-4">
                        <i class="fa-brands fa-google text-2xl text-blue-600"></i>
                        <div>
                            <h3 class="font-medium">Google Ads</h3>
                            <p class="text-sm text-gray-500">Run targeted ad campaigns</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-blue-600">Pay per click</div>
                        <button class="woodash-btn woodash-btn-primary text-xs mt-1">Install</button>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <div class="flex items-center gap-4">
                        <i class="fa-brands fa-facebook text-2xl text-blue-700"></i>
                        <div>
                            <h3 class="font-medium">Facebook Ads</h3>
                            <p class="text-sm text-gray-500">Social media advertising</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-blue-600">Pay per click</div>
                        <button class="woodash-btn woodash-btn-primary text-xs mt-1">Install</button>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <div class="flex items-center gap-4">
                        <i class="fa-brands fa-slack text-2xl text-purple-600"></i>
                        <div>
                            <h3 class="font-medium">Slack</h3>
                            <p class="text-sm text-gray-500">Team communication & notifications</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-green-600">Free</div>
                        <button class="woodash-btn woodash-btn-primary text-xs mt-1">Install</button>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <div class="flex items-center gap-4">
                        <i class="fa-brands fa-discord text-2xl text-indigo-600"></i>
                        <div>
                            <h3 class="font-medium">Discord</h3>
                            <p class="text-sm text-gray-500">Community engagement & support</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-green-600">Free</div>
                        <button class="woodash-btn woodash-btn-primary text-xs mt-1">Install</button>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <div class="flex items-center gap-4">
                        <i class="fa-brands fa-instagram text-2xl text-pink-600"></i>
                        <div>
                            <h3 class="font-medium">Instagram</h3>
                            <p class="text-sm text-gray-500">Social media marketing & insights</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium text-green-600">Free</div>
                        <button class="woodash-btn woodash-btn-primary text-xs mt-1">Install</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Integration Logs -->
    <div class="woodash-chart-container woodash-hover-card woodash-glow mt-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg font-bold woodash-gradient-text">Integration Activity Logs</h2>
                <p class="text-gray-500 text-sm">Recent integration activities and errors</p>
            </div>
            <button class="woodash-btn woodash-btn-secondary woodash-hover-card">
                <i class="fa-solid fa-download"></i>
                <span>Export Logs</span>
            </button>
        </div>
        <div class="space-y-3 max-h-64 overflow-y-auto woodash-scrollbar">
            <div class="darkmode-toggle4 flex items-start gap-3 p-3 bg-green-50 border-l-4 border-green-500 rounded-lg">
                <i class="fa-solid fa-check-circle text-green-600 mt-1"></i>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-green-800">PayPal payment processed successfully</p>
                        <span class="text-sm text-green-600">2 min ago</span>
                    </div>
                    <p class="text-sm text-green-700">Order #1234 - $299.99</p>
                </div>
            </div>
            <div class="darkmode-toggle4 flex items-start gap-3 p-3 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                <i class="fa-solid fa-info-circle text-blue-600 mt-1"></i>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-blue-800">Stripe webhook received</p>
                        <span class="text-sm text-blue-600">5 min ago</span>
                    </div>
                    <p class="text-sm text-blue-700">Payment intent succeeded</p>
                </div>
            </div>
            <div class="darkmode-toggle4 flex items-start gap-3 p-3 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                <i class="fa-solid fa-exclamation-triangle text-yellow-600 mt-1"></i>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-yellow-800">FedEx API rate limit warning</p>
                        <span class="text-sm text-yellow-600">15 min ago</span>
                    </div>
                    <p class="text-sm text-yellow-700">Approaching API call limit</p>
                </div>
            </div>
            <div class="darkmode-toggle4 flex items-start gap-3 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <i class="fa-solid fa-times-circle text-red-600 mt-1"></i>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-red-800">Google Analytics connection failed</p>
                        <span class="text-sm text-red-600">1 hour ago</span>
                    </div>
                    <p class="text-sm text-red-700">Invalid API credentials</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Integrations Page -->

                    <!-- Settings Page -->
                    <div id="settings-page" class="woodash-page-content hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- General Settings -->
                            <div class="woodash-chart-container woodash-hover-card woodash-glow h-[380px]">
                                <div class="flex justify-between items-center mb-6">
                                    <div>
                                        <h2 class="text-lg font-bold woodash-gradient-text">General Settings</h2>
                                        <p class="text-gray-500 text-sm">Configure your dashboard preferences</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Store Name</label>
                                        <input type="text" value="My Awesome Store" class="woodash-form-input w-full">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                                        <select class="woodash-form-input w-full">
                                            <option>USD ($)</option>
                                            <option>EUR (€)</option>
                                            <option>GBP (£)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                                        <select class="woodash-form-input w-full">
                                            <option>UTC-5 (Eastern Time)</option>
                                            <option>UTC-8 (Pacific Time)</option>
                                            <option>UTC+0 (GMT)</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center gap-3 pt-2">
                                        <input type="checkbox" id="notifications" class="w-4 h-4 text-[#814ce4] rounded">
                                        <label for="notifications" class="text-sm text-gray-700">Enable notifications</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Dashboard Settings -->
                            <div class="woodash-chart-container woodash-hover-card woodash-glow h-[380px]">
                                <div class="flex justify-between items-center mb-6">
                                    <div>
                                        <h2 class="text-lg font-bold woodash-gradient-text">Dashboard Settings</h2>
                                        <p class="text-gray-500 text-sm">Customize your dashboard appearance</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Theme</label>
                                        <select class="woodash-form-input w-full">
                                            <option>Light</option>
                                            <option>Dark</option>
                                            <option>Auto</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Data Refresh Rate</label>
                                        <select class="woodash-form-input w-full">
                                            <option>Real-time</option>
                                            <option>Every 5 minutes</option>
                                            <option>Every 15 minutes</option>
                                            <option>Manual</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center gap-3 pt-2">
                                        <input type="checkbox" id="animations" class="w-4 h-4 text-[#814ce4] rounded" checked>
                                        <label for="animations" class="text-sm text-gray-700">Enable animations</label>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="slideshow" class="w-4 h-4 text-[#814ce4] rounded" checked>
                                        <label for="slideshow" class="text-sm text-gray-700">Show slideshow</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Settings -->
                            <div class="woodash-chart-container woodash-hover-card woodash-glow h-[380px]">
                                <div class="flex justify-between items-center mb-6">
                                    <div>
                                        <h2 class="text-lg font-bold woodash-gradient-text">Account Settings</h2>
                                        <p class="text-gray-500 text-sm">Manage your account information</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                        <input type="text" value="John Doe" class="woodash-form-input w-full">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                        <input type="email" value="john@example.com" class="woodash-form-input w-full">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                        <input type="password" placeholder="Enter new password" class="woodash-form-input w-full">
                                    </div>
                                    <button class="woodash-btn woodash-btn-primary">
                                        <i class="fa-solid fa-save mr-2"></i>
                                        Save Changes
                                    </button>
                                </div>
                            </div>

                            <!-- Export Settings -->
                            <div class="woodash-chart-container woodash-hover-card woodash-glow h-[380px]">
                                <div class="flex justify-between items-center mb-6">
                                    <div>
                                        <h2 class="text-lg font-bold woodash-gradient-text">Export Settings</h2>
                                        <p class="text-gray-500 text-sm">Configure data export preferences</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Export Format</label>
                                        <select class="woodash-form-input w-full">
                                            <option>CSV</option>
                                            <option>Excel</option>
                                            <option>PDF</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                                        <select class="woodash-form-input w-full">
                                            <option>Last 30 days</option>
                                            <option>Last 90 days</option>
                                            <option>Custom range</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center gap-3 pt-2">
                                        <input type="checkbox" id="include-images" class="w-4 h-4 text-[#814ce4] rounded">
                                        <label for="include-images" class="text-sm text-gray-700">Include product images</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Settings Page -->
                </div>
                <!-- End Page Content Container -->

            </div>
        </main>
    </div>
</div>

<!-- Add Task Modal -->
<div id="add-task-modal" class="woodash-modal hidden">
    <div class="woodash-modal-overlay" id="modal-overlay"></div>
    <div class="woodash-modal-content">
        <div class="woodash-modal-header">
            <h3 class="text-lg font-bold woodash-gradient-text">Add New Task</h3>
            <button class="woodash-modal-close" id="close-modal-btn">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        
        <form id="add-task-form" class="space-y-4 p-6">
            <div>
                <label for="task-title" class="block text-sm font-medium text-gray-700 mb-2">Task Title</label>
                <input type="text" id="task-title" name="task-title" 
                       class="woodash-form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#814ce4] focus:border-transparent" 
                       placeholder="Enter task title..." required>
            </div>
            
            <div>
                <label for="task-description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                <textarea id="task-description" name="task-description" rows="3"
                          class="woodash-form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#814ce4] focus:border-transparent" 
                          placeholder="Enter task description..."></textarea>
            </div>
            
            <div>
                <label for="task-priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select id="task-priority" name="task-priority" 
                        class="woodash-form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#814ce4] focus:border-transparent">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            
            <div>
                <label for="task-due-date" class="block text-sm font-medium text-gray-700 mb-2">Due Date (Optional)</label>
                <input type="date" id="task-due-date" name="task-due-date" 
                       class="woodash-form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#814ce4] focus:border-transparent">
            </div>
            
            <div class="flex items-center gap-3">
                <input type="checkbox" id="task-urgent" name="task-urgent" 
                       class="w-4 h-4 text-[#814ce4] rounded focus:ring-[#814ce4]">
                <label for="task-urgent" class="text-sm text-gray-700">Mark as urgent</label>
            </div>
        </form>
        
        <div class="woodash-modal-footer">
            <button type="button" class="woodash-btn woodash-btn-secondary" id="cancel-task-btn">
                <i class="fa-solid fa-times mr-2"></i>
                Cancel
            </button>
            <button type="submit" form="add-task-form" class="woodash-btn woodash-btn-primary" id="save-task-btn">
                <i class="fa-solid fa-check mr-2"></i>
                Add Task
            </button>
        </div>
    </div>
</div>

<!-- AI Assistant Chat Widget -->
<div class="woodash-ai-container">
    <!-- AI Chat Interface -->
    <div class="woodash-ai-chat" id="woodash-ai-chat">
        <div class="woodash-ai-header">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                    <i class="fa-solid fa-robot text-sm"></i>
                </div>
                <div>
                    <h4 class="font-medium text-sm">AI Assistant</h4>
                    <div class="woodash-ai-status">
                        <div class="woodash-ai-status-dot"></div>
                        <span class="text-xs opacity-90">Online</span>
                    </div>
                </div>
            </div>
            <button onclick="toggleAIChat()" class="text-white hover:text-gray-200 transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        
        <div class="woodash-ai-messages" id="ai-messages">
            <div class="woodash-ai-message ai">
                <div class="woodash-ai-message-avatar">
                    <i class="fa-solid fa-robot text-xs"></i>
                </div>
                <div class="woodash-ai-message-content">
                    Hi! I'm your AI assistant. I can help you analyze your store data, provide insights, and answer questions about your business. How can I help you today?
                </div>
            </div>
        </div>
        
        <div class="woodash-ai-suggestions">
            <div class="text-xs text-gray-600 mb-2">Quick suggestions:</div>
            <div class="flex flex-wrap gap-2">
                <span class="woodash-ai-suggestion" onclick="sendQuickMessage('Analyze my sales trends')">Analyze sales trends</span>
                <span class="woodash-ai-suggestion" onclick="sendQuickMessage('Show top products')">Top products</span>
                <span class="woodash-ai-suggestion" onclick="sendQuickMessage('Customer insights')">Customer insights</span>
            </div>
        </div>
        
        <div class="woodash-ai-input-container">
            <input type="text" placeholder="Ask me anything..." class="woodash-ai-input" id="ai-input" onkeypress="handleAIInputKeypress(event)">
            <div class="woodash-ai-send" onclick="sendAIMessage()">
                <i class="fa-solid fa-paper-plane text-xs"></i>
            </div>
        </div>
    </div>
    
    <!-- AI Toggle Button -->
    <div class="woodash-ai-toggle" id="ai-toggle" onclick="toggleAIChat()">
        <i class="fa-solid fa-robot text-xl"></i>
    </div>
</div>

<!-- Footer Section -->
<footer class="bg-white border-t border-gray-100 py-8 mt-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#814ce4] to-[#2d0873] flex items-center justify-center">
                        <i class="fa-solid fa-chart-line text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold woodash-gradient-text">WooDash Pro</h3>
                </div>
                <p class="text-gray-600 text-sm">Your all-in-one dashboard for managing your WooCommerce store. Track sales, monitor performance, and grow your business.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-400 hover:text-[#814ce4] transition-colors duration-200">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-[#814ce4] transition-colors duration-200">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-[#814ce4] transition-colors duration-200">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-[#814ce4] transition-colors duration-200">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-[#814ce4] transition-colors duration-200">Dashboard</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#814ce4] transition-colors duration-200">Products</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#814ce4] transition-colors duration-200">Orders</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#814ce4] transition-colors duration-200">Customers</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#814ce4] transition-colors duration-200">Reports</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Support</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-[#814ce4] transition-colors duration-200">Documentation</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#814ce4] transition-colors duration-200">Help Center</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#814ce4] transition-colors duration-200">API Reference</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#814ce4] transition-colors duration-200">Community</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#814ce4] transition-colors duration-200">Contact Us</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Stay Updated</h4>
                <p class="text-gray-600 text-sm mb-4">Subscribe to our newsletter for the latest updates and features.</p>
                <form class="space-y-3">
                    <div class="flex gap-2">
                        <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#814ce4] focus:border-transparent">
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
                <a href="#" class="text-gray-600 hover:text-[#814ce4] text-sm transition-colors duration-200">Privacy Policy</a>
                <a href="#" class="text-gray-600 hover:text-[#814ce4] text-sm transition-colors duration-200">Terms of Service</a>
                <a href="#" class="text-gray-600 hover:text-[#814ce4] text-sm transition-colors duration-200">Cookie Policy</a>
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

// Task Modal Functionality
document.addEventListener('DOMContentLoaded', function() {
    const addTaskBtn = document.getElementById('add-task-btn');
    const addTaskModal = document.getElementById('add-task-modal');
    const modalOverlay = document.getElementById('modal-overlay');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelTaskBtn = document.getElementById('cancel-task-btn');
    const addTaskForm = document.getElementById('add-task-form');
    const taskList = document.getElementById('task-list');

    // Open modal
    addTaskBtn.addEventListener('click', function() {
        addTaskModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
        
        // Focus on first input
        setTimeout(() => {
            document.getElementById('task-title').focus();
        }, 100);
    });

    // Close modal functions
    function closeModal() {
        addTaskModal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
        addTaskForm.reset(); // Clear form
    }

    // Close modal events
    closeModalBtn.addEventListener('click', closeModal);
    cancelTaskBtn.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', closeModal);

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !addTaskModal.classList.contains('hidden')) {
            closeModal();
        }
    });

    document.getElementById('darkmode-toggle').addEventListener('click', function() {
    const dashboard = document.getElementById('woodash-dashboard');
    if (dashboard.classList.contains('dark-theme')) {
        dashboard.classList.remove('dark-theme');
        localStorage.setItem('woodash_theme', 'light');
    } else {
        dashboard.classList.add('dark-theme');
        localStorage.setItem('woodash_theme', 'dark');
    }
    });
    // Handle form submission
    addTaskForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(addTaskForm);
        const taskData = {
            title: formData.get('task-title').trim(),
            description: formData.get('task-description').trim(),
            priority: formData.get('task-priority'),
            dueDate: formData.get('task-due-date'),
            urgent: formData.get('task-urgent') === 'on'
        };

        // Validate required fields
        if (!taskData.title) {
            alert('Please enter a task title.');
            return;
        }

        // Add task to the list
        addTaskToList(taskData);
        
        // Close modal
        closeModal();
        
        // Show success message
        showNotification('Task added successfully!', 'success');
    });

    function addTaskToList(taskData) {
        const taskElement = document.createElement('div');
        taskElement.className = 'flex items-center gap-3 p-3 bg-gray-50 rounded-lg woodash-fade-in';
        
        const priorityColors = {
            'low': 'text-purple-600',
            'medium': 'text-yellow-600', 
            'high': 'text-red-600'
        };

        const priorityColor = priorityColors[taskData.priority] || 'text-gray-600';
        const urgentBadge = taskData.urgent ? '<span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-full ml-2">Urgent</span>' : '';
        const dueDateText = taskData.dueDate ? `<div class="text-xs text-gray-400 mt-1">Due: ${new Date(taskData.dueDate).toLocaleDateString()}</div>` : '';
        const descriptionText = taskData.description ? `<div class="text-xs text-gray-500 mt-1">${taskData.description}</div>` : '';

        taskElement.innerHTML = `
            <input type="checkbox" class="w-4 h-4 text-[#814ce4] rounded focus:ring-[#814ce4]">
            <div class="flex-1">
                <div class="flex items-center">
                    <span class="text-sm">${taskData.title}</span>
                    ${urgentBadge}
                </div>
                ${descriptionText}
                ${dueDateText}
            </div>
            <span class="text-xs ${priorityColor} capitalize">${taskData.priority}</span>
            <button class="text-red-500 hover:text-red-700 transition-colors" onclick="removeTask(this)" title="Delete task">
                <i class="fa-solid fa-trash text-xs"></i>
            </button>
        `;

        // Add event listener for checkbox
        const checkbox = taskElement.querySelector('input[type="checkbox"]');
        checkbox.addEventListener('change', function() {
            const taskText = taskElement.querySelector('span');
            if (this.checked) {
                taskText.classList.add('line-through', 'text-gray-500');
                taskElement.classList.add('opacity-75');
            } else {
                taskText.classList.remove('line-through', 'text-gray-500');
                taskElement.classList.remove('opacity-75');
            }
            updateTaskProgress();
        });

        taskList.appendChild(taskElement);
        updateTaskProgress();
    }

    // Function to remove task
    window.removeTask = function(button) {
        if (confirm('Are you sure you want to delete this task?')) {
            button.closest('.flex').remove();
            updateTaskProgress();
            showNotification('Task deleted', 'info');
        }
    };

function updateTaskProgress() {
    const taskList = document.getElementById('task-list');
    if (!taskList) return;

    const allTasks = taskList.querySelectorAll('div.flex');
    const completedTasks = taskList.querySelectorAll('input[type="checkbox"]:checked');
    const total = allTasks.length;
    const completed = completedTasks.length;

    // Find the correct progress bar and text inside the Task Manager card
    const card = taskList.closest('.woodash-chart-container');
    const progressText = card ? card.querySelector('.mt-4.text-center p') : null;
    const progressBar = card ? card.querySelector('.woodash-progress-bar') : null;

    if (progressText) {
        progressText.textContent = `${completed} of ${total} tasks completed`;
    }
    if (progressBar) {
        const percentage = total > 0 ? (completed / total) * 100 : 0;
        progressBar.style.width = `${percentage}%`;
    }
}

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
        const response = await fetch(endpoint, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        // Check if response is ok
        if (!response.ok) {
            console.error(`HTTP error! status: ${response.status}`);
            // Read the response as text to see the actual content
            const responseText = await response.text();
            console.error('Response content:', responseText);
            console.warn(`API endpoint ${endpoint} not available (${response.status}), using mock data`);
            return getMockData(endpoint);
        }
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            console.warn(`API endpoint ${endpoint} returned non-JSON content (${contentType}), using mock data`);
            // Read the response as text to see what we actually got
            const responseText = await response.text();
            console.error('Non-JSON response content:', responseText);
            return getMockData(endpoint);
        }
        
        // Attempt to parse as JSON
        const data = await response.json();
        dataCache.set(endpoint, {
            data,
            timestamp: Date.now()
        });
        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
        
        // If it's a JSON parsing error, try to get the response text for debugging
        if (error instanceof SyntaxError && error.message.includes('Unexpected token')) {
            console.error('JSON parsing failed - likely received HTML instead of JSON');
            console.error('This usually means the server returned an error page (404, 500, etc.)');
        }
        
        console.log('Using mock data instead');
        return getMockData(endpoint);
    }
}

function getMockData(endpoint) {
    switch(endpoint) {
        case '/api/top-products':
            return [
                { name: 'Wireless Headphones', sales: 245, revenue: 12250 },
                { name: 'Gaming Mouse', sales: 189, revenue: 9450 },
                { name: 'USB Cable', sales: 156, revenue: 3120 },
                { name: 'Bluetooth Speaker', sales: 134, revenue: 6700 },
                { name: 'Phone Case', sales: 98, revenue: 1960 }
            ];
        case '/api/top-customers':
            return [
                { name: 'John Smith', orders: 12, total: 2450 },
                { name: 'Sarah Johnson', orders: 8, total: 1890 },
                { name: 'Mike Wilson', orders: 15, total: 3200 },
                { name: 'Emily Davis', orders: 6, total: 1200 },
                { name: 'David Brown', orders: 10, total: 2100 }
            ];
        default:
            return [];
    }
}

// Initialize Coupon Usage Chart
function initializeCouponUsageChart() {
    const ctx = document.getElementById('coupon-usage-chart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Coupon Usage',
                    data: [45, 67, 89, 123, 156, 134, 178],
                    borderColor: '#814ce4',
                    backgroundColor: 'rgba(129, 76, 228, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#814ce4',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#814ce4',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3
                }, {
                    label: 'Coupon Views',
                    data: [120, 180, 220, 280, 340, 290, 380],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: false,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            color: '#6b7280'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#1f2937',
                        bodyColor: '#6b7280',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            title: function(context) {
                                return context[0].label + ' 2024';
                            },
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.y.toLocaleString();
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            padding: 10
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.2)',
                            drawBorder: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            padding: 10,
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                },
                elements: {
                    line: {
                        borderJoinStyle: 'round'
                    }
                }
            }
        });
    }
}

// Call this function when the coupons page is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize chart when coupons page is shown
    const couponsPage = document.getElementById('coupons-page');
    if (couponsPage && !couponsPage.classList.contains('hidden')) {
        initializeCouponUsageChart();
    }
});

// Also initialize when switching to coupons page
function showCouponsPage() {
    // Your existing page switching logic here
    
    // Initialize the chart after a small delay to ensure the canvas is visible
    setTimeout(() => {
        initializeCouponUsageChart();
    }, 100);
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

// Comprehensive error handling function
function handleApiError(error, endpoint) {
    console.error(`API Error for ${endpoint}:`, error);
    
    if (error instanceof SyntaxError && error.message.includes('Unexpected token')) {
        console.error('🔍 JSON Parsing Error Detected:');
        console.error('   - Expected: JSON data');
        console.error('   - Received: HTML or other non-JSON content');
        console.error('   - Likely cause: Server returned error page (404, 500, etc.)');
        console.error('   - Solution: Check if API endpoint exists and is working');
    } else if (error.name === 'TypeError' && error.message.includes('fetch')) {
        console.error('🔍 Network Error Detected:');
        console.error('   - Likely cause: Network connectivity issue or CORS problem');
        console.error('   - Solution: Check network connection and CORS settings');
    } else {
        console.error('🔍 Unknown Error:', error.name, error.message);
    }
}

// Global error handler for unhandled promise rejections
window.addEventListener('unhandledrejection', function(event) {
    console.error('🚨 Unhandled promise rejection:', event.reason);
    
    // Check if it's a JSON parsing error
    if (event.reason instanceof SyntaxError && event.reason.message.includes('Unexpected token')) {
        handleApiError(event.reason, 'Unknown endpoint');
    }
    
    // Prevent the default behavior (which would log the error to console)
    event.preventDefault();
});

// Global error handler for JavaScript errors
window.addEventListener('error', function(event) {
    console.error('🚨 JavaScript error:', event.error);
    
    // Check if it's a JSON parsing error
    if (event.error instanceof SyntaxError && event.error.message.includes('Unexpected token')) {
        handleApiError(event.error, 'Unknown endpoint');
    }
});

// Keyboard Shortcuts System
class KeyboardShortcuts {
    constructor() {
        this.shortcuts = new Map();
        this.init();
    }
    
    init() {
        document.addEventListener('keydown', (e) => {
            this.handleKeydown(e);
        });
        
        // Register default shortcuts
        this.registerShortcuts();
    }
    
    registerShortcuts() {
        // Theme toggle: Ctrl/Cmd + Shift + T
        this.add('ctrl+shift+t', () => {
            toggleTheme();
            showNotification('Theme toggled (Ctrl+Shift+T)', 'info');
        });
        
        // Refresh data: Ctrl/Cmd + R
        this.add('ctrl+r', (e) => {
            e.preventDefault();
            refreshDashboardData();
        });
        
        // Search: Ctrl/Cmd + K
        this.add('ctrl+k', (e) => {
            e.preventDefault();
            const searchInput = document.querySelector('.woodash-search-input');
            if (searchInput) {
                searchInput.focus();
                showNotification('Search activated (Ctrl+K)', 'info');
            }
        });
        
        // Add Product: Ctrl/Cmd + Shift + P
        this.add('ctrl+shift+p', () => {
            const addProductBtn = document.getElementById('openProductModal');
            if (addProductBtn) {
                addProductBtn.click();
                showNotification('Add Product opened (Ctrl+Shift+P)', 'info');
            }
        });
        
        // Fullscreen: F11
        this.add('f11', (e) => {
            e.preventDefault();
            toggleFullscreen();
        });
        
        // Help: Ctrl/Cmd + ?
        this.add('ctrl+?', (e) => {
            e.preventDefault();
            this.showHelp();
        });
        
        // Escape: Close modals
        this.add('escape', () => {
            const modals = document.querySelectorAll('[id$="Modal"]');
            modals.forEach(modal => {
                if (modal.style.display !== 'none') {
                    window.closeModal();
                }
            });
        });
        
        // Navigation shortcuts
        this.add('ctrl+1', () => this.navigateToPage('dashboard'));
        this.add('ctrl+2', () => this.navigateToPage('products'));
        this.add('ctrl+3', () => this.navigateToPage('orders'));
        this.add('ctrl+4', () => this.navigateToPage('analytics'));
    }
    
    add(keys, callback) {
        this.shortcuts.set(keys, callback);
    }
    
    handleKeydown(e) {
        const key = this.getKeyString(e);
        const shortcut = this.shortcuts.get(key);
        
        if (shortcut) {
            shortcut(e);
        }
    }
    
    getKeyString(e) {
        const parts = [];
        
        if (e.ctrlKey || e.metaKey) parts.push('ctrl');
        if (e.shiftKey) parts.push('shift');
        if (e.altKey) parts.push('alt');
        
        parts.push(e.key.toLowerCase());
        
        return parts.join('+');
    }
    
    navigateToPage(page) {
        const navLink = document.querySelector(`[data-page="${page}"]`);
        if (navLink) {
            navLink.click();
            showNotification(`Navigated to ${page} (Ctrl+${page === 'dashboard' ? '1' : page === 'products' ? '2' : page === 'orders' ? '3' : '4'})`, 'info');
        }
    }
    
    showHelp() {
        const helpContent = `
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">Keyboard Shortcuts</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Toggle Theme</span>
                        <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl+Shift+T</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span>Refresh Data</span>
                        <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl+R</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span>Search</span>
                        <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl+K</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span>Add Product</span>
                        <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl+Shift+P</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span>Fullscreen</span>
                        <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">F11</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span>Dashboard</span>
                        <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl+1</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span>Products</span>
                        <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl+2</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span>Orders</span>
                        <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl+3</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span>Analytics</span>
                        <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl+4</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span>Close Modals</span>
                        <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Escape</kbd>
                    </div>
                    <div class="flex justify-between">
                        <span>Show Help</span>
                        <kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl+?</kbd>
                    </div>
                </div>
            </div>
        `;
        
        showNotification('Keyboard shortcuts help opened!', 'info');
        
        // Create a temporary modal for help
        const helpModal = document.createElement('div');
        helpModal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        helpModal.innerHTML = `
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
                ${helpContent}
                <div class="flex justify-end p-6 border-t border-gray-200">
                    <button onclick="this.closest('.fixed').remove()" class="woodash-btn woodash-btn-primary">
                        Close
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(helpModal);
        
        // Close on outside click
        helpModal.addEventListener('click', (e) => {
            if (e.target === helpModal) {
                helpModal.remove();
            }
        });
    }
}

// Initialize keyboard shortcuts
const keyboardShortcuts = new KeyboardShortcuts();

// Performance Optimization System
class PerformanceOptimizer {
    constructor() {
        this.observers = new Map();
        this.cache = new Map();
        this.init();
    }
    
    init() {
        this.setupIntersectionObserver();
        this.setupLazyLoading();
        this.optimizeImages();
        this.debounceScrollEvents();
    }
    
    setupIntersectionObserver() {
        // Lazy load charts and heavy components
        const chartObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const chartType = element.dataset.chartType;
                    
                    if (chartType) {
                        this.loadChart(element, chartType);
                        chartObserver.unobserve(element);
                    }
                }
            });
        }, { threshold: 0.1 });
        
        // Observe all chart elements
        document.querySelectorAll('[data-chart-type]').forEach(el => {
            chartObserver.observe(el);
        });
    }
    
    setupLazyLoading() {
        // Lazy load images
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.remove('woodash-skeleton');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    optimizeImages() {
        // Add loading states to images
        document.querySelectorAll('img').forEach(img => {
            if (!img.src) {
                img.classList.add('woodash-skeleton');
            }
        });
    }
    
    debounceScrollEvents() {
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                this.handleScroll();
            }, 16); // ~60fps
        });
    }
    
    handleScroll() {
        // Optimize scroll performance
        const scrollY = window.scrollY;
        
        // Update sticky elements
        document.querySelectorAll('.woodash-sticky').forEach(el => {
            el.style.transform = `translateY(${scrollY * 0.1}px)`;
        });
    }
    
    loadChart(element, chartType) {
        // Simulate chart loading with skeleton
        element.classList.add('woodash-skeleton');
        
        setTimeout(() => {
            element.classList.remove('woodash-skeleton');
            element.classList.add('woodash-card-entrance');
            
            // Initialize actual chart here
            console.log(`Loading ${chartType} chart`);
        }, 500);
    }
    
    // Cache management
    setCache(key, value, ttl = 300000) { // 5 minutes default
        this.cache.set(key, {
            value,
            timestamp: Date.now(),
            ttl
        });
    }
    
    getCache(key) {
        const cached = this.cache.get(key);
        if (cached && Date.now() - cached.timestamp < cached.ttl) {
            return cached.value;
        }
        this.cache.delete(key);
        return null;
    }
    
    // Memory management
    cleanup() {
        // Clear old cache entries
        for (const [key, value] of this.cache.entries()) {
            if (Date.now() - value.timestamp > value.ttl) {
                this.cache.delete(key);
            }
        }
    }
}

// Initialize performance optimizer
const performanceOptimizer = new PerformanceOptimizer();

// Cleanup cache every 5 minutes
setInterval(() => {
    performanceOptimizer.cleanup();
}, 300000);

// Advanced Search System
class AdvancedSearch {
    constructor() {
        this.searchIndex = new Map();
        this.searchHistory = [];
        this.init();
    }
    
    init() {
        this.buildSearchIndex();
        this.setupSearchUI();
    }
    
    buildSearchIndex() {
        // Index all searchable content
        const searchableElements = document.querySelectorAll('[data-searchable]');
        
        searchableElements.forEach(element => {
            const text = element.textContent.toLowerCase();
            const keywords = text.split(/\s+/).filter(word => word.length > 2);
            
            keywords.forEach(keyword => {
                if (!this.searchIndex.has(keyword)) {
                    this.searchIndex.set(keyword, []);
                }
                this.searchIndex.get(keyword).push({
                    element,
                    text: element.textContent,
                    type: element.dataset.searchable
                });
            });
        });
    }
    
    setupSearchUI() {
        // Create search overlay
        const searchOverlay = document.createElement('div');
        searchOverlay.id = 'search-overlay';
        searchOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 hidden';
        searchOverlay.innerHTML = `
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl">
                    <div class="p-6">
                        <div class="relative mb-4">
                            <input type="text" id="search-input" 
                                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="Search products, orders, customers...">
                            <i class="fa-solid fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <button id="search-close" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>
                        <div id="search-results" class="max-h-96 overflow-y-auto">
                            <div class="text-center text-gray-500 py-8">
                                <i class="fa-solid fa-search text-4xl mb-2"></i>
                                <p>Start typing to search...</p>
                            </div>
                        </div>
                        <div id="search-history" class="mt-4 hidden">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Recent searches</h4>
                            <div id="history-items" class="space-y-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(searchOverlay);
        
        // Setup event listeners
        this.setupSearchEvents();
    }
    
    setupSearchEvents() {
        const searchInput = document.getElementById('search-input');
        const searchResults = document.getElementById('search-results');
        const searchClose = document.getElementById('search-close');
        const searchOverlay = document.getElementById('search-overlay');
        
        let searchTimeout;
        
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.performSearch(e.target.value);
            }, 300);
        });
        
        searchClose.addEventListener('click', () => {
            this.closeSearch();
        });
        
        searchOverlay.addEventListener('click', (e) => {
            if (e.target === searchOverlay) {
                this.closeSearch();
            }
        });
        
        // Keyboard navigation
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeSearch();
            }
        });
    }
    
    performSearch(query) {
        if (!query.trim()) {
            this.showSearchHistory();
            return;
        }
        
        const results = this.search(query);
        this.displayResults(results, query);
        this.addToHistory(query);
    }
    
    search(query) {
        const keywords = query.toLowerCase().split(/\s+/);
        const results = new Map();
        
        keywords.forEach(keyword => {
            // Exact matches
            if (this.searchIndex.has(keyword)) {
                this.searchIndex.get(keyword).forEach(item => {
                    const key = `${item.type}-${item.element.id || item.text}`;
                    if (!results.has(key)) {
                        results.set(key, { ...item, score: 0 });
                    }
                    results.get(key).score += 10;
                });
            }
            
            // Partial matches
            for (const [indexKeyword, items] of this.searchIndex.entries()) {
                if (indexKeyword.includes(keyword)) {
                    items.forEach(item => {
                        const key = `${item.type}-${item.element.id || item.text}`;
                        if (!results.has(key)) {
                            results.set(key, { ...item, score: 0 });
                        }
                        results.get(key).score += 5;
                    });
                }
            }
        });
        
        return Array.from(results.values()).sort((a, b) => b.score - a.score);
    }
    
    displayResults(results, query) {
        const searchResults = document.getElementById('search-results');
        const searchHistory = document.getElementById('search-history');
        
        searchHistory.classList.add('hidden');
        
        if (results.length === 0) {
            searchResults.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <i class="fa-solid fa-search text-4xl mb-2"></i>
                    <p>No results found for "${query}"</p>
                </div>
            `;
            return;
        }
        
        const resultsHTML = results.slice(0, 10).map(result => `
            <div class="p-3 hover:bg-gray-50 rounded-lg cursor-pointer search-result-item" 
                 data-element-id="${result.element.id || ''}">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-${this.getIconForType(result.type)} text-purple-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium">${this.highlightQuery(result.text, query)}</div>
                        <div class="text-sm text-gray-500">${result.type}</div>
                    </div>
                    <div class="text-xs text-gray-400">${result.score}%</div>
                </div>
            </div>
        `).join('');
        
        searchResults.innerHTML = resultsHTML;
        
        // Add click handlers
        document.querySelectorAll('.search-result-item').forEach(item => {
            item.addEventListener('click', () => {
                const elementId = item.dataset.elementId;
                if (elementId) {
                    const element = document.getElementById(elementId);
                    if (element) {
                        element.scrollIntoView({ behavior: 'smooth' });
                        element.classList.add('woodash-pulse-glow');
                        setTimeout(() => {
                            element.classList.remove('woodash-pulse-glow');
                        }, 2000);
                    }
                }
                this.closeSearch();
            });
        });
    }
    
    highlightQuery(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
    }
    
    getIconForType(type) {
        const icons = {
            'product': 'box',
            'order': 'shopping-cart',
            'customer': 'user',
            'analytics': 'chart-line',
            'settings': 'cog'
        };
        return icons[type] || 'file';
    }
    
    addToHistory(query) {
        if (!this.searchHistory.includes(query)) {
            this.searchHistory.unshift(query);
            this.searchHistory = this.searchHistory.slice(0, 5); // Keep only 5 recent searches
        }
    }
    
    showSearchHistory() {
        const searchResults = document.getElementById('search-results');
        const searchHistory = document.getElementById('search-history');
        const historyItems = document.getElementById('history-items');
        
        searchResults.innerHTML = '';
        searchHistory.classList.remove('hidden');
        
        if (this.searchHistory.length === 0) {
            historyItems.innerHTML = '<p class="text-gray-500 text-sm">No recent searches</p>';
            return;
        }
        
        historyItems.innerHTML = this.searchHistory.map(query => `
            <div class="p-2 hover:bg-gray-50 rounded cursor-pointer history-item" data-query="${query}">
                <i class="fa-solid fa-clock text-gray-400 mr-2"></i>
                ${query}
            </div>
        `).join('');
        
        // Add click handlers for history
        document.querySelectorAll('.history-item').forEach(item => {
            item.addEventListener('click', () => {
                const query = item.dataset.query;
                document.getElementById('search-input').value = query;
                this.performSearch(query);
            });
        });
    }
    
    openSearch() {
        const searchOverlay = document.getElementById('search-overlay');
        const searchInput = document.getElementById('search-input');
        
        searchOverlay.classList.remove('hidden');
        searchInput.focus();
        this.showSearchHistory();
    }
    
    closeSearch() {
        const searchOverlay = document.getElementById('search-overlay');
        searchOverlay.classList.add('hidden');
    }
}

// Initialize advanced search
const advancedSearch = new AdvancedSearch();

// Global function to open search
window.openSearch = () => advancedSearch.openSearch();

// Real-time metrics simulation
function simulateRealTimeMetrics() {
    const visitors = document.getElementById('live-visitors');
    const cartAdditions = document.getElementById('cart-additions');
    const pageViews = document.getElementById('page-views');

    setInterval(() => {
        if (visitors) {
            const currentVisitors = parseInt(visitors.textContent);
            const change = Math.floor(Math.random() * 5) - 2; // -2 to +2
            visitors.textContent = Math.max(0, currentVisitors + change);
        }
        
        if (cartAdditions) {
            const current = parseInt(cartAdditions.textContent);
            if (Math.random() < 0.3) { // 30% chance to increase
                cartAdditions.textContent = current + 1;
            }
        }
        
        if (pageViews) {
            const current = parseInt(pageViews.textContent);
            const increment = Math.floor(Math.random() * 3) + 1;
            pageViews.textContent = current + increment;
        }
    }, 5000); // Update every 5 seconds
}

// Task management functionality
function initTaskManager() {
    const addTaskBtn = document.getElementById('add-task-btn');
    const taskList = document.getElementById('task-list');
    
    addTaskBtn?.addEventListener('click', () => {
        const taskText = prompt('Enter a new task:');
        if (taskText) {
            addNewTask(taskText, 'Medium');
        }
    });
    
    // Add event listeners to existing checkboxes
    document.querySelectorAll('#task-list input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateTaskProgress);
    });
}

function addNewTask(text, priority) {
    const taskList = document.getElementById('task-list');
    const taskDiv = document.createElement('div');
    taskDiv.className = 'flex items-center gap-3 p-3 bg-gray-50 rounded-lg';
    
    taskDiv.innerHTML = `
        <input type="checkbox" class="w-4 h-4 text-[#814ce4] rounded focus:ring-[#814ce4]">
        <span class="flex-1 text-sm">${text}</span>
        <span class="text-xs text-gray-500">${priority}</span>
    `;
    
    taskList.appendChild(taskDiv);
    
    // Add event listener to new checkbox
    taskDiv.querySelector('input').addEventListener('change', updateTaskProgress);
    updateTaskProgress();
}

function updateTaskProgress() {
    const checkboxes = document.querySelectorAll('#task-list input[type="checkbox"]');
    const completed = document.querySelectorAll('#task-list input[type="checkbox"]:checked').length;
    const total = checkboxes.length;
    
    // Update text spans based on checkbox state
    checkboxes.forEach(checkbox => {
        const span = checkbox.nextElementSibling;
        if (checkbox.checked) {
            span.classList.add('line-through', 'text-gray-500');
        } else {
            span.classList.remove('line-through', 'text-gray-500');
        }
    });
    
    // Update progress
    const progressText = document.querySelector('#task-list').parentElement.querySelector('p');
    const progressBar = document.querySelector('#task-list').parentElement.querySelector('.woodash-progress-bar');
    
    if (progressText) {
        progressText.textContent = `${completed} of ${total} tasks completed`;
    }
    
    if (progressBar) {
        const percentage = total > 0 ? (completed / total) * 100 : 0;
        progressBar.style.width = `${percentage}%`;
    }
}

// Weather widget functionality
function initWeatherWidget() {
    // Simulate weather data
    const weatherIcons = ['fa-sun', 'fa-cloud', 'fa-cloud-rain', 'fa-snowflake'];
    const weatherConditions = ['Sunny', 'Cloudy', 'Rainy', 'Snowy'];
    const temperatures = [18, 22, 15, 5];
    
    const weatherIcon = document.querySelector('.fa-sun');
    const tempElement = weatherIcon?.parentElement.nextElementSibling;
    const conditionElement = tempElement?.nextElementSibling;
    
    // Update weather every hour (simulated)
    setInterval(() => {
        if (Math.random() < 0.1) { // 10% chance to change weather
            const randomIndex = Math.floor(Math.random() * weatherIcons.length);
            
            if (weatherIcon) {
                weatherIcon.className = `fa-solid ${weatherIcons[randomIndex]} text-4xl text-yellow-500`;
            }
            
            if (tempElement) {
                tempElement.textContent = `${temperatures[randomIndex]}°C`;
            }
            
            if (conditionElement) {
                conditionElement.textContent = weatherConditions[randomIndex];
            }
        }
    }, 60000); // Check every minute
}

// Initialize new features
document.addEventListener('DOMContentLoaded', function() {
    simulateRealTimeMetrics();
    initTaskManager();
    initWeatherWidget();
});

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

    // Export functionality
    const exportBtn = document.getElementById('export-btn');
    const exportDropdown = document.getElementById('export-dropdown');
    
    exportBtn?.addEventListener('click', () => {
        exportDropdown.classList.toggle('hidden');
    });
    
    // Handle export options
    document.querySelectorAll('[data-export]').forEach(item => {
        item.addEventListener('click', () => {
            const format = item.getAttribute('data-export');
            exportData(format);
            exportDropdown.classList.add('hidden');
        });
    });

    // Settings functionality
    const settingsBtn = document.getElementById('settings-btn');
    const settingsDropdown = document.getElementById('settings-dropdown');
    
    settingsBtn?.addEventListener('click', () => {
        settingsDropdown.classList.toggle('hidden');
    });
    
    // Handle settings options
    document.getElementById('theme-toggle')?.addEventListener('click', toggleTheme);
    document.getElementById('refresh-data')?.addEventListener('click', refreshDashboardData);
    document.getElementById('fullscreen-toggle')?.addEventListener('click', toggleFullscreen);

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
                } else if (chartId === 'revenue-category-chart') {
                    initRevenueCategoryChart();
                } else if (chartId === 'goal-progress-chart') {
                    initGoalProgressChart();
                }
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    // Observe chart elements
    document.querySelectorAll('#sales-chart, [id^="mini-trend-"], #revenue-category-chart, #goal-progress-chart').forEach(chart => {
        observer.observe(chart);
    });
}

// Initialize revenue category chart
function initRevenueCategoryChart() {
    const ctx = document.getElementById('revenue-category-chart')?.getContext('2d');
    if (!ctx) return;

    const chartConfig = {
        type: 'doughnut',
        data: {
            labels: ['Electronics', 'Clothing', 'Books', 'Home & Garden', 'Sports'],
            datasets: [{
                data: [35, 25, 15, 15, 10],
                backgroundColor: ['#814ce4', '#7849d1ff', '#5d21cbff', '#470db4ff', '#3d05a5ff'],
                borderWidth: 0,
                cutout: '60%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 12,
                            family: "ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial"
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + '%';
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                duration: 1000
            }
        }
    };

    createOptimizedChart(ctx, chartConfig);
}

// Initialize goal progress chart
function initGoalProgressChart() {
    const ctx = document.getElementById('goal-progress-chart')?.getContext('2d');
    if (!ctx) return;

    const progress = 72; // 72% completion
    
    const chartConfig = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [progress, 100 - progress],
                backgroundColor: ['#814ce4', '#E5E7EB'],
                borderWidth: 0,
                cutout: '85%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            },
            animation: {
                animateRotate: true,
                duration: 1500
            }
        }
    };

    createOptimizedChart(ctx, chartConfig);
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
                borderColor: '#814ce4',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(130, 77, 235, 0.1)'
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
    const baseColor = chartId === 'mini-trend-orders' ? '#814ce4'
                     : chartId === 'mini-trend-aov' ? '#814ce4'
                     : chartId === 'mini-trend-customers' ? '#814ce4'
                     : chartId === 'mini-trend-profit' ? '#814ce4'
                     : '#814ce4';

    // Subtle vertical gradient fill
    const gradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
    gradient.addColorStop(0, 'rgba(130, 77, 235, 0.18)');
    gradient.addColorStop(1, 'rgba(130, 77, 235, 0)');

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
async function initTopProductsChart() {
    const ctx = document.getElementById('top-products-chart')?.getContext('2d');
    if (!ctx) return;

    const data = await fetchData('/api/top-products');
    if (!data) return;

    const chartConfig = {
        type: 'bar',
        data: {
            labels: data.map(item => item.name),
            datasets: [{
                label: 'Sales',
                data: data.map(item => item.sales),
                backgroundColor: '#814ce4',
                borderColor: '#814ce4',
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
async function initTopCustomersChart() {
    const ctx = document.getElementById('top-customers-chart')?.getContext('2d');
    if (!ctx) return;

    const data = await fetchData('/api/top-customers');
    if (!data) return;

    const chartConfig = {
        type: 'bar',
        data: {
            labels: data.map(item => item.name),
            datasets: [{
                label: 'Total Spent',
                data: data.map(item => item.total),
                backgroundColor: '#814ce4',
                borderColor: '#814ce4',
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
        'mini-trend-profit': 80,
        'mini-trend-products': 245
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

// Add notification system
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `woodash-notification woodash-notification-${type}`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fa-solid ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + / for search focus
    if ((e.ctrlKey || e.metaKey) && e.key === '/') {
        e.preventDefault();
        document.getElementById('woodash-search')?.focus();
    }
    
    // Escape to close dropdowns
    if (e.key === 'Escape') {
        document.querySelectorAll('.woodash-dropdown:not(.hidden)').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }
});

// Auto-save functionality for tasks
function autoSaveState() {
    const tasks = [];
    document.querySelectorAll('#task-list > div').forEach(taskDiv => {
        const checkbox = taskDiv.querySelector('input[type="checkbox"]');
        const text = taskDiv.querySelector('span').textContent;
        const priority = taskDiv.querySelector('.text-xs').textContent;
        
        tasks.push({
            text: text,
            priority: priority,
            completed: checkbox.checked
        });
    });
    
    localStorage.setItem('woodash_tasks', JSON.stringify(tasks));
}

// Load saved state
function loadSavedState() {
    const savedTasks = localStorage.getItem('woodash_tasks');
    if (savedTasks) {
        const tasks = JSON.parse(savedTasks);
        const taskList = document.getElementById('task-list');
        
        // Clear existing tasks
        taskList.innerHTML = '';
        
        // Recreate tasks
        tasks.forEach(task => {
            const taskDiv = document.createElement('div');
            taskDiv.className = 'flex items-center gap-3 p-3 bg-gray-50 rounded-lg';
            
            taskDiv.innerHTML = `
                <input type="checkbox" class="w-4 h-4 text-[#814ce4] rounded focus:ring-[#814ce4]" ${task.completed ? 'checked' : ''}>
                <span class="flex-1 text-sm ${task.completed ? 'line-through text-gray-500' : ''}">${task.text}</span>
                <span class="text-xs text-gray-500">${task.priority}</span>
            `;
            
            taskList.appendChild(taskDiv);
            taskDiv.querySelector('input').addEventListener('change', () => {
                updateTaskProgress();
                autoSaveState();
            });
        });
        
        updateTaskProgress();
    }
}

// Initialize saved state on load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(loadSavedState, 100);
});

// Export functionality
function exportData(format) {
    showNotification(`Exporting data as ${format.toUpperCase()}...`, 'success');
    
    // Simulate export process
    setTimeout(() => {
        if (format === 'csv') {
            exportAsCSV();
        } else if (format === 'pdf') {
            exportAsPDF();
        } else if (format === 'excel') {
            exportAsExcel();
        }
    }, 1000);
}

function exportAsCSV() {
    const data = [
        ['Metric', 'Value', 'Change'],
        ['Total Sales', '$72,450', '+12.5%'],
        ['Total Orders', '156', '+8.2%'],
        ['Average Order Value', '$464.42', '-3.1%'],
        ['New Customers', '42', '+15.3%'],
        ['Total Products', '245', '+5'],
    ];
    
    const csvContent = data.map(row => row.join(',')).join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `dashboard-export-${new Date().toISOString().split('T')[0]}.csv`;
    a.click();
    window.URL.revokeObjectURL(url);
    
    showNotification('CSV export completed!', 'success');
}

function exportAsPDF() {
    showNotification('PDF export feature coming soon!', 'info');
}

function exportAsExcel() {
    showNotification('Excel export feature coming soon!', 'info');
}

// Theme toggle functionality
function toggleTheme() {
    const dashboard = document.getElementById('woodash-dashboard');
    const themeToggle = document.getElementById('theme-toggle');
    const isDark = dashboard.classList.contains('dark-theme');
    
    if (isDark) {
        // Switch to light theme
        dashboard.classList.remove('dark-theme');
        localStorage.setItem('woodash_theme', 'light');
        
        // Update theme toggle icon and text
        if (themeToggle) {
            const icon = themeToggle.querySelector('i');
            const text = themeToggle.querySelector('span');
            if (icon) icon.className = 'fa-solid fa-sun text-yellow-500';
            if (text) text.textContent = 'Switch to Dark';
        }
        
        showNotification('Switched to light theme', 'success');
    } else {
        // Switch to dark theme
        dashboard.classList.add('dark-theme');
        localStorage.setItem('woodash_theme', 'dark');
        
        // Update theme toggle icon and text
        if (themeToggle) {
            const icon = themeToggle.querySelector('i');
            const text = themeToggle.querySelector('span');
            if (icon) icon.className = 'fa-solid fa-moon text-blue-400';
            if (text) text.textContent = 'Switch to Light';
        }
        
        showNotification('Switched to dark theme', 'success');
    }
    
    // Update system theme preference if supported
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        // System prefers dark theme
        console.log('System theme preference detected');
    }
}

// Refresh data functionality
function refreshDashboardData() {
    showNotification('Refreshing dashboard data...', 'success');
    
    // Simulate data refresh
    setTimeout(() => {
        // Update metric values with animation
        animateNumbers();
        showNotification('Dashboard data refreshed!', 'success');
    }, 2000);
}

// Fullscreen toggle
function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().then(() => {
            showNotification('Entered fullscreen mode', 'success');
        });
    } else {
        document.exitFullscreen().then(() => {
            showNotification('Exited fullscreen mode', 'success');
        });
    }
}

// Number animation function
function animateNumbers() {
    const numberElements = document.querySelectorAll('.woodash-metric-value');
    
    numberElements.forEach(element => {
        element.classList.add('woodash-number-animate');
        
        // Simulate number update
        const currentValue = element.textContent;
        if (currentValue.includes('$')) {
            const baseValue = parseFloat(currentValue.replace(/[$,]/g, ''));
            const change = (Math.random() - 0.5) * baseValue * 0.1; // ±10% change
            const newValue = Math.max(0, baseValue + change);
            element.textContent = '$' + newValue.toLocaleString(undefined, {maximumFractionDigits: 0});
        } else if (currentValue.includes('%')) {
            const baseValue = parseFloat(currentValue.replace('%', ''));
            const change = (Math.random() - 0.5) * 2; // ±1% change
            const newValue = Math.max(0, baseValue + change);
            element.textContent = newValue.toFixed(1) + '%';
        } else if (!isNaN(parseInt(currentValue))) {
            const baseValue = parseInt(currentValue);
            const change = Math.floor((Math.random() - 0.5) * baseValue * 0.1); // ±10% change
            const newValue = Math.max(0, baseValue + change);
            element.textContent = newValue.toString();
        }
        
        setTimeout(() => {
            element.classList.remove('woodash-number-animate');
        }, 800);
    });
}

// Load theme preference and initialize theme toggle
document.addEventListener('DOMContentLoaded', function() {
    const dashboard = document.getElementById('woodash-dashboard');
    const themeToggle = document.getElementById('theme-toggle');
    const savedTheme = localStorage.getItem('woodash_theme');
    
    // Check system preference if no saved theme
    const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const shouldUseDark = savedTheme === 'dark' || (!savedTheme && systemPrefersDark);
    
    if (shouldUseDark) {
        dashboard.classList.add('dark-theme');
        localStorage.setItem('woodash_theme', 'dark');
        
        // Update theme toggle button
        if (themeToggle) {
            const icon = themeToggle.querySelector('i');
            const text = themeToggle.querySelector('span');
            if (icon) icon.className = 'fa-solid fa-moon text-blue-400';
            if (text) text.textContent = 'Switch to Light';
        }
    } else {
        // Update theme toggle button for light theme
        if (themeToggle) {
            const icon = themeToggle.querySelector('i');
            const text = themeToggle.querySelector('span');
            if (icon) icon.className = 'fa-solid fa-sun text-yellow-500';
            if (text) text.textContent = 'Switch to Dark';
        }
    }
    
    // Listen for system theme changes
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            if (!localStorage.getItem('woodash_theme')) {
                // Only auto-switch if user hasn't manually set a preference
                if (e.matches) {
                    dashboard.classList.add('dark-theme');
                } else {
                    dashboard.classList.remove('dark-theme');
                }
            }
        });
    }
});

// AI Assistant Functionality
let aiChatOpen = false;
let isTyping = false;

function toggleAIChat() {
    const chatWidget = document.getElementById('woodash-ai-chat');
    const toggleButton = document.getElementById('ai-toggle');
    
    aiChatOpen = !aiChatOpen;
    
    if (aiChatOpen) {
        chatWidget.classList.add('active');
        toggleButton.style.transform = 'scale(0.9)';
        // Focus on input when chat opens
        setTimeout(() => {
            document.getElementById('ai-input').focus();
        }, 300);
    } else {
        chatWidget.classList.remove('active');
        toggleButton.style.transform = 'scale(1)';
    }
}

function sendAIMessage() {
    const input = document.getElementById('ai-input');
    const message = input.value.trim();
    
    if (message) {
        addMessage(message, 'user');
        input.value = '';
        
        // Show typing indicator
        showTypingIndicator();
        
        // Simulate AI response after delay
        setTimeout(() => {
            hideTypingIndicator();
            const response = generateAIResponse(message);
            addMessage(response, 'ai');
        }, 1500 + Math.random() * 1000);
    }
}

function sendQuickMessage(message) {
    addMessage(message, 'user');
    
    // Show typing indicator
    showTypingIndicator();
    
    // Simulate AI response
    setTimeout(() => {
        hideTypingIndicator();
        const response = generateAIResponse(message);
        addMessage(response, 'ai');
    }, 1000 + Math.random() * 500);
}

function addMessage(content, sender) {
    const messagesContainer = document.getElementById('ai-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `woodash-ai-message ${sender}`;
    
    const avatar = document.createElement('div');
    avatar.className = 'woodash-ai-message-avatar';
    avatar.innerHTML = sender === 'ai' ? '<i class="fa-solid fa-robot text-xs"></i>' : '<i class="fa-solid fa-user text-xs"></i>';
    
    const messageContent = document.createElement('div');
    messageContent.className = 'woodash-ai-message-content';
    messageContent.textContent = content;
    
    messageDiv.appendChild(avatar);
    messageDiv.appendChild(messageContent);
    
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    
    // Add animation
    messageDiv.style.opacity = '0';
    messageDiv.style.transform = 'translateY(10px)';
    requestAnimationFrame(() => {
        messageDiv.style.transition = 'all 0.3s ease';
        messageDiv.style.opacity = '1';
        messageDiv.style.transform = 'translateY(0)';
    });
}

function showTypingIndicator() {
    if (isTyping) return;
    isTyping = true;
    
    const messagesContainer = document.getElementById('ai-messages');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'woodash-ai-typing';
    typingDiv.id = 'typing-indicator';
    typingDiv.innerHTML = `
        <span>AI is typing</span>
        <div class="flex gap-1 ml-2">
            <div class="woodash-ai-typing-dot"></div>
            <div class="woodash-ai-typing-dot"></div>
            <div class="woodash-ai-typing-dot"></div>
        </div>
    `;
    
    messagesContainer.appendChild(typingDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function hideTypingIndicator() {
    isTyping = false;
    const typingIndicator = document.getElementById('typing-indicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

function generateAIResponse(userMessage) {
    const message = userMessage.toLowerCase();
    
    // Simple response generation based on keywords
    if (message.includes('sales') || message.includes('revenue')) {
        return "Your sales are performing well! Based on current trends, I recommend focusing on your electronics category which shows 23% growth. Would you like me to create a detailed sales analysis?";
    } else if (message.includes('customers') || message.includes('customer')) {
        return "You have 42 new customers this month. I've noticed that mobile users have higher conversion rates. Consider optimizing your mobile experience for better results.";
    } else if (message.includes('products') || message.includes('inventory')) {
        return "Your top-selling products are Wireless Headphones and Smart Watches. However, 3 items are running low on stock. Shall I help you create a reorder list?";
    } else if (message.includes('trends') || message.includes('analyze')) {
        return "I've analyzed your data and found some interesting patterns. Your peak sales hours are 2-4 PM and 7-9 PM. Weekend sales are 18% higher than weekdays. Would you like more detailed insights?";
    } else if (message.includes('help') || message.includes('what can you do')) {
        return "I can help you with: 📊 Sales analysis, 👥 Customer insights, 📦 Inventory management, 💰 Pricing optimization, 📈 Trend forecasting, and 📋 Report generation. What would you like to explore?";
    } else if (message.includes('pricing') || message.includes('price')) {
        return "Based on market analysis, I suggest slight price adjustments: Increase Smart Watch prices by 5% (demand is high), and consider bundle deals for slower-moving items. This could boost revenue by 8-12%.";
    } else {
        const responses = [
            "That's an interesting question! Based on your store data, I can provide specific insights. Could you be more specific about what you'd like to know?",
            "I'm analyzing your request. Your dashboard shows some great opportunities for optimization. What aspect would you like me to focus on?",
            "Great question! I have access to all your store metrics. Let me know if you'd like insights about sales, customers, products, or forecasting.",
            "I'm here to help optimize your business! Your current performance is strong. Would you like me to identify areas for improvement?",
            "Based on your store's performance, I can provide actionable recommendations. What's your main goal right now - increasing sales, improving customer retention, or optimizing inventory?"
        ];
        return responses[Math.floor(Math.random() * responses.length)];
    }
}

function handleAIInputKeypress(event) {
    if (event.key === 'Enter') {
        sendAIMessage();
    }
}

// AI Quick Actions
function triggerAIAction(action) {
    showNotification('AI action triggered: ' + action.replace('-', ' '), 'success');
    
    switch(action) {
        case 'optimize-prices':
            setTimeout(() => {
                showNotification('Price optimization complete! 3 products updated.', 'success');
                if (aiChatOpen) {
                    addMessage("I've analyzed your pricing and made recommendations for 3 products. Smart Watch prices can be increased by 5%, while Bluetooth Speaker bundles could boost sales by 15%.", 'ai');
                }
            }, 2000);
            break;
            
        case 'generate-report':
            setTimeout(() => {
                showNotification('AI report generated successfully!', 'success');
                if (aiChatOpen) {
                    addMessage("I've generated a comprehensive performance report. Key highlights: 12.5% sales growth, 42 new customers, and inventory alerts for 3 products. Would you like me to email this to you?", 'ai');
                }
            }, 1500);
            break;
            
        case 'predict-demand':
            setTimeout(() => {
                showNotification('Demand prediction completed!', 'success');
                if (aiChatOpen) {
                    addMessage("Based on historical data and current trends, I predict: Electronics will see 18% increase next month, especially Smart Watches. Stock up on Wireless Headphones before the holiday season.", 'ai');
                }
            }, 2500);
            break;
            
        case 'customer-segments':
            setTimeout(() => {
                showNotification('Customer segmentation analysis complete!', 'success');
                if (aiChatOpen) {
                    addMessage("I've identified 4 customer segments: Premium Buyers (23%), Budget Conscious (34%), Tech Enthusiasts (28%), and Casual Shoppers (15%). Each group shows different buying patterns. Want details?", 'ai');
                }
            }, 2000);
            break;
    }
}

// Refresh AI insights
function refreshAIInsights() {
    showNotification('Refreshing AI insights...', 'success');
    
    // Simulate insight refresh with animation
    const insights = document.querySelectorAll('.woodash-ai-insight-item');
    insights.forEach((insight, index) => {
        setTimeout(() => {
            insight.style.transform = 'scale(0.95)';
            insight.style.opacity = '0.7';
            
            setTimeout(() => {
                insight.style.transform = 'scale(1)';
                insight.style.opacity = '1';
            }, 200);
        }, index * 100);
    });
    
    setTimeout(() => {
        showNotification('AI insights updated!', 'success');
    }, 1000);
}

// Initialize AI system
document.addEventListener('DOMContentLoaded', function() {
    // Add welcome message after delay
    setTimeout(() => {
        if (!aiChatOpen) {
            // Show a subtle notification that AI is available
            const aiToggle = document.getElementById('ai-toggle');
            aiToggle.style.animation = 'woodash-ai-pulse 2s infinite';
            
            setTimeout(() => {
                aiToggle.style.animation = '';
            }, 6000);
        }
    }, 3000);
});

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#export-btn') && !e.target.closest('#export-dropdown')) {
        document.getElementById('export-dropdown')?.classList.add('hidden');
    }
    if (!e.target.closest('#settings-btn') && !e.target.closest('#settings-dropdown')) {
        document.getElementById('settings-dropdown')?.classList.add('hidden');
    }
    if (!e.target.closest('#notifications-btn') && !e.target.closest('#notifications-dropdown')) {
        document.getElementById('notifications-dropdown')?.classList.add('hidden');
    }
});

// Page Navigation System
class WoodashPageManager {
    constructor() {
        this.currentPage = 'dashboard';
        this.pages = ['dashboard', 'analytics', 'products', 'orders', 'customers', 'coupons', 'inventory', 'reviews', 'reports','integrations', 'settings'];
        this.pageData = {
            'dashboard': {
                title: 'Dashboard',
                description: 'Welcome back, John! Here\'s what\'s happening with your store.'
            },
            'analytics': {
                title: 'Analytics',
                description: 'Detailed performance metrics and business insights.'
            },
            'products': {
                title: 'Products',
                description: 'Manage your product catalog and inventory.'
            },
            'orders': {
                title: 'Orders',
                description: 'Track and manage customer orders.'
            },
            'customers': {
                title: 'Customers',
                description: 'View and manage your customer relationships.'
            },
            'coupons': {
                title: 'Coupons',
                description: 'View and manage your coupons relationships.'
            },
            'inventory': {
                title: 'Inventory',
                description: 'Monitor and control your stock levels.'
            },
            'reviews': {
                title: 'Reviews',
                description: 'Generate comprehensive business reviews.'
            },
            'reports': {
                title: 'Reports',
                description: 'Generate comprehensive business reports.'
            },
            'integrations': {
                title: 'Integrations',
                description: 'Integration'
            },
            'settings': {
                title: 'Settings',
                description: 'Configure your dashboard and account preferences.'
            }
        };
        this.init();
    }

    init() {
        // Add click handlers to navigation links
        document.querySelectorAll('.woodash-nav-link[data-page]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = link.getAttribute('data-page');
                this.navigateToPage(page);
            });
        });

        // Set initial active page
        this.navigateToPage('dashboard');
    }

    navigateToPage(pageName) {
        if (!this.pages.includes(pageName)) {
            console.warn(`Page "${pageName}" not found`);
            return;
        }

        // Update current page
        this.currentPage = pageName;

        // Update navigation active state
        document.querySelectorAll('.woodash-nav-link').forEach(link => {
            link.classList.remove('active');
        });
        document.querySelector(`[data-page="${pageName}"]`)?.classList.add('active');

        // Update page title and description
        const pageData = this.pageData[pageName];
        if (pageData) {
            document.getElementById('page-title').textContent = pageData.title;
            document.getElementById('page-description').textContent = pageData.description;
        }

        // Hide all pages
        document.querySelectorAll('.woodash-page-content').forEach(page => {
            page.classList.remove('active');
            page.classList.add('hidden');
        });

        // Show current page with animation
        setTimeout(() => {
            const targetPage = document.getElementById(`${pageName}-page`);
            if (targetPage) {
                targetPage.classList.remove('hidden');
                targetPage.classList.add('active');
                
                // Initialize page-specific functionality
                this.initPageFeatures(pageName);
            }
        }, 100);

        // Update URL without refresh (optional)
        if (history.pushState) {
            const url = new URL(window.location);
            url.searchParams.set('page', pageName);
            history.pushState({page: pageName}, '', url);
        }

        // Show notification
        showNotification(`Switched to ${pageData.title}`, 'success');
    }

    initPageFeatures(pageName) {
        // Initialize page-specific features
        switch(pageName) {
            case 'analytics':
                this.initAnalyticsCharts();
                break;
            case 'products':
                this.initProductsFeatures();
                break;
            case 'orders':
                this.initOrdersFeatures();
                break;
            case 'customers':
                this.initCustomersFeatures();
                break;
            case 'inventory':
                this.initInventoryFeatures();
                break;
            case 'reports':
                this.initReportsFeatures();
                break;
            case 'settings':
                this.initSettingsFeatures();
                break;
        }
    }

    initAnalyticsCharts() {
        // Initialize analytics charts
        const advancedCtx = document.getElementById('advanced-analytics-chart');
        if (advancedCtx && !advancedCtx.chart) {
            const ctx = advancedCtx.getContext('2d');
            advancedCtx.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue',
                        data: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000, 32000, 40000, 38000, 45000],
                        borderColor: '#814ce4',
                        backgroundColor: 'rgba(130, 77, 235, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Orders',
                        data: [120, 190, 150, 250, 220, 300, 280, 350, 320, 400, 380, 450],
                        borderColor: '#2d0873',
                        backgroundColor: 'rgba(130, 77, 235, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12
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

        // Revenue breakdown chart
        const revenueCtx = document.getElementById('revenue-breakdown-chart');
        if (revenueCtx && !revenueCtx.chart) {
            const ctx = revenueCtx.getContext('2d');
            revenueCtx.chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Online Store', 'Mobile App', 'Marketplace', 'Wholesale', 'Other'],
                    datasets: [{
                        data: [45, 25, 15, 10, 5],
                        backgroundColor: ['#814ce4', '#7849d1ff', '#5d21cbff', '#470db4ff', '#3d05a5ff'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 20 }
                        }
                    }
                }
            });
        }
    }

    initProductsFeatures() {
        // Add product management features
        console.log('Initializing products features...');
    }

    initOrdersFeatures() {
        // Add order management features
        console.log('Initializing orders features...');
    }

    initCustomersFeatures() {
        // Add customer management features
        console.log('Initializing customers features...');
    }

    initInventoryFeatures() {
        // Add inventory management features
        console.log('Initializing inventory features...');
    }

    initReportsFeatures() {
        // Add reports features
        const reportButtons = document.querySelectorAll('#reports-page .woodash-btn-primary');
        reportButtons.forEach(button => {
            button.addEventListener('click', () => {
                const reportType = button.closest('.woodash-chart-container').querySelector('h3');
                showNotification(`Generating ${reportType}...`, 'success');
                
                setTimeout(() => {
                    showNotification(`${reportType} generated successfully!`, 'success');
                }, 2000);
            });
        });
    }

    initSettingsFeatures() {
        // Add settings features
        const saveButton = document.querySelector('#settings-page .woodash-btn-primary');
        if (saveButton) {
            saveButton.addEventListener('click', () => {
                showNotification('Settings saved successfully!', 'success');
            });
        }
    }

    initProductModal() {
        // Product modal functionality
        const openModalBtn = document.getElementById('openProductModal');
        const closeModalBtn = document.getElementById('closeProductModal');
        const cancelBtn = document.getElementById('cancelProductForm');
        const modal = document.getElementById('addProductModal');
        const form = document.getElementById('addProductForm');
        const imageInput = document.getElementById('productImage');
        const imageUploadArea = document.getElementById('imageUploadArea');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        console.log('Initializing product modal...', {
            openModalBtn: !!openModalBtn,
            modal: !!modal,
            form: !!form
        });

        // Open modal
        if (openModalBtn) {
            openModalBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Add Product button clicked');
                if (modal) {
                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    console.log('Modal opened');
                } else {
                    console.error('Modal not found');
                }
            });
        } else {
            console.error('Open modal button not found');
        }

        // Close modal functions
        const closeModal = () => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            if (form) form.reset();
            if (imagePreview) imagePreview.style.display = 'none';
        };

        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeModal);
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeModal);
        }

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        // Image upload functionality
        if (imageUploadArea && imageInput) {
            imageUploadArea.addEventListener('click', () => {
                imageInput.click();
            });

            imageInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImg.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        imageUploadArea.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Drag and drop functionality
            imageUploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                imageUploadArea.classList.add('border-purple-400', 'bg-purple-50');
            });

            imageUploadArea.addEventListener('dragleave', (e) => {
                e.preventDefault();
                imageUploadArea.classList.remove('border-purple-400', 'bg-purple-50');
            });

            imageUploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                imageUploadArea.classList.remove('border-purple-400', 'bg-purple-50');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        imageInput.files = files;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            previewImg.src = e.target.result;
                            imagePreview.classList.remove('hidden');
                            imageUploadArea.classList.add('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        showNotification('Please select a valid image file', 'error');
                    }
                }
            });
        }

        // Form submission
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(form);
                const productData = {
                    name: formData.get('productName'),
                    sku: formData.get('productSku'),
                    category: formData.get('productCategory'),
                    price: parseFloat(formData.get('productPrice')),
                    stock: parseInt(formData.get('productStock')),
                    weight: parseFloat(formData.get('productWeight')) || 0,
                    dimensions: formData.get('productDimensions'),
                    description: formData.get('productDescription'),
                    status: formData.get('productStatus'),
                    image: formData.get('productImage')
                };

                // Validate required fields
                if (!productData.name || !productData.sku || !productData.category || !productData.price || productData.stock === null) {
                    showNotification('Please fill in all required fields', 'error');
                    return;
                }

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Adding Product...';
                submitBtn.disabled = true;

                // Simulate API call (replace with actual API call)
                setTimeout(() => {
                    // Here you would typically send the data to your backend
                    console.log('Product data:', productData);
                    
                    // Show success notification
                    showNotification('Product added successfully!', 'success');
                    
                    // Close modal and reset form
                    closeModal();
                    
                    // Reset button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    
                    // Here you could also refresh the product table or add the new product to the table
                    // refreshProductTable();
                    
                }, 1500);
            });
        }
    }
}

// Initialize page manager
let pageManager;
document.addEventListener('DOMContentLoaded', function() {
    pageManager = new WoodashPageManager();
    
    // Initialize product modal
    pageManager.initProductModal();
    
    // Handle browser back/forward
    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.page) {
            pageManager.navigateToPage(e.state.page);
        }
    });
    
    // Handle URL parameters on load
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('page');
    if (page && pageManager.pages.includes(page)) {
        pageManager.navigateToPage(page);
    }
});

// Add keyboard shortcuts for page navigation
document.addEventListener('keydown', function(e) {
    if (e.altKey) {
        const keyMap = {
            '1': 'dashboard',
            '2': 'analytics', 
            '3': 'products',
            '4': 'orders',
            '5': 'customers',
            '6': 'inventory',
            '7': 'reports',
            '8': 'settings'
        };
        
        if (keyMap[e.key]) {
            e.preventDefault();
            pageManager.navigateToPage(keyMap[e.key]);
        }
    }
});
</script>

<script>
// Feedback form handler for Updates page
document.addEventListener('DOMContentLoaded', function() {
    const feedbackForm = document.getElementById('update-feedback-form');
    const feedbackInput = document.getElementById('update-feedback-input');
    const feedbackSuccess = document.getElementById('update-feedback-success');
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (feedbackInput.value.trim()) {
                feedbackSuccess.classList.remove('hidden');
                feedbackInput.value = '';
                setTimeout(() => feedbackSuccess.classList.add('hidden'), 3000);
            }
        });
    }
});
</script>