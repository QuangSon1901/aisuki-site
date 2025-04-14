<footer class="bg-footer-bg text-footer-text py-12 sm:py-16 px-4" id="contact">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
            <div>
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="inline-block mb-6">
                    <img src="{{ asset(setting('logo', 'logo.png')) }}" alt="{{ setting('site_name', 'AISUKI') }} Logo" class="h-12">
                </a>
                <p class="text-gray-400 text-sm font-brand mb-4">
                    {{ trans_db('sections', 'footer_about_text', false) ?: 'AISUKI is an authentic Japanese restaurant, bringing diners the true culinary experiences of the cherry blossom country.' }}
                </p>
                <div class="flex gap-3 mt-6">
                    @if(setting('facebook'))
                        <a href="{{ setting('facebook') }}" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center text-white hover:bg-aisuki-red hover:-translate-y-1 transition-all">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    
                    @if(setting('instagram'))
                        <a href="{{ setting('instagram') }}" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center text-white hover:bg-aisuki-red hover:-translate-y-1 transition-all">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    
                    @if(setting('twitter'))
                        <a href="{{ setting('twitter') }}" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center text-white hover:bg-aisuki-red hover:-translate-y-1 transition-all">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    
                    @if(setting('youtube'))
                        <a href="{{ setting('youtube') }}" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center text-white hover:bg-aisuki-red hover:-translate-y-1 transition-all">
                            <i class="fab fa-youtube"></i>
                        </a>
                    @endif
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-6 pb-2 border-b border-aisuki-red inline-block text-aisuki-red">{{ trans_db('sections', 'quick_links', false) ?: 'Quick Links' }}</h3>
                <ul class="space-y-2">
                    <li><a href="#home" class="text-gray-400 hover:text-aisuki-red hover:pl-1 transition-all text-sm">{{ trans_db('sections', 'home', false) ?: 'Home' }}</a></li>
                    <li><a href="#about" class="text-gray-400 hover:text-aisuki-red hover:pl-1 transition-all text-sm">{{ trans_db('sections', 'about_us', false) ?: 'About Us' }}</a></li>
                    <li><a href="#menu" class="text-gray-400 hover:text-aisuki-red hover:pl-1 transition-all text-sm">{{ trans_db('sections', 'menu', false) ?: 'Menu' }}</a></li>
                    <li><a href="#contact" class="text-gray-400 hover:text-aisuki-red hover:pl-1 transition-all text-sm">{{ trans_db('sections', 'contact', false) ?: 'Contact' }}</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-6 pb-2 border-b border-aisuki-red inline-block text-aisuki-red">{{ trans_db('sections', 'contact_info', false) ?: 'Contact Info' }}</h3>
                <div class="space-y-3">
                    <div class="flex">
                        <i class="fas fa-map-marker-alt text-aisuki-red mt-1 mr-3"></i>
                        <span class="text-gray-400 text-sm">{{ setting('address') }}</span>
                    </div>
                    <div class="flex">
                        <i class="fas fa-phone-alt text-aisuki-red mt-1 mr-3"></i>
                        <span class="text-gray-400 text-sm">{{ setting('phone') }}</span>
                    </div>
                    <div class="flex">
                        <i class="fas fa-envelope text-aisuki-red mt-1 mr-3"></i>
                        <span class="text-gray-400 text-sm">{{ setting('email') }}</span>
                    </div>
                    <div class="flex">
                        <i class="fas fa-clock text-aisuki-red mt-1 mr-3"></i>
                        <span class="text-gray-400 text-sm">{{ setting('opening_hours') }}</span>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-6 pb-2 border-b border-aisuki-red inline-block text-aisuki-red">{{ trans_db('sections', 'newsletter', false) ?: 'Newsletter' }}</h3>
                <p class="text-gray-400 text-sm mb-4">{{ trans_db('sections', 'newsletter_text', false) ?: 'Subscribe to receive information about promotions and latest updates from AISUKI' }}</p>
                <div class="mb-4">
                    <input type="email" placeholder="{{ trans_db('sections', 'email_placeholder', false) ?: 'Your email' }}" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-gray-300 focus:outline-none focus:border-aisuki-red">
                </div>
                <button class="bg-aisuki-red text-white py-2 px-6 rounded-full font-semibold hover:bg-[#c41017] transition-all">{{ trans_db('sections', 'subscribe', false) ?: 'Subscribe' }}</button>
            </div>
        </div>
        <div class="pt-6 border-t border-gray-800 text-center">
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} {{ setting('site_name', 'AISUKI') }}. {{ trans_db('sections', 'all_rights_reserved', false) ?: 'All rights reserved.' }}</p>
        </div>
    </div>
</footer>