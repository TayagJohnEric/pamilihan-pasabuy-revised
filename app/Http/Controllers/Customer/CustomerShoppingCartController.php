<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ShoppingCartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * Update cart item with enhanced error handling
     */
    public function update(Request $request, $cartItemId)
    {
        try {
            // Use explicit model finding instead of route model binding for better control
            $cartItem = ShoppingCartItem::where('id', $cartItemId)
                ->where('user_id', Auth::id())
                ->with('product')
                ->first();

            if (!$cartItem) {
                $message = 'Cart item not found or unauthorized access.';
                return $request->ajax()
                    ? response()->json(['success' => false, 'message' => $message], 404)
                    : redirect()->route('cart.index')->with('error', $message);
            }

            $product = $cartItem->product;

            if (!$product->is_available) {
                $message = 'Product is no longer available.';
                return $request->ajax()
                    ? response()->json(['success' => false, 'message' => $message], 400)
                    : redirect()->route('cart.index')->with('error', $message);
            }

            return DB::transaction(function () use ($request, $cartItem, $product) {
                // Check if this cart item is currently being used as budget-based
                $isCurrentlyBudgetBased = !is_null($cartItem->customer_budget);
                
                // Check if this is a conversion to budget-based (from regular price)
                $isConvertingToBudget = !$isCurrentlyBudgetBased && $request->filled('customer_budget') && $request->customer_budget > 0;
                
                if ($isConvertingToBudget) {
                    return $this->handleBudgetConversion($request, $cartItem, $product);
                } elseif ($isCurrentlyBudgetBased) {
                    return $this->handleBudgetUpdate($request, $cartItem);
                } else {
                    return $this->handleQuantityUpdate($request, $cartItem, $product);
                }
            });

        } catch (ModelNotFoundException $e) {
            $message = 'Cart item not found.';
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $message], 404)
                : redirect()->route('cart.index')->with('error', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Validation failed.', 
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->route('cart.index')->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Cart update error: ' . $e->getMessage(), [
                'cart_item_id' => $cartItemId,
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);
            
            $message = 'An error occurred while updating your cart. Please try again.';
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $message], 500)
                : redirect()->route('cart.index')->with('error', $message);
        }
    }

    /**
     * Handle budget conversion with proper cleanup
     */
    private function handleBudgetConversion(Request $request, ShoppingCartItem $cartItem, Product $product)
    {
        $validated = $request->validate([
            'customer_budget' => 'required|numeric|min:0.01|max:999999.99',
            'customer_notes' => 'nullable|string|max:500',
        ]);

        // Check if there's already a budget-based cart item for this product
        $existingBudgetItem = ShoppingCartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->whereNotNull('customer_budget')
            ->where('id', '!=', $cartItem->id)
            ->first();

        $deletedItemId = null;
        if ($existingBudgetItem) {
            $deletedItemId = $existingBudgetItem->id;
            $existingBudgetItem->delete();
        }

        // Update the cart item to be budget-based
        $cartItem->update([
            'customer_budget' => $validated['customer_budget'],
            'customer_notes' => $validated['customer_notes'],
            'customer_notes' => $validated['customer_notes'] ?? null,
            'quantity' => 1, // Budget-based items always have quantity 1
        ]);

        $message = 'Item converted to budget-based pricing successfully!';
        $cartSummary = $this->getCartSummary();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_summary' => $cartSummary,
                'converted_item_id' => $cartItem->id,
                'deleted_item_id' => $deletedItemId
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', $message);
    }

    /**
     * Handle budget-based item updates
     */
    private function handleBudgetUpdate(Request $request, ShoppingCartItem $cartItem)
    {
        $validated = $request->validate([
            'customer_budget' => 'required|numeric|min:0.01|max:999999.99',
            'customer_notes' => 'nullable|string|max:500',
        ]);

        $cartItem->update($validated);
        
        $cartSummary = $this->getCartSummary();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Budget updated successfully',
                'cart_summary' => $cartSummary
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Budget-based item updated successfully!');
    }

    /**
     * Handle quantity updates for regular items
     */
    private function handleQuantityUpdate(Request $request, ShoppingCartItem $cartItem, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->quantity_in_stock,
        ]);

        $cartItem->update($validated);
        
        $cartSummary = $this->getCartSummary();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully.',
                'cart_summary' => $cartSummary
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove item from cart
     */
    public function destroy($cartItemId)
    {
        try {
            $cartItem = ShoppingCartItem::where('id', $cartItemId)
                ->where('user_id', Auth::id())
                ->with('product')
                ->first();

            if (!$cartItem) {
                return redirect()->route('cart.index')
                    ->with('error', 'Cart item not found or unauthorized access.');
            }

            $productName = $cartItem->product->product_name;
            $cartItem->delete();

            return redirect()->route('cart.index')
                           ->with('success', "'{$productName}' has been removed from your cart.");

        } catch (\Exception $e) {
            \Log::error('Cart item deletion error: ' . $e->getMessage(), [
                'cart_item_id' => $cartItemId,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('cart.index')
                           ->with('error', 'An error occurred while removing the item. Please try again.');
        }
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