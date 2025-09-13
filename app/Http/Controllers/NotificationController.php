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

    /**
 * Get notification display text based on notification type
 * This method can be used in your notification component
 */
public function getNotificationDisplayText($notification)
{
    $message = is_array($notification->message) ? $notification->message : ['message' => $notification->message];
    
    switch ($notification->type) {
        // Existing notification types
        case 'order_processing':
            return "Order #{$message['order_id']} is being prepared";
            
        case 'rider_assigned':
            return "{$message['rider_name']} has been assigned to your order";
            
        case 'payment_failed':
            return "Payment failed for Order #{$message['order_id']}";
            
        case 'new_order':
            return "New order from {$message['customer_name']}";
            
        case 'delivery_assigned':
            return "New delivery assignment for Order #{$message['order_id']}";
            
        case 'rider_assignment_delayed':
            return "Rider assignment delayed for Order #{$message['order_id']}";
            
        case 'order_failed':
            return "Order #{$message['order_id']} processing failed";
            
        // New payout notification types
        case 'rider_payout_paid':
            return "Payout of ₱{$message['amount']} has been processed successfully";
            
        case 'rider_payout_failed':
            return "Payout of ₱{$message['amount']} processing failed";
            
        case 'vendor_payout_paid':
            return "Payout of ₱{$message['amount']} has been processed successfully";
            
        case 'vendor_payout_failed':
            return "Payout of ₱{$message['amount']} processing failed";
            
        default:
            return $message['message'] ?? 'You have a new notification';
    }
}

/**
 * Get notification icon color based on type
 * This method can be used in your notification component
 */
public function getNotificationIconColor($notification)
{
    switch ($notification->type) {
        case 'order_processing':
        case 'rider_payout_paid':
        case 'vendor_payout_paid':
            return $notification->read_at ? 'bg-gray-400' : 'bg-blue-500';
            
        case 'rider_assigned':
            return $notification->read_at ? 'bg-gray-400' : 'bg-green-500';
            
        case 'payment_failed':
        case 'order_failed':
        case 'rider_payout_failed':
        case 'vendor_payout_failed':
            return $notification->read_at ? 'bg-gray-400' : 'bg-red-500';
            
        case 'rider_assignment_delayed':
            return $notification->read_at ? 'bg-gray-400' : 'bg-yellow-500';
            
        case 'new_order':
            return $notification->read_at ? 'bg-gray-400' : 'bg-purple-500';
            
        case 'delivery_assigned':
            return $notification->read_at ? 'bg-gray-400' : 'bg-indigo-500';
            
        default:
            return $notification->read_at ? 'bg-gray-400' : 'bg-blue-500';
    }
}
}
