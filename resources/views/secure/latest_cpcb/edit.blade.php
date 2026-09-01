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

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ $data->title }}" required />
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <x-translate-button source="title"
                                        target="title_hi" /></label>
                                <input type="text" name="title_hi" class="form-control" value="{{ $data->title_hi }}" />
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Division: </label>
                                <select name="division_id" class="form-control">
                                    <option value="">-- Select Division --</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}" {{ $data->division_id == $division->id ? 'selected' : '' }}>
                                            {{ $division->title }} ({{ $division->title_hi }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Publish Date: <span class="text-danger">*</span></label>
                                <input type="text" name="publish_date" class="form-control"
                                    value="{{ $data->publish_date?date('d-m-Y', strtotime($data->publish_date)):null }}" />
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">File (English):</label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf" />
                                @if($data->file_name)
                                    <div>
                                        Current File: <a href="{{ generate_file_view_path_for_backend($data->file_url) }}"
                                            target='_BLANK'>View Document</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">File (Hindi):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf" />
                                @if($data->file_name_hi)
                                    <div>
                                        Current File: <a href="{{ generate_file_view_path_for_backend($data->file_url_hi) }}"
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
                    publish_date: {
                        required: true
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('latest-cpcbs.update', $data->id) }}",
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
                                    window.location.href = "{{ route('latest-cpcbs.index') }}";
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
