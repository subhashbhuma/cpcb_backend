<script @cspNonce>
    $(document).ready(function () {
        let personnelIndex = $('#personnelsTableBody tr').length;
        let activityIndex = $('#activitiesTableBody tr').length;
        let stateIndex = $('#statesTableBody tr').length;

        // Add Personnel Row
        $('#addPersonnelRowBtn').on('click', function () {
            let rowHtml = `
                <tr class="personnel-row" data-index="${personnelIndex}">
                    <td style="min-width: 190px;">
                        <label class="form-label small mb-1"><strong>Name / Title (English)</strong> <span class="text-danger">*</span></label>
                        <input type="text" name="personnels[${personnelIndex}][title]"
                            class="form-control item-title-en" placeholder="Enter Name / Title" required>
                    </td>
                    <td style="min-width: 190px;">
                        <label class="form-label small mb-1">
                            <strong>Name / Title (Hindi)</strong>
                            <button type="button" class="btn btn-link p-0 text-primary text-underline translate-btn"
                                data-source="title" data-target="title_hi">Translate</button>
                        </label>
                        <input type="text" name="personnels[${personnelIndex}][title_hi]"
                            class="form-control item-title-hi" placeholder="नाम / शीर्षक दर्ज करें">
                    </td>
                    <td style="min-width: 170px;">
                        <label class="form-label small mb-1"><strong>Designation (English)</strong></label>
                        <input type="text" name="personnels[${personnelIndex}][designation]"
                            class="form-control item-designation-en" placeholder="Enter Designation">
                    </td>
                    <td style="min-width: 170px;">
                        <label class="form-label small mb-1">
                            <strong>Designation (Hindi)</strong>
                            <button type="button" class="btn btn-link p-0 text-primary text-underline translate-btn"
                                data-source="designation" data-target="designation_hi">Translate</button>
                        </label>
                        <input type="text" name="personnels[${personnelIndex}][designation_hi]"
                            class="form-control item-designation-hi" placeholder="पदनाम दर्ज करें">
                    </td>
                    <td style="width: 90px; min-width: 80px;">
                        <label class="form-label small mb-1"><strong>Order</strong></label>
                        <input type="number" name="personnels[${personnelIndex}][order]"
                            class="form-control px-2" value="${personnelIndex}" min="0">
                    </td>
                    <td style="width: 120px; min-width: 110px;">
                        <label class="form-label small mb-1"><strong>Status</strong></label>
                        <select name="personnels[${personnelIndex}][record_status]" class="form-select px-2">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
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
            `;
            $('#personnelsTableBody').append(rowHtml);
            $('#noPersonnelsMsg').addClass('d-none');
            personnelIndex++;
        });

        // Add Activity Row
        $('#addActivityRowBtn').on('click', function () {
            let rowHtml = `
                <tr class="activity-row" data-index="${activityIndex}">
                    <td style="min-width: 250px;">
                        <label class="form-label small mb-1"><strong>Title (English)</strong> <span class="text-danger">*</span></label>
                        <input type="text" name="profile_activities[${activityIndex}][title]"
                            class="form-control item-title-en" placeholder="Enter Activity Title" required>
                    </td>
                    <td style="min-width: 250px;">
                        <label class="form-label small mb-1">
                            <strong>Title (Hindi)</strong>
                            <button type="button" class="btn btn-link p-0 text-primary text-underline translate-btn"
                                data-source="title" data-target="title_hi">Translate</button>
                        </label>
                        <input type="text" name="profile_activities[${activityIndex}][title_hi]"
                            class="form-control item-title-hi" placeholder="गतिविधि शीर्षक दर्ज करें">
                    </td>
                    <td style="width: 90px; min-width: 80px;">
                        <label class="form-label small mb-1"><strong>Order</strong></label>
                        <input type="number" name="profile_activities[${activityIndex}][order]"
                            class="form-control px-2" value="${activityIndex}" min="0">
                    </td>
                    <td style="width: 120px; min-width: 110px;">
                        <label class="form-label small mb-1"><strong>Status</strong></label>
                        <select name="profile_activities[${activityIndex}][record_status]" class="form-select px-2">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
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
            `;
            $('#activitiesTableBody').append(rowHtml);
            $('#noActivitiesMsg').addClass('d-none');
            activityIndex++;
        });

        // Add State Row
        $('#addStateRowBtn').on('click', function () {
            let rowHtml = `
                <tr class="state-row" data-index="${stateIndex}">
                    <td style="min-width: 250px;">
                        <label class="form-label small mb-1"><strong>State Name / Title (English)</strong> <span class="text-danger">*</span></label>
                        <input type="text" name="states[${stateIndex}][title]"
                            class="form-control item-title-en" placeholder="Enter State Name" required>
                    </td>
                    <td style="min-width: 250px;">
                        <label class="form-label small mb-1">
                            <strong>State Name / Title (Hindi)</strong>
                            <button type="button" class="btn btn-link p-0 text-primary text-underline translate-btn"
                                data-source="title" data-target="title_hi">Translate</button>
                        </label>
                        <input type="text" name="states[${stateIndex}][title_hi]"
                            class="form-control item-title-hi" placeholder="राज्य का नाम दर्ज करें">
                    </td>
                    <td style="width: 90px; min-width: 80px;">
                        <label class="form-label small mb-1"><strong>Order</strong></label>
                        <input type="number" name="states[${stateIndex}][order]"
                            class="form-control px-2" value="${stateIndex}" min="0">
                    </td>
                    <td style="width: 120px; min-width: 110px;">
                        <label class="form-label small mb-1"><strong>Status</strong></label>
                        <select name="states[${stateIndex}][record_status]" class="form-select px-2">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
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
            `;
            $('#statesTableBody').append(rowHtml);
            $('#noStatesMsg').addClass('d-none');
            stateIndex++;
        });

        // Remove Row
        $(document).on('click', '.remove-item-row-btn', function () {
            let row = $(this).closest('tr');
            let tbody = row.closest('tbody');
            let tableId = tbody.attr('id');
            row.remove();

            if (tableId === 'personnelsTableBody') {
                if ($('#personnelsTableBody tr').length === 0) {
                    $('#noPersonnelsMsg').removeClass('d-none');
                }
            } else if (tableId === 'activitiesTableBody') {
                if ($('#activitiesTableBody tr').length === 0) {
                    $('#noActivitiesMsg').removeClass('d-none');
                }
            } else if (tableId === 'statesTableBody') {
                if ($('#statesTableBody tr').length === 0) {
                    $('#noStatesMsg').removeClass('d-none');
                }
            }
        });
    });
</script>
