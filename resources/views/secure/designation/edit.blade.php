@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <p>All (<span class="text-danger">*</span>) marked fields are mandatory.</p>

                    <form id="designationForm" action="{{ route('designation.update', $record->id) }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title" class="form-control"
                                    value="{{ old('title', $record->title) }}" required>
                            </div>

                            <!-- Title Hindi -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title (Hindi) <span class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" id="title_hi" name="title_hi" class="form-control"
                                    value="{{ old('title_hi', $record->title_hi) }}" required>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Update Designation
                            </button>
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

            $('#designationForm').validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    title_hi: {
                        required: true,
                        maxlength: 255
                    }
                },

                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: form.action,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,

                        success: function (response) {
                            if (response.success) {
                                Swal.fire("Updated!", response.message, "success")
                                    .then(() => window.location.href = response
                                        .redirect_url);
                            } else {
                                toastr.error(response.message);
                            }
                        },

                        error: function (xhr) {
                            if (xhr.status === 422) {
                                let errors = Object.values(xhr.responseJSON.errors).flat()
                                    .join("<br>");
                                Swal.fire("Validation Error", errors, "error");
                            } else {
                                Swal.fire("Error!",
                                    "Something went wrong. Please try again.", "error");
                            }
                        }
                    });

                    return false;
                }
            });

        });
    </script>
@endsection