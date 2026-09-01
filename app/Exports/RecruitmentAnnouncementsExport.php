<?php

namespace App\Exports;

use App\Models\RecruitmentAnnouncement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RecruitmentAnnouncementsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return RecruitmentAnnouncement::with('jobPost.job')->orderBy('id', 'DESC')->get();
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

    public function map($announcement): array
    {
        return [
            $announcement->jobPost->job->title ?? '',
            $announcement->job_post_id,
            $announcement->jobPost->title ?? '',
            $announcement->title,
            $announcement->title_hi,
            $announcement->start_date,
            $announcement->end_date,
            $announcement->file_name,
            $announcement->file_name_hi,
            $announcement->remarks,
            $announcement->is_approved,
            $announcement->is_published,
            $announcement->publish_remark,
        ];
    }
}
