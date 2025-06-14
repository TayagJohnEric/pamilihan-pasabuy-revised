<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'vendor_id',
        'category_id',
        'product_name',
        'description',
        'price',
        'unit',
        'is_budget_based',
        'indicative_price_per_unit',
        'image_url',
        'is_available',
        'quantity_in_stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'indicative_price_per_unit' => 'decimal:2',
        'is_budget_based' => 'boolean',
        'is_available' => 'boolean',
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function substitutedOrderItems()
    {
        return $this->hasMany(OrderItem::class, 'substituted_with_product_id');
    }

    public function shoppingCartItems()
    {
        return $this->hasMany(ShoppingCartItem::class, 'product_id');
    }
}
