<?php

namespace App\DTO;

class JobDto
{
    public $title;
    public $title_hi;
    public $start_date;
    public $end_date;
    public $advertisement_file_name;
    public $advertisement_file_hi_name;
    public $direct_application_form_name;
    public $direct_application_form_hi_name;
    public $deputation_application_form_name;
    public $deputation_application_form_hi_name;
    public $job_type;
    public $direct_application;
    public $deputation_application;
    public $direct_application_url;
    public $deputation_application_url;
    public $online_form_url;
    public $walk_in_interview_date;
    public $posts;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $created_at;
    public $updated_by;
    public $updated_at;

    public function __construct(
        $title,
        $title_hi,
        $start_date,
        $end_date = null,
        $advertisement_file_name = null,
        $advertisement_file_hi_name = null,
        $direct_application_form_name = null,
        $direct_application_form_hi_name = null,
        $deputation_application_form_name = null,
        $deputation_application_form_hi_name = null,
        $job_type = 'regular',
        $direct_application = 'offline',
        $deputation_application = 'offline',
        $direct_application_url = null,
        $deputation_application_url = null,
        $online_form_url = null,
        $walk_in_interview_date = null,
        $posts = [],
        $is_approved = 0,
        $is_published = 0,
        $created_by = null,
        $created_at = null,
        $updated_by = null,
        $updated_at = null,
        $remarks = null,
        $publish_remark = null
    ) {
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->advertisement_file_name = $advertisement_file_name;
        $this->advertisement_file_hi_name = $advertisement_file_hi_name;
        $this->direct_application_form_name = $direct_application_form_name;
        $this->direct_application_form_hi_name = $direct_application_form_hi_name;
        $this->deputation_application_form_name = $deputation_application_form_name;
        $this->deputation_application_form_hi_name = $deputation_application_form_hi_name;
        $this->job_type = $job_type;
        $this->direct_application = $direct_application;
        $this->deputation_application = $deputation_application;
        $this->direct_application_url = $direct_application_url;
        $this->deputation_application_url = $deputation_application_url;
        $this->online_form_url = $online_form_url;
        $this->walk_in_interview_date = $walk_in_interview_date;
        $this->posts = $posts;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->created_by = $created_by;
        $this->created_at = $created_at;
        $this->updated_by = $updated_by;
        $this->updated_at = $updated_at;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'advertisement_file_name' => $this->advertisement_file_name,
            'advertisement_file_hi_name' => $this->advertisement_file_hi_name,
            'direct_application_form_name' => $this->direct_application_form_name,
            'direct_application_form_hi_name' => $this->direct_application_form_hi_name,
            'deputation_application_form_name' => $this->deputation_application_form_name,
            'deputation_application_form_hi_name' => $this->deputation_application_form_hi_name,
            'job_type' => $this->job_type,
            'direct_application' => $this->direct_application,
            'deputation_application' => $this->deputation_application,
            'direct_application_url' => $this->direct_application_url,
            'deputation_application_url' => $this->deputation_application_url,
            'online_form_url' => $this->online_form_url,
            'walk_in_interview_date' => $this->walk_in_interview_date,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ];
    }
}
