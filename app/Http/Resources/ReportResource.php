<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'description'      => $this->description,
            'location'         => $this->location,
            'status'           => $this->status,
            'is_verified'      => $this->is_verified,
            'media'            => $this->media_urls,
            'admin_response'   => $this->admin_response,
            'responded_at'     => $this->responded_at?->toISOString(),
            'rejection_reason' => $this->rejection_reason,
            'created_at'       => $this->created_at?->toISOString(),
            'category'         => $this->whenLoaded('category', fn () => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ]),
            'user' => $this->whenLoaded('user', fn () => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ]),
        ];
    }
}
