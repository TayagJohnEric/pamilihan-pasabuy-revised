<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedAddress extends Model
{
    protected $table = 'saved_addresses';

    protected $fillable = [
        'user_id',
        'district_id',
        'address_line1',
        'address_label',
        'delivery_notes',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'delivery_address_id');
    }
}
