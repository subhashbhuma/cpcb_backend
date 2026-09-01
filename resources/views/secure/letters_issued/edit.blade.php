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

                    <form id="lettersIssuedForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <select name="direction_state_id[]" class="form-control select2" multiple>
                                    @foreach ($states as $item)
                                        <option value="{{ $item->id }}" {{ in_array($item->id, $selectedStates) ? 'selected' : '' }}>{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-control">
                                    <option value="">-- Select Type --</option>
                                    @foreach ($types as $key => $value)
                                        <option value="{{ $key }}" {{ $record->type == $key ? 'selected' : '' }}>{{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Publish Date: <span class="text-danger">*</span></label>
                                <input type="date" name="publish_date" class="form-control"
                                    value="{{ $record->publish_date }}" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ $record->title }}"
                                    required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <span class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control" value="{{ $record->title_hi }}"
                                    required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (English):</label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf" />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB. Leave blank to keep
                                    current.</small>
                                @if($record->file_name)
                                    <div class="mt-1">
                                        <a href="{{ generate_file_view_path_for_backend($record->file_url) }}" target="_blank"
                                            class="text-info"><i class="fa fa-eye"></i> View Current File</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (Hindi):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf" />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB. Leave blank to keep
                                    current.</small>
                                @if($record->file_name_hi)
                                    <div class="mt-1">
                                        <a href="{{ generate_file_view_path_for_backend($record->file_url_hi) }}"
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
            function initSelect2() {
                if ($('.select2').length > 0) {
                    $('.select2').select2({
                        placeholder: "Select Option",
                        allowClear: true
                    });
                }
            }
            initSelect2();

            function handleTypeChange(isInitial = false) {
                let type = $('select[name="type"]').val();
                let stateSelect = $('select[name="direction_state_id[]"]');

                // Destroy Select2
                if (stateSelect.data('select2')) {
                    stateSelect.select2('destroy');
                }

                if (!isInitial) {
                    stateSelect.val(null);
                }

                if (type === 'OTHER') {
                    stateSelect.removeAttr('multiple');
                } else {
                    stateSelect.attr('multiple', 'multiple');
                }

                // Re-init Select2
                initSelect2();
            }

            // Initial Load Logic
            if ($('select[name="type"]').val() === 'OTHER') {
                handleTypeChange(true);
            }

            // Change Event
            $('select[name="type"]').on('change', function () {
                handleTypeChange(false);
            });

            $("#lettersIssuedForm").validate({
                rules: {
                    'direction_state_id[]': { required: true },
                    title: { required: true },
                    title_hi: { required: true },
                    publish_date: { required: true }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('letters-issued.update', $record->id) }}",
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
                                    window.location.href = "{{ route('letters-issued.index') }}";
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
        });
    </script>
@endsection