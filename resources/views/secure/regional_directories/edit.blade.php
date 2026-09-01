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
                    <form id="regional_directoriesForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Zone: <span class="text-danger">*</span></label>
                                <input type="text" name="zone" class="form-control"
                                    value="{{ $regional_directories->zone }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">State: <span class="text-danger">*</span></label>
                                <input type="text" name="state" class="form-control"
                                    value="{{ $regional_directories->state }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Address:</label>
                                <textarea name="address" class="form-control"
                                    rows="3">{{ $regional_directories->address }}</textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Phone Numbers:</label>
                                <input type="number" name="phone_numbers" class="form-control" rows="3"
                                    value="{{ $regional_directories->phone_numbers }}" />
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Jurdiction:</label>
                                <textarea name="jurdiction" class="form-control"
                                    rows="3">{{ $regional_directories->jurdiction }}</textarea>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" name="email_ids" class="form-control" rows="3"
                                    value="{{ $regional_directories->email_ids }}" />
                            </div>


                            <div class="col-md-12 col-12 mb-3">
                                <label class="form-label">Link: <span class="text-danger">*</span></label>
                                <input type="url" name="link" class="form-control"
                                    value="{{ $regional_directories->location_link }}" />
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
            $("#regional_directoriesForm").validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    title_hi: {
                        required: true,
                        maxlength: 255
                    },
                    description: {
                        maxlength: 1000
                    },
                    description_hi: {
                        maxlength: 1000
                    },
                    link: {
                        required: true,
                        url: true,
                        maxlength: 2048
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('regional_directories.update', $regional_directories->id) }}",
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