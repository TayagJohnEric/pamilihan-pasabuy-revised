<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiderPayout extends Model
{
    protected $table = 'rider_payouts';

    protected $fillable = [
        'rider_user_id',
        'payout_period_start_date',
        'payout_period_end_date',
        'total_delivery_fees_earned',
        'total_incentives_earned',
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
        'total_delivery_fees_earned' => 'decimal:2',
        'total_incentives_earned' => 'decimal:2',
        'adjustments_amount' => 'decimal:2',
        'total_payout_amount' => 'decimal:2',
        'status' => 'string',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_user_id');
    }
}
