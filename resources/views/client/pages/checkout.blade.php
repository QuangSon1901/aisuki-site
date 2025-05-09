@extends('client.layouts.app')

@push('styles')
    <style>
        #submitOrderBtn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .loading-state .fa-spin {
            animation: fa-spin 1s infinite linear;
        }
        
        @keyframes fa-spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
@section('content')
    <!-- Tiêu đề trang -->
    <section class="py-6 bg-theme-primary border-b border-theme">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <h1 class="text-2xl md:text-3xl font-bold text-theme-primary text-center">{{ trans_db('sections', 'checkout_title', false) ?: 'Checkout' }}</h1>
        </div>
    </section>

    <!-- Nội dung checkout -->
    <section class="py-8 md:py-12" id="checkoutPage">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cột trái - Form thông tin đặt hàng -->
                <div class="w-full lg:w-2/3">
                    <form id="checkoutForm" method="POST" action="{{ route('checkout.submit', ['locale' => app()->getLocale()]) }}">
                        @csrf
                        <!-- Thông tin khách hàng -->
                        <div class="card rounded-lg shadow-md overflow-hidden mb-6">
                            <div class="p-4 bg-theme-secondary border-b border-theme">
                                <h2 class="text-lg font-semibold text-theme-primary">
                                    <i class="fas fa-user-circle mr-2"></i>
                                    {{ trans_db('sections', 'customer_information', false) ?: 'Customer Information' }}
                                </h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Họ tên -->
                                    <div>
                                        <label for="fullName" class="block text-theme-primary font-medium mb-1">
                                            {{ trans_db('sections', 'full_name', false) ?: 'Full Name' }} <span class="text-aisuki-red">*</span>
                                        </label>
                                        <input type="text" id="fullName" name="full_name" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red">
                                    </div>
                                    
                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-theme-primary font-medium mb-1">
                                            {{ trans_db('sections', 'email', false) ?: 'Email' }} <span class="text-aisuki-red">*</span>
                                        </label>
                                        <input type="email" id="email" name="email" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red">
                                    </div>
                                </div>
                                
                                <!-- Số điện thoại -->
                                <div>
                                    <label for="phone" class="block text-theme-primary font-medium mb-1">
                                        {{ trans_db('sections', 'phone', false) ?: 'Phone' }} <span class="text-aisuki-red">*</span>
                                    </label>
                                    <input type="tel" id="phone" name="phone" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red">
                                </div>
                            </div>
                        </div>

                        <!-- Phương thức giao hàng -->
                        <div class="card rounded-lg shadow-md overflow-hidden mb-6">
                            <div class="p-4 bg-theme-secondary border-b border-theme">
                                <h2 class="text-lg font-semibold text-theme-primary">
                                    <i class="fas fa-truck mr-2"></i>
                                    {{ trans_db('sections', 'delivery_method', false) ?: 'Delivery Method' }}
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <!-- Lựa chọn phương thức -->
                                    <div class="flex flex-col md:flex-row gap-4">
                                        @if(setting('enable_delivery', 1))
                                        <div class="flex-1">
                                            <label for="deliveryMethod1" class="flex items-center p-4 border border-theme rounded-md cursor-pointer delivery-option transition-colors hover:border-aisuki-red" data-method="delivery">
                                                <input type="radio" id="deliveryMethod1" name="delivery_method" value="delivery" class="hidden" checked>
                                                <div class="w-6 h-6 rounded-full border-2 border-theme-secondary flex items-center justify-center mr-3 delivery-radio">
                                                    <div class="w-3 h-3 rounded-full bg-aisuki-red hidden delivery-radio-inner"></div>
                                                </div>
                                                <div style="flex: 1;">
                                                    <span class="font-medium text-theme-primary">{{ trans_db('sections', 'delivery', false) ?: 'Delivery' }}</span>
                                                    <p class="text-sm text-theme-secondary">{{ trans_db('sections', 'delivery_to_address', false) ?: 'Delivery to your address' }}</p>
                                                </div>
                                            </label>
                                        </div>
                                        @endif
                                        
                                        @if(setting('enable_pickup', 1))
                                        <div class="flex-1">
                                            <label for="deliveryMethod2" class="flex items-center p-4 border border-theme rounded-md cursor-pointer delivery-option transition-colors hover:border-aisuki-red" data-method="pickup">
                                                <input type="radio" id="deliveryMethod2" name="delivery_method" value="pickup" class="hidden" {{ !setting('enable_delivery', 1) ? 'checked' : '' }}>
                                                <div class="w-6 h-6 rounded-full border-2 border-theme-secondary flex items-center justify-center mr-3 delivery-radio">
                                                    <div class="w-3 h-3 rounded-full bg-aisuki-red hidden delivery-radio-inner"></div>
                                                </div>
                                                <div style="flex: 1;">
                                                    <span class="font-medium text-theme-primary">{{ trans_db('sections', 'pickup', false) ?: 'Pickup' }}</span>
                                                    <p class="text-sm text-theme-secondary">{{ trans_db('sections', 'pickup_from_restaurant', false) ?: 'Pickup from our restaurant' }}</p>
                                                </div>
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Form địa chỉ giao hàng -->
                                    <div id="deliveryAddressForm" class="space-y-4 {{ !setting('enable_delivery', 1) ? 'hidden' : '' }}">
                                        <div class="grid grid-cols-2 gap-4">
                                            <!-- Đường -->
                                            <div>
                                                <label for="street" class="block text-theme-primary font-medium mb-1">
                                                    {{ trans_db('sections', 'street', false) ?: 'Street' }} <span class="text-aisuki-red">*</span>
                                                </label>
                                                <input type="text" id="street" name="street" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red delivery-required">
                                            </div>
                                            
                                            <!-- Số nhà -->
                                            <div>
                                                <label for="houseNumber" class="block text-theme-primary font-medium mb-1">
                                                    {{ trans_db('sections', 'house_number', false) ?: 'House Number' }} <span class="text-aisuki-red">*</span>
                                                </label>
                                                <input type="text" id="houseNumber" name="house_number" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red delivery-required">
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-4">
                                            <!-- Thành phố -->
                                            <div>
                                                <label for="city" class="block text-theme-primary font-medium mb-1">
                                                    {{ trans_db('sections', 'city', false) ?: 'City' }} <span class="text-aisuki-red">*</span>
                                                </label>
                                                <input type="text" id="city" name="city" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red delivery-required">
                                            </div>
                                            
                                            <!-- Mã bưu điện -->
                                            <div>
                                                <label for="postalCode" class="block text-theme-primary font-medium mb-1">
                                                    {{ trans_db('sections', 'postal_code', false) ?: 'Postal Code' }} <span class="text-aisuki-red">*</span>
                                                </label>
                                                <input type="text" id="postalCode" name="postal_code" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red delivery-required">
                                            </div>
                                        </div>
                                        
                                        <!-- Thời gian giao hàng -->
                                        <div>
                                            <label for="deliveryTime" class="block text-theme-primary font-medium mb-1">
                                                {{ trans_db('sections', 'delivery_time', false) ?: 'Delivery Time' }}
                                            </label>
                                            <select id="deliveryTime" name="delivery_time" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red">
                                                <option value="asap">{{ trans_db('sections', 'asap', false) ?: 'As soon as possible' }}</option>
                                                <option value="today_11:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 11:00</option>
                                                <option value="today_11:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 11:30</option>
                                                <option value="today_12:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 12:00</option>
                                                <option value="today_12:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 12:30</option>
                                                <option value="today_13:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 13:00</option>
                                                <option value="today_13:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 13:30</option>
                                                <option value="today_14:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 14:00</option>
                                                <option value="today_14:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 14:30</option>
                                                <option value="today_15:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 15:00</option>
                                                <option value="today_15:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 15:30</option>
                                                <option value="today_16:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 16:00</option>
                                                <option value="today_16:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 16:30</option>
                                                <option value="today_17:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 17:00</option>
                                                <option value="today_17:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 17:30</option>
                                                <option value="today_18:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 18:00</option>
                                                <option value="today_18:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 18:30</option>
                                                <option value="today_19:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 19:00</option>
                                                <option value="today_19:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 19:30</option>
                                                <option value="today_20:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 20:00</option>
                                                <option value="today_20:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 20:30</option>
                                                <option value="today_21:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 21:00</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Thông tin nhận tại cửa hàng -->
                                    <div id="pickupInformation" class="space-y-4 {{ setting('enable_delivery', 1) ? 'hidden' : '' }}">
                                        <div class="bg-theme-secondary p-4 rounded-md">
                                            <h3 class="font-medium text-theme-primary mb-2">
                                                {{ trans_db('sections', 'store_pickup_address', false) ?: 'Store Pickup Address' }}:
                                            </h3>
                                            <p class="text-theme-secondary">{{ setting('store_address', setting('address')) }}</p>
                                            
                                            <h3 class="font-medium text-theme-primary mt-4 mb-2">
                                                {{ trans_db('sections', 'pickup_instructions', false) ?: 'Pickup Instructions' }}:
                                            </h3>
                                            <p class="text-theme-secondary">{{ trans_db('sections', 'pickup_instruction_text', false) ?: 'Please come to the restaurant and give your name at the counter.' }}</p>
                                        </div>
                                        
                                        <!-- Thời gian nhận hàng -->
                                        <div>
                                            <label for="pickupTime" class="block text-theme-primary font-medium mb-1">
                                                {{ trans_db('sections', 'pickup_time', false) ?: 'Pickup Time' }}
                                            </label>
                                            <select id="pickupTime" name="pickup_time" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red">
                                                <option value="asap">{{ trans_db('sections', 'asap', false) ?: 'As soon as possible' }}</option>
                                                <option value="today_11:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 11:00</option>
                                                <option value="today_11:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 11:30</option>
                                                <option value="today_12:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 12:00</option>
                                                <option value="today_12:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 12:30</option>
                                                <option value="today_13:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 13:00</option>
                                                <option value="today_13:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 13:30</option>
                                                <option value="today_14:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 14:00</option>
                                                <option value="today_14:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 14:30</option>
                                                <option value="today_15:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 15:00</option>
                                                <option value="today_15:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 15:30</option>
                                                <option value="today_16:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 16:00</option>
                                                <option value="today_16:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 16:30</option>
                                                <option value="today_17:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 17:00</option>
                                                <option value="today_17:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 17:30</option>
                                                <option value="today_18:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 18:00</option>
                                                <option value="today_18:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 18:30</option>
                                                <option value="today_19:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 19:00</option>
                                                <option value="today_19:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 19:30</option>
                                                <option value="today_20:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 20:00</option>
                                                <option value="today_20:30">{{ trans_db('sections', 'today', false) ?: 'Today' }} 20:30</option>
                                                <option value="today_21:00">{{ trans_db('sections', 'today', false) ?: 'Today' }} 21:00</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Ghi chú đơn hàng -->
                        <div class="card rounded-lg shadow-md overflow-hidden mb-6">
                            <div class="p-4 bg-theme-secondary border-b border-theme">
                                <h2 class="text-lg font-semibold text-theme-primary">
                                    <i class="fas fa-sticky-note mr-2"></i>
                                    {{ trans_db('sections', 'notes', false) ?: 'Notes' }}
                                </h2>
                            </div>
                            <div class="p-6">
                                <textarea id="orderNotes" name="notes" rows="3" class="w-full p-3 border border-theme rounded-md bg-theme-primary text-theme-primary focus:border-aisuki-red" placeholder="{{ trans_db('sections', 'notes_placeholder', false) ?: 'Any special requests or information for your order' }}"></textarea>
                            </div>
                        </div>
                        
                        <!-- Phương thức thanh toán -->
                        <div class="card rounded-lg shadow-md overflow-hidden mb-6">
                            <div class="p-4 bg-theme-secondary border-b border-theme">
                                <h2 class="text-lg font-semibold text-theme-primary">
                                    <i class="fas fa-money-bill-wave mr-2"></i>
                                    {{ trans_db('sections', 'payment_method', false) ?: 'Payment Method' }}
                                </h2>
                            </div>
                            <div class="p-6">
                                <label for="paymentMethod1" class="flex items-center p-4 border rounded-md cursor-pointer transition-colors border-aisuki-red">
                                    <input type="radio" id="paymentMethod1" name="payment_method" value="cash" class="hidden" checked>
                                    <div class="w-6 h-6 rounded-full border-2 border-theme-secondary flex items-center justify-center mr-3">
                                        <div class="w-3 h-3 rounded-full bg-aisuki-red"></div>
                                    </div>
                                    <div style="flex: 1;">
                                        <span class="font-medium text-theme-primary">{{ trans_db('sections', 'cash', false) ?: 'Cash on Delivery/Pickup' }}</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Điều khiển -->
                        <div class="flex flex-col sm:flex-row justify-between gap-4 mb-8">
                            <a href="{{ route('cart', ['locale' => app()->getLocale()]) }}" class="flex items-center justify-center text-aisuki-red hover:underline">
                                <i class="fas fa-chevron-left mr-2"></i> {{ trans_db('sections', 'back_to_cart', false) ?: 'Back to Cart' }}
                            </a>
                            <button type="submit" id="submitOrderBtn" class="bg-aisuki-red text-white py-3 px-6 rounded-md hover:bg-aisuki-red-dark transition-colors flex items-center justify-center">
                                <span class="normal-state">
                                    {{ trans_db('sections', 'submit_order', false) ?: 'Submit Order' }} <i class="fas fa-chevron-right ml-2"></i>
                                </span>
                                <span class="loading-state hidden">
                                    <i class="fas fa-spinner fa-spin mr-2"></i> {{ trans_db('sections', 'processing', false) ?: 'Processing...' }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Cột phải - Tóm tắt đơn hàng -->
                <div class="w-full lg:w-1/3">
                    <div class="card rounded-lg shadow-md overflow-hidden sticky top-24">
                        <div class="p-4 bg-theme-secondary border-b border-theme">
                            <h2 class="font-semibold text-theme-primary">{{ trans_db('sections', 'order_summary', false) ?: 'Order Summary' }}</h2>
                        </div>
                        <div class="p-4 divide-y divide-theme" id="checkoutSummaryItems">
                            <!-- JavaScript sẽ điền nội dung vào đây -->
                            <div class="p-4 text-center">
                                <i class="fas fa-spinner fa-spin text-3xl mb-3 text-theme-secondary"></i>
                                <p class="text-theme-secondary">{{ trans_db('sections', 'loading_cart', false) ?: 'Loading...' }}</p>
                            </div>
                        </div>
                        <div class="p-4 border-t border-theme">
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between text-theme-primary">
                                    <span>{{ trans_db('sections', 'subtotal', false) ?: 'Subtotal' }}:</span>
                                    <span id="checkoutSubtotal">0,00 {{ setting('currency', '€') }}</span>
                                </div>
                                <div class="flex justify-between text-theme-primary" id="checkoutDeliveryRow">
                                    <span>{{ trans_db('sections', 'delivery_fee', false) ?: 'Delivery Fee' }}:</span>
                                    <span id="checkoutDeliveryFee" data-fee="{{ setting('delivery_fee', 2.50) }}">{{ number_format(setting('delivery_fee', 2.50), 2, ',', '.') }} {{ setting('currency', '€') }}</span>
                                </div>
                                <div id="checkoutDiscountRow" class="flex justify-between text-green-600 hidden">
                                    <span>{{ trans_db('sections', 'discount', false) ?: 'Discount' }}:</span>
                                    <span id="checkoutDiscountAmount" data-value="0">-0,00 {{ setting('currency', '€') }}</span>
                                </div>
                            </div>
                            
                            <!-- Mã giảm giá -->
                            <div class="mb-4">
                                <div class="flex">
                                    <input type="text" id="promoCode" placeholder="{{ trans_db('sections', 'promo_code_placeholder', false) ?: 'Nhập mã giảm giá' }}" class="flex-1 p-2 border border-theme rounded-l-md bg-theme-primary text-theme-primary">
                                    <button id="applyPromo" class="bg-aisuki-red text-white px-4 py-2 rounded-r-md hover:bg-aisuki-red-dark transition-colors">
                                        {{ trans_db('sections', 'apply', false) ?: 'Áp dụng' }}
                                    </button>
                                </div>
                                {{--<div class="text-xs text-theme-secondary mt-1">
                                    <i class="fas fa-info-circle mr-1"></i> Mã thử nghiệm: <strong>AISUKI10</strong>
                                </div>--}}
                            </div>
                            
                            <div class="border-t border-theme pt-3">
                                <div class="flex justify-between font-semibold text-theme-primary">
                                    <span>{{ trans_db('sections', 'total', false) ?: 'Total' }}:</span>
                                    <span class="text-aisuki-red text-lg" id="checkoutTotal">0,00 {{ setting('currency', '€') }}</span>
                                </div>
                                <p class="text-xs text-theme-secondary text-right mt-1">{{ trans_db('sections', 'including_vat', false) ?: 'Including VAT' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#applyPromo').click(function() {
            const promoCode = $('#promoCode').val().trim();
            
            if (!promoCode) {
                Cart.showToast(window.translations.enter_promo_code || 'Vui lòng nhập mã giảm giá', 'error');
                return;
            }
            
            // Mock promo code check (in reality, this would be a server request)
            if (promoCode.toUpperCase() === 'AISUKI10') {
                // Assume €3 discount
                const discount = 3.00;
                
                // Show discount row
                $('#checkoutDiscountRow').removeClass('hidden');
                $('#checkoutDiscountAmount').text(`-${Cart.formatPrice(discount)}`).data('value', discount);
                
                // Update total
                updateCheckoutTotals();
                
                // Show success notification
                Cart.showToast(window.translations.promo_applied || 'Đã áp dụng mã giảm giá');
            } else {
                // Invalid promo code
                Cart.showToast(window.translations.invalid_promo || 'Mã giảm giá không hợp lệ', 'error');
            }
        });

        $('input, select, textarea').on('input change', function() {
            $(this).removeClass('border-red-500');
            $(this).next('.validation-error').remove();
        });

        // Khởi tạo trang checkout
        function initCheckout() {
            updateOrderSummary();
            setupDeliveryMethodToggle();
            setupFormSubmit();
        }
        
        // Cập nhật tóm tắt đơn hàng
        function updateOrderSummary() {
            const cartItems = Cart.getItems();
            const summaryContainer = $('#checkoutSummaryItems');
            
            // Xóa nội dung hiện tại
            summaryContainer.empty();
            
            if (cartItems.length === 0) {
                summaryContainer.html(`
                    <div class="py-6 text-center">
                        <p class="text-theme-secondary">${window.translations.cart_empty || 'Your cart is empty'}</p>
                        <a href="${window.routes.menu}" class="inline-block mt-4 text-aisuki-red hover:underline">
                            ${window.translations.view_menu || 'View menu'}
                        </a>
                    </div>
                `);
                
                // Vô hiệu hóa nút Đặt hàng
                $('#submitOrderBtn').addClass('opacity-50 pointer-events-none');
                return;
            }
            
            // Hiển thị các món ăn trong giỏ hàng
            cartItems.forEach(item => {
                let addonsText = '';
                if (item.addons && item.addons.length > 0) {
                    addonsText = '<div class="text-xs text-theme-secondary mt-1">';
                    item.addons.forEach(addon => {
                        addonsText += `<div>+ ${addon.name}</div>`;
                    });
                    addonsText += '</div>';
                }
                
                const itemHtml = `
                    <div class="py-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="flex items-center">
                                    <span class="text-theme-primary mr-2">${item.quantity}x</span>
                                    <h4 class="font-medium text-theme-primary">${item.name}</h4>
                                </div>
                                ${addonsText}
                            </div>
                            <p class="font-medium text-theme-primary">${Cart.formatPrice(item.total)}</p>
                        </div>
                    </div>
                `;
                
                summaryContainer.append(itemHtml);
            });
            
            // Cập nhật tổng tiền
            updateCheckoutTotals();
            
            // Bật nút Đặt hàng
            $('#submitOrderBtn').removeClass('opacity-50 pointer-events-none');
        }
        
        // Cập nhật tổng tiền trong tóm tắt đơn hàng
        function updateCheckoutTotals() {
            const subtotal = Cart.getSubtotal();
            let deliveryFee = 0;
            
            // Kiểm tra phương thức giao hàng
            if ($('input[name="delivery_method"]:checked').val() === 'delivery') {
                deliveryFee = parseFloat($('#checkoutDeliveryFee').data('fee') || 0);
                $('#checkoutDeliveryRow').removeClass('hidden');
            } else {
                $('#checkoutDeliveryRow').addClass('hidden');
            }
            
            // Kiểm tra xem có mã giảm giá không
            let discount = 0;
            if (!$('#checkoutDiscountRow').hasClass('hidden')) {
                discount = parseFloat($('#checkoutDiscountAmount').data('value') || 0);
            }
            
            const total = Cart.getTotal(deliveryFee, discount);
            
            // Cập nhật tạm tính
            $('#checkoutSubtotal').text(Cart.formatPrice(subtotal));
            
            // Cập nhật tổng cộng
            $('#checkoutTotal').text(Cart.formatPrice(total));
        }
        
        // Thiết lập sự kiện khi thay đổi phương thức giao hàng
        function setupDeliveryMethodToggle() {
            // Hiển thị phần tương ứng khi chọn phương thức giao hàng
            $('.delivery-option').click(function() {
                const method = $(this).data('method');
                
                // Loại bỏ trạng thái đã chọn khỏi tất cả các tùy chọn
                $('.delivery-option').removeClass('border-aisuki-red').addClass('border-theme');
                $('.delivery-radio-inner').addClass('hidden');
                
                // Thêm trạng thái đã chọn vào tùy chọn được chọn
                $(this).removeClass('border-theme').addClass('border-aisuki-red');
                $(this).find('.delivery-radio-inner').removeClass('hidden');
                
                // Hiển thị form tương ứng
                if (method === 'delivery') {
                    $('#deliveryAddressForm').removeClass('hidden');
                    $('#pickupInformation').addClass('hidden');
                } else {
                    $('#deliveryAddressForm').addClass('hidden');
                    $('#pickupInformation').removeClass('hidden');
                }
                
                // Cập nhật tổng tiền
                updateCheckoutTotals();
            });
            
            // Thiết lập trạng thái ban đầu cho phương thức đã chọn
            const selectedMethod = $('input[name="delivery_method"]:checked').val();
            $(`.delivery-option[data-method="${selectedMethod}"]`).click();
        }
        
        // Thiết lập sự kiện submit form
        function setupFormSubmit() {
            // Biến để theo dõi trạng thái submit
            let isSubmitting = false;
            
            // Chuẩn bị nút submit
            const submitBtn = $('#submitOrderBtn');
            
            // Xử lý submit form
            $('#checkoutForm').on('submit', function(e) {
                // Ngăn chặn submit nếu đang trong quá trình submit
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }
                
                // Remove existing error messages
                $('.validation-error').remove();
                
                // Kiểm tra giỏ hàng có trống không
                const cartItems = Cart.getItems();
                if (cartItems.length === 0) {
                    e.preventDefault();
                    Cart.showToast(window.translations.cart_empty || 'Your cart is empty', 'error');
                    return false;
                }
                
                // Kiểm tra các trường form
                if (!validateFormFields()) {
                    e.preventDefault();
                    return false;
                }
                
                // Thiết lập trạng thái đang submit
                isSubmitting = true;
                
                // Hiển thị loading và vô hiệu hóa nút
                submitBtn.prop('disabled', true);
                submitBtn.find('.normal-state').addClass('hidden');
                submitBtn.find('.loading-state').removeClass('hidden');
                
                // Thêm cart items vào form data
                const cartItemsInput = $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'cart_items')
                    .val(JSON.stringify(cartItems));
                
                $(this).append(cartItemsInput);
                
                // Form sẽ tự submit
                return true;
            });
            
            // Reset trạng thái form khi back lại trang
            $(window).on('pageshow', function(event) {
                if (event.originalEvent.persisted) {
                    // Trang được tải từ cache (back button)
                    isSubmitting = false;
                    submitBtn.prop('disabled', false);
                    submitBtn.find('.normal-state').removeClass('hidden');
                    submitBtn.find('.loading-state').addClass('hidden');
                    $('.validation-error').remove();
                }
            });
        }
        
        // Form field validation
        function validateFormFields() {
            let isValid = true;
            
            // Reset all error states
            $('.validation-error').remove();
            $('.border-red-500').removeClass('border-red-500');
            
            // Define validation rules for each field
            const fields = [
                { 
                    id: 'fullName', 
                    rules: ['required'], 
                    messages: {
                        required: "{{ trans_db('validation', 'name_required', false) ?: 'Name is required' }}"
                    }
                },
                { 
                    id: 'email', 
                    rules: ['required', 'email'], 
                    messages: {
                        required: "{{ trans_db('validation', 'email_required', false) ?: 'Email is required' }}",
                        email: "{{ trans_db('validation', 'email_invalid', false) ?: 'Please enter a valid email address' }}"
                    }
                },
                { 
                    id: 'phone', 
                    rules: ['required'], 
                    messages: {
                        required: "{{ trans_db('validation', 'phone_required', false) ?: 'Phone number is required' }}"
                    }
                }
            ];
            
            // Add delivery fields if delivery method is selected
            if ($('input[name="delivery_method"]:checked').val() === 'delivery') {
                fields.push(
                    { 
                        id: 'street', 
                        rules: ['required'], 
                        messages: {
                            required: "{{ trans_db('validation', 'street_required', false) ?: 'Street is required' }}"
                        }
                    },
                    { 
                        id: 'houseNumber', 
                        rules: ['required'], 
                        messages: {
                            required: "{{ trans_db('validation', 'house_number_required', false) ?: 'House number is required' }}"
                        }
                    },
                    { 
                        id: 'city', 
                        rules: ['required'], 
                        messages: {
                            required: "{{ trans_db('validation', 'city_required', false) ?: 'City is required' }}"
                        }
                    },
                    { 
                        id: 'postalCode', 
                        rules: ['required'], 
                        messages: {
                            required: "{{ trans_db('validation', 'postal_code_required', false) ?: 'Postal code is required' }}"
                        }
                    }
                );
            }
            
            // Validate each field
            fields.forEach(field => {
                const $field = $('#' + field.id);
                let fieldErrors = [];
                
                // Check each validation rule
                field.rules.forEach(rule => {
                    const value = $field.val().trim();
                    
                    if (rule === 'required' && value === '') {
                        fieldErrors.push(field.messages.required);
                    } else if (rule === 'email' && value !== '') {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(value)) {
                            fieldErrors.push(field.messages.email);
                        }
                    }
                });
                
                // If field has errors, mark it and show messages
                if (fieldErrors.length > 0) {
                    $field.addClass('border-red-500');
                    
                    // Add error message after the field
                    const errorContainer = $('<div class="validation-error text-red-500 text-xs mt-1"></div>');
                    errorContainer.text(fieldErrors[0]); // Show only the first error for each field
                    
                    // Find the field's parent and append error
                    $field.parent().append(errorContainer);
                    
                    isValid = false;
                }
            });
            
            if (!isValid) {
                // Show error message
                Cart.showToast("{{ trans_db('validation', 'form_errors', false) ?: 'Please fix the errors in the form' }}", 'error');
            }
            
            return isValid;
        }
        
        // Kiểm tra email hợp lệ
        function isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
        
        // Khởi tạo trang checkout
        initCheckout();
    });
</script>
@endpush