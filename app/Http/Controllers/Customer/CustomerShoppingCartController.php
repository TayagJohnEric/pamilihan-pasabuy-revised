<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ShoppingCartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerShoppingCartController extends Controller
{
    /**
     * Display the shopping cart
     */
    public function index()
    {
        // Validate cart items before displaying
        $invalidItems = $this->validateCartItems();
        
        if (!empty($invalidItems)) {
            // Remove invalid items and notify user
            foreach ($invalidItems as $item) {
                $item->delete();
            }
            
            session()->flash('warning', 'Some items in your cart were removed because they are no longer available.');
        }

        $cartSummary = $this->getCartSummary();

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
    public function store(Request $request)
    {
        // Validate the request using the same logic as AddToCartRequest
        $this->validateAddToCartRequest($request);

        try {
            // Determine if this is a budget-based purchase
            $isBudgetPurchase = $request->filled('customer_budget') && $request->customer_budget > 0;
            
            $this->addToCart(
                $request->product_id,
                $request->quantity ?? 1,
                $isBudgetPurchase ? $request->customer_budget : null,
                $isBudgetPurchase ? $request->customer_notes : null,
                $isBudgetPurchase
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
            // Check if this cart item is currently being used as budget-based
            $isCurrentlyBudgetBased = !is_null($cartItem->customer_budget);
            
            // Check if this is a conversion to budget-based (from regular price)
            $isConvertingToBudget = !$isCurrentlyBudgetBased && $request->filled('customer_budget') && $request->customer_budget > 0;
            
            if ($isConvertingToBudget) {
                // Converting from regular price to budget-based
                $validated = $request->validate([
                    'customer_budget' => 'required|numeric|min:0.01|max:999999.99',
                    'customer_notes' => 'nullable|string|max:500',
                ]);

                // Update the cart item to be budget-based
                $cartItem->update([
                    'customer_budget' => $validated['customer_budget'],
                    'customer_notes' => $validated['customer_notes'],
                    'quantity' => 1, // Budget-based items always have quantity 1
                ]);

                $message = 'Item converted to budget-based pricing successfully!';
            } elseif ($isCurrentlyBudgetBased) {
                // Updating existing budget-based item
                $validated = $request->validate([
                    'customer_budget' => 'required|numeric|min:0.01|max:999999.99',
                    'customer_notes' => 'nullable|string|max:500',
                ]);

                $cartItem->update($validated);
                $message = 'Budget-based item updated successfully!';
            } else {
                // Updating regular price item
                $validated = $request->validate([
                    'quantity' => 'required|integer|min:1|max:' . $product->quantity_in_stock,
                ]);

                $cartItem->update($validated);
                $message = 'Cart updated successfully!';
            }

            if ($request->ajax()) {
                return response()->json(['message' => $message]);
            }

            return redirect()->route('cart.index')->with('success', $message);

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
            $this->clearCart();
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
     * Get cart summary for mini-cart display (AJAX) IN THE HEADER
     */
    public function getCartSummaryAjax()
    {
        $cartSummary = $this->getCartSummary();
        
        return response()->json([
            'count' => $cartSummary['item_count'],
            'subtotal' => $cartSummary['subtotal'],
            'items' => $cartSummary['items']->map(function ($item) {
                $isBudgetBased = !is_null($item->customer_budget);
                
                return [
                    'id' => $item->id,
                    'product_name' => $item->product->product_name,
                    'product_image' => $item->product->image_url,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $item->subtotal,
                    'is_budget_based' => $isBudgetBased,
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
            
            // Determine if this is a budget-based purchase
            $isBudgetPurchase = $request->filled('customer_budget') && $request->customer_budget > 0;
            
            $this->addToCart(
                $request->product_id,
                $request->quantity ?? 1,
                $isBudgetPurchase ? $request->customer_budget : null,
                $isBudgetPurchase ? $request->customer_notes : null,
                $isBudgetPurchase
            );

            $cartSummary = $this->getCartSummary();

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

    // ===== MERGED CART SERVICE METHODS =====

    private function addToCart($productId, $quantity = 1, $customerBudget = null, $customerNotes = null, $isBudgetPurchase = false)
    {
        $product = Product::findOrFail($productId);
        
        // Validate product availability
        if (!$product->is_available || (!$product->is_budget_based && !$isBudgetPurchase && $product->quantity_in_stock <= 0)) {
            throw new \Exception('Product is not available.');
        }
        
        return DB::transaction(function () use ($product, $quantity, $customerBudget, $customerNotes, $isBudgetPurchase) {
            // Look for existing cart item with same purchase type
            $cartItem = ShoppingCartItem::where('user_id', Auth::id())
                                      ->where('product_id', $product->id)
                                      ->where(function($query) use ($isBudgetPurchase) {
                                          if ($isBudgetPurchase) {
                                              $query->whereNotNull('customer_budget');
                                          } else {
                                              $query->whereNull('customer_budget');
                                          }
                                      })
                                      ->first();
            
            if ($cartItem) {
                return $this->updateExistingCartItem($cartItem, $quantity, $customerBudget, $customerNotes, $isBudgetPurchase);
            } else {
                return $this->createNewCartItem($product, $quantity, $customerBudget, $customerNotes, $isBudgetPurchase);
            }
        });
    }

    private function updateExistingCartItem($cartItem, $quantity, $customerBudget, $customerNotes, $isBudgetPurchase)
    {
        $product = $cartItem->product;
        
        if ($isBudgetPurchase) {
            $cartItem->update([
                'customer_budget' => $customerBudget,
                'customer_notes' => $customerNotes,
            ]);
        } else {
            $newQuantity = $cartItem->quantity + $quantity;
            if (!$product->is_budget_based && $newQuantity > $product->quantity_in_stock) {
                $newQuantity = $product->quantity_in_stock;
            }
            $cartItem->update(['quantity' => $newQuantity]);
        }
        
        return $cartItem;
    }

    private function createNewCartItem($product, $quantity, $customerBudget, $customerNotes, $isBudgetPurchase)
    {
        return ShoppingCartItem::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => $isBudgetPurchase ? 1 : $quantity,
            'customer_budget' => $isBudgetPurchase ? $customerBudget : null,
            'customer_notes' => $isBudgetPurchase ? $customerNotes : null,
        ]);
    }

    private function getCartSummary()
    {
        $cartItems = Auth::user()->shoppingCartItems()->with('product')->get();
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });
        
        $itemCount = $cartItems->count();
        $totalQuantity = $cartItems->sum('quantity');
        
        return [
            'items' => $cartItems,
            'subtotal' => $subtotal,
            'item_count' => $itemCount,
            'total_quantity' => $totalQuantity,
        ];
    }

    private function clearCart()
    {
        return Auth::user()->shoppingCartItems()->delete();
    }

    private function removeItem($cartItemId)
    {
        $cartItem = ShoppingCartItem::where('id', $cartItemId)
                                   ->where('user_id', Auth::id())
                                   ->first();
                                   
        if ($cartItem) {
            return $cartItem->delete();
        }
        
        return false;
    }

    private function validateCartItems()
    {
        $cartItems = Auth::user()->shoppingCartItems()->with('product')->get();
        $invalidItems = [];
        
        foreach ($cartItems as $item) {
            if (!$item->is_valid) {
                $invalidItems[] = $item;
            }
        }
        
        return $invalidItems;
    }

    // ===== MERGED ADD TO CART REQUEST VALIDATION =====

    private function validateAddToCartRequest(Request $request)
    {
        // Check authentication first
        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        $product = Product::find($request->product_id);
        
        if (!$product) {
            $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);
            return;
        }

        $rules = [
            'product_id' => 'required|exists:products,id',
        ];

        // Check if this is a budget-based purchase request
        $isBudgetRequest = $request->filled('customer_budget') && $request->customer_budget > 0;

        if ($isBudgetRequest) {
            $rules['customer_budget'] = 'required|numeric|min:0.01|max:999999.99';
            $rules['customer_notes'] = 'nullable|string|max:500';
        } else {
            // For regular purchases (including budget-based products at original price)
            $maxQuantity = $product->is_budget_based ? 999 : $product->quantity_in_stock;
            $rules['quantity'] = [
                'required',
                'integer',
                'min:1',
                'max:' . $maxQuantity
            ];
        }

        $messages = [
            'customer_budget.required' => 'Budget amount is required for this item.',
            'customer_budget.min' => 'Budget must be at least ₱0.01.',
            'customer_budget.max' => 'Budget cannot exceed ₱999,999.99.',
            'quantity.max' => 'Quantity cannot exceed available stock.',
        ];

        $request->validate($rules, $messages);
    }
}