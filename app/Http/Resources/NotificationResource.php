<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'sender_role' => $this->sender_role,
            'message'     => $this->message,
            'status'      => $this->status,
            'is_read'     => $this->is_read,
            'created_at'  => $this->created_at?->toISOString(),
        ];
    }
}
