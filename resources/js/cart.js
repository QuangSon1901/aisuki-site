/**
 * AISUKI Restaurant - Cart Module
 * Xử lý tất cả các tính năng liên quan đến giỏ hàng
 */

// Định nghĩa object Cart - chịu trách nhiệm quản lý giỏ hàng
const Cart = {
    // Khóa lưu giỏ hàng trong localStorage
    storageKey: 'aisuki_cart',
    
    // Ký hiệu tiền tệ
    currencySymbol: '€',
    
    // Lấy danh sách sản phẩm trong giỏ hàng
    getItems() {
        const cartData = localStorage.getItem(this.storageKey);
        return cartData ? JSON.parse(cartData) : [];
    },
    
    // Lưu giỏ hàng vào localStorage
    saveCart(items) {
        localStorage.setItem(this.storageKey, JSON.stringify(items));
        this.updateCartUI();
    },
    
    // Thêm sản phẩm vào giỏ hàng
    addItem(item) {
        const cartItems = this.getItems();
        
        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
        // So sánh ID và danh sách addon
        const existingItemIndex = cartItems.findIndex(cartItem => 
            cartItem.id === item.id && 
            JSON.stringify(cartItem.addons || []) === JSON.stringify(item.addons || []) &&
            cartItem.note === item.note
        );
        
        if (existingItemIndex !== -1) {
            // Cập nhật số lượng nếu sản phẩm đã tồn tại
            cartItems[existingItemIndex].quantity += item.quantity;
            cartItems[existingItemIndex].total = this.calculateItemTotal(cartItems[existingItemIndex]);
        } else {
            // Thêm sản phẩm mới
            item.total = this.calculateItemTotal(item);
            cartItems.push(item);
        }
        
        this.saveCart(cartItems);
        return cartItems;
    },
    
    // Xóa sản phẩm khỏi giỏ hàng
    removeItem(index) {
        const cartItems = this.getItems();
        if (index >= 0 && index < cartItems.length) {
            cartItems.splice(index, 1);
            this.saveCart(cartItems);
        }
        return cartItems;
    },
    
    // Cập nhật số lượng sản phẩm
    updateQuantity(index, quantity) {
        const cartItems = this.getItems();
        if (index >= 0 && index < cartItems.length) {
            // Đảm bảo số lượng ít nhất là 1
            quantity = Math.max(1, parseInt(quantity) || 1);
            
            cartItems[index].quantity = quantity;
            cartItems[index].total = this.calculateItemTotal(cartItems[index]);
            this.saveCart(cartItems);
        }
        return cartItems;
    },
    
    // Tính tổng giá trị của một sản phẩm (giá * số lượng + giá addon)
    calculateItemTotal(item) {
        let total = parseFloat(item.price || 0) * parseInt(item.quantity || 1);
        
        // Cộng thêm giá trị addon (addon chỉ tính 1 lần, không nhân với số lượng)
        if (item.addons && item.addons.length > 0) {
            item.addons.forEach(addon => {
                total += parseFloat(addon.price || 0);
            });
        }
        
        return parseFloat(total.toFixed(2));
    },
    
    // Tính tổng tiền tạm tính (chưa bao gồm phí vận chuyển)
    getSubtotal() {
        const cartItems = this.getItems();
        const subtotal = cartItems.reduce((total, item) => total + (parseFloat(item.total) || 0), 0);
        return parseFloat(subtotal.toFixed(2));
    },
    
    // Tính tổng tiền (bao gồm phí vận chuyển)
    getTotal(deliveryFee = 0, discount = 0) {
        const subtotal = this.getSubtotal();
        const total = subtotal + parseFloat(deliveryFee || 0) - parseFloat(discount || 0);
        return parseFloat(total.toFixed(2));
    },
    
    // Lấy tổng số sản phẩm trong giỏ hàng
    getCount() {
        const cartItems = this.getItems();
        return cartItems.reduce((count, item) => count + (parseInt(item.quantity) || 0), 0);
    },
    
    // Định dạng giá tiền
    formatPrice(price) {
        return new Intl.NumberFormat('de-DE', { 
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2 
        }).format(price) + ' ' + this.currencySymbol;
    },
    
    // Kiểm tra giỏ hàng có trống không
    isEmpty() {
        return this.getItems().length === 0;
    },
    
    // Xóa toàn bộ giỏ hàng
    clearCart() {
        localStorage.removeItem(this.storageKey);
        this.updateCartUI();
    },
    
    // Cập nhật giao diện người dùng
    updateCartUI() {
        // Cập nhật số lượng sản phẩm hiển thị trên icon giỏ hàng
        const cartCount = this.getCount();
        $('.cart-count').text(cartCount);
        
        // Cập nhật mini cart dropdown
        this.updateMiniCart();
        
        // Nếu đang ở trang giỏ hàng, cập nhật trang giỏ hàng
        if ($('#cartPage').length) {
            this.updateCartPage();
        }
    },
    
    // Cập nhật mini cart trong dropdown
    updateMiniCart() {
        const cartItems = this.getItems();
        const subtotal = this.getSubtotal();
        
        // Lấy container của mini cart
        const miniCartItems = $('.mini-cart-items');
        if (!miniCartItems.length) return;
        
        // Xóa nội dung hiện tại
        miniCartItems.empty();
        
        if (cartItems.length === 0) {
            // Hiển thị thông báo giỏ hàng trống
            miniCartItems.html(`
                <div class="p-6 text-center">
                    <i class="fas fa-shopping-cart text-3xl mb-2 text-theme-secondary"></i>
                    <p class="text-theme-secondary">${window.translations.cart_empty || 'Giỏ hàng trống'}</p>
                </div>
            `);
            
            // Vô hiệu hóa nút thanh toán
            $('.mini-cart-checkout').addClass('opacity-50 pointer-events-none');
        } else {
            // Thêm các sản phẩm vào mini cart
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
            
            // Bật nút thanh toán
            $('.mini-cart-checkout').removeClass('opacity-50 pointer-events-none');
        }
        
        // Cập nhật tổng tiền
        $('.mini-cart-subtotal').text(this.formatPrice(subtotal));
    },
    
    // Cập nhật trang giỏ hàng
    updateCartPage() {
        const cartItems = this.getItems();
        const cartItemsContainer = $('#cartItems');
        
        if (!cartItemsContainer.length) return;
        
        // Xóa nội dung hiện tại
        cartItemsContainer.empty();
        
        if (cartItems.length === 0) {
            // Hiển thị thông báo giỏ hàng trống
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
            
            // Ẩn phần điều khiển thanh toán
            $('#checkoutControls').addClass('hidden');
            
            // Cập nhật tiêu đề
            $('.cart-title').text(window.translations.cart_empty || 'Giỏ hàng trống');
        } else {
            // Cập nhật tiêu đề
            $('.cart-title').text(`${window.translations.cart_items || 'Món ăn trong giỏ'} (${cartItems.length})`);
            
            // Thêm từng sản phẩm vào giỏ hàng
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
                                    <i class="fas fa-trash-alt mr-1"></i> ${window.translations.remove || 'Xóa'}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                cartItemsContainer.append(itemHtml);
            });
            
            // Hiện phần điều khiển thanh toán
            $('#checkoutControls').removeClass('hidden');
            
            // Cập nhật phần tóm tắt giỏ hàng
            this.updateCartSummary();
        }
    },
    
    // Cập nhật phần tóm tắt giỏ hàng (tổng tiền, phí vận chuyển,...)
    updateCartSummary() {
        const subtotal = this.getSubtotal();
        const deliveryFee = parseFloat($('#deliveryFee').data('fee') || 0);
        
        // Kiểm tra xem có mã giảm giá không
        let discount = 0;
        if (!$('#discountRow').hasClass('hidden')) {
            discount = parseFloat($('#discountAmount').data('value') || 0);
        }
        
        const total = this.getTotal(deliveryFee, discount);
        
        // Cập nhật tạm tính
        $('#cartSubtotal').text(this.formatPrice(subtotal));
        
        // Cập nhật tổng cộng
        $('#cartTotal').text(this.formatPrice(total));
    },
    
    // Hiển thị thông báo
    showToast(message, type = 'success') {
        // Kiểm tra xem container toast đã tồn tại chưa
        let toastContainer = $('.toast-container');
        if (!toastContainer.length) {
            // Tạo container toast
            $('body').append('<div class="toast-container"></div>');
            toastContainer = $('.toast-container');
        }
        
        // Tạo toast
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
        
        // Thêm toast vào container
        toastContainer.append(toast);
        
        // Hiển thị toast
        setTimeout(() => {
            toast.addClass('show');
        }, 100);
        
        // Tự động đóng sau 3 giây
        setTimeout(() => {
            toast.removeClass('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
        
        // Đóng khi click vào nút đóng
        toast.find('.toast-close').on('click', function() {
            toast.removeClass('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        });
    },
    
    // Khởi tạo các lắng nghe sự kiện
    initEventListeners() {
        const self = this;
        
        // Modal sản phẩm
        
        // Mở modal khi click vào nút "Đặt món"
        $(document).on('click', '.order-btn', function(e) {
            e.preventDefault();
            
            // Lấy thông tin sản phẩm
            const productId = $(this).data('id');
            const productCode = $(this).data('code');
            const productName = $(this).data('name');
            const productPrice = $(this).data('price');
            const productImage = $(this).data('image');
            const productDesc = $(this).data('description');
            
            // Reset modal
            $('#productQty').val(1);
            $('.addon-checkbox').prop('checked', false);
            $('#orderNote').val('');
            
            // Thiết lập thông tin sản phẩm trong modal
            $('#modalProductCode').text(productCode);
            $('#modalProductName').text(productName);
            $('#modalProductImage').attr('src', productImage);
            $('#modalProductDesc').text(productDesc);
            $('#modalProductPrice').text(self.formatPrice(productPrice));
            $('#modalTotal').text(self.formatPrice(productPrice));
            $('#modalQtySummary').text(`1 x ${self.formatPrice(productPrice)}`);
            
            // Lưu thông tin sản phẩm vào nút "Thêm vào giỏ hàng"
            $('#addToCartBtn').data({
                id: productId,
                code: productCode,
                name: productName,
                price: productPrice,
                image: productImage
            });
            
            // Mở modal
            $('#productModalOverlay').addClass('active');
            $('#productModal').addClass('active');
            $('body').addClass('overflow-hidden');
        });
        
        // Đóng modal sản phẩm
        $(document).on('click', '#closeProductModal, #productModalOverlay', function() {
            $('#productModalOverlay').removeClass('active');
            $('#productModal').removeClass('active');
            $('body').removeClass('overflow-hidden');
        });
        
        // Ngăn modal đóng khi click vào nội dung
        $(document).on('click', '#productModal', function(e) {
            e.stopPropagation();
        });
        
        // Tăng số lượng trong modal
        $(document).on('click', '#increaseQty', function() {
            let qty = parseInt($('#productQty').val()) || 1;
            $('#productQty').val(qty + 1);
            updateModalTotal();
        });
        
        // Giảm số lượng trong modal
        $(document).on('click', '#decreaseQty', function() {
            let qty = parseInt($('#productQty').val()) || 1;
            if (qty > 1) {
                $('#productQty').val(qty - 1);
                updateModalTotal();
            }
        });
        
        // Thay đổi số lượng trực tiếp
        $(document).on('input', '#productQty', function() {
            let qty = parseInt($(this).val()) || 1;
            if (qty < 1) {
                qty = 1;
                $(this).val(1);
            }
            updateModalTotal();
        });
        
        // Thay đổi addon
        $(document).on('change', '.addon-checkbox', function() {
            updateModalTotal();
        });
        
        // Thêm vào giỏ hàng
        $(document).on('click', '#addToCartBtn', function() {
            const productData = $(this).data();
            const qty = parseInt($('#productQty').val()) || 1;
            const note = $('#orderNote').val() || '';
            
            // Lấy thông tin addon
            let addons = [];
            $('.addon-checkbox:checked').each(function() {
                const addonId = $(this).data('id');
                const addonName = $(this).next('label').text().trim();
                const addonPrice = parseFloat($(this).data('price')) || 0;
                
                addons.push({
                    id: addonId,
                    name: addonName,
                    price: addonPrice
                });
            });
            
            // Tạo sản phẩm mới
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
            
            // Thêm vào giỏ hàng
            self.addItem(newItem);
            
            // Đóng modal
            $('#productModalOverlay').removeClass('active');
            $('#productModal').removeClass('active');
            $('body').removeClass('overflow-hidden');
            
            // Hiển thị thông báo thành công
            self.showToast(`${qty}x ${productData.name} ${window.translations.added_to_cart || 'đã thêm vào giỏ hàng'}`);
            
            // Hiển thị mini cart
            $('#miniCart').addClass('active');
        });
        
        // Tính tổng tiền trong modal
        function updateModalTotal() {
            const basePrice = parseFloat($('#addToCartBtn').data('price')) || 0;
            let qty = parseInt($('#productQty').val()) || 1;
            
            // Tính tổng giá trị addon
            let addonTotal = 0;
            $('.addon-checkbox:checked').each(function() {
                const addonPrice = parseFloat($(this).data('price')) || 0;
                addonTotal += addonPrice;
            });
            
            // Tính tổng cộng
            const total = (basePrice * qty) + addonTotal;
            
            // Cập nhật hiển thị
            $('#modalTotal').text(self.formatPrice(total));
            $('#modalQtySummary').text(`${qty} x ${self.formatPrice(basePrice)}`);
            
            if (addonTotal > 0) {
                $('#modalQtySummary').append(` + ${self.formatPrice(addonTotal)}`);
            }
        }
        
        // Mini Cart
        
        // Tăng số lượng trong mini cart
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
        
        // Giảm số lượng trong mini cart
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
        
        // Xóa sản phẩm khỏi mini cart
        $(document).on('click', '.remove-cart-item', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const index = $(this).data('index');
            const item = self.getItems()[index];
            self.removeItem(index);
            self.showToast(`${item.name} ${window.translations.removed_from_cart || 'đã xóa khỏi giỏ hàng'}`);
        });
        
        // Cart Page
        
        // Tăng số lượng trong trang giỏ hàng
        $(document).on('click', '.cart-item-increase', function(e) {
            e.preventDefault();
            const index = $(this).data('index');
            const cartItems = self.getItems();
            if (index >= 0 && index < cartItems.length) {
                const newQty = cartItems[index].quantity + 1;
                self.updateQuantity(index, newQty);
            }
        });
        
        // Giảm số lượng trong trang giỏ hàng
        $(document).on('click', '.cart-item-decrease', function(e) {
            e.preventDefault();
            const index = $(this).data('index');
            const cartItems = self.getItems();
            if (index >= 0 && index < cartItems.length && cartItems[index].quantity > 1) {
                const newQty = cartItems[index].quantity - 1;
                self.updateQuantity(index, newQty);
            }
        });
        
        // Thay đổi số lượng trực tiếp trong trang giỏ hàng
        $(document).on('change', '.cart-item-qty', function() {
            const index = $(this).data('index');
            const qty = parseInt($(this).val()) || 1;
            self.updateQuantity(index, Math.max(1, qty));
            $(this).val(Math.max(1, qty)); // Đảm bảo giá trị hiển thị là >= 1
        });
        
        // Xóa sản phẩm khỏi trang giỏ hàng
        $(document).on('click', '.cart-item-remove', function(e) {
            e.preventDefault();
            const index = $(this).data('index');
            const item = self.getItems()[index];
            self.removeItem(index);
            self.showToast(`${item.name} ${window.translations.removed_from_cart || 'đã xóa khỏi giỏ hàng'}`);
        });
        
        // Xóa toàn bộ giỏ hàng
        $(document).on('click', '#clearCart', function() {
            if (confirm(window.translations.confirm_clear_cart || 'Bạn có chắc muốn xóa toàn bộ giỏ hàng không?')) {
                self.clearCart();
                self.showToast(window.translations.cart_cleared || 'Giỏ hàng đã được xóa');
            }
        });
        
        // Áp dụng mã giảm giá
        $(document).on('click', '#applyPromo', function() {
            const promoCode = $('#promoCode').val().trim();
            
            if (!promoCode) {
                self.showToast(window.translations.enter_promo_code || 'Vui lòng nhập mã giảm giá', 'error');
                return;
            }
            
            // Giả lập kiểm tra mã giảm giá (thực tế sẽ gửi yêu cầu lên máy chủ)
            if (promoCode.toUpperCase() === 'AISUKI10') {
                // Giả sử giảm 3€
                const discount = 3.00;
                
                // Hiển thị hàng giảm giá
                $('#discountRow').removeClass('hidden');
                $('#discountAmount').text(`-${self.formatPrice(discount)}`).data('value', discount);
                
                // Cập nhật tổng cộng
                self.updateCartSummary();
                
                // Hiển thị thông báo thành công
                self.showToast(window.translations.promo_applied || 'Đã áp dụng mã giảm giá');
            } else {
                // Mã giảm giá không hợp lệ
                self.showToast(window.translations.invalid_promo || 'Mã giảm giá không hợp lệ', 'error');
            }
        });
        
        // Bật/tắt Mini Cart dropdown
        $(document).on('click', '#cartToggle', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#miniCart').toggleClass('active');
            
            // Đóng các dropdown khác
            $('#langDropdown').removeClass('active');
        });
        
        // Đóng dropdown khi click ra ngoài
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#miniCart, #cartToggle').length) {
                $('#miniCart').removeClass('active');
            }
        });
        
        // Ngăn dropdown đóng khi click vào nội dung
        $(document).on('click', '#miniCart', function(e) {
            e.stopPropagation();
        });
    },
    
    // Khởi tạo Cart
    init() {
        this.updateCartUI();
        this.initEventListeners();
        console.log('Cart initialized');
    }
};

// Khởi tạo đối tượng Cart khi trang đã tải xong
$(document).ready(function() {
    // Thiết lập đối tượng translations để sử dụng trong cart
    window.translations = window.translations || {};
    
    // Thiết lập đối tượng routes
    window.routes = window.routes || {
        cart: '/cart',
        menu: '/menu'
    };
    
    // Khởi tạo cart
    Cart.init();
});

// Xuất đối tượng Cart để sử dụng ở các file khác
window.Cart = Cart;