<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

class PublicJobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'title'                => $this->title,
            'title_hi'             => $this->title_hi,
            'start_date' => $this->start_date ? \Carbon\Carbon::parse($this->start_date)->format('d-m-Y') : 'NA',
            'end_date'   => $this->end_date ? \Carbon\Carbon::parse($this->end_date)->format('d-m-Y') : 'NA',
            'adv_url'      => $this->generateFileUrl('JOB_ADVERTIESMENT_FILE_EN_PATH', $this->advertisement_file_name),
            'adv_url_hi'   => $this->generateFileUrl('JOB_ADVERTIESMENT_FILE_HI_PATH', $this->advertisement_file_hi_name),
            'direct_application'   => $this->direct_application,
            'direct_url'           => $this->direct_application == 'online' ? $this->direct_application_url : $this->generateFileUrl('DIRECT_APPLICATION_FILE_EN_PATH', $this->direct_application_form_name),
            'direct_url_hi'        => $this->direct_application == 'online' ? null : $this->generateFileUrl('DIRECT_APPLICATION_FILE_HI_PATH', $this->direct_application_form_hi_name),
            'deputation_application' => $this->deputation_application,
            'dep_url'              => $this->deputation_application == 'online' ? $this->deputation_application_url : $this->generateFileUrl('DEPUTATION_APPLICATION_FILE_EN_PATH', $this->deputation_application_form_name),
            'dep_url_hi'           => $this->deputation_application == 'online' ? null : $this->generateFileUrl('DEPUTATION_APPLICATION_FILE_HI_PATH', $this->deputation_application_form_hi_name),
            'online_form_url'      => $this->online_form_url,
            'walk_in_interview_date' => $this->walk_in_interview_date ? \Carbon\Carbon::parse($this->walk_in_interview_date)->format('d-m-Y') : null,
            'job_type'             => $this->job_type,
            'posts'        => PublicJobPostResource::collection($this->whenLoaded('jobPosts')),
        ];
    }

    /**
     * Helper inside resource to handle Base64 URL generation
     */
    private function generateFileUrl($configKey, $fileName)
    {
        if (empty($fileName)) return null;
        return base64_encode(Config::get('file_paths')[$configKey] . '/' . $fileName);
    }
}
