<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ShoppingCartItem;
use App\Models\SavedAddress;
use App\Models\District;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerCheckoutController extends Controller
{
    /**
     * Display the checkout page with all necessary information
     * 
     * This method handles the unified checkout step where customers make all
     * their final decisions before placing an order including:
     * - Reviewing cart items
     * - Selecting delivery address
     * - Choosing payment method
     * - Selecting rider preference
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get cart items with product and vendor relationships
        $cartItems = ShoppingCartItem::with(['product.vendor', 'product'])
            ->where('user_id', $user->id)
            ->get();
        
        // Redirect to cart if empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('warning', 'Your cart is empty. Add some items before checkout.');
        }
        
        // Validate cart items (check product availability and stock)
        $invalidItems = [];
        foreach ($cartItems as $item) {
            if (!$item->is_valid) {
                $invalidItems[] = $item->product->product_name;
            }
        }
        
        if (!empty($invalidItems)) {
            return redirect()->route('cart.index')
                ->with('error', 'Some items in your cart are no longer available: ' . implode(', ', $invalidItems));
        }
        
        // Get user's saved addresses with district information
        $savedAddresses = SavedAddress::with('district')
            ->where('user_id', $user->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get available districts if user has no saved addresses
        $availableDistricts = District::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Get available riders for "Choose My Rider" option
        $availableRiders = User::with('rider')
            ->whereHas('rider', function ($query) {
                $query->where('is_available', true)
                    ->where('verification_status', 'verified');
            })
            ->where('role', 'rider')
            ->where('is_active', true)
            ->select(['id', 'first_name', 'last_name', 'profile_image_url'])
            ->get();
        
        // Calculate cart summary
        $subtotal = $cartItems->sum('subtotal');
        $itemCount = $cartItems->count();
        $hasBudgetItems = $cartItems->where('customer_budget', '!=', null)->count() > 0;
        
        // Payment methods available
        $paymentMethods = [
            'cash_on_delivery' => 'Cash on Delivery',
            'online_payment' => 'Online Payment'
        ];
        
        return view('customer.checkout.index', compact(
            'cartItems',
            'savedAddresses', 
            'availableDistricts',
            'availableRiders',
            'subtotal',
            'itemCount',
            'hasBudgetItems',
            'paymentMethods'
        ));
    }
    
    /**
     * Get delivery fee for a specific address via AJAX
     * 
     * This method is called when user selects a delivery address
     * to instantly display the delivery fee from the district
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeliveryFee(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:saved_addresses,id'
        ]);
        
        $address = SavedAddress::with('district')
            ->where('id', $request->address_id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'delivery_fee' => $address->district->delivery_fee,
            'district_name' => $address->district->name,
            'formatted_fee' => '₱' . number_format($address->district->delivery_fee, 2)
        ]);
    }
    
    /**
     * Process the checkout form and prepare order placement
     * 
     * This method handles all the customer's final decisions:
     * - Validates the form data
     * - Processes rider selection logic
     * - Prepares order data for final confirmation
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function process(Request $request)
    {
        $user = Auth::user();
        
        // Validate the checkout form
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
                Rule::in(['cash_on_delivery', 'online_payment'])
            ],
            'rider_selection_type' => [
                'required',
                Rule::in(['choose_rider', 'system_assign'])
            ],
            'selected_rider_id' => [
                'required_if:rider_selection_type,choose_rider',
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->rider_selection_type === 'choose_rider' && $value) {
                        $rider = User::with('rider')
                            ->where('id', $value)
                            ->where('role', 'rider')
                            ->where('is_active', true)
                            ->whereHas('rider', function ($query) {
                                $query->where('is_available', true)
                                    ->where('verification_status', 'verified');
                            })
                            ->first();
                        
                        if (!$rider) {
                            $fail('Selected rider is not available.');
                        }
                    }
                }
            ]
        ]);
        
        // Re-validate cart items
        $cartItems = ShoppingCartItem::with(['product.vendor', 'product'])
            ->where('user_id', $user->id)
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }
        
        // Final validation of cart items
        foreach ($cartItems as $item) {
            if (!$item->is_valid) {
                return redirect()->route('cart.index')
                    ->with('error', 'Some items in your cart are no longer available. Please review your cart.');
            }
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
            } else {
                return redirect()->back()
                    ->with('error', 'No riders are currently available. Please try again later.')
                    ->withInput();
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
            'payment_method' => $validated['payment_method'],
            'rider_selection_type' => $validated['rider_selection_type'],
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_amount' => $totalAmount,
            'item_count' => $cartItems->count(),
            'has_budget_items' => $cartItems->where('customer_budget', '!=', null)->count() > 0
        ];
        
        // Store order summary in session for final confirmation
        session(['order_summary' => $orderSummary]);
        
        // Redirect based on payment method
        if ($validated['payment_method'] === 'online_payment') {
            // For online payment, redirect to payment confirmation
            return redirect()->route('checkout.payment-confirmation')
                ->with('success', 'Please review your order and proceed to payment.');
        } else {
            // For COD, redirect to final confirmation
            return redirect()->route('checkout.confirmation')
                ->with('success', 'Please review your order before final confirmation.');
        }
    }
    
    /**
     * Display payment confirmation page for online payments (PayMongo)
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function paymentConfirmation()
    {
        $orderSummary = session('order_summary');
        
        if (!$orderSummary || $orderSummary['payment_method'] !== 'online_payment') {
            return redirect()->route('checkout.index')
                ->with('error', 'Invalid checkout session. Please try again.');
        }
        
        return view('customer.checkout.payment-confirmation', compact('orderSummary'));
    }
    
    /**
     * Display final order confirmation page
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function confirmation()
    {
        $orderSummary = session('order_summary');
        
        if (!$orderSummary) {
            return redirect()->route('checkout.index')
                ->with('error', 'Invalid checkout session. Please try again.');
        }
        
        return view('customer.checkout.confirmation', compact('orderSummary'));
    }
    
    /**
     * Place the final order (this would integrate with your Order creation logic)
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeOrder(Request $request)
    {
        $orderSummary = session('order_summary');
        
        if (!$orderSummary) {
            return redirect()->route('checkout.index')
                ->with('error', 'Invalid checkout session. Please start over.');
        }
        
        try {
            DB::transaction(function () use ($orderSummary, $request) {
                // Here you would implement your Order creation logic
                // This is a placeholder for the actual order creation
                
                /*
                Example Order creation (adjust according to your Order model):
                
                $order = Order::create([
                    'customer_user_id' => Auth::id(),
                    'rider_user_id' => $orderSummary['selected_rider']->id,
                    'delivery_address_id' => $orderSummary['delivery_address']->id,
                    'payment_method' => $orderSummary['payment_method'],
                    'subtotal' => $orderSummary['subtotal'],
                    'delivery_fee' => $orderSummary['delivery_fee'],
                    'total_amount' => $orderSummary['total_amount'],
                    'status' => 'pending',
                ]);
                
                // Create order items from cart
                foreach ($orderSummary['cart_items'] as $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'unit_price' => $cartItem->product->price,
                        'subtotal' => $cartItem->subtotal,
                        'customer_budget' => $cartItem->customer_budget,
                        'customer_notes' => $cartItem->customer_notes,
                    ]);
                }
                */
                
                // Clear cart after successful order
                ShoppingCartItem::where('user_id', Auth::id())->delete();
                
                // Clear session
                session()->forget('order_summary');
            });
            
            return redirect()->route('orders.success')
                ->with('success', 'Your order has been placed successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to place order. Please try again.');
        }
    }
}
