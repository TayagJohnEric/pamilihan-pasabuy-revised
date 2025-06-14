<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name_snapshot',
        'quantity_requested',
        'unit_price_snapshot',
        'customer_budget_requested',
        'vendor_assigned_quantity_description',
        'actual_item_price',
        'is_substituted',
        'substituted_with_product_id',
        'customerNotes_snapshot',
        'vendor_fulfillment_notes',
    ];

    protected $casts = [
        'unit_price_snapshot' => 'decimal:2',
        'customer_budget_requested' => 'decimal:2',
        'actual_item_price' => 'decimal:2',
        'is_substituted' => 'boolean',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function substitutedProduct()
    {
        return $this->belongsTo(Product::class, 'substituted_with_product_id');
    }
}
