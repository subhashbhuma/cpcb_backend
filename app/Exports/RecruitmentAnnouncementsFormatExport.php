<?php

namespace App\Exports;

use App\Models\JobPost;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RecruitmentAnnouncementsFormatExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return JobPost::with('job')->get();
    }

    public function headings(): array
    {
        return [
            'job title (read-only)',
            'post_id',
            'post_title',
            'title',
            'title_hi',
            'start_date',
            'end_date',
            'file_name',
            'file_name_hi',
            'remarks',
            'is_approved',
            'is_published',
            'publish_remark',
        ];
    }

    public function map($jobPost): array
    {
        return [
            $jobPost->job->title ?? '',
            $jobPost->id, // post_id
            $jobPost->title ?? '',
            '', // title
            '', // title_hi
            '', // start_date
            '', // end_date
            '', // file_name
            '', // file_name_hi
            '', // remarks
            '0', // is_approved
            '0', // is_published
            '', // publish_remark
        ];
    }
}
