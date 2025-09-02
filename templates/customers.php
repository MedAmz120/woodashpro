<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

// Check if user has required capabilities
if (!current_user_can('manage_options')) {
	wp_die(__('You do not have sufficient permissions to access this page.', 'woodash-pro'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Customers Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <link rel="stylesheet" href="<?php echo plugin_dir_url(dirname(__FILE__)) . 'assets/css/buttons.css'; ?>">

	<style>
		/* Enhanced, modern, soft style */
		body {
			background: #f3f4f6;
		}
		.woodash-card {
			background: #fff;
			border-radius: 1.25rem;
			box-shadow: 0 4px 24px 0 rgba(0,0,0,0.07), 0 1.5px 4px 0 rgba(0,0,0,0.03);
			border: none;
			transition: box-shadow 0.2s, transform 0.2s;
		}
		.woodash-card:hover {
			box-shadow: 0 8px 32px 0 rgba(0,204,97,0.10), 0 2px 8px 0 rgba(0,0,0,0.04);
			transform: translateY(-2px) scale(1.01);
		}
		.customer-avatar {
			width: 2.5rem;
			height: 2.5rem;
			border-radius: 9999px;
			background: linear-gradient(135deg, #00CC61 60%, #00b357 100%);
			color: #fff;
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: 600;
			font-size: 1rem;
			margin-right: 0.75rem;
			box-shadow: 0 2px 8px 0 rgba(0,204,97,0.10);
		}
		.customer-badge {
			display: inline-block;
			padding: 0.25rem 0.75rem;
			border-radius: 9999px;
			font-size: 0.75rem;
			font-weight: 500;
			background: #e0f7ef;
			color: #00b357;
			margin-left: 0.5rem;
			letter-spacing: 0.01em;
		}
		.woodash-table-header {
			background: #f8fafc;
			font-size: 1rem;
			letter-spacing: 0.01em;
			color: #374151;
		}
		.woodash-table-row {
			transition: background 0.2s, box-shadow 0.2s;
		}
		.woodash-table-row:hover {
			background: #e6f9f0;
			box-shadow: 0 2px 8px 0 rgba(0,204,97,0.07);
		}
		.woodash-search-bar {
			display: flex;
			align-items: center;
			gap: 0.5rem;
			background: #fff;
			border: 1.5px solid #e5e7eb;
			border-radius: 0.75rem;
			padding: 0.5rem 1rem;
			margin-bottom: 1.5rem;
			box-shadow: 0 1.5px 4px 0 rgba(0,0,0,0.04);
			max-width: 350px;
		}
		.woodash-search-bar input {
			border: none;
			outline: none;
			background: transparent;
			width: 100%;
			font-size: 1rem;
			color: #374151;
		}
		/* ===== MODERN BUTTON SYSTEM ===== */

		/* Base Button Styles */
		.woodash-btn {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 0.5rem;
			padding: 0.75rem 1.5rem;
			border-radius: 0.75rem;
			font-size: 0.875rem;
			font-weight: 600;
			line-height: 1.25rem;
			text-decoration: none;
			border: 2px solid transparent;
			cursor: pointer;
			transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
			position: relative;
			overflow: hidden;
			min-height: 2.75rem;
			white-space: nowrap;
			user-select: none;
			box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
		}

		.woodash-btn:focus {
			outline: none;
			box-shadow: 0 0 0 3px rgba(0, 204, 97, 0.1);
		}

		.woodash-btn:disabled {
			opacity: 0.6;
			cursor: not-allowed;
			pointer-events: none;
		}

		/* Primary Buttons */
		.woodash-btn-primary {
			background: linear-gradient(135deg, #00CC61 0%, #00b357 100%);
			color: #ffffff;
			border-color: #00CC61;
			box-shadow: 0 4px 14px 0 rgba(0, 204, 97, 0.25);
		}

		.woodash-btn-primary:hover {
			background: linear-gradient(135deg, #00b357 0%, #00994d 100%);
			transform: translateY(-1px);
			box-shadow: 0 6px 20px 0 rgba(0, 204, 97, 0.35);
		}

		.woodash-btn-primary:active {
			transform: translateY(0);
			box-shadow: 0 2px 8px 0 rgba(59, 130, 246, 0.2);
		}

		/* Secondary Buttons */
		.woodash-btn-secondary {
			background: linear-gradient(135deg, #e0f7ef 0%, #d1fae5 100%);
			color: #065f46;
			border-color: #a7f3d0;
			box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
		}

		.woodash-btn-secondary:hover {
			background: linear-gradient(135deg, #a7f3d0 0%, #86efac 100%);
			border-color: #00CC61;
			color: #064e3b;
			transform: translateY(-1px);
			box-shadow: 0 4px 12px 0 rgba(0, 204, 97, 0.15);
		}

		.woodash-btn-secondary:active {
			transform: translateY(0);
			box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
		}

		/* Success Buttons */
		.woodash-btn-success {
			background: linear-gradient(135deg, #10b981 0%, #059669 100%);
			color: #ffffff;
			border-color: #10b981;
			box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.25);
		}

		.woodash-btn-success:hover {
			background: linear-gradient(135deg, #059669 0%, #047857 100%);
			transform: translateY(-1px);
			box-shadow: 0 6px 20px 0 rgba(16, 185, 129, 0.35);
		}

		/* Danger Buttons */
		.woodash-btn-danger {
			background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
			color: #ffffff;
			border-color: #ef4444;
			box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.25);
		}

		.woodash-btn-danger:hover {
			background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
			transform: translateY(-1px);
			box-shadow: 0 6px 20px 0 rgba(239, 68, 68, 0.35);
		}

		/* Warning Buttons */
		.woodash-btn-warning {
			background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
			color: #ffffff;
			border-color: #f59e0b;
			box-shadow: 0 4px 14px 0 rgba(245, 158, 11, 0.25);
		}

		.woodash-btn-warning:hover {
			background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
			transform: translateY(-1px);
			box-shadow: 0 6px 20px 0 rgba(245, 158, 11, 0.35);
		}

		/* Info Buttons */
		.woodash-btn-info {
			background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
			color: #ffffff;
			border-color: #3b82f6;
			box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.25);
		}

		.woodash-btn-info:hover {
			background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
			transform: translateY(-1px);
			box-shadow: 0 6px 20px 0 rgba(59, 130, 246, 0.35);
		}

		/* Ghost Buttons */
		.woodash-btn-ghost {
			background: transparent;
			color: #6b7280;
			border-color: #e5e7eb;
			box-shadow: none;
		}

		.woodash-btn-ghost:hover {
			background: #f8fafc;
			color: #374151;
			border-color: #d1d5db;
		}

		/* Outline Buttons */
		.woodash-btn-outline {
			background: transparent;
			color: #00CC61;
			border-color: #00CC61;
			box-shadow: none;
		}

		.woodash-btn-outline:hover {
			background: #00CC61;
			color: #ffffff;
		}

		/* Button Sizes */
		.woodash-btn-sm {
			padding: 0.5rem 1rem;
			font-size: 0.75rem;
			min-height: 2rem;
			border-radius: 0.5rem;
		}

		.woodash-btn-lg {
			padding: 1rem 2rem;
			font-size: 1rem;
			min-height: 3.25rem;
			border-radius: 1rem;
		}

		.woodash-btn-xl {
			padding: 1.25rem 2.5rem;
			font-size: 1.125rem;
			min-height: 3.75rem;
			border-radius: 1.25rem;
		}

		/* Icon-only Buttons */
		.woodash-btn-icon {
			padding: 0.75rem;
			width: 2.75rem;
			height: 2.75rem;
		}

		.woodash-btn-icon-sm {
			padding: 0.5rem;
			width: 2rem;
			height: 2rem;
		}

		.woodash-btn-icon-lg {
			padding: 1rem;
			width: 3.25rem;
			height: 3.25rem;
		}

		/* Button Groups */
		.woodash-btn-group {
			display: inline-flex;
			border-radius: 0.75rem;
			overflow: hidden;
			box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
		}

		.woodash-btn-group .woodash-btn {
			border-radius: 0;
			border-right-width: 1px;
			border-color: rgba(255, 255, 255, 0.2);
			box-shadow: none;
		}

		.woodash-btn-group .woodash-btn:first-child {
			border-top-left-radius: 0.75rem;
			border-bottom-left-radius: 0.75rem;
		}

		.woodash-btn-group .woodash-btn:last-child {
			border-top-right-radius: 0.75rem;
			border-bottom-right-radius: 0.75rem;
			border-right-width: 0;
		}

		.woodash-btn-group .woodash-btn:only-child {
			border-radius: 0.75rem;
			border-right-width: 0;
		}

		/* Loading State */
		.woodash-btn-loading {
			position: relative;
			color: transparent !important;
		}

		.woodash-btn-loading::after {
			content: '';
			position: absolute;
			width: 1rem;
			height: 1rem;
			top: 50%;
			left: 50%;
			margin-left: -0.5rem;
			margin-top: -0.5rem;
			border: 2px solid transparent;
			border-top: 2px solid currentColor;
			border-radius: 50%;
			animation: spin 1s linear infinite;
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}

		/* Pulse Animation */
		.woodash-btn-pulse {
			animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
		}

		@keyframes pulse {
			0%, 100% {
				opacity: 1;
			}
			50% {
				opacity: 0.5;
			}
		}

		/* Ripple Effect */
		.woodash-btn-ripple {
			position: relative;
			overflow: hidden;
		}

		.woodash-btn-ripple::before {
			content: '';
			position: absolute;
			top: 50%;
			left: 50%;
			width: 0;
			height: 0;
			border-radius: 50%;
			background: rgba(255, 255, 255, 0.3);
			transform: translate(-50%, -50%);
			transition: width 0.6s, height 0.6s;
		}

		.woodash-btn-ripple:active::before {
			width: 300px;
			height: 300px;
		}

		/* Special Effects */
		.woodash-btn-glow {
			box-shadow: 0 0 20px rgba(0, 204, 97, 0.3);
		}

		.woodash-btn-glow:hover {
			box-shadow: 0 0 30px rgba(0, 204, 97, 0.5);
		}

		.woodash-btn-shine {
			position: relative;
		}

		.woodash-btn-shine::before {
			content: '';
			position: absolute;
			top: 0;
			left: -100%;
			width: 100%;
			height: 100%;
			background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
			transition: left 0.5s;
		}

		.woodash-btn-shine:hover::before {
			left: 100%;
		}

		/* Floating Action Button */
		.woodash-btn-fab {
			position: fixed;
			bottom: 2rem;
			right: 2rem;
			width: 3.5rem;
			height: 3.5rem;
			border-radius: 50%;
			padding: 0;
			box-shadow: 0 6px 20px 0 rgba(0, 204, 97, 0.3);
			z-index: 1000;
		}

		.woodash-btn-fab:hover {
			transform: scale(1.1);
			box-shadow: 0 8px 25px 0 rgba(0, 204, 97, 0.4);
		}

		/* Responsive Button Adjustments */
		@media (max-width: 640px) {
			.woodash-btn {
				padding: 0.625rem 1.25rem;
				font-size: 0.8125rem;
				min-height: 2.5rem;
			}

			.woodash-btn-sm {
				padding: 0.5rem 1rem;
				font-size: 0.75rem;
				min-height: 2rem;
			}

			.woodash-btn-lg {
				padding: 0.875rem 1.75rem;
				font-size: 0.9375rem;
				min-height: 3rem;
			}

			.woodash-btn-xl {
				padding: 1rem 2rem;
				font-size: 1rem;
				min-height: 3.25rem;
			}
		}

		/* Dark Mode Support */
		@media (prefers-color-scheme: dark) {
			.woodash-btn-secondary {
				background: #1f2937;
				color: #00CC61;
				border-color: #374151;
			}

			.woodash-btn-secondary:hover {
				background: #111827;
				border-color: #00CC61;
			}

			.woodash-btn-ghost {
				background: transparent;
				color: #9ca3af;
				border-color: #374151;
			}

			.woodash-btn-ghost:hover {
				background: #1f2937;
				color: #f3f4f6;
				border-color: #4b5563;
			}
		}

		/* ===== MODERN SEARCH INPUT STYLES ===== */

		/* Modern Search Input Styles */
		.woodash-search-input {
			width: 100%;
			min-width: 280px;
			padding: 0.75rem 3rem 0.75rem 2.5rem;
			border: 2px solid #e5e7eb;
			border-radius: 12px;
			font-size: 0.875rem;
			font-weight: 500;
			color: #374151;
			background: #ffffff;
			transition: all 0.2s ease-in-out;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
		}

		.woodash-search-input:focus {
			outline: none;
			border-color: #00CC61;
			box-shadow: 0 0 0 3px rgba(0, 204, 97, 0.1), 0 4px 12px rgba(0, 204, 97, 0.15);
			background: #fefefe;
		}

		.woodash-search-input:hover {
			border-color: #d1d5db;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		}

		.woodash-search-input::placeholder {
			color: #9ca3af;
			font-weight: 400;
		}

		/* Search input container hover effect */
		.woodash-search-input:focus + .fa-search,
		.woodash-search-input:hover + .fa-search {
			color: #00CC61;
		}

		/* Clear button styles */
		#clear-search {
			transition: all 0.2s ease-in-out;
		}

		#clear-search:hover {
			color: #dc2626 !important;
			transform: scale(1.1);
		}

		/* Responsive search input */
		@media (max-width: 640px) {
			.woodash-search-input {
				min-width: 240px;
				padding: 0.625rem 2.5rem 0.625rem 2rem;
				font-size: 0.8125rem;
			}
		}

		/* Dark mode support for search input */
		@media (prefers-color-scheme: dark) {
			.woodash-search-input {
				background: #1f2937;
				border-color: #374151;
				color: #f3f4f6;
			}

			.woodash-search-input:focus {
				background: #111827;
				border-color: #00CC61;
			}

			.woodash-search-input:hover {
				border-color: #4b5563;
			}

			.woodash-search-input::placeholder {
				color: #9ca3af;
			}
		}
		.woodash-gradient-text {
			background: linear-gradient(45deg, #00CC61, #00b357);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
		}
		.woodash-main h1 {
			font-size: 2.2rem;
			font-weight: 700;
			margin-bottom: 0.25rem;
		}
		.woodash-main p {
			color: #6b7280;
		}
		.woodash-card .text-3xl {
			font-size: 2.1rem;
			color: #00b357;
		}
		.woodash-card h3 {
			color: #6b7280;
		}
		@media (max-width: 640px) {
			.customer-avatar { width: 2rem; height: 2rem; font-size: 0.9rem; }
			.woodash-search-bar { max-width: 100%; }
			.woodash-main h1 { font-size: 1.3rem; }
		}
	</style>
</head>
<body class="bg-gray-100">
<button id="woodash-menu-toggle" class="woodash-menu-toggle fixed top-4 left-4 z-60 bg-white p-2 rounded-lg shadow-lg lg:hidden" aria-label="Open menu">
	<i class="fa-solid fa-bars text-xl text-[#00CC61]"></i>
</button>
<div id="woodash-sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-30 z-40 hidden lg:hidden"></div>
	<style>
		.woodash-menu-toggle {
			display: none;
		}
		@media (max-width: 1024px) {
			.woodash-menu-toggle {
				display: block;
			}
			.woodash-sidebar {
				transform: translateX(-100%);
				transition: transform 0.3s ease;
			}
			.woodash-sidebar.active {
				transform: translateX(0);
			}
			#woodash-sidebar-overlay {
				display: block;
			}
		}
		</style>
<div id="woodash-dashboard" class="min-h-screen w-full flex">
	<!-- Sidebar -->
    <aside class="woodash-sidebar w-64 bg-white/90 border-r border-gray-100 woodash-glass-effect flex flex-col justify-between">

	
		<div class="pt-10 pb-4 px-6">
			<div class="flex items-center gap-4 mb-10 woodash-fade-in">
				<div class="w-12 h-12 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center woodash-glow">
					<i class="fa-solid fa-users text-white text-2xl"></i>
				</div>
				<h2 class="text-2xl font-bold woodash-gradient-text ml-1">WooDash Pro</h2>
			</div>
			<!-- Main Navigation -->
			<nav class="flex flex-col gap-1 mt-2">
				<a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
					<i class="fa-solid fa-gauge w-5"></i>
					<span>Dashboard</span>
				</a>
				<a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-orders')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
					<i class="fa-solid fa-cart-shopping w-5"></i>
					<span>Orders</span>
				</a>
				<a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-products')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
					<i class="fa-solid fa-box w-5"></i>
					<span>Products</span>
				</a>
				<a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-customers')); ?>" class="woodash-nav-link active flex items-center gap-4 px-4 py-2 rounded-lg bg-[#e0f7ef] text-[#00b357] font-semibold">
					<i class="fa-solid fa-users w-5"></i>
					<span>Customers</span>
				</a>
				<a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-stock')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
					<i class="fa-solid fa-boxes-stacked w-5"></i>
					<span>Stock</span>
				</a>
				<a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-reviews')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
					<i class="fa-solid fa-star w-5"></i>
					<span>Reviews</span>
				</a>
				<a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-marketing')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
					<i class="fa-solid fa-bullhorn w-5"></i>
					<span>Marketing</span>
				</a>
				<a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-reports')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
					<i class="fa-solid fa-file-lines w-5"></i>
					<span>Reports</span>
				</a>
				<a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-settings')); ?>" class="woodash-nav-link flex items-center gap-4 px-4 py-2 rounded-lg hover:bg-[#f3f4f6] transition">
					<i class="fa-solid fa-gear w-5"></i>
					<span>Settings</span>
				</a>
			</nav>
		</div>
	</aside>

	<!-- Scroll to Top Button -->
	<button id="woodash-scroll-to-top" class="fixed bottom-6 right-6 woodash-btn woodash-btn-primary rounded-full w-12 h-12 flex items-center justify-center woodash-hover-card woodash-glow" style="display: none;">
		<i class="fa-solid fa-arrow-up"></i>
	</button>

	<!-- Main Content -->
	<main class="woodash-main flex-1 p-6 md:p-8">
		<div class="max-w-7xl mx-auto">
			<!-- Enhanced Header with Filters -->
			<header class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
				<div>
					<h1 class="text-2xl font-bold woodash-gradient-text">Customers</h1>
					<p class="text-gray-500">Manage and analyze your customer base</p>
				</div>
				<div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
					<!-- Customer Segmentation Filter -->
					<select id="customer-segment" class="woodash-btn">
						<option value="all">All Customers</option>
						<option value="vip">VIP Customers</option>
						<option value="new">New Customers</option>
						<option value="returning">Returning Customers</option>
						<option value="inactive">Inactive Customers</option>
					</select>

					<!-- Date Range Filter -->
					<select id="customer-date-filter" class="woodash-btn">
						<option value="all">All Time</option>
						<option value="last30days">Last 30 Days</option>
						<option value="last90days">Last 90 Days</option>
						<option value="thisyear">This Year</option>
					</select>

					<!-- Export Button -->
					<button class="woodash-btn woodash-btn-primary " onclick="exportCustomers()">
						<i class="fa fa-download mr-1"></i> Export
					</button>

					<!-- Add Customer Button -->
					<button class="woodash-btn woodash-btn-primary" onclick="showAddCustomerModal()">
						<i class="fa fa-plus mr-1"></i> Add Customer
					</button>
				</div>
			</header>


			<!-- Customer Analytics Overview -->
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
				<div class="woodash-card p-6">
					<div class="flex items-center justify-between">
						<div>
							<h3 class=" font-medium text-gray-500 mb-1">Total Customers</h3>
							<div class="text-3xl font-bold text-gray-900" id="total-customers">0</div>
							<div class="flex items-center gap-1 mt-2">
								<i class="fa-solid fa-arrow-up text-green-500 text-xs"></i>
								<span class="text-green-600 " id="customers-growth">+12.5%</span>
								<span class="text-xs text-gray-500">vs last month</span>
							</div>
						</div>
						<div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
							<i class="fa-solid fa-users text-lg"></i>
						</div>
					</div>
				</div>
				<div class="woodash-card p-6">
					<div class="flex items-center justify-between">
						<div>
							<h3 class=" font-medium text-gray-500 mb-1">New This Month</h3>
							<div class="text-3xl font-bold text-gray-900" id="new-customers-month">0</div>
							<div class="flex items-center gap-1 mt-2">
								<i class="fa-solid fa-user-plus text-green-500 text-xs"></i>
								<span class="text-green-600 ">Active</span>
							</div>
						</div>
						<div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
							<i class="fa-solid fa-user-plus text-lg"></i>
						</div>
					</div>
				</div>
				<div class="woodash-card p-6">
					<div class="flex items-center justify-between">
						<div>
							<h3 class=" font-medium text-gray-500 mb-1">Avg Customer Value</h3>
							<div class="text-3xl font-bold text-gray-900" id="avg-customer-value">$0</div>
							<div class="flex items-center gap-1 mt-2">
								<i class="fa-solid fa-chart-line text-blue-500 text-xs"></i>
								<span class="text-blue-600 ">LTV</span>
							</div>
						</div>
						<div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
							<i class="fa-solid fa-dollar-sign text-lg"></i>
						</div>
					</div>
				</div>
				<div class="woodash-card p-6">
					<div class="flex items-center justify-between">
						<div>
							<h3 class=" font-medium text-gray-500 mb-1">Retention Rate</h3>
							<div class="text-3xl font-bold text-gray-900" id="retention-rate">0%</div>
							<div class="flex items-center gap-1 mt-2">
								<i class="fa-solid fa-heart text-red-500 text-xs"></i>
								<span class="text-red-600 ">Loyal</span>
							</div>
						</div>
						<div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
							<i class="fa-solid fa-heart text-lg"></i>
						</div>
					</div>
				</div>
			</div>

			<!-- Customer Growth Chart -->
			<div class="woodash-card p-6 mb-8">
				<div class="flex justify-between items-center mb-6">
					<div>
						<h3 class="text-lg font-bold woodash-gradient-text">Customer Growth</h3>
						<p class="text-gray-500 ">Track customer acquisition over time</p>
					</div>
					<div class="flex gap-2">
						<button class="woodash-btn woodash-btn-primary " data-chart-range="7days">7D</button>
						<button class="woodash-btn woodash-btn-primary " data-chart-range="30days">30D</button>
						<button class="woodash-btn woodash-btn-primary " data-chart-range="90days">90D</button>
					</div>
				</div>
				<div class="h-64">
					<canvas id="customer-growth-chart"></canvas>
				</div>
			</div>

			<!-- Customers Table with Enhanced Features -->
			<div class="woodash-card p-6">
				<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
					<h3 class="text-lg font-bold woodash-gradient-text">Customer List</h3>

					<!-- Advanced Search and Filters -->
					<div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
						<!-- Search Input -->
						<div class="relative group">
							<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
								<svg class="w-5 h-5 text-gray-400 group-focus-within:text-[#00CC61] transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
								</svg>
							</div>
							<input type="text"
								   id="customer-search"
								   class="w-full pl-12 pr-12 py-4 text-gray-700 bg-white border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-[#00CC61]/20 focus:border-[#00CC61] focus:outline-none transition-all duration-300 placeholder-gray-400 text-sm font-medium shadow-lg hover:shadow-xl hover:border-gray-300 hover:bg-gray-50/30 backdrop-blur-sm"
								   placeholder="Search customers by name, email, or phone..."
								   autocomplete="off">
							<button type="button"
									id="clear-search"
									class="absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-gray-100 hover:bg-red-100 focus:bg-red-100 text-gray-500 hover:text-red-600 focus:text-red-600 transition-all duration-300 opacity-0 pointer-events-none flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-red-500/50 hover:scale-110 focus:scale-110 group">
								<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
								</svg>
							</button>
						</div>

						<!-- Bulk Actions -->
						<div class="flex gap-2">
							<button class="woodash-btn woodash-btn-primary " id="select-all-btn" onclick="toggleSelectAll()">
								<i class="fa fa-check-square mr-1"></i> Select All
							</button>
							<div class="relative">
								<button class="woodash-btn woodash-btn-primary " id="bulk-actions-btn" onclick="toggleBulkActions()">
									<i class="fa fa-ellipsis-v mr-1"></i> Actions
								</button>
								<div id="bulk-actions-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 hidden">
									<button class="w-full text-left px-4 py-2  text-gray-700 hover:bg-gray-50" onclick="bulkEmailCustomers()">
										<i class="fa fa-envelope mr-2"></i> Send Email
									</button>
									<button class="w-full text-left px-4 py-2  text-gray-700 hover:bg-gray-50" onclick="bulkExportCustomers()">
										<i class="fa fa-download mr-2"></i> Export Selected
									</button>
									<button class="w-full text-left px-4 py-2  text-red-600 hover:bg-red-50" onclick="bulkDeleteCustomers()">
										<i class="fa fa-trash mr-2"></i> Delete Selected
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Table Controls -->
				<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3 bg-green-50 p-4 rounded-lg border border-green-200">
					<div class="flex items-center gap-3">
						<span class=" text-gray-700 font-medium">Show</span>
						<select id="per-page-select" class="woodash-btn bg-green-50">
							<option value="10">10</option>
							<option value="25" selected>25</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
						<span class=" text-gray-700 font-medium">entries</span>
					</div>
					<div class=" text-gray-700 font-medium" id="table-info">Showing 0 to 0 of 0 entries</div>
				</div>

				<div class="overflow-x-auto">
					<table class="w-full">
						<thead class="woodash-table-header">
							<tr class="border-b border-gray-200">
								<th class="text-left py-3 px-4 font-medium text-gray-600 w-12">
									<input type="checkbox" id="master-checkbox" onchange="toggleAllCheckboxes()">
								</th>
								<th class="text-left py-3 px-4 font-medium text-gray-600">Customer</th>
								<th class="text-left py-3 px-4 font-medium text-gray-600">Contact</th>
								<th class="text-left py-3 px-4 font-medium text-gray-600">Orders</th>
								<th class="text-left py-3 px-4 font-medium text-gray-600">Total Spent</th>
								<th class="text-left py-3 px-4 font-medium text-gray-600">Last Order</th>
								<th class="text-left py-3 px-4 font-medium text-gray-600">Status</th>
								<th class="text-left py-3 px-4 font-medium text-gray-600">Actions</th>
							</tr>
						</thead>
						<tbody id="customers-table">
							<!-- Customer data will be loaded here -->
						</tbody>
					</table>
				</div>

				<!-- Pagination -->
				<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-6 gap-3">
					<div class=" text-gray-500" id="pagination-info">No customers found</div>
					<div class="flex gap-2" id="pagination-controls">
						<!-- Pagination buttons will be generated here -->
					</div>
				</div>
			</div>
		</div>

		</footer>
	</main>
</div>

<!-- Customer Details Modal -->
<div id="customer-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
	<div class="flex items-center justify-center min-h-screen p-4">
		<div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
			<div class="p-6 border-b border-gray-200">
				<div class="flex justify-between items-center">
					<h2 class="text-2xl font-bold woodash-gradient-text">Customer Details</h2>
					<button onclick="closeCustomerModal()" class="text-gray-400 hover:text-gray-600">
						<i class="fa fa-times text-xl"></i>
					</button>
				</div>
			</div>
			<div class="p-6" id="customer-modal-content">
				<!-- Customer details will be loaded here -->
			</div>
		</div>
	</div>
</div>

<!-- Add Customer Modal -->
<div id="add-customer-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
	<div class="flex items-center justify-center min-h-screen p-4">
		<div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
			<div class="p-6 border-b border-gray-200">
				<div class="flex justify-between items-center">
					<h2 class="text-xl font-bold woodash-gradient-text">Add New Customer</h2>
					<button onclick="closeAddCustomerModal()" class="text-gray-400 hover:text-gray-600">
						<i class="fa fa-times text-xl"></i>
					</button>
				</div>
			</div>
			<form id="add-customer-form" class="p-6">
				<div class="space-y-4">
					<div>
						<label class="block  font-medium text-gray-700 mb-1">First Name</label>
						<input type="text" name="first_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" required>
					</div>
					<div>
						<label class="block  font-medium text-gray-700 mb-1">Last Name</label>
						<input type="text" name="last_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" required>
					</div>
					<div>
						<label class="block  font-medium text-gray-700 mb-1">Email</label>
						<input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent" required>
					</div>
					<div>
						<label class="block  font-medium text-gray-700 mb-1">Phone</label>
						<input type="tel" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00CC61] focus:border-transparent">
					</div>
				</div>
				<div class="flex gap-3 mt-6">
					<button type="button" onclick="closeAddCustomerModal()" class="flex-1 woodash-btn woodash-btn-secondary">Cancel</button>
					<button type="submit" class="flex-1 woodash-btn woodash-btn-primary">Add Customer</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-30 hidden z-40 flex items-center justify-center">
	<div class="bg-white rounded-lg p-6 flex items-center gap-3">
		<div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#00CC61]"></div>
		<span>Loading...</span>
	</div>
</div>


<script>
// WordPress AJAX Configuration
window.woodash_ajax = {
	ajax_url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
	nonce: '<?php echo wp_create_nonce('woodash_nonce'); ?>'
};

document.addEventListener('DOMContentLoaded', function() {
	// Initialize the customers manager
	CustomersManager.init();

	// Mobile sidebar toggle
	const menuToggle = document.getElementById('woodash-menu-toggle');
	const sidebar = document.querySelector('.woodash-sidebar');
	const sidebarOverlay = document.getElementById('woodash-sidebar-overlay');
	if (menuToggle && sidebar) {
		menuToggle.addEventListener('click', function(e) {
			sidebar.classList.add('active');
			if (sidebarOverlay) sidebarOverlay.classList.remove('hidden');
		});
	}
	if (sidebarOverlay && sidebar) {
		sidebarOverlay.addEventListener('click', function() {
			sidebar.classList.remove('active');
			sidebarOverlay.classList.add('hidden');
		});
	}

	// Scroll to Top Button
	const scrollTopButton = document.getElementById('woodash-scroll-to-top');
	if (scrollTopButton) {
		window.addEventListener('scroll', () => {
			scrollTopButton.style.display = window.scrollY > 300 ? 'flex' : 'none';
		});
		scrollTopButton.addEventListener('click', () => {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
	}
});

const CustomersManager = {
	state: {
		customers: [],
		filteredCustomers: [],
		selectedCustomers: new Set(),
		currentPage: 1,
		perPage: 25,
		totalCustomers: 0,
		searchTerm: '',
		segment: 'all',
		dateFilter: 'all',
		customerGrowthChart: null
	},

	init() {
		this.bindEvents();
		this.loadCustomerStats();
		this.loadCustomers();
		this.initializeCharts();
	},

	bindEvents() {
		// Search functionality
		const searchInput = document.getElementById('customer-search');
		if (searchInput) {
			searchInput.addEventListener('input', (e) => {
				this.state.searchTerm = e.target.value.trim();
				this.filterCustomers();
				this.updateClearButtonVisibility();
			});
		}

		// Clear search button
		const clearButton = document.getElementById('clear-search');
		if (clearButton) {
			clearButton.addEventListener('click', () => {
				this.clearSearch();
			});
		}

		// Segment filter
		const segmentSelect = document.getElementById('customer-segment');
		if (segmentSelect) {
			segmentSelect.addEventListener('change', (e) => {
				this.state.segment = e.target.value;
				this.loadCustomers();
			});
		}

		// Date filter
		const dateFilter = document.getElementById('customer-date-filter');
		if (dateFilter) {
			dateFilter.addEventListener('change', (e) => {
				this.state.dateFilter = e.target.value;
				this.loadCustomers();
			});
		}

		// Per page selector
		const perPageSelect = document.getElementById('per-page-select');
		if (perPageSelect) {
			perPageSelect.addEventListener('change', (e) => {
				this.state.perPage = parseInt(e.target.value);
				this.state.currentPage = 1;
				this.renderCustomersTable();
			});
		}

		// Add customer form
		const addForm = document.getElementById('add-customer-form');
		if (addForm) {
			addForm.addEventListener('submit', (e) => {
				e.preventDefault();
				this.addCustomer(new FormData(e.target));
			});
		}

		// Chart range buttons
		document.querySelectorAll('[data-chart-range]').forEach(btn => {
			btn.addEventListener('click', (e) => {
				document.querySelectorAll('[data-chart-range]').forEach(b => b.classList.remove('woodash-btn-primary', 'woodash-btn-secondary'));
				document.querySelectorAll('[data-chart-range]').forEach(b => b.classList.add('woodash-btn-secondary'));
				e.target.classList.remove('woodash-btn-secondary');
				e.target.classList.add('woodash-btn-primary');
				this.loadCustomerGrowthChart(e.target.dataset.chartRange);
			});
		});
	},

	async loadCustomerStats() {
		try {
			const formData = new FormData();
			formData.append('action', 'woodash_get_customer_analytics');
			formData.append('nonce', woodash_ajax.nonce);

			const response = await fetch(woodash_ajax.ajax_url, {
				method: 'POST',
				body: formData
			});

			const result = await response.json();
			if (result.success) {
				this.updateStats(result.data);
			}
		} catch (error) {
			console.error('Error loading customer stats:', error);
		}
	},

	updateStats(data) {
		document.getElementById('total-customers').textContent = this.formatNumber(data.total_customers || 0);
		document.getElementById('new-customers-month').textContent = this.formatNumber(data.new_customers_month || 0);
		document.getElementById('avg-customer-value').textContent = this.formatCurrency(data.avg_customer_value || 0);
		document.getElementById('retention-rate').textContent = `${(data.retention_rate || 0).toFixed(1)}%`;
		document.getElementById('customers-growth').textContent = `+${(data.growth_rate || 0).toFixed(1)}%`;
	},

	async loadCustomers() {
		this.showLoading();

		try {
			const formData = new FormData();
			formData.append('action', 'woodash_get_customers_enhanced');
			formData.append('nonce', woodash_ajax.nonce);
			formData.append('segment', this.state.segment);
			formData.append('date_filter', this.state.dateFilter);
			formData.append('per_page', this.state.perPage);
			formData.append('page', this.state.currentPage);

			const response = await fetch(woodash_ajax.ajax_url, {
				method: 'POST',
				body: formData
			});

			const result = await response.json();
			if (result.success) {
				this.state.customers = result.data.customers;
				this.state.totalCustomers = result.data.total;
				this.state.filteredCustomers = this.state.customers;
				this.renderCustomersTable();
				this.updatePagination();
			} else {
				this.loadDemoCustomers();
			}
		} catch (error) {
			console.error('Error loading customers:', error);
			this.loadDemoCustomers();
		} finally {
			this.hideLoading();
		}
	},

	filterCustomers() {
		if (!this.state.searchTerm) {
			this.state.filteredCustomers = this.state.customers;
		} else {
			const term = this.state.searchTerm.toLowerCase();
			this.state.filteredCustomers = this.state.customers.filter(customer =>
				customer.name.toLowerCase().includes(term) ||
				customer.email.toLowerCase().includes(term) ||
				(customer.phone && customer.phone.includes(term))
			);
		}
		this.renderCustomersTable();
		this.updatePagination();
		this.updateClearButtonVisibility();
	},

	clearSearch() {
		this.state.searchTerm = '';
		const searchInput = document.getElementById('customer-search');
		if (searchInput) {
			searchInput.value = '';
		}
		this.filterCustomers();
	},

	updateClearButtonVisibility() {
		const clearButton = document.getElementById('clear-search');
		if (clearButton) {
			if (this.state.searchTerm.length > 0) {
				clearButton.classList.remove('opacity-0', 'pointer-events-none');
				clearButton.classList.add('opacity-100', 'pointer-events-auto');
			} else {
				clearButton.classList.remove('opacity-100', 'pointer-events-auto');
				clearButton.classList.add('opacity-0', 'pointer-events-none');
			}
		}
	},

	renderCustomersTable() {
		const tbody = document.getElementById('customers-table');
		if (!tbody) return;

		const start = (this.state.currentPage - 1) * this.state.perPage;
		const end = start + this.state.perPage;
		const customersToShow = this.state.filteredCustomers.slice(start, end);

		tbody.innerHTML = '';

		if (customersToShow.length === 0) {
			tbody.innerHTML = `
				<tr>
					<td colspan="8" class="text-center py-8 text-gray-500">
						<i class="fa fa-users text-3xl mb-2 block"></i>
						No customers found
					</td>
				</tr>
			`;
			return;
		}

		customersToShow.forEach(customer => {
			const row = document.createElement('tr');
			row.className = 'woodash-table-row border-b border-gray-200 hover:bg-gray-50';

			const initials = (customer.name || '').split(' ').map(n => n[0]).join('').substring(0,2).toUpperCase();
			const statusBadge = this.getStatusBadge(customer);
			const lastOrderDate = customer.last_order_date ? new Date(customer.last_order_date).toLocaleDateString() : 'Never';

			row.innerHTML = `
				<td class="px-4 py-3">
					<input type="checkbox" class="customer-checkbox" value="${customer.id}" onchange="CustomersManager.toggleCustomerSelection(${customer.id})">
				</td>
				<td class="px-4 py-3">
					<div class="flex items-center gap-3">
						<div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white  font-semibold">
							${initials}
						</div>
						<div>
							<div class="font-medium text-gray-900">${this.escapeHtml(customer.name)}</div>
							<div class=" text-gray-500">ID: ${customer.id}</div>
						</div>
					</div>
				</td>
				<td class="px-4 py-3">
					<div>
						<div class=" font-medium text-gray-900">${this.escapeHtml(customer.email)}</div>
						<div class=" text-gray-500">${customer.phone || 'No phone'}</div>
					</div>
				</td>
				<td class="px-4 py-3  text-gray-900">${customer.total_orders || 0}</td>
				<td class="px-4 py-3  font-medium text-gray-900">${this.formatCurrency(customer.total_spent || 0)}</td>
				<td class="px-4 py-3  text-gray-900">${lastOrderDate}</td>
				<td class="px-4 py-3">${statusBadge}</td>
				<td class="px-4 py-3">
					<div class="flex gap-2">
						<button onclick="CustomersManager.viewCustomerDetails(${customer.id})" class="text-blue-600 hover:text-blue-800" title="View Details">
							<i class="fa fa-eye"></i>
						</button>
						<button onclick="CustomersManager.editCustomer(${customer.id})" class="text-green-600 hover:text-green-800" title="Edit">
							<i class="fa fa-edit"></i>
						</button>
						<button onclick="CustomersManager.deleteCustomer(${customer.id})" class="text-red-600 hover:text-red-800" title="Delete">
							<i class="fa fa-trash"></i>
						</button>
					</div>
				</td>
			`;
			tbody.appendChild(row);
		});

		this.updateTableInfo();
	},

	getStatusBadge(customer) {
		const orders = customer.total_orders || 0;
		const lastOrder = customer.last_order_date ? new Date(customer.last_order_date) : null;
		const daysSinceLastOrder = lastOrder ? Math.floor((new Date() - lastOrder) / (1000 * 60 * 60 * 24)) : Infinity;

		if (orders === 0) return '<span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">New</span>';
		if (orders >= 10) return '<span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">VIP</span>';
		if (daysSinceLastOrder <= 30) return '<span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Active</span>';
		if (daysSinceLastOrder <= 90) return '<span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Regular</span>';
		return '<span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Inactive</span>';
	},

	updateTableInfo() {
		const start = (this.state.currentPage - 1) * this.state.perPage + 1;
		const end = Math.min(start + this.state.perPage - 1, this.state.filteredCustomers.length);
		const total = this.state.filteredCustomers.length;

		document.getElementById('table-info').textContent = `Showing ${start} to ${end} of ${total} entries`;
	},

	updatePagination() {
		const totalPages = Math.ceil(this.state.filteredCustomers.length / this.state.perPage);
		const controls = document.getElementById('pagination-controls');
		const info = document.getElementById('pagination-info');

		info.textContent = `Page ${this.state.currentPage} of ${totalPages}`;

		controls.innerHTML = '';

		if (totalPages <= 1) return;

		// Previous button
		const prevBtn = document.createElement('button');
		prevBtn.className = 'woodash-btn woodash-btn-secondary ';
		prevBtn.textContent = 'Previous';
		prevBtn.disabled = this.state.currentPage === 1;
		prevBtn.onclick = () => this.changePage(this.state.currentPage - 1);
		controls.appendChild(prevBtn);

		// Page numbers
		const startPage = Math.max(1, this.state.currentPage - 2);
		const endPage = Math.min(totalPages, this.state.currentPage + 2);

		for (let i = startPage; i <= endPage; i++) {
			const pageBtn = document.createElement('button');
			pageBtn.className = `woodash-btn  ${i === this.state.currentPage ? 'woodash-btn-primary' : 'woodash-btn-secondary'}`;
			pageBtn.textContent = i;
			pageBtn.onclick = () => this.changePage(i);
			controls.appendChild(pageBtn);
		}

		// Next button
		const nextBtn = document.createElement('button');
		nextBtn.className = 'woodash-btn woodash-btn-secondary ';
		nextBtn.textContent = 'Next';
		nextBtn.disabled = this.state.currentPage === totalPages;
		nextBtn.onclick = () => this.changePage(this.state.currentPage + 1);
		controls.appendChild(nextBtn);
	},

	changePage(page) {
		this.state.currentPage = page;
		this.renderCustomersTable();
		this.updatePagination();
	},

	toggleCustomerSelection(customerId) {
		if (this.state.selectedCustomers.has(customerId)) {
			this.state.selectedCustomers.delete(customerId);
		} else {
			this.state.selectedCustomers.add(customerId);
		}
		this.updateBulkActionsVisibility();
	},

	toggleSelectAll() {
		const checkboxes = document.querySelectorAll('.customer-checkbox');
		const allSelected = Array.from(checkboxes).every(cb => cb.checked);

		checkboxes.forEach(cb => {
			cb.checked = !allSelected;
			this.toggleCustomerSelection(parseInt(cb.value));
		});
	},

	toggleAllCheckboxes() {
		const masterCheckbox = document.getElementById('master-checkbox');
		const checkboxes = document.querySelectorAll('.customer-checkbox');

		checkboxes.forEach(cb => {
			cb.checked = masterCheckbox.checked;
			this.toggleCustomerSelection(parseInt(cb.value));
		});
	},

	updateBulkActionsVisibility() {
		const bulkBtn = document.getElementById('bulk-actions-btn');
		if (bulkBtn) {
			bulkBtn.style.display = this.state.selectedCustomers.size > 0 ? 'block' : 'none';
		}
	},

	toggleBulkActions() {
		const menu = document.getElementById('bulk-actions-menu');
		if (menu) {
			menu.classList.toggle('hidden');
		}
	},

	async viewCustomerDetails(customerId) {
		this.showLoading();

		try {
			const formData = new FormData();
			formData.append('action', 'woodash_get_customer_details');
			formData.append('nonce', woodash_ajax.nonce);
			formData.append('customer_id', customerId);

			const response = await fetch(woodash_ajax.ajax_url, {
				method: 'POST',
				body: formData
			});

			const result = await response.json();
			if (result.success) {
				this.showCustomerModal(result.data);
			}
		} catch (error) {
			console.error('Error loading customer details:', error);
			alert('Error loading customer details');
		} finally {
			this.hideLoading();
		}
	},

	showCustomerModal(customer) {
		const modal = document.getElementById('customer-modal');
		const content = document.getElementById('customer-modal-content');

		const initials = (customer.name || '').split(' ').map(n => n[0]).join('').substring(0,2).toUpperCase();

		content.innerHTML = `
			<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
				<!-- Customer Info -->
				<div class="lg:col-span-1">
					<div class="text-center mb-6">
						<div class="w-20 h-20 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">
							${initials}
						</div>
						<h3 class="text-xl font-bold text-gray-900">${this.escapeHtml(customer.name)}</h3>
						<p class="text-gray-500">${this.escapeHtml(customer.email)}</p>
						<p class=" text-gray-400">Customer since ${new Date(customer.registered_date).toLocaleDateString()}</p>
					</div>

					<!-- Quick Stats -->
					<div class="space-y-4">
						<div class="bg-gray-50 p-4 rounded-lg">
							<div class=" text-gray-500">Total Orders</div>
							<div class="text-2xl font-bold text-gray-900">${customer.stats.total_orders}</div>
						</div>
						<div class="bg-gray-50 p-4 rounded-lg">
							<div class=" text-gray-500">Total Spent</div>
							<div class="text-2xl font-bold text-gray-900">${this.formatCurrency(customer.stats.total_spent)}</div>
						</div>
						<div class="bg-gray-50 p-4 rounded-lg">
							<div class=" text-gray-500">Average Order</div>
							<div class="text-2xl font-bold text-gray-900">${this.formatCurrency(customer.stats.avg_order_value)}</div>
						</div>
					</div>
				</div>

				<!-- Customer Details -->
				<div class="lg:col-span-2">
					<div class="space-y-6">
						<!-- Contact Information -->
						<div>
							<h4 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h4>
							<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
								<div>
									<label class="block  font-medium text-gray-700">Email</label>
									<p class=" text-gray-900">${this.escapeHtml(customer.email)}</p>
								</div>
								<div>
									<label class="block  font-medium text-gray-700">Phone</label>
									<p class=" text-gray-900">${customer.phone || 'Not provided'}</p>
								</div>
							</div>
						</div>

						<!-- Billing Address -->
						<div>
							<h4 class="text-lg font-semibold text-gray-900 mb-4">Billing Address</h4>
							<div class="bg-gray-50 p-4 rounded-lg">
								${this.formatAddress(customer.billing_address)}
							</div>
						</div>

						<!-- Recent Orders -->
						<div>
							<h4 class="text-lg font-semibold text-gray-900 mb-4">Recent Orders</h4>
							<div class="space-y-2 max-h-60 overflow-y-auto">
								${customer.orders.slice(0, 5).map(order => `
									<div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
										<div>
											<div class="font-medium text-gray-900">${order.id}</div>
											<div class=" text-gray-500">${new Date(order.date).toLocaleDateString()}</div>
										</div>
										<div class="text-right">
											<div class="font-medium text-gray-900">${this.formatCurrency(order.total)}</div>
											<div class=" text-gray-500">${order.status}</div>
										</div>
									</div>
								`).join('')}
							</div>
						</div>
					</div>
				</div>
			</div>
		`;

		modal.classList.remove('hidden');
	},

	formatAddress(address) {
		if (!address || !address.address_1) {
			return '<p class="text-gray-500">No address provided</p>';
		}

		return `
			<p class=" text-gray-900">
				${address.address_1}<br>
				${address.address_2 ? address.address_2 + '<br>' : ''}
				${address.city}, ${address.state} ${address.postcode}<br>
				${address.country}
			</p>
		`;
	},

	closeCustomerModal() {
		document.getElementById('customer-modal').classList.add('hidden');
	},

	showAddCustomerModal() {
		document.getElementById('add-customer-modal').classList.remove('hidden');
	},

	closeAddCustomerModal() {
		document.getElementById('add-customer-modal').classList.add('hidden');
		document.getElementById('add-customer-form').reset();
	},

	async addCustomer(formData) {
		this.showLoading();

		try {
			const data = new FormData();
			data.append('action', 'woodash_create_customer');
			data.append('nonce', woodash_ajax.nonce);

			for (let [key, value] of formData.entries()) {
				data.append(key, value);
			}

			const response = await fetch(woodash_ajax.ajax_url, {
				method: 'POST',
				body: data
			});

			const result = await response.json();
			if (result.success) {
				this.closeAddCustomerModal();
				this.loadCustomers();
				this.showNotification('Customer added successfully', 'success');
			} else {
				alert(result.data || 'Error adding customer');
			}
		} catch (error) {
			console.error('Error adding customer:', error);
			alert('Error adding customer');
		} finally {
			this.hideLoading();
		}
	},

	async deleteCustomer(customerId) {
		if (!confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
			return;
		}

		this.showLoading();

		try {
			const formData = new FormData();
			formData.append('action', 'woodash_delete_customer');
			formData.append('nonce', woodash_ajax.nonce);
			formData.append('customer_id', customerId);

			const response = await fetch(woodash_ajax.ajax_url, {
				method: 'POST',
				body: formData
			});

			const result = await response.json();
			if (result.success) {
				this.loadCustomers();
				this.showNotification('Customer deleted successfully', 'success');
			} else {
				alert(result.data || 'Error deleting customer');
			}
		} catch (error) {
			console.error('Error deleting customer:', error);
			alert('Error deleting customer');
		} finally {
			this.hideLoading();
		}
	},

	editCustomer(customerId) {
		// For now, just view details. Could be enhanced to edit inline
		this.viewCustomerDetails(customerId);
	},

	initializeCharts() {
		this.loadCustomerGrowthChart('30days');
	},

	async loadCustomerGrowthChart(range) {
		try {
			const formData = new FormData();
			formData.append('action', 'woodash_get_customer_growth_data');
			formData.append('nonce', woodash_ajax.nonce);
			formData.append('range', range);

			const response = await fetch(woodash_ajax.ajax_url, {
				method: 'POST',
				body: formData
			});

			const result = await response.json();
			if (result.success) {
				this.renderCustomerGrowthChart(result.data);
			}
		} catch (error) {
			console.error('Error loading customer growth data:', error);
		}
	},

	renderCustomerGrowthChart(data) {
		const ctx = document.getElementById('customer-growth-chart');
		if (!ctx) return;

		if (this.state.customerGrowthChart) {
			this.state.customerGrowthChart.destroy();
		}

		this.state.customerGrowthChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: data.labels,
				datasets: [{
					label: 'New Customers',
					data: data.newCustomers,
					borderColor: '#00CC61',
					backgroundColor: 'rgba(0, 204, 97, 0.1)',
					borderWidth: 3,
					tension: 0.4,
					fill: true
				}, {
					label: 'Total Customers',
					data: data.totalCustomers,
					borderColor: '#3B82F6',
					backgroundColor: 'rgba(59, 130, 246, 0.1)',
					borderWidth: 3,
					tension: 0.4,
					fill: true
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
					legend: { display: true },
					tooltip: {
						backgroundColor: 'rgba(0, 0, 0, 0.9)',
						titleColor: '#ffffff',
						bodyColor: '#ffffff',
						cornerRadius: 8,
						padding: 12
					}
				},
				scales: {
					y: {
						beginAtZero: true,
						ticks: { callback: (value) => this.formatNumber(value) }
					}
				}
			}
		});
	},

	loadDemoCustomers() {
		this.state.customers = [
			{
				id: 1,
				name: 'Jane Smith',
				email: 'jane@example.com',
				phone: '+1-555-0123',
				total_orders: 12,
				total_spent: 1500.75,
				last_order_date: '2024-01-15',
				registered_date: '2023-01-15'
			},
			{
				id: 2,
				name: 'Bob Johnson',
				email: 'bob@example.com',
				phone: '+1-555-0124',
				total_orders: 8,
				total_spent: 950.00,
				last_order_date: '2024-01-10',
				registered_date: '2022-11-03'
			},
			{
				id: 3,
				name: 'Alice Lee',
				email: 'alice@example.com',
				phone: '+1-555-0125',
				total_orders: 5,
				total_spent: 600.50,
				last_order_date: '2024-01-08',
				registered_date: '2023-03-22'
			}
		];
		this.state.totalCustomers = this.state.customers.length;
		this.state.filteredCustomers = this.state.customers;
		this.renderCustomersTable();
		this.updatePagination();
	},

	showLoading() {
		document.getElementById('loading-overlay').classList.remove('hidden');
	},

	hideLoading() {
		document.getElementById('loading-overlay').classList.add('hidden');
	},

	showNotification(message, type = 'info') {
		// Simple notification - could be enhanced
		alert(message);
	},

	formatCurrency(amount) {
		return new Intl.NumberFormat('en-US', {
			style: 'currency',
			currency: 'USD'
		}).format(amount);
	},

	formatNumber(number) {
		return new Intl.NumberFormat('en-US').format(number);
	},

	exportCustomers() {
		// Simple CSV export
		if (this.state.customers.length === 0) {
			alert('No customers to export');
			return;
		}

		const csvContent = this.generateCSV(this.state.customers);
		this.downloadCSV(csvContent, 'customers.csv');
	},

	bulkEmailCustomers() {
		const selected = Array.from(this.state.selectedCustomers);
		if (selected.length === 0) {
			alert('Please select customers to email');
			return;
		}
		alert(`Email functionality for ${selected.length} customers - Feature not implemented yet`);
	},

	bulkExportCustomers() {
		const selected = Array.from(this.state.selectedCustomers);
		if (selected.length === 0) {
			alert('Please select customers to export');
			return;
		}

		const selectedCustomers = this.state.customers.filter(c => selected.includes(c.id));
		const csvContent = this.generateCSV(selectedCustomers);
		this.downloadCSV(csvContent, 'selected_customers.csv');
	},

	bulkDeleteCustomers() {
		const selected = Array.from(this.state.selectedCustomers);
		if (selected.length === 0) {
			alert('Please select customers to delete');
			return;
		}

		if (!confirm(`Are you sure you want to delete ${selected.length} customers? This action cannot be undone.`)) {
			return;
		}

		// Delete customers one by one
		selected.forEach(customerId => {
			this.deleteCustomer(customerId);
		});
	},

	generateCSV(customers) {
		const headers = ['ID', 'Name', 'Email', 'Phone', 'Total Orders', 'Total Spent', 'Last Order Date', 'Registered Date'];
		const rows = customers.map(customer => [
			customer.id,
			customer.name,
			customer.email,
			customer.phone || '',
			customer.total_orders || 0,
			customer.total_spent || 0,
			customer.last_order_date || '',
			customer.registered_date || ''
		]);

		const csvData = [headers, ...rows];
		return csvData.map(row => row.map(field => `"${field}"`).join(',')).join('\n');
	},

	downloadCSV(content, filename) {
		const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
		const link = document.createElement('a');
		const url = URL.createObjectURL(blob);
		link.setAttribute('href', url);
		link.setAttribute('download', filename);
		link.style.visibility = 'hidden';
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	},

// Global functions for HTML onclick handlers
function showAddCustomerModal() {
	CustomersManager.showAddCustomerModal();
}

function closeAddCustomerModal() {
	CustomersManager.closeAddCustomerModal();
}

function closeCustomerModal() {
	CustomersManager.closeCustomerModal();
}

function exportCustomers() {
	CustomersManager.exportCustomers();
}

function toggleSelectAll() {
	CustomersManager.toggleSelectAll();
}

function toggleBulkActions() {
	CustomersManager.toggleBulkActions();
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
	CustomersManager.init();
});
</script>

</body>
</html>
