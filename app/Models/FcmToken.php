<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FcmToken extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'fcm_tokens';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'fcm_token',
        'device_type',
    ];

    /**
     * Get the user that owns this FCM token.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
