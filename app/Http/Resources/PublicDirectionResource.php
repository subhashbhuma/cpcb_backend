<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicDirectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'sl_no' => $this->sl_no ?? 0,

            'id' => $this->id,

            'title' => $this->title,

            'title_hi' => $this->title_hi,

            'publish_date' => $this->publish_date,

            'file_path_en' => $this->file_path_en,

            'file_path_hi' => $this->file_path_hi,

            /*
            |--------------------------------------------------------------------------
            | SINGLE RELATIONS
            |--------------------------------------------------------------------------
            */

            'act_type' =>
                $this->directionActType?->title,

            'act_type_hi' =>
                $this->directionActType?->title_hi,

            'type' =>
                $this->directionType?->title,

            'type_hi' =>
                $this->directionType?->title_hi,

            'subject' =>
                $this->directionSubject?->title,

            'subject_hi' =>
                $this->directionSubject?->title_hi,

            /*
            |--------------------------------------------------------------------------
            | CSV ACCESSOR COLLECTIONS
            |--------------------------------------------------------------------------
            */

            'categories' =>
                $this->categories
                    ->pluck('title')
                    ->implode(', '),

            'categories_hi' =>
                $this->categories
                    ->pluck('title_hi')
                    ->implode(', '),

            'states' =>
                $this->states
                    ->pluck('title')
                    ->implode(', '),

            'states_hi' =>
                $this->states
                    ->pluck('title_hi')
                    ->implode(', '),

            'issued_to' =>
                $this->issuedTos
                    ->pluck('title')
                    ->implode(', '),

            'issued_to_hi' =>
                $this->issuedTos
                    ->pluck('title_hi')
                    ->implode(', '),

            'updated_at' => $this->updated_at,
        ];
    }
}