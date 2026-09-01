<div class="row">
    <!-- Admin/Super Admin gets all cards -->
    <div class="col-12 mb-3">
        <h4 class="text-primary">{{ auth()->user()->hasRole('SUPER_ADMIN') ? 'Super Admin' : (auth()->user()->hasRole('ADMIN') ? 'Admin' : 'Employee') }} Dashboard</h4>
    </div>
    

    @include('secure.dashboard.roles.partials.content_cards')
    
    @hasanyrole('SUPER_ADMIN|ADMIN')
    <!-- System & Admin Stats -->
    <div class="col-xl-8 col-md-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h4>Recent Activity Logs</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $activity)
                                <tr>
                                    <td>{{ $activity->causer->name ?? 'System' }}</td>
                                    <td><span class="badge {{ $activity->description == 'created' ? 'bg-light-success' : ($activity->description == 'deleted' ? 'bg-light-danger' : 'bg-light-info') }}">{{ ucfirst($activity->description) }}</span></td>
                                    <td>{{ ucfirst($activity->log_name) }}</td>
                                    <td>{{ $activity->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No recent activity found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h4>Platform Overview</h4>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="mb-0 text-muted">Total Visitors</p>
                    <h5>{{ number_format($stats['visitors']['total']) }}</h5>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="mb-0 text-muted">Today's Visitors</p>
                    <h5>{{ number_format($stats['visitors']['today']) }}</h5>
                </div>
                <hr>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="mb-0 text-muted">Total Menus</p>
                    <a href="{{ route('menus.index') }}">
                        <h5>{{ $stats['menus'] }}</h5>
                    </a>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="mb-0 text-muted">Total Pages</p>
                    <a href="{{ route('pages.index') }}">
                        <h5>{{ $stats['pages'] }}</h5>
                    </a>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="mb-0 text-muted">Media Library</p>
                    <a href="{{ route('medias.index') }}">
                        <h5>{{ $stats['media_files'] }}</h5>
                    </a>
                </div>
                <hr>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="mb-0 text-muted">Total Users</p>
                    <h5>{{ $stats['users']['total'] }}</h5>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="mb-0 text-muted">Total Feedback</p>
                    <span class="badge bg-light-primary">{{ $stats['feedback'] }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <p class="mb-0 text-muted">Total Complaints</p>
                    <span class="badge bg-light-danger">{{ $stats['complaints'] }}</span>
                </div>
            </div>
        </div>
    </div>
    @endhasanyrole

    @hasanyrole('SUPER_ADMIN|ADMIN')
    <!-- System Logs Downloads Section (Immersive Bottom UI) -->
    <div class="col-12 mt-4 mb-5">
        <h4 class="mb-4"><i class="ti ti-download me-2 text-primary"></i>System Log Exports</h4>
        <div class="row g-4">
            <!-- Audit Logs Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 shadow-sm border-0 transition-hover" style="border-radius: 15px; overflow: hidden; background: linear-gradient(145deg, #ffffff, #f5f8ff);">
                    <div class="card-body text-center p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="avtar avtar-l bg-light-primary text-primary mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%;">
                                <i class="ti ti-file-analytics fs-2"></i>
                            </div>
                            <h5 class="mb-2 fw-bold text-dark">Audit Logs</h5>
                            <p class="text-muted text-sm mb-4">Export detailed user activity and system audit records in an Excel spreadsheet.</p>
                        </div>
                        <a href="javascript:void(0)" data-url="{{ route('audit-logs.download-audit') }}" class="btn btn-primary rounded-pill w-100 shadow-sm ajax-export-btn" style="transition: all 0.3s;">
                            <i class="ti ti-download me-1"></i> Download Excel
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- OTP / Auth Logs Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 shadow-sm border-0 transition-hover" style="border-radius: 15px; overflow: hidden; background: linear-gradient(145deg, #ffffff, #f0fbff);">
                    <div class="card-body text-center p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="avtar avtar-l bg-light-info text-info mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%;">
                                <i class="ti ti-key fs-2"></i>
                            </div>
                            <h5 class="mb-2 fw-bold text-dark">OTP & Auth Logs</h5>
                            <p class="text-muted text-sm mb-4">Download secure authentication attempts, logins, and OTP validations securely.</p>
                        </div>
                        <a href="javascript:void(0)" data-url="{{ route('audit-logs.download-auth') }}" class="btn btn-info rounded-pill w-100 shadow-sm text-white ajax-export-btn" style="transition: all 0.3s;">
                            <i class="ti ti-download me-1"></i> Download Excel
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Logs Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 shadow-sm border-0 transition-hover" style="border-radius: 15px; overflow: hidden; background: linear-gradient(145deg, #ffffff, #fffdf2);">
                    <div class="card-body text-center p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="avtar avtar-l bg-light-warning text-warning mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%;">
                                <i class="ti ti-server fs-2"></i>
                            </div>
                            <h5 class="mb-2 fw-bold text-dark">Server Logs</h5>
                            <p class="text-muted text-sm mb-4">Export the raw physical Laravel text logs directly from the system storage.</p>
                        </div>
                        <a href="javascript:void(0)" data-url="{{ route('audit-logs.download-system') }}" class="btn btn-warning rounded-pill w-100 shadow-sm text-dark ajax-export-btn" style="transition: all 0.3s;">
                            <i class="ti ti-download me-1"></i> Download Zip
                        </a>
                    </div>
                </div>
            </div>

            <!-- Master Zip Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-dark h-100 shadow border-0 transition-hover" style="border-radius: 15px; overflow: hidden; background: linear-gradient(145deg, #2b323b, #1e242a) !important;">
                    <div class="card-body text-center p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="avtar avtar-l text-white mx-auto mb-3" style="width: 60px; height: 60px; border-radius: 50%; background: rgba(255,255,255,0.1);">
                                <i class="ti ti-archive fs-2"></i>
                            </div>
                            <h5 class="mb-2 fw-bold text-white">Master Archive</h5>
                            <p class="text-white-50 text-sm mb-4">Download a complete compressed archive containing all logs simultaneously.</p>
                        </div>
                        <a href="javascript:void(0)" data-url="{{ route('audit-logs.download-all') }}" class="btn btn-light rounded-pill w-100 shadow-sm text-dark fw-bold ajax-export-btn" style="transition: all 0.3s;">
                            <i class="ti ti-download me-1"></i> Download All
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requested Exports Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header border-bottom bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Your Requested Exports</h5>
                        <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()"><i class="ti ti-refresh me-1"></i>Refresh Status</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="requested-exports-table">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Export Type</th>
                                        <th>Status</th>
                                        <th>Requested At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logExports ?? [] as $export)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-dark">
                                                    {{ strtoupper($export->export_type) }} Logs
                                                </span>
                                            </td>
                                            <td>
                                                @if($export->status === 'completed')
                                                    <span class="badge bg-light-success text-success"><i class="ti ti-check me-1"></i>Completed</span>
                                                @elseif($export->status === 'processing')
                                                    <span class="badge bg-light-warning text-warning"><i class="ti ti-loader me-1"></i>Processing...</span>
                                                @elseif($export->status === 'failed')
                                                    <span class="badge bg-light-danger text-danger"><i class="ti ti-x me-1"></i>Failed</span>
                                                @else
                                                    <span class="badge bg-light-secondary text-secondary"><i class="ti ti-clock me-1"></i>Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $export->created_at->format('d M Y, h:i A') }}</td>
                                            <td>
                                                @if($export->status === 'completed')
                                                    <a href="{{ route('audit-logs.download-ready', $export->id) }}" class="btn btn-sm btn-primary rounded-pill">
                                                        <i class="ti ti-download"></i> Download
                                                    </a>
                                                @elseif($export->status === 'failed')
                                                    <span class="text-muted text-sm">N/A</span>
                                                @else
                                                    <span class="text-muted text-sm">Please wait...</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">You haven't requested any exports yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endhasanyrole
</div>

@push('pages-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!$.fn.DataTable.isDataTable('#requested-exports-table')) {
            $('#requested-exports-table').DataTable({
                "order": [[ 2, "desc" ]],
                "pageLength": 10,
                "language": {
                    "emptyTable": "You haven't requested any exports yet."
                }
            });
        }
        
        $('.ajax-export-btn').on('click', function(e) {
            e.preventDefault();
            let btn = $(this);
            let url = btn.data('url');
            
            let originalHtml = btn.html();
            btn.html('<i class="ti ti-loader me-1"></i> Queuing...');
            btn.prop('disabled', true).addClass('disabled');
            
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message || 'Your export has been queued successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        alert(response.message || 'Your export has been queued successfully.');
                        window.location.reload();
                    }
                },
                error: function() {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong while queuing the export.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        alert('Something went wrong while queuing the export.');
                    }
                    btn.html(originalHtml);
                    btn.prop('disabled', false).removeClass('disabled');
                }
            });
        });
    });
</script>
@endpush
