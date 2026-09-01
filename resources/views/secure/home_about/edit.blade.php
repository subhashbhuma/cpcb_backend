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
                    <form id="homeAboutForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Image File:</label>
                                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp" />
                                <p class="text-danger mb-0">
                                    Allowed types jpg, jpeg, png, gif, webp. Max allowed size 2MB.
                                </p>

                                @if ($homeAbout->image)
                                    <div class="mt-2">
                                        <strong>Current Image:</strong><br>
                                        <img src="{{ $homeAbout->image_path }}" alt="Home About Image" class="img-fluid"
                                            style="max-height: 70px;">
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English):</label>
                                <input type="text" name="title" class="form-control" value="{{ $homeAbout->title }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi):</label>
                                <input type="text" name="title_hi" class="form-control"
                                    value="{{ $homeAbout->title_hi }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Description (English):</label>
                                <textarea name="description" class="form-control"
                                    rows="3">{{ $homeAbout->description }}</textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Description (Hindi):</label>
                                <textarea name="description_hi" class="form-control"
                                    rows="3">{{ $homeAbout->description_hi }}</textarea>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Button Link:</label>
                                <input type="text" name="button_link" class="form-control"
                                    value="{{ $homeAbout->button_link }}" />
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
            $("#homeAboutForm").validate({
                rules: {
                    image: {
                        required: false
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('home-about.update', $homeAbout->id) }}",
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
                                    window.location.href = "{{ route('home-about.index') }}";
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