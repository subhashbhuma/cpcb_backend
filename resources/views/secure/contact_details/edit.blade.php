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

                    <form id="contactDetailForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ $contactDetail->title }}"
                                    required />
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <span class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control"
                                    value="{{ $contactDetail->title_hi }}" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Department (English): <span class="text-danger">*</span></label>
                                <input type="text" name="department" id="department" class="form-control"
                                    value="{{ $contactDetail->department }}" required />
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Department (Hindi): <x-translate-button source="department" target="department_hi" /></label>
                                <input type="text" name="department_hi" id="department_hi" class="form-control"
                                    value="{{ $contactDetail->department_hi }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Address (English): <span class="text-danger">*</span></label>
                                <input type="text" name="address" id="address" class="form-control" value="{{ $contactDetail->address }}"
                                    required />
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Address (Hindi): <x-translate-button source="address" target="address_hi" /></label>
                                <input type="text" name="address_hi" id="address_hi" class="form-control"
                                    value="{{ $contactDetail->address_hi }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Profile Image: </label>
                                <input type="file" name="profile_image" class="form-control"
                                    accept=".png,.jpg,.jpeg,.webp" />
                                <small class="text-muted">Accepted formats: JPEG, PNG, JPG, WebP (Max 2MB)</small>
                                @if($contactDetail->profile_image)
                                    <div class="mt-2">
                                        <img src="{{ generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths.CONTACT_DETAIL_PROFILE_IMAGE_PATH') . '/' . $contactDetail->profile_image)) }}"
                                            alt="Current Image" style="max-width: 200px; height: auto;" />
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Phone Numbers: <span class="text-danger">*</span></label>
                                <input type="text" name="phone_numbers" class="form-control"
                                    value="{{ $contactDetail->phone_numbers }}" required />
                                <small class="form-text text-muted">Enter multiple phone numbers, separated by
                                    commas.</small>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Email IDs: <span class="text-danger">*</span></label>
                                <input type="email" name="email_ids" class="form-control"
                                    value="{{ $contactDetail->email_ids }}" required />
                                <small class="form-text text-muted">Enter multiple email IDs, separated by commas.</small>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Order: </label>
                                <input type="number" name="myorder" class="form-control"
                                    value="{{ $contactDetail->myorder }}" />
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update
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
            $("#contactDetailForm").validate({
                rules: {
                    title: {
                        required: true
                    },
                    title_hi: {
                        required: true
                    },
                    department: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    phone_numbers: {
                        required: false
                    },
                    email_ids: {
                        required: false
                    },
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('contact-details.update', $contactDetail->id) }}",
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
                                    window.location.href = "{{ route('contact-details.index') }}";
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