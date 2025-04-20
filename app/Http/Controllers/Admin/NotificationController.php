<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::orderBy('created_at', 'desc');
        
        // Filter by type (order, reservation, contact)
        if ($request->has('type') && in_array($request->type, ['order', 'reservation', 'contact'])) {
            $query->where('type', $request->type);
        }
        
        // Filter by read status
        if ($request->has('read') && in_array($request->read, ['0', '1'])) {
            $query->where('is_read', $request->read);
        }
        
        // Filter by processed status
        if ($request->has('processed') && in_array($request->processed, ['0', '1'])) {
            $query->where('is_processed', $request->processed);
        }
        
        $notifications = $query->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }
    
    public function show(Notification $notification)
    {
        // Mark notification as read
        if (!$notification->is_read) {
            $notification->markAsRead();
        }
        
        return view('admin.notifications.show', compact('notification'));
    }
    
    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();
        return redirect()->back()->with('success', 'Notification marked as read');
    }
    
    public function markAsProcessed(Notification $notification)
    {
        $notification->markAsProcessed();
        return redirect()->back()->with('success', 'Notification marked as processed');
    }
    
    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        return redirect()->back()->with('success', 'All notifications marked as read');
    }
}