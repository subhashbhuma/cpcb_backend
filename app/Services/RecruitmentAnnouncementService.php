<?php

namespace App\Services;

use App\DTO\RecruitmentAnnouncementDto;
use App\Repositories\RecruitmentAnnouncementRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class RecruitmentAnnouncementService
{
    use FileUploadTrait;
    private $recruitmentAnnouncementRepository;

    public function __construct()
    {
        $this->recruitmentAnnouncementRepository = new RecruitmentAnnouncementRepository();
    }

    public function findAll()
    {
        return $this->recruitmentAnnouncementRepository->findAll();
    }

    public function findById($id)
    {
        return $this->recruitmentAnnouncementRepository->findById($id);
    }

    public function create(RecruitmentAnnouncementDto $dto)
    {
        if ($dto->file_name) {
            $uploadedEn = $this->uploadFile($dto->file_name, Config::get('file_paths')['RECRUITMENT_ANNOUNCEMENT_FILE_EN_PATH']);
            $dto->file_name = $uploadedEn['file_name'];
        }

        if ($dto->file_name_hi) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['RECRUITMENT_ANNOUNCEMENT_FILE_HI_PATH']);
            $dto->file_name_hi = $uploadedHi['file_name'];
        }

        $results = [];
        $jobPostIds = is_array($dto->job_post_id) ? $dto->job_post_id : [$dto->job_post_id];

        foreach ($jobPostIds as $jobPostId) {
            $results[] = $this->recruitmentAnnouncementRepository->create([
                'job_post_id' => $jobPostId,
                'type' => $dto->type,
                'title' => $dto->title,
                'title_hi' => $dto->title_hi,
                'start_date' => $dto->start_date,
                'end_date' => $dto->end_date,
                'file_name' => $dto->file_name,
                'file_name_hi' => $dto->file_name_hi,
                'remarks' => $dto->remarks,
                'is_approved' => $dto->is_approved,
                'is_published' => $dto->is_published,
                'created_by' => $dto->created_by,
                'created_at' => $dto->created_at,
                'updated_by' => $dto->updated_by,
                'updated_at' => $dto->updated_at,
            ]);
        }

        return !empty($results) ? $results[0] : false;
    }

    public function update(RecruitmentAnnouncementDto $dto, $id)
    {
        if ($dto->file_name) {
            $uploadedEn = $this->uploadFile($dto->file_name, Config::get('file_paths')['RECRUITMENT_ANNOUNCEMENT_FILE_EN_PATH']);
            $dto->file_name = $uploadedEn['file_name'];
        }

        if ($dto->file_name_hi) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['RECRUITMENT_ANNOUNCEMENT_FILE_HI_PATH']);
            $dto->file_name_hi = $uploadedHi['file_name'];
        }

        $jobPostIds = is_array($dto->job_post_id) ? $dto->job_post_id : [$dto->job_post_id];
        $primaryJobPostId = array_shift($jobPostIds);

        $data = [
            'job_post_id' => $primaryJobPostId,
            'type' => $dto->type,
            'title' => $dto->title,
            'title_hi' => $dto->title_hi,
            'start_date' => $dto->start_date,
            'end_date' => $dto->end_date,
            'remarks' => null,
            'is_approved' => 0,
            'is_published' => 0,
            'publish_remark' => null,
            'updated_by' => $dto->updated_by,
            'updated_at' => $dto->updated_at,
        ];

        if ($dto->file_name) {
            $data['file_name'] = $dto->file_name;
        }

        if ($dto->file_name_hi) {
            $data['file_name_hi'] = $dto->file_name_hi;
        }

        $mainUpdate = $this->recruitmentAnnouncementRepository->update($data, $id);

        // If extra posts were selected in Edit, create new records for them
        foreach ($jobPostIds as $jobPostId) {
            $extraData = $data;
            $extraData['job_post_id'] = $jobPostId;
            $extraData['created_by'] = $dto->created_by;
            $extraData['created_at'] = $dto->created_at;
            
            // Use existing files for clones if no new file uploaded
            if (!isset($extraData['file_name'])) {
                $announcement = $this->findById($id);
                $extraData['file_name'] = $announcement->file_name;
                $extraData['file_name_hi'] = $announcement->file_name_hi;
            }

            $this->recruitmentAnnouncementRepository->create($extraData);
        }

        return $mainUpdate;
    }

    public function delete($id)
    {
        return $this->recruitmentAnnouncementRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->recruitmentAnnouncementRepository->update($updateData, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $updateData = [
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->recruitmentAnnouncementRepository->update($updateData, $id);
    }
}
