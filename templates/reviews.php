<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Check if user has required capabilities
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'woodash-pro'));
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
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: var(--transition-base);
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .woodash-btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .woodash-btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }

        .woodash-btn-secondary {
            background: #F3F4F6;
            color: #374151;
        }

        .woodash-btn-secondary:hover {
            background: #E5E7EB;
        }

        /* Table Styles */
        .woodash-table {
            width: 100%;
            border-collapse: collapse;
        }

        .woodash-table th,
        .woodash-table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #E5E7EB;
        }

        .woodash-table th {
            font-weight: 600;
            color: #374151;
            background: #F9FAFB;
        }

        .woodash-table tbody tr:last-child td {
            border-bottom: none;
        }

        .woodash-table tr {
            transition: background-color 0.2s ease;
        }

        .woodash-table tr:hover {
            background: #F9FAFB;
        }

        /* Badge Styles */
        .woodash-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .woodash-badge-success {
            background: #DCFCE7;
            color: #16A34A;
        }

        .woodash-badge-danger {
            background: #FEE2E2;
            color: #DC2626;
        }

        .woodash-badge-warning {
            background: #FEF3C7;
            color: #D97706;
        }

        .woodash-badge-blue {
            background: #DBEAFE;
            color: #2563EB;
        }

        /* Star Rating */
        .star-rating {
            display: flex;
            gap: 0.125rem;
        }

        .star {
            color: #D1D5DB;
            transition: color 0.2s ease;
        }

        .star.filled {
            color: #F59E0B;
        }

        /* Mobile responsiveness */
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
                display: block;
            }
        }

        .woodash-menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 60;
        }
    </style>
</head>
<body>

<div id="woodash-dashboard" class="woodash-fullscreen flex">
    <!-- Sidebar -->
    <aside class="woodash-sidebar w-64 bg-white/90 border-r border-gray-100 p-6 woodash-glass-effect">
        <div class="flex items-center gap-3 mb-8 woodash-fade-in">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center woodash-glow">
                <i class="fa-solid fa-chart-line text-white text-xl"></i>
            </div>
            <h2 class="text-xl font-bold woodash-gradient-text">WooDash Pro</h2>
        </div>

        <!-- Main Navigation -->
        <nav class="space-y-1">
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-gauge w-5"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-orders')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-cart-shopping w-5"></i>
                <span>Orders</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-products')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-box w-5"></i>
                <span>Products</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-customers')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-users w-5"></i>
                <span>Customers</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-stock')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-boxes-stacked w-5"></i>
                <span>Stock</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-reviews')); ?>" class="woodash-nav-link active">
                <i class="fa-solid fa-star w-5"></i>
                <span>Reviews</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-marketing')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-bullhorn w-5"></i>
                <span>Marketing</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-reports')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-file-lines w-5"></i>
                <span>Reports</span>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=woodash-pro-settings')); ?>" class="woodash-nav-link">
                <i class="fa-solid fa-gear w-5"></i>
                <span>Settings</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="woodash-main flex-1 p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold woodash-gradient-text">Reviews</h1>
                    <p class="text-gray-500">Monitor and manage customer reviews and ratings.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="woodash-btn woodash-btn-secondary">
                        <i class="fa-solid fa-filter"></i>
                        <span>Filter</span>
                    </button>
                    <button class="woodash-btn woodash-btn-primary">
                        <i class="fa-solid fa-download"></i>
                        <span>Export</span>
                    </button>
                </div>
            </header>

            <!-- Review Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Total Reviews</h3>
                            <div class="text-3xl font-bold text-gray-900">1,247</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Average Rating</h3>
                            <div class="text-3xl font-bold text-gray-900">4.6</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Pending Reviews</h3>
                            <div class="text-3xl font-bold text-gray-900">23</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="woodash-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">This Month</h3>
                            <div class="text-3xl font-bold text-gray-900">186</div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Table -->
            <div class="woodash-card p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <h2 class="text-lg font-bold woodash-gradient-text">Recent Reviews</h2>
                    <div class="flex items-center gap-3">
                        <input type="text" placeholder="Search reviews..." class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <select class="woodash-btn woodash-btn-secondary">
                            <option>All Reviews</option>
                            <option>5 Stars</option>
                            <option>4 Stars</option>
                            <option>3 Stars</option>
                            <option>2 Stars</option>
                            <option>1 Star</option>
                            <option>Pending</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="woodash-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reviews-table-body">
                            <!-- Reviews data will be loaded here -->
                        </tbody>
                    </table>
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

<script>
// WordPress AJAX Configuration
window.woodash_ajax = {
    ajax_url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
    nonce: '<?php echo wp_create_nonce('woodash_nonce'); ?>'
};

document.addEventListener('DOMContentLoaded', function() {
    const ReviewsManager = {
        state: {
            reviews: [],
            currentPage: 1,
            reviewsPerPage: 20
        },

        init() {
            this.loadRealReviews();
            this.initEventListeners();
            this.loadReviewStats();
        },

        async loadRealReviews() {
            try {
                const formData = new FormData();
                formData.append('action', 'woodash_get_reviews');
                formData.append('nonce', woodash_ajax.nonce);

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.state.reviews = result.data;
                    this.renderReviewsTable();
                } else {
                    console.error('Failed to load reviews:', result.data);
                    this.loadDemoReviews(); // Fallback to demo data
                }
            } catch (error) {
                console.error('Error loading reviews:', error);
                this.loadDemoReviews(); // Fallback to demo data
            }
        },

        async loadReviewStats() {
            try {
                const formData = new FormData();
                formData.append('action', 'woodash_get_review_stats');
                formData.append('nonce', woodash_ajax.nonce);

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.updateReviewStats(result.data);
                }
            } catch (error) {
                console.error('Error loading review stats:', error);
            }
        },

        renderReviewsTable() {
            const tbody = document.querySelector('#reviews-table tbody');
            if (!tbody) return;

            tbody.innerHTML = '';

            this.state.reviews.forEach(review => {
                const row = document.createElement('tr');
                row.className = 'border-b border-gray-200 hover:bg-gray-50 transition-colors';
                
                const statusClass = review.status === '1' || review.status === 'approved' ? 
                    'bg-green-100 text-green-800' : 
                    review.status === '0' || review.status === 'pending' ?
                    'bg-yellow-100 text-yellow-800' : 
                    'bg-red-100 text-red-800';

                const statusText = review.status === '1' || review.status === 'approved' ? 'Approved' :
                                 review.status === '0' || review.status === 'pending' ? 'Pending' : 'Spam';
                
                row.innerHTML = `
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">${review.author}</div>
                        <div class="text-sm text-gray-500">${review.author_email}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">${review.product_name}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            ${this.renderStars(review.rating)}
                            <span class="ml-2 text-sm text-gray-600">${review.rating}/5</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">${review.content}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                            ${statusText}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">${this.formatDate(review.date)}</td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <button onclick="ReviewsManager.approveReview(${review.id})" 
                                class="text-green-600 hover:text-green-900 mr-2" title="Approve">
                            <i class="fas fa-check"></i>
                        </button>
                        <button onclick="ReviewsManager.rejectReview(${review.id})" 
                                class="text-yellow-600 hover:text-yellow-900 mr-2" title="Pending">
                            <i class="fas fa-clock"></i>
                        </button>
                        <button onclick="ReviewsManager.spamReview(${review.id})" 
                                class="text-red-600 hover:text-red-900 mr-2" title="Spam">
                            <i class="fas fa-ban"></i>
                        </button>
                        <button onclick="ReviewsManager.deleteReview(${review.id})" 
                                class="text-red-600 hover:text-red-900" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        },

        renderStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '<i class="fas fa-star text-yellow-400"></i>';
                } else {
                    stars += '<i class="far fa-star text-gray-300"></i>';
                }
            }
            return stars;
        },

        async approveReview(reviewId) {
            await this.updateReviewStatus(reviewId, 'approve');
        },

        async rejectReview(reviewId) {
            await this.updateReviewStatus(reviewId, 'hold');
        },

        async spamReview(reviewId) {
            await this.updateReviewStatus(reviewId, 'spam');
        },

        async updateReviewStatus(reviewId, status) {
            try {
                const formData = new FormData();
                formData.append('action', 'woodash_update_review_status');
                formData.append('nonce', woodash_ajax.nonce);
                formData.append('review_id', reviewId);
                formData.append('status', status);

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    this.loadRealReviews(); // Reload reviews
                } else {
                    alert('Failed to update review status: ' + result.data);
                }
            } catch (error) {
                console.error('Error updating review status:', error);
                alert('Error updating review status');
            }
        },

        async deleteReview(reviewId) {
            if (!confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
                return;
            }

            try {
                const formData = new FormData();
                formData.append('action', 'woodash_delete_review');
                formData.append('nonce', woodash_ajax.nonce);
                formData.append('review_id', reviewId);

                const response = await fetch(woodash_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    alert('Review deleted successfully');
                    this.loadRealReviews(); // Reload reviews
                } else {
                    alert('Failed to delete review: ' + result.data);
                }
            } catch (error) {
                console.error('Error deleting review:', error);
                alert('Error deleting review');
            }
        },

        updateReviewStats(stats) {
            // Update stats cards with real data
            const elements = {
                totalReviews: document.querySelector('[data-stat="total-reviews"]'),
                pendingReviews: document.querySelector('[data-stat="pending-reviews"]'),
                approvedReviews: document.querySelector('[data-stat="approved-reviews"]'),
                averageRating: document.querySelector('[data-stat="average-rating"]')
            };

            if (elements.totalReviews) elements.totalReviews.textContent = stats.total_reviews || '0';
            if (elements.pendingReviews) elements.pendingReviews.textContent = stats.pending_reviews || '0';
            if (elements.approvedReviews) elements.approvedReviews.textContent = stats.approved_reviews || '0';
            if (elements.averageRating) elements.averageRating.textContent = (stats.average_rating || 0).toFixed(1);
        },

        loadDemoReviews() {
            // Fallback demo data
            this.state.reviews = [
                {
                    id: 1,
                    author: 'John Doe',
                    author_email: 'john@example.com',
                    product_name: 'Wireless Headphones',
                    rating: 5,
                    content: 'Excellent quality and great sound!',
                    date: '2024-01-15',
                    status: 'approved'
                },
                {
                    id: 2,
                    author: 'Jane Smith',
                    author_email: 'jane@example.com',
                    product_name: 'Cotton T-Shirt',
                    rating: 4,
                    content: 'Very comfortable and good fit.',
                    date: '2024-01-14',
                    status: 'approved'
                },
                {
                    id: 3,
                    author: 'Bob Wilson',
                    author_email: 'bob@example.com',
                    product_name: 'Running Shoes',
                    rating: 3,
                    content: 'Good shoes but sizing runs small.',
                    date: '2024-01-13',
                    status: 'pending'
                }
            ];
            this.renderReviewsTable();
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        },

        initEventListeners() {
            // Mobile menu toggle
            const menuToggle = document.getElementById('woodash-menu-toggle');
            const sidebar = document.querySelector('.woodash-sidebar');

            menuToggle?.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });

            // Handle click outside to close mobile menu
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768 && 
                    !sidebar.contains(e.target) && 
                    !menuToggle.contains(e.target) && 
                    sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            });

            // Search functionality
            const searchInput = document.querySelector('.woodash-search-input');
            searchInput?.addEventListener('input', (e) => {
                this.searchReviews(e.target.value);
            });
        },

        searchReviews(query) {
            if (!query.trim()) {
                this.loadRealReviews();
                return;
            }

            const filteredReviews = this.state.reviews.filter(review => 
                review.author.toLowerCase().includes(query.toLowerCase()) ||
                review.product_name.toLowerCase().includes(query.toLowerCase()) ||
                review.content.toLowerCase().includes(query.toLowerCase())
            );

            this.state.reviews = filteredReviews;
            this.renderReviewsTable();
        }
    };

    // Initialize the reviews manager with backend integration
    ReviewsManager.init();
});
</script>
                },
                {
                    id: 3,
                    customer: 'Bob Johnson',
                    product: 'Programming Book',
                    rating: 3,
                    review: 'Good content but could be better organized.',
                    date: '2024-01-13',
                    status: 'Pending'
                }
            ];

            this.renderReviewsTable(reviews);
        },

        renderReviewsTable(reviews) {
            const tbody = document.getElementById('reviews-table-body');
            if (!tbody) return;

            const html = reviews.map(review => `
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#00CC61] to-[#00b357] flex items-center justify-center text-white font-semibold">
                                ${review.customer.charAt(0)}
                            </div>
                            <span class="font-medium">${review.customer}</span>
                        </div>
                    </td>
                    <td>${review.product}</td>
                    <td>
                        <div class="star-rating">
                            ${this.renderStars(review.rating)}
                        </div>
                    </td>
                    <td class="max-w-xs truncate">${review.review}</td>
                    <td>${review.date}</td>
                    <td>
                        <span class="woodash-badge ${this.getStatusBadgeClass(review.status)}">
                            ${review.status}
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <button class="text-blue-600 hover:text-blue-800" title="View Review">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-800" title="Approve">
                                <i class="fa-solid fa-check"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-800" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');

            tbody.innerHTML = html;
        },

        renderStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                const filled = i <= rating ? 'filled' : '';
                stars += `<i class="fa-solid fa-star star ${filled}"></i>`;
            }
            return stars;
        },

        getStatusBadgeClass(status) {
            switch (status) {
                case 'Approved':
                    return 'woodash-badge-success';
                case 'Pending':
                    return 'woodash-badge-warning';
                case 'Rejected':
                    return 'woodash-badge-danger';
                default:
                    return 'woodash-badge-blue';
            }
        }
    };

    // Initialize the reviews manager
    ReviewsManager.init();
});
</script>

</body>
</html>
