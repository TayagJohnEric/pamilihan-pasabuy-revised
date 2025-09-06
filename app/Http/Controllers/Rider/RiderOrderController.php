<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Notification;
use App\Models\Rider;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RiderOrderController extends Controller
{
    /**
     * Display dashboard with pending assignments and current deliveries
     * Shows orders that need rider action or are currently being handled
     */
    public function index()
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        // Get pending assignments (awaiting rider acceptance)
        $pendingAssignments = Order::where('rider_user_id', Auth::id())
            ->where('status', 'awaiting_rider_assignment')
            ->with([
                'customer',
                'deliveryAddress.district',
                'orderItems.product.vendor'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get current active deliveries
        $activeDeliveries = Order::where('rider_user_id', Auth::id())
            ->whereIn('status', ['out_for_delivery'])
            ->with([
                'customer',
                'deliveryAddress.district',
                'orderItems.product.vendor'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get delivery history
        $deliveryHistory = Order::where('rider_user_id', Auth::id())
            ->whereIn('status', ['delivered', 'cancelled'])
            ->with([
                'customer',
                'deliveryAddress.district'
            ])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return view('rider.orders.index', compact('pendingAssignments', 'activeDeliveries', 'deliveryHistory'));
    }

    /**
     * Show detailed view of a specific order
     * Displays all order information including pickup and delivery details
     */
    public function show(Order $order)
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        // Verify this rider is assigned to this order
        if ($order->rider_user_id !== Auth::id()) {
            return redirect()->route('rider.orders.index')->with('error', 'Order not found or not assigned to you.');
        }

        // Load order with all necessary relationships
        $order->load([
            'customer',
            'deliveryAddress.district',
            'orderItems.product.vendor',
            'statusHistory.updatedBy'
        ]);

        // Group order items by vendor for easier pickup organization
        $vendorGroups = $order->orderItems->groupBy('product.vendor.id');

        return view('rider.orders.show', compact('order', 'vendorGroups'));
    }

    /**
     * Accept an assigned order
     * Changes status from awaiting_rider_assignment to out_for_delivery
     */
    public function acceptOrder(Request $request, Order $order)
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Rider profile not found.'], 403);
            }
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        // Verify this rider is assigned to this order
        if ($order->rider_user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Order not assigned to you.'], 403);
            }
            return redirect()->back()->with('error', 'Order not assigned to you.');
        }

        // Check if order is still pending acceptance
        if ($order->status !== 'awaiting_rider_assignment') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Order is no longer available for acceptance.'], 400);
            }
            return redirect()->back()->with('error', 'Order is no longer available for acceptance.');
        }

        try {
            DB::beginTransaction();

            // Update order status to out_for_delivery
            $order->update(['status' => 'out_for_delivery']);

            // Log status change
            $this->logOrderStatusChange(
                $order->id,
                'out_for_delivery',
                'Rider accepted delivery assignment',
                Auth::id()
            );

            // Notify customer that rider accepted and order is out for delivery
            $this->createNotification(
                $order->customer_user_id,
                'rider_assigned',
                'Rider Assigned to Your Order',
                [
                    'order_id' => $order->id,
                    'rider_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                    'rider_phone' => Auth::user()->phone_number,
                    'message' => 'Your order is now out for delivery!'
                ],
                Order::class,
                $order->id
            );

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order accepted successfully!',
                    'redirect_url' => route('rider.orders.show', $order)
                ]);
            }

            return redirect()->route('rider.orders.show', $order)->with('success', 'Order accepted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error accepting order: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to accept order. Please try again.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to accept order. Please try again.');
        }
    }

    /**
     * Decline an assigned order
     * Triggers reassignment process to find another rider
     */
    public function declineOrder(Request $request, Order $order)
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Rider profile not found.'], 403);
            }
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        // Verify this rider is assigned to this order
        if ($order->rider_user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Order not assigned to you.'], 403);
            }
            return redirect()->back()->with('error', 'Order not assigned to you.');
        }

        // Check if order is still pending acceptance
        if ($order->status !== 'awaiting_rider_assignment') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Order is no longer available for decline.'], 400);
            }
            return redirect()->back()->with('error', 'Order is no longer available for decline.');
        }

        try {
            DB::beginTransaction();

            // Remove rider assignment but keep status as awaiting_rider_assignment
            $order->update(['rider_user_id' => null]);

            // Log the decline
            $this->logOrderStatusChange(
                $order->id,
                'awaiting_rider_assignment',
                'Rider declined assignment - reassigning to another rider',
                Auth::id()
            );

            // Trigger reassignment process (this would typically be handled by a job or event)
            // For now, we'll just log it - the VendorOrderController handles reassignment
            Log::info("Order {$order->id} declined by rider {$rider->id} - triggering reassignment");

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order declined. The system will assign it to another rider.',
                    'redirect_url' => route('rider.orders.index')
                ]);
            }

            return redirect()->route('rider.orders.index')->with('info', 'Order declined. The system will assign it to another rider.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error declining order: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to decline order. Please try again.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to decline order. Please try again.');
        }
    }

    /**
     * Confirm pickup from vendor
     * Updates order status and notifies customer that order is on the way
     */
    public function confirmPickup(Request $request, Order $order)
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Rider profile not found.'], 403);
            }
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        // Verify this rider is assigned to this order
        if ($order->rider_user_id !== Auth::id() || $order->status !== 'out_for_delivery') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unable to confirm pickup for this order.'], 403);
            }
            return redirect()->back()->with('error', 'Unable to confirm pickup for this order.');
        }

        try {
            DB::beginTransaction();

            // Log pickup confirmation (keep status as out_for_delivery)
            $this->logOrderStatusChange(
                $order->id,
                'out_for_delivery',
                'Items picked up from vendor - on the way to customer',
                Auth::id()
            );

            // Notify customer that order is on the way
            $this->createNotification(
                $order->customer_user_id,
                'order_picked_up',
                'Your Order is On the Way!',
                [
                    'order_id' => $order->id,
                    'rider_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                    'message' => 'Your order has been picked up and is on the way to you!'
                ],
                Order::class,
                $order->id
            );

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pickup confirmed successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Pickup confirmed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming pickup: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to confirm pickup. Please try again.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to confirm pickup. Please try again.');
        }
    }

    /**
     * Mark order as delivered
     * Updates order status, rider stats, and payment status for COD orders
     */
    public function markDelivered(Request $request, Order $order)
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Rider profile not found.'], 403);
            }
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        // Verify this rider is assigned to this order
        if ($order->rider_user_id !== Auth::id() || $order->status !== 'out_for_delivery') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unable to mark this order as delivered.'], 403);
            }
            return redirect()->back()->with('error', 'Unable to mark this order as delivered.');
        }

        try {
            DB::beginTransaction();

            // Update order status to delivered
            $order->update(['status' => 'delivered']);

            // If payment method is COD, mark payment as paid
            if ($order->payment_method === 'cod') {
                $order->update(['payment_status' => 'paid']);
            }

            // Update rider performance stats
            $rider->increment('total_deliveries');
            $rider->increment('daily_deliveries');

            // Log delivery completion
            $this->logOrderStatusChange(
                $order->id,
                'delivered',
                'Order successfully delivered to customer',
                Auth::id()
            );

            // Notify customer about successful delivery and prompt for rating
            $this->createNotification(
                $order->customer_user_id,
                'order_delivered',
                'Order Delivered Successfully!',
                [
                    'order_id' => $order->id,
                    'rider_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                    'message' => 'Your order has been delivered! Please rate your experience.'
                ],
                Order::class,
                $order->id
            );

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order marked as delivered successfully!',
                    'redirect_url' => route('rider.orders.index')
                ]);
            }

            return redirect()->route('rider.orders.index')->with('success', 'Order marked as delivered successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error marking order as delivered: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to mark order as delivered. Please try again.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to mark order as delivered. Please try again.');
        }
    }

    /**
     * Toggle rider availability status
     * Controls whether rider receives new order assignments
     */
    public function toggleAvailability(Request $request)
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Rider profile not found.'], 403);
            }
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        try {
            $newStatus = !$rider->is_available;
            $rider->update(['is_available' => $newStatus]);

            $message = $newStatus ? 'You are now available for deliveries!' : 'You are no longer available for deliveries.';

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'is_available' => $newStatus
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error toggling rider availability: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update availability status.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to update availability status.');
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Log order status changes for audit trail
     * Records who made the change and any relevant notes
     */
    private function logOrderStatusChange($orderId, $status, $notes = null, $userId = null)
    {
        OrderStatusHistory::create([
            'order_id' => $orderId,
            'status' => $status,
            'notes' => $notes,
            'updated_by_user_id' => $userId,
            'created_at' => now(),
        ]);
    }

    /**
     * Create notification records for users
     * Follows the same pattern as existing notification system
     */
    private function createNotification($userId, $type, $title, $message, $entityType = null, $entityId = null)
    {
        Notification::create([
            'id' => (string) Str::uuid(),
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'related_entity_type' => $entityType,
            'related_entity_id' => $entityId,
        ]);
    }
}