<!-- Summary Cards Module for Employee -->
<div class="col-md-4 col-xl-4">
    <div class="card bg-grd-success order-card overflow-hidden dashboard_content">
        <div class="card-body position-relative">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="text-white mb-0 dashboard_content_title">Circulars</h6>
                <i class="ti ti-refresh fs-1 text-white opacity-50"></i>
            </div>
            <h2 class="text-white mb-3">
                <a href="{{ route('circulars.employee-index', ['category' => 'circular']) }}"
                    class="text-white text-decoration-none stretched-link">{{ $stats['employee_circulars'] }}</a>
            </h2>
            <div class="d-flex gap-2">
                 <p class="text-white m-0 p-0" style="font-size: 0.85rem; opacity: 0.8;">Latest information in last 30 days</p>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4 col-xl-4">
    <div class="card bg-grd-primary order-card overflow-hidden dashboard_content">
        <div class="card-body position-relative">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="text-white mb-0 dashboard_content_title">Memorandum</h6>
                <i class="ti ti-file-text fs-1 text-white opacity-50"></i>
            </div>
            <h2 class="text-white mb-3">
                <a href="{{ route('circulars.employee-index', ['category' => 'memorandum']) }}"
                    class="text-white text-decoration-none stretched-link">{{ $stats['employee_memorandums'] }}</a>
            </h2>
            <div class="d-flex gap-2">
                 <p class="text-white m-0 p-0" style="font-size: 0.85rem; opacity: 0.8;">Latest information in last 30 days</p>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4 col-xl-4">
    <div class="card bg-grd-info order-card overflow-hidden dashboard_content">
        <div class="card-body position-relative">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="text-white mb-0 dashboard_content_title">Office Orders</h6>
                <i class="ti ti-direction fs-1 text-white opacity-50"></i>
            </div>
            <h2 class="text-white mb-3">
                <a href="{{ route('circulars.employee-index', ['category' => 'office_order']) }}"
                    class="text-white text-decoration-none stretched-link">{{ $stats['employee_office_orders'] }}</a>
            </h2>
            <div class="d-flex gap-2">
                 <p class="text-white m-0 p-0" style="font-size: 0.85rem; opacity: 0.8;">Latest information in last 30 days</p>
            </div>
        </div>
    </div>
</div>
