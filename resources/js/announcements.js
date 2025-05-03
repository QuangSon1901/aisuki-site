/**
 * AISUKI Announcements System
 * Handles display and management of restaurant announcements
 */

// Define the Announcements object
const Announcements = {
    // DOM Elements
    elements: {
        modal: null,
        overlay: null,
        closeBtn: null,
        slider: null,
        prevBtn: null,
        nextBtn: null,
        dots: null,
        toggleBtn: null,
        dismissBtn: null,
    },
    
    // Configuration
    config: {
        cookieName: 'aisuki_announcements_dismissed',
        cookieDuration: 24, // hours
        autoShowDelay: 1000, // milliseconds
    },
    
    // Data
    data: {
        announcements: [],
        currentIndex: 0,
        isDismissed: false,
        isLoading: true,
    },
    
    // Initialize the announcements system
    init() {
        this.cacheElements();
        this.attachEventListeners();
        this.checkDismissalCookie();
        this.fetchAnnouncements();
    },
    
    // Cache DOM elements for better performance
    cacheElements() {
        this.elements.modal = document.getElementById('announcementModal');
        this.elements.overlay = document.getElementById('announcementModalOverlay');
        this.elements.closeBtn = document.getElementById('closeAnnouncementModal');
        this.elements.slider = document.getElementById('announcementSlider');
        this.elements.prevBtn = document.getElementById('prevAnnouncement');
        this.elements.nextBtn = document.getElementById('nextAnnouncement');
        this.elements.dots = document.getElementById('announcementDots');
        this.elements.toggleBtn = document.getElementById('announcementToggle');
        this.elements.dismissBtn = document.getElementById('dismissFor24h');
        this.elements.navContainer = document.getElementById('announcementSliderNav');
        this.elements.actionsContainer = document.getElementById('announcementActions');
    },
    
    // Attach event listeners
    attachEventListeners() {
        // Close button
        this.elements.closeBtn.addEventListener('click', () => {
            this.closeModal();
            this.dismissFor24Hours();
        });
        
        // Overlay click
        this.elements.overlay.addEventListener('click', () => {
            this.closeModal();
            this.dismissFor24Hours();
        });
        
        // Previous button
        this.elements.prevBtn.addEventListener('click', () => {
            this.navigateSlide('prev');
        });
        
        // Next button
        this.elements.nextBtn.addEventListener('click', () => {
            this.navigateSlide('next');
        });
        
        // Toggle button
        this.elements.toggleBtn.addEventListener('click', () => {
            this.openModal();
        });
        
        // Dismiss for 24h button
        this.elements.dismissBtn.addEventListener('click', () => {
            this.dismissFor24Hours();
        });
        
        // Key events (escape to close)
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isModalOpen()) {
                this.closeModal();
            }
        });
    },
    
    // Check if user has dismissed the announcements
    checkDismissalCookie() {
        const dismissedCookie = this.getCookie(this.config.cookieName);
        this.data.isDismissed = dismissedCookie ? true : false;
    },
    
    // Fetch announcements from the server
fetchAnnouncements() {
    this.data.isLoading = true;
    
    // Get current locale from HTML lang attribute
    const locale = document.documentElement.lang || 'en';
    
    fetch(`/api/announcements?locale=${locale}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.announcements.length > 0) {
                console.log(`Loaded ${data.announcements.length} announcements for locale: ${data.locale}`);
                this.data.announcements = data.announcements;
                this.renderAnnouncements();
                this.showToggleButton();
                
                // Auto-show the modal if not dismissed
                if (!this.data.isDismissed) {
                    setTimeout(() => {
                        this.openModal();
                    }, this.config.autoShowDelay);
                }
            } else {
                // No announcements, hide the toggle button
                this.elements.toggleBtn.style.display = 'none';
                console.log('No announcements found or error loading announcements');
            }
        })
        .catch(error => {
            console.error('Error fetching announcements:', error);
            // Hide toggle button on error
            this.elements.toggleBtn.style.display = 'none';
        })
        .finally(() => {
            this.data.isLoading = false;
        });
},
    
    renderAnnouncements() {
        // Clear loading message and prepare container
        this.elements.slider.innerHTML = '';
        
        // Create slides
        this.data.announcements.forEach((announcement, index) => {
            const slide = document.createElement('div');
            slide.className = `announcement-slide ${index === 0 ? 'active' : ''}`;
            slide.innerHTML = `
                <h3 class="announcement-slide-title">${announcement.title}</h3>
                <div class="announcement-slide-content">${announcement.content}</div>
            `;
            this.elements.slider.appendChild(slide);
            
            // Create dot for navigation
            const dot = document.createElement('div');
            dot.className = `dot ${index === 0 ? 'active' : ''}`;
            dot.dataset.index = index;
            dot.addEventListener('click', () => {
                this.goToSlide(index);
            });
            this.elements.dots.appendChild(dot);
        });
        
        // Update badge number
        const badgeElement = document.getElementById('announcementBadge');
        if (badgeElement) {
            badgeElement.textContent = this.data.announcements.length;
            badgeElement.style.display = this.data.announcements.length > 0 ? 'flex' : 'none';
        }
        
        // Show/hide navigation elements based on announcement count
        if (this.data.announcements.length <= 1) {
            this.elements.navContainer.style.display = 'none';
        } else {
            this.elements.navContainer.style.display = 'flex';
            this.updateNavigationState();
        }
        
        // Show/hide actions based on announcements dismissibility
        const hasDismissible = this.data.announcements.some(a => a.is_dismissible);
        if (!hasDismissible) {
            this.elements.actionsContainer.style.display = 'none';
        } else {
            this.elements.actionsContainer.style.display = 'flex';
        }
    },
    
    // Navigate to previous or next slide
    navigateSlide(direction) {
        if (direction === 'prev' && this.data.currentIndex > 0) {
            this.goToSlide(this.data.currentIndex - 1);
        } else if (direction === 'next' && this.data.currentIndex < this.data.announcements.length - 1) {
            this.goToSlide(this.data.currentIndex + 1);
        }
    },
    
    // Go to a specific slide by index
    goToSlide(index) {
        // Update current index
        this.data.currentIndex = index;
        
        // Update slides
        const slides = this.elements.slider.querySelectorAll('.announcement-slide');
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        
        // Update dots
        const dots = this.elements.dots.querySelectorAll('.dot');
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
        
        // Update navigation buttons
        this.updateNavigationState();
    },
    
    // Update the state of navigation buttons
    updateNavigationState() {
        this.elements.prevBtn.disabled = this.data.currentIndex === 0;
        this.elements.nextBtn.disabled = this.data.currentIndex === this.data.announcements.length - 1;
    },
    
    // Show the announcement toggle button
    showToggleButton() {
        setTimeout(() => {
            this.elements.toggleBtn.classList.add('show');
        }, 1000);
    },
    
    // Open the announcements modal
    openModal() {
        this.elements.modal.classList.add('active');
        this.elements.overlay.classList.add('active');
        document.body.classList.add('overflow-hidden');
    },
    
    // Close the announcements modal
    closeModal() {
        this.elements.modal.classList.remove('active');
        this.elements.overlay.classList.remove('active');
        document.body.classList.remove('overflow-hidden');
    },
    
    // Check if modal is currently open
    isModalOpen() {
        return this.elements.modal.classList.contains('active');
    },
    
    // Dismiss announcements for 24 hours
    dismissFor24Hours() {
        // Set a cookie that expires in 24 hours
        this.setCookie(
            this.config.cookieName, 
            'dismissed', 
            this.config.cookieDuration
        );
        
        // Mark as dismissed and close the modal
        this.data.isDismissed = true;
        this.closeModal();
    },
    
    // Set a cookie with name, value, and expiration hours
    setCookie(name, value, hours) {
        const date = new Date();
        date.setTime(date.getTime() + (hours * 60 * 60 * 1000));
        const expires = `expires=${date.toUTCString()}`;
        document.cookie = `${name}=${value};${expires};path=/`;
    },
    
    // Get a cookie value by name
    getCookie(name) {
        const cname = `${name}=`;
        const decodedCookie = decodeURIComponent(document.cookie);
        const ca = decodedCookie.split(';');
        
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(cname) === 0) {
                return c.substring(cname.length, c.length);
            }
        }
        
        return '';
    }
};

// Initialize the announcements system when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    Announcements.init();
});

// Export for potential use in other modules
export default Announcements;