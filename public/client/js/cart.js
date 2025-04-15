/**
 * Cart functionality for AISUKI restaurant
 */
class ShoppingCart {
    constructor() {
        this.items = [];
        this.total = 0;
        this.count = 0;
        this.init();
    }

    /**
     * Initialize cart from localStorage
     */
    init() {
        try {
            const storedItems = localStorage.getItem('aisuki_cart');
            if (storedItems) {
                this.items = JSON.parse(storedItems);
                this.calculateTotals();
            }
            this.renderCart();
        } catch (error) {
            console.error('Error initializing cart:', error);
            // Reset cart if corrupted
            localStorage.removeItem('aisuki_cart');
            this.items = [];
            this.calculateTotals();
        }
    }

    /**
     * Add item to cart
     * @param {Object} item - Item to add
     */
    addItem(item) {
        if (!item || !item.id || !item.name || isNaN(parseFloat(item.price))) {
            console.error('Invalid item data:', item);
            return false;
        }

        // Sanitize numeric values
        item.price = parseFloat(item.price);
        item.quantity = parseInt(item.quantity) || 1;
        
        // Sanitize add-ons
        if (Array.isArray(item.addons)) {
            item.addons = item.addons.map(addon => {
                return {
                    name: String(addon.name || ''),
                    price: parseFloat(addon.price) || 0
                };
            });
        } else {
            item.addons = [];
        }

        // Create a unique key for the item including its add-ons
        const itemKey = this.generateItemKey(item);
        
        // Check if item already exists in cart
        const existingItemIndex = this.items.findIndex(i => this.generateItemKey(i) === itemKey);
        
        if (existingItemIndex !== -1) {
            // Update existing item quantity
            this.items[existingItemIndex].quantity += item.quantity;
            this.updateItemTotal(this.items[existingItemIndex]);
        } else {
            // Calculate item total
            item.total = this.calculateItemTotal(item);
            
            // Add new item
            this.items.push(item);
        }

        this.calculateTotals();
        this.saveCart();
        this.renderCart();
        return true;
    }

    /**
     * Generate a unique key for an item based on its ID and add-ons
     * @param {Object} item - Cart item
     * @returns {String} Unique key
     */
    generateItemKey(item) {
        let addonsKey = '';
        if (item.addons && item.addons.length > 0) {
            addonsKey = item.addons
                .map(addon => `${addon.name}:${addon.price}`)
                .sort()
                .join('|');
        }
        // Add special note to the key if present
        const noteKey = item.note ? `|note:${item.note}` : '';
        return `${item.id}|${addonsKey}${noteKey}`;
    }

    /**
     * Update item quantity
     * @param {String} itemId - Item ID in cart
     * @param {Number} quantity - New quantity
     */
    updateQuantity(itemIndex, quantity) {
        if (itemIndex < 0 || itemIndex >= this.items.length) {
            console.error('Invalid item index:', itemIndex);
            return false;
        }

        quantity = parseInt(quantity);
        if (isNaN(quantity) || quantity < 1) {
            console.error('Invalid quantity:', quantity);
            return false;
        }

        this.items[itemIndex].quantity = quantity;
        this.updateItemTotal(this.items[itemIndex]);
        this.calculateTotals();
        this.saveCart();
        this.renderCart();
        return true;
    }

    /**
     * Remove item from cart
     * @param {Number} itemIndex - Item index to remove
     */
    removeItem(itemIndex) {
        if (itemIndex < 0 || itemIndex >= this.items.length) {
            console.error('Invalid item index:', itemIndex);
            return false;
        }

        this.items.splice(itemIndex, 1);
        this.calculateTotals();
        this.saveCart();
        this.renderCart();
        return true;
    }

    /**
     * Calculate total for a specific item
     * @param {Object} item - Cart item
     * @returns {Number} Item total
     */
    calculateItemTotal(item) {
        if (!item) return 0;
        
        let addonsTotal = 0;
        if (item.addons && item.addons.length > 0) {
            addonsTotal = item.addons.reduce((sum, addon) => sum + (parseFloat(addon.price) || 0), 0);
        }
        
        const itemPrice = parseFloat(item.price) || 0;
        const quantity = parseInt(item.quantity) || 1;
        
        return (itemPrice * quantity) + addonsTotal;
    }

    /**
     * Update the total for a specific item
     * @param {Object} item - Cart item to update
     */
    updateItemTotal(item) {
        item.total = this.calculateItemTotal(item);
    }

    /**
     * Calculate cart totals
     */
    calculateTotals() {
        this.total = 0;
        this.count = 0;
        
        this.items.forEach(item => {
            // Update each item's total
            this.updateItemTotal(item);
            
            // Add to cart total
            this.total += item.total;
            
            // Update item count
            this.count += item.quantity;
        });
    }

    /**
     * Save cart to localStorage
     */
    saveCart() {
        try {
            localStorage.setItem('aisuki_cart', JSON.stringify(this.items));
        } catch (error) {
            console.error('Error saving cart to localStorage:', error);
        }
    }

    /**
     * Clear all items from cart
     */
    clearCart() {
        this.items = [];
        this.calculateTotals();
        this.saveCart();
        this.renderCart();
    }

    /**
     * Format price
     * @param {Number} price - Price to format
     * @returns {String} Formatted price
     */
    formatPrice(price) {
        return new Intl.NumberFormat('de-DE', { 
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2 
        }).format(price) + ' €';
    }

    /**
     * Render cart in the UI
     */
    renderCart() {
        // Update cart count badge
        this.updateCartCountBadge();
        
        // Render mini cart in header
        this.renderMiniCart();
    }

    /**
     * Update cart count badge
     */
    updateCartCountBadge() {
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach(element => {
            element.textContent = this.count;
        });
    }

    /**
     * Render mini cart in header dropdown
     */
    renderMiniCart() {
        const cartContainer = document.getElementById('miniCart');
        if (!cartContainer) return;

        // Update cart header
        const cartHeaderElement = cartContainer.querySelector('.p-4.border-b h3');
        if (cartHeaderElement) {
            cartHeaderElement.innerHTML = `${trans('your_cart', 'Your Cart')} (${this.count})`;
        }

        // Get items container
        const itemsContainer = cartContainer.querySelector('.max-h-80.overflow-y-auto');
        if (!itemsContainer) return;

        // Clear existing items
        itemsContainer.innerHTML = '';

        // Add items
        if (this.items.length === 0) {
            itemsContainer.innerHTML = `
                <div class="p-8 text-center text-theme-secondary">
                    <i class="fas fa-shopping-cart text-4xl mb-4 opacity-30"></i>
                    <p>${trans('cart_empty', 'Your cart is empty')}</p>
                </div>
            `;
        } else {
            this.items.forEach((item, index) => {
                const itemElement = document.createElement('div');
                itemElement.className = 'p-4 border-b border-theme flex';
                
                // Format add-ons for display
                const addonsHtml = item.addons && item.addons.length > 0 
                    ? `<div class="text-xs text-theme-secondary mt-1">
                         + ${item.addons.map(a => a.name).join(', ')}
                       </div>` 
                    : '';
                
                // Format note if exists
                const noteHtml = item.note 
                    ? `<div class="text-xs italic text-theme-secondary mt-1">
                         "${item.note}"
                       </div>` 
                    : '';
                
                itemElement.innerHTML = `
                    <img src="${item.image}" alt="${item.name}" class="w-16 h-16 rounded object-cover">
                    <div class="ml-3 flex-1">
                        <div class="flex justify-between">
                            <h4 class="font-medium text-theme-primary">${item.name}</h4>
                            <button class="text-gray-400 hover:text-aisuki-red remove-item" data-index="${index}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        ${addonsHtml}
                        ${noteHtml}
                        <div class="flex justify-between mt-2">
                            <div class="flex items-center border rounded overflow-hidden border-theme">
                                <button class="px-2 py-1 bg-theme-secondary hover:bg-opacity-80 decrease-qty" data-index="${index}">-</button>
                                <span class="px-2 text-theme-primary item-qty">${item.quantity}</span>
                                <button class="px-2 py-1 bg-theme-secondary hover:bg-opacity-80 increase-qty" data-index="${index}">+</button>
                            </div>
                            <p class="font-semibold text-aisuki-red">${this.formatPrice(item.total)}</p>
                        </div>
                    </div>
                `;
                
                itemsContainer.appendChild(itemElement);
            });
        }

        // Update cart footer
        const totalElement = cartContainer.querySelector('.flex.justify-between.mb-3 .font-bold');
        if (totalElement) {
            totalElement.textContent = this.formatPrice(this.total);
        }

        // Add event listeners
        this.addCartEventListeners();
    }

    /**
     * Add event listeners to cart elements
     */
    addCartEventListeners() {
        // Increase quantity buttons
        document.querySelectorAll('.increase-qty').forEach(button => {
            button.addEventListener('click', (e) => {
                const index = parseInt(e.currentTarget.dataset.index);
                if (!isNaN(index) && index >= 0 && index < this.items.length) {
                    this.updateQuantity(index, this.items[index].quantity + 1);
                }
            });
        });

        // Decrease quantity buttons
        document.querySelectorAll('.decrease-qty').forEach(button => {
            button.addEventListener('click', (e) => {
                const index = parseInt(e.currentTarget.dataset.index);
                if (!isNaN(index) && index >= 0 && index < this.items.length) {
                    if (this.items[index].quantity > 1) {
                        this.updateQuantity(index, this.items[index].quantity - 1);
                    }
                }
            });
        });

        // Remove item buttons
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', (e) => {
                const index = parseInt(e.currentTarget.dataset.index);
                if (!isNaN(index) && index >= 0 && index < this.items.length) {
                    this.removeItem(index);
                }
            });
        });
    }
}

/**
 * Helper function for translations
 * @param {String} key - Translation key
 * @param {String} fallback - Fallback text
 * @returns {String} Translated text or fallback
 */
function trans(key, fallback) {
    // Here you would normally connect to your trans_db function
    // For simplicity, we'll just return the fallback
    return fallback;
}

// Initialize cart when DOM is loaded
let cart;
document.addEventListener('DOMContentLoaded', () => {
    cart = new ShoppingCart();
    
    // Add event listener for "Add to Cart" button in modal
    const addToCartBtn = document.getElementById('addToCartBtn');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', () => {
            const productData = $(addToCartBtn).data();
            const qty = parseInt($('#productQty').val()) || 1;
            const note = $('#orderNote').val();
            
            // Get selected add-ons
            let addons = [];
            $('.addon-checkbox:checked').each(function() {
                const addonLabel = $(this).next('label').text();
                const addonPrice = parseFloat($(this).data('price')) || 0;
                
                addons.push({
                    name: addonLabel,
                    price: addonPrice
                });
            });
            
            // Create item object
            const newItem = {
                id: productData.id,
                code: productData.code,
                name: productData.name,
                price: productData.price,
                image: productData.image,
                quantity: qty,
                addons: addons,
                note: note,
            };
            
            // Add to cart
            const added = cart.addItem(newItem);
            
            if (added) {
                // Close modal
                $('#productModalOverlay').removeClass('active');
                $('#productModal').removeClass('active');
                $('body').removeClass('overflow-hidden');
                
                // Show success notification (you could replace this with a nicer toast notification)
                const message = `${qty}x ${productData.name} added to your cart`;
                showNotification(message, 'success');
            }
        });
    }
});

/**
 * Show a notification message
 * @param {String} message - Message to display
 * @param {String} type - Message type (success, error, etc.)
 */
function showNotification(message, type = 'info') {
    // Simple alert for now, can be replaced with a better notification system
    alert(message);
}