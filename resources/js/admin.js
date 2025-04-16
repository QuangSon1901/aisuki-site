// resources/js/admin.js
import './bootstrap';

$(document).ready(function() {
    // Sidebar toggle functionality
    const $adminContainer = $('.admin-container');
    const $sidebar = $('.sidebar');
    const $sidebarOverlay = $('#sidebarOverlay');
    
    // Check for saved state (only apply on desktop)
    if ($(window).width() >= 768) {
        const sidebarState = localStorage.getItem('sidebar-collapsed');
        if (sidebarState === 'true') {
            $adminContainer.addClass('sidebar-collapsed');
        }
    }
    
    // Toggle sidebar on desktop
    $('#sidebarToggle').click(function() {
        if ($(window).width() >= 768) {
            // Desktop behavior - collapse sidebar
            $adminContainer.toggleClass('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', $adminContainer.hasClass('sidebar-collapsed'));
        } else {
            // Mobile behavior - show sidebar as overlay
            $sidebar.addClass('show');
            $sidebarOverlay.addClass('show');
            $('body').addClass('sidebar-open');
        }
    });
    
    // Close sidebar on mobile
    $('#sidebarClose, #sidebarOverlay').click(function() {
        $sidebar.removeClass('show');
        $sidebarOverlay.removeClass('show');
        $('body').removeClass('sidebar-open');
    });
    
    // Handle window resize
    $(window).resize(function() {
        if ($(window).width() >= 768) {
            // Reset mobile sidebar state when resizing to desktop
            $sidebar.removeClass('show');
            $sidebarOverlay.removeClass('show');
            $('body').removeClass('sidebar-open');
        }
    });
    
    // Close sidebar when clicking a menu item on mobile
    $('.sidebar .nav-link').click(function() {
        if ($(window).width() < 768) {
            $sidebar.removeClass('show');
            $sidebarOverlay.removeClass('show');
            $('body').removeClass('sidebar-open');
        }
    });
    
    // Auto close alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
    
    // Image preview functionality
    $('.image-preview-input').change(function() {
        const previewId = $(this).data('preview');
        const $preview = $('#' + previewId);
        
        if ($preview.length && this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                $preview.attr('src', e.target.result).show();
            };
            
            reader.readAsDataURL(this.files[0]);
        }
    });
});