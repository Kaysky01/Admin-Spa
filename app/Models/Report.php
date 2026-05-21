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

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // ============================================
    // ACCESSORS — Eliminates duplicate media URL logic
    // ============================================

    /**
     * Get media paths transformed to full API URLs.
     * This replaces the 4x duplicated closure in the controller.
     */
    public function getMediaUrlsAttribute(): array
    {
        return collect($this->media ?? [])->map(function ($file) {
            $filename = basename($file);
            return url('api/v1/media/reports/' . $filename);
        })->toArray();
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCreatedToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
