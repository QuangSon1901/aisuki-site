<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationApiController extends Controller
{
    /**
     * Kiểm tra và lấy thông báo mới
     */
    public function checkNewNotifications(Request $request)
    {
        // Lấy timestamp lần kiểm tra cuối cùng
        $lastChecked = $request->input('last_checked');
        
        // Query thông báo chưa đọc
        $query = Notification::where('is_read', false);
        
        // Nếu có timestamp, chỉ lấy thông báo mới từ thời điểm đó
        if ($lastChecked) {
            $query->where('created_at', '>', date('Y-m-d H:i:s', $lastChecked));
        }
        
        // Tổng số thông báo chưa đọc
        $totalUnread = Notification::where('is_read', false)->count();
        
        // 5 thông báo gần nhất (ưu tiên chưa đọc)
        $recentNotifications = Notification::orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'content' => Str::limit($notification->content, 100),
                    'type' => $notification->type,
                    'is_read' => $notification->is_read,
                    'time' => $notification->created_at->diffForHumans(),
                    'color_class' => $notification->getColorClass(),
                    'icon_class' => $notification->getIconClass(),
                    'url' => route('admin.notifications.show', $notification->id)
                ];
            });
        
        // Thông báo mới từ lần kiểm tra cuối cùng
        $newNotifications = null;
        if ($lastChecked) {
            $newNotifications = $query->get()->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'type' => $notification->type,
                ];
            });
        }
        
        return response()->json([
            'success' => true,
            'unread_count' => $totalUnread,
            'recent_notifications' => $recentNotifications,
            'new_notifications' => $newNotifications,
            'current_timestamp' => time()
        ]);
    }
}