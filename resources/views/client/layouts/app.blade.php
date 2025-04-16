<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="{{ setting('theme_mode', 'light') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('meta_title', 'AISUKI - Japanese Restaurant') }}</title>
    <meta name="description" content="{{ setting('meta_description') }}">
    <meta name="keywords" content="{{ setting('meta_keywords') }}">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('uploads/logo.png') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- Google Fonts -->
    <style> @import url('https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Boldonse&display=swap'); </style>
    
    <!-- Vite CSS -->
    @vite(['resources/css/client.css', 'resources/js/client.js'])
    <!-- Page specific CSS -->
    @stack('styles')
</head>
<body>
    @php
        $currentLocale = app()->getLocale();
    @endphp
    @include('client.layouts.header')
    
    <main>
        @yield('content')
    </main>
    
    @include('client.layouts.footer')
    
    <!-- Fixed Mobile CTA -->
    <div class="fixed bottom-0 left-0 right-0 bg-theme-primary flex justify-between p-3 shadow-lg z-30 md:hidden transform translate-y-full transition-transform duration-300" id="mobileCta">
        <a href="tel:{{ setting('phone') }}" class="flex-1 bg-aisuki-red text-white font-semibold py-2 px-1 rounded text-center text-sm mr-2">
            <i class="fas fa-phone-alt mr-1"></i> Call Now
        </a>
        <a href="{{ route('contact', ['locale' => $currentLocale]) }}" class="flex-1 bg-aisuki-yellow text-aisuki-black font-semibold py-2 px-1 rounded text-center text-sm ml-2">
            <i class="fas fa-calendar-alt mr-1"></i> Reservation
        </a>
    </div>

    <!-- Scroll To Top Button -->
    <button id="scrollToTop" class="fixed bottom-20 right-5 w-12 h-12 bg-aisuki-red text-white rounded-full shadow-lg flex items-center justify-center z-30 opacity-0 invisible transition-all duration-300 hover:bg-[#c41017]">
        <i class="fas fa-chevron-up"></i>
    </button>
    
    <!-- Page specific JS -->
    @stack('scripts')

    <script>
        window.translations = {
            added_to_cart: "{{ trans_db('sections', 'added_to_cart', false) ?: 'Added to cart' }}",
            cart_items: "{{ trans_db('sections', 'cart_items', false) ?: 'Cart Items' }}",
            cart_empty: "{{ trans_db('sections', 'cart_empty', false) ?: 'Giỏ hàng trống' }}",
            cart_empty_message: "{{ trans_db('sections', 'cart_empty_message', false) ?: 'Hãy thêm món ăn vào giỏ hàng' }}",
            view_menu: "{{ trans_db('sections', 'view_menu', false) ?: 'Xem thực đơn' }}",
            removed_from_cart: "{{ trans_db('sections', 'removed_from_cart', false) ?: 'đã xóa khỏi giỏ hàng' }}",
            remove: "{{ trans_db('sections', 'remove', false) ?: 'Remove' }}",
            cart_cleared: "{{ trans_db('sections', 'cart_cleared', false) ?: 'Giỏ hàng đã được xóa' }}",
            confirm_clear_cart: "{{ trans_db('sections', 'confirm_clear_cart', false) ?: 'Bạn có chắc muốn xóa toàn bộ giỏ hàng không?' }}",
            enter_promo_code: "{{ trans_db('sections', 'enter_promo_code', false) ?: 'Vui lòng nhập mã giảm giá' }}",
            promo_applied: "{{ trans_db('sections', 'promo_applied', false) ?: 'Đã áp dụng mã giảm giá' }}",
            invalid_promo: "{{ trans_db('sections', 'invalid_promo', false) ?: 'Mã giảm giá không hợp lệ' }}",
            enter_promo_code: "{{ trans_db('sections', 'enter_promo_code', false) ?: 'Mã giảm giá không hợp lệ' }}"
        };
        
        window.routes = {
            cart: "{{ route('cart', ['locale' => app()->getLocale()]) }}",
            menu: "{{ route('menu', ['locale' => app()->getLocale()]) }}"
        };
    </script>
</body>
</html>