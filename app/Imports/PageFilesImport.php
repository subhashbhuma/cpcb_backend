<?php

namespace App\Imports;

use App\Models\PageFile;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PageFilesImport implements ToModel, WithHeadingRow
{
    protected $pageId;
    protected $updatedCount = 0;
    protected $insertedCount = 0;
    protected $skippedCount = 0;

    public function __construct($pageId)
    {
        $this->pageId = $pageId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            Log::info('Importing page file row data:', $row);

            // Parse upload_date — handle Excel serial date numbers
            $uploadDate = $this->parseDate($row['upload_date'] ?? null);

            // If id is provided, update existing record
            if (!empty($row['id'])) {
                return $this->updateExistingRecord($row, $uploadDate);
            }

            // If id is empty, create a new record
            return $this->createNewRecord($row, $uploadDate);
        } catch (\Exception $e) {
            Log::error('Error importing PageFile row: ' . $e->getMessage(), ['row' => $row]);
            $this->skippedCount++;
            return null;
        }
    }

    /**
     * Update an existing PageFile record.
     */
    protected function updateExistingRecord(array $row, ?string $uploadDate)
    {
        // Find the existing page file and verify it belongs to this page
        $pageFile = PageFile::where('id', $row['id'])
            ->where('page_id', $this->pageId)
            ->first();

        if (!$pageFile) {
            Log::warning('Skipping row: page file not found or does not belong to this page', [
                'id' => $row['id'],
                'page_id' => $this->pageId,
            ]);
            $this->skippedCount++;
            return null;
        }

        // Update the metadata fields
        $pageFile->update([
            'title' => isset($row['title']) ? strip_tags((string) $row['title']) : $pageFile->title,
            'title_hi' => isset($row['title_hi']) ? strip_tags((string) $row['title_hi']) : $pageFile->title_hi,
            'upload_date' => $uploadDate ?? $pageFile->upload_date,
            'order_number' => isset($row['order_number']) ? (int) $row['order_number'] : $pageFile->order_number,
            'updated_by' => Auth::id(),
        ]);

        $this->updatedCount++;
        return null;
    }

    /**
     * Create a new PageFile record (when id is empty in Excel).
     */
    protected function createNewRecord(array $row, ?string $uploadDate)
    {
        // Must have at least a title to create a meaningful record
        $title = isset($row['title']) ? strip_tags((string) $row['title']) : null;
        $titleHi = isset($row['title_hi']) ? strip_tags((string) $row['title_hi']) : null;

        if (empty($title) && empty($titleHi)) {
            Log::warning('Skipping new row: no title provided', ['row' => $row]);
            $this->skippedCount++;
            return null;
        }

        PageFile::create([
            'page_id' => $this->pageId,
            'title' => $title,
            'title_hi' => $titleHi,
            'upload_date' => $uploadDate ?? date('Y-m-d'),
            'order_number' => isset($row['order_number']) ? (int) $row['order_number'] : 0,
            'file_name' => $row['file_name'] ?? null,
            'file_name_hi' => $row['file_name_hi'] ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $this->insertedCount++;
        return null;
    }

    /**
     * Parse date value — handles Excel serial date numbers and string dates.
     */
    protected function parseDate($dateValue): ?string
    {
        if (empty($dateValue)) {
            return null;
        }

        if (is_numeric($dateValue)) {
            try {
                return ExcelDate::excelToDateTimeObject($dateValue)->format('Y-m-d');
            } catch (\Exception $e) {
                Log::warning('Could not parse Excel date serial: ' . $dateValue);
                return null;
            }
        }

        return (string) $dateValue;
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
