<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\Notification;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VendorOrderController extends Controller
{
    /**
     * Display list of orders that contain items from the authenticated vendor
     * Groups order items by order for better organization
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
        }

        // Get orders that contain items from this vendor
        $orders = Order::whereHas('orderItems.product', function($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            })
            ->with([
                'customer',
                'deliveryAddress.district',
                'orderItems' => function($query) use ($vendor) {
                    // Only load items that belong to this vendor
                    $query->whereHas('product', function($q) use ($vendor) {
                        $q->where('vendor_id', $vendor->id);
                    })->with('product');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Show detailed view of a specific order with items for this vendor
     * Includes customer information and delivery details
     */
    public function show(Order $order)
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
        }

        // Check if this vendor has items in this order
        $hasVendorItems = $order->orderItems()
            ->whereHas('product', function($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            })
            ->exists();

        if (!$hasVendorItems) {
            return redirect()->route('vendor.orders.index')->with('error', 'Order not found or does not contain your items.');
        }

        // Load order with related data, filtering items to only this vendor's items
        $order->load([
            'customer',
            'deliveryAddress.district',
            'orderItems' => function($query) use ($vendor) {
                $query->whereHas('product', function($q) use ($vendor) {
                    $q->where('vendor_id', $vendor->id);
                })->with('product');
            }
        ]);

        return view('vendor.orders.show', compact('order'));
    }

    /**
     * Update individual order item status and fulfillment details
     * Handles both regular and budget-based items
     */
    public function updateOrderItem(Request $request, OrderItem $orderItem)
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Vendor profile not found.'], 403);
            }
            return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
        }

        // Verify this item belongs to the vendor
        if ($orderItem->product->vendor_id !== $vendor->id) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized to update this item.'], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized to update this item.');
        }

        // Validate request data
        $request->validate([
            'status' => 'required|in:pending,preparing,ready_for_pickup',
            'vendor_assigned_quantity_description' => 'nullable|string|max:255',
            'actual_item_price' => 'nullable|numeric|min:0',
            'vendor_fulfillment_notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $orderItem->status;

            // Update the order item
            $orderItem->update([
                'status' => $request->status,
                'vendor_assigned_quantity_description' => $request->vendor_assigned_quantity_description,
                'actual_item_price' => $request->actual_item_price,
                'vendor_fulfillment_notes' => $request->vendor_fulfillment_notes,
            ]);

            // If marked as ready, check if all items in the order are ready
            if ($request->status === 'ready_for_pickup' && $oldStatus !== 'ready_for_pickup') {
                $this->checkAndProcessOrderReadiness($orderItem->order);
            }

            // Don't log item status changes to order_status_history as it has different status constraints
            // Instead, we could log this information elsewhere or create a separate item_status_history table
            // For now, we'll just skip this to prevent the constraint violation

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Order item updated successfully.',
                    'new_status' => $request->status
                ]);
            }

            return redirect()->back()->with('success', 'Order item updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order item: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update order item. Please try again.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to update order item. Please try again.');
        }
    }

    /**
     * Bulk update multiple order items status to 'ready_for_pickup'
     * Useful when vendor wants to mark multiple items as ready at once
     */
    public function bulkMarkReady(Request $request)
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Vendor profile not found.'], 403);
            }
            return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
        }

        $request->validate([
            'order_item_ids' => 'required|array',
            'order_item_ids.*' => 'exists:order_items,id'
        ]);

        try {
            DB::beginTransaction();

            // Get order items that belong to this vendor
            $orderItems = OrderItem::whereIn('id', $request->order_item_ids)
                ->whereHas('product', function($query) use ($vendor) {
                    $query->where('vendor_id', $vendor->id);
                })
                ->get();

            if ($orderItems->isEmpty()) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'No valid items found to update.'], 400);
                }
                return redirect()->back()->with('error', 'No valid items found to update.');
            }

            // Update all items to ready_for_pickup
            foreach ($orderItems as $item) {
                $item->update(['status' => 'ready_for_pickup']);
                
                // Don't log item status changes to order_status_history as it has different status constraints
                // Instead, we could log this information elsewhere or create a separate item_status_history table
                // For now, we'll just skip this to prevent the constraint violation
            }

            // Check each unique order for readiness
            $uniqueOrders = $orderItems->pluck('order_id')->unique();
            foreach ($uniqueOrders as $orderId) {
                $order = Order::find($orderId);
                if ($order) {
                    $this->checkAndProcessOrderReadiness($order);
                }
            }

            DB::commit();

            $message = count($orderItems) . ' items marked as ready for pickup.';

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk update: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update items. Please try again.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to update items. Please try again.');
        }
    }

    /**
     * Check if all items in an order are ready for pickup
     * If so, update order status and assign rider
     */
    private function checkAndProcessOrderReadiness(Order $order)
    {
        // Count total items vs ready items in the order
        $totalItems = $order->orderItems()->count();
        $readyItems = $order->orderItems()->where('status', 'ready_for_pickup')->count();

        // If all items are ready, process for delivery
        if ($totalItems === $readyItems && $totalItems > 0) {
            // Update order status to awaiting rider assignment
            $order->update(['status' => 'awaiting_rider_assignment']);
            
            $this->logOrderStatusChange(
                $order->id,
                'awaiting_rider_assignment',
                'All items ready for pickup - awaiting rider assignment',
                null
            );

            // Try to assign a rider automatically
            $this->assignBestAvailableRider($order);
        }
    }

    /**
     * Find and assign the best available rider automatically
     * Uses rating and delivery count as criteria
     */
    private function assignBestAvailableRider(Order $order)
    {
        $availableRiders = Rider::with('user')
            ->where('is_available', true)
            ->where('verification_status', 'verified')
            ->whereHas('user', function($query) {
                $query->where('is_active', true);
            })
            ->orderBy('average_rating', 'desc')
            ->orderBy('total_deliveries', 'asc')
            ->get();

        if ($availableRiders->isEmpty()) {
            Log::warning("No available riders for order {$order->id}");
            
            // Notify customer about rider assignment delay
            $this->createNotification(
                $order->customer_user_id,
                'rider_assignment_delayed',
                'Rider Assignment Delayed',
                [
                    'order_id' => $order->id,
                    'message' => 'We are currently looking for an available rider for your order.'
                ],
                Order::class,
                $order->id
            );
            return;
        }

        $bestRider = $availableRiders->first();
        $order->update(['rider_user_id' => $bestRider->user_id]);
        $this->completeRiderAssignment($order, $bestRider);
    }

    /**
     * Complete the rider assignment process
     * Updates order status and sends notifications
     */
    private function completeRiderAssignment(Order $order, Rider $rider)
    {
        $order->update(['status' => 'out_for_delivery']);
        
        $this->logOrderStatusChange(
            $order->id, 
            'out_for_delivery', 
            "Rider assigned: {$rider->user->first_name} {$rider->user->last_name}", 
            null
        );

        // Notify customer about rider assignment
        $this->createNotification(
            $order->customer_user_id,
            'rider_assigned',
            'Rider Assigned to Your Order',
            [
                'order_id' => $order->id,
                'rider_name' => $rider->user->first_name . ' ' . $rider->user->last_name,
                'rider_phone' => $rider->user->phone_number,
                'message' => 'Your order is now out for delivery!'
            ],
            Order::class,
            $order->id
        );

        // Notify rider about new delivery assignment
        $this->createNotification(
            $rider->user_id,
            'delivery_assigned',
            'New Delivery Assignment',
            [   
                'order_id' => $order->id,
                'customer_name' => $order->customer->first_name . ' ' . $order->customer->last_name,
                'delivery_fee' => $order->delivery_fee,
                'message' => 'You have been assigned a new delivery.'
            ],
            Order::class,
            $order->id
        );
    }

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