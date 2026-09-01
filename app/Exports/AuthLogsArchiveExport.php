<?php

namespace App\Exports;

use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AuthLogsArchiveExport implements FromQuery, WithHeadings, WithMapping
{
    protected $cutoffDate;
    
    // Pass null if we want all logs, or a date if we only want older logs
    public function __construct(?Carbon $cutoffDate = null)
    {
        $this->cutoffDate = $cutoffDate;
    }

    public function query()
    {
        $query = AuthenticationLog::query()->orderBy('login_at', 'desc');
        if ($this->cutoffDate) {
            $query->where('login_at', '<', $this->cutoffDate);
        }
        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Authenticatable Type',
            'Authenticatable ID',
            'IP Address',
            'User Agent',
            'Login At',
            'Login Successful',
            'Logout At',
            'Cleared By User',
            'Location',
        ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->authenticatable_type,
            $log->authenticatable_id,
            $log->ip_address,
            $log->user_agent,
            $log->login_at,
            $log->login_successful ? 'Yes' : 'No',
            $log->logout_at,
            $log->cleared_by_user ? 'Yes' : 'No',
            json_encode($log->location),
        ];
    }
}
