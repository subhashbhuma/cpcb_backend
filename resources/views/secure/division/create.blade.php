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
                    <form id="divisionForm" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Title (English) -->
                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Title (English)<span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title" class="form-control" required>
                            </div>

                            <!-- Title (हिंदी) -->
                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Title (Hindi) <span class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" id="title_hi" name="title_hi" class="form-control" required>
                            </div>

                            <!-- Is New -->
                            <div class="col-md-4 col-12 mb-3">
                                <div class="form-check form-switch mt-4 pt-2">
                                    <input class="form-check-input" type="checkbox" name="is_new" id="is_new" value="1">
                                    <label class="form-check-label" for="is_new">Is New?</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Create Division
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

            $('#divisionForm').validate({
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
                        url: "{{ route('division.store') }}",
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