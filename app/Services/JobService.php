<?php

namespace App\Services;

use App\DTO\JobDto;
use App\Repositories\JobRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class JobService
{
    use FileUploadTrait;
    private $jobRepository;

    public function __construct()
    {
        $this->jobRepository = new JobRepository();
    }


    public function findForPublic($limit = null)
    {
        return $this->jobRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->jobRepository->findAll();
    }

    public function findById($id)
    {
        return $this->jobRepository->findById($id);
    }

    public function create(JobDto $jobDto)
    {
        if ($jobDto->advertisement_file_name) {
            $uploaded = $this->uploadFile($jobDto->advertisement_file_name, Config::get('file_paths')['JOB_ADVERTIESMENT_FILE_EN_PATH']);
            $jobDto->advertisement_file_name = $uploaded['file_name'];
        }

        if ($jobDto->advertisement_file_hi_name) {
            $uploadedHi = $this->uploadFile($jobDto->advertisement_file_hi_name, Config::get('file_paths')['JOB_ADVERTIESMENT_FILE_HI_PATH']);
            $jobDto->advertisement_file_hi_name = $uploadedHi['file_name'];
        }

        if ($jobDto->direct_application_form_name) {
            $uploaded = $this->uploadFile($jobDto->direct_application_form_name, Config::get('file_paths')['DIRECT_APPLICATION_FILE_EN_PATH']);
            $jobDto->direct_application_form_name = $uploaded['file_name'];
        }
        if ($jobDto->direct_application_form_hi_name) {
            $uploadedHi = $this->uploadFile($jobDto->direct_application_form_hi_name, Config::get('file_paths')['DIRECT_APPLICATION_FILE_HI_PATH']);
            $jobDto->direct_application_form_hi_name = $uploadedHi['file_name'];
        }
        if ($jobDto->deputation_application_form_name) {
            $uploaded = $this->uploadFile($jobDto->deputation_application_form_name, Config::get('file_paths')['DEPUTATION_APPLICATION_FILE_EN_PATH']);
            $jobDto->deputation_application_form_name = $uploaded['file_name'];
        }
        if ($jobDto->deputation_application_form_hi_name) {
            $uploadedHi = $this->uploadFile($jobDto->deputation_application_form_hi_name, Config::get('file_paths')['DEPUTATION_APPLICATION_FILE_HI_PATH']);
            $jobDto->deputation_application_form_hi_name = $uploadedHi['file_name'];
        }

        $job = $this->jobRepository->create([
            'title' => $jobDto->title,
            'title_hi' => $jobDto->title_hi,
            'start_date' => $jobDto->start_date,
            'end_date' => $jobDto->end_date,
            'advertisement_file_name' => $jobDto->advertisement_file_name,
            'advertisement_file_hi_name' => $jobDto->advertisement_file_hi_name,
            'direct_application_form_name' => $jobDto->direct_application_form_name,
            'direct_application_form_hi_name' => $jobDto->direct_application_form_hi_name,
            'deputation_application_form_name' => $jobDto->deputation_application_form_name,
            'deputation_application_form_hi_name' => $jobDto->deputation_application_form_hi_name,
            'job_type' => $jobDto->job_type,
            'direct_application' => $jobDto->direct_application,
            'deputation_application' => $jobDto->deputation_application,
            'direct_application_url' => $jobDto->direct_application_url,
            'deputation_application_url' => $jobDto->deputation_application_url,
            'online_form_url' => $jobDto->online_form_url,
            'walk_in_interview_date' => $jobDto->walk_in_interview_date,
            'is_approved' => $jobDto->is_approved,
            'is_published' => $jobDto->is_published,
            'remarks' => $jobDto->remarks,
            'created_by' => $jobDto->created_by,
            'created_at' => $jobDto->created_at,
            'updated_by' => $jobDto->updated_by,
            'updated_at' => $jobDto->updated_at,
        ]);

        if ($job && !empty($jobDto->posts)) {
            foreach ($jobDto->posts as $post) {
                if (!empty($post['title'])) {
                    \App\Models\JobPost::create([
                        'job_id' => $job->id,
                        'title' => $post['title'],
                        'title_hi' => $post['title_hi'] ?? null,
                        'created_by' => $jobDto->created_by,
                        'updated_by' => $jobDto->updated_by,
                    ]);
                }
            }
        }

        return $job;
    }

    public function update(JobDto $jobDto, $id)
    {
        if ($jobDto->advertisement_file_name) {
            $uploaded = $this->uploadFile($jobDto->advertisement_file_name, Config::get('file_paths')['JOB_ADVERTIESMENT_FILE_EN_PATH']);
            $jobDto->advertisement_file_name = $uploaded['file_name'];
        }

        if ($jobDto->advertisement_file_hi_name) {
            $uploadedHi = $this->uploadFile($jobDto->advertisement_file_hi_name, Config::get('file_paths')['JOB_ADVERTIESMENT_FILE_HI_PATH']);
            $jobDto->advertisement_file_hi_name = $uploadedHi['file_name'];
        }

        if ($jobDto->direct_application_form_name) {
            $uploaded = $this->uploadFile($jobDto->direct_application_form_name, Config::get('file_paths')['DIRECT_APPLICATION_FILE_EN_PATH']);
            $jobDto->direct_application_form_name = $uploaded['file_name'];
        }
        if ($jobDto->direct_application_form_hi_name) {
            $uploadedHi = $this->uploadFile($jobDto->direct_application_form_hi_name, Config::get('file_paths')['DIRECT_APPLICATION_FILE_HI_PATH']);
            $jobDto->direct_application_form_hi_name = $uploadedHi['file_name'];
        }
        if ($jobDto->deputation_application_form_name) {
            $uploaded = $this->uploadFile($jobDto->deputation_application_form_name, Config::get('file_paths')['DEPUTATION_APPLICATION_FILE_EN_PATH']);
            $jobDto->deputation_application_form_name = $uploaded['file_name'];
        }
        if ($jobDto->deputation_application_form_hi_name) {
            $uploadedHi = $this->uploadFile($jobDto->deputation_application_form_hi_name, Config::get('file_paths')['DEPUTATION_APPLICATION_FILE_HI_PATH']);
            $jobDto->deputation_application_form_hi_name = $uploadedHi['file_name'];
        }

        $data = [
            'title' => $jobDto->title,
            'title_hi' => $jobDto->title_hi,
            'start_date' => $jobDto->start_date,
            'end_date' => $jobDto->end_date,
            'job_type' => $jobDto->job_type,
            'direct_application' => $jobDto->direct_application,
            'deputation_application' => $jobDto->deputation_application,
            'direct_application_url' => $jobDto->direct_application_url,
            'deputation_application_url' => $jobDto->deputation_application_url,
            'online_form_url' => $jobDto->online_form_url,
            'walk_in_interview_date' => $jobDto->walk_in_interview_date,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $jobDto->updated_by,
            'updated_at' => $jobDto->updated_at,
        ];

        if ($jobDto->advertisement_file_name) {
            $data['advertisement_file_name'] = $jobDto->advertisement_file_name;
        }

        if ($jobDto->advertisement_file_hi_name) {
            $data['advertisement_file_hi_name'] = $jobDto->advertisement_file_hi_name;
        }

        if ($jobDto->direct_application_form_name) {
            $data['direct_application_form_name'] = $jobDto->direct_application_form_name;
        }

        if ($jobDto->direct_application_form_hi_name) {
            $data['direct_application_form_hi_name'] = $jobDto->direct_application_form_hi_name;
        }

        if ($jobDto->deputation_application_form_name) {
            $data['deputation_application_form_name'] = $jobDto->deputation_application_form_name;
        }

        if ($jobDto->deputation_application_form_hi_name) {
            $data['deputation_application_form_hi_name'] = $jobDto->deputation_application_form_hi_name;
        }

        $result = $this->jobRepository->update($data, $id);

        if ($result !== false) {
            // Handle Job Posts synchronization
            $currentPostIds = [];
            if (!empty($jobDto->posts)) {
                foreach ($jobDto->posts as $post) {
                    if (!empty($post['title'])) {
                        // If ID exists, update by ID, otherwise updateOrCreate by title
                        $searchCriteria = !empty($post['id']) ? ['id' => $post['id']] : ['job_id' => $id, 'title' => $post['title']];
                        
                        $jobPost = \App\Models\JobPost::updateOrCreate(
                            $searchCriteria,
                            [
                                'job_id' => $id,
                                'title' => $post['title'],
                                'title_hi' => $post['title_hi'] ?? null,
                                'updated_by' => $jobDto->updated_by,
                                'created_by' => $jobDto->created_by // Ensure created_by is set if created
                            ]
                        );
                        $currentPostIds[] = $jobPost->id;
                    }
                }
            }
            // Delete posts that were removed from the UI
            \App\Models\JobPost::where('job_id', $id)->whereNotIn('id', $currentPostIds)->delete();
        }

        return $result;
    }


    public function delete($id)
    {
        return $this->jobRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->jobRepository->update($updateData, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'is_published' => $isPublished,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->jobRepository->update($updateData, $id);
    }
}
