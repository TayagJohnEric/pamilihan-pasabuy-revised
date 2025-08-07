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
        if ($this->product->is_budget_based) {
            return $this->customer_budget ?? 0;
        }
        return $this->product->price * $this->quantity;
    }

    public function getIsValidAttribute()
    {
        // Check if product is still available and in stock
        return $this->product->is_available && 
               $this->product->quantity_in_stock >= $this->quantity;
    }
}
