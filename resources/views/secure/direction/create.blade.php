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


                        <div class="row">

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Direction Act Type <span class="text-danger">*</span></label>
                                <select name="direction_act_type_id" id="direction_act_type_id"
                                    class="form-control select2">
                                    <option value="">Select Act Type</option>
                                    @foreach ($actTypes as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Direction Type</label>
                                <select name="direction_type_id" class="form-control select2">
                                    <option value="">Select Direction Type</option>
                                    @foreach ($types as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-12 mb-3" id="subject_col">
                                <label class="form-label">Subject</label>
                                <select name="direction_subject_id" class="form-control select2">
                                    <option value="">Select Subject</option>
                                    @foreach ($subjects as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <select name="direction_state_id[]" class="form-control select2" multiple>
                                    @foreach ($states as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="direction_category_id[]" class="form-control select2" multiple>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Issued To <span class="text-danger">*</span></label>
                                <select name="direction_issued_to_id[]" class="form-control select2" multiple>
                                    @foreach ($issuedTos as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label" id="title_label">Title (English): <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <div class="d-flex gap-3">
                                    <label class="form-label" id="title_hi_label">Title (Hindi): <span
                                            class="text-danger">*</span></label><x-translate-button source="title"
                                        target="title_hi" />
                                </div>
                                <input type="text" name="title_hi" id="title_hi" class="form-control" required />
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Publish Date: <span class="text-danger">*</span></label>
                                <input type="text" name="publish_date" class="form-control" required />
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">File (English): <span class="text-danger">*</span></label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf" required />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB</small>
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">File (Hindi):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf" />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB</small>
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
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
        $(document).ready(function() {
            datePickerInit('publish_date');
            // Initialize Select2 if not automatically initialized
            if ($('.select2').length > 0) {
                $('.select2').select2({
                    placeholder: "Select Option",
                    allowClear: true
                });
            }

            $("#directionForm").validate({
                rules: {
                    direction_act_type_id: {
                        required: true
                    },
                    'direction_state_id[]': {
                        required: true
                    },
                    'direction_category_id[]': {
                        required: true
                    },
                    'direction_issued_to_id[]': {
                        required: true
                    },
                    title: {
                        required: true
                    },
                    title_hi: {
                        required: true
                    },
                    publish_date: {
                        required: true
                    },
                    file_name: {
                        required: true
                    },
                },
                submitHandler: function(form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('direction.store') }}",
                        method: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('direction.index') }}";
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessages = Object.values(errors).flat().join(
                                    "<br>");
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
                } else if (actTypeId == '2') { // Section 5 EP Act
                    $('#subject_col').show();
                    $('#title_label').html(
                        'Name of Industry/Body/Person (English): <span class="text-danger">*</span>');
                    $('#title_hi_label').html(
                        'Name of Industry/Body/Person (Hindi): <span class="text-danger">*</span>');
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

            // Initial Load logic if pre-selected (e.g. back button or default)
            var initialActTypeId = $('#direction_act_type_id').val();
            if (initialActTypeId) {
                toggleFields(initialActTypeId);
            }

            $('#direction_act_type_id').on('change', function() {
                var actTypeId = $(this).val();
                toggleFields(actTypeId);

                // UI Logic based on Act Type
                if (actTypeId == '1') { // Section 18(1)(b)
                    $('#subject_col').hide();
                    $('#title_label').html('Subject (English): <span class="text-danger">*</span>');
                    $('#title_hi_label').html('Subject (Hindi): <span class="text-danger">*</span>');
                } else if (actTypeId == '2') { // Section 5 EP Act
                    $('#subject_col').show();
                    $('#title_label').html(
                        'Name of Industry/Body/Person (English): <span class="text-danger">*</span>');
                    $('#title_hi_label').html(
                        'Name of Industry/Body/Person (Hindi): <span class="text-danger">*</span>');
                } else {
                    $('#subject_col').show();
                    $('#title_label').html('Title (English): <span class="text-danger">*</span>');
                    $('#title_hi_label').html('Title (Hindi): <span class="text-danger">*</span>');
                }

                if (actTypeId) {
                    $.ajax({
                        url: "{{ route('direction.fetch-dependencies') }}",
                        type: "POST",
                        data: {
                            act_type_id: actTypeId,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        beforeSend: function() {
                            // Show loader/Disable fields
                            $('select[name="direction_type_id"]').prop('disabled', true).html(
                                '<option>Loading...</option>');
                            $('select[name="direction_subject_id"]').prop('disabled', true)
                                .html('<option>Loading...</option>');
                            $('select[name^="direction_category_id"]').prop('disabled', true)
                                .html('<option>Loading...</option>');
                            $('select[name="direction_issued_to_id[]"]').prop('disabled', true)
                                .html('<option>Loading...</option>');
                        },
                        success: function(data) {
                            if (data.success) {
                                // Direction Type
                                var typeSelect = $('select[name="direction_type_id"]');
                                typeSelect.prop('disabled', false).empty().append(
                                    '<option value="">Select Direction Type</option>');
                                $.each(data.types, function(key, value) {
                                    typeSelect.append('<option value="' + value.id +
                                        '">' + value.title + '</option>');
                                });
                                typeSelect.trigger('change'); // Notify Select2

                                // Subject
                                var subjectSelect = $('select[name="direction_subject_id"]');
                                subjectSelect.prop('disabled', false).empty().append(
                                    '<option value="">Select Subject</option>');
                                $.each(data.subjects, function(key, value) {
                                    subjectSelect.append('<option value="' + value.id +
                                        '">' + value.title + '</option>');
                                });
                                subjectSelect.trigger('change');

                                // Category
                                var categorySelect = $('select[name^="direction_category_id"]');
                                categorySelect.prop('disabled', false).empty();
                                $.each(data.categories, function(key, value) {
                                    categorySelect.append('<option value="' + value.id +
                                        '">' + value.title + '</option>');
                                });
                                categorySelect.trigger('change');

                                // Issued To
                                var issuedToSelect = $(
                                    'select[name="direction_issued_to_id[]"]');
                                issuedToSelect.prop('disabled', false).empty();
                                $.each(data.issued_tos, function(key, value) {
                                    issuedToSelect.append('<option value="' + value.id +
                                        '">' + value.title + '</option>');
                                });
                                issuedToSelect.trigger('change');
                            }
                        },
                        error: function() {
                            console.error('Failed to fetch dependencies');
                            // Reset on error
                            $('select[name="direction_type_id"]').prop('disabled', false).html(
                                '<option value="">Select Direction Type</option>');
                            $('select[name="direction_subject_id"]').prop('disabled', false)
                                .html('<option value="">Select Subject</option>');
                            $('select[name^="direction_category_id"]').prop('disabled', false)
                                .empty();
                            $('select[name="direction_issued_to_id[]"]').prop('disabled', false)
                                .empty();
                        }
                    });
                } else {
                    $('select[name="direction_type_id"]').empty().append(
                        '<option value="">Select Direction Type</option>').trigger('change');
                    $('select[name="direction_subject_id"]').empty().append(
                        '<option value="">Select Subject</option>').trigger('change');
                    $('select[name^="direction_category_id"]').empty().trigger('change');
                    $('select[name="direction_issued_to_id[]"]').empty().trigger('change');
                }
            });
        });
    </script>
@endsection
