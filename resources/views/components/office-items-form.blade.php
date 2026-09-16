@props([
    'personnels' => collect(),
    'profileActivities' => collect(),
    'states' => null,
])

<!-- Personnels Section -->
<div class="col-12 mb-4">
    <div class="card border">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
            <h6 class="mb-0"><i class="fa fa-users text-primary me-1"></i> Personnels</h6>
            <button type="button" class="btn btn-sm btn-success" id="addPersonnelRowBtn">
                <i class="fa fa-plus"></i> Add Personnel
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="personnelsTable">
                    <tbody id="personnelsTableBody">
                        @forelse ($personnels as $index => $item)
                            <tr class="personnel-row" data-index="{{ $index }}">
                                <td style="min-width: 190px;">
                                    <label class="form-label small mb-1"><strong>Name / Title (English)</strong> <span class="text-danger">*</span></label>
                                    <input type="hidden" name="personnels[{{ $index }}][id]" value="{{ $item->id }}">
                                    <input type="text" name="personnels[{{ $index }}][title]"
                                        class="form-control item-title-en" value="{{ $item->title }}"
                                        placeholder="Enter Name / Title" required>
                                </td>
                                <td style="min-width: 190px;">
                                    <label class="form-label small mb-1">
                                        <strong>Name / Title (Hindi)</strong> <x-translate-button source="title" target="title_hi" />
                                    </label>
                                    <input type="text" name="personnels[{{ $index }}][title_hi]"
                                        class="form-control item-title-hi" value="{{ $item->title_hi }}"
                                        placeholder="नाम / शीर्षक दर्ज करें">
                                </td>
                                <td style="min-width: 170px;">
                                    <label class="form-label small mb-1"><strong>Designation (English)</strong></label>
                                    <input type="text" name="personnels[{{ $index }}][designation]"
                                        class="form-control item-designation-en" value="{{ $item->designation }}"
                                        placeholder="Enter Designation">
                                </td>
                                <td style="min-width: 170px;">
                                    <label class="form-label small mb-1">
                                        <strong>Designation (Hindi)</strong> <x-translate-button source="designation" target="designation_hi" />
                                    </label>
                                    <input type="text" name="personnels[{{ $index }}][designation_hi]"
                                        class="form-control item-designation-hi" value="{{ $item->designation_hi }}"
                                        placeholder="पदनाम दर्ज करें">
                                </td>
                                <td style="width: 90px; min-width: 80px;">
                                    <label class="form-label small mb-1"><strong>Order</strong></label>
                                    <input type="number" name="personnels[{{ $index }}][order]"
                                        class="form-control px-2" value="{{ $item->order ?? 0 }}" min="0">
                                </td>
                                <td style="width: 120px; min-width: 110px;">
                                    <label class="form-label small mb-1"><strong>Status</strong></label>
                                    <select name="personnels[{{ $index }}][record_status]" class="form-select px-2">
                                        <option value="1" {{ ($item->record_status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ ($item->record_status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </td>
                                <td style="width: 50px; min-width: 50px;" class="text-center align-middle">
                                    <label class="form-label small mb-1 d-block">&nbsp;</label>
                                    <button type="button" class="btn btn-sm btn-danger remove-item-row-btn"
                                        title="Remove Row">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div id="noPersonnelsMsg" class="p-3 text-center text-muted {{ count($personnels) > 0 ? 'd-none' : '' }}">
                <small>No personnels added yet. Click <strong>"Add Personnel"</strong> to add records.</small>
            </div>
        </div>
    </div>
</div>

<!-- Profile & Activity Section -->
<div class="col-12 mb-4">
    <div class="card border">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
            <h6 class="mb-0"><i class="fa fa-tasks text-info me-1"></i> Profile & Activities</h6>
            <button type="button" class="btn btn-sm btn-success" id="addActivityRowBtn">
                <i class="fa fa-plus"></i> Add Profile & Activity
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="activitiesTable">
                    <tbody id="activitiesTableBody">
                        @forelse ($profileActivities as $index => $item)
                            <tr class="activity-row" data-index="{{ $index }}">
                                <td style="min-width: 250px;">
                                    <label class="form-label small mb-1"><strong>Title (English)</strong> <span class="text-danger">*</span></label>
                                    <input type="hidden" name="profile_activities[{{ $index }}][id]" value="{{ $item->id }}">
                                    <input type="text" name="profile_activities[{{ $index }}][title]"
                                        class="form-control item-title-en" value="{{ $item->title }}"
                                        placeholder="Enter Activity Title" required>
                                </td>
                                <td style="min-width: 250px;">
                                    <label class="form-label small mb-1">
                                        <strong>Title (Hindi)</strong> <x-translate-button source="title" target="title_hi" />
                                    </label>
                                    <input type="text" name="profile_activities[{{ $index }}][title_hi]"
                                        class="form-control item-title-hi" value="{{ $item->title_hi }}"
                                        placeholder="गतिविधि शीर्षक दर्ज करें">
                                </td>

                                <td style="width: 90px; min-width: 80px;">
                                    <label class="form-label small mb-1"><strong>Order</strong></label>
                                    <input type="number" name="profile_activities[{{ $index }}][order]"
                                        class="form-control px-2" value="{{ $item->order ?? 0 }}" min="0">
                                </td>
                                <td style="width: 120px; min-width: 110px;">
                                    <label class="form-label small mb-1"><strong>Status</strong></label>
                                    <select name="profile_activities[{{ $index }}][record_status]" class="form-select px-2">
                                        <option value="1" {{ ($item->record_status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ ($item->record_status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </td>
                                <td style="width: 50px; min-width: 50px;" class="text-center align-middle">
                                    <label class="form-label small mb-1 d-block">&nbsp;</label>
                                    <button type="button" class="btn btn-sm btn-danger remove-item-row-btn"
                                        title="Remove Row">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div id="noActivitiesMsg" class="p-3 text-center text-muted {{ count($profileActivities) > 0 ? 'd-none' : '' }}">
                <small>No profile & activities added yet. Click <strong>"Add Profile & Activity"</strong> to add records.</small>
            </div>
        </div>
    </div>
</div>

@if (!is_null($states))
<!-- Regional Directorate States Section -->
<div class="col-12 mb-4">
    <div class="card border">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
            <h6 class="mb-0"><i class="fa fa-map-marker-alt text-warning me-1"></i> Regional Directorate States</h6>
            <button type="button" class="btn btn-sm btn-success" id="addStateRowBtn">
                <i class="fa fa-plus"></i> Add State
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="statesTable">
                    <tbody id="statesTableBody">
                        @forelse ($states as $index => $item)
                            <tr class="state-row" data-index="{{ $index }}">
                                <td style="min-width: 250px;">
                                    <label class="form-label small mb-1"><strong>State Name / Title (English)</strong> <span class="text-danger">*</span></label>
                                    <input type="hidden" name="states[{{ $index }}][id]" value="{{ $item->id }}">
                                    <input type="text" name="states[{{ $index }}][title]"
                                        class="form-control item-title-en" value="{{ $item->title }}"
                                        placeholder="Enter State Name" required>
                                </td>
                                <td style="min-width: 250px;">
                                    <label class="form-label small mb-1">
                                        <strong>State Name / Title (Hindi)</strong> <x-translate-button source="title" target="title_hi" />
                                    </label>
                                    <input type="text" name="states[{{ $index }}][title_hi]"
                                        class="form-control item-title-hi" value="{{ $item->title_hi }}"
                                        placeholder="राज्य का नाम दर्ज करें">
                                </td>

                                <td style="width: 90px; min-width: 80px;">
                                    <label class="form-label small mb-1"><strong>Order</strong></label>
                                    <input type="number" name="states[{{ $index }}][order]"
                                        class="form-control px-2" value="{{ $item->order ?? 0 }}" min="0">
                                </td>
                                <td style="width: 120px; min-width: 110px;">
                                    <label class="form-label small mb-1"><strong>Status</strong></label>
                                    <select name="states[{{ $index }}][record_status]" class="form-select px-2">
                                        <option value="1" {{ ($item->record_status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ ($item->record_status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </td>
                                <td style="width: 50px; min-width: 50px;" class="text-center align-middle">
                                    <label class="form-label small mb-1 d-block">&nbsp;</label>
                                    <button type="button" class="btn btn-sm btn-danger remove-item-row-btn"
                                        title="Remove Row">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div id="noStatesMsg" class="p-3 text-center text-muted {{ count($states) > 0 ? 'd-none' : '' }}">
                <small>No states added yet. Click <strong>"Add State"</strong> to add records.</small>
            </div>
        </div>
    </div>
</div>
@endif
