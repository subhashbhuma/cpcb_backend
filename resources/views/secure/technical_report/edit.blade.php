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

                    <form id="technicalReportForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Subject Area: <span class="text-danger">*</span></label>
                                <select name="subject_area_id" id="subject_area_id" class="form-control" required>
                                    <option value="">-- Select Subject Area --</option>
                                    @foreach($subjectAreas as $subjectArea)
                                        <option value="{{ $subjectArea->id }}" {{ old('subject_area_id', $technicalReport->subject_area_id) == $subjectArea->id ? 'selected' : '' }}>
                                            {{ $subjectArea->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Division: <span class="text-danger">*</span></label>
                                <select name="division_id" id="division_id" class="form-control" required>
                                    <option value="">-- Select Division --</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ old('division_id', $technicalReport->division_id) == $division->id ? 'selected' : '' }}>
                                            {{ $division->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control"
                                    value="{{ old('title', $technicalReport->title) }}" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <x-translate-button source="title"
                                        target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control"
                                    value="{{ old('title_hi', $technicalReport->title_hi) }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Release Date: <span class="text-danger">*</span></label>
                                <input type="text" name="release_date" class="form-control"
                                    value="{{ old('release_date', $technicalReport->release_date?date('d-m-Y', strtotime($technicalReport->release_date)) : null) }}" required />
                            </div>



                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (English):</label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf" />
                                <small class="text-muted d-block mt-1">PDF only (Max 50MB)</small>
                                @if($technicalReport->file_name)
                                    <div class="mt-2">
                                        <strong>Current File:</strong>
                                        <a href="{{ generate_file_view_path_for_backend($technicalReport->file_url) }}"
                                            target="_blank">
                                            <i class="fa fa-file-pdf"></i> View English PDF
                                        </a>
                                    </div>
                                @endif
                                <small class="text-muted">Leave empty to keep current file</small>
                            </div>


                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (Hindi):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf" />
                                <small class="text-muted d-block mt-1">PDF only (Max 50MB)</small>
                                @if($technicalReport->file_name_hi)
                                    <div class="mt-2">
                                        <strong>Current File:</strong>
                                        <a href="{{ generate_file_view_path_for_backend($technicalReport->file_url_hi) }}"
                                            target="_blank">
                                            <i class="fa fa-file-pdf"></i> View Hindi PDF
                                        </a>
                                    </div>
                                @endif
                                <small class="text-muted">Leave empty to keep current file</small>
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save"></i> Update Technical Report
                                </button>
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
            datePickerInit('release_date');
            $("#technicalReportForm").validate({
                rules: {
                    subject_area_id: {
                        required: true
                    },
                    division_id: {
                        required: true
                    },
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    title_hi: {
                        maxlength: 255
                    },
                },
                messages: {
                    subject_area_id: "Please select a subject area",
                    division_id: "Please select a division",
                    title: "Please enter title in English",
                    release_date: "Please select a release date"
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    // Show loading state
                    let $submitBtn = $(form).find('button[type="submit"]');
                    let originalText = $submitBtn.html();
                    $submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

                    $.ajax({
                        url: "{{ route('technical_report.update', $technicalReport->id) }}",
                        method: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Updated!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href = response.redirect_url || "{{ route('technical_report.index') }}";
                                });
                            } else {
                                $submitBtn.prop('disabled', false).html(originalText);
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            $submitBtn.prop('disabled', false).html(originalText);

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
