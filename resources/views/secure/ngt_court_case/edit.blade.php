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

                    <form id="recordForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ $data->title }}" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <span class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control" value="{{ $data->title_hi }}"
                                    required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">QA Number (English):</label>
                                <input type="text" name="qa_number" id="qa_number" class="form-control" value="{{ $data->qa_number }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">QA Number (Hindi): <x-translate-button source="qa_number" target="qa_number_hi" /></label>
                                <input type="text" name="qa_number_hi" id="qa_number_hi" class="form-control"
                                    value="{{ $data->qa_number_hi }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Type (English):</label>
                                <textarea name="type" id="type" class="form-control" rows="2">{{ $data->type }}</textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Type (Hindi): <x-translate-button source="type" target="type_hi" /></label>
                                <textarea name="type_hi" id="type_hi" class="form-control" rows="2">{{ $data->type_hi }}</textarea>
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Publish Date: <span class="text-danger">*</span></label>
                                <input type="text" name="publish_date" class="form-control"
                                    value="{{ $data->publish_date?date('d-m-Y', strtotime($data->publish_date)):null }}" required />
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">File (English):</label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf" />
                                @if($data->file_name)
                                    <div>
                                        Current File: <a
                                            href="{{ generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['NGT_COURT_CASE_FILE_EN_PATH'] . '/' . $data->file_name)) }}"
                                            target='_BLANK'>View Document</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">File (Hindi):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf" />
                                @if($data->file_name_hi)
                                    <div>
                                        Current File: <a
                                            href="{{ generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['NGT_COURT_CASE_FILE_HI_PATH'] . '/' . $data->file_name_hi)) }}"
                                            target='_BLANK'>View Document</a>
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
            $("#recordForm").validate({
                rules: {
                    title: {
                        required: true
                    },
                    title_hi: {
                        required: true
                    },
                    publish_date: {
                        required: true
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('ngt-court-cases.update', $data->id) }}",
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
                                    window.location.href = "{{ route('ngt-court-cases.index') }}";
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
