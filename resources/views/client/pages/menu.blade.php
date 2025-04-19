@extends('client.layouts.app')

@section('content')
    <!-- Menu Title Section -->
    <section class="pt-8 pb-4 bg-theme-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl font-bold text-aisuki-red">{{ trans_db('sections', 'menu_page_title', false) ?: 'Our Menu' }}</h1>
                <p class="text-theme-secondary mt-2">{{ trans_db('sections', 'menu_page_subtitle', false) ?: 'Discover authentic Japanese flavors' }}</p>
            </div>
        </div>
    </section>

    <!-- Category Filters -->
    <section class="py-4 bg-theme-primary border-b border-theme sticky-category-nav z-20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="overflow-x-auto category-tabs hide-scrollbar">
                <div class="inline-flex space-x-2 min-w-full">
                    <button class="active-tab whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium border transition-colors" data-target="all">
                        {{ trans_db('sections', 'all_categories', false) ?: 'All Categories' }}
                    </button>
                    
                    @foreach($categories as $category)
                        @if(isset($menuItems[$category->slug]) && count($menuItems[$category->slug]) > 0)
                        <button class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium border border-theme text-theme-primary hover:bg-theme-secondary transition-colors" data-target="{{ $category->slug }}">
                            {{ $category->name }}
                        </button>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Items Section -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <!-- Loop through categories -->
            @foreach($categories as $category)
                @if(isset($menuItems[$category->slug]) && count($menuItems[$category->slug]) > 0)
                <div class="menu-category mb-10" id="category-{{ $category->slug }}">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-theme-primary">{{ $category->name }}</h2>
                    </div>
                    
                    <!-- Desktop Menu Grid - Hidden on mobile -->
                    <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($menuItems[$category->slug] as $item)
                        <div class="card rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all">
                            <div class="relative">
                                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
                                <div class="absolute top-0 left-0 bg-aisuki-red text-white px-2 py-1 text-xs font-bold">{{ $item->code }}</div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-1 text-theme-primary truncate-none">{{ $item->name }}</h3>
                                <p class="text-theme-secondary text-sm mb-3 line-clamp-2">{{ $item->description }}</p>
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-aisuki-red font-bold text-lg">{{ number_format($item->price, 2, ',', '.') }} {{ setting('currency', '€') }}</span>
                                </div>
                                <button class="w-full bg-aisuki-red text-white py-2 px-4 rounded-full font-medium hover:bg-aisuki-red-dark transition-colors order-btn" 
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
                    
                    <!-- Mobile Menu List - Visible only on small screens -->
                    <div class="md:hidden space-y-4">
                        @foreach($menuItems[$category->slug] as $item)
                        <div class="card rounded-lg overflow-hidden shadow-md flex h-28">
                            <div class="w-1/3 bg-cover bg-center relative" style="background-image: url('{{ asset($item->image) }}')">
                                <div class="absolute top-1 left-1 bg-aisuki-red text-white px-1.5 py-0.5 text-xs font-bold">
                                    {{ $item->code }}
                                </div>
                            </div>
                            <div class="w-2/3 p-3 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-semibold text-sm text-theme-primary truncate-none">{{ $item->name }}</h3>
                                    <p class="text-theme-secondary text-xs mt-1 line-clamp-2">{{ $item->description }}</p>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-aisuki-red font-bold text-sm">{{ number_format($item->price, 2, ',', '.') }} {{ setting('currency', '€') }}</span>
                                    <button class="bg-aisuki-red text-white py-1.5 px-3 rounded-full text-xs font-medium hover:bg-aisuki-red-dark transition-colors order-btn" 
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
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </section>
    
    <!-- Product Detail Modal -->
    @include('client.components.food-order-modal')
@endsection

@push('styles')
<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    .category-tabs .active-tab {
        background-color: #e61c23;
        color: white;
        border-color: #e61c23;
    }
    
    .truncate-none {
        overflow: visible;
        text-overflow: initial;
        white-space: normal;
    }
    
    .sticky-category-nav {
        position: sticky;
        top: 0;
        transition: top 0.3s;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Category tab switching
        $('.category-tabs button').click(function() {
            $('.category-tabs button').removeClass('active-tab').addClass('border-theme text-theme-primary hover:bg-theme-secondary');
            $(this).addClass('active-tab').removeClass('border-theme text-theme-primary hover:bg-theme-secondary');
            
            const targetCategory = $(this).data('target');
            
            if (targetCategory === 'all') {
                $('.menu-category').show();
            } else {
                $('.menu-category').hide();
                $('#category-' + targetCategory).show();
            }
        });
        
        // Fix sticky header issue
        const header = $('header');
        const categoriesNav = $('.sticky-category-nav');
        let headerHeight = header.outerHeight();
        let isHeaderVisible = true;
        
        function updateStickyNav() {
            if (isHeaderVisible) {
                categoriesNav.css('top', headerHeight + 'px');
            } else {
                categoriesNav.css('top', '0');
            }
        }
        
        // Initial setup
        updateStickyNav();
        
        // Monitor header visibility changes
        let lastScrollTop = 0;
        $(window).scroll(function() {
            let scrollTop = $(window).scrollTop();
            
            // Check if header is visible
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                // Scrolling down, header is hidden
                isHeaderVisible = false;
            } else {
                // Scrolling up, header is visible
                isHeaderVisible = true;
            }
            
            updateStickyNav();
            lastScrollTop = scrollTop;
        });
        
        // Update on resize
        $(window).resize(function() {
            headerHeight = header.outerHeight();
            updateStickyNav();
        });
        
        // NEW CODE: Check URL hash on page load to activate the correct category tab
        function activateCategoryFromHash() {
            if (window.location.hash) {
                // Extract the category slug from the hash (e.g., #category-sushi -> sushi)
                const categorySlug = window.location.hash.substring(1).replace('category-', '');
                
                // Find the corresponding category tab
                const categoryTab = $(`.category-tabs button[data-target="${categorySlug}"]`);
                
                if (categoryTab.length) {
                    // Activate the category tab
                    categoryTab.click();
                    
                    // Calculate scroll offset considering sticky header
                    const headerHeight = $('header').outerHeight() || 0;
                    const categoriesNavHeight = $('.sticky-category-nav').outerHeight() || 0;
                    const scrollOffset = headerHeight + categoriesNavHeight + 20;
                    
                    // Smooth scroll to the category section
                    setTimeout(function() {
                        $('html, body').animate({
                            scrollTop: $(window.location.hash).offset().top - scrollOffset
                        }, 500);
                    }, 100);
                }
            }
        }
        
        // Activate category from URL hash when page loads
        activateCategoryFromHash();
        
        // Also handle hash changes during navigation
        $(window).on('hashchange', function() {
            activateCategoryFromHash();
        });
    });
</script>
@endpush