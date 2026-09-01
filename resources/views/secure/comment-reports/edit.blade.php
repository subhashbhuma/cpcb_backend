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

                    <form id="commentReportForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ $commentReport->title }}"
                                    required>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <span class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control"
                                    value="{{ $commentReport->title_hi }}" required>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Emails: <span class="text-danger">*</span></label>
                                <input type="text" name="emails" class="form-control" value="{{ $commentReport->emails }}"
                                    required>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Last Date: <span class="text-danger">*</span></label>
                                <input type="text" name="published_date" class="form-control"
                                    value="{{ $commentReport->published_date ? $commentReport->published_date->format('d-m-Y') : '' }}"
                                    required>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File Name (English):</label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf">
                                <small class="text-muted">Allowed types: pdf. Max: 5MB. Leave blank to keep
                                    current.</small>
                                @if ($commentReport->file_name)
                                    <div class="mt-2">
                                        <strong>Current File:</strong>
                                        <a href="{{ generate_file_view_path_for_backend($commentReport->file_url) }}"
                                            target="_blank">View Document</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File Name (Hindi):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf">
                                <small class="text-muted">Allowed types: pdf. Max: 5MB. Leave blank to keep
                                    current.</small>
                                @if ($commentReport->file_name_hi)
                                    <div class="mt-2">
                                        <strong>Current Hindi File:</strong>
                                        <a href="{{ generate_file_view_path_for_backend($commentReport->file_url_hi) }}"
                                            target="_blank">View Document</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-12 text-center mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update
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
            datePickerInit('published_date');
            $("#commentReportForm").validate({
                rules: {
                    title: {
                        required: true
                    },
                    title_hi: {
                        required: true
                    },
                    emails: {
                        required: true
                    },
                    published_date: {
                        required: true
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('comment-reports.update', $commentReport->id) }}",
                        method: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Updated!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    window.location.href = "{{ route('comment-reports.index') }}";
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
