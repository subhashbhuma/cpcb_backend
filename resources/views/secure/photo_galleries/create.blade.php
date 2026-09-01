<!-- resources/views/secure/photo_gallery/create.blade.php -->

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
                        All (<span class="text-danger">*</span>) marked fields are mandatory.
                    </p>
                    <form id="photoGalleryForm" action="" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Event: <span class="text-danger">*</span></label>
                                <select name="gallery_event_id" id="gallery_event_id" class="form-control" required>
                                    <option value="">-- Select Event --</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English):<span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (हिंदी): <span class="text-danger">*</span> <x-translate-button
                                        source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" class="form-control" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Featured Image: <span class="text-danger">*</span></label>
                                <input type="file" name="featured_image" class="form-control" accept="image/*" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Date:</label>
                                <input type="text" name="date" class="form-control" />
                            </div>
                        </div>

                        <div class="col-md-12 col-12 mb-3">
                            <ul class="nav nav-tabs mb-3 page-creation-tab" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="en-tab" data-bs-toggle="tab" href="#en" role="tab"
                                        aria-controls="en" aria-selected="true"><i class="ti ti-file"></i>
                                        Description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="files-tab" data-bs-toggle="tab" href="#files" role="tab"
                                        aria-controls="files" aria-selected="false"><i class="fa fa-image"></i> Images</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="en" role="tabpanel" aria-labelledby="en-tab">
                                    <div class="mb-3">
                                        <label class="form-label">Description (English):</label>
                                        <textarea name="description" class="form-control" id="page-editor"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description (हिंदी):</label>
                                        <textarea name="description_hi" class="form-control" id="hi-page-editor"></textarea>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                                    <div class="mb-3">
                                        <h6>
                                            You can upload multiple files for this gallery. Click on the button below to add
                                            more.
                                        </h6>
                                        <button class="btn btn-success btn-add-page-file">
                                            <i class="fa fa-plus"></i> <span>Add Image</span>
                                        </button>
                                    </div>
                                    <div id="page-files-wrapper"></div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Create
                                Gallery</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('components.photo_gallery_images_template')
@endsection

@section('pages-scripts')
    <script @cspNonce>
        let fileCounter = 1;
        let fileCountArray = [];

        function generateFileRow() {
            const template = document.getElementById("fileFieldsetTemplate").innerHTML;
            const html = template.replace(/__index__/g, fileCounter);
            fileCountArray.push(fileCounter);
            fileCounter++;
            $('#page-files-wrapper').append(html);
        }

        $(document).ready(function () {
            datePickerInit('date');

            $(document).on('click', '.btn-add-page-file', function (e) {
                e.preventDefault();
                generateFileRow();
            });

            $('#page-files-wrapper').on('click', '.btn-remove-page-file', function (e) {
                e.preventDefault();
                let id = $(this).attr('id');
                $('#fieldset-' + id).remove();
                fileCountArray = fileCountArray.filter(count => count !== parseInt(id));
            });

            $('#photoGalleryForm').validate({
                rules: {
                    gallery_event_id: {
                        required: true,
                    },
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
                    window.editors.forEach(({
                        editor,
                        name,
                        id
                    }) => {
                        const content = editor.getData();
                        formData.set(name, content);
                    });
                    formData.append('fileCountArray', JSON.stringify(fileCountArray));

                    $.ajax({
                        url: "{{ route('photo-gallery.store') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href = response.redirect_url;
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessages = Object.values(errors).flat().join(
                                    "<br>");
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
