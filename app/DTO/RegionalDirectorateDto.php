<?php

namespace App\DTO;

use Illuminate\Http\Request;

class RegionalDirectorateDto
{
    public function __construct(
        public ?string $regional_directorate,
        public ?string $regional_directorate_hi,
        public ?string $title,
        public ?string $title_hi,
        public ?string $designation,
        public ?string $designation_hi,
        public ?string $email,
        public ?string $description,
        public ?string $description_hi,
        public ?int $is_approved,
        public ?int $is_published,
        public ?string $remarks,
        public ?string $publish_remark,
        public ?int $created_by,
        public ?int $updated_by = null,
        public ?int $order = 0,
        public $image = null
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('regional_directorate'),
            $request->input('regional_directorate_hi'),
            $request->input('title'),
            $request->input('title_hi'),
            $request->input('designation'),
            $request->input('designation_hi'),
            $request->input('email'),
            $request->input('description'),
            $request->input('description_hi'),
            $request->input('is_approved', 0),
            $request->input('is_published', 0),
            $request->input('remarks'),
            $request->input('publish_remark'),
            auth()->id(),
            $request->isMethod('put') || $request->isMethod('patch') ? auth()->id() : null,
            $request->input('order', 0),
            $request->file('image')
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'regional_directorate' => $this->regional_directorate,
            'regional_directorate_hi' => $this->regional_directorate_hi,
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'designation' => $this->designation,
            'designation_hi' => $this->designation_hi,
            'email' => $this->email,
            'image' => is_string($this->image) ? $this->image : null,
            'description' => $this->description,
            'description_hi' => $this->description_hi,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'order' => $this->order,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ], fn($value) => !is_null($value));
    }
}
