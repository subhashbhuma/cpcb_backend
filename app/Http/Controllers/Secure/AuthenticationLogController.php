<?php

namespace App\Http\Controllers\Secure;

use App\Http\Controllers\Controller;
use App\Services\AuthenticationLogService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

class AuthenticationLogController extends Controller
{
    protected $authenticationLogService;

    public function __construct()
    {
        $this->authenticationLogService = new AuthenticationLogService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Authentication Log';
        return view('secure.authentication_logs.index', compact('pageTitle'));
    }

    /**
     * Fetch a listing of the resource.
     */
    public function fetchForDatatable(Request $request)
    {
        if ($request->ajax()) {
            $logs = AuthenticationLog::with('authenticatable')->orderBy('id', 'DESC');

            return DataTables::of($logs)
                ->addColumn('time_stamp', function ($log) {
                    $date = $log->login_at ?? $log->logout_at;
                    return $date ? \Carbon\Carbon::parse($date)->format('d/m/Y;H.i.s') : 'N/A';
                })
                ->addColumn('ip_address', function ($log) {
                    return $log->ip_address ?? '-';
                })
                ->addColumn('browser', function ($log) {
                    $agent = new Agent();
                    $agent->setUserAgent($log->user_agent);
                    return $agent->browser();
                })
                ->addColumn('platform', function ($log) {
                    $agent = new Agent();
                    $agent->setUserAgent($log->user_agent);
                    return $agent->platform();
                })
                ->addColumn('login_successful_desc', function ($log) {
                    // Logic to prioritize Logout status:
                    // 1. If logout_at is present, it is a completed session or logout event.
                    if ($log->logout_at != null) {
                        return '<span class="badge bg-warning">Logout</span>';
                    }

                    // 2. Otherwise, check if it was a successful or failed login attempt.
                    if ($log->login_at != null) {
                        return $log->login_successful == 1
                            ? '<span class="badge bg-success">Login Successful</span>'
                            : '<span class="badge bg-danger">Login Failed</span>';
                    }

                    return '<span class="badge bg-secondary">Unknown</span>';
                })



                ->rawColumns(['login_at', 'logout_at', 'ip_address', 'browser', 'platform', 'login_successful_desc'])
                ->make(true);
        }
    }
}
