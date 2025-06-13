<?php
// This file is included via AJAX to load content for the products side panel.

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Get products (you can add pagination/filtering args here later if needed)
$args = array(
    'post_type' => 'product',
    'posts_per_page' => 10, // Display 10 products for the panel
    'orderby' => 'date',
    'order' => 'DESC'
);

$products = wc_get_products($args);

if (empty($products)) {
    echo '<div class="text-center text-gray-500 p-4">No products found.</div>';
} else {
    foreach ($products as $product) {
        // Basic HTML structure for a product item in the panel
        ?>
            <!-- Products Side Panel -->
    <div id="products-panel" class="fixed top-0 right-0 h-full w-96 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50">
        <div class="h-full flex flex-col">
            <!-- Panel Header -->
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-bold woodash-gradient-text">Products</h2>
                <button class="close-products-panel p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <i class="fa-solid fa-times text-gray-500"></i>
                </button>
            </div>

            <!-- Panel Content -->
            <div class="flex-1 overflow-y-auto p-4">
                <div class="woodash-loading-spinner"></div>
            </div>
        </div>
    </div>
        <!-- <div class="mb-4 p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center gap-4">
                <img src="<?php echo esc_url(wp_get_attachment_image_url($product->get_image_id(), 'thumbnail') ?: wc_placeholder_img_src()); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="w-16 h-16 object-cover rounded-lg">
                <div class="flex-1">
                    <h3 class="font-medium text-gray-900"><?php echo esc_html($product->get_name()); ?></h3>
                    <p class="text-sm text-gray-500"><?php echo esc_html(wp_strip_all_tags($product->get_short_description() ?: $product->get_description())); ?></p>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[#00CC61] font-medium"><?php echo $product->get_price_html(); ?></span>
                        <span class="text-sm <?php echo $product->get_stock_status() === 'instock' ? 'text-green-600' : 'text-red-600'; ?>"><?php echo $product->get_stock_status() === 'instock' ? 'In Stock' : 'Out of Stock'; ?></span>
                    </div>
                </div>
            </div>
        </div> -->
        <?php
    }
} 