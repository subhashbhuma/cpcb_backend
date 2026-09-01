<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LogExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Exports\AuditLogsArchiveExport;
use App\Exports\AuthLogsArchiveExport;

class GenerateLogExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $logExport;

    /**
     * Create a new job instance.
     */
    public function __construct(LogExport $logExport)
    {
        $this->logExport = $logExport;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->logExport->update(['status' => 'processing']);

        try {
            $fileName = '';
            $filePath = '';

            if ($this->logExport->export_type === 'audit') {
                $fileName = 'audit_logs_' . time() . '_' . $this->logExport->user_id . '.xlsx';
                $filePath = 'exports/' . $fileName;
                Excel::store(new AuditLogsArchiveExport(), $filePath, 'local');

            } elseif ($this->logExport->export_type === 'auth') {
                $fileName = 'auth_logs_' . time() . '_' . $this->logExport->user_id . '.xlsx';
                $filePath = 'exports/' . $fileName;
                Excel::store(new AuthLogsArchiveExport(), $filePath, 'local');

            } elseif ($this->logExport->export_type === 'system') {
                $fileName = 'system_logs_' . time() . '_' . $this->logExport->user_id . '.zip';
                $filePath = 'exports/' . $fileName;
                $absoluteZipFile = Storage::disk('local')->path($filePath);
                
                // Ensure directory exists
                Storage::disk('local')->makeDirectory('exports');
                
                $zip = new \ZipArchive();
                if ($zip->open($absoluteZipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                    $logsPath = storage_path('logs');
                    if (is_dir($logsPath)) {
                        $logFiles = \Illuminate\Support\Facades\File::files($logsPath);
                        foreach ($logFiles as $logFile) {
                            if ($logFile->getExtension() === 'log') {
                                $zip->addFile($logFile->getPathname(), $logFile->getFilename());
                            }
                        }
                    }
                    $zip->close();
                } else {
                    throw new \Exception('Failed to create zip archive.');
                }

            } elseif ($this->logExport->export_type === 'all') {
                $fileName = 'master_logs_' . time() . '_' . $this->logExport->user_id . '.zip';
                $filePath = 'exports/' . $fileName;
                $absoluteZipFile = Storage::disk('local')->path($filePath);
                
                Storage::disk('local')->makeDirectory('exports');

                $zip = new \ZipArchive();
                if ($zip->open($absoluteZipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                    
                    // Audit Excel
                    $auditExcel = 'temp_audit_' . time() . '.xlsx';
                    Excel::store(new AuditLogsArchiveExport(), $auditExcel, 'local');
                    $zip->addFile(Storage::disk('local')->path($auditExcel), 'audit_logs.xlsx');
                    
                    // Auth Excel
                    $authExcel = 'temp_auth_' . time() . '.xlsx';
                    Excel::store(new AuthLogsArchiveExport(), $authExcel, 'local');
                    $zip->addFile(Storage::disk('local')->path($authExcel), 'authentication_logs.xlsx');
                    
                    // System Logs
                    $logsPath = storage_path('logs');
                    if (is_dir($logsPath)) {
                        $logFiles = \Illuminate\Support\Facades\File::files($logsPath);
                        foreach ($logFiles as $logFile) {
                            if ($logFile->getExtension() === 'log') {
                                $zip->addFile($logFile->getPathname(), 'laravel_logs/' . $logFile->getFilename());
                            }
                        }
                    }
                    
                    $zip->close();
                    
                    // Cleanup temp
                    Storage::disk('local')->delete([$auditExcel, $authExcel]);
                } else {
                    throw new \Exception('Failed to create master zip archive.');
                }
            }

            $this->logExport->update([
                'status' => 'completed',
                'file_path' => $filePath,
                'file_name' => $fileName,
            ]);

        } catch (\Exception $e) {
            $this->logExport->update(['status' => 'failed']);
            \Log::error('GenerateLogExportJob failed: ' . $e->getMessage());
        }
    }
}
