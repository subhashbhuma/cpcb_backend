<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicComplaintResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // 'complaint_subject' => $this->complaintSubject->title ?? null,
            // 'full_name' => $this->full_name,
            // 'email' => $this->email,
            // 'phone' => $this->phone,
            // 'message' => $this->message,
            // 'status' => $this->status,
            // 'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
