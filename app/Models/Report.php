<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'location',
        'media',
        'status',
        'admin_response',
        'responded_at',
        'is_verified',
        'verified_at',
        'verified_by',
        'rejection_reason'
    ];

    protected $casts = [
        'media' => 'array',
        'responded_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
