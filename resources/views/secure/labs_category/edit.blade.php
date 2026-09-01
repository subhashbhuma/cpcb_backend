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
                    <form id="labCatForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ $labCategory->title }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <span class="text-danger">*</span></label>
                                <input type="text" name="title_hi" class="form-control"
                                    value="{{ $labCategory->title_hi }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Slogan (English): <span class="text-danger">*</span></label>
                                <input type="text" name="slogan" class="form-control" value="{{ $labCategory->slogan }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Slogan (Hindi): <span class="text-danger">*</span></label>
                                <input type="text" name="slogan_hi" class="form-control"
                                    value="{{ $labCategory->slogan_hi }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Description (English):</label>
                                <textarea name="description" class="form-control"
                                    rows="3">{{ $labCategory->description }}</textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Description (Hindi):</label>
                                <textarea name="description_hi" class="form-control"
                                    rows="3">{{ $labCategory->description_hi }}</textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Featured Image:</label>
                                <input type="file" name="featured_image" class="form-control" accept="image/*" />
                                @if($labCategory->featured_image)
                                    <div class="mt-2">
                                        <strong class="me-2">Current:</strong>
                                        <a href="{{ asset('storage/' . Config::get('file_paths')['LABORATORIES_CATEGORY_FEATURED_IMAGE_PATH'] . '/' . $labCategory->featured_image) }}"
                                            target="_blank">
                                            <img src="{{ asset('storage/' . Config::get('file_paths')['LABORATORIES_CATEGORY_FEATURED_IMAGE_PATH'] . '/' . $labCategory->featured_image) }}"
                                                alt="Featured Image" class="img-thumbnail show-preview-image" />
                                        </a>
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
            $("#labCatForm").validate({
                rules: {
                    title: {
                        required: true
                    },
                    title_hi: {
                        required: true
                    },
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: "{{ route('labs_category.update', $labCategory->id) }}",
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