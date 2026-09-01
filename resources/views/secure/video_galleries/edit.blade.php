<!-- resources/views/secure/video_gallery/edit.blade.php -->

@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="Edit Video Gallery" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>All (<span class="text-danger">*</span>) marked fields are mandatory.</p>
                    <form id="videoGalleryForm" action="{{ route('video-gallery.update', $videoGallery->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Event <span class="text-danger">*</span></label>
                                <select name="gallery_event_id" id="gallery_event_id" class="form-select" required>
                                    <option value="">-- Select Event --</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}" @selected($videoGallery->gallery_event_id == $event->id)>
                                            {{ $event->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ $videoGallery->title }}" class="form-control"
                                    required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (हिंदी): <span class="text-danger">*</span> <x-translate-button
                                        source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" value="{{ $videoGallery->title_hi }}"
                                    class="form-control" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Thumbnail Image:</label>
                                <input type="file" name="thumbnail_image" class="form-control" accept="image/*" />
                                @if ($videoGallery->thumbnail_image)
                                    <div class="mt-2">
                                        <strong class="me-2">Current:</strong>
                                        <a href="{{ generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['VIDEO_GALLERY_THUMBNAIL_IMAGE_PATH'] . '/' . $videoGallery->thumbnail_image)) }}"
                                            target="_blank">
                                            <img src="{{ generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['VIDEO_GALLERY_THUMBNAIL_IMAGE_PATH'] . '/' . $videoGallery->thumbnail_image)) }}"
                                                alt="Thumbnail Image" class="img-thumbnail show-preview-image" />
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Type: <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1" {{ $videoGallery->type == 1 ? 'selected' : '' }}>File</option>
                                    <option value="2" {{ $videoGallery->type == 2 ? 'selected' : '' }}>YouTube Embed
                                        Code</option>
                                    <option value="3" {{ $videoGallery->type == 3 ? 'selected' : '' }}>URL</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3 file_name_col" @if ($videoGallery->type == 1)
                            style="display: block;" @else style="display: none;" @endif>
                                <label class="form-label">Video File:</label>
                                <input type="file" name="file_name" class="form-control" accept=".mp4" />
                                <p class="text-danger mb-0">(Max file size 100MB, Allowed types are .mp4)</p>
                                @if ($videoGallery->file_name)
                                    <video
                                        src="{{ generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['VIDEO_GALLERY_VIDEO_PATH'] . '/' . $videoGallery->file_name)) }}"
                                        class="w-25 mt-2 img-thumbnail"></video>
                                @endif

                            </div>

                            <div class="col-md-6 col-12 mb-3 youtube_code_col" @if ($videoGallery->type == 2)
                            style="display: block;" @else style="display: none;" @endif>
                                <label class="form-label">YouTube Embed Code <span class="text-danger">*</span>:</label>
                                <textarea name="youtube_embed_code" rows="3"
                                    class="form-control">{{ $videoGallery->youtube_embed_code }}</textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3 url_col" @if ($videoGallery->type == 3) style="display: block;"
                            @else style="display: none;" @endif>
                                <label class="form-label">URL <span class="text-danger">*</span>:</label>
                                <input type="url" name="url" value="{{ $videoGallery->url }}" class="form-control" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Date:</label>
                                <input type="text" name="date" class="form-control"
                                    value="{{ $videoGallery->date ? Carbon\Carbon::parse($videoGallery->date)->format('d-m-Y') : '' }}" />
                            </div>

                            <div class="col-md-12 col-12 mb-3">
                                <label class="form-label">Description (English):</label>
                                <textarea name="description" class="form-control"
                                    id="page-editor">{!! $videoGallery->description !!}</textarea>
                            </div>

                            <div class="col-md-12 col-12 mb-3">
                                <label class="form-label">Description (हिंदी):</label>
                                <textarea name="description_hi" class="form-control"
                                    id="hi-page-editor">{!! $videoGallery->description_hi !!}</textarea>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
    <script @cspNonce>
        function changeType(type) {
            console.log(type)
            $('.file_name_col').hide();
            $('.youtube_code_col').hide();
            $('.url_col').hide();

            if (type) {
                type = parseInt(type)
                if (type == 1) {
                    $('.file_name_col').show();
                } else if (type == 2) {
                    $('.youtube_code_col').show();
                } else if (type == 3) {
                    $('.url_col').show();
                }
            }
        }
        $(document).ready(function () {
            datePickerInit('date');
            var type = $('#type').val();
            changeType(type)

            $('#type').on('change', function () {
                var type = $('#type').val();
                changeType(type)
            })

            $('#videoGalleryForm').validate({
                rules: {
                    gallery_event_id: {
                        required: true
                    },
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    title_hi: {
                        required: true,
                        maxlength: 255
                    },
                    thumbnail_image: {
                        required: false,
                    },
                    type: {
                        required: true
                    },
                    youtube_embed_code: {
                        required: function () {
                            return $('#type').val() ==
                                2; // Only required if Type is 'YouTube Embed Code'
                        }
                    },
                    url: {
                        required: function () {
                            return $('#type').val() == 3; // Only required if Type is 'URL'
                        },
                        url: true // Ensure the URL is in a valid format
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

                    $.ajax({
                        url: form.action,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Updated!",
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
