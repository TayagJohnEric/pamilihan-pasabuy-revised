<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShoppingCartItem;
use App\Models\OrderStatusHistory;
use App\Models\Notification;
use App\Models\Rider;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CustomerOrderFulfillmentController extends Controller
{
    /**
     * Handle PayMongo webhook for payment verification
     * This processes the payment confirmation and triggers order finalization
     */
    public function handlePaymentWebhook(Request $request)
    {
        try {
            Log::info('PayMongo Webhook Received', $request->all());

            if (!$this->validateWebhookSignature($request)) {
                Log::warning('Invalid webhook signature');
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $payload = $request->all();
            $paymentIntentId = $payload['data']['attributes']['payment_intent_id'] ?? null;

            if (!$paymentIntentId) {
                return response()->json(['error' => 'No payment intent ID'], 400);
            }

            $order = Order::where('payment_intent_id', $paymentIntentId)->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $paymentStatus = $payload['data']['attributes']['status'] ?? null;
            
            if ($paymentStatus === 'succeeded') {
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid'
                ]);

                $this->logOrderStatusChange($order->id, 'processing', 'Payment verified via webhook', null);

                // Trigger order finalization
                $this->finalizeOrder($order);

                return response()->json(['success' => true]);
            } else {
                $order->update([
                    'status' => 'failed',
                    'payment_status' => 'failed'
                ]);

                $this->logOrderStatusChange($order->id, 'failed', 'Payment failed via webhook', null);

                return response()->json(['error' => 'Payment failed'], 400);
            }

        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal error'], 500);
        }
    }

    /**
     * Finalize order - SIMPLIFIED VERSION
     * This run for BOTH COD and online payment orders consistently
     */
    public function finalizeOrder(Order $order)
    {
        try {
            Log::info("Starting finalizeOrder for order {$order->id}", [
                'order_status' => $order->status,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status
            ]);
            
            DB::beginTransaction();

            // Get order items (these should already exist from order creation)
            $orderItems = $order->orderItems()->with('product')->get();
            
            if ($orderItems->isEmpty()) {
                throw new \Exception('Order has no items to process');
            }

            // Process stock decrementing for all non-budget items
            foreach ($orderItems as $orderItem) {
                $product = $orderItem->product;
                
                // Only decrement stock for non-budget purchases
                if (is_null($orderItem->customer_budget_requested)) {
                    if ($product->quantity_in_stock < $orderItem->quantity_requested) {
                        throw new \Exception("Insufficient stock for product: {$product->product_name}. Available: {$product->quantity_in_stock}, Requested: {$orderItem->quantity_requested}");
                    }
                    
                    $this->decrementProductStock($product, $orderItem->quantity_requested);
                }
            }

            // Clear the user's cart
            $cartItemsDeleted = DB::table('shopping_cart_items')
                ->where('user_id', $order->customer_user_id)
                ->delete();
                
            Log::info("Cart cleared for order {$order->id}", [
                'cart_items_deleted' => $cartItemsDeleted
            ]);

            // Update payment record if it exists
            $payment = Payment::where('order_id', $order->id)->first();
            if ($payment && $payment->status === 'pending') {
                $payment->update([
                    'status' => 'completed',
                    'payment_processed_at' => now(),
                ]);
            }

            // Log status in history
            $this->logOrderStatusChange($order->id, 'processing', 'Order finalized successfully', null);

            // Send notifications
            $this->notifyVendorsOfNewOrder($order);
            $this->notifyCustomerOrderProcessing($order);

            DB::commit();
            Log::info("Order {$order->id} finalized successfully");

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error finalizing order {$order->id}: " . $e->getMessage());

            // Update order status to failed
            $order->update(['status' => 'failed']);
            
            $this->notifyCustomerOrderFailed($order);

            throw $e;
        }
    }

    /**
     * Helper method to decrement product stock safely
     */
    private function decrementProductStock(Product $product, int $quantity)
    {
        $oldStock = $product->quantity_in_stock;
        $newStock = $oldStock - $quantity;
        
        // Update stock with optimistic locking
        $updated = DB::table('products')
            ->where('id', $product->id)
            ->where('quantity_in_stock', $oldStock)
            ->update(['quantity_in_stock' => $newStock]);
        
        if (!$updated) {
            throw new \Exception("Failed to update stock for product: {$product->product_name}");
        }
        
        Log::info("Stock decremented for product {$product->id}", [
            'product_name' => $product->product_name,
            'quantity_decremented' => $quantity,
            'old_stock' => $oldStock,
            'new_stock' => $newStock
        ]);
    }

    /**
     * Handle vendor item preparation completion
     */
    public function handleVendorItemReady(Request $request)
    {
        $request->validate([
            'order_item_ids' => 'required|array',
            'order_item_ids.*' => 'exists:order_items,id'
        ]);

        try {
            DB::beginTransaction();

            // Update order items status
            OrderItem::whereIn('id', $request->order_item_ids)
                ->update(['status' => 'ready_for_pickup']);

            // Get the order and check if all items are ready
            $orderItem = OrderItem::find($request->order_item_ids[0]);
            $order = $orderItem->order;

            $totalItems = $order->orderItems()->count();
            $readyItems = $order->orderItems()->where('status', 'ready_for_pickup')->count();

            if ($totalItems === $readyItems) {
                $this->triggerRiderAssignment($order);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Items marked as ready']);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error handling vendor item ready: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update item status'], 500);
        }
    }

    /**
     * Trigger automatic rider assignment when all items are ready
     */
    private function triggerRiderAssignment(Order $order)
    {
        $order->update(['status' => 'awaiting_rider_assignment']);
        
        $this->logOrderStatusChange(
            $order->id, 
            'awaiting_rider_assignment', 
            'All vendor items ready, initiating rider assignment', 
            null
        );

        if ($order->preferred_rider_id) {
            $this->assignSpecificRider($order);
        } else {
            $this->assignBestAvailableRider($order);
        }
    }

    /**
     * Assign specific rider chosen by customer
     */
    private function assignSpecificRider(Order $order)
    {
        $preferredRider = Rider::with('user')
            ->where('user_id', $order->preferred_rider_id)
            ->where('is_available', true)
            ->where('verification_status', 'verified')
            ->first();

        if ($preferredRider && $preferredRider->user->is_active) {
            $order->update(['rider_user_id' => $order->preferred_rider_id]);
            $this->completeRiderAssignment($order, $preferredRider);
        } else {
            Log::info("Preferred rider not available, falling back to auto-assignment");
            $this->assignBestAvailableRider($order);
        }
    }

    /**
     * Find and assign the best available rider automatically
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
     * Complete rider assignment process
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

        // Notify customer
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

        // Notify rider
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
     * Get real-time order status updates (AJAX endpoint)
     */
    public function getOrderStatusUpdate($orderId)
    {
        $order = Order::with([
            'rider.user',
            'orderItems',
            'statusHistory' => function($query) {
                $query->latest()->limit(5);
            }
        ])->findOrFail($orderId);

        if ($order->customer_user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json([
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'rider' => $order->rider ? [
                'name' => $order->rider->user->first_name . ' ' . $order->rider->user->last_name,
                'phone' => $order->rider->user->phone_number,
                'rating' => $order->rider->average_rating,
                'vehicle_type' => $order->rider->vehicle_type
            ] : null,
            'items_ready_count' => $order->orderItems->where('status', 'ready_for_pickup')->count(),
            'total_items_count' => $order->orderItems->count(),
            'latest_update' => $order->statusHistory->first()?->created_at?->diffForHumans()
        ]);
    }

    // ==================== NOTIFICATION METHODS ====================

    /**
     * Send notifications to all vendors involved in the order
     */
    public function notifyVendorsOfNewOrder(Order $order)
    {
        $vendorIds = $order->orderItems()
            ->with('product.vendor')
            ->get()
            ->pluck('product.vendor.user_id')
            ->unique();

        foreach ($vendorIds as $vendorUserId) {
            $this->createNotification(
                $vendorUserId,
                'new_order',
                'New Order Received',
                [
                    'order_id' => $order->id,
                    'customer_name' => $order->customer->first_name . ' ' . $order->customer->last_name,
                    'item_count' => $order->orderItems->count(),
                    'message' => 'You have received a new order. Please prepare the items for pickup.'
                ],
                Order::class,
                $order->id
            );
        }
    }

    /**
     * Notify customer that order is being processed
     */
    private function notifyCustomerOrderProcessing(Order $order)
    {
        $this->createNotification(
            $order->customer_user_id,
            'order_processing',
            'Order is Being Prepared',
            [
                'order_id' => $order->id,
                'message' => 'Your order is now being prepared by our vendors.'
            ],
            Order::class,
            $order->id
        );
    }


    

    /**
     * Notify customer of order failure
     */
    private function notifyCustomerOrderFailed(Order $order)
    {
        $this->createNotification(
            $order->customer_user_id,
            'order_failed',
            'Order Processing Failed',
            [
                'order_id' => $order->id,
                'message' => 'There was an issue processing your order. Please contact support.'
            ],
            Order::class,
            $order->id
        );
    }

    /**
     * Create a notification record
     */
    public function createNotification($userId, $type, $title, $message, $entityType = null, $entityId = null)
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

    /**
     * Log order status change in history
     */
    public function logOrderStatusChange($orderId, $status, $notes = null, $updatedByUserId = null)
    {
        try {
            OrderStatusHistory::create([
                'order_id' => $orderId,
                'status' => $status,
                'notes' => $notes,
                'updated_by_user_id' => $updatedByUserId,
                'created_at' => now(),
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to log order status change: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate webhook signature
     */
    private function validateWebhookSignature(Request $request)
    {
         // Implement signature validation based on PayMongo webhook security
        // This is a placeholder - you need to implement actual signature verification
        $signature = $request->header('X-PayMongo-Signature');
        $payload = $request->getContent();
        
        Log::info('Webhook signature validation', [
            'has_signature_header' => !empty($signature),
            'signature_header' => $signature,
            'payload_length' => strlen($payload),
            'webhook_source' => 'PayMongo'
        ]);
        
        // TODO: Implement actual signature validation based on PayMongo documentation
        // For now, we'll log the signature for debugging but allow the webhook to proceed
        // In production, you should implement proper signature validation
        
        if (empty($signature)) {
            Log::warning('No webhook signature provided - this should be investigated');
        }
        
        // Placeholder implementation - replace with actual signature validation
        return true;
    }
}