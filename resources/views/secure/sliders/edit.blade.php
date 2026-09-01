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
                    <form id="sliderForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Image File:</label>
                                <input type="file" name="file_name" class="form-control"
                                    accept=".jpg,.jpeg,.png,.gif,.webp" />
                                <p class="text-danger mb-0">
                                    Allowed types jpg, jpeg, png, gif, webp. Max allowed size 2MB.
                                </p>

                                @if ($slider->file_name)
                                    <div class="mt-2">
                                        <strong>Current Image:</strong><br>
                                        <img src="{{ generate_file_view_path_for_backend($slider->file_url) }}"
                                            alt="Slider Image" class="img-fluid" style="max-height: 70px;">
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English):</label>
                                <input type="text" name="title" class="form-control" value="{{ $slider->title }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <x-translate-button source="title"
                                        target="title_hi" /></label>
                                <input type="text" name="title_hi" class="form-control" value="{{ $slider->title_hi }}" />
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Link:</label>
                                <input type="test" name="link" class="form-control" value="{{ $slider->link }}" />

                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Description (English):</label>
                                <textarea name="description" class="form-control"
                                    rows="3">{{ $slider->description }}</textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Description (Hindi): <x-translate-button source="description"
                                        target="description_hi" /></label>
                                <textarea name="description_hi" class="form-control"
                                    rows="3">{{ $slider->description_hi }}</textarea>
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
            $("#sliderForm").validate({
                rules: {
                    file_name: {
                        required: false
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('sliders.update', $slider->id) }}",
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
                                    window.location.href = "{{ route('sliders.index') }}";
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