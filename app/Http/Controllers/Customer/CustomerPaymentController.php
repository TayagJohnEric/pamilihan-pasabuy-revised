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
            
        } else {
            // Get order summary from session (set by checkout process)
            $orderSummary = session('order_summary');
            
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
    public function processOnlinePayment(Request $request)
    {
        $user = Auth::user();
        $orderSummary = session('order_summary');
        
        Log::info('Payment processing started', [
            'user_id' => $user->id,
            'has_order_summary' => !empty($orderSummary),
            'payment_method' => $orderSummary['payment_method'] ?? 'none'
        ]);
        
        if (!$orderSummary || $orderSummary['payment_method'] !== 'online_payment') {
            Log::warning('Invalid payment session', [
                'user_id' => $user->id,
                'order_summary' => $orderSummary
            ]);
            return redirect()->route('checkout.index')
                ->with('error', 'Invalid payment session. Please try again.');
        }
        
        DB::beginTransaction();
        
        try {
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
            
            // Create order items from cart
            foreach ($orderSummary['cart_items'] as $cartItem) {
                $order->orderItems()->create([
                    'product_id' => $cartItem->product_id,
                    'status' => 'pending',
                    'product_name_snapshot' => $cartItem->product->product_name,
                    'quantity_requested' => $cartItem->quantity ?? 1,
                    'unit_price_snapshot' => $cartItem->product->price,
                    'customer_budget_requested' => $cartItem->customer_budget,
                    'customerNotes_snapshot' => $cartItem->customer_notes,
                ]);
            }
            
            // Create draft payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount_paid' => $orderSummary['total_amount'],
                'payment_method_used' => 'online_payment',
                'status' => 'pending',
            ]);
            
            // Create PayMongo checkout session
            $checkoutSession = $this->createPayMongoCheckout($order, $orderSummary);
            
            if (!$checkoutSession) {
                throw new Exception('Failed to create PayMongo checkout session');
            }
            
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
            
            // Clear order summary from session
            session()->forget('order_summary');
            
            // Redirect to PayMongo checkout URL
            return redirect($checkoutSession['checkout_url']);
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('PayMongo checkout creation failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'order_summary' => $orderSummary,
                'exception' => $e
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
        
        if (!$orderSummary || $orderSummary['payment_method'] !== 'cash_on_delivery') {
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
            
            DB::commit();
            
            Log::info('COD order transaction committed successfully', [
                'order_id' => $order->id,
                'transaction_committed' => true
            ]);
            
            // For COD orders, we don't need to call finalizeOrder since order is already properly set up
            // Just send notifications to vendors
            $orderFulfillmentController = new \App\Http\Controllers\Customer\CustomerOrderFulfillmentController();
            
            // Log the initial order status in history
            $orderFulfillmentController->logOrderStatusChange(
                $order->id, 
                'processing', 
                'Order placed successfully via COD', 
                null
            );
            
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
        $checkoutSessionId = $request->query('checkout_session_id');
        
        if (!$checkoutSessionId) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Invalid payment callback.');
        }
        
        try {
            // Find order by payment intent ID
            $order = Order::where('payment_intent_id', $checkoutSessionId)->first();
            
            if (!$order) {
                return redirect()->route('customer.orders.index')
                    ->with('error', 'Order not found.');
            }
            
            // Verify payment status with PayMongo
            $paymentStatus = $this->verifyPayMongoPayment($checkoutSessionId);
            
            if ($paymentStatus === 'paid') {
                DB::beginTransaction();
                
                try {
                    // Update order status
                    $order->update([
                        'status' => 'processing',
                        'payment_status' => 'paid'
                    ]);
                    
                    // Update payment record
                    $payment = $order->payment;
                    if ($payment) {
                        $payment->update([
                            'status' => 'completed',
                            'payment_processed_at' => now(),
                            'payment_gateway_response' => ['status' => 'paid', 'verified_at' => now()]
                        ]);
                    }
                    
                    // Clear shopping cart
                    ShoppingCartItem::where('user_id', $order->customer_user_id)->delete();
                    
                    DB::commit();
                    
                    // Trigger order fulfillment after transaction is committed
                    $orderFulfillmentController = new \App\Http\Controllers\Customer\CustomerOrderFulfillmentController();
                    $orderFulfillmentController->finalizeOrder($order);
                    
                    return redirect()->route('customer.orders.show', $order->id)
                        ->with('success', 'Payment successful! Your order is now being processed.');
                    
                } catch (Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            } else {
                // Payment failed or pending
                return redirect()->route('customer.orders.show', $order->id)
                    ->with('warning', 'Payment verification pending. Please contact support if needed.');
            }
            
        } catch (Exception $e) {
            Log::error('Payment success callback failed: ' . $e->getMessage());
            
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
        $checkoutSessionId = $request->query('checkout_session_id');
        
        if ($checkoutSessionId) {
            // Find and update order
            $order = Order::where('payment_intent_id', $checkoutSessionId)->first();
            
            if ($order) {
                $order->update([
                    'status' => 'failed',
                    'payment_status' => 'failed'
                ]);
                
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
            if (empty($secretKey)) {
                Log::error('PayMongo secret key not configured');
                throw new Exception('Payment gateway not configured. Please contact support.');
            }

            $response = Http::withBasicAuth($secretKey, '')
                ->post('https://api.paymongo.com/v1/checkout_sessions', [
                    'data' => [
                        'attributes' => [
                            'cancel_url' => route('payment.failed', ['checkout_session_id' => '__CHECKOUT_SESSION_ID__']),
                            'success_url' => route('payment.success', ['checkout_session_id' => '__CHECKOUT_SESSION_ID__']),
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
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                Log::info('PayMongo checkout session created successfully', [
                    'order_id' => $order->id,
                    'checkout_session_id' => $data['data']['id']
                ]);
                return [
                    'id' => $data['data']['id'],
                    'checkout_url' => $data['data']['attributes']['checkout_url'],
                    'payment_intent' => $data['data']['attributes']['payment_intent'] ?? null,
                ];
            }
            
            Log::error('PayMongo API Error: ' . $response->body(), [
                'order_id' => $order->id,
                'status_code' => $response->status(),
                'response_body' => $response->body()
            ]);
            return null;
            
        } catch (Exception $e) {
            Log::error('PayMongo checkout creation exception: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'exception' => $e
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
