// Notification Polling System
document.addEventListener('DOMContentLoaded', function() {
    // Biến lưu trữ
    let lastCheckedTimestamp = null;
    let originalTitle = document.title;
    let isFlashing = false;
    let flashInterval;
    let flashCount = 0;
    
    /**
     * Cập nhật UI thông báo
     */
    function updateNotificationUI(data) {
        // Cập nhật số lượng thông báo chưa đọc
        const unreadCount = data.unread_count;
        const notificationBadges = document.querySelectorAll('.notification-badge');
        
        // Cập nhật tất cả badge
        notificationBadges.forEach(badge => {
            if (unreadCount > 0) {
                badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        });
        
        // Cập nhật dropdown menu
        const notificationList = document.querySelector('.notification-list');
        if (notificationList && data.recent_notifications.length) {
            // Xóa nội dung cũ
            notificationList.innerHTML = '';
            
            // Thêm thông báo mới
            data.recent_notifications.forEach(notification => {
                const notificationItem = document.createElement('a');
                notificationItem.href = notification.url;
                notificationItem.className = `dropdown-item d-flex align-items-center ${notification.is_read ? '' : 'bg-light'}`;
                
                notificationItem.innerHTML = `
                    <div class="flex-shrink-0">
                        <div class="bg-${notification.color_class} text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas ${notification.icon_class}" style="margin-right: 0px;"></i>
                        </div>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="small ${notification.is_read ? '' : 'fw-bold'}">${notification.title}</div>
                        <div class="text-muted smaller">${notification.time}</div>
                    </div>
                `;
                
                notificationList.appendChild(notificationItem);
            });
        } else if (notificationList && !data.recent_notifications.length) {
            notificationList.innerHTML = `
                <div class="dropdown-item text-center text-muted py-3">
                    <i class="fas fa-bell-slash me-1"></i> No notifications
                </div>
            `;
        }
        
        // Xử lý thông báo mới
        if (data.new_notifications && data.new_notifications.length > 0) {
            // Nháy title
            startTitleFlash(data.new_notifications.length);
            
            // Phát âm thanh
            playNotificationSound();
        }
        
        // Cập nhật timestamp
        lastCheckedTimestamp = data.current_timestamp;
    }
    
    /**
     * Bắt đầu hiệu ứng nhấp nháy tiêu đề
     */
    function startTitleFlash(count) {
        // Ngừng nếu đang nhấp nháy
        if (isFlashing) return;
        
        isFlashing = true;
        flashCount = 0;
        const maxFlashCount = 10; // Số lần nhấp nháy tối đa
        
        // Tạo tiêu đề thông báo
        const notificationTitle = `(${count}) Thông báo mới - AISUKI Admin`;
        
        // Thiết lập interval
        flashInterval = setInterval(function() {
            // Đổi qua lại giữa tiêu đề gốc và tiêu đề thông báo
            document.title = document.title === originalTitle ? notificationTitle : originalTitle;
            
            flashCount++;
            if (flashCount >= maxFlashCount) {
                stopTitleFlash();
            }
        }, 1000);
    }
    
    /**
     * Dừng hiệu ứng nhấp nháy
     */
    function stopTitleFlash() {
        if (!isFlashing) return;
        
        clearInterval(flashInterval);
        document.title = originalTitle;
        isFlashing = false;
    }
    
    /**
     * Phát âm thanh thông báo
     */
    function playNotificationSound() {
        const audio = new Audio('/sounds/notification.mp3');
        audio.play().catch(error => {
            console.log('Cannot play notification sound:', error);
        });
    }
    
    /**
     * Kiểm tra thông báo mới
     */
    function checkNewNotifications() {
        // Lấy route API
        const url = getRoute('admin.api.notifications.check');
        
        // Nếu không có route, bỏ qua
        if (!url) return;
        
        // Gọi API
        fetch(`${url}?last_checked=${lastCheckedTimestamp || ''}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateNotificationUI(data);
                }
            })
            .catch(error => {
                console.error('Failed to check notifications:', error);
            });
    }
    
    /**
     * Helper function để lấy route URL
     */
    function getRoute(name) {
        // Check if routes object exists (defined in layout)
        if (typeof window.routes !== 'undefined' && window.routes[name]) {
            return window.routes[name];
        }
        
        console.error('Route not found:', name);
        return null;
    }
    
    // Kiểm tra thông báo khi load trang
    checkNewNotifications();
    
    // Thiết lập interval 5 giây
    setInterval(checkNewNotifications, 5000);
    
    // Dừng nhấp nháy khi focus vào tab
    window.addEventListener('focus', function() {
        stopTitleFlash();
    });
});