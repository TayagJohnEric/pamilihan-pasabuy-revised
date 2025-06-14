<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'category_name',
        'description',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
