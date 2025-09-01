<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SavedAddress;
use App\Models\ShoppingCartItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class CustomerPaymentController extends Controller
{
     /**
     * Display the payment confirmation page for online payments
     * 
     * This method shows the final confirmation before redirecting to PayMongo.
     * It validates the order summary from session and prepares payment data.
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function paymentConfirmation(Request $request)
    {
        $user = Auth::user();
        
        // DEBUG: Log the request details
        Log::info('Payment confirmation accessed', [
            'user_id' => $user->id,
            'request_method' => $request->method(),
            'has_order_summary' => session()->has('order_summary'),
            'session_keys' => array_keys(session()->all()),
            'session_id' => session()->getId(),
            'request_data' => $request->all()
        ]);
        
        // Check if this is a direct form submission from checkout
        if ($request->isMethod('post')) {
            // Validate the checkout form data
            $validated = $request->validate([
                'delivery_address_id' => [
                    'required',
                    'exists:saved_addresses,id',
                    function ($attribute, $value, $fail) use ($user) {
                        $address = SavedAddress::where('id', $value)
                            ->where('user_id', $user->id)
                            ->first();
                        if (!$address) {
                            $fail('Invalid delivery address selected.');
                        }
                    }
                ],
                'payment_method' => [
                    'required',
                    'in:online_payment'
                ],
                'rider_selection_type' => [
                    'required',
                    'in:choose_rider,system_assign'
                ],
                'selected_rider_id' => [
                    'required_if:rider_selection_type,choose_rider',
                    'nullable',
                    'exists:users,id'
                ]
            ]);
            
            // Get cart items
            $cartItems = ShoppingCartItem::with(['product.vendor', 'product'])
                ->where('user_id', $user->id)
                ->get();
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Your cart is empty.');
            }
            
            // Get delivery address with district
            $deliveryAddress = SavedAddress::with('district')
                ->where('id', $validated['delivery_address_id'])
                ->where('user_id', $user->id)
                ->first();
            
            // Process rider selection
            $selectedRider = null;
            if ($validated['rider_selection_type'] === 'choose_rider') {
                $selectedRider = User::with('rider')
                    ->where('id', $validated['selected_rider_id'])
                    ->first();
            } else {
                // System assign: randomly select an available rider
                $availableRiders = User::with('rider')
                    ->whereHas('rider', function ($query) {
                        $query->where('is_available', true)
                            ->where('verification_status', 'verified');
                    })
                    ->where('role', 'rider')
                    ->where('is_active', true)
                    ->get();
                
                if ($availableRiders->isNotEmpty()) {
                    $selectedRider = $availableRiders->random();
                }
            }
            
            // Calculate totals
            $subtotal = $cartItems->sum('subtotal');
            $deliveryFee = $deliveryAddress->district->delivery_fee;
            $totalAmount = $subtotal + $deliveryFee;
            
            // Prepare order summary data
            $orderSummary = [
                'cart_items' => $cartItems,
                'delivery_address' => $deliveryAddress,
                'selected_rider' => $selectedRider,
                'payment_method' => 'online_payment',
                'rider_selection_type' => $validated['rider_selection_type'],
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $totalAmount,
                'item_count' => $cartItems->count(),
                'has_budget_items' => $cartItems->where('customer_budget', '!=', null)->count() > 0
            ];
            
            // Store order summary in session for payment processing
            session(['order_summary' => $orderSummary]);
            
            Log::info('Order summary stored in session', [
                'user_id' => $user->id,
                'session_id' => session()->getId(),
                'order_summary_keys' => array_keys($orderSummary),
                'session_has_order_summary' => session()->has('order_summary'),
                'session_all_keys' => array_keys(session()->all())
            ]);
            
        } else {
            // Get order summary from session (set by checkout process)
            $orderSummary = session('order_summary');
            
            Log::info('Retrieved order summary from session', [
                'user_id' => $user->id,
                'session_id' => session()->getId(),
                'has_order_summary' => !empty($orderSummary),
                'order_summary_keys' => $orderSummary ? array_keys($orderSummary) : [],
                'session_all_keys' => array_keys(session()->all())
            ]);
            
            if (!$orderSummary) {
                return redirect()->route('checkout.index')
                    ->with('error', 'Order session expired. Please complete checkout again.');
            }
            
            // Validate that payment method is online payment
            if ($orderSummary['payment_method'] !== 'online_payment') {
                return redirect()->route('checkout.confirmation')
                    ->with('info', 'Redirected to order confirmation for COD.');
            }
        }
        
        // Re-validate cart items to ensure they're still available
        $cartItems = ShoppingCartItem::with(['product.vendor', 'product'])
            ->where('user_id', $user->id)
            ->get();
        
        if ($cartItems->isEmpty() || $cartItems->count() !== $orderSummary['item_count']) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart has changed. Please review and try again.');
        }
        
        return view('customer.checkout.payment-confirmation', compact('orderSummary'));
    }
    
    /**
     * Process online payment by creating PayMongo checkout session
     * 
     * This method:
     * 1. Creates a preliminary order record
     * 2. Creates PayMongo checkout session
     * 3. Redirects customer to PayMongo payment page
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Process online payment by creating PayMongo checkout session
     * ENHANCED WITH DEBUGGING
     */
    public function processOnlinePayment(Request $request)
    {
        $user = Auth::user();
        $orderSummary = session('order_summary');
        
        // ENHANCED DEBUGGING
        Log::info('=== PAYMENT PROCESSING DEBUG START ===', [
            'user_id' => $user->id,
            'request_method' => $request->method(),
            'request_url' => $request->fullUrl(),
            'has_order_summary' => !empty($orderSummary),
            'order_summary_keys' => $orderSummary ? array_keys($orderSummary) : [],
            'payment_method' => $orderSummary['payment_method'] ?? 'none',
            'session_id' => session()->getId(),
            'request_data' => $request->all(),
            'session_all' => array_keys(session()->all())
        ]);
        
        // Check if order summary exists
        if (!$orderSummary) {
            Log::error('Order summary not found in session', [
                'user_id' => $user->id,
                'session_data' => session()->all(),
                'session_id' => session()->getId(),
                'cart_items_count' => ShoppingCartItem::where('user_id', $user->id)->count(),
                'all_session_keys' => array_keys(session()->all())
            ]);
            
            return redirect()->route('checkout.index')
                ->with('error', 'Order session expired. Please complete checkout again.');
        }
        
        // Validate payment method
        if ($orderSummary['payment_method'] !== 'online_payment') {
            Log::warning('Invalid payment method for online processing', [
                'user_id' => $user->id,
                'expected' => 'online_payment',
                'actual' => $orderSummary['payment_method']
            ]);
            
            return redirect()->route('checkout.index')
                ->with('error', 'Invalid payment session. Please try again.');
        }
        
        DB::beginTransaction();
        
        try {
            Log::info('Creating order for online payment', [
                'user_id' => $user->id,
                'delivery_address_id' => $orderSummary['delivery_address']->id ?? 'missing',
                'total_amount' => $orderSummary['total_amount'] ?? 'missing',
                'cart_items_count' => count($orderSummary['cart_items'] ?? [])
            ]);
            
            // Create preliminary order record with pending_payment status
            $order = Order::create([
                'customer_user_id' => $user->id,
                'rider_user_id' => $orderSummary['selected_rider']->id ?? null,
                'preferred_rider_id' => $orderSummary['rider_selection_type'] === 'choose_rider' 
                    ? $orderSummary['selected_rider']->id : null,
                'delivery_address_id' => $orderSummary['delivery_address']->id,
                'order_date' => now(),
                'status' => 'pending_payment',
                'delivery_fee' => $orderSummary['delivery_fee'],
                'final_total_amount' => $orderSummary['total_amount'],
                'payment_method' => 'online_payment',
                'payment_status' => 'pending',
                'special_instructions' => $request->input('special_instructions', null),
            ]);
            
            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'user_id' => $user->id
            ]);
            
            // Log initial order creation for online payment
            $orderFulfillmentController = new \App\Http\Controllers\Customer\CustomerOrderFulfillmentController();
            $orderFulfillmentController->logOrderStatusChange(
                $order->id, 
                'pending_payment', 
                'Order created, awaiting payment', 
                null
            );
            
            // Create order items from cart
            foreach ($orderSummary['cart_items'] as $cartItem) {
                $orderItem = $order->orderItems()->create([
                    'product_id' => $cartItem->product_id,
                    'status' => 'pending',
                    'product_name_snapshot' => $cartItem->product->product_name,
                    'quantity_requested' => $cartItem->quantity ?? 1,
                    'unit_price_snapshot' => $cartItem->product->price,
                    'customer_budget_requested' => $cartItem->customer_budget,
                    'customerNotes_snapshot' => $cartItem->customer_notes,
                ]);
                
                Log::info('Order item created', [
                    'order_item_id' => $orderItem->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity ?? 1
                ]);
            }
            
            // Create draft payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount_paid' => $orderSummary['total_amount'],
                'payment_method_used' => 'online_payment',
                'status' => 'pending',
            ]);
            
            Log::info('Payment record created', [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'amount' => $orderSummary['total_amount']
            ]);
            
            // Create PayMongo checkout session
            Log::info('Attempting to create PayMongo checkout session', [
                'order_id' => $order->id,
                'amount' => $orderSummary['total_amount']
            ]);
            
            $checkoutSession = $this->createPayMongoCheckout($order, $orderSummary);
            
            if (!$checkoutSession) {
                Log::error('PayMongo checkout session creation failed', [
                    'order_id' => $order->id,
                    'user_id' => $user->id
                ]);
                throw new Exception('Failed to create PayMongo checkout session');
            }
            
            Log::info('PayMongo checkout session created successfully', [
                'order_id' => $order->id,
                'checkout_session_id' => $checkoutSession['id'],
                'checkout_url' => $checkoutSession['checkout_url']
            ]);
            
            // Update order with checkout session ID
            $order->update([
                'payment_intent_id' => $checkoutSession['id']
            ]);
            
            // Update payment record with gateway info
            $payment->update([
                'gateway_transaction_id' => $checkoutSession['id'],
                'payment_gateway_response' => $checkoutSession,
            ]);
            
            DB::commit();
            
            Log::info('Database transaction committed, preparing redirect', [
                'order_id' => $order->id,
                'checkout_url' => $checkoutSession['checkout_url']
            ]);
            
            // Clear order summary from session
            session()->forget('order_summary');
            
            // IMPORTANT: Use proper redirect with full URL
            Log::info('Redirecting to PayMongo', [
                'checkout_url' => $checkoutSession['checkout_url'],
                'redirect_method' => 'away'
            ]);
            
            // Use redirect()->away() for external URLs
            return redirect()->away($checkoutSession['checkout_url']);
            
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('=== PAYMENT PROCESSING ERROR ===', [
                'user_id' => $user->id,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'order_summary' => $orderSummary
            ]);
            
            // Provide more specific error messages
            $errorMessage = 'Payment processing failed. ';
            if (str_contains($e->getMessage(), 'Payment gateway not configured')) {
                $errorMessage .= 'Payment system is currently unavailable. Please contact support.';
            } elseif (str_contains($e->getMessage(), 'SQLSTATE')) {
                $errorMessage .= 'Database error occurred. Please try again.';
            } else {
                $errorMessage .= 'Please try again or contact support if the problem persists.';
            }
            
            return redirect()->back()
                ->with('error', $errorMessage);
        }
    }
    
    /**
     * Process Cash on Delivery (COD) order
     * 
     * This method:
     * 1. Creates order with processing status
     * 2. Updates payment status to pending (to be paid on delivery)
     * 3. Clears cart and redirects to order status
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processCOD(Request $request)
    {
        $user = Auth::user();
        $orderSummary = session('order_summary');
        
        if (!$orderSummary || $orderSummary['payment_method'] !== 'cod') {
            return redirect()->route('checkout.index')
                ->with('error', 'Invalid order session. Please try again.');
        }
        
                    DB::beginTransaction();
            
            try {
                Log::info('Creating COD order', [
                    'user_id' => $user->id,
                    'order_summary' => $orderSummary,
                    'transaction_started' => true
                ]);
            
            // Create order record with processing status for COD
            $order = Order::create([
                'customer_user_id' => $user->id,
                'rider_user_id' => $orderSummary['selected_rider']->id ?? null,
                'preferred_rider_id' => $orderSummary['rider_selection_type'] === 'choose_rider' 
                    ? $orderSummary['selected_rider']->id : null,
                'delivery_address_id' => $orderSummary['delivery_address']->id,
                'order_date' => now(),
                'status' => 'processing',
                'delivery_fee' => $orderSummary['delivery_fee'],
                'final_total_amount' => $orderSummary['total_amount'],
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'special_instructions' => $request->input('special_instructions', null),
            ]);
            
            Log::info('COD order created successfully', [
                'order_id' => $order->id,
                'status' => $order->status
            ]);
            
            // Create order items from cart and handle stock decrementing
            foreach ($orderSummary['cart_items'] as $cartItem) {
                $product = $cartItem->product;
                
                // Check stock availability for non-budget purchases
                if (is_null($cartItem->customer_budget) && $product->quantity_in_stock < $cartItem->quantity) {
                    throw new \Exception("Insufficient stock for product: {$product->product_name}. Available: {$product->quantity_in_stock}, Requested: {$cartItem->quantity}");
                }
                
                $order->orderItems()->create([
                    'product_id' => $cartItem->product_id,
                    'status' => 'pending',
                    'product_name_snapshot' => $product->product_name,
                    'quantity_requested' => $cartItem->quantity ?? 1,
                    'unit_price_snapshot' => $product->price,
                    'customer_budget_requested' => $cartItem->customer_budget,
                    'customerNotes_snapshot' => $cartItem->customer_notes,
                ]);
                
                // Decrement stock for non-budget purchases
                if (is_null($cartItem->customer_budget)) {
                    Log::info("Before stock decrement for product {$product->id}", [
                        'product_name' => $product->product_name,
                        'current_stock' => $product->quantity_in_stock,
                        'quantity_to_decrement' => $cartItem->quantity
                    ]);
                    
                    // Use raw SQL to ensure stock is decremented
                    $oldStock = $product->quantity_in_stock;
                    $newStock = $oldStock - $cartItem->quantity;
                    
                    // Force refresh from database to get latest stock value
                    $product->refresh();
                    
                    // Update stock
                    $updated = $product->update(['quantity_in_stock' => $newStock]);
                    
                    if (!$updated) {
                        Log::error("Failed to update stock for product {$product->id}");
                        throw new \Exception("Failed to update stock for product: {$product->product_name}");
                    }
                    
                    // Verify the update
                    $product->refresh();
                    
                    Log::info("Stock decremented for product {$product->id}", [
                        'product_name' => $product->product_name,
                        'quantity_decremented' => $cartItem->quantity,
                        'old_stock' => $oldStock,
                        'new_stock' => $newStock,
                        'verified_stock' => $product->quantity_in_stock,
                        'update_successful' => $updated
                    ]);
                }
            }
            
            // Create payment record for COD (will be completed on delivery)
            Payment::create([
                'order_id' => $order->id,
                'amount_paid' => $orderSummary['total_amount'],
                'payment_method_used' => 'cod',
                'status' => 'pending',
            ]);
            
            // Clear shopping cart
            ShoppingCartItem::where('user_id', $user->id)->delete();
            
            // Log the initial order status in history BEFORE committing the transaction
            $orderFulfillmentController = new \App\Http\Controllers\Customer\CustomerOrderFulfillmentController();
            
            try {
                $orderFulfillmentController->logOrderStatusChange(
                    $order->id, 
                    'processing', 
                    'Order placed successfully via COD', 
                    null
                );
                
                Log::info('Status history logged successfully for COD order', [
                    'order_id' => $order->id,
                    'status' => 'processing'
                ]);
                
            } catch (\Exception $e) {
                Log::error('Failed to log status history for COD order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
                // Don't throw here, continue with the transaction
            }
            
            DB::commit();
            
            Log::info('COD order transaction committed successfully', [
                'order_id' => $order->id,
                'transaction_committed' => true
            ]);
            
            // For COD orders, we don't need to call finalizeOrder since order is already properly set up
            // Just send notifications to vendors
            $orderFulfillmentController->notifyVendorsOfNewOrder($order);
            
            // Notify customer that order is being prepared
            $orderFulfillmentController->createNotification(
                $order->customer_user_id,
                'order_processing',
                'Order is Being Prepared',
                [
                    'order_id' => $order->id,
                    'message' => 'Your order is now being prepared by our vendors. You will be notified when it\'s ready for pickup.'
                ],
                \App\Models\Order::class,
                $order->id
            );
            
            // Clear order summary from session
            session()->forget('order_summary');
            
            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Order placed successfully! Your order will be prepared for delivery.');
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('COD order creation failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'order_summary' => $orderSummary,
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Order placement failed. Please try again.');
        }
    }
    
    /**
     * Handle PayMongo payment success callback
     * 
     * This method is called when PayMongo redirects back after successful payment.
     * It updates the order and payment records accordingly.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentSuccess(Request $request)
    {
        // For PayMongo, we need to find the order differently since they don't pass session ID in redirect
        // We'll look for the most recent pending payment order for the current user
        $user = Auth::user();
        
        try {
            Log::info('Payment success callback received', [
                'user_id' => $user->id,
                'request_query' => $request->query()
            ]);
            
            // Find the most recent pending payment order for this user
            $order = Order::where('customer_user_id', $user->id)
                ->where('payment_method', 'online_payment')
                ->where('status', 'pending_payment')
                ->latest()
                ->first();
            
            if (!$order) {
                Log::warning('No pending payment order found for user', [
                    'user_id' => $user->id
                ]);
                return redirect()->route('customer.orders.index')
                    ->with('error', 'Order not found.');
            }
            
            Log::info('Found pending payment order', [
                'order_id' => $order->id,
                'payment_intent_id' => $order->payment_intent_id,
                'amount' => $order->final_total_amount
            ]);
            
            // Verify payment status with PayMongo using the order's payment intent ID
            $paymentStatus = $this->verifyPayMongoPayment($order->payment_intent_id);
            
            Log::info('PayMongo payment verification result', [
                'order_id' => $order->id,
                'payment_status' => $paymentStatus,
                'payment_intent_id' => $order->payment_intent_id
            ]);
            
            if ($paymentStatus === 'paid') {
                Log::info('Payment verified as paid, processing order finalization', [
                    'order_id' => $order->id
                ]);
                
                DB::beginTransaction();
                
                try {
                    // Update order status
                    $order->update([
                        'status' => 'processing',
                        'payment_status' => 'paid'
                    ]);
                    
                    Log::info('Order status updated to processing', [
                        'order_id' => $order->id,
                        'new_status' => 'processing',
                        'new_payment_status' => 'paid'
                    ]);
                    
                    // Log status change for online payment
                    $orderFulfillmentController = new \App\Http\Controllers\Customer\CustomerOrderFulfillmentController();
                    $orderFulfillmentController->logOrderStatusChange(
                        $order->id, 
                        'processing', 
                        'Payment verified and order confirmed', 
                        null
                    );
                    
                    // Update payment record
                    $payment = $order->payment;
                    if ($payment) {
                        $payment->update([
                            'status' => 'completed',
                            'payment_processed_at' => now(),
                            'payment_gateway_response' => ['status' => 'paid', 'verified_at' => now()]
                        ]);
                        
                        Log::info('Payment record updated to completed', [
                            'payment_id' => $payment->id,
                            'order_id' => $order->id
                        ]);
                    }
                    
                    DB::commit();
                    Log::info('Transaction committed successfully, triggering order fulfillment', [
                        'order_id' => $order->id
                    ]);
                    
                    // Trigger order fulfillment after transaction is committed
                    $orderFulfillmentController->finalizeOrder($order);
                    
                    Log::info('Order fulfillment completed successfully', [
                        'order_id' => $order->id
                    ]);
                    
                    return redirect()->route('customer.orders.show', $order->id)
                        ->with('success', 'Payment successful! Your order is now being processed.');
                    
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error('Error during payment success processing', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            } else {
                Log::warning('Payment verification failed or pending', [
                    'order_id' => $order->id,
                    'payment_status' => $paymentStatus
                ]);
                
                // Payment failed or pending
                return redirect()->route('customer.orders.show', $order->id)
                    ->with('warning', 'Payment verification pending. Please contact support if needed.');
            }
            
        } catch (Exception $e) {
            Log::error('Payment success callback failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('customer.orders.index')
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }
    
    /**
     * Handle PayMongo payment failure callback
     * 
     * This method is called when PayMongo redirects back after failed payment.
     * It updates the order status and provides feedback to the customer.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentFailed(Request $request)
    {
        // For PayMongo, we need to find the order differently since they don't pass session ID in redirect
        // We'll look for the most recent pending payment order for the current user
        $user = Auth::user();
        
        // Find the most recent pending payment order for this user
        $order = Order::where('customer_user_id', $user->id)
            ->where('payment_method', 'online_payment')
            ->where('status', 'pending_payment')
            ->latest()
            ->first();
        
        if ($order) {
                $order->update([
                    'status' => 'failed',
                    'payment_status' => 'failed'
                ]);
                
                // Log status change for failed payment
                $orderFulfillmentController = new \App\Http\Controllers\Customer\CustomerOrderFulfillmentController();
                $orderFulfillmentController->logOrderStatusChange(
                    $order->id, 
                    'failed', 
                    'Payment failed via callback', 
                    null
                );
                
                // Update payment record
                $payment = $order->payment;
                if ($payment) {
                    $payment->update([
                        'status' => 'failed',
                        'payment_gateway_response' => ['status' => 'failed', 'failed_at' => now()]
                    ]);
                }
                
                return redirect()->route('customer.orders.show', $order->id)
                    ->with('error', 'Payment failed. You can try placing the order again or use a different payment method.');
        }
        
        return redirect()->route('checkout.index')
            ->with('error', 'Payment was cancelled or failed. Please try again.');
    }
    
    /**
     * Create PayMongo checkout session
     * 
     * This private method handles the PayMongo API integration to create
     * a checkout session for online payments.
     * 
     * @param Order $order
     * @param array $orderSummary
     * @return array|null
     */
     private function createPayMongoCheckout(Order $order, array $orderSummary): ?array
    {
        try {
            // Check if PayMongo credentials are configured
            $secretKey = config('services.paymongo.secret_key');
            
            Log::info('PayMongo configuration check', [
                'has_secret_key' => !empty($secretKey),
                'secret_key_length' => $secretKey ? strlen($secretKey) : 0,
                'config_exists' => config('services.paymongo') !== null
            ]);
            
            if (empty($secretKey)) {
                Log::error('PayMongo secret key not configured', [
                    'config_path' => 'services.paymongo.secret_key',
                    'config_services' => config('services')
                ]);
                throw new Exception('Payment gateway not configured. Please contact support.');
            }
            
            $payloadData = [
                'data' => [
                    'attributes' => [
                        'cancel_url' => route('payment.failed'),
                        'success_url' => route('payment.success'),
                        'line_items' => [
                            [
                                'name' => 'Order #' . $order->id . ' - ' . $orderSummary['item_count'] . ' items',
                                'quantity' => 1,
                                'amount' => (int)($orderSummary['total_amount'] * 100), // Convert to centavos
                                'currency' => 'PHP',
                                'description' => 'Food delivery order with ' . $orderSummary['item_count'] . ' items (including delivery fee)',
                            ]
                        ],
                        'payment_method_types' => ['card', 'gcash', 'grab_pay', 'paymaya'],
                        'description' => 'Food Delivery Order Payment',
                        'reference_number' => (string)$order->id,
                    ]
                ]
            ];
            
            Log::info('PayMongo API request payload', [
                'order_id' => $order->id,
                'payload' => $payloadData,
                'cancel_url' => route('payment.failed'),
                'success_url' => route('payment.success')
            ]);

            $response = Http::withBasicAuth($secretKey, '')
                ->timeout(30) // Add timeout
                ->post('https://api.paymongo.com/v1/checkout_sessions', $payloadData);
            
            Log::info('PayMongo API response', [
                'order_id' => $order->id,
                'status_code' => $response->status(),
                'successful' => $response->successful(),
                'response_headers' => $response->headers(),
                'response_body' => $response->body()
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('PayMongo checkout session created successfully', [
                    'order_id' => $order->id,
                    'checkout_session_id' => $data['data']['id'],
                    'checkout_url' => $data['data']['attributes']['checkout_url']
                ]);
                
                return [
                    'id' => $data['data']['id'],
                    'checkout_url' => $data['data']['attributes']['checkout_url'],
                    'payment_intent' => $data['data']['attributes']['payment_intent'] ?? null,
                ];
            }
            
            Log::error('PayMongo API Error Response', [
                'order_id' => $order->id,
                'status_code' => $response->status(),
                'response_body' => $response->body(),
                'response_json' => $response->json()
            ]);
            
            return null;
            
        } catch (Exception $e) {
            Log::error('PayMongo checkout creation exception', [
                'order_id' => $order->id,
                'exception_message' => $e->getMessage(),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
    
    /**
     * Verify PayMongo payment status
     * 
     * This private method verifies the payment status with PayMongo API
     * to ensure the payment was actually completed.
     * 
     * @param string $checkoutSessionId
     * @return string
     */
    private function verifyPayMongoPayment(string $checkoutSessionId): string
    {
        try {
            $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
                ->get("https://api.paymongo.com/v1/checkout_sessions/{$checkoutSessionId}");
            
            if ($response->successful()) {
                $data = $response->json();
                $status = $data['data']['attributes']['payment_intent']['attributes']['status'] ?? 'failed';
                
                // Map PayMongo statuses to our internal statuses
                return match($status) {
                    'succeeded' => 'paid',
                    'processing' => 'pending',
                    default => 'failed'
                };
            }
            
            return 'failed';
            
        } catch (Exception $e) {
            Log::error('PayMongo verification exception: ' . $e->getMessage());
            return 'failed';
        }
    }
    
    /**
     * Handle payment success from webhook
     * 
     * @param array $payload
     * @return void
     */
    private function handlePaymentSuccess(array $payload): void
    {
        try {
            $paymentIntentId = $payload['data']['id'] ?? null;
            
            if ($paymentIntentId) {
                $order = Order::where('payment_intent_id', $paymentIntentId)->first();
                
                if ($order) {
                    $order->update([
                        'status' => 'processing',
                        'payment_status' => 'paid'
                    ]);
                    
                    // Log status change for webhook payment success
                    $orderFulfillmentController = new \App\Http\Controllers\Customer\CustomerOrderFulfillmentController();
                    $orderFulfillmentController->logOrderStatusChange(
                        $order->id, 
                        'processing', 
                        'Payment verified via webhook', 
                        null
                    );
                    
                    // Update payment record
                    $payment = $order->payment;
                    if ($payment) {
                        $payment->update([
                            'status' => 'completed',
                            'payment_processed_at' => now(),
                            'payment_gateway_response' => ['status' => 'paid', 'webhook_processed_at' => now()]
                        ]);
                    }
                    
                    // Clear shopping cart
                    ShoppingCartItem::where('user_id', $order->customer_user_id)->delete();
                    
                    Log::info('Payment success processed via webhook', ['order_id' => $order->id]);
                }
            }
        } catch (Exception $e) {
            Log::error('Webhook payment success processing failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Handle payment failure from webhook
     * 
     * @param array $payload
     * @return void
     */
    private function handlePaymentFailure(array $payload): void
    {
        try {
            $paymentIntentId = $payload['data']['id'] ?? null;
            
            if ($paymentIntentId) {
                $order = Order::where('payment_intent_id', $paymentIntentId)->first();
                
                if ($order) {
                    $order->update([
                        'status' => 'failed',
                        'payment_status' => 'failed'
                    ]);
                    
                    // Log status change for webhook payment failure
                    $orderFulfillmentController = new \App\Http\Controllers\Customer\CustomerOrderFulfillmentController();
                    $orderFulfillmentController->logOrderStatusChange(
                        $order->id, 
                        'failed', 
                        'Payment failed via webhook', 
                        null
                    );
                    
                    // Update payment record
                    $payment = $order->payment;
                    if ($payment) {
                        $payment->update([
                            'status' => 'failed',
                            'payment_gateway_response' => ['status' => 'failed', 'webhook_processed_at' => now()]
                        ]);
                    }
                    
                    Log::info('Payment failure processed via webhook', ['order_id' => $order->id]);
                }
            }
        } catch (Exception $e) {
            Log::error('Webhook payment failure processing failed: ' . $e->getMessage());
        }
    }
}
