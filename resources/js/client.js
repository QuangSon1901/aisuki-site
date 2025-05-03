import './bootstrap';
import './cart';
import './announcements';

// public/client/js/global.js
$(document).ready(function() {
    // Theme switching functionality
    const htmlElement = document.documentElement;
    const darkIcon = document.getElementById('darkIcon');
    const lightIcon = document.getElementById('lightIcon');

    const getCurrentTheme = () => {
        const savedTheme = localStorage.getItem('aisuki-theme');
        if (savedTheme) return savedTheme;
        if (window.settings.theme_mode && window.settings.theme_mode != 'system') return window.settings.theme_mode;
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    const applyTheme = (theme) => {
        htmlElement.setAttribute('data-theme', theme);
        localStorage.setItem('aisuki-theme', theme);
        
        if (theme === 'dark') {
            darkIcon.classList.add('hidden');
            lightIcon.classList.remove('hidden');
            $("#mobileLightTheme").removeClass('border-aisuki-red text-aisuki-red').addClass('border-transparent text-theme-secondary');
            $("#mobileDarkTheme").removeClass('border-transparent text-theme-secondary').addClass('border-aisuki-red text-aisuki-red');
        } else {
            darkIcon.classList.remove('hidden');
            lightIcon.classList.add('hidden');
            $("#mobileLightTheme").removeClass('border-transparent text-theme-secondary').addClass('border-aisuki-red text-aisuki-red');
            $("#mobileDarkTheme").removeClass('border-aisuki-red text-aisuki-red').addClass('border-transparent text-theme-secondary');
        }
    };

    // Initialize theme
    applyTheme(getCurrentTheme());

    // Toggle theme
    $('#themeToggle').click(function() {
        const currentTheme = htmlElement.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        applyTheme(newTheme);
    });

    // Mobile theme toggles
    $('#mobileLightTheme').click(function() {
        applyTheme('light');
    });

    $('#mobileDarkTheme').click(function() {
        applyTheme('dark');
    });

    // Mobile menu toggle
    $('#mobileMenu').click(function() {
        $('#sidebar').addClass('active');
        $('#sidebarOverlay').addClass('active');
        $('body').addClass('overflow-hidden');
    });

    // Close sidebar
    $('#closeSidebar, #sidebarOverlay, #sidebar a').click(function() {
        $('#sidebar').removeClass('active');
        $('#sidebarOverlay').removeClass('active');
        $('body').removeClass('overflow-hidden');
    });

    $('#langToggle').click(function(e) {
        e.stopPropagation();
        $('#langDropdown').toggleClass('active');
        $('#miniCart').removeClass('active'); // Close cart if open
    });

    $('#cartToggle').click(function(e) {
        e.stopPropagation();
        $('#miniCart').toggleClass('active');
        $('#langDropdown').removeClass('active'); // Close language if open
    });

    // Close dropdowns when clicking elsewhere
    $(document).click(function() {
        $('#langDropdown, #miniCart').removeClass('active');
    });

    // Scroll handling
    let lastScrollTop = 0;
    const header = $('header');
    const categoryNav = $('.sticky-category-nav');
    let headerHeight = header.outerHeight();
    let isHeaderVisible = true;

    function updateStickyNav() {
        if (isHeaderVisible) {
            categoryNav.css('top', headerHeight + 'px');
        } else {
            categoryNav.css('top', '0');
        }
    }

    $(window).scroll(function() {
        let scrollTop = $(window).scrollTop();
        
        // Header visibility
        if (scrollTop > lastScrollTop && scrollTop > 200) {
            header.css('transform', 'translateY(-100%)');
            isHeaderVisible = false;
        } else {
            header.css('transform', 'translateY(0)');
            isHeaderVisible = true;
        }
        
        updateStickyNav();
        
        // Mobile CTA visibility
        if (scrollTop > 300 && $(window).width() < 768) {
            $('#mobileCta').css('transform', 'translateY(0)');
        } else if (scrollTop <= 300 && $(window).width() < 768) {
            $('#mobileCta').css('transform', 'translateY(100%)');
        }
        
        // Scroll To Top button visibility
        if (scrollTop > 600) {
            $('#scrollToTop').removeClass('opacity-0 invisible');
        } else {
            $('#scrollToTop').addClass('opacity-0 invisible');
        }
        
        lastScrollTop = scrollTop;
    });

    // Update on resize
    $(window).resize(function() {
        headerHeight = header.outerHeight();
        updateStickyNav();
    });

    // Initial setup
    $(document).ready(function() {
        updateStickyNav();
    });

    // Scroll to top functionality
    $('#scrollToTop').click(function() {
        $('html, body').animate({
            scrollTop: 0
        }, 800);
        return false;
    });

    // Smooth scroll for anchor links
    $('a[href^="#"]').click(function(e) {
        e.preventDefault();
        
        let target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 70
            }, 800);
        }
    });

    // Initialize everything visible on mobile after page load
    if ($(window).scrollTop() > 300 && $(window).width() < 768) {
        $('#mobileCta').css('transform', 'translateY(0)');
    }
});