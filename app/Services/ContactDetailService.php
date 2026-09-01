<?php

namespace App\Services;

use App\DTO\ContactDetailDto;
use App\Repositories\ContactDetailRepository;
use App\Traits\FileUploadTrait;

class ContactDetailService
{
    use FileUploadTrait;
    private $contactDetailRepository;

    public function __construct()
    {
        $this->contactDetailRepository = new ContactDetailRepository();
    }

    public function findAll()
    {
        return $this->contactDetailRepository->findAll();
    }

    public function findById($id)
    {
        return $this->contactDetailRepository->findById($id);
    }

    public function create(ContactDetailDto $contactDetailDto)
    {
        return $this->contactDetailRepository->create([
            'title' => $contactDetailDto->title,
            'title_hi' => $contactDetailDto->title_hi,
            'department' => $contactDetailDto->department,
            'department_hi' => $contactDetailDto->department_hi,
            'address' => $contactDetailDto->address,
            'address_hi' => $contactDetailDto->address_hi,
            'phone_numbers' => $contactDetailDto->phone_numbers,
            'email_ids' => $contactDetailDto->email_ids,
            'profile_image' => $contactDetailDto->profile_image,
            'myorder' => $contactDetailDto->myorder,
            'is_approved' => $contactDetailDto->is_approved,
            'is_published' => $contactDetailDto->is_published,
            'remarks' => $contactDetailDto->remarks,
            'publish_remark' => $contactDetailDto->publish_remark,
            'created_by' => $contactDetailDto->created_by,
            'updated_by' => $contactDetailDto->updated_by,
        ]);
    }

    public function update(ContactDetailDto $contactDetailDto, $id)
    {

        $data = [
            'title' => $contactDetailDto->title,
            'title_hi' => $contactDetailDto->title_hi,
            'department' => $contactDetailDto->department,
            'department_hi' => $contactDetailDto->department_hi,
            'address' => $contactDetailDto->address,
            'address_hi' => $contactDetailDto->address_hi,
            'phone_numbers' => $contactDetailDto->phone_numbers,
            'email_ids' => $contactDetailDto->email_ids,
            'myorder' => $contactDetailDto->myorder,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $contactDetailDto->updated_by,
        ];

        if ($contactDetailDto->profile_image) {
            $data['profile_image'] = $contactDetailDto->profile_image;
        }

        return $this->contactDetailRepository->update($data, $id);
    }


    public function delete($id)
    {
        return $this->contactDetailRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updatedData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->contactDetailRepository->update($updatedData, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $updatedData = [
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->contactDetailRepository->update($updatedData, $id);
    }
}
