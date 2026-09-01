<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

class PublicJobResultResource extends JsonResource
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
            'job_id' => $this->job_id,
            'post_name' => $this->post_name,
            'post_name_hi' => $this->post_name_hi,
            'test_date' => $this->test_date,
            'interview_date' => $this->interview_date,
            'eligible_written_file_url' =>$this->generateFileUrl('JOB_RESULT_ELIGIBLE_WRITTEN_FILE_EN_PATH',  $this->eligible_written_file),
            'eligible_written_file_url_hi' =>$this->generateFileUrl('JOB_RESULT_ELIGIBLE_WRITTEN_FILE_HI_PATH',  $this->eligible_written_file_hi),
            'eligible_interview_file_url' =>$this->generateFileUrl('JOB_RESULT_ELIGIBLE_INTERVIEW_FILE_EN_PATH',  $this->eligible_interview_file),
            'eligible_interview_file_url_hi' =>$this->generateFileUrl('JOB_RESULT_ELIGIBLE_INTERVIEW_FILE_HI_PATH',  $this->eligible_interview_file_hi),
            'final_written_result_file_url' =>$this->generateFileUrl('JOB_RESULT_FINAL_WRITTEN_FILE_EN_PATH',  $this->final_written_result_file),
            'final_written_result_file_url_hi' =>$this->generateFileUrl('JOB_RESULT_FINAL_WRITTEN_FILE_HI_PATH',  $this->final_written_result_file_hi),
            'final_interview_result_file_url' =>$this->generateFileUrl('JOB_RESULT_FINAL_INTERVIEW_FILE_EN_PATH',  $this->final_interview_result_file),
            'final_interview_result_file_url_hi' =>$this->generateFileUrl('JOB_RESULT_FINAL_INTERVIEW_FILE_HI_PATH', $this->final_interview_result_file_hi),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

     private function generateFileUrl($configKey, $fileName)
    {
        if (empty($fileName)) return null;
        return base64_encode(Config::get('file_paths')[$configKey] . '/' . $fileName);
    }
}
