<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicFaqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'question'                => $this->question,
            'question_hi'             => $this->question_hi,
            'answer'                => $this->answer,
            'answer_hi'             => $this->answer_hi,
        ];
    }
}
