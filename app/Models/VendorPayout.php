<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPayout extends Model
{
    protected $table = 'vendor_payouts';

    protected $fillable = [
        'vendor_user_id',
        'payout_period_start_date',
        'payout_period_end_date',
        'total_sales_amount',
        'platform_commission_amount',
        'adjustments_amount',
        'adjustments_notes',
        'total_payout_amount',
        'status',
        'paid_at',
        'transaction_reference',
    ];

    protected $casts = [
        'payout_period_start_date' => 'date',
        'payout_period_end_date' => 'date',
        'total_sales_amount' => 'decimal:2',
        'platform_commission_amount' => 'decimal:2',
        'adjustments_amount' => 'decimal:2',
        'total_payout_amount' => 'decimal:2',
        'status' => 'string',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_user_id');
    }
}
