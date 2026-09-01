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
                    <form id="labCatForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <label class="form-label" for="title">Title (English) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="title" id="title" class="form-control">
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label" for="title_hi">Title (हिंदी) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control">
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label" for="slogan">Slogan <span class="text-danger">*</span> </label>
                                <input type="text" name="slogan" id="slogan" class="form-control">
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label" for="slogan_hi">Slogan (हिंदी) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="slogan_hi" id="slogan_hi" class="form-control">
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label" for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label" for="description_hi">Description (हिंदी)</label>
                                <textarea name="description_hi" id="description_hi" class="form-control"
                                    rows="2"></textarea>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label" for="permission_group">Permission Name</label>
                                <input type="text" name="permission_group" id="permission_group" class="form-control"
                                    rows="2"></input>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label" for="featured_image">Featured Image <span
                                        class="text-danger">*</span> </label>
                                <input type="file" name="featured_image" id="featured_image" class="form-control"
                                    accept="image/*">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save
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
            $("#labCatForm").validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    title_hi: {
                        required: true,
                        maxlength: 255
                    },
                    slogan: {
                        required: true,
                        maxlength: 255
                    },
                    slogan_hi: {
                        required: true,
                        maxlength: 255
                    },
                    permission_group: {
                        required: true
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('labs_category.store') }}",
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
                                    window.location.href = "{{ route('labs_category.index') }}";
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