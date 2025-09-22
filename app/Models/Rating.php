<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';

    protected $fillable = [
        'order_id',
        'user_id',
        'rateable_id',
        'rateable_type',
        'rating_value',
        'comment',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rateable()
    {
        return $this->morphTo();
    }
}
