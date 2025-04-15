<!-- Product Detail Modal -->
<div class="product-modal-overlay" id="productModalOverlay"></div>
<div class="product-modal" id="productModal">
    <!-- Modal Header -->
    <div class="modal-header">
        <h3 class="modal-title" id="modalProductCode">Order Details</h3>
        <button id="closeProductModal" class="modal-close-btn">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Modal Content -->
    <div class="modal-content">
        <!-- Product Images and Basic Info -->
        <div class="product-showcase">
            <div class="product-image-container">
                <img id="modalProductImage" src="" alt="Product" class="product-image">
            </div>
            <div class="product-basic-info">
                <div class="product-name-price">
                    <div>
                        <h4 id="modalProductName" class="product-name">Product Name</h4>
                    </div>
                    <div class="product-price-container">
                        <div id="modalProductPrice" class="product-price">0.00 €</div>
                    </div>
                </div>
                <p id="modalProductDesc" class="product-description mt-2"></p>
            </div>
        </div>
        
        <!-- Product Options -->
        <div class="product-options">
            <!-- Quantity Section -->
            <div class="option-section">
                <h5 class="option-title">{{ trans_db('sections', 'quantity', false) ?: 'Quantity' }}</h5>
                <div class="quantity-control">
                    <button class="quantity-btn" id="decreaseQty">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" value="1" min="1" class="quantity-input" id="productQty">
                    <button class="quantity-btn" id="increaseQty">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            
            <!-- Add-ons Section -->
            <div class="option-section" id="addonSection">
                <h5 class="option-title">{{ trans_db('sections', 'addons', false) ?: 'Add-ons' }}</h5>
                <div class="addons-grid">
                    @foreach($addons as $addon)
                    <div class="addon-item">
                        <div class="addon-checkbox-container">
                            <input type="checkbox" id="addon{{ $addon->id }}" class="custom-checkbox addon-checkbox" data-price="{{ $addon->price }}">
                            <label for="addon{{ $addon->id }}" class="addon-label">{{ $addon->name }}</label>
                        </div>
                        <span class="addon-price">{{ number_format($addon->price, 2, ',', '.') }} €</span>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Special Notes -->
            <div class="option-section">
                <h5 class="option-title">{{ trans_db('sections', 'special_note', false) ?: 'Special Note' }}</h5>
                <textarea id="orderNote" rows="2" placeholder="Example: Less spicy, no onions..." class="order-note"></textarea>
            </div>
        </div>
    </div>
    
    <!-- Modal Footer -->
    <div class="modal-footer">
        <div class="order-summary">
            <div class="total-amount">
                <span class="total-label">{{ trans_db('sections', 'total', false) ?: 'Total' }}:</span>
                <span id="modalTotal" class="total-value">0.00 €</span>
            </div>
            <div class="order-calculation">
                <span id="modalQtySummary">1 x 0.00 €</span>
            </div>
        </div>
        <button class="add-to-cart-btn" id="addToCartBtn">
            {{ trans_db('sections', 'add_to_cart', false) ?: 'Add to Cart' }}
        </button>
    </div>
</div>

<style>
    /* Product modal overlay */
    .product-modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 50;
        transition: opacity 0.3s;
        opacity: 0;
        visibility: hidden;
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
    }
    .product-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Base product modal styles */
    .product-modal {
        background-color: var(--card-bg, #ffffff);
        z-index: 51;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 4rem);
        max-width: 900px;
        width: 100%;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.95);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        color: var(--text-primary, #222222);
    }
    
    .product-modal.active {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
        visibility: visible;
    }

    /* Modal header */
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.6rem 1.25rem;
        border-bottom: 1px solid var(--border-color, #e5e5e5);
        position: relative;
        background-color: var(--card-bg, #ffffff);
    }

    .modal-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--text-primary, #222222);
    }

    .modal-close-btn {
        border: none;
        background: transparent;
        font-size: 1.25rem;
        cursor: pointer;
        color: var(--text-secondary, #666666);
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .modal-close-btn:hover {
        color: var(--accent-primary, #e61c23);
        background-color: var(--bg-secondary, #f9f9f9);
    }

    /* Modal content - scrollable area */
    .modal-content {
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        flex: 1;
        scrollbar-width: thin;
        scrollbar-color: var(--border-color, #e5e5e5) var(--bg-secondary, #f9f9f9);
        background-color: var(--card-bg, #ffffff);
    }

    .modal-content::-webkit-scrollbar {
        width: 6px;
    }

    .modal-content::-webkit-scrollbar-track {
        background: var(--bg-secondary, #f9f9f9);
    }

    .modal-content::-webkit-scrollbar-thumb {
        background-color: var(--border-color, #e5e5e5);
        border-radius: 6px;
    }

    /* Product showcase area */
    .product-showcase {
        display: flex;
        flex-direction: column;
    }

    .product-image-container {
        width: 100%;
        position: relative;
    }

    .product-image {
        width: 100%;
        height: auto;
        max-height: 320px;
        object-fit: cover;
        display: block;
    }

    .product-basic-info {
        padding: 1.5rem;
    }

    .product-name-price {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .product-name {
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--text-primary, #222222);
        margin-bottom: 0.5rem;
        word-break: normal;
        overflow-wrap: anywhere;
        line-height: 1.3;
    }

    .product-price-container {
        text-align: right;
        flex-shrink: 0;
        margin-left: 1rem;
    }

    .product-price {
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--accent-primary, #e61c23);
    }

    .product-description {
        color: var(--text-secondary, #666666);
        font-size: 1rem;
        line-height: 1.6;
    }

    /* Product options */
    .product-options {
        padding: 0 1.5rem;
    }

    .option-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--border-color, #e5e5e5);
    }

    .option-title {
        font-weight: 600;
        font-size: 1.125rem;
        margin-bottom: 1rem;
        color: var(--text-primary, #222222);
    }

    /* Quantity controls */
    .quantity-control {
        display: flex;
        align-items: center;
    }

    .quantity-btn {
        width: 40px;
        height: 40px;
        background-color: var(--button-bg-secondary, #f3f4f6);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--button-text-secondary, #4b5563);
        cursor: pointer;
        transition: all 0.2s;
    }

    .quantity-btn:hover {
        background-color: var(--accent-primary, #e61c23);
        color: white;
    }

    .quantity-input {
        width: 60px;
        height: 40px;
        border: 1px solid var(--border-color, #e5e7eb);
        margin: 0 12px;
        text-align: center;
        border-radius: 8px;
        -moz-appearance: textfield;
        background-color: var(--bg-primary, #ffffff);
        color: var(--text-primary, #222222);
        font-size: 1.125rem;
    }

    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Add-ons section */
    .addons-grid {
        display: grid;
        gap: 0.75rem;
        grid-template-columns: repeat(1, 1fr);
    }

    .addon-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color, #e5e5e5);
    }

    .addon-checkbox-container {
        display: flex;
        align-items: center;
    }

    /* Custom checkbox styling */
    .custom-checkbox {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .addon-label {
        cursor: pointer;
        color: var(--text-primary, #222222);
        padding-left: 2rem;
        position: relative;
        font-size: 1rem;
    }

    .addon-label::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid var(--border-color, #e5e7eb);
        border-radius: 4px;
        background-color: var(--bg-primary, #ffffff);
        transition: all 0.2s;
    }

    .custom-checkbox:checked + .addon-label::before {
        background-color: var(--accent-primary, #e61c23);
        border-color: var(--accent-primary, #e61c23);
    }

    .custom-checkbox:checked + .addon-label::after {
        content: '✓';
        position: absolute;
        left: 0.35rem;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 0.75rem;
    }

    .addon-price {
        font-weight: 600;
        color: var(--accent-primary, #e61c23);
        font-size: 1rem;
    }

    /* Special notes textarea */
    .order-note {
        width: 100%;
        padding: 1rem;
        border: 1px solid var(--border-color, #e5e7eb);
        border-radius: 0.5rem;
        font-size: 1rem;
        line-height: 1.6;
        resize: vertical;
        min-height: 100px;
        transition: border-color 0.2s;
        background-color: var(--bg-primary, #ffffff);
        color: var(--text-primary, #222222);
    }

    .order-note:focus {
        outline: none;
        border-color: var(--accent-primary, #e61c23);
        box-shadow: 0 0 0 2px rgba(230, 28, 35, 0.1);
    }

    /* Modal footer */
    .modal-footer {
        border-top: 1px solid var(--border-color, #e5e5e5);
        padding: 1.25rem;
        background-color: var(--card-bg, #ffffff);
    }

    .order-summary {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .total-amount {
        display: flex;
        align-items: center;
    }

    .total-label {
        color: var(--text-secondary, #666666);
        margin-right: 0.75rem;
        font-size: 1.125rem;
    }

    .total-value {
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--accent-primary, #e61c23);
    }

    .order-calculation {
        font-size: 1rem;
        color: var(--text-secondary, #666666);
    }

    .add-to-cart-btn {
        width: 100%;
        background-color: var(--accent-primary, #e61c23);
        color: white;
        border: none;
        border-radius: 9999px;
        padding: 1rem 1.5rem;
        font-weight: 700;
        font-size: 1.125rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .add-to-cart-btn:hover {
        background-color: var(--accent-secondary, #c41017);
    }

    /* Responsive styles */
    @media (min-width: 768px) {
        /* Two-column layout for product showcase */
        .product-showcase {
            flex-direction: row;
        }
        
        .product-image-container {
            width: 45%;
        }
        
        .product-image {
            height: 100%;
            max-height: 400px;
            object-fit: cover;
        }
        
        .product-basic-info {
            width: 55%;
            padding: 2rem;
        }
        
        /* Two-column add-ons grid */
        .addons-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 767px) {
        /* Mobile modal positioning */
        .product-modal {
            top: auto;
            left: 0;
            right: 0;
            bottom: 0;
            transform: translateY(100%);
            border-radius: 16px 16px 0 0;
            max-height: 90vh;
        }
        
        .product-modal.active {
            transform: translateY(0);
        }
        
        /* Add drag handle for mobile */
        .modal-header::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 48px;
            height: 5px;
            background-color: var(--border-color, #e5e5e5);
            border-radius: 2.5px;
        }
        
        /* Adjust image height for mobile */
        .product-image {
            max-height: 240px;
        }
        
        .product-price {
            font-size: 1.25rem;
        }
        
        .product-name {
            font-size: 1.25rem;
        }
        
        .option-title {
            font-size: 1rem;
        }
        
        .addon-label {
            font-size: 0.9375rem;
        }
        
        .add-to-cart-btn {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
        }
    }

    /* Dark mode support */
    [data-theme="dark"] .product-modal,
    [data-theme="dark"] .modal-header,
    [data-theme="dark"] .modal-content,
    [data-theme="dark"] .modal-footer {
        background-color: var(--card-bg, #2a2a2a);
    }

    [data-theme="dark"] .modal-title,
    [data-theme="dark"] .product-name {
        color: var(--text-primary, #ffffff);
    }

    [data-theme="dark"] .product-description,
    [data-theme="dark"] .addon-label {
        color: var(--text-secondary, #cccccc);
    }

    [data-theme="dark"] .order-note,
    [data-theme="dark"] .quantity-input {
        background-color: var(--bg-secondary, #1e1e1e);
        border-color: var(--border-color, #333333);
        color: var(--text-primary, #ffffff);
    }

    [data-theme="dark"] .addon-label::before {
        border-color: var(--border-color, #444444);
        background-color: var(--bg-secondary, #1e1e1e);
    }

    [data-theme="dark"] .quantity-btn {
        background-color: var(--button-bg-secondary, #333333);
        color: var(--button-text-secondary, #cccccc);
    }

    [data-theme="dark"] .modal-close-btn {
        color: var(--text-secondary, #aaaaaa);
    }

    [data-theme="dark"] .modal-close-btn:hover {
        background-color: var(--bg-secondary, #333333);
    }
</style>

<script>
    $(document).ready(function() {
        // Product Modal Open
        $('.order-btn').click(function() {
            const productId = $(this).data('id');
            const productCode = $(this).data('code');
            const productName = $(this).data('name');
            const productPrice = $(this).data('price');
            const productImage = $(this).data('image');
            const productDesc = $(this).data('description');
            
            // Reset modal state
            $('#productQty').val(1);
            $('.addon-checkbox').prop('checked', false);
            $('#orderNote').val('');
            
            // Set product details in modal
            $('#modalProductCode').text(productCode);
            $('#modalProductName').text(productName);
            $('#modalProductImage').attr('src', productImage);
            $('#modalProductDesc').text(productDesc);
            $('#modalProductPrice').text(formatPrice(productPrice));
            $('#modalTotal').text(formatPrice(productPrice));
            $('#modalQtySummary').text(`1 x ${formatPrice(productPrice)}`);
            
            // Store data for later use
            $('#addToCartBtn').data({
                id: productId,
                code: productCode,
                name: productName,
                price: productPrice,
                image: productImage
            });
            
            // Open modal
            $('#productModalOverlay').addClass('active');
            $('#productModal').addClass('active');
            $('body').addClass('overflow-hidden');
            
            // Update modal total on load
            updateModalTotal();
        });

        // Close Product Modal
        $('#closeProductModal, #productModalOverlay').click(function() {
            $('#productModalOverlay').removeClass('active');
            $('#productModal').removeClass('active');
            $('body').removeClass('overflow-hidden');
        });
        
        // Stop propagation on modal click
        $('#productModal').click(function(e) {
            e.stopPropagation();
        });

        // Quantity controls
        $('#increaseQty').click(function() {
            let qty = parseInt($('#productQty').val());
            $('#productQty').val(qty + 1);
            updateModalTotal();
        });
        
        $('#decreaseQty').click(function() {
            let qty = parseInt($('#productQty').val());
            if (qty > 1) {
                $('#productQty').val(qty - 1);
                updateModalTotal();
            }
        });
        
        // Quantity manually changed
        $('#productQty').on('input', function() {
            updateModalTotal();
        });
        
        // Add-on checkboxes
        $('.addon-checkbox').change(function() {
            updateModalTotal();
        });
        
        // Update modal total
        function updateModalTotal() {
            const basePrice = $('#addToCartBtn').data('price');
            let qty = parseInt($('#productQty').val());
            
            // Ensure quantity is at least 1
            qty = Math.max(1, qty);
            
            // Calculate add-ons total
            let addonTotal = 0;
            $('.addon-checkbox:checked').each(function() {
                const addonPrice = parseFloat($(this).data('price'));
                addonTotal += addonPrice;
            });
            
            // Calculate total
            const total = (basePrice * qty) + addonTotal;
            
            // Update display
            $('#modalTotal').text(formatPrice(total));
            $('#modalQtySummary').text(`${qty} x ${formatPrice(basePrice)}`);
            if (addonTotal > 0) {
                $('#modalQtySummary').append(` + ${formatPrice(addonTotal)}`);
            }
        }
        
        // Format price
        function formatPrice(price) {
            return new Intl.NumberFormat('de-DE', { 
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2 
            }).format(price) + ' €';
        }
        
        // Add to cart
        $('#addToCartBtn').click(function() {
            const productData = $(this).data();
            const qty = parseInt($('#productQty').val());
            const note = $('#orderNote').val();
            
            // Lấy thông tin add-ons
            let addons = [];
            $('.addon-checkbox:checked').each(function() {
                const addonLabel = $(this).next('label').text();
                const addonPrice = parseFloat($(this).data('price'));
                
                addons.push({
                    name: addonLabel,
                    price: addonPrice
                });
            });
            
            // Tính tổng giá tiền
            let total = productData.price * qty;
            addons.forEach(addon => {
                total += addon.price;
            });
            
            // Gửi dữ liệu đến server hoặc lưu vào localStorage
            // Đây là ví dụ đơn giản lưu vào localStorage
            const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            
            // Tạo đối tượng cart item mới
            const newItem = {
                id: productData.id,
                code: productData.code,
                name: productData.name,
                price: productData.price,
                image: productData.image,
                quantity: qty,
                addons: addons,
                note: note,
                total: total,
                dateAdded: new Date().toISOString()
            };
            
            // Thêm vào giỏ hàng
            cartItems.push(newItem);
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            
            // Cập nhật số lượng hiển thị trên icon giỏ hàng
            updateCartCount();
            
            // Đóng modal
            $('#productModalOverlay').removeClass('active');
            $('#productModal').removeClass('active');
            $('body').removeClass('overflow-hidden');
            
            // Hiển thị thông báo
            alert(`Added ${qty}x ${productData.name} to your cart.`);
        });
        
        // Hàm cập nhật số lượng hiển thị giỏ hàng
        function updateCartCount() {
            const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            let count = 0;
            
            cartItems.forEach(item => {
                count += item.quantity;
            });
            
            // Cập nhật số hiển thị trên icon giỏ hàng
            $('.cart-count').text(count);
        }
        
        // Khởi tạo số lượng hiển thị giỏ hàng khi load trang
        updateCartCount();
        
        // Mobile swipe to close modal
        if ('ontouchstart' in window) {
            let touchStartY = 0;
            let touchEndY = 0;
            
            document.getElementById('productModal').addEventListener('touchstart', function(e) {
                touchStartY = e.changedTouches[0].screenY;
            }, { passive: true });
            
            document.getElementById('productModal').addEventListener('touchend', function(e) {
                touchEndY = e.changedTouches[0].screenY;
                
                // Calculate swipe distance
                let swipeDistance = touchEndY - touchStartY;
                
                // If swiped down more than 100px, close modal
                if (swipeDistance > 100) {
                    $('#productModalOverlay').removeClass('active');
                    $('#productModal').removeClass('active');
                    $('body').removeClass('overflow-hidden');
                }
            }, { passive: true });
        }
    });
</script>