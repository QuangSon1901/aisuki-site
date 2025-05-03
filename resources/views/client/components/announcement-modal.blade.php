<!-- Announcement Modal -->
<div id="announcementModalOverlay" class="announcement-modal-overlay"></div>

<div id="announcementModal" class="announcement-modal">
    <div class="announcement-modal-content">
        <button id="closeAnnouncementModal" class="announcement-modal-close-btn">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Announcement Slider -->
        <div class="announcement-slider-container">
            <div id="announcementSlider" class="announcement-slider">
                <!-- Announcements will be added here via JavaScript -->
                <div class="announcement-slider-loading">
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </div>
            </div>
        </div>
        
        <!-- Slider Navigation (if multiple announcements) -->
        <div class="announcement-slider-nav" id="announcementSliderNav">
            <button id="prevAnnouncement" class="nav-btn prev-btn" disabled>
                <i class="fas fa-chevron-left"></i>
            </button>
            <div id="announcementDots" class="announcement-dots">
                <!-- Dots will be added here via JavaScript -->
            </div>
            <button id="nextAnnouncement" class="nav-btn next-btn" disabled>
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <!-- Show Later / Don't Show Again options -->
        <div class="announcement-actions" id="announcementActions">
            <button id="dismissFor24h" class="action-btn dismiss-24h-btn">
                <i class="fas fa-clock mr-1"></i> {{ trans_db('sections', 'dismiss_for_24h', false) ?: 'Dismiss for 24 hours' }}
            </button>
        </div>
    </div>
</div>

<!-- Announcement Toggle Button (always visible) -->
<div id="announcementToggle" class="announcement-toggle">
    <i class="fas fa-bullhorn"></i>
    <span class="announcement-toggle-label">{{ trans_db('sections', 'announcements', false) ?: 'Announcements' }}</span>
    <span class="announcement-badge" id="announcementBadge">1</span>
</div>