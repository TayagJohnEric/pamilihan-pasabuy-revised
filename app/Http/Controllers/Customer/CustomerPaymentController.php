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
     */
    public function paymentConfirmation(Request $request)
    {
        $user = Auth::user();
        
        Log::info('Payment confirmation accessed', [
            'user_id' => $user->id,
            'request_method' => $request->method(),
            'has_order_summary' => session()->has('order_summary')
        ]);
        
        if ($request->isMethod('post')) {
            $orderSummary = $this->processCheckoutForm($request);
            session(['order_summary' => $orderSummary]);
        } else {
            $orderSummary = session('order_summary');
            
            if (!$orderSummary) {
                return redirect()->route('checkout.index')
                    ->with('error', 'Order session expired. Please complete checkout again.');
            }
            
            if ($orderSummary['payment_method'] !== 'online_payment') {
                return redirect()->route('checkout.confirmation')
                    ->with('info', 'Redirected to order confirmation for COD.');
            }
        }
        
        // Validate cart items are still available
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
     */
    public function processOnlinePayment(Request $request)
    {
        $orderSummary = session('order_summary');
        
        if (!$orderSummary || $orderSummary['payment_method'] !== 'online_payment') {
            return redirect()->route('checkout.index')
                ->with('error', 'Invalid payment session. Please try again.');
        }
        
        DB::beginTransaction();
        
        try {
            // Create order with pending_payment status
            $order = $this->createOrderFromSession($orderSummary, 'online_payment', $request->input('special_instructions'));
            
            // Create order items
            $this->createOrderItems($order, $orderSummary['cart_items']);
            
            // Create payment record
            $payment = $this->createPaymentRecord($order, $orderSummary['total_amount'], 'online_payment');
            
            // Create PayMongo checkout session
            $checkoutSession = $this->createPayMongoCheckout($order, $orderSummary);
            
            if (!$checkoutSession) {
                throw new Exception('Failed to create PayMongo checkout session');
            }
            
            // Update order with checkout session ID
            $order->update(['payment_intent_id' => $checkoutSession['id']]);
            $payment->update([
                'gateway_transaction_id' => $checkoutSession['id'],
                'payment_gateway_response' => $checkoutSession,
            ]);
            
            DB::commit();
            
            session()->forget('order_summary');
            
            return redirect()->away($checkoutSession['checkout_url']);
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);
            
            return redirect()->back()
                ->with('error', 'Payment processing failed. Please try again.');
        }
    }
    
    /**
     * Process Cash on Delivery (COD) order
     */
    public function processCOD(Request $request)
    {
        $orderSummary = session('order_summary');
        
        if (!$orderSummary || $orderSummary['payment_method'] !== 'cod') {
            return redirect()->route('checkout.index')
                ->with('error', 'Invalid order session. Please try again.');
        }
        
        DB::beginTransaction();
        
        try {
            // Create order with processing status
            $order = $this->createOrderFromSession($orderSummary, 'cod', $request->input('special_instructions'));
            
            // Create order items
            $this->createOrderItems($order, $orderSummary['cart_items']);
            
            // Create payment record
            $this->createPaymentRecord($order, $orderSummary['total_amount'], 'cod');
            
            DB::commit();
            
            // Trigger order finalization (handles stock, cart clearing, notifications)
            $fulfillmentController = new CustomerOrderFulfillmentController();
            $fulfillmentController->finalizeOrder($order);
            
            session()->forget('order_summary');
            
            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Order placed successfully! Your order will be prepared for delivery.');
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('COD order creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);
            
            return redirect()->back()
                ->with('error', 'Order placement failed. Please try again.');
        }
    }
    
    /**
     * Handle PayMongo payment success callback
     */
    public function paymentSuccess(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Find the most recent pending payment order for this user
            $order = Order::where('customer_user_id', $user->id)
                ->where('payment_method', 'online_payment')
                ->where('status', 'pending_payment')
                ->latest()
                ->first();
            
            if (!$order) {
                return redirect()->route('customer.orders.index')
                    ->with('error', 'Order not found.');
            }
            
            // Verify payment status with PayMongo
            $paymentStatus = $this->verifyPayMongoPayment($order->payment_intent_id);
            
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
                        ]);
                    }
                    
                    DB::commit();
                    
                    // Trigger order finalization after successful payment
                    $fulfillmentController = new CustomerOrderFulfillmentController();
                    $fulfillmentController->finalizeOrder($order);
                    
                    return redirect()->route('customer.orders.show', $order->id)
                        ->with('success', 'Payment successful! Your order is now being processed.');
                    
                } catch (Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            } else {
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
     */
    public function paymentFailed(Request $request)
    {
        $user = Auth::user();
        
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
            
            $payment = $order->payment;
            if ($payment) {
                $payment->update(['status' => 'failed']);
            }
            
            return redirect()->route('customer.orders.show', $order->id)
                ->with('error', 'Payment failed. You can try placing the order again.');
        }
        
        return redirect()->route('checkout.index')
            ->with('error', 'Payment was cancelled or failed. Please try again.');
    }
    
    // ==================== PRIVATE HELPER METHODS ====================
    
    /**
     * Process checkout form data into order summary
     */
    private function processCheckoutForm(Request $request): array
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'delivery_address_id' => 'required|exists:saved_addresses,id',
            'payment_method' => 'required|in:online_payment',
            'rider_selection_type' => 'required|in:choose_rider,system_assign',
            'selected_rider_id' => 'required_if:rider_selection_type,choose_rider|nullable|exists:users,id'
        ]);
        
        // Get cart items and delivery address
        $cartItems = ShoppingCartItem::with(['product.vendor', 'product'])
            ->where('user_id', $user->id)
            ->get();
        
        if ($cartItems->isEmpty()) {
            throw new Exception('Cart is empty');
        }
        
        $deliveryAddress = SavedAddress::with('district')
            ->where('id', $validated['delivery_address_id'])
            ->where('user_id', $user->id)
            ->first();
        
        // Process rider selection
        $selectedRider = null;
        if ($validated['rider_selection_type'] === 'choose_rider') {
            $selectedRider = User::find($validated['selected_rider_id']);
        }
        
        // Calculate totals
        $subtotal = $cartItems->sum('subtotal');
        $deliveryFee = $deliveryAddress->district->delivery_fee;
        $totalAmount = $subtotal + $deliveryFee;
        
        return [
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
    }
    
    /**
     * Create order record from session data
     */
    private function createOrderFromSession(array $orderSummary, string $paymentMethod, ?string $specialInstructions = null): Order
    {
        $user = Auth::user();
        
        return Order::create([
            'customer_user_id' => $user->id,
            'rider_user_id' => $orderSummary['selected_rider']->id ?? null,
            'preferred_rider_id' => $orderSummary['rider_selection_type'] === 'choose_rider' 
                ? $orderSummary['selected_rider']->id : null,
            'delivery_address_id' => $orderSummary['delivery_address']->id,
            'order_date' => now(),
            'status' => $paymentMethod === 'cod' ? 'processing' : 'pending_payment',
            'delivery_fee' => $orderSummary['delivery_fee'],
            'final_total_amount' => $orderSummary['total_amount'],
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'special_instructions' => $specialInstructions,
        ]);
    }
    
    /**
     * Create order items from cart items
     */
    private function createOrderItems(Order $order, $cartItems): void
    {
        foreach ($cartItems as $cartItem) {
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
    }
    
    /**
     * Create payment record
     */
    private function createPaymentRecord(Order $order, float $amount, string $paymentMethod): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'amount_paid' => $amount,
            'payment_method_used' => $paymentMethod,
            'status' => 'pending',
        ]);
    }
    
    /**
     * Create PayMongo checkout session
     */
    private function createPayMongoCheckout(Order $order, array $orderSummary): ?array
    {
        try {
            $secretKey = config('services.paymongo.secret_key');
            
            if (empty($secretKey)) {
                throw new Exception('Payment gateway not configured');
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
                                'description' => 'Food delivery order',
                            ]
                        ],
                        'payment_method_types' => ['card', 'gcash', 'grab_pay', 'paymaya'],
                        'description' => 'Food Delivery Order Payment',
                        'reference_number' => (string)$order->id,
                    ]
                ]
            ];

            $response = Http::withBasicAuth($secretKey, '')
                ->timeout(30)
                ->post('https://api.paymongo.com/v1/checkout_sessions', $payloadData);
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'id' => $data['data']['id'],
                    'checkout_url' => $data['data']['attributes']['checkout_url'],
                ];
            }
            
            Log::error('PayMongo API Error', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return null;
            
        } catch (Exception $e) {
            Log::error('PayMongo checkout creation failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Verify PayMongo payment status
     */
    private function verifyPayMongoPayment(string $checkoutSessionId): string
    {
        try {
            $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
                ->get("https://api.paymongo.com/v1/checkout_sessions/{$checkoutSessionId}");
            
            if ($response->successful()) {
                $data = $response->json();
                $status = $data['data']['attributes']['payment_intent']['attributes']['status'] ?? 'failed';
                
                return match($status) {
                    'succeeded' => 'paid',
                    'processing' => 'pending',
                    default => 'failed'
                };
            }
            
            return 'failed';
            
        } catch (Exception $e) {
            Log::error('PayMongo verification failed: ' . $e->getMessage());
            return 'failed';
        }
    }
}