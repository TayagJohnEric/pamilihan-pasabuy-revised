<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Models\Product;
use App\Models\ShoppingCartItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerShoppingCartController extends Controller
{
      protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display the shopping cart
     */
    public function index()
    {
        // Validate cart items before displaying
        $invalidItems = $this->cartService->validateCartItems();
        
        if (!empty($invalidItems)) {
            // Remove invalid items and notify user
            foreach ($invalidItems as $item) {
                $item->delete();
            }
            
            session()->flash('warning', 'Some items in your cart were removed because they are no longer available.');
        }

        $cartSummary = $this->cartService->getCartSummary();

        return view('customer.cart.index', [
            'cartItems' => $cartSummary['items'],
            'subtotal' => $cartSummary['subtotal'],
            'itemCount' => $cartSummary['item_count'],
            'totalQuantity' => $cartSummary['total_quantity'],
        ]);
    }

    /**
     * Add item to cart
     */
    public function store(AddToCartRequest $request)
    {
        try {
            $this->cartService->addToCart(
                $request->product_id,
                $request->quantity ?? 1,
                $request->customer_budget,
                $request->customer_notes
            );

            // Get product name for success message
            $product = Product::find($request->product_id);
            $message = "'{$product->product_name}' has been added to your cart!";

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update cart item
     */
    public function update(Request $request, ShoppingCartItem $cartItem)
{
    if ($cartItem->user_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    $product = $cartItem->product;

    if (!$product->is_available) {
        return $request->ajax()
            ? response()->json(['message' => 'Product is no longer available.'], 400)
            : redirect()->route('cart.index')->with('error', 'This product is no longer available.');
    }

    try {
        if ($product->is_budget_based) {
            $validated = $request->validate([
                'customer_budget' => 'required|numeric|min:0.01|max:999999.99',
                'customer_notes' => 'nullable|string|max:500',
            ]);

            $cartItem->update($validated);
        } else {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:' . $product->quantity_in_stock,
            ]);

            $cartItem->update($validated);
        }

        if ($request->ajax()) {
            return response()->json(['message' => 'Cart updated successfully.']);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        if ($request->ajax()) {
            return response()->json(['message' => 'Validation failed.', 'errors' => $e->errors()], 422);
        }

        return redirect()->route('cart.index')->withErrors($e->errors())->withInput();
    }
}

    /**
     * Remove item from cart
     */
    public function destroy(ShoppingCartItem $cartItem)
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cartItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $productName = $cartItem->product->product_name;
        $cartItem->delete();

        return redirect()->route('cart.index')
                       ->with('success', "'{$productName}' has been removed from your cart.");
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $itemCount = Auth::user()->shoppingCartItems()->count();
        
        if ($itemCount > 0) {
            $this->cartService->clearCart();
            return redirect()->route('cart.index')
                           ->with('success', "All {$itemCount} item(s) have been removed from your cart.");
        }

        return redirect()->route('cart.index')
                       ->with('info', 'Your cart is already empty.');
    }

    /**
     * Get cart count for header display (AJAX)
     */
    public function getCartCount()
    {
        $count = Auth::user()->shoppingCartItems()->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Get cart summary for mini-cart display (AJAX)
     */
    public function getCartSummary()
    {
        $cartSummary = $this->cartService->getCartSummary();
        
        return response()->json([
            'count' => $cartSummary['item_count'],
            'subtotal' => $cartSummary['subtotal'],
            'items' => $cartSummary['items']->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product->product_name,
                    'product_image' => $item->product->image_url,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $item->subtotal,
                    'is_budget_based' => $item->product->is_budget_based,
                    'customer_budget' => $item->customer_budget,
                ];
            }),
        ]);
    }

    /**
     * Quick add to cart (for AJAX requests)
     */
    public function quickAdd(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'customer_budget' => 'nullable|numeric|min:0.01',
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            
            $this->cartService->addToCart(
                $request->product_id,
                $request->quantity ?? 1,
                $request->customer_budget,
                $request->customer_notes
            );

            $cartSummary = $this->cartService->getCartSummary();

            return response()->json([
                'success' => true,
                'message' => "'{$product->product_name}' added to cart!",
                'cart_count' => $cartSummary['item_count'],
                'cart_subtotal' => $cartSummary['subtotal'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
