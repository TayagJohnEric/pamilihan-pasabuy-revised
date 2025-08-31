<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
     /**
     * Get notifications for the authenticated user
     * Returns the latest 10 notifications, with unread ones first
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderByRaw('read_at IS NULL DESC') // unread first
            ->latest()
            ->take(10)
            ->get();

        return response()->json($notifications);
    }

    /**
     * Mark a specific notification as read
     * 
     * @param string $id Notification ID
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $notification->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read for the authenticated user
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Get the count of unread notifications for the authenticated user
     */
    public function getUnreadCount()
    {
        $unreadCount = Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();
        
        return response()->json(['unread_count' => $unreadCount]);
    }
}
