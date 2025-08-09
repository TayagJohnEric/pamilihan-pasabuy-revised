<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCartItem extends Model
{
    protected $table = 'shopping_cart_items';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'customer_budget',
        'customer_notes',
    ];

    protected $casts = [
        'customer_budget' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Calculated attributes
    public function getSubtotalAttribute()
    {
        // If this is a budget-based purchase (customer_budget is set), use that
        if (!is_null($this->customer_budget)) {
            return $this->customer_budget;
        }
        
        // For regular price purchases (including budget-based products at original price)
        return $this->product->price * $this->quantity;
    }

    public function getIsValidAttribute()
    {
        // Check if product is still available
        if (!$this->product->is_available) {
            return false;
        }
        
        // For budget-based purchases, no quantity validation needed
        if (!is_null($this->customer_budget)) {
            return true;
        }
        
        // For regular price purchases, check stock quantity
        return $this->product->quantity_in_stock >= $this->quantity;
    }
}
