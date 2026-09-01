@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>All (<span class="text-danger">*</span>) marked fields are required.</p>

                    <form id="directionForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Direction Act Type <span class="text-danger">*</span></label>
                                <select name="direction_act_type_id" id="direction_act_type_id"
                                    class="form-control select2">
                                    <option value="">Select Act Type</option>
                                    @foreach ($actTypes as $item)
                                        <option value="{{ $item->id }}" {{ $direction->direction_act_type_id == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Direction Type</label>
                                <select name="direction_type_id" class="form-control select2">
                                    <option value="">Select Direction Type</option>
                                    @foreach ($types as $item)
                                        <option value="{{ $item->id }}" {{ $direction->direction_type_id == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-12 mb-3" id="subject_col">
                                <label class="form-label">Subject</label>
                                <select name="direction_subject_id" class="form-control select2">
                                    <option value="">Select Subject</option>
                                    @foreach ($subjects as $item)
                                        <option value="{{ $item->id }}" {{ $direction->direction_subject_id == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <select name="direction_state_id[]" class="form-control select2" multiple>
                                    @foreach ($states as $item)
                                        <option value="{{ $item->id }}" {{ in_array($item->id, $selectedStates) ? 'selected' : '' }}>{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="direction_category_id[]" class="form-control select2" multiple>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}" {{ in_array($item->id, $selectedCategories) ? 'selected' : '' }}>{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Issued To <span class="text-danger">*</span></label>
                                <select name="direction_issued_to_id[]" class="form-control select2" multiple>
                                    @foreach ($issuedTos as $item)
                                        <option value="{{ $item->id }}" {{ in_array($item->id, $selectedIssuedTos) ? 'selected' : '' }}>{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label" id="title_label">Title (English): <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control"
                                    value="{{ $direction->title }}" required />
                            </div>


                            <div class="col-md-6 col-12 mb-3">
                                <div class="d-flex gap-3">
                                <label class="form-label" id="title_hi_label">Title (Hindi): <span
                                        class="text-danger">*</span>
                                </label>
                                <x-translate-button source="title"
                                        target="title_hi" />
                                </div>
                                <input type="text" name="title_hi" id="title_hi" class="form-control"
                                    value="{{ $direction->title_hi }}" required />
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Publish Date: <span class="text-danger">*</span></label>
                                <input type="text" name="publish_date" class="form-control"
                                    value="{{ $direction->publish_date?date('d-m-Y', strtotime($direction->publish_date)):null }}" required />
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">File (English):</label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf" />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB. Leave blank to keep
                                    current.</small>
                                @if($direction->file_name)
                                    <div class="mt-1">
                                        <a href="{{ generate_file_view_path_for_backend($direction->file_url) }}"
                                            target="_blank" class="text-info"><i class="fa fa-eye"></i> View Current File</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">File (Hindi):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf" />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB. Leave blank to keep
                                    current.</small>
                                @if($direction->file_name_hi)
                                    <div class="mt-1">
                                        <a href="{{ generate_file_view_path_for_backend($direction->file_url_hi) }}"
                                            target="_blank" class="text-info"><i class="fa fa-eye"></i> View Current File</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {
            datePickerInit('publish_date');
            // Inject PHP variables
            const initialActTypeId = "{{ $direction->direction_act_type_id }}";
            const initialTypeId = "{{ $direction->direction_type_id }}";
            const initialSubjectId = "{{ $direction->direction_subject_id }}";
            const initialStates = @json($selectedStates);
            const initialCategories = @json($selectedCategories);
            const initialIssuedTos = @json($selectedIssuedTos);

            if ($('.select2').length > 0) {
                $('.select2').select2({
                    placeholder: "Select Option",
                    allowClear: true
                });
            }

            $("#directionForm").validate({
                rules: {
                    direction_act_type_id: { required: true },
                    'direction_state_id[]': { required: true },
                    // Category rule handled dynamically or generic "required" check if name changes?
                    // jQuery Validate usually tracks by name. We might need to adjust rules if name changes,
                    // or validaton works if we target the element.
                    // Simplest is to rely on 'required' attribute if present, or add rules dynamically.
                    // For now, let's keep array rule, and we might need to add one for non-array if it fails.
                    // Actually, if we change name to direction_category_id, the 'direction_category_id[]' rule won't apply.
                    // We can add a class rule or just basic required attribute.
                    'direction_issued_to_id[]': { required: true },
                    title: { required: true },
                    title_hi: { required: true },
                    publish_date: { required: true }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('direction.update', $direction->id) }}",
                        method: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href = "{{ route('direction.index') }}";
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessages = Object.values(errors).flat().join("<br>");
                                Swal.fire({
                                    title: "Validation Error",
                                    html: errorMessages,
                                    icon: "error"
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Something went wrong. Please try again.",
                                    icon: "error"
                                });
                            }
                        }
                    });
                }
            });

            // Dynamic Dependent Dropdown Loading
            function toggleFields(actTypeId) {
                // UI Logic based on Act Type
                if (actTypeId == '1') { // Section 18(1)(b)
                    $('#subject_col').hide();
                    $('#title_label').html('Subject (English): <span class="text-danger">*</span>');
                    $('#title_hi_label').html('Subject (Hindi): <span class="text-danger">*</span>');

                    // Reset Category to Multi if coming from Act Type 2
                    var catSelect = $('#category_select'); // We need an ID for the select
                    // However, the ID isn't set in blade yet, let's use the name selector carefully or add ID.
                    // The blade above used class select2. Let's assume we can target it.
                    // Best to add ID to the select inputs in blade first?
                    // Actually, I can replace the whole script section, but reliance on IDs added in previous steps matches "edit.blade.php".
                    // Let's assume the selector `select[name^="direction_category_id"]` works.

                } else if (actTypeId == '2') { // Section 5 EP Act
                    $('#subject_col').show();
                    $('#title_label').html('Name of Industry/Body/Person (English): <span class="text-danger">*</span>');
                    $('#title_hi_label').html('Name of Industry/Body/Person (Hindi): <span class="text-danger">*</span>');
                } else {
                    $('#subject_col').show();
                    $('#title_label').html('Title (English): <span class="text-danger">*</span>');
                    $('#title_hi_label').html('Title (Hindi): <span class="text-danger">*</span>');
                }

                // Handle Category Single/Multi Select
                var $categorySelect = $('select[name^="direction_category_id"]');
                // Destroy select2 to modify underlying element
                if ($categorySelect.hasClass("select2-hidden-accessible")) {
                    $categorySelect.select2('destroy');
                }

                if (actTypeId == '2') {
                    // Single Select
                    $categorySelect.removeAttr('multiple');
                    $categorySelect.attr('name', 'direction_category_id');
                } else {
                    // Multi Select
                    $categorySelect.attr('multiple', 'multiple');
                    $categorySelect.attr('name', 'direction_category_id[]');
                }
                // Re-init select2
                $categorySelect.select2({
                    placeholder: "Select Option",
                    allowClear: true
                });
            }

            $('#direction_act_type_id').on('change', function () {
                var actTypeId = $(this).val();
                toggleFields(actTypeId); // Update UI

                if (actTypeId) {
                    $.ajax({
                        url: "{{ route('direction.fetch-dependencies') }}",
                        type: "POST",
                        data: {
                            act_type_id: actTypeId,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        beforeSend: function () {
                            // Show loader/Disable fields
                            $('select[name="direction_type_id"]').prop('disabled', true).html('<option>Loading...</option>');
                            $('select[name="direction_subject_id"]').prop('disabled', true).html('<option>Loading...</option>');
                            $('select[name^="direction_category_id"]').prop('disabled', true).html('<option>Loading...</option>');
                            $('select[name="direction_issued_to_id[]"]').prop('disabled', true).html('<option>Loading...</option>');
                        },
                        success: function (data) {
                            if (data.success) {
                                // Direction Type
                                var typeSelect = $('select[name="direction_type_id"]');
                                typeSelect.prop('disabled', false).empty().append('<option value="">Select Direction Type</option>');
                                $.each(data.types, function (key, value) {
                                    // Restore selection if Act Type matches initial
                                    let isSelected = (actTypeId == initialActTypeId && value.id == initialTypeId) ? 'selected' : '';
                                    typeSelect.append('<option value="' + value.id + '" ' + isSelected + '>' + value.title + '</option>');
                                });
                                typeSelect.trigger('change');

                                // Subject
                                var subjectSelect = $('select[name="direction_subject_id"]');
                                subjectSelect.prop('disabled', false).empty().append('<option value="">Select Subject</option>');
                                $.each(data.subjects, function (key, value) {
                                    let isSelected = (actTypeId == initialActTypeId && value.id == initialSubjectId) ? 'selected' : '';
                                    subjectSelect.append('<option value="' + value.id + '" ' + isSelected + '>' + value.title + '</option>');
                                });
                                subjectSelect.trigger('change');

                                // Category
                                var categorySelect = $('select[name^="direction_category_id"]');
                                categorySelect.prop('disabled', false).empty();

                                $.each(data.categories, function (key, value) {
                                    let isSelected = '';
                                    if (actTypeId == initialActTypeId) {
                                        // If Act Type 2 (Single), initialCategories might be array of 1 or string?
                                        // PHP helper `in_array` was used in blade. JS `includes` works for array.
                                        // If Single select saved, initialCategories from PHP is still array from explode.
                                        // So logic is consistent: check if ID is in initialAttributes.
                                        if (initialCategories.includes(String(value.id)) || initialCategories.includes(value.id)) {
                                            isSelected = 'selected';
                                        }
                                    }
                                    categorySelect.append('<option value="' + value.id + '" ' + isSelected + '>' + value.title + '</option>');
                                });
                                categorySelect.trigger('change');

                                // Issued To
                                var issuedToSelect = $('select[name="direction_issued_to_id[]"]');
                                issuedToSelect.prop('disabled', false).empty();
                                $.each(data.issued_tos, function (key, value) {
                                    let isSelected = '';
                                    if (actTypeId == initialActTypeId) {
                                        if (initialIssuedTos.includes(String(value.id)) || initialIssuedTos.includes(value.id)) {
                                            isSelected = 'selected';
                                        }
                                    }
                                    issuedToSelect.append('<option value="' + value.id + '" ' + isSelected + '>' + value.title + '</option>');
                                });
                                issuedToSelect.trigger('change');
                            }
                        },
                        error: function () {
                            console.error('Failed to fetch dependencies');
                            // Reset
                            $('select[name="direction_type_id"]').prop('disabled', false).html('<option value="">Select Direction Type</option>');
                            $('select[name="direction_subject_id"]').prop('disabled', false).html('<option value="">Select Subject</option>');
                            $('select[name^="direction_category_id"]').prop('disabled', false).empty();
                            $('select[name="direction_issued_to_id[]"]').prop('disabled', false).empty();
                        }
                    });
                } else {
                    $('select[name="direction_type_id"]').empty().append('<option value="">Select Direction Type</option>').trigger('change');
                    $('select[name="direction_subject_id"]').empty().append('<option value="">Select Subject</option>').trigger('change');
                    $('select[name^="direction_category_id"]').empty().trigger('change');
                    $('select[name="direction_issued_to_id[]"]').empty().trigger('change');
                }
            });

            // Initial Load Logic: Trigger change to filter lists and apply UI logic
            if ($('#direction_act_type_id').val()) {
                $('#direction_act_type_id').trigger('change');
            }
        });
    </script>
@endsection
