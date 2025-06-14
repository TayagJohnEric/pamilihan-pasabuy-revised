<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';

    protected $fillable = [
        'name',
        'distance_km',
        'delivery_fee',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'distance_km' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
    ];

    // Relationships
    public function savedAddresses()
    {
        return $this->hasMany(SavedAddress::class, 'district_id');
    }
}
