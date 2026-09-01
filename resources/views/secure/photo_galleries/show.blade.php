@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Event:</label>
                            <p class="mb-0">{{ $photoGallery?->event?->title }}</p>
                        </div>


                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Title (English):</label>
                            <p class="mb-0">{{ $photoGallery->title }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Title (हिंदी):</label>
                            <p class="mb-0">{{ $photoGallery->title_hi }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Date:</label>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($photoGallery->date)->format('d-m-Y') }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Featured Image:</label>
                            @if ($photoGallery->featured_image)
                                <a href="{{ generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['PHOTO_GALLERY_FEATURED_IMAGE_PATH'] . '/' . $photoGallery->featured_image)) }}"
                                    target="_blank">
                                    <img src="{{ generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['PHOTO_GALLERY_FEATURED_IMAGE_PATH'] . '/' . $photoGallery->featured_image)) }}"
                                        alt="Featured Image" class="img-thumbnail show-preview-image" />
                                </a>
                            @else
                                <p class="mb-0 text-danger">No image available</p>
                            @endif
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created Date:</label>
                            <p class="mb-0">
                                {{ $photoGallery->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created By</label>
                            <p class="mb-0">
                                {{ $photoGallery->created_by_user ? $photoGallery->created_by_user->name : 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated Date:</label>
                            <p class="mb-0">
                                {{ $photoGallery->updated_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated By</label>
                            <p class="mb-0">
                                {{ $photoGallery->updated_by_user ? $photoGallery->updated_by_user->name : 'N/A' }}
                            </p>
                        </div>

                    </div>
                </div> <!-- card-body -->
            </div> <!-- card -->

            <div class="card card-body">
                <div class="row">
                    <div class="col-md-12 col-12 mb-3">
                        <ul class="nav nav-tabs mb-3 page-creation-tab" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true"><i class="ti ti-file"></i> Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="false"><i class="fa fa-file-pdf"></i> Images</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="mb-3" style="max-height: 400px; overflow-y: auto;">
                                    <label class="form-label">Description:</label>
                                    <div class="border p-3">
                                        @if ($photoGallery->description)
                                            {!! $photoGallery->description !!}
                                        @else
                                            <span class="text-danger"> No content available</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3" style="max-height: 400px; overflow-y: auto;">
                                    <label class="form-label">Description (हिंदी):</label>
                                    <div class="border p-3">
                                        @if ($photoGallery->description_hi)
                                            {!! $photoGallery->description_hi !!}
                                        @else
                                            <span class="text-danger"> No content available</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="10%">S.No.</th>
                                                <th width="45%">Image</th>
                                                <th width="45%">Title</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($photoGallery->images as $key => $file)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        @if ($file->file_name)
                                                            <a href="{{ generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['PHOTO_GALLERY_IMAGES_PATH'] . '/' . $file->file_name)) }}"
                                                                target="_blank">
                                                                <img src="{{ generate_file_view_path_for_backend(asset('storage/' . Config::get('file_paths')['PHOTO_GALLERY_IMAGES_PATH'] . '/' . $file->file_name)) }}"
                                                                    width="100" class="img-thumbnail" />
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $file->title }}<br>
                                                        <small class="text-muted">{{ $file->title_hi }}</small>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No files available.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- tab-content -->
                    </div> <!-- col-12 -->
                </div>
            </div>

            <div class="card card-body">
                <div class="row">
                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Approval Status:</strong></label>
                        <p class="mb-0">
                            @if ($photoGallery->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($photoGallery->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $photoGallery->is_published
        ? '<span class="badge bg-primary">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">
                            {{ $photoGallery->remarks ? $photoGallery->remarks : 'No remarks available' }}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Remark:</strong></label>
                        <p class="mb-0">
                            {{ $photoGallery->publish_remark ?: 'No publish remarks available' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve photo gallery')
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
                @can('publish photo gallery')
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
                            url: "{{ route('photo-gallery.approve', $photoGallery->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success")
                                    .then(() => window.location.href = "{{ route('photo-gallery.index') }}");
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
                            url: "{{ route('photo-gallery.publish', $photoGallery->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success")
                                    .then(() => window.location.href = "{{ route('photo-gallery.index') }}");
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