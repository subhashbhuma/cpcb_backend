<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicDirectoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cpcb_no' => $this->cleanText($this->cpcb_no),
            'name' => $this->cleanText($this->name),
            'name_hi' => $this->cleanText($this->name_hi),
            'designation' => $this->cleanText($this->designation),
            'designation_hi' => $this->cleanText($this->designation_hi),
            'division' => $this->division ? $this->division->title : null,
            'division_hi' => $this->division ? $this->division->title_hi : null,
            'office_ph_no' => $this->cleanText($this->office_ph_no),
            'mobile_no' => $this->cleanText($this->mobile_no),
            'email' => $this->cleanText($this->email),
            'ext_number' => $this->cleanText($this->ext_number),
            'assigned_work' => $this->cleanText($this->assigned_work),
            'assigned_work_hi' => $this->cleanText($this->assigned_work_hi),
            'image_url' => $this->image_url,
            'order_no' => $this->order_no,
            'show_order' => $this->show_order,
            'updated_at' => $this->updated_at ? $this->updated_at->format('d-m-Y H:i:s') : null,
        ];
    }

    private function cleanText($value): string
    {
        $value = trim((string) $value);
        return $value === '' || strtoupper($value) === 'NULL' ? '' : $value;
    }
}
