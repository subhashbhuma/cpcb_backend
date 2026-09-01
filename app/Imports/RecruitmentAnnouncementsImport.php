<?php

namespace App\Imports;

use App\Models\RecruitmentAnnouncement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class RecruitmentAnnouncementsImport implements ToModel, WithHeadingRow
{
    protected $updatedCount = 0;
    protected $insertedCount = 0;
    protected $skippedCount = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            // Check for post_id which maps to job_post_id
            if (empty($row['post_id'])) {
                Log::warning('Skipping row due to empty post_id', ['row' => $row]);
                $this->skippedCount++;
                return null;
            }

            $startDate = $this->parseDate($row['start_date'] ?? null);
            $endDate = $this->parseDate($row['end_date'] ?? null);
            
            $data = [
                'job_post_id' => $row['post_id'],
                'type' => 'notification',
                'title' => isset($row['title']) ? strip_tags((string) $row['title']) : '',
                'title_hi' => isset($row['title_hi']) ? strip_tags((string) $row['title_hi']) : null,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'file_name' => $row['file_name'] ?? null,
                'file_name_hi' => $row['file_name_hi'] ?? null,
                'remarks' => $row['remarks'] ?? null,
                'is_approved' => isset($row['is_approved']) ? (int) $row['is_approved'] : 0,
                'is_published' => isset($row['is_published']) ? (int) $row['is_published'] : 0,
                'publish_remark' => $row['publish_remark'] ?? null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];

            RecruitmentAnnouncement::create($data);
            $this->insertedCount++;

            return null;
        } catch (\Exception $e) {
            Log::error('Error importing RecruitmentAnnouncement row: ' . $e->getMessage(), ['row' => $row]);
            $this->skippedCount++;
            return null;
        }
    }

    private function parseDate($dateValue)
    {
        if (empty($dateValue)) return null;

        if (is_numeric($dateValue)) {
            try {
                return ExcelDate::excelToDateTimeObject($dateValue)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }
        
        return $dateValue;
    }

    public function getUpdatedCount(): int
    {
        return $this->updatedCount;
    }

    public function getInsertedCount(): int
    {
        return $this->insertedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }
}
