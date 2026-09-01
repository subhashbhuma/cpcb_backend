<?php

namespace App\Exports;

use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AuditLogsArchiveExport implements FromQuery, WithHeadings, WithMapping
{
    protected $cutoffDate;
    
    // Pass null if we want all logs, or a date if we only want older logs
    public function __construct(?Carbon $cutoffDate = null)
    {
        $this->cutoffDate = $cutoffDate;
    }

    public function query()
    {
        $query = Activity::query()->orderBy('created_at', 'desc');
        if ($this->cutoffDate) {
            $query->where('created_at', '<', $this->cutoffDate);
        }
        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Log Name',
            'Description',
            'Subject Type',
            'Subject ID',
            'Causer Type',
            'Causer ID',
            'Properties',
            'Created At',
            'Updated At',
        ];
    }

    public function map($activity): array
    {
        return [
            $activity->id,
            $activity->log_name,
            $activity->description,
            $activity->subject_type,
            $activity->subject_id,
            $activity->causer_type,
            $activity->causer_id,
            json_encode($activity->properties),
            $activity->created_at,
            $activity->updated_at,
        ];
    }
}
