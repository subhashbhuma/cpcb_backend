<?php

namespace App\Http\Controllers\Secure;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class AuditLogController extends Controller
{
    protected $auditLogService;

    public function __construct()
    {
        $this->auditLogService = new AuditLogService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Audit Log';
        
        $archives = [];
        if (\Illuminate\Support\Facades\Storage::disk('local')->exists('log_archives')) {
            $files = \Illuminate\Support\Facades\Storage::disk('local')->files('log_archives');
            foreach ($files as $file) {
                $archives[] = [
                    'name' => basename($file),
                    'size' => round(\Illuminate\Support\Facades\Storage::disk('local')->size($file) / 1024, 2) . ' KB',
                    'modified_at' => Carbon::createFromTimestamp(\Illuminate\Support\Facades\Storage::disk('local')->lastModified($file))->format('Y-m-d H:i:s'),
                ];
            }
        }
        
        // sort by modified_at desc
        usort($archives, function($a, $b) {
            return $b['modified_at'] <=> $a['modified_at'];
        });

        return view('secure.audit_logs.index', compact('pageTitle', 'archives'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $auditLogs = $this->auditLogService->findAll();

            return DataTables::of($auditLogs)
                ->addColumn('causer', function ($row) {
                    return $row->causer ? ['id' => $row->causer->id, 'name' => $row->causer->name] : null;
                })
                ->addColumn('changes', function ($row) {
                    $changes = [];
                    $old = $row->properties['old'] ?? [];
                    $new = $row->properties['attributes'] ?? [];
                    foreach ($new as $key => $value) {
                        $oldValue = $old[$key] ?? null;
                        if ($oldValue != $value) {
                            // Format key name
                            $label = Str::title(str_replace('_', ' ', $key));
                            // Format values
                            $formattedOld = $this->formatValue($key, $oldValue);
                            $formattedNew = $this->formatValue($key, $value);

                            if (is_null($oldValue)) {
                                $changes[] = "
        <strong>{$label}</strong>: 
        <span class='text-success'>{$formattedNew}</span>
    ";
                            } elseif (is_null($value)) {
                                $changes[] = "
        <strong>{$label}</strong>: 
        <span class='text-danger'>{$formattedOld}</span>
    ";
                            } else {
                                $changes[] = "
        <strong>{$label}</strong>: 
        <span class='text-danger'>{$formattedOld}</span> → 
        <span class='text-success'>{$formattedNew}</span>
    ";
                            }
                        }
                    }

                    return implode('<br>', $changes);
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->rawColumns(['causer', 'changes'])
                ->make(true);
        }
    }

    private function formatValue($key, $value)
    {
        // Format datetime fields
        if (Str::contains($key, ['date', 'time', 'at'])) {
            try {
                if ($value != '') {
                    return Carbon::parse($value)->format('d M Y, h:i A');
                }
                return '';
            } catch (\Exception $e) {
                return e($value);
            }
        }

        // Format boolean
        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        return e($value);
    }

    /**
     * Dispatch an export job to the queue.
     */
    private function queueExport($type)
    {
        $export = \App\Models\LogExport::create([
            'user_id' => auth()->id(),
            'export_type' => $type,
            'status' => 'pending',
        ]);

        \App\Jobs\GenerateLogExportJob::dispatch($export);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your export has been queued. It will appear in the "Requested Exports" table when ready.'
            ]);
        }

        return redirect()->back()->with('success', 'Your export has been queued. It will appear in the "Requested Exports" table when ready.');
    }

    /**
     * Download all current live logs into a ZIP file (Queued).
     */
    public function downloadAllLogs()
    {
        return $this->queueExport('all');
    }

    /**
     * Download Audit Logs Excel directly (Queued).
     */
    public function downloadAuditLogs()
    {
        return $this->queueExport('audit');
    }

    /**
     * Download Auth/OTP Logs Excel directly (Queued).
     */
    public function downloadAuthLogs()
    {
        return $this->queueExport('auth');
    }

    /**
     * Download System/Storage Logs Zip directly (Queued).
     */
    public function downloadSystemLogs()
    {
        return $this->queueExport('system');
    }

    /**
     * Download a completed queued export.
     */
    public function downloadReadyExport($id)
    {
        $export = \App\Models\LogExport::findOrFail($id);
        
        if ($export->user_id !== auth()->id() && !auth()->user()->hasRole('SUPER_ADMIN')) {
            abort(403);
        }

        if ($export->status !== 'completed' || empty($export->file_path)) {
            return redirect()->back()->with('error', 'Export is not ready yet.');
        }

        if (\Illuminate\Support\Facades\Storage::disk('local')->exists($export->file_path)) {
            return \Illuminate\Support\Facades\Storage::disk('local')->download($export->file_path, $export->file_name);
        }

        return redirect()->back()->with('error', 'Export file not found on server.');
    }
}
