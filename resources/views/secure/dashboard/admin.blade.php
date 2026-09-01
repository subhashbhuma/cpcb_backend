@extends('layouts.app_layout')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">System Logs Downloads</h5>
                    <small class="text-muted">Download historical logs in Excel and Zip formats.</small>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('audit-logs.download-audit') }}" class="btn btn-outline-primary w-100 d-flex flex-column align-items-center p-3">
                                <i class="ti ti-file-analytics fs-2 mb-2"></i>
                                <span>Audit Logs</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('audit-logs.download-auth') }}" class="btn btn-outline-info w-100 d-flex flex-column align-items-center p-3">
                                <i class="ti ti-key fs-2 mb-2"></i>
                                <span>OTP / Auth Logs</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('audit-logs.download-system') }}" class="btn btn-outline-warning w-100 d-flex flex-column align-items-center p-3">
                                <i class="ti ti-server fs-2 mb-2"></i>
                                <span>System/Server Logs</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('audit-logs.download-all') }}" class="btn btn-primary w-100 d-flex flex-column align-items-center p-3">
                                <i class="ti ti-download fs-2 mb-2 text-white"></i>
                                <span class="text-white">Download All (Master Zip)</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-muted">Total Page Views</h6>
                    <h4 class="mb-3">4,42,236 <span class="badge bg-light-primary border border-primary"><i
                                class="ti ti-trending-up"></i> 59.3%</span></h4>
                    <p class="mb-0 text-muted text-sm">You made an extra <span class="text-primary">35,000</span> this year
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-muted">Total Users</h6>
                    <h4 class="mb-3">78,250 <span class="badge bg-light-success border border-success"><i
                                class="ti ti-trending-up"></i> 70.5%</span></h4>
                    <p class="mb-0 text-muted text-sm">You made an extra <span class="text-success">8,900</span> this year
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-muted">Total Order</h6>
                    <h4 class="mb-3">18,800 <span class="badge bg-light-warning border border-warning"><i
                                class="ti ti-trending-down"></i> 27.4%</span></h4>
                    <p class="mb-0 text-muted text-sm">You made an extra <span class="text-warning">1,943</span> this year
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-muted">Total Sales</h6>
                    <h4 class="mb-3">$35,078 <span class="badge bg-light-danger border border-danger"><i
                                class="ti ti-trending-down"></i> 27.4%</span></h4>
                    <p class="mb-0 text-muted text-sm">You made an extra <span class="text-danger">$20,395</span> this year
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
    <!-- [Page Specific JS] start -->
    <script @cspNonce src="{{asset('assets/js/plugins/apexcharts.min.js')}}"></script>
    <script @cspNonce src="{{asset('assets/js/pages/dashboard-default.js')}}"></script>
    <!-- [Page Specific JS] end -->
@endsection