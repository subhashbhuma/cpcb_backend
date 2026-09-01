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

                    <form id="whoIsWhoForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Name (Hindi) <span class="text-danger">*</span> <x-translate-button source="name" target="name_hi" /></label>
                                <input type="text" name="name_hi" class="form-control" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Designation</label>
                                <input type="text" name="designation" class="form-control" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Designation (Hindi) <x-translate-button source="designation" target="designation_hi" /></label>
                                <input type="text" name="designation_hi" class="form-control" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Order</label>
                                <input type="number" name="order" class="form-control" value="0" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile_number" class="form-control" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Email ID</label>
                                <input type="email" name="email_id" class="form-control" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Division</label>
                                <select name="division_id" class="form-control">
                                    <option value="">-- Select Division --</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}">{{ $division->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 col-12 mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*" />
                                <small class="text-muted">Allowed file types are jpg, jpeg, png, gif, webp and maximum file size is 2MB.</small>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Address (Hindi) <x-translate-button source="address" target="address_hi" /></label>
                                <textarea name="address_hi" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label ">Show On Homepage <span class="text-danger">*</span></label>
                                <select name="show_on_homepage" class="form-control">
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label ">Hide On Who Is Who page <span
                                        class="text-danger">*</span></label>
                                <select name="hide_on_who_is_who" class="form-control">
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                </select>
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Save
                                </button>
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
            $("#whoIsWhoForm").validate({
                rules: {
                    name: {
                        required: true
                    },
                    name_hi: {
                        required: true
                    },
                    image: {
                        required: false,
                        filesize: 2048000 // 2MB
                    }
                },
                messages: {
                    image: {
                        filesize: "Image size must be less than 2MB."
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('who-is-who.store') }}", // update with your actual route
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
                                    window.location.href = "{{ route('who-is-who.index') }}"; // update route
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

            // Custom method for file size validation
            $.validator.addMethod('filesize', function (value, element, param) {
                return this.optional(element) || (element.files[0].size <= param);
            });
        });
    </script>
@endsection