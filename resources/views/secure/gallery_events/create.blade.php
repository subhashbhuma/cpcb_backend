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

                    <!-- Photo Gallery Event Form -->
                    <form id="photoGalleryEventForm" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Title (English) -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English)<span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required>
                            </div>

                            <!-- Title (हिंदी) -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi) <span class="text-danger">*</span> <x-translate-button source="title"
                                        target="title_hi" /></label>
                                <input type="text" name="title_hi" class="form-control" required>
                            </div>

                            <!-- Featured Image -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Featured Image</label>
                                <input type="file" name="featured_image" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Create Event
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

            $('#photoGalleryEventForm').validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    title_hi: {
                        required: true,
                        maxlength: 255
                    },
                },

                submitHandler: function (form) {
                    let formData = new FormData(form);

                    // Append CKEditor content if exists
                    if (window.editors) {
                        window.editors.forEach(({
                            editor,
                            name
                        }) => {
                            formData.set(name, editor.getData());
                        });
                    }

                    $.ajax({
                        url: "{{ route('gallery-event.store') }}", // ✅ FIXED ROUTE
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,

                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    window.location.href = response.redirect_url;
                                });
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
                }
            });

        });
    </script>
@endsection