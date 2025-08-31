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
            // Log webhook payload for debugging
            Log::info('PayMongo Webhook Received', $request->all());

            // Validate webhook signature (implement based on PayMongo documentation)
            if (!$this->validateWebhookSignature($request)) {
                Log::warning('Invalid webhook signature');
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $payload = $request->all();
            $paymentIntentId = $payload['data']['attributes']['payment_intent_id'] ?? null;

            if (!$paymentIntentId) {
                Log::warning('No payment_intent_id in webhook payload');
                return response()->json(['error' => 'No payment intent ID'], 400);
            }

            // Find order by payment intent ID
            $order = Order::where('payment_intent_id', $paymentIntentId)->first();

            if (!$order) {
                Log::warning("Order not found for payment_intent_id: {$paymentIntentId}");
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Check if payment was successful
            $paymentStatus = $payload['data']['attributes']['status'] ?? null;
            
            if ($paymentStatus === 'succeeded') {
                // Update order payment status
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid'
                ]);

                // Log status change
                $this->logOrderStatusChange($order->id, 'processing', 'Payment verified via webhook', null);

                // Trigger order finalization
                $this->finalizeOrder($order);

                return response()->json(['success' => true]);
            } else {
                // Handle failed payment
                $order->update([
                    'status' => 'failed',
                    'payment_status' => 'failed'
                ]);

                $this->logOrderStatusChange($order->id, 'failed', 'Payment failed via webhook', null);

                // Notify customer of failed payment
                $this->createNotification(
                    $order->customer_user_id,
                    'payment_failed',
                    'Payment Failed',
                    [
                        'order_id' => $order->id,
                        'message' => 'Your payment could not be processed. Please try again or contact support.'
                    ],
                    Order::class,
                    $order->id
                );

                return response()->json(['error' => 'Payment failed'], 400);
            }

        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal error'], 500);
        }
    }

    /**
     * Finalize order - process order items, update stock, clear cart, and notify vendors
     * This runs for COD orders immediately, and for online orders after payment verification
     */
    public function finalizeOrder(Order $order)
    {
        try {
            Log::info("Starting finalizeOrder for order {$order->id}", [
                'order_status' => $order->status,
                'payment_method' => $order->payment_method
            ]);
            
            DB::beginTransaction();

            // Check if order items already exist (online payment case)
            $existingOrderItems = $order->orderItems()->count();
            
            Log::info("Order {$order->id} has {$existingOrderItems} existing order items");
            
            if ($existingOrderItems === 0) {
                // COD case: Copy items from cart to order_items
                $cartItems = ShoppingCartItem::where('user_id', $order->customer_user_id)
                    ->with('product')
                    ->get();

                if ($cartItems->isEmpty()) {
                    throw new \Exception('Cart is empty, cannot finalize order');
                }

                // Copy items from cart to order_items
                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;

                    // Check stock availability for non-budget purchases
                    if (is_null($cartItem->customer_budget) && $product->quantity_in_stock < $cartItem->quantity) {
                        throw new \Exception("Insufficient stock for product: {$product->product_name}");
                    }

                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'status' => 'pending',
                        'product_name_snapshot' => $product->product_name,
                        'quantity_requested' => $cartItem->quantity,
                        'unit_price_snapshot' => $product->price,
                        'customer_budget_requested' => $cartItem->customer_budget,
                        'customerNotes_snapshot' => $cartItem->customer_notes,
                    ]);

                    // Decrement stock for non-budget purchases
                    if (is_null($cartItem->customer_budget)) {
                        $product->decrement('quantity_in_stock', $cartItem->quantity);
                    }
                }

                // Clear the user's cart
                ShoppingCartItem::where('user_id', $order->customer_user_id)->delete();
            } else {
                // Online payment case: Order items already exist, just update stock
                // For COD orders, stock is already decremented during order creation
                if ($order->payment_method === 'cod') {
                    Log::info("Order {$order->id} is COD - stock already decremented during order creation");
                } else {
                    $orderItems = $order->orderItems()->with('product')->get();
                    
                    foreach ($orderItems as $orderItem) {
                        $product = $orderItem->product;
                        
                        Log::info("Processing order item {$orderItem->id} for product {$product->id}", [
                            'product_name' => $product->product_name,
                            'current_stock' => $product->quantity_in_stock,
                            'quantity_requested' => $orderItem->quantity_requested,
                            'customer_budget_requested' => $orderItem->customer_budget_requested
                        ]);
                        
                        // Decrement stock for non-budget purchases
                        if (is_null($orderItem->customer_budget_requested)) {
                            if ($product->quantity_in_stock < $orderItem->quantity_requested) {
                                throw new \Exception("Insufficient stock for product: {$product->product_name}. Available: {$product->quantity_in_stock}, Requested: {$orderItem->quantity_requested}");
                            }
                            $product->decrement('quantity_in_stock', $orderItem->quantity_requested);
                            Log::info("Stock decremented for product {$product->id}", [
                                'new_stock' => $product->fresh()->quantity_in_stock
                            ]);
                        }
                    }
                }
            }

            // Update payment record if it exists (for online payments)
            $payment = Payment::where('order_id', $order->id)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'payment_processed_at' => now(),
                ]);
            }

            // Log status in history
            $this->logOrderStatusChange($order->id, 'processing', 'Order finalized successfully', null);

            // Send notifications to vendors
            $this->notifyVendorsOfNewOrder($order);

            // Notify customer that order is being prepared
            $this->createNotification(
                $order->customer_user_id,
                'order_processing',
                'Order is Being Prepared',
                [
                    'order_id' => $order->id,
                    'message' => 'Your order is now being prepared by our vendors. You will be notified when it\'s ready for pickup.'
                ],
                Order::class,
                $order->id
            );

            DB::commit();
            Log::info("Order {$order->id} finalized successfully");

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error finalizing order {$order->id}: " . $e->getMessage(), [
                'order_id' => $order->id,
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            // Update order status to failed
            $order->update(['status' => 'failed']);
            
            // Notify customer of error
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

            throw $e;
        }
    }

    /**
     * Process COD order finalization (called immediately after order creation)
     */
    public function processCodOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        if ($order->payment_method !== 'cod') {
            throw new \Exception('This method is only for COD orders');
        }

        // Update status to processing
        $order->update([
            'status' => 'processing',
            'payment_status' => 'pending' // Will be 'paid' when rider collects payment
        ]);

        // Finalize the order
        $this->finalizeOrder($order);
    }

    /**
     * Handle vendor item preparation completion
     * Called when vendor marks their items as "ready for pickup"
     */
    public function handleVendorItemReady(Request $request)
    {
        $request->validate([
            'order_item_ids' => 'required|array',
            'order_item_ids.*' => 'exists:order_items,id'
        ]);

        try {
            DB::beginTransaction();

            // Update order items status to ready_for_pickup
            OrderItem::whereIn('id', $request->order_item_ids)
                ->update(['status' => 'ready_for_pickup']);

            // Get the order from the first order item
            $orderItem = OrderItem::find($request->order_item_ids[0]);
            $order = $orderItem->order;

            // Check if all items in the order are ready
            $totalItems = $order->orderItems()->count();
            $readyItems = $order->orderItems()->where('status', 'ready_for_pickup')->count();

            if ($totalItems === $readyItems) {
                // All items are ready, trigger rider assignment
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
        // Update order status
        $order->update(['status' => 'awaiting_rider_assignment']);
        
        $this->logOrderStatusChange(
            $order->id, 
            'awaiting_rider_assignment', 
            'All vendor items ready, initiating rider assignment', 
            null
        );

        // Check customer's rider preference
        if ($order->preferred_rider_id) {
            // Customer chose a specific rider
            $this->assignSpecificRider($order);
        } else {
            // Customer chose "Assign One For Me"
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
            // Assign the preferred rider
            $order->update(['rider_user_id' => $order->preferred_rider_id]);
            
            $this->completeRiderAssignment($order, $preferredRider);
        } else {
            // Preferred rider is not available, fallback to automatic assignment
            Log::info("Preferred rider {$order->preferred_rider_id} not available for order {$order->id}, falling back to auto-assignment");
            
            $this->assignBestAvailableRider($order);
        }
    }

    /**
     * Find and assign the best available rider automatically
     */
    private function assignBestAvailableRider(Order $order)
    {
        // Find available riders based on criteria (rating, location, etc.)
        $availableRiders = Rider::with('user')
            ->where('is_available', true)
            ->where('verification_status', 'verified')
            ->whereHas('user', function($query) {
                $query->where('is_active', true);
            })
            ->orderBy('average_rating', 'desc')
            ->orderBy('total_deliveries', 'asc') // Prefer riders with fewer deliveries to balance workload
            ->get();

        if ($availableRiders->isEmpty()) {
            // No riders available
            Log::warning("No available riders for order {$order->id}");
            
            $this->createNotification(
                $order->customer_user_id,
                'rider_assignment_delayed',
                'Rider Assignment Delayed',
                [
                    'order_id' => $order->id,
                    'message' => 'We are currently looking for an available rider for your order. You will be notified once assigned.'
                ],
                Order::class,
                $order->id
            );

            return;
        }

        // Assign the best available rider
        $bestRider = $availableRiders->first();
        $order->update(['rider_user_id' => $bestRider->user_id]);

        $this->completeRiderAssignment($order, $bestRider);
    }

    /**
     * Complete rider assignment process
     */
    private function completeRiderAssignment(Order $order, Rider $rider)
    {
        // Update order status
        $order->update(['status' => 'out_for_delivery']);
        
        $this->logOrderStatusChange(
            $order->id, 
            'out_for_delivery', 
            "Rider assigned: {$rider->user->first_name} {$rider->user->last_name}", 
            null
        );

        // Notify customer with rider details
        $this->createNotification(
            $order->customer_user_id,
            'rider_assigned',
            'Rider Assigned to Your Order',
            [
                'order_id' => $order->id,
                'rider_name' => $rider->user->first_name . ' ' . $rider->user->last_name,
                'rider_phone' => $rider->user->phone_number,
                'rider_rating' => $rider->average_rating,
                'vehicle_type' => $rider->vehicle_type,
                'message' => 'Your order is now out for delivery!'
            ],
            Order::class,
            $order->id
        );

        // Notify rider of new delivery assignment
        $this->createNotification(
            $rider->user_id,
            'delivery_assigned',
            'New Delivery Assignment',
            [
                'order_id' => $order->id,
                'customer_name' => $order->customer->first_name . ' ' . $order->customer->last_name,
                'delivery_address' => $order->deliveryAddress->full_address ?? 'Address details available in app',
                'delivery_fee' => $order->delivery_fee,
                'message' => 'You have been assigned a new delivery. Please proceed to pickup location.'
            ],
            Order::class,
            $order->id
        );

        Log::info("Rider {$rider->user_id} assigned to order {$order->id}");
    }

    /**
     * Display order status page for customers
     */
    public function showOrderStatus($orderId)
    {
        $order = Order::with([
            'customer',
            'rider.user',
            'deliveryAddress',
            'orderItems.product.vendor.user',
            'statusHistory',
            'payment'
        ])->findOrFail($orderId);

        // Ensure customer can only view their own orders
        if ($order->customer_user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order');
        }

        return view('customer.orders.status', compact('order'));
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

        // Ensure customer can only access their own orders
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
            Log::info("Logging order status change", [
                'order_id' => $orderId,
                'status' => $status,
                'notes' => $notes,
                'updated_by_user_id' => $updatedByUserId
            ]);
            
            $statusHistory = OrderStatusHistory::create([
                'order_id' => $orderId,
                'status' => $status,
                'notes' => $notes,
                'updated_by_user_id' => $updatedByUserId,
            ]);
            
            Log::info("Order status change logged successfully", [
                'status_history_id' => $statusHistory->id,
                'order_id' => $orderId,
                'status' => $status
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to log order status change", [
                'order_id' => $orderId,
                'status' => $status,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Validate webhook signature (implement based on PayMongo requirements)
     */
    private function validateWebhookSignature(Request $request)
    {
        // Implement signature validation based on PayMongo webhook security
        // This is a placeholder - you need to implement actual signature verification
        $signature = $request->header('X-PayMongo-Signature');
        $payload = $request->getContent();
        
        // TODO: Implement actual signature validation
        return true; // Placeholder
    }
}
