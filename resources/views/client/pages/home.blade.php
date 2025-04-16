@extends('client.layouts.app')

@section('content')
@php
    $currentLocale = app()->getLocale();
@endphp
<!-- Hero Banner -->
<section class="relative h-[400px] sm:h-[450px] lg:h-[500px] bg-cover bg-center flex justify-center items-center" id="home" style="background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('https://images.unsplash.com/photo-1579871494447-9811cf80d66c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80')">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="z-10 text-center text-white max-w-3xl px-4 sm:px-6">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-brand mb-5 drop-shadow-lg">{{ setting('site_name', 'AISUKI') }}</h1>
        <p class="text-lg mb-8 drop-shadow-md">
            {{ trans_db('sections', 'hero_subtitle', false) ?: 'Authentic Japanese restaurant with traditional flavors and cozy atmosphere' }}
        </p>
        <a href="{{ route('contact', ['locale' => $currentLocale]) }}" class="inline-block bg-aisuki-red text-white py-3 px-6 rounded-full font-semibold hover:bg-[#c41017] transform hover:-translate-y-0.5 transition-all duration-300 mb-2 md:mb-0">
            {{ trans_db('sections', 'hero_button_reservation', false) ?: 'Book a Table Now' }}
        </a>
        <a href="#menu" class="inline-block bg-transparent border-2 border-white text-white py-3 px-6 rounded-full font-semibold mb-4 md:mb-0 md:ml-4 hover:bg-white hover:text-aisuki-red transform hover:-translate-y-0.5 transition-all duration-300">
            {{ trans_db('sections', 'hero_button_menu', false) ?: 'View Menu' }}
        </a>
    </div>
</section>

<!-- Quick Contact -->
<div class="max-w-7xl mx-auto px-4">
    <div class="card rounded-lg shadow-lg -mt-16 sm:-mt-20 relative z-10 p-5 sm:p-8 flex flex-col md:flex-row justify-between">
        <div class="flex flex-col sm:flex-row items-center mb-7 md:mb-0">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-aisuki-red/10 flex justify-center items-center mb-4 sm:mb-0 sm:mr-4">
                <i class="fas fa-phone-alt text-aisuki-red"></i>
            </div>
            <div class="text-center sm:text-left">
                <h4 class="text-sm sm:text-base font-semibold mb-1 text-theme-primary">
                    {{ trans_db('sections', 'quick_contact_reservation_title', false) ?: 'Reservation Contact' }}
                </h4>
                <p class="text-theme-secondary text-sm">{{ setting('phone') }}</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-center mb-7 md:mb-0">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-aisuki-red/10 flex justify-center items-center mb-4 sm:mb-0 sm:mr-4">
                <i class="fas fa-clock text-aisuki-red"></i>
            </div>
            <div class="text-center sm:text-left">
                <h4 class="text-sm sm:text-base font-semibold mb-1 text-theme-primary">
                    {{ trans_db('sections', 'quick_contact_hours_title', false) ?: 'Opening Hours' }}
                </h4>
                <p class="text-theme-secondary text-sm">{{ setting('opening_hours') }}</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-aisuki-red/10 flex justify-center items-center mb-4 sm:mb-0 sm:mr-4">
                <i class="fas fa-map-marker-alt text-aisuki-red"></i>
            </div>
            <div class="text-center sm:text-left">
                <h4 class="text-sm sm:text-base font-semibold mb-1 text-theme-primary">
                    {{ trans_db('sections', 'quick_contact_address_title', false) ?: 'Address' }}
                </h4>
                <p class="text-theme-secondary text-sm">{{ setting('address') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Menu Section -->
<section class="py-12 sm:py-16 px-4" id="menu">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl text-aisuki-red font-bold mb-4 inline-block relative">
                {{ trans_db('sections', 'menu_title', false) ?: 'Menu' }}
                <span class="absolute w-20 h-0.5 bg-aisuki-red -bottom-2 left-1/2 transform -translate-x-1/2"></span>
            </h2>
            <p class="text-theme-secondary mt-6">{{trans_db('sections', 'call_hotline', false)}}: {{ setting('phone') }}</p>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-10">
            <a href="tel:{{ setting('phone') }}" class="flex items-center justify-center bg-aisuki-red text-white rounded-full py-3 px-6 font-semibold transition-all hover:bg-[#c41017]">
                <i class="fas fa-phone-alt mr-2"></i> {{ trans_db('sections', 'call_hotline', false) ?: 'Call Hotline' }}
            </a>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('whatsapp')) }}" target="_blank" class="flex items-center justify-center bg-[#25D366] text-white rounded-full py-3 px-6 font-semibold transition-all hover:bg-[#128C7E]">
                <i class="fab fa-whatsapp mr-2"></i> WhatsApp
            </a>
        </div>

        <!-- Menu categories carousel -->
        <div class="flex overflow-x-auto pb-5 snap-x snap-mandatory scrollbar-hide gap-4 mb-10">
            @foreach($categories as $category)
            <div class="flex-shrink-0 text-center snap-center min-w-[100px] sm:min-w-[120px]">
                <a href="{{ route('menu', ['locale' => app()->getLocale()]) }}#category-{{ $category->slug }}" class="block">
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="w-20 h-20 sm:w-28 sm:h-28 object-cover rounded-full mx-auto mb-2 transition-all hover:shadow-lg">
                    <h3 class="text-xs sm:text-sm font-medium text-theme-primary">{{ $category->name }}</h3>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Menu Items - Desktop Layout -->
        <div class="hidden sm:grid sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @foreach($featuredItems as $item)
            <div class="card rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all">
                <div class="h-44 sm:h-48 bg-cover bg-center relative" style="background-image: url('{{ asset($item->image) }}')"></div>
                <div class="p-5">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-semibold text-theme-primary truncate-none">{{ $item->name }}</h3>
                        <span class="text-aisuki-red font-bold">{{ setting('currency', '€') }}{{ number_format($item->price, 2, '.', ',') }}</span>
                    </div>
                    <p class="text-theme-secondary text-sm mb-4 line-clamp-2">{{ $item->description }}</p>
                    <button class="inline-block w-full bg-aisuki-red text-white py-2 px-4 rounded-full text-sm font-semibold hover:bg-[#c41017] transition-all order-btn"
                        data-id="{{ $item->id }}"
                        data-code="{{ $item->code }}"
                        data-name="{{ $item->name }}"
                        data-description="{{ $item->description }}"
                        data-price="{{ $item->price }}"
                        data-image="{{ asset($item->image) }}">
                        <i class="fas fa-cart-plus mr-1"></i> {{ trans_db('sections', 'order_now', false) ?: 'Order Now' }}
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Menu Items - Mobile Layout (Horizontal) -->
        <div class="sm:hidden space-y-4">
            @foreach($featuredItems as $item)
            <div class="card rounded-lg overflow-hidden shadow-md flex">
                <div class="w-1/3 bg-cover bg-center relative" style="background-image: url('{{ asset($item->image) }}')">
                    <div class="absolute top-1 left-1 bg-aisuki-red text-white px-1.5 py-0.5 text-xs font-bold">
                        {{ $item->code }}
                    </div>
                </div>
                <div class="w-2/3 p-3">
                    <div class="flex justify-between items-center mb-1">
                        <h3 class="font-semibold text-sm text-theme-primary truncate-none">{{ $item->name }}</h3>
                        <span class="text-aisuki-red font-bold text-sm">{{ setting('currency', '€') }}{{ number_format($item->price, 2, '.', ',') }}</span>
                    </div>
                    <p class="text-theme-secondary text-xs mb-2 line-clamp-2">{{ $item->description }}</p>
                    <button class="inline-block bg-aisuki-red text-white py-1.5 px-3 rounded-full text-xs font-semibold hover:bg-[#c41017] transition-all order-btn"
                        data-id="{{ $item->id }}"
                        data-code="{{ $item->code }}"
                        data-name="{{ $item->name }}"
                        data-description="{{ $item->description }}"
                        data-price="{{ $item->price }}"
                        data-image="{{ asset($item->image) }}">
                        <i class="fas fa-cart-plus mr-1"></i> {{ trans_db('sections', 'order_now', false) ?: 'Order Now' }}
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-10 sm:mt-12">
            <a href="{{ route('menu', ['locale' => app()->getLocale()]) }}" class="inline-block bg-aisuki-red text-white py-3 px-6 rounded-full font-semibold hover:bg-[#c41017] transition-all">{{ trans_db('sections', 'view_full_menu', false) ?: 'View Full Menu' }}</a>
        </div>
    </div>
</section>

<!-- Call Action Section with Fixed Background -->
<section class="py-12 sm:py-16 px-4 text-center text-white bg-fixed bg-cover bg-center" style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1579871494447-9811cf80d66c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4">
            {{ trans_db('sections', 'cta_title', false) ?: 'Book a table today' }}
        </h2>
        <p class="text-base sm:text-lg max-w-2xl mx-auto mb-8">
            {{ trans_db('sections', 'cta_subtitle', false) ?: 'Make a reservation to choose your favorite seat and experience authentic Japanese cuisine at AISUKI' }}
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="tel:{{ setting('phone') }}" class="flex items-center justify-center text-white bg-aisuki-red py-3 px-6 rounded-full font-semibold transition-all hover:bg-[#c41017]">
                <i class="fas fa-phone-alt mr-2"></i> {{ trans_db('sections', 'call', false) ?: 'Call' }} {{ setting('phone') }}
            </a>
            <a href="{{ route('contact', ['locale' => $currentLocale]) }}" class="flex items-center justify-center bg-transparent border-2 border-white text-white py-3 px-6 rounded-full font-semibold transition-all hover:bg-white hover:text-aisuki-red">
                <i class="fas fa-calendar-alt mr-2"></i> {{ trans_db('sections', 'online_reservation', false) ?: 'Online Reservation' }}
            </a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-12 sm:py-16 px-4 bg-theme-secondary" id="about">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row items-center">
            <div class="w-full md:w-1/2 p-4">
                <img src="{{ asset('uploads/photo-1579871494447-9811cf80d66c.avif') }}" alt="About AISUKI" class="w-full rounded-lg shadow-xl">
            </div>
            <div class="w-full md:w-1/2 p-4 sm:p-8">
                <h3 class="text-2xl sm:text-3xl text-aisuki-red font-bold mb-4">
                    {{ trans_db('sections', 'about_title', false) ?: 'About AISUKI' }}
                </h3>
                <p class="text-theme-primary mb-4">
                    {{ trans_db('sections', 'about_paragraph_1', false) ?: 'AISUKI started as a small sushi cart, and through much effort, for which the AISUKI brand is always grateful.' }}
                </p>
                <p class="text-theme-primary mb-4">
                    {{ trans_db('sections', 'about_paragraph_2', false) ?: 'With the dedicated purpose of finding the best Japanese food to satisfy our customers at reasonable prices. More accessible to the German people compared to traditional Japanese restaurants.' }}
                </p>
                <p class="text-theme-primary mb-6">
                    {{ trans_db('sections', 'about_paragraph_3', false) ?: 'Thanks to our commitment to quality and our passion for authentic Japanese cuisine, AISUKI has received support and love from customers. We are always grateful and wish to express our sincere thanks to our customers until the next encounter.' }}
                </p>
                <a href="#" class="inline-block bg-aisuki-red text-white py-3 px-6 rounded-full font-semibold hover:bg-[#c41017] transition-all">
                    {{ trans_db('sections', 'about_button', false) ?: 'Learn More' }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Reservation Section -->
<section class="py-12 sm:py-16 px-4" id="contact">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl text-aisuki-red font-bold mb-4 inline-block relative">
                {{ trans_db('sections', 'reservation_title', false) ?: 'Reservation' }}
                <span class="absolute w-20 h-0.5 bg-aisuki-red -bottom-2 left-1/2 transform -translate-x-1/2"></span>
            </h2>
        </div>
        <div class="flex flex-col md:flex-row card rounded-lg overflow-hidden shadow-xl">
            <div class="w-full md:w-1/2 order-1 md:order-0 p-6 sm:p-8">
                <h3 class="text-xl sm:text-2xl text-aisuki-red font-bold mb-6">
                    {{ trans_db('sections', 'reservation_form_title', false) ?: 'Reservation Information' }}
                </h3>
                <form>
                    <div class="mb-4">
                        <label for="name" class="block font-semibold text-sm mb-2 text-theme-primary">
                            {{ trans_db('sections', 'reservation_form_name', false) ?: 'Full Name' }}
                        </label>
                        <input type="text" id="name" placeholder="{{ trans_db('sections', 'reservation_form_name_placeholder', false) ?: 'Enter your full name' }}" class="w-full px-4 py-3 border border-theme rounded-lg focus:outline-none focus:ring-2 focus:ring-aisuki-red/30 focus:border-aisuki-red">
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block font-semibold text-sm mb-2 text-theme-primary">
                            {{ trans_db('sections', 'reservation_form_phone', false) ?: 'Phone Number' }}
                        </label>
                        <input type="tel" id="phone" placeholder="{{ trans_db('sections', 'reservation_form_phone_placeholder', false) ?: 'Enter your phone number' }}" class="w-full px-4 py-3 border border-theme rounded-lg focus:outline-none focus:ring-2 focus:ring-aisuki-red/30 focus:border-aisuki-red">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block font-semibold text-sm mb-2 text-theme-primary">
                            {{ trans_db('sections', 'reservation_form_email', false) ?: 'Email' }}
                        </label>
                        <input type="email" id="email" placeholder="{{ trans_db('sections', 'reservation_form_email_placeholder', false) ?: 'Enter your email' }}" class="w-full px-4 py-3 border border-theme rounded-lg focus:outline-none focus:ring-2 focus:ring-aisuki-red/30 focus:border-aisuki-red">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="date" class="block font-semibold text-sm mb-2 text-theme-primary">
                                {{ trans_db('sections', 'reservation_form_date', false) ?: 'Date' }}
                            </label>
                            <input type="date" id="date" class="w-full px-4 py-3 border border-theme rounded-lg focus:outline-none focus:ring-2 focus:ring-aisuki-red/30 focus:border-aisuki-red">
                        </div>
                        <div>
                            <label for="time" class="block font-semibold text-sm mb-2 text-theme-primary">
                                {{ trans_db('sections', 'reservation_form_time', false) ?: 'Time' }}
                            </label>
                            <input type="time" id="time" class="w-full px-4 py-3 border border-theme rounded-lg focus:outline-none focus:ring-2 focus:ring-aisuki-red/30 focus:border-aisuki-red">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="guests" class="block font-semibold text-sm mb-2 text-theme-primary">
                            {{ trans_db('sections', 'reservation_form_guests', false) ?: 'Number of Guests' }}
                        </label>
                        <select id="guests" class="w-full px-4 py-3 border border-theme rounded-lg focus:outline-none focus:ring-2 focus:ring-aisuki-red/30 focus:border-aisuki-red">
                            <option value="1">{{ trans_db('sections', 'reservation_form_guests_1', false) ?: '1 person' }}</option>
                            <option value="2">{{ trans_db('sections', 'reservation_form_guests_2', false) ?: '2 people' }}</option>
                            <option value="3">{{ trans_db('sections', 'reservation_form_guests_3', false) ?: '3 people' }}</option>
                            <option value="4">{{ trans_db('sections', 'reservation_form_guests_4', false) ?: '4 people' }}</option>
                            <option value="5">{{ trans_db('sections', 'reservation_form_guests_5', false) ?: '5 people' }}</option>
                            <option value="6">{{ trans_db('sections', 'reservation_form_guests_6plus', false) ?: '6 or more people' }}</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="message" class="block font-semibold text-sm mb-2 text-theme-primary">
                            {{ trans_db('sections', 'reservation_form_notes', false) ?: 'Notes' }}
                        </label>
                        <textarea id="message" rows="3" placeholder="{{ trans_db('sections', 'reservation_form_notes_placeholder', false) ?: 'Enter your notes' }}" class="w-full px-4 py-3 border border-theme rounded-lg focus:outline-none focus:ring-2 focus:ring-aisuki-red/30 focus:border-aisuki-red"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-aisuki-red text-white py-3 px-6 rounded-full font-semibold hover:bg-[#c41017] transition-all">
                        {{ trans_db('sections', 'reservation_form_submit', false) ?: 'Confirm Reservation' }}
                    </button>
                </form>
            </div>
            <div class="w-full md:w-1/2 order-0 md:order-1 h-64 md:h-auto">
                {!! setting('google_maps_iframe') !!}
            </div>
        </div>
    </div>
</section>

@include('client.components.food-order-modal')
@endsection

@push('styles')
<!-- Home page specific CSS -->
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }
    }

    .bounce-animation {
        animation: bounce 2s infinite;
    }

    .truncate-none {
        overflow: visible;
        text-overflow: initial;
        white-space: normal;
    }
</style>
@endpush