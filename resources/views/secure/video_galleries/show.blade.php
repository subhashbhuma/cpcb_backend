@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="View Video Gallery" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Event:</label>
                            <p class="mb-0">{{ $videoGallery->event->title }}</p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Title (English):</label>
                            <p class="mb-0">{{ $videoGallery->title }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Title (हिंदी):</label>
                            <p class="mb-0">{{ $videoGallery->title_hi }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Date:</label>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($videoGallery->date)->format('d-m-Y') }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Thumbnail Image:</label>
                            @if ($videoGallery->thumbnail_image)
                                <a href="{{ generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['VIDEO_GALLERY_THUMBNAIL_IMAGE_PATH'] . '/' . $videoGallery->thumbnail_image)) }}"
                                    target="_blank">
                                    <img src="{{ generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['VIDEO_GALLERY_THUMBNAIL_IMAGE_PATH'] . '/' . $videoGallery->thumbnail_image)) }}"
                                        alt="Thumbnail Image" class="img-thumbnail show-preview-image" />
                                </a>
                            @else
                                <p class="mb-0 text-danger">No image available</p>
                            @endif
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Type:</label>
                            <p class="mb-0">{{ Config::get('constants')['VIDEO_GALLERY_TYPE'][$videoGallery->type] }}</p>
                        </div>

                        @if ($videoGallery->type == 1)
                            <!-- File -->
                            <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                                <label class="form-label">Video (File):</label>
                                @if ($videoGallery->file_name)
                                    <div>
                                        <a href="{{ generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['VIDEO_GALLERY_VIDEO_PATH'] . '/' . $videoGallery->file_name)) }}"
                                            target="_blank" class="btn btn-primary">
                                            <i class="fa fa-eye"></i> View File
                                        </a>
                                    </div>
                                @else
                                    <p class="text-danger">No file available</p>
                                @endif
                            </div>
                        @elseif($videoGallery->type == 2)
                            <!-- YouTube Embed -->
                            <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                                <label class="form-label">Video (YouTube):</label>
                                @if ($videoGallery->youtube_embed_code)
                                    <div class="embed-responsive embed-responsive-16by9">
                                        {!! $videoGallery->youtube_embed_code !!}
                                    </div>
                                @else
                                    <p class="text-danger">No YouTube Embed Code available</p>
                                @endif
                            </div>
                        @elseif($videoGallery->type == 3)
                            <!-- URL -->
                            <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                                <label class="form-label">Video (URL):</label>
                                @if ($videoGallery->url)
                                    <a href="{{ $videoGallery->url }}" target="_blank">View Video</a>
                                @else
                                    <p class="text-danger">No URL available</p>
                                @endif
                            </div>
                        @else
                            <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                                <p class="text-danger">No video available</p>
                            </div>
                        @endif

                        <div class="col-12 mb-3" style="max-height: 400px; overflow-y: auto;">
                            <label class="form-label">Description:</label>
                            <div class="border p-3">
                                @if ($videoGallery->description)
                                    {!! $videoGallery->description !!}
                                @else
                                    <span class="text-danger"> No content available</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 mb-3" style="max-height: 400px; overflow-y: auto;">
                            <label class="form-label">Description (हिंदी):</label>
                            <div class="border p-3">
                                @if ($videoGallery->description_hi)
                                    {!! $videoGallery->description_hi !!}
                                @else
                                    <span class="text-danger"> No content available</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created Date:</label>
                            <p class="mb-0">
                                {{ $videoGallery->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created By</label>
                            <p class="mb-0">
                                {{ $videoGallery->created_by_user ? $videoGallery->created_by_user->name : 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated Date:</label>
                            <p class="mb-0">
                                {{ $videoGallery->updated_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated By</label>
                            <p class="mb-0">
                                {{ $videoGallery->updated_by_user ? $videoGallery->updated_by_user->name : 'N/A' }}
                            </p>
                        </div>

                    </div>
                </div> <!-- card-body -->
            </div> <!-- card -->



            <div class="card card-body">
                <div class="row">
                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Approval Status:</strong></label>
                        <p class="mb-0">
                            @if ($videoGallery->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($videoGallery->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $videoGallery->is_published
        ? '<span class="badge bg-primary">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">
                            {{ $videoGallery->remarks ? $videoGallery->remarks : 'No remarks available' }}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Remark:</strong></label>
                        <p class="mb-0">
                            {{ $videoGallery->publish_remark ?: 'No publish remarks available' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve video gallery')
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fa fa-check"></i> Approval Form
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <form id="approveForm">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-3">
                                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                                <select name="is_approved" class="form-control" required>
                                                    <option value="">-- Select Status --</option>
                                                    <option value="1">Approve</option>
                                                    <option value="2">Reject</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Remarks <span class="text-danger">*</span></label>
                                                <textarea name="remarks" class="form-control" rows="3"></textarea>
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa fa-check"></i> Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('publish video gallery')
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fa fa-paper-plane"></i> Publish Form
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <form id="publishForm">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-3">
                                                <label class="form-label">Publish Status <span
                                                        class="text-danger">*</span></label>
                                                <select name="is_published" class="form-control" required>
                                                    <option value="">-- Select Option --</option>
                                                    <option value="1">Publish</option>
                                                    <option value="0">Unpublish</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Publish Remark</label>
                                                <textarea name="publish_remark" class="form-control" rows="3"></textarea>
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa fa-upload"></i> Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div> <!-- col -->
    </div> <!-- row -->
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {
            // Approve Form Validation
            if ($("#approveForm").length) {
                $("#approveForm").validate({
                    rules: {
                        is_approved: { required: true },
                        remarks: {
                            required: function (element) {
                                return $("#approveForm select[name='is_approved']").val() == "2";
                            },
                            maxlength: 500
                        }
                    },
                    submitHandler: function (form) {
                        let formData = new FormData(form);

                        $.ajax({
                            url: "{{ route('video-gallery.approve', $videoGallery->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success")
                                    .then(() => window.location.href = "{{ route('video-gallery.index') }}");
                            },
                            error: function (xhr) {
                                const msg = xhr.status === 422 ?
                                    Object.values(xhr.responseJSON.errors).flat().join("<br>") :
                                    "Something went wrong!";
                                Swal.fire("Error", msg, "error");
                            }
                        });
                        return false;
                    }
                });
            }

            // Publish Form Validation
            if ($("#publishForm").length) {
                $("#publishForm").validate({
                    rules: {
                        is_published: { required: true },
                        publish_remark: { maxlength: 500 }
                    },
                    submitHandler: function (form) {
                        let formData = new FormData(form);

                        $.ajax({
                            url: "{{ route('video-gallery.publish', $videoGallery->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success")
                                    .then(() => window.location.href = "{{ route('video-gallery.index') }}");
                            },
                            error: function (xhr) {
                                const msg = xhr.status === 422 ?
                                    Object.values(xhr.responseJSON.errors).flat().join("<br>") :
                                    "Something went wrong!";
                                Swal.fire("Error", msg, "error");
                            }
                        });
                        return false;
                    }
                });
            }
        });
    </script>
@endsection