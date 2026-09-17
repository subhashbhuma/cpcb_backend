<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicHeadOfficeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'title_hi'       => $this->title_hi,
            'order' => $this->order,
            'email' => $this->email,
            'image_url' => $this->final_image_url,
            'ext_number' => $this->ext_number,
            'division'=>$this?->division?->title,
            'division_hi'=>$this?->division?->title_hi,
            'personnels' => $this->relationLoaded('active_personnels') ? $this->active_personnels->map(fn($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'title_hi' => $p->title_hi,
                'designation' => $p->designation,
                'designation_hi' => $p->designation_hi,
                'order' => $p->order,
            ]) : ($this->relationLoaded('personnels') ? $this->personnels->where('record_status', 1)->values()->map(fn($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'title_hi' => $p->title_hi,
                'designation' => $p->designation,
                'designation_hi' => $p->designation_hi,
                'order' => $p->order,
            ]) : []),
            'profile_activities' => $this->relationLoaded('active_profile_activities') ? $this->active_profile_activities->map(fn($a) => [
                'id' => $a->id,
                'title' => $a->title,
                'title_hi' => $a->title_hi,
                'order' => $a->order,
            ]) : ($this->relationLoaded('profileActivities') ? $this->profileActivities->where('record_status', 1)->values()->map(fn($a) => [
                'id' => $a->id,
                'title' => $a->title,
                'title_hi' => $a->title_hi,
                'order' => $a->order,
            ]) : []),
        ];
    }
}

