/**
 * AISUKI Restaurant - Cart Module
 * Enhanced version with item-specific addon support
 */

// Define the Cart object - responsible for managing cart functionality
const Cart = {
    // Key for storing cart in localStorage
    storageKey: 'aisuki_cart',
    
    // Currency symbol
    currencySymbol: '€',
    
    // --- CART DATA MANAGEMENT METHODS ---
    
    // Get items from cart
    getItems() {
        const cartData = localStorage.getItem(this.storageKey);
        return cartData ? JSON.parse(cartData) : [];
    },
    
    // Save cart to localStorage
    saveCart(items) {
        localStorage.setItem(this.storageKey, JSON.stringify(items));
        this.updateCartUI();
    },
    
    // Add item to cart
    addItem(item) {
        const cartItems = this.getItems();
        
        // Check if item already exists (compare ID, addons, and note)
        const existingItemIndex = cartItems.findIndex(cartItem => 
            cartItem.id === item.id && 
            JSON.stringify(cartItem.addons || []) === JSON.stringify(item.addons || []) &&
            cartItem.note === item.note
        );
        
        if (existingItemIndex !== -1) {
            // Update quantity if item exists
            cartItems[existingItemIndex].quantity += item.quantity;
            cartItems[existingItemIndex].total = this.calculateItemTotal(cartItems[existingItemIndex]);
        } else {
            // Add new item
            item.total = this.calculateItemTotal(item);
            cartItems.push(item);
        }
        
        this.saveCart(cartItems);
        return cartItems;
    },
    
    // Remove item from cart
    removeItem(index) {
        const cartItems = this.getItems();
        if (index >= 0 && index < cartItems.length) {
            cartItems.splice(index, 1);
            this.saveCart(cartItems);
        }
        return cartItems;
    },
    
    // Update item quantity
    updateQuantity(index, quantity) {
        const cartItems = this.getItems();
        if (index >= 0 && index < cartItems.length) {
            // Ensure quantity is at least 1
            quantity = Math.max(1, parseInt(quantity) || 1);
            
            cartItems[index].quantity = quantity;
            cartItems[index].total = this.calculateItemTotal(cartItems[index]);
            this.saveCart(cartItems);
        }
        return cartItems;
    },
    
    // --- CALCULATION METHODS ---
    
    // Calculate total price for an item (price * quantity + addon prices)
    calculateItemTotal(item) {
        let total = parseFloat(item.price || 0) * parseInt(item.quantity || 1);
        
        // Add addon prices (addons are counted once, not multiplied by quantity)
        if (item.addons && item.addons.length > 0) {
            item.addons.forEach(addon => {
                total += parseFloat(addon.price || 0);
            });
        }
        
        return parseFloat(total.toFixed(2));
    },
    
    // Calculate subtotal (before shipping, discounts)
    getSubtotal() {
        const cartItems = this.getItems();
        const subtotal = cartItems.reduce((total, item) => total + (parseFloat(item.total) || 0), 0);
        return parseFloat(subtotal.toFixed(2));
    },
    
    // Calculate total (including shipping, discounts)
    getTotal(deliveryFee = 0, discount = 0) {
        const subtotal = this.getSubtotal();
        const total = subtotal + parseFloat(deliveryFee || 0) - parseFloat(discount || 0);
        return parseFloat(total.toFixed(2));
    },
    
    // Get total number of items in cart
    getCount() {
        const cartItems = this.getItems();
        return cartItems.reduce((count, item) => count + (parseInt(item.quantity) || 0), 0);
    },
    
    // Format price with currency symbol
    formatPrice(price) {
        return new Intl.NumberFormat('de-DE', { 
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2 
        }).format(price) + ' ' + this.currencySymbol;
    },
    
    // Check if cart is empty
    isEmpty() {
        return this.getItems().length === 0;
    },
    
    // Clear entire cart
    clearCart() {
        localStorage.removeItem(this.storageKey);
        this.updateCartUI();
    },
    
    // --- UI UPDATE METHODS ---
    
    // Update all UI elements
    updateCartUI() {
        // Update cart count on icons
        const cartCount = this.getCount();
        $('.cart-count').text(cartCount);
        
        // Update total on mobile sidebar
        const subtotal = this.getSubtotal();
        $('.mobile-cart-subtotal').text(this.formatPrice(subtotal));
        
        // Update mini cart dropdown
        this.updateMiniCart();
        
        // Update cart page if we're on it
        if ($('#cartPage').length) {
            this.updateCartPage();
        }
        
        // Update checkout button state
        if (this.isEmpty()) {
            $('#mobileSummaryCart a').addClass('opacity-50 pointer-events-none');
        } else {
            $('#mobileSummaryCart a').removeClass('opacity-50 pointer-events-none');
        }
    },
    
    // Update mini cart dropdown
    updateMiniCart() {
        const cartItems = this.getItems();
        const subtotal = this.getSubtotal();
        
        // Get mini cart container
        const miniCartItems = $('.mini-cart-items');
        if (!miniCartItems.length) return;
        
        // Clear current content
        miniCartItems.empty();
        
        if (cartItems.length === 0) {
            // Show empty cart message
            miniCartItems.html(`
                <div class="p-6 text-center">
                    <i class="fas fa-shopping-cart text-3xl mb-2 text-theme-secondary"></i>
                    <p class="text-theme-secondary">${window.translations.cart_empty || 'Giỏ hàng trống'}</p>
                </div>
            `);
            
            // Disable checkout button
            $('.mini-cart-checkout').addClass('opacity-50 pointer-events-none');
        } else {
            // Add each item to mini cart
            cartItems.forEach((item, index) => {
                let addonsHtml = '';
                if (item.addons && item.addons.length > 0) {
                    addonsHtml = '<div class="text-xs text-theme-secondary mt-1">';
                    item.addons.forEach(addon => {
                        addonsHtml += `<span>+ ${addon.name}</span><br>`;
                    });
                    addonsHtml += '</div>';
                }
                
                const itemHtml = `
                    <div class="p-4 border-b border-theme flex">
                        <img src="${item.image}" alt="${item.name}" class="w-16 h-16 rounded object-cover">
                        <div class="ml-3 flex-1">
                            <div class="flex justify-between">
                                <h4 class="font-medium text-theme-primary">${item.name}</h4>
                                <button class="text-gray-400 hover:text-aisuki-red remove-cart-item" data-index="${index}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            ${addonsHtml}
                            <div class="flex justify-between mt-2">
                                <div class="flex items-center border rounded overflow-hidden border-theme">
                                    <button class="px-2 py-1 bg-theme-secondary hover:bg-opacity-80 decrease-qty-mini" data-index="${index}">-</button>
                                    <span class="px-2 text-theme-primary">${item.quantity}</span>
                                    <button class="px-2 py-1 bg-theme-secondary hover:bg-opacity-80 increase-qty-mini" data-index="${index}">+</button>
                                </div>
                                <p class="font-semibold text-aisuki-red">${this.formatPrice(item.total)}</p>
                            </div>
                        </div>
                    </div>
                `;
                
                miniCartItems.append(itemHtml);
            });
            
            // Enable checkout button
            $('.mini-cart-checkout').removeClass('opacity-50 pointer-events-none');
        }
        
        // Update total
        $('.mini-cart-subtotal').text(this.formatPrice(subtotal));
    },
    
    // Update cart page
    updateCartPage() {
        const cartItems = this.getItems();
        const cartItemsContainer = $('#cartItems');
        
        if (!cartItemsContainer.length) return;
        
        // Clear current content
        cartItemsContainer.empty();
        
        if (cartItems.length === 0) {
            // Show empty cart message
            cartItemsContainer.html(`
                <div class="p-8 text-center">
                    <i class="fas fa-shopping-cart text-5xl mb-4 text-theme-secondary"></i>
                    <h3 class="text-xl font-bold text-theme-primary mb-2">${window.translations.cart_empty || 'Giỏ hàng trống'}</h3>
                    <p class="text-theme-secondary mb-6">${window.translations.cart_empty_message || 'Hãy thêm món ăn vào giỏ hàng'}</p>
                    <a href="${window.routes.menu}" class="inline-block bg-aisuki-red text-white py-2 px-6 rounded-md hover:bg-aisuki-red-dark transition-colors">
                        ${window.translations.view_menu || 'Xem thực đơn'}
                    </a>
                </div>
            `);
            
            // Hide checkout controls
            $('#checkoutControls').addClass('hidden');
            
            // Update title
            $('.cart-title').text(window.translations.cart_empty || 'Giỏ hàng trống');
        } else {
            // Update title
            $('.cart-title').text(`${window.translations.cart_items || 'Cart Items'} (${cartItems.length})`);
            
            // Add each item to cart page
            cartItems.forEach((item, index) => {
                let addonsHtml = '';
                if (item.addons && item.addons.length > 0) {
                    addonsHtml = '<div class="text-sm text-theme-secondary mt-1">';
                    item.addons.forEach(addon => {
                        addonsHtml += `<div>+ ${addon.name} (${this.formatPrice(addon.price)})</div>`;
                    });
                    addonsHtml += '</div>';
                }
                
                let noteHtml = '';
                if (item.note) {
                    noteHtml = `
                        <div class="mt-2 italic text-xs text-theme-secondary">
                            <i class="fas fa-sticky-note mr-1"></i> ${item.note}
                        </div>
                    `;
                }
                
                const itemHtml = `
                    <div class="p-4 flex flex-col sm:flex-row sm:items-center border-b border-theme">
                        <div class="w-full sm:w-20 h-20 rounded-md overflow-hidden mb-3 sm:mb-0 sm:mr-4 flex-shrink-0">
                            <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:justify-between">
                                <h3 class="font-medium text-theme-primary">${item.name} <span class="text-xs text-theme-secondary">${item.code}</span></h3>
                                <div class="text-left sm:text-right mt-1 sm:mt-0">
                                    <p class="font-semibold text-aisuki-red">${this.formatPrice(item.total)}</p>
                                    <p class="text-xs text-theme-secondary">${item.quantity} x ${this.formatPrice(item.price)}</p>
                                </div>
                            </div>
                            ${addonsHtml}
                            ${noteHtml}
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center border border-theme rounded-md">
                                    <button class="px-2 py-1 bg-theme-secondary text-theme-primary hover:bg-opacity-80 cart-item-decrease" data-index="${index}">-</button>
                                    <input type="number" min="1" value="${item.quantity}" class="w-14 text-center py-1 border-x border-theme cart-item-qty" data-index="${index}">
                                    <button class="px-2 py-1 bg-theme-secondary text-theme-primary hover:bg-opacity-80 cart-item-increase" data-index="${index}">+</button>
                                </div>
                                <button class="text-theme-secondary hover:text-aisuki-red transition-colors cart-item-remove" data-index="${index}">
                                    <i class="fas fa-trash-alt mr-1"></i> ${window.translations.remove || 'Remove'}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                cartItemsContainer.append(itemHtml);
            });
            
            // Show checkout controls
            $('#checkoutControls').removeClass('hidden');
            
            // Update cart summary
            this.updateCartSummary();
        }
    },
    
    // Update cart summary (subtotal, shipping, etc.)
    updateCartSummary() {
        const subtotal = this.getSubtotal();
        
        // On the cart page, we just show subtotal as total
        if ($('#cartPage').length) {
            // Update subtotal
            $('#cartSubtotal').text(this.formatPrice(subtotal));
            
            // For cart page, total equals subtotal (no delivery fee or discount)
            $('#cartTotal').text(this.formatPrice(subtotal));
        } 
        // On checkout page calculation is handled separately
    },
    
    // Show toast notification
    showToast(message, type = 'success') {
        // Check if toast container exists
        let toastContainer = $('.toast-container');
        if (!toastContainer.length) {
            // Create toast container
            $('body').append('<div class="toast-container"></div>');
            toastContainer = $('.toast-container');
        }
        
        // Create toast
        const toastId = 'toast-' + Date.now();
        const toast = $(`
            <div id="${toastId}" class="toast toast-${type}">
                <div class="toast-content">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} toast-icon"></i>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close"><i class="fas fa-times"></i></button>
                <div class="toast-progress"></div>
            </div>
        `);
        
        // Add toast to container
        toastContainer.append(toast);
        
        // Show toast
        setTimeout(() => {
            toast.addClass('show');
        }, 100);
        
        // Auto-close after 3 seconds
        setTimeout(() => {
            toast.removeClass('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
        
        // Close on click
        toast.find('.toast-close').on('click', function() {
            toast.removeClass('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        });
    },
    
    // --- MENU ITEM SPECIFIC ADDON METHODS ---
    
    // Load addons for a specific menu item
    loadMenuItemAddons(menuItemId) {
        // Get addon container
        const addonSection = $('#addonSection');
        const addonsContainer = $('#menuItemAddons');
        
        // Show loading state
        addonsContainer.html('<div class="text-center py-2"><i class="fas fa-spinner fa-spin mr-2"></i> Loading...</div>');
        
        // Fetch addons for this menu item
        $.ajax({
            url: `/api/menu-items/${menuItemId}/addons`,
            type: 'GET',
            success: (response) => {
                if (response.success && response.addons.length > 0) {
                    // Render addons
                    let addonHtml = '';
                    
                    response.addons.forEach(addon => {
                        addonHtml += `
                            <div class="addon-item">
                                <div class="addon-checkbox-container">
                                    <input type="checkbox" id="addon${addon.id}" class="custom-checkbox addon-checkbox" 
                                        data-id="${addon.id}" 
                                        data-name="${addon.name}" 
                                        data-price="${addon.price}">
                                    <label for="addon${addon.id}" class="addon-label">${addon.name}</label>
                                </div>
                                <span class="addon-price">${this.formatPrice(addon.price)}</span>
                            </div>
                        `;
                    });
                    
                    // Update addon container
                    addonsContainer.html(addonHtml);
                    addonSection.show();
                } else {
                    // No addons available
                    addonSection.hide();
                }
                
                // Update modal total after addons are loaded
                this.updateModalTotal();
            },
            error: () => {
                // Handle error
                addonSection.hide();
            }
        });
    },
    
    // Calculate and update total in order modal
    updateModalTotal() {
        const basePrice = parseFloat($('#addToCartBtn').data('price')) || 0;
        let qty = parseInt($('#productQty').val()) || 1;
        
        // Calculate addon total
        let addonTotal = 0;
        $('.addon-checkbox:checked').each(function() {
            const addonPrice = parseFloat($(this).data('price')) || 0;
            addonTotal += addonPrice;
        });
        
        // Calculate total
        const total = (basePrice * qty) + addonTotal;
        
        // Update display
        $('#modalTotal').text(this.formatPrice(total));
        $('#modalQtySummary').text(`${qty} x ${this.formatPrice(basePrice)}`);
        
        if (addonTotal > 0) {
            $('#modalQtySummary').append(` + ${this.formatPrice(addonTotal)}`);
        }
    },
    
    // --- EVENT HANDLERS ---
    
    // Initialize event listeners
    initEventListeners() {
        const self = this;
        
        // --- PRODUCT MODAL HANDLERS ---
        
        // Open modal when clicking "Order" button
        $(document).on('click', '.order-btn', function(e) {
            e.preventDefault();
            
            // Get product information
            const productId = $(this).data('id');
            const productCode = $(this).data('code');
            const productName = $(this).data('name');
            const productPrice = $(this).data('price');
            const productImage = $(this).data('image');
            const productDesc = $(this).data('description');
            
            // Reset modal
            $('#productQty').val(1);
            $('#orderNote').val('');
            
            // Set product information in modal
            $('#modalProductCode').text(productCode);
            $('#modalProductName').text(productName);
            $('#modalProductImage').attr('src', productImage);
            $('#modalProductDesc').text(productDesc);
            $('#modalProductPrice').text(self.formatPrice(productPrice));
            $('#modalTotal').text(self.formatPrice(productPrice));
            $('#modalQtySummary').text(`1 x ${self.formatPrice(productPrice)}`);
            
            // Save product information to "Add to Cart" button
            $('#addToCartBtn').data({
                id: productId,
                code: productCode,
                name: productName,
                price: productPrice,
                image: productImage
            });
            
            // Load item-specific addons
            self.loadMenuItemAddons(productId);
            
            // Open modal
            $('#productModalOverlay').addClass('active');
            $('#productModal').addClass('active');
            $('body').addClass('overflow-hidden');
        });
        
        // Close product modal
        $(document).on('click', '#closeProductModal, #productModalOverlay', function() {
            $('#productModalOverlay').removeClass('active');
            $('#productModal').removeClass('active');
            $('body').removeClass('overflow-hidden');
        });
        
        // Prevent modal closing when clicking modal content
        $(document).on('click', '#productModal', function(e) {
            e.stopPropagation();
        });
        
        // Increase quantity in modal
        $(document).on('click', '#increaseQty', function() {
            let qty = parseInt($('#productQty').val()) || 1;
            $('#productQty').val(qty + 1);
            self.updateModalTotal();
        });
        
        // Decrease quantity in modal
        $(document).on('click', '#decreaseQty', function() {
            let qty = parseInt($('#productQty').val()) || 1;
            if (qty > 1) {
                $('#productQty').val(qty - 1);
                self.updateModalTotal();
            }
        });
        
        // Direct quantity change
        $(document).on('input', '#productQty', function() {
            let qty = parseInt($(this).val()) || 1;
            if (qty < 1) {
                qty = 1;
                $(this).val(1);
            }
            self.updateModalTotal();
        });
        
        // Addon checkbox change
        $(document).on('change', '.addon-checkbox', function() {
            self.updateModalTotal();
        });
        
        // Add to cart
        $(document).on('click', '#addToCartBtn', function() {
            const productData = $(this).data();
            const qty = parseInt($('#productQty').val()) || 1;
            const note = $('#orderNote').val() || '';
            
            // Get addon information
            let addons = [];
            $('.addon-checkbox:checked').each(function() {
                const addonId = $(this).data('id');
                const addonName = $(this).data('name');
                const addonPrice = parseFloat($(this).data('price')) || 0;
                
                addons.push({
                    id: addonId,
                    name: addonName,
                    price: addonPrice
                });
            });
            
            // Create new item
            const newItem = {
                id: productData.id,
                code: productData.code,
                name: productData.name,
                price: productData.price,
                image: productData.image,
                quantity: qty,
                addons: addons,
                note: note,
                dateAdded: new Date().toISOString()
            };
            
            // Add to cart
            self.addItem(newItem);
            
            // Close modal
            $('#productModalOverlay').removeClass('active');
            $('#productModal').removeClass('active');
            $('body').removeClass('overflow-hidden');
            
            // Show success notification
            self.showToast(`${qty}x ${productData.name} ${window.translations.added_to_cart || 'đã thêm vào giỏ hàng'}`);
            
            // Show mini cart
            $('#miniCart').addClass('active');
        });
        
        // --- MINI CART HANDLERS ---
        
        // Increase quantity in mini cart
        $(document).on('click', '.increase-qty-mini', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const index = $(this).data('index');
            const cartItems = self.getItems();
            if (index >= 0 && index < cartItems.length) {
                const newQty = cartItems[index].quantity + 1;
                self.updateQuantity(index, newQty);
            }
        });
        
        // Decrease quantity in mini cart
        $(document).on('click', '.decrease-qty-mini', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const index = $(this).data('index');
            const cartItems = self.getItems();
            if (index >= 0 && index < cartItems.length && cartItems[index].quantity > 1) {
                const newQty = cartItems[index].quantity - 1;
                self.updateQuantity(index, newQty);
            }
        });
        
        // Remove item from mini cart
        $(document).on('click', '.remove-cart-item', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const index = $(this).data('index');
            const item = self.getItems()[index];
            self.removeItem(index);
            self.showToast(`${item.name} ${window.translations.removed_from_cart || 'đã xóa khỏi giỏ hàng'}`);
        });
        
        // --- CART PAGE HANDLERS ---
        
        // Increase quantity in cart page
        $(document).on('click', '.cart-item-increase', function(e) {
            e.preventDefault();
            const index = $(this).data('index');
            const cartItems = self.getItems();
            if (index >= 0 && index < cartItems.length) {
                const newQty = cartItems[index].quantity + 1;
                self.updateQuantity(index, newQty);
            }
        });
        
        // Decrease quantity in cart page
        $(document).on('click', '.cart-item-decrease', function(e) {
            e.preventDefault();
            const index = $(this).data('index');
            const cartItems = self.getItems();
            if (index >= 0 && index < cartItems.length && cartItems[index].quantity > 1) {
                const newQty = cartItems[index].quantity - 1;
                self.updateQuantity(index, newQty);
            }
        });
        
        // Direct quantity change in cart page
        $(document).on('change', '.cart-item-qty', function() {
            const index = $(this).data('index');
            const qty = parseInt($(this).val()) || 1;
            self.updateQuantity(index, Math.max(1, qty));
            $(this).val(Math.max(1, qty)); // Ensure displayed value is >= 1
        });
        
        // Remove item from cart page
        $(document).on('click', '.cart-item-remove', function(e) {
            e.preventDefault();
            const index = $(this).data('index');
            const item = self.getItems()[index];
            self.removeItem(index);
            self.showToast(`${item.name} ${window.translations.removed_from_cart || 'đã xóa khỏi giỏ hàng'}`);
        });
        
        // Clear entire cart
        $(document).on('click', '#clearCart', function() {
            if (confirm(window.translations.confirm_clear_cart || 'Bạn có chắc muốn xóa toàn bộ giỏ hàng không?')) {
                self.clearCart();
                self.showToast(window.translations.cart_cleared || 'Giỏ hàng đã được xóa');
            }
        });
        
        // // Apply promo code
        // $(document).on('click', '#applyPromo', function() {
        //     const promoCode = $('#promoCode').val().trim();
            
        //     if (!promoCode) {
        //         self.showToast(window.translations.enter_promo_code || 'Vui lòng nhập mã giảm giá', 'error');
        //         return;
        //     }
            
        //     // Mock promo code check (in reality, this would be a server request)
        //     if (promoCode.toUpperCase() === 'AISUKI10') {
        //         // Assume €3 discount
        //         const discount = 3.00;
                
        //         // Show discount row
        //         $('#discountRow').removeClass('hidden');
        //         $('#discountAmount').text(`-${self.formatPrice(discount)}`).data('value', discount);
                
        //         // Update total
        //         self.updateCartSummary();
                
        //         // Show success notification
        //         self.showToast(window.translations.promo_applied || 'Đã áp dụng mã giảm giá');
        //     } else {
        //         // Invalid promo code
        //         self.showToast(window.translations.invalid_promo || 'Mã giảm giá không hợp lệ', 'error');
        //     }
        // });
        
        // Toggle mini cart dropdown
        $(document).on('click', '#cartToggle', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#miniCart').toggleClass('active');
            
            // Close other dropdowns
            $('#langDropdown').removeClass('active');
        });
        
        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#miniCart, #cartToggle').length) {
                $('#miniCart').removeClass('active');
            }
        });
        
        // Prevent dropdown closing when clicking content
        $(document).on('click', '#miniCart', function(e) {
            e.stopPropagation();
        });
    },
    
    // Initialize Cart
    init() {
        this.updateCartUI();
        this.initEventListeners();
    }
};

// Initialize Cart when page is loaded
$(document).ready(function() {
    // Set up translations object
    window.translations = window.translations || {};
    
    // Set up routes object
    window.routes = window.routes || {
        cart: '/cart',
        menu: '/menu'
    };
    
    // Initialize cart
    Cart.init();
});

// Export Cart object for use in other files
window.Cart = Cart;