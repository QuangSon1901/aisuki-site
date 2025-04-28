@php
    $currentLocale = app()->getLocale();
@endphp

<div class="bg-aisuki-black text-white text-sm py-1.5 px-4 hidden sm:block">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="flex items-center">
                <i class="fas fa-map-marker-alt text-aisuki-red mr-1.5"></i>
                <span>{{ $currentLocale == 'en' ? setting('address') : trans_db('settings', 'address', false) }}</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-phone-alt text-aisuki-red mr-1.5"></i>
                <span>{{ setting('phone') }}</span>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <div class="flex items-center">
                <i class="fas fa-clock text-aisuki-red mr-1.5"></i>
                <span>{{ str_replace(["\\r\\n", "\\n"], "", $currentLocale == 'en' ? setting('opening_hours') : trans_db('settings', 'opening_hours', false)) }}</span>
            </div>
            <div class="flex space-x-2">
                @if(setting('facebook'))
                    <a href="{{ setting('facebook') }}" target="_blank" class="hover:text-aisuki-red transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                @endif
                
                @if(setting('instagram'))
                    <a href="{{ setting('instagram') }}" target="_blank" class="hover:text-aisuki-red transition-colors">
                        <i class="fab fa-instagram"></i>
                    </a>
                @endif
                
                @if(setting('twitter'))
                    <a href="{{ setting('twitter') }}" target="_blank" class="hover:text-aisuki-red transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Header -->
<header class="sticky top-0 z-30 transition-transform duration-300 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home', ['locale' => $currentLocale]) }}" class="flex items-center">
                    <div class="rounded-full">
                        <img src="{{ asset(setting('site_logo', 'logo.png')) }}" alt="{{ setting('site_name', 'AISUKI') }} Logo" class="h-10 scale-[1.2] sm:scale-[1.4]">
                    </div>
                    <h1 class="text-white ml-2.5 text-xl font-brand tracking-wide">{{ setting('site_name', 'AISUKI') }}</h1>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-1">
                <a href="{{ route('home', ['locale' => $currentLocale]) }}" class="text-white font-medium px-4 py-2 rounded-md {{ request()->routeIs('home') ? 'bg-white/20' : 'hover:bg-white/10'}}">{{ trans_db('sections', 'home', false) ?: 'Home' }}</a>
                <a href="{{ route('menu', ['locale' => $currentLocale]) }}" class="text-white font-medium px-4 py-2 rounded-md {{ request()->routeIs('menu') ? 'bg-white/20' : 'hover:bg-white/10'}}">{{ trans_db('sections', 'menu', false) ?: 'Menu' }}</a>
                <a href="{{ route('about', ['locale' => $currentLocale]) }}" class="text-white font-medium px-4 py-2 rounded-md {{ request()->routeIs('about') ? 'bg-white/20' : 'hover:bg-white/10'}}">{{ trans_db('sections', 'about_us', false) ?: 'About Us' }}</a>
                <a href="{{ route('contact', ['locale' => $currentLocale]) }}" class="text-white font-medium px-4 py-2 rounded-md {{ request()->routeIs('contact') ? 'bg-white/20' : 'hover:bg-white/10'}}">{{ trans_db('sections', 'contact', false) ?: 'Contact' }}</a>
            </nav>
            
            <!-- Right Side Actions -->
            <div class="flex items-center space-x-1 sm:space-x-3">
                <!-- Theme Toggle -->
                <div class="relative">
                    <button id="themeToggle" class="flex items-center bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-md transition-colors">
                        <i class="fas fa-moon" id="darkIcon"></i>
                        <i class="fas fa-sun hidden" id="lightIcon"></i>
                        <span class="ml-1.5 hidden sm:inline-block">{{ trans_db('sections', 'mode', false) ?: 'Mode' }}</span>
                    </button>
                </div>
                
                <!-- Language Selector -->
                <div class="relative group">
                    <button id="langToggle" class="flex items-center bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-md transition-colors">
                        @foreach(get_languages() as $language)
                            @if($language->code == $currentLocale)
                                <span class="hidden sm:inline-block mr-1.5">{{ strtoupper($language->code) }}</span>
                            @endif
                        @endforeach
                        <i class="fas fa-globe"></i>
                    </button>
                    <div id="langDropdown" class="lang-dropdown z-50 w-36">
                        <div class="py-1">
                            @foreach(get_languages() as $language)
                                <a href="{{ route('language.change', ['locale' => $language->code]) ?? "" }}" class="dropdown-item flex items-center">
                                    <span class="mr-2">{{ $language->flag }}</span> 
                                    <span>{{ $language->native_name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Cart -->
                <div class="relative group">
                    <button id="cartToggle" class="flex items-center bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-md transition-colors">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="ml-1.5 hidden sm:inline-block">{{ trans_db('sections', 'cart', false) ?: 'Cart' }}</span>
                        <span class="absolute -top-2 -right-2 bg-aisuki-yellow text-aisuki-red text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center cart-count">0</span>
                    </button>
                    
                    <!-- Mini Cart Dropdown -->
                    <div id="miniCart" class="mini-cart">
                        <div class="p-4 border-b border-theme">
                            <h3 class="font-bold text-lg text-theme-primary">{{ trans_db('sections', 'your_cart', false) ?: 'Giỏ hàng' }} (<span class="cart-count">0</span>)</h3>
                        </div>
                        
                        <div class="mini-cart-items max-h-80 overflow-y-auto">
                            <!-- Nội dung giỏ hàng sẽ được điền bởi JavaScript -->
                            <div class="p-6 text-center">
                                <i class="fas fa-shopping-cart text-3xl mb-2 text-theme-secondary"></i>
                                <p class="text-theme-secondary">{{ trans_db('sections', 'cart_empty', false) ?: 'Giỏ hàng trống' }}</p>
                            </div>
                        </div>
                        
                        <div class="p-4 border-t border-theme">
                            <div class="flex justify-between mb-3">
                                <span class="font-medium text-theme-primary">{{ trans_db('sections', 'total', false) ?: 'Tổng cộng' }}:</span>
                                <span class="font-bold text-aisuki-red mini-cart-subtotal">0,00 {{ setting('currency', '€') }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('cart', ['locale' => app()->getLocale()]) }}" class="text-center py-2 px-4 border border-aisuki-red text-aisuki-red rounded-md hover:bg-aisuki-red hover:text-white transition-colors">
                                    {{ trans_db('sections', 'view_cart', false) ?: 'Xem giỏ hàng' }}
                                </a>
                                <a href="{{ route('checkout', ['locale' => app()->getLocale()]) }}" class="text-center py-2 px-4 bg-aisuki-red text-white rounded-md hover:bg-aisuki-red-dark transition-colors mini-cart-checkout opacity-50 pointer-events-none">
                                    {{ trans_db('sections', 'checkout', false) ?: 'Đặt hàng' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Hotline Button -->
                <a href="tel:{{ setting('phone') }}" class="hidden md:flex items-center bg-white text-aisuki-red px-4 py-2 rounded-md font-semibold hover:bg-aisuki-yellow transition-colors">
                    <i class="fas fa-phone-alt mr-2"></i>
                    <span>{{ setting('phone') }}</span>
                </a>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden flex items-center bg-white/20 hover:bg-white/3 text-white px-3 py-2 rounded-md transition-colors" id="mobileMenu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Sidebar Menu -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="sidebar shadow-2xl" id="sidebar">
    <div class="px-4 py-3 flex justify-between items-center border-b border-theme bg-aisuki-red text-white">
        <div class="flex items-center">
            <img src="{{ asset(setting('site_logo', 'logo.png')) }}" alt="{{ setting('site_name', 'AISUKI') }} Logo" class="h-10 scale-[1.2]">
            <h2 class="text-xl font-brand ml-2">{{ setting('site_name', 'AISUKI') }}</h2>
        </div>
        <button id="closeSidebar" class="text-2xl text-white hover:text-aisuki-yellow">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Theme Toggle in Mobile Sidebar -->
    <div class="p-4 border-b border-theme">
        <div class="text-sm text-theme-secondary mb-2">{{ trans_db('sections', 'display_mode', false) ?: 'Display Mode' }}</div>
        <div class="flex space-x-2">
            <button id="mobileLightTheme" class="flex-1 py-2 border-b-2 border-aisuki-red text-center font-medium text-theme-primary">
                <i class="fas fa-sun mr-2"></i> {{ trans_db('sections', 'light', false) ?: 'Light' }}
            </button>
            <button id="mobileDarkTheme" class="flex-1 py-2 border-b-2 border-transparent text-center font-medium text-theme-secondary">
                <i class="fas fa-moon mr-2"></i> {{ trans_db('sections', 'dark', false) ?: 'Dark' }}
            </button>
        </div>
    </div>
    
    <!-- Mobile Cart Summary -->
    <div class="bg-theme-secondary px-4 py-2 flex justify-between items-center" id="mobileSummaryCart">
        <div>
            <div class="font-medium text-theme-primary">{{ trans_db('sections', 'your_cart', false) ?: 'Giỏ hàng' }}</div>
            <div class="text-sm text-theme-secondary">
                <span class="cart-count">0</span> {{ trans_db('sections', 'items', false) ?: 'món' }} - 
                <span class="mobile-cart-subtotal">{{ setting('currency', '€') }}0,00</span>
            </div>
        </div>
        <a href="{{ route('cart', ['locale' => app()->getLocale()]) }}" class="bg-aisuki-red text-white px-3 py-1.5 rounded-md text-sm">
            {{ trans_db('sections', 'view_cart', false) ?: 'Xem giỏ hàng' }}
        </a>
    </div>
    
    <!-- Language Selector Mobile -->
    <div class="p-4 border-b border-theme">
        <div class="text-sm text-theme-secondary mb-2">{{ trans_db('sections', 'language', false) ?: 'Language' }}</div>
        <div class="flex space-x-2">
            @foreach(get_languages() as $language)
                <a href="{{ route('language.change', ['locale' => $language->code]) ?? "" }}" 
                class="flex-1 py-2 border-b-2 {{ app()->getLocale() == $language->code ? 'border-aisuki-red text-aisuki-red' : 'border-transparent text-theme-secondary hover:text-theme-primary' }} text-center font-medium">
                    <span class="mr-1">{{ $language->flag }}</span> {{ $language->native_name }}
                </a>
            @endforeach
        </div>
    </div>
    
    <div class="p-4">
        <ul class="mb-6 border-b border-theme pb-6">
            <li class="mb-1">
                <a href="{{ route('home', ['locale' => $currentLocale]) }}" class="flex items-center p-3 rounded-md {{ request()->routeIs('home') ? 'text-aisuki-red bg-theme-secondary' : 'text-theme-primary hover:text-aisuki-red hover:bg-theme-secondary'}}">
                    <i class="fas fa-home mr-3 w-5 text-center text-aisuki-red"></i> {{ trans_db('sections', 'home', false) ?: 'Home' }}
                </a>
            </li>
            <li class="mb-1">
                <a href="{{ route('menu', ['locale' => $currentLocale]) }}" class="flex items-center p-3 rounded-md {{ request()->routeIs('menu') ? 'text-aisuki-red bg-theme-secondary' : 'text-theme-primary hover:text-aisuki-red hover:bg-theme-secondary'}}">
                    <i class="fas fa-utensils mr-3 w-5 text-center text-aisuki-red"></i> {{ trans_db('sections', 'menu', false) ?: 'Menu' }}
                </a>
            </li>
            <li class="mb-1">
                <a href="{{ route('about', ['locale' => $currentLocale]) }}" class="flex items-center p-3 rounded-md {{ request()->routeIs('about') ? 'text-aisuki-red bg-theme-secondary' : 'text-theme-primary hover:text-aisuki-red hover:bg-theme-secondary'}}">
                    <i class="fas fa-home mr-3 w-5 text-center text-aisuki-red"></i> {{ trans_db('sections', 'about_us', false) ?: 'About Us' }}
                </a>
            </li>
            <li class="mb-1">
                <a href="{{ route('contact', ['locale' => $currentLocale]) }}" class="flex items-center p-3 rounded-md {{ request()->routeIs('contact') ? 'text-aisuki-red bg-theme-secondary' : 'text-theme-primary hover:text-aisuki-red hover:bg-theme-secondary'}}">
                    <i class="fas fa-envelope mr-3 w-5 text-center text-aisuki-red"></i> {{ trans_db('sections', 'contact', false) ?: 'Contact' }}
                </a>
            </li>
        </ul>
        
        <div class="mb-6 border-b border-theme pb-6">
            <h3 class="text-lg font-semibold mb-4 text-aisuki-red">{{ trans_db('sections', 'contact_info', false) ?: 'Contact Us' }}</h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <i class="fas fa-phone-alt text-aisuki-red mt-1 mr-3 w-5 text-center"></i>
                    <div>
                        <p class="font-medium text-theme-primary">{{ trans_db('sections', 'call_hotline', false) ?: 'Hotline' }}</p>
                        <p class="text-theme-secondary">{{ setting('phone') }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-clock text-aisuki-red mt-1 mr-3 w-5 text-center"></i>
                    <div>
                        <p class="font-medium text-theme-primary">{{ trans_db('sections', 'quick_contact_hours_title', false) ?: 'Opening Hours' }}</p>
                        <p class="text-theme-secondary">{!! nl2br(str_replace(["\\r\\n", "\\n"], "<br />", $currentLocale == 'en' ? setting('opening_hours') : trans_db('settings', 'opening_hours', false))) !!}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-map-marker-alt text-aisuki-red mt-1 mr-3 w-5 text-center"></i>
                    <div>
                        <p class="font-medium text-theme-primary">{{ trans_db('sections', 'quick_contact_address_title', false) ?: 'Address' }}</p>
                        <p class="text-theme-secondary">{{ $currentLocale == 'en' ? setting('address') : trans_db('settings', 'address', false) }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4 text-aisuki-red">{{ trans_db('sections', 'follow_us', false) ?: 'Follow Us' }}</h3>
            <div class="flex gap-3">
                @if(setting('facebook'))
                    <a href="{{ setting('facebook') }}" target="_blank" class="w-10 h-10 bg-theme-secondary rounded-full flex items-center justify-center text-theme-secondary hover:bg-aisuki-red hover:text-white transition duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                @endif
                
                @if(setting('instagram'))
                    <a href="{{ setting('instagram') }}" target="_blank" class="w-10 h-10 bg-theme-secondary rounded-full flex items-center justify-center text-theme-secondary hover:bg-aisuki-red hover:text-white transition duration-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                @endif
                
                @if(setting('twitter'))
                    <a href="{{ setting('twitter') }}" target="_blank" class="w-10 h-10 bg-theme-secondary rounded-full flex items-center justify-center text-theme-secondary hover:bg-aisuki-red hover:text-white transition duration-300">
                        <i class="fab fa-twitter"></i>
                    </a>
                @endif
                
                @if(setting('youtube'))
                    <a href="{{ setting('youtube') }}" target="_blank" class="w-10 h-10 bg-theme-secondary rounded-full flex items-center justify-center text-theme-secondary hover:bg-aisuki-red hover:text-white transition duration-300">
                        <i class="fab fa-youtube"></i>
                    </a>
                @endif
            </div>
        </div>
        
        <a href="tel:{{ setting('phone') }}" class="block w-full bg-aisuki-red text-white py-3 px-6 rounded-md font-semibold text-center hover:bg-opacity-90 transition duration-300">
            <i class="fas fa-phone-alt mr-2"></i> {{ trans_db('sections', 'call_now', false) ?: 'Call Now' }}
        </a>
    </div>
</div>