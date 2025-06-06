document.addEventListener('DOMContentLoaded', function() {
    initializeCouponActions();
    initializeNewCouponForm();
});

// Initialize coupon actions
function initializeCouponActions() {
    // Edit coupon handler
    document.addEventListener('click', function(e) {
        if (e.target.matches('.edit-coupon') || e.target.closest('.edit-coupon')) {
            const couponId = e.target.closest('[data-coupon-id]').dataset.couponId;
            editCoupon(couponId);
        }
    });

    // Remove coupon handler
    document.addEventListener('click', function(e) {
        if (e.target.matches('.remove-coupon') || e.target.closest('.remove-coupon')) {
            const couponId = e.target.closest('[data-coupon-id]').dataset.couponId;
            removeCoupon(couponId);
        }
    });
}

// Initialize new coupon form
function initializeNewCouponForm() {
    const addNewButton = document.getElementById('add-new-coupon');
    if (addNewButton) {
        addNewButton.addEventListener('click', showNewCouponModal);
    }
}

// Edit coupon function
function editCoupon(couponId) {
    // Show loading state
    showLoading(true);

    // Fetch coupon data
    jQuery.post(woodash_ajax.ajax_url, {
        action: 'woodash_get_coupon_data',
        coupon_id: couponId,
        nonce: woodash_ajax.nonce
    }, function(response) {
        showLoading(false);
        if (response.success) {
            showEditModal(response.data);
        } else {
            showNotification('Error loading coupon data', 'error');
        }
    }).fail(function() {
        showLoading(false);
        showNotification('Error loading coupon data', 'error');
    });
}

// Remove coupon function
function removeCoupon(couponId) {
    if (confirm('Are you sure you want to remove this coupon?')) {
        showLoading(true);
        
        jQuery.post(woodash_ajax.ajax_url, {
            action: 'woodash_remove_coupon',
            coupon_id: couponId,
            nonce: woodash_ajax.nonce
        }, function(response) {
            showLoading(false);
            if (response.success) {
                // Remove the coupon element from the DOM
                const couponElement = document.querySelector(`[data-coupon-id="${couponId}"]`);
                if (couponElement) {
                    couponElement.remove();
                }
                showNotification('Coupon removed successfully', 'success');
                // Refresh the coupons list
                refreshCouponsList();
            } else {
                showNotification('Error removing coupon', 'error');
            }
        }).fail(function() {
            showLoading(false);
            showNotification('Error removing coupon', 'error');
        });
    }
}

// Show edit modal
function showEditModal(couponData) {
    // Create modal HTML
    const modalHtml = `
        <div class="woodash-modal" id="edit-coupon-modal">
            <div class="woodash-modal-content">
                <div class="woodash-modal-header">
                    <h3 class="text-lg font-semibold">Edit Coupon</h3>
                    <button class="woodash-modal-close">&times;</button>
                </div>
                <div class="woodash-modal-body">
                    <form id="edit-coupon-form">
                        <input type="hidden" name="coupon_id" value="${couponData.id}">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Code</label>
                            <input type="text" name="code" value="${couponData.code}" class="woodash-input" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Discount Type</label>
                            <select name="discount_type" class="woodash-select">
                                <option value="percent" ${couponData.type === 'percent' ? 'selected' : ''}>Percentage</option>
                                <option value="fixed" ${couponData.type === 'fixed' ? 'selected' : ''}>Fixed Amount</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" name="amount" value="${couponData.amount}" class="woodash-input" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                            <input type="date" name="expiry_date" value="${couponData.expiry_date}" class="woodash-input">
                        </div>
                    </form>
                </div>
                <div class="woodash-modal-footer">
                    <button class="woodash-btn woodash-btn-secondary woodash-modal-close">Cancel</button>
                    <button class="woodash-btn woodash-btn-primary" id="save-coupon">Save Changes</button>
                </div>
            </div>
        </div>
    `;

    // Add modal to DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Add event listeners
    const modal = document.getElementById('edit-coupon-modal');
    const closeButtons = modal.querySelectorAll('.woodash-modal-close');
    const saveButton = modal.querySelector('#save-coupon');

    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            modal.remove();
        });
    });

    saveButton.addEventListener('click', () => {
        const form = document.getElementById('edit-coupon-form');
        const formData = new FormData(form);
        
        showLoading(true);
        
        jQuery.post(woodash_ajax.ajax_url, {
            action: 'woodash_update_coupon',
            coupon_id: formData.get('coupon_id'),
            code: formData.get('code'),
            discount_type: formData.get('discount_type'),
            amount: formData.get('amount'),
            expiry_date: formData.get('expiry_date'),
            nonce: woodash_ajax.nonce
        }, function(response) {
            showLoading(false);
            if (response.success) {
                modal.remove();
                showNotification('Coupon updated successfully', 'success');
                refreshCouponsList();
            } else {
                showNotification('Error updating coupon', 'error');
            }
        }).fail(function() {
            showLoading(false);
            showNotification('Error updating coupon', 'error');
        });
    });
}

// Show new coupon modal
window.showNewCouponModal = function() {
    console.log('Opening new item modal...');
    
    // Create modal container
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
    modal.id = 'new-item-modal';

    // Create modal content
    modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Add New Item</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeNewItemModal()">
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
                            ${getCategoriesOptions()}
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
    `;

    // Add modal to body
    document.body.appendChild(modal);

    // Initialize form handling
    initializeNewItemForm();
};

// Make sure these functions are also in the global scope
window.closeNewItemModal = function() {
    const modal = document.getElementById('new-item-modal');
    if (modal) {
        modal.remove();
    }
};

window.getCategoriesOptions = function() {
    // This function will be populated with actual categories from WordPress
    // For now, returning a placeholder
    return `
        <option value="1">Category 1</option>
        <option value="2">Category 2</option>
        <option value="3">Category 3</option>
    `;
};

window.initializeNewItemForm = function() {
    const form = document.getElementById('add-item-form');
    const fileInput = document.getElementById('file-upload');
    let uploadedImageId = null;

    // Handle file upload
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('action', 'woodash_upload_image');
            formData.append('nonce', woodash_ajax.nonce);
            formData.append('image', file);

            showLoading(true);

            jQuery.ajax({
                url: woodash_ajax.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    showLoading(false);
                    if (response.success) {
                        uploadedImageId = response.data.id;
                        showNotification('Image uploaded successfully', 'success');
                    } else {
                        showNotification('Error uploading image', 'error');
                    }
                },
                error: function() {
                    showLoading(false);
                    showNotification('Error uploading image', 'error');
                }
            });
        }
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        formData.append('action', 'woodash_add_item');
        formData.append('nonce', woodash_ajax.nonce);
        if (uploadedImageId) {
            formData.append('image_id', uploadedImageId);
        }

        showLoading(true);

        jQuery.ajax({
            url: woodash_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showLoading(false);
                if (response.success) {
                    showNotification('Item added successfully', 'success');
                    closeNewItemModal();
                    // Optionally refresh the page or update the UI
                    location.reload();
                } else {
                    showNotification(response.data || 'Error adding item', 'error');
                }
            },
            error: function() {
                showLoading(false);
                showNotification('Error adding item', 'error');
            }
        });
    });
};

// Refresh coupons list
function refreshCouponsList() {
    const container = document.getElementById('top-coupons-list');
    if (!container) return;

    showLoading(true);
    
    jQuery.post(woodash_ajax.ajax_url, {
        action: 'woodash_get_coupons_list',
        nonce: woodash_ajax.nonce
    }, function(response) {
        showLoading(false);
        if (response.success) {
            container.innerHTML = response.data;
        } else {
            showNotification('Error refreshing coupons list', 'error');
        }
    }).fail(function() {
        showLoading(false);
        showNotification('Error refreshing coupons list', 'error');
    });
}

// Show loading state
function showLoading(show) {
    const loader = document.getElementById('woodash-loading');
    if (loader) {
        loader.style.display = show ? 'flex' : 'none';
    }
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `woodash-notification woodash-notification-${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
} 