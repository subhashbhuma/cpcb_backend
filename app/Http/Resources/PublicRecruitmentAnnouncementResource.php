<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

class PublicRecruitmentAnnouncementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'start_date' => $this->start_date ? \Carbon\Carbon::parse($this->start_date)->format('d-m-Y') : null,
            'end_date' => $this->end_date ? \Carbon\Carbon::parse($this->end_date)->format('d-m-Y') : null,
            'file_url' => $this->generateFileUrl('RECRUITMENT_ANNOUNCEMENT_FILE_EN_PATH', $this->file_name),
            'file_url_hi' => $this->generateFileUrl('RECRUITMENT_ANNOUNCEMENT_FILE_HI_PATH', $this->file_name_hi),
            'remarks' => $this->remarks,
        ];
    }

    private function generateFileUrl($configKey, $fileName)
    {
        if (empty($fileName)) return null;
        return base64_encode(Config::get('file_paths')[$configKey] . '/' . $fileName);
    }
}
