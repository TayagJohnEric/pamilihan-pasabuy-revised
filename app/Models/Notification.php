<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'related_entity_type',
        'related_entity_id',
        'read_at',
    ];

    protected $casts = [
        'message' => 'array',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function relatedEntity()
    {
        return $this->morphTo();
    }
}
