<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    protected $table = 'vendors';

    protected $fillable = [
        'user_id',
        'vendor_name',
        'shop_logo_url',
        'shop_banner_url',
        'stall_number',
        'market_section',
        'business_hours',
        'public_contact_number',
        'public_email',
        'description',
        'verification_status',
        'permit_documents_path',
        'accepts_cod',
        'average_rating',
        'is_active',
    ];

    protected $casts = [
        'accepts_cod' => 'boolean',
        'is_active' => 'boolean',
        'average_rating' => 'decimal:2',
        'verification_status' => 'string',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }
}
