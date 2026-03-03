<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $fillable = [
        'user_id',
        'sender_role',
        'message',
        'status',
        'is_read',
    ];

    
}

