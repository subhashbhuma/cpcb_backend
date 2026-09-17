<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicRegionalDirectorateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'regional_directorate'    => $this->regional_directorate,
            'regional_directorate_hi' => $this->regional_directorate_hi,
            'title'                  => $this->title,
            'title_hi'               => $this->title_hi,
            'designation'            => $this->designation,
            'designation_hi'         => $this->designation_hi,
            'email'                  => $this->email,
            'order'                  => $this->order,
            'image_url'              => $this->final_image_url,
            'description'            => $this->description,
            'description_hi'         => $this->description_hi,
            'states'                 => $this->relationLoaded('active_states') ? $this->active_states->map(fn($s) => [
                'id'       => $s->id,
                'title'    => $s->title,
                'title_hi' => $s->title_hi,
                'order'    => $s->order,
            ]) : ($this->relationLoaded('states') ? $this->states->where('record_status', 1)->values()->map(fn($s) => [
                'id'       => $s->id,
                'title'    => $s->title,
                'title_hi' => $s->title_hi,
                'order'    => $s->order,
            ]) : []),
            'personnels'             => $this->relationLoaded('active_personnels') ? $this->active_personnels->map(fn($p) => [
                'id'             => $p->id,
                'title'          => $p->title,
                'title_hi'       => $p->title_hi,
                'designation'    => $p->designation,
                'designation_hi' => $p->designation_hi,
                'order'          => $p->order,
            ]) : ($this->relationLoaded('personnels') ? $this->personnels->where('record_status', 1)->values()->map(fn($p) => [
                'id'             => $p->id,
                'title'          => $p->title,
                'title_hi'       => $p->title_hi,
                'designation'    => $p->designation,
                'designation_hi' => $p->designation_hi,
                'order'          => $p->order,
            ]) : []),
            'profile_activities'     => $this->relationLoaded('active_profile_activities') ? $this->active_profile_activities->map(fn($a) => [
                'id'       => $a->id,
                'title'    => $a->title,
                'title_hi' => $a->title_hi,
                'order'    => $a->order,
            ]) : ($this->relationLoaded('profileActivities') ? $this->profileActivities->where('record_status', 1)->values()->map(fn($a) => [
                'id'       => $a->id,
                'title'    => $a->title,
                'title_hi' => $a->title_hi,
                'order'    => $a->order,
            ]) : []),
        ];
    }
}
