@props([
    'personnels' => collect(),
    'profileActivities' => collect(),
    'states' => null,
])

<!-- Personnels View Table -->
<div class="col-12 mb-4">
    <div class="card border">
        <div class="card-header bg-light py-2">
            <h6 class="mb-0"><i class="fa fa-users text-primary me-1"></i> Personnels ({{ count($personnels) }})</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Name / Title (English)</th>
                            <th style="width: 25%;">Name / Title (Hindi)</th>
                            <th style="width: 20%;">Designation (English)</th>
                            <th style="width: 20%;">Designation (Hindi)</th>
                            <th style="width: 5%;">Order</th>
                            <th style="width: 5%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($personnels as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->title ?? '—' }}</td>
                                <td>{{ $item->title_hi ?? '—' }}</td>
                                <td>{{ $item->designation ?? '—' }}</td>
                                <td>{{ $item->designation_hi ?? '—' }}</td>
                                <td>{{ $item->order ?? 0 }}</td>
                                <td>
                                    @if (($item->record_status ?? 1) == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">No personnels available.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<!-- Profile & Activity View Table -->
<div class="col-12 mb-4">
    <div class="card border">
        <div class="card-header bg-light py-2">
            <h6 class="mb-0"><i class="fa fa-tasks text-info me-1"></i> Profile & Activities ({{ count($profileActivities) }})</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 40%;">Title (English)</th>
                            <th style="width: 40%;">Title (Hindi)</th>
                            <th style="width: 8%;">Order</th>
                            <th style="width: 7%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($profileActivities as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->title ?? '—' }}</td>
                                <td>{{ $item->title_hi ?? '—' }}</td>
                                <td>{{ $item->order ?? 0 }}</td>
                                <td>
                                    @if (($item->record_status ?? 1) == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No profile & activities available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if (!is_null($states))
<!-- Regional Directorate States View Table -->
<div class="col-12 mb-4">
    <div class="card border">
        <div class="card-header bg-light py-2">
            <h6 class="mb-0"><i class="fa fa-map-marker-alt text-warning me-1"></i> Regional Directorate States ({{ count($states) }})</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 40%;">State Name / Title (English)</th>
                            <th style="width: 40%;">State Name / Title (Hindi)</th>
                            <th style="width: 8%;">Order</th>
                            <th style="width: 7%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($states as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->title ?? '—' }}</td>
                                <td>{{ $item->title_hi ?? '—' }}</td>
                                <td>{{ $item->order ?? 0 }}</td>
                                <td>
                                    @if (($item->record_status ?? 1) == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No states available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
