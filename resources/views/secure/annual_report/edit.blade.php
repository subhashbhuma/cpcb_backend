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

                    <form id="annualReportForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">



                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" required
                                    value="{{ $annual_report->title }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <span class="text-danger">*</span>
                                    <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control"
                                    value="{{ $annual_report->title_hi }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (English):</label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf" />
                                @if($annual_report->file_name)
                                    <div>
                                        Current File: <a
                                            href="{{ generate_file_view_path_for_backend($annual_report->file_url) }}"
                                            target='_BLANK'>View Document</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (Hindi):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf" />
                                @if($annual_report->file_name_hi)
                                    <div>
                                        Current File: <a
                                            href="{{ generate_file_view_path_for_backend($annual_report->file_url_hi) }}"
                                            target='_BLANK'>View Document</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Release Date: <span class="text-danger">*</span></label>
                                <input type="text" name="release_date" id="release_date" class="form-control"
                                    value="{{ $annual_report->release_date ? date('d-m-Y', strtotime($annual_report->release_date)) : '' }}">
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
            datePickerInit('release_date');
            $("#annualReportForm").validate({
                rules: {
                    title: {
                        required: true
                    },
                    title_hi: {
                        required: true
                    },
                    release_date: {
                        required: true
                    },
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('annual_report.update', $annual_report->id) }}",
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
                                    window.location.href = "{{ route('annual_report.index') }}";
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
