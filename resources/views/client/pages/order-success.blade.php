@extends('client.layouts.app')

@section('content')
    <!-- Page Title -->
    <section class="py-6 bg-theme-primary border-b border-theme">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <h1 class="text-2xl md:text-3xl font-bold text-theme-primary text-center">{{ trans_db('sections', 'order_success_title', false) ?: 'Order Successful' }}</h1>
        </div>
    </section>

    <!-- Success content -->
    <section class="py-8 md:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <div class="card rounded-lg shadow-md overflow-hidden">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-3xl"></i>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-theme-primary mb-2">{{ trans_db('sections', 'thank_you_order', false) ?: 'Thank You for Your Order!' }}</h2>
                    <p class="text-theme-secondary mb-4">{{ trans_db('sections', 'order_received', false) ?: 'Your order has been received and is being processed.' }}</p>
                    
                    <div class="bg-theme-secondary p-4 rounded-md text-left mb-6">
                        <h3 class="font-semibold text-theme-primary mb-2">{{ trans_db('sections', 'order_details', false) ?: 'Order Details:' }}</h3>
                        <p><strong>{{ trans_db('sections', 'order_number', false) ?: 'Order Number:' }}</strong> {{ $order->order_number }}</p>
                        <p><strong>{{ trans_db('sections', 'order_date', false) ?: 'Order Date:' }}</strong> {{ $order->created_at->format('M d, Y, h:i A') }}</p>
                        <p><strong>{{ trans_db('sections', 'total', false) ?: 'Total:' }}</strong> {{ setting('currency', '€') }}{{ number_format($order->total, 2) }}</p>
                    </div>
                    
                    <p class="text-theme-secondary mb-6">{{ trans_db('sections', 'order_confirmation_email', false) ?: 'A confirmation email has been sent to your email address.' }}</p>
                    
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('menu', ['locale' => app()->getLocale()]) }}" class="inline-block bg-aisuki-red text-white py-2 px-6 rounded-md hover:bg-aisuki-red-dark transition-colors">
                            <i class="fas fa-utensils mr-2"></i> {{ trans_db('sections', 'back_to_menu', false) ?: 'Back to Menu' }}
                        </a>
                        <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="inline-block bg-theme-secondary text-theme-primary py-2 px-6 rounded-md hover:bg-opacity-80 transition-colors">
                            <i class="fas fa-home mr-2"></i> {{ trans_db('sections', 'back_to_home', false) ?: 'Go to Homepage' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Clear cart script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Clear the cart after successful order
            if (typeof Cart !== 'undefined') {
                Cart.clearCart();
            } else {
                localStorage.removeItem('aisuki_cart');
            }
        });
    </script>
@endsection