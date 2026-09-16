<!-- ========================================== -->
<!-- Section 1: Employee Documents & Services   -->
<!-- ========================================== -->
<div class="col-12 mb-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-2">
            <div class="emp-section-icon bg-primary text-white shadow-sm">
                <i class="ti ti-folders fs-4"></i>
            </div>
            <h5 class="fw-bold text-dark mb-0">Employee Documents & Services</h5>
        </div>
        <span class="badge bg-light-primary text-primary px-3 py-1.5 rounded-pill fw-semibold">
            <i class="ti ti-files me-1"></i> Internal Services
        </span>
    </div>
</div>

<!-- 1. Circulars -->
<div class="col-sm-6 col-lg-4 mb-3">
    <div class="card emp-glass-card border-0 h-100 emp-card-circular">
        <div class="card-body p-4 position-relative">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="emp-icon-circle bg-white text-primary shadow-sm">
                    <i class="ti ti-refresh fs-2"></i>
                </div>
                <h2 class="text-white fw-bold mb-0">{{ $stats['employee_circulars'] ?? 0 }}</h2>
            </div>
            <h5 class="text-white fw-bold mb-0">
                <a href="{{ url('secure/circulars/circular') }}" class="text-white text-decoration-none stretched-link">
                    Circulars
                </a>
            </h5>
        </div>
    </div>
</div>

<!-- 2. Office Orders -->
<div class="col-sm-6 col-lg-4 mb-3">
    <div class="card emp-glass-card border-0 h-100 emp-card-office">
        <div class="card-body p-4 position-relative">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="emp-icon-circle bg-white text-info shadow-sm">
                    <i class="ti ti-direction fs-2"></i>
                </div>
                <h2 class="text-white fw-bold mb-0">{{ $stats['employee_office_orders'] ?? 0 }}</h2>
            </div>
            <h5 class="text-white fw-bold mb-0">
                <a href="{{ url('secure/circulars/office_order') }}" class="text-white text-decoration-none stretched-link">
                    Office Orders
                </a>
            </h5>
        </div>
    </div>
</div>

<!-- 3. Memorandum -->
<div class="col-sm-6 col-lg-4 mb-3">
    <div class="card emp-glass-card border-0 h-100 emp-card-memorandum">
        <div class="card-body p-4 position-relative">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="emp-icon-circle bg-white text-secondary shadow-sm">
                    <i class="ti ti-file-text fs-2"></i>
                </div>
                <h2 class="text-white fw-bold mb-0">{{ $stats['employee_memorandums'] ?? 0 }}</h2>
            </div>
            <h5 class="text-white fw-bold mb-0">
                <a href="{{ url('secure/circulars/memorandum') }}" class="text-white text-decoration-none stretched-link">
                    Memorandum
                </a>
            </h5>
        </div>
    </div>
</div>

<!-- 4. Download Forms -->
<div class="col-sm-6 col-lg-6 mb-3">
    <div class="card emp-glass-card border-0 h-100 emp-card-forms">
        <div class="card-body p-4 position-relative">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="emp-icon-circle bg-white text-success shadow-sm">
                    <i class="ti ti-download fs-2"></i>
                </div>
                <i class="ti ti-arrow-up-right fs-2 text-white text-opacity-50"></i>
            </div>
            <h5 class="text-white fw-bold mb-0">
                <a href="{{ url('secure/download-forms') }}" class="text-white text-decoration-none stretched-link">
                    Download Forms
                </a>
            </h5>
        </div>
    </div>
</div>

<!-- 5. CPCB Medical Facility -->
<div class="col-sm-6 col-lg-6 mb-3">
    <div class="card emp-glass-card border-0 h-100 emp-card-medical">
        <div class="card-body p-4 position-relative">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="emp-icon-circle bg-white text-danger shadow-sm">
                    <i class="ti ti-heart-handshake fs-2"></i>
                </div>
                <i class="ti ti-arrow-up-right fs-2 text-white text-opacity-50"></i>
            </div>
            <h5 class="text-white fw-bold mb-0">
                <a href="{{ url('secure/csma-scheme-in-cpcb') }}" class="text-white text-decoration-none stretched-link">
                    CPCB Medical Facility
                </a>
            </h5>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- Visual Divider / Separation                -->
<!-- ========================================== -->
<div class="col-12 my-3">
    <div class="position-relative d-flex align-items-center justify-content-center">
        <div class="w-100 border-top border-2 border-slate-200"></div>
        <div class="position-absolute bg-white px-3 py-1 rounded-pill border shadow-xs d-flex align-items-center gap-2 text-muted small fw-semibold">
            <i class="ti ti-layout-grid text-primary"></i>
            <span>External Portals Gateway</span>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- Section 2: CPCBs Internal Portals          -->
<!-- ========================================== -->
<div class="col-12 mb-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-2">
            <div class="emp-section-icon bg-warning text-dark shadow-sm">
                <i class="ti ti-world fs-4"></i>
            </div>
            <h5 class="fw-bold text-dark mb-0">CPCBs Internal Portals</h5>
        </div>
        <span class="badge bg-light-warning text-dark px-3 py-1.5 rounded-pill fw-semibold border border-warning border-opacity-25">
            <i class="ti ti-external-link me-1"></i> External Systems
        </span>
    </div>
</div>

<!-- Portal 1: Legal Cases Portal -->
<div class="col-sm-6 col-lg-4 mb-3">
    <div class="card emp-glass-portal-card border-0 h-100 emp-portal-legal">
        <div class="card-body p-4 position-relative">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="emp-portal-icon-circle bg-white text-indigo shadow-sm">
                    <i class="ti ti-scale fs-2"></i>
                </div>
                <i class="ti ti-external-link fs-2 text-white text-opacity-50"></i>
            </div>
            <h5 class="text-white fw-bold mb-0">
                <a href="https://legal.cpcb.gov.in:5009/legalcase/#/home" target="_blank" class="text-white text-decoration-none stretched-link external-link">
                    Legal Cases Portal
                </a>
            </h5>
        </div>
    </div>
</div>

<!-- Portal 2: Annual Property Return / LMS -->
<div class="col-sm-6 col-lg-4 mb-3">
    <div class="card emp-glass-portal-card border-0 h-100 emp-portal-lms">
        <div class="card-body p-4 position-relative">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="emp-portal-icon-circle bg-white text-amber shadow-sm">
                    <i class="ti ti-calendar-time fs-2"></i>
                </div>
                <i class="ti ti-external-link fs-2 text-white text-opacity-50"></i>
            </div>
            <h5 class="text-white fw-bold mb-0">
                <a href="http://103.221.209.84:2004/lms/" target="_blank" class="text-white text-decoration-none stretched-link external-link">
                    Annual Property Return / LMS
                </a>
            </h5>
        </div>
    </div>
</div>

<!-- Portal 3: Internal Helpdesk Portal -->
<div class="col-sm-6 col-lg-4 mb-3">
    <div class="card emp-glass-portal-card border-0 h-100 emp-portal-helpdesk">
        <div class="card-body p-4 position-relative">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="emp-portal-icon-circle bg-white text-teal shadow-sm">
                    <i class="ti ti-headset fs-2"></i>
                </div>
                <i class="ti ti-external-link fs-2 text-white text-opacity-50"></i>
            </div>
            <h5 class="text-white fw-bold mb-0">
                <a href="http://10.24.84.19/glpi/" target="_blank" class="text-white text-decoration-none stretched-link external-link">
                    Internal Helpdesk Portal
                </a>
            </h5>
        </div>
    </div>
</div>

<!-- Portal 4: IT Division Gate Pass System -->
<div class="col-sm-6 col-lg-6 mb-3">
    <div class="card emp-glass-portal-card border-0 h-100 emp-portal-gatepass">
        <div class="card-body p-4 position-relative">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="emp-portal-icon-circle bg-white text-slate shadow-sm">
                    <i class="ti ti-shield-lock fs-2"></i>
                </div>
                <i class="ti ti-external-link fs-2 text-white text-opacity-50"></i>
            </div>
            <h5 class="text-white fw-bold mb-0">
                <a href="http://10.24.84.23/Gate/" target="_blank" class="text-white text-decoration-none stretched-link external-link">
                    IT Division Gate Pass System
                </a>
            </h5>
        </div>
    </div>
</div>

<!-- Portal 5: Workload Allotment System -->
<div class="col-sm-6 col-lg-6 mb-3">
    <div class="card emp-glass-portal-card border-0 h-100 emp-portal-workload">
        <div class="card-body p-4 position-relative">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="emp-portal-icon-circle bg-white text-violet shadow-sm">
                    <i class="ti ti-layout-kanban fs-2"></i>
                </div>
                <i class="ti ti-external-link fs-2 text-white text-opacity-50"></i>
            </div>
            <h5 class="text-white fw-bold mb-0">
                <a href="http://103.221.209.84:2018/workload/" target="_blank" class="text-white text-decoration-none stretched-link external-link">
                    Workload Allotment System
                </a>
            </h5>
        </div>
    </div>
</div>
