<!-- Summary Cards Module -->
@can('view tender')
<div class="col-md-6 col-xl-3">
    <div class="card bg-grd-primary order-card overflow-hidden dashboard_content">
        <div class="card-body position-relative">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="text-white mb-0 dashboard_content_title">Tenders</h6>
                <i class="ti ti-file-text fs-1 text-white opacity-50"></i>
            </div>
            <h2 class="text-white mb-3">
                <a href="{{ route('tenders.index') }}"
                    class="text-white text-decoration-none">{{ $stats['tenders']['total'] }}</a>
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('tenders.index', ['status' => 'published']) }}"
                    class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Published</p>
                    <strong>{{ $stats['tenders']['published'] }}</strong>
                </a>
                <a href="{{ route('tenders.index', ['status' => 'pending']) }}" class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Pending</p>
                    <strong>{{ $stats['tenders']['pending'] }}</strong>
                </a>
            </div>
        </div>
    </div>
</div>
@endcan

@can('view circular')
<div class="col-md-6 col-xl-3">
    <div class="card bg-grd-success order-card overflow-hidden dashboard_content">
        <div class="card-body position-relative">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="text-white mb-0 dashboard_content_title">Circulars</h6>
                <i class="ti ti-refresh fs-1 text-white opacity-50"></i>
            </div>

            <h2 class="text-white mb-3">
                <a href="{{ route('circulars.index') }}"
                    class="text-white text-decoration-none">{{ $stats['circulars']['total'] }}</a>
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('circulars.index', ['status' => 'published']) }}"
                    class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Published</p>
                    <strong>{{ $stats['circulars']['published'] }}</strong>
                </a>
                <a href="{{ route('circulars.index', ['status' => 'pending']) }}"
                    class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Pending</p>
                    <strong>{{ $stats['circulars']['pending'] }}</strong>
                </a>
            </div>
        </div>
    </div>
</div>
@endcan

@can('view direction')
<div class="col-md-6 col-xl-3">
    <div class="card bg-grd-info order-card overflow-hidden dashboard_content">
        <div class="card-body position-relative">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="text-white mb-0 dashboard_content_title">Directions</h6>
                <i class="ti ti-direction fs-1 text-white opacity-50"></i>
            </div>
            <h2 class="text-white mb-3">
                <a href="{{ route('direction.index') }}"
                    class="text-white text-decoration-none">{{ $stats['directions']['total'] }}</a>
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('direction.index', ['status' => 'published']) }}"
                    class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Published</p>
                    <strong>{{ $stats['directions']['published'] }}</strong>
                </a>
                <a href="{{ route('direction.index', ['status' => 'pending']) }}"
                    class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Pending</p>
                    <strong>{{ $stats['directions']['pending'] }}</strong>
                </a>
            </div>
        </div>
    </div>
</div>
@endcan

@can('view letters_issued')
<div class="col-md-6 col-xl-3">
    <div class="card bg-grd-purple order-card overflow-hidden dashboard_content">
        <div class="card-body position-relative">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="text-white mb-0 dashboard_content_title">Latest Issued</h6>
                <i class="ti ti-mail-forward fs-1 text-white opacity-50"></i>
            </div>
            <h2 class="text-white mb-3">
                <a href="{{ route('letters-issued.index') }}"
                    class="text-white text-decoration-none">{{ $stats['letters_issued']['total'] }}</a>
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('letters-issued.index', ['status' => 'published']) }}"
                    class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Published</p>
                    <strong>{{ $stats['letters_issued']['published'] }}</strong>
                </a>
                <a href="{{ route('letters-issued.index', ['status' => 'pending']) }}"
                    class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Pending</p>
                    <strong>{{ $stats['letters_issued']['pending'] }}</strong>
                </a>
            </div>
        </div>
    </div>
</div>
@endcan

@can('view publication')
<div class="col-md-6 col-xl-3">
    <div class="card bg-grd-emerald order-card overflow-hidden dashboard_content">
        <div class="card-body position-relative">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="text-white mb-0 dashboard_content_title">Publications</h6>
                <i class="ti ti-book fs-1 text-white opacity-50"></i>
            </div>
            <h2 class="text-white mb-3">
                <a href="{{ route('publication.index') }}"
                    class="text-white text-decoration-none">{{ $stats['publications']['total'] }}</a>
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('publication.index', ['status' => 'published']) }}"
                    class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Published</p>
                    <strong>{{ $stats['publications']['published'] }}</strong>
                </a>
                <a href="{{ route('publication.index', ['status' => 'pending']) }}"
                    class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Pending</p>
                    <strong>{{ $stats['publications']['pending'] }}</strong>
                </a>
            </div>
        </div>
    </div>
</div>
@endcan

@can('view technical report')
<div class="col-md-6 col-xl-3">
    <div class="card bg-grd-danger order-card overflow-hidden dashboard_content">
        <div class="card-body position-relative">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="text-white mb-0 dashboard_content_title">Technical Reports</h6>
                <i class="ti ti-report fs-1 text-white opacity-50"></i>
            </div>
            <h2 class="text-white mb-3">
                <a href="{{ route('technical_report.index') }}"
                    class="text-white text-decoration-none">{{ $stats['technical_reports']['total'] }}</a>
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('technical_report.index', ['status' => 'published']) }}"
                    class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Published</p>
                    <strong>{{ $stats['technical_reports']['published'] }}</strong>
                </a>
                <a href="{{ route('technical_report.index', ['status' => 'pending']) }}"
                    class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Pending</p>
                    <strong>{{ $stats['technical_reports']['pending'] }}</strong>
                </a>
            </div>
        </div>
    </div>
</div>
@endcan

@can('view announcement')
<div class="col-md-6 col-xl-3">
    <div class="card bg-grd-sunset order-card overflow-hidden dashboard_content">
        <div class="card-body position-relative">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="text-white mb-0 dashboard_content_title">Announcements</h6>
                <i class="ti ti-speakerphone fs-1 text-white opacity-50"></i>
            </div>
            <h2 class="text-white mb-3">
                <a href="{{ route('announcements.index') }}"
                    class="text-white text-decoration-none">{{ $stats['announcements']['total'] }}</a>
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('announcements.index', ['status' => 'published']) }}"
                    class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Published</p>
                    <strong>{{ $stats['announcements']['published'] }}</strong>
                </a>
                <a href="{{ route('announcements.index', ['status' => 'pending']) }}"
                    class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Pending</p>
                    <strong>{{ $stats['announcements']['pending'] }}</strong>
                </a>
            </div>
        </div>
    </div>
</div>
@endcan

@can('view job')
<div class="col-md-6 col-xl-3">
    <div class="card bg-grd-royal order-card overflow-hidden dashboard_content">
        <div class="card-body position-relative">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="text-white mb-0 dashboard_content_title">Jobs</h6>
                <i class="ti ti-briefcase fs-1 text-white opacity-50"></i>
            </div>
            <h2 class="text-white mb-3">
                <a href="{{ route('jobs.index') }}"
                    class="text-white text-decoration-none">{{ $stats['jobs']['total'] }}</a>
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('jobs.index', ['status' => 'published']) }}" class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Published</p>
                    <strong>{{ $stats['jobs']['published'] }}</strong>
                </a>
                <a href="{{ route('jobs.index', ['status' => 'pending']) }}" class="stat-unit flex-fill text-center">
                    <p class="d-block m-0 p-0 ">Pending</p>
                    <strong>{{ $stats['jobs']['pending'] }}</strong>
                </a>
            </div>
        </div>
    </div>
</div>
@endcan