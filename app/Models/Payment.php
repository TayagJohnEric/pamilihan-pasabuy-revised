<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'order_id',
        'amount_paid',
        'payment_method_used',
        'status',
        'gateway_transaction_id',
        'payment_gateway_response',
        'payment_processed_at',
        'refunded_at',
        'refund_details',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_method_used' => 'string',
        'status' => 'string',
        'payment_gateway_response' => 'array',
        'payment_processed_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
