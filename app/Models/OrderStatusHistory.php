<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $table = 'order_status_history';

    // Disable updated_at since we only have created_at
    public $timestamps = false;
    
    protected $fillable = [
        'order_id',
        'status',
        'notes',
        'updated_by_user_id',
        'created_at',
    ];

    protected $casts = [
        'status' => 'string',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
}
