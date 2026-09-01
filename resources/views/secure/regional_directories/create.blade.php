@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>
                        All (<span class="text-danger">*</span>) marked fields are required.
                    </p>
                    <form id="regionalDirectoriesForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Zone: <span class="text-danger">*</span></label>
                                <input type="text" name="zone" class="form-control" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">State: <span class="text-danger">*</span></label>
                                <input type="text" name="state" class="form-control" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Address:</label>
                                <textarea name="address" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Phone Numbers:</label>
                                <input type="number" name="phone_numbers" class="form-control" rows="3"></input>
                            </div>

                            <div class="col-md-12 col-12 mb-3">
                                <label class="form-label">Email: <span class="text-danger">*</span></label>
                                <input type="email" id="email_ids" name="email_ids" class="form-control" />
                            </div>

                            <div class="col-md-12 col-12 mb-3">
                                <label class="form-label">Jurdiction: <span class="text-danger">*</span></label>
                                <textarea id="jurdiction" name="jurdiction" class="form-control"></textarea>
                            </div>

                            <div class="col-md-12 col-12 mb-3">
                                <label class="form-label">Location Link: <span class="text-danger">*</span></label>
                                <input type="text" id="url" name="location_link" class="form-control" />
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Save</button>
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
        $.validator.addMethod("url", function (value, element) {
            return this.optional(element) || /^(https?:\/\/)?([\w\-\.]+)(:\d+)?(\/[\w\-\.]*)*\/?$/.test(value);
        }, "Please enter a valid URL.");
        $(document).ready(function () {
            $("#regionalDirectoriesForm").validate({
                rules: {
                    zone: {
                        required: true,
                        maxlength: 100
                    },
                    state: {
                        required: true,
                        maxlength: 100
                    },
                    address: {
                        required: false,
                        maxlength: 500
                    },
                    phone_numbers: {
                        required: false,
                        maxlength: 15
                    },
                    email_ids: {
                        required: true,
                        email: true,
                        maxlength: 255
                    },
                    jurdiction: {
                        required: true,
                        maxlength: 500
                    },
                    location_link: {
                        required: true,
                        url: true,
                        maxlength: 2048
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('regional_directories.store') }}",
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
                                    window.location.href = "{{ route('regional_directories.index') }}";
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