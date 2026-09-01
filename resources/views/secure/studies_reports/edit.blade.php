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

                    <form id="studiesReportEditForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Report Year <span class="text-danger">*</span></label>
                                <input type="number" name="report_year" class="form-control"
                                    value="{{ $studiesReport->report_year }}" placeholder="YYYY" min="1950" max="2100"
                                    required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English) <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ $studiesReport->title }}"
                                    required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi) <span class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control"
                                    value="{{ $studiesReport->title_hi }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Division <span class="text-danger">*</span></label>
                                <select name="division_id" class="form-control" required>
                                    <option value="">-- Select Division --</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}" {{ $studiesReport->division_id == $division->id ? 'selected' : '' }}>
                                            {{ $division->title }} ({{ $division->title_hi }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (English)</label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf,.doc,.docx" />
                                <small class="text-muted">Allowed types: pdf, doc, docx. Leave empty to keep
                                    existing.</small>
                                @if($studiesReport->file_name)
                                    <br><a href="{{ generate_file_view_path_for_backend($studiesReport->file_url) }}"
                                        target="_blank">View Current File</a>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (Hindi)</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf,.doc,.docx" />
                                <small class="text-muted">Allowed types: pdf, doc, docx. Leave empty to keep
                                    existing.</small>
                                @if($studiesReport->file_name_hi)
                                    <br><a href="{{ generate_file_view_path_for_backend($studiesReport->file_url_hi) }}"
                                        target="_blank">View Current File</a>
                                @endif
                            </div>

                            <div class="col-12 text-center mt-3">
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
            $("#studiesReportEditForm").validate({
                rules: {
                    title: { required: true },
                    title_hi: { required: true },
                    division_id: { required: true },
                    report_year: { required: true, number: true, minlength: 4, maxlength: 4 },
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('studies_reports.update', $studiesReport->id) }}",
                        method: "POST", // POST with _method PUT handled by FormData automatically if I append it? No, Laravel pseudo-method.
                        // Wait, form has @method('PUT') which adds <input type="hidden" name="_method" value="PUT">
                        // So method POST is correct for AJAX with FormData.
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function () { showLoader(); },
                        success: function (response) {
                            hideLoader();
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href = "{{ route('studies_reports.index') }}";
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            hideLoader();
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessages = Object.values(errors).flat().join("<br>");
                                Swal.fire({ title: "Validation Error", html: errorMessages, icon: "error" });
                            } else {
                                Swal.fire({ title: "Error!", text: "Something went wrong.", icon: "error" });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection