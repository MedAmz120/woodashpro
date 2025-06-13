<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
?>

<!-- Add New Item Form Container -->
<div id="item-form-container" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Add New Item</h3>
            <button type="button" class="text-gray-400 hover:text-gray-500" id="close-item-form">
                <i class="fas fa-times"></i>
            </button>
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
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                        <input type="number" name="regular_price" class="woodash-input w-full pl-7" step="0.01" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sale Price</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                        <input type="number" name="sale_price" class="woodash-input w-full pl-7" step="0.01">
                    </div>
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
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors duration-200">
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
                <div id="image-preview" class="mt-2 hidden">
                    <img src="" alt="Preview" class="max-h-32 mx-auto">
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
            <div class="flex justify-end gap-3">
                <button type="button" class="woodash-btn woodash-btn-secondary" id="cancel-item-form">
                    Cancel
                </button>
                <button type="submit" class="woodash-btn woodash-btn-primary flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Add Item</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Item Form Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addNewItemBtn = document.getElementById('add-new-item-btn');
    const itemFormContainer = document.getElementById('item-form-container');
    const closeItemFormBtn = document.getElementById('close-item-form');
    const cancelItemFormBtn = document.getElementById('cancel-item-form');
    const addItemForm = document.getElementById('add-item-form');
    const fileUpload = document.getElementById('file-upload');
    const imagePreview = document.getElementById('image-preview');
    const imagePreviewImg = imagePreview.querySelector('img');

    // Show form
    addNewItemBtn.addEventListener('click', function() {
        itemFormContainer.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    // Close form
    function closeForm() {
        itemFormContainer.classList.add('hidden');
        document.body.style.overflow = '';
        addItemForm.reset();
        imagePreview.classList.add('hidden');
    }

    closeItemFormBtn.addEventListener('click', closeForm);
    cancelItemFormBtn.addEventListener('click', closeForm);

    // Close on outside click
    itemFormContainer.addEventListener('click', function(e) {
        if (e.target === itemFormContainer) {
            closeForm();
        }
    });

    // Handle image preview
    fileUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreviewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle form submission
    addItemForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'woodash_add_item');
        formData.append('nonce', woodash_ajax.nonce);

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding Item...';
        submitBtn.disabled = true;

        jQuery.ajax({
            url: woodash_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showNotification('Item added successfully', 'success');
                    closeForm();
                    // Optionally refresh the page or update the UI
                    location.reload();
                } else {
                    showNotification(response.data || 'Error adding item', 'error');
                }
            },
            error: function() {
                showNotification('Error adding item', 'error');
            },
            complete: function() {
                // Reset button state
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
        });
    });
});
</script> 