<?php
namespace App\Services;

use App\Models\Product;
use App\Models\ShoppingCartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function addToCart($productId, $quantity = 1, $customerBudget = null, $customerNotes = null)
    {
        $product = Product::findOrFail($productId);
        
        // Validate product availability
        if (!$product->is_available || (!$product->is_budget_based && $product->quantity_in_stock <= 0)) {
            throw new \Exception('Product is not available.');
        }

        return DB::transaction(function () use ($product, $quantity, $customerBudget, $customerNotes) {
            $cartItem = ShoppingCartItem::where('user_id', Auth::id())
                                      ->where('product_id', $product->id)
                                      ->first();

            if ($cartItem) {
                return $this->updateExistingCartItem($cartItem, $quantity, $customerBudget, $customerNotes);
            } else {
                return $this->createNewCartItem($product, $quantity, $customerBudget, $customerNotes);
            }
        });
    }

    private function updateExistingCartItem($cartItem, $quantity, $customerBudget, $customerNotes)
    {
        $product = $cartItem->product;

        if ($product->is_budget_based) {
            $cartItem->update([
                'customer_budget' => $customerBudget,
                'customer_notes' => $customerNotes,
            ]);
        } else {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->quantity_in_stock) {
                $newQuantity = $product->quantity_in_stock;
            }
            $cartItem->update(['quantity' => $newQuantity]);
        }

        return $cartItem;
    }

    private function createNewCartItem($product, $quantity, $customerBudget, $customerNotes)
    {
        return ShoppingCartItem::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => $product->is_budget_based ? 1 : $quantity,
            'customer_budget' => $product->is_budget_based ? $customerBudget : null,
            'customer_notes' => $product->is_budget_based ? $customerNotes : null,
        ]);
    }

    public function getCartSummary()
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

    public function clearCart()
    {
        return Auth::user()->shoppingCartItems()->delete();
    }

    public function removeItem($cartItemId)
    {
        $cartItem = ShoppingCartItem::where('id', $cartItemId)
                                   ->where('user_id', Auth::id())
                                   ->first();
                                   
        if ($cartItem) {
            return $cartItem->delete();
        }
        
        return false;
    }

    public function validateCartItems()
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
}