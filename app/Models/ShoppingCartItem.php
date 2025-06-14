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
}
