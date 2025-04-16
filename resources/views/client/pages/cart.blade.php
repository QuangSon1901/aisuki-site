@extends('client.layouts.app')

@section('content')
    <!-- Tiêu đề trang -->
    <section class="py-6 bg-theme-primary border-b border-theme">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <h1 class="text-2xl md:text-3xl font-bold text-theme-primary text-center cart-title">{{ trans_db('sections', 'your_cart', false) ?: 'Giỏ hàng' }}</h1>
        </div>
    </section>

    <!-- Nội dung giỏ hàng -->
    <section class="py-8 md:py-12" id="cartPage">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cột trái - Danh sách sản phẩm -->
                <div class="w-full lg:w-2/3">
                    <div class="card rounded-lg shadow-md overflow-hidden">
                        <div class="p-4 bg-theme-secondary border-b border-theme">
                            <h2 class="text-lg font-semibold text-theme-primary cart-title">{{ trans_db('sections', 'cart_items', false) ?: 'Món ăn trong giỏ' }}</h2>
                        </div>
                        
                        <!-- Danh sách sản phẩm -->
                        <div id="cartItems" class="divide-y divide-theme">
                            <!-- JavaScript sẽ điền nội dung vào đây -->
                            <div class="p-8 text-center">
                                <i class="fas fa-spinner fa-spin text-3xl mb-3 text-theme-secondary"></i>
                                <p class="text-theme-secondary">{{ trans_db('sections', 'loading_cart', false) ?: 'Đang tải giỏ hàng...' }}</p>
                            </div>
                        </div>

                        <!-- Điều khiển giỏ hàng -->
                        <div id="checkoutControls" class="p-4 border-t border-theme hidden">
                            <div class="flex justify-between items-center">
                                <div class="flex flex-wrap gap-4">
                                    <a href="{{ route('menu', ['locale' => app()->getLocale()]) }}" class="text-aisuki-red hover:underline flex items-center">
                                        <i class="fas fa-chevron-left mr-2"></i> {{ trans_db('sections', 'continue_shopping', false) ?: 'Tiếp tục mua hàng' }}
                                    </a>
                                    <button id="clearCart" class="text-theme-secondary hover:text-aisuki-red transition-colors flex items-center">
                                        <i class="fas fa-trash-alt mr-1"></i> {{ trans_db('sections', 'clear_cart', false) ?: 'Xóa giỏ hàng' }}
                                    </button>
                                </div>
                                <button id="proceedToCheckout" class="bg-aisuki-red text-white py-2 px-6 rounded-md hover:bg-aisuki-red-dark transition-colors">
                                    {{ trans_db('sections', 'proceed_to_checkout', false) ?: 'Tiến hành đặt hàng' }} <i class="fas fa-chevron-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cột phải - Tóm tắt đơn hàng -->
                <div class="w-full lg:w-1/3">
                    <div class="card rounded-lg shadow-md overflow-hidden sticky top-24">
                        <div class="p-4 bg-theme-secondary border-b border-theme">
                            <h2 class="font-semibold text-theme-primary">{{ trans_db('sections', 'order_summary', false) ?: 'Tóm tắt đơn hàng' }}</h2>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between text-theme-primary">
                                    <span>{{ trans_db('sections', 'subtotal', false) ?: 'Tạm tính' }}:</span>
                                    <span id="cartSubtotal">0,00 €</span>
                                </div>
                                <div class="flex justify-between text-theme-primary">
                                    <span>{{ trans_db('sections', 'delivery_fee', false) ?: 'Phí vận chuyển' }}:</span>
                                    <span id="deliveryFee" data-fee="{{ $deliveryFee }}">{{ setting('currency', '€') }}{{ number_format($deliveryFee, 2, ',', '.') }}</span>
                                </div>
                                <div id="discountRow" class="flex justify-between text-green-600 hidden">
                                    <span>{{ trans_db('sections', 'discount', false) ?: 'Giảm giá' }}:</span>
                                    <span id="discountAmount" data-value="0">-0,00 €</span>
                                </div>
                            </div>
                            
                            <div class="border-t border-theme pt-3 mb-4">
                                <div class="flex justify-between font-semibold text-theme-primary">
                                    <span>{{ trans_db('sections', 'total', false) ?: 'Tổng cộng' }}:</span>
                                    <span class="text-aisuki-red text-lg" id="cartTotal">0,00 €</span>
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

                            <!-- Phần trợ giúp -->
                            <div class="bg-theme-secondary p-3 rounded-md">
                                <p class="text-theme-primary font-medium mb-2">{{ trans_db('sections', 'need_help', false) ?: 'Cần trợ giúp?' }}</p>
                                <p class="text-theme-secondary text-sm mb-2">{{ trans_db('sections', 'call_us', false) ?: 'Gọi cho chúng tôi' }}: {{ setting('phone') }}</p>
                                <p class="text-theme-secondary text-sm">{{ trans_db('sections', 'opening_hours', false) ?: 'Giờ mở cửa' }}: {{ setting('opening_hours') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Container Toast -->
    <div class="toast-container"></div>
@endsection
