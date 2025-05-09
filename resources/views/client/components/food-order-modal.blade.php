<!-- Modal đặt món - Enhanced Desktop Design -->
<div class="product-modal-overlay" id="productModalOverlay"></div>
<div class="product-modal" id="productModal">
    <!-- Modal Header -->
    <div class="modal-header">
        <h3 class="modal-title" id="modalProductCode"></h3>
        <button id="closeProductModal" class="modal-close-btn">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Modal Content -->
    <div class="modal-content">
        <!-- Enhanced Product Showcase -->
        <div class="product-showcase">
            <div class="product-image-container">
                <div class="image-wrapper">
                    <img id="modalProductImage" src="" alt="Product" class="product-image">
                </div>
            </div>
            <div class="product-basic-info">
                <div class="product-name-price">
                    <div>
                        <h4 id="modalProductName" class="product-name"></h4>
                    </div>
                    <div class="product-price-container">
                        <div id="modalProductPrice" class="product-price"></div>
                    </div>
                </div>
                <div class="product-description-container">
                    <p id="modalProductDesc" class="product-description"></p>
                </div>
                
                <!-- Product options moved inside basic info for better layout -->
                <div class="product-options-wrapper">
                    <!-- Quantity Selection - Enhanced -->
                    <div class="option-section quantity-section">
                        <h5 class="option-title">{{ trans_db('sections', 'quantity', false) ?: 'Số lượng' }}</h5>
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
                
                    <!-- Special Note -->
                    <div class="option-section note-section">
                        <h5 class="option-title">{{ trans_db('sections', 'special_note', false) ?: 'Ghi chú' }}</h5>
                        <textarea id="orderNote" rows="2" placeholder="{{ trans_db('sections', 'special_note_placeholder', false) ?: 'VD: Ít cay, không hành...' }}" class="order-note"></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Addon Section - Enhanced -->
        <div class="addon-container" id="addonSection">
            <h5 class="addon-container-title">{{ trans_db('sections', 'addons', false) ?: 'Món ăn kèm' }}</h5>
            
            <div class="addons-grid" id="menuItemAddons">
                <!-- Loading state -->
                <div class="loading-addons">
                    <div class="loader-spinner"><i class="fas fa-spinner fa-spin"></i></div>
                    <p>{{ trans_db('sections', 'loading_addons', false) ?: 'Đang tải món ăn kèm...' }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Footer - Enhanced -->
    <div class="modal-footer">
        <div class="order-summary">
            <div class="total-amount">
                <span class="total-label">{{ trans_db('sections', 'total', false) ?: 'Tổng cộng' }}:</span>
                <span id="modalTotal" class="total-value"></span>
            </div>
            <div class="order-calculation">
                <span id="modalQtySummary"></span>
            </div>
        </div>
        <button class="add-to-cart-btn" id="addToCartBtn">
            <i class="fas fa-cart-plus mr-2"></i> {{ trans_db('sections', 'add_to_cart', false) ?: 'Thêm vào giỏ hàng' }}
        </button>
    </div>
</div>