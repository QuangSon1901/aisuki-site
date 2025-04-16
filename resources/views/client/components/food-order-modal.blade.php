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

<style>
    /* Modern styled modal for desktop */
    .product-modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 50;
        transition: opacity 0.3s ease;
        opacity: 0;
        visibility: hidden;
    }
    
    .product-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .product-modal {
        background-color: var(--card-bg, #ffffff);
        z-index: 51;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: 90vh;
        max-width: 1000px;
        width: 90%;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.95);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1);
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        color: var(--text-primary, #222222);
    }
    
    .product-modal.active {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
        visibility: visible;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color, rgba(229, 229, 229, 0.5));
        position: relative;
        background-color: var(--card-bg, #ffffff);
    }
    
    .modal-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--text-primary, #222222);
        padding: 0.5rem 1rem;
        background-color: var(--accent-primary, #e61c23);
        color: white;
        border-radius: 8px;
    }
    
    .modal-close-btn {
        border: none;
        background: transparent;
        font-size: 1.25rem;
        cursor: pointer;
        color: var(--text-secondary, #666666);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .modal-close-btn:hover {
        color: var(--accent-primary, #e61c23);
        background-color: var(--bg-secondary, #f9f9f9);
        transform: rotate(90deg);
    }
    
    .modal-content {
        overflow-y: auto;
        flex: 1;
        scrollbar-width: thin;
        scrollbar-color: var(--border-color, #e5e5e5) var(--bg-secondary, #f9f9f9);
        padding: 0;
        background-color: var(--card-bg, #ffffff);
    }
    
    .modal-content::-webkit-scrollbar {
        width: 8px;
    }
    
    .modal-content::-webkit-scrollbar-track {
        background: var(--bg-secondary, #f9f9f9);
        border-radius: 4px;
    }
    
    .modal-content::-webkit-scrollbar-thumb {
        background-color: var(--border-color, #e5e5e5);
        border-radius: 4px;
    }
    
    /* Enhanced Product Showcase */
    .product-showcase {
        display: flex;
        padding: 0;
        min-height: 350px;
    }
    
    .product-image-container {
        width: 45%;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--bg-secondary, #f9f9f9);
    }
    
    .image-wrapper {
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }
    
    .product-image-container:hover .product-image {
        transform: scale(1.05);
    }
    
    .product-basic-info {
        width: 55%;
        padding: 2rem;
        display: flex;
        flex-direction: column;
    }
    
    .product-name-price {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }
    
    .product-name {
        font-weight: 700;
        font-size: 1.8rem;
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
        background-color: rgba(230, 28, 35, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 10px;
    }
    
    .product-price {
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--accent-primary, #e61c23);
    }
    
    .product-description-container {
        background-color: var(--bg-secondary, #f9f9f9);
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        flex-grow: 0;
    }
    
    .product-description {
        color: var(--text-secondary, #666666);
        font-size: 1rem;
        line-height: 1.6;
        margin: 0;
    }
    
    .product-options-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    
    .option-section {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .option-title {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.75rem;
        color: var(--text-primary, #222222);
        position: relative;
        padding-left: 0.75rem;
    }
    
    .option-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 16px;
        background-color: var(--accent-primary, #e61c23);
        border-radius: 2px;
    }
    
    .quantity-section {
        background-color: var(--bg-secondary, #f9f9f9);
        padding: 1rem;
        border-radius: 10px;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .quantity-btn {
        width: 40px;
        height: 40px;
        background-color: white;
        border: 1px solid var(--border-color, #e5e7eb);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-primary, #222222);
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .quantity-btn:hover {
        background-color: var(--accent-primary, #e61c23);
        color: white;
        border-color: var(--accent-primary, #e61c23);
    }
    
    .quantity-input {
        width: 60px;
        height: 40px;
        border: 1px solid var(--border-color, #e5e7eb);
        margin: 0 12px;
        text-align: center;
        border-radius: 8px;
        -moz-appearance: textfield;
        background-color: white;
        color: var(--text-primary, #222222);
        font-size: 1.125rem;
        font-weight: 600;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    .note-section {
        background-color: var(--bg-secondary, #f9f9f9);
        padding: 1rem;
        border-radius: 10px;
    }
    
    .order-note {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-color, #e5e7eb);
        border-radius: 8px;
        font-size: 0.9rem;
        line-height: 1.5;
        resize: vertical;
        min-height: 40px;
        transition: all 0.2s;
        background-color: white;
        color: var(--text-primary, #222222);
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .order-note:focus {
        outline: none;
        border-color: var(--accent-primary, #e61c23);
        box-shadow: 0 0 0 2px rgba(230, 28, 35, 0.1);
    }
    
    /* Enhanced Addon Section */
    .addon-container {
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--border-color, rgba(229, 229, 229, 0.5));
    }
    
    .addon-container-title {
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 1.25rem;
        color: var(--text-primary, #222222);
        position: relative;
        display: inline-block;
    }
    
    .addon-container-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -5px;
        width: 100%;
        height: 3px;
        background-color: var(--accent-primary, #e61c23);
        border-radius: 1.5px;
    }
    
    .addons-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    
    .addon-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        border: 1px solid var(--border-color, #e5e7eb);
        border-radius: 10px;
        transition: all 0.2s;
        background-color: var(--bg-primary, white);
    }
    
    .addon-item:hover {
        border-color: var(--accent-primary, #e61c23);
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    
    .addon-checkbox-container {
        display: flex;
        align-items: center;
    }
    
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
        padding-left: 2.2rem;
        position: relative;
        font-size: 0.95rem;
        font-weight: 500;
    }
    
    .addon-label::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 1.5rem;
        height: 1.5rem;
        border: 2px solid var(--border-color, #e5e7eb);
        border-radius: 5px;
        background-color: var(--bg-primary, white);
        transition: all 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .custom-checkbox:checked + .addon-label::before {
        background-color: var(--accent-primary, #e61c23);
        border-color: var(--accent-primary, #e61c23);
    }
    
    .custom-checkbox:checked + .addon-label::after {
        content: '✓';
        position: absolute;
        left: 0.45rem;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 0.8rem;
        font-weight: bold;
    }
    
    .addon-price {
        font-weight: 600;
        color: var(--accent-primary, #e61c23);
        font-size: 0.95rem;
        padding: 0.35rem 0.75rem;
        background-color: rgba(230, 28, 35, 0.08);
        border-radius: 8px;
    }
    
    /* Loading and empty states */
    .loading-addons {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        text-align: center;
        color: var(--text-secondary, #666666);
    }
    
    .loader-spinner {
        font-size: 1.5rem;
        margin-bottom: 0.75rem;
        color: var(--accent-primary, #e61c23);
    }
    
    .empty-addons-message {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        text-align: center;
        color: var(--text-secondary, #666666);
        background-color: var(--bg-secondary, #f9f9f9);
        border-radius: 10px;
    }
    
    .empty-addons-message i {
        font-size: 2rem;
        margin-bottom: 1rem;
        opacity: 0.7;
    }
    
    /* Enhanced Footer */
    .modal-footer {
        border-top: 1px solid var(--border-color, rgba(229, 229, 229, 0.5));
        padding: 1.5rem 2rem;
        background-color: var(--card-bg, white);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .order-summary {
        display: flex;
        flex-direction: column;
    }
    
    .total-amount {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
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
        font-size: 0.95rem;
        color: var(--text-secondary, #666666);
    }
    
    .add-to-cart-btn {
        background-color: var(--accent-primary, #e61c23);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 12px rgba(230, 28, 35, 0.25);
    }
    
    .add-to-cart-btn:hover {
        background-color: var(--accent-secondary, #c41017);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(230, 28, 35, 0.35);
    }
    
    .add-to-cart-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(230, 28, 35, 0.2);
    }
    
    /* Mobile styles */
    @media (max-width: 767px) {
        .product-modal {
            top: auto;
            left: 0;
            right: 0;
            bottom: 0;
            transform: translateY(100%);
            width: 100%;
            max-width: 100%;
            border-radius: 20px 20px 0 0;
            max-height: 90vh;
        }
        
        .product-modal.active {
            transform: translateY(0);
        }
        
        .product-showcase {
            flex-direction: column;
            min-height: auto;
        }
        
        .product-image-container,
        .product-basic-info {
            width: 100%;
        }
        
        .product-basic-info {
            padding: 1.5rem;
        }
        
        .product-options-wrapper {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .addon-container {
            padding: 1.5rem;
        }
        
        .addons-grid {
            grid-template-columns: 1fr;
        }
        
        .modal-footer {
            flex-direction: column;
            gap: 1rem;
            padding: 1.25rem;
        }
        
        .add-to-cart-btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    /* Dark Mode Support */
    [data-theme="dark"] .product-modal,
    [data-theme="dark"] .modal-header,
    [data-theme="dark"] .modal-content,
    [data-theme="dark"] .modal-footer {
        background-color: var(--card-bg, #2a2a2a);
    }
    
    [data-theme="dark"] .modal-title {
        background-color: var(--accent-primary, #e61c23);
    }
    
    [data-theme="dark"] .product-image-container {
        background-color: rgba(255, 255, 255, 0.05);
    }
    
    [data-theme="dark"] .product-description-container,
    [data-theme="dark"] .quantity-section,
    [data-theme="dark"] .note-section,
    [data-theme="dark"] .empty-addons-message {
        background-color: var(--bg-secondary, #222);
    }
    
    [data-theme="dark"] .product-price-container {
        background-color: rgba(230, 28, 35, 0.15);
    }
    
    [data-theme="dark"] .addon-price {
        background-color: rgba(230, 28, 35, 0.15);
    }
    
    [data-theme="dark"] .addon-item {
        background-color: var(--bg-primary, #333);
        border-color: var(--border-color, #444);
    }
    
    [data-theme="dark"] .quantity-btn {
        background-color: var(--bg-primary, #333);
        border-color: var(--border-color, #444);
        color: var(--text-primary, white);
    }
    
    [data-theme="dark"] .quantity-input,
    [data-theme="dark"] .order-note {
        background-color: var(--bg-primary, #333);
        border-color: var(--border-color, #444);
        color: var(--text-primary, white);
    }
    
    [data-theme="dark"] .addon-label::before {
        background-color: var(--bg-primary, #333);
        border-color: var(--border-color, #444);
    }
</style>