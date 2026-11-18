<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialMediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this?->name,
            'name_hi' => $this->socialMediaPlatform?->name_hi,
            'url' => $this->url,
            'embed_code' => $this->embed_code,
            'icon_class' => $this->icon_class,
            'id' => $this->id,
        ];
    }
}
