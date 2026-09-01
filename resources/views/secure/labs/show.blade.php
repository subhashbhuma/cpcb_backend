@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="View Page" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Title (English):</label>
                            <p class="mb-0">{{ $labs->title }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Title (हिंदी):</label>
                            <p class="mb-0">{{ $labs->title_hi }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Title (हिंदी):</label>
                            <p class="mb-0">{{ $labs->title }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Featured Image:</label>
                            @if($labs->featured_image)
                                <a href="{{ asset('storage/' . Config::get('file_paths')['LABORATORIES_FEATURED_IMAGE_PATH'] . '/' . $labs->featured_image) }}"
                                    target="_blank">
                                    <img src="{{ asset('storage/' . Config::get('file_paths')['LABORATORIES_FEATURED_IMAGE_PATH'] . '/' . $labs->featured_image) }}"
                                        alt="Featured Image" class="img-thumbnail show-preview-image" />
                                </a>
                            @else
                                <p class="mb-0 text-danger">No image available</p>
                            @endif
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Public Comment (हिंदी):</label>
                            <p class="mb-0">{{ $labs->public_comment }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Public Comment (हिंदी):</label>
                            <p class="mb-0">{{ $labs->public_comment_hi }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Category:</label>
                            <p class="mb-0">
                                {{ optional($labs->category)->title ?? 'N/A' }}
                            </p>
                        </div>
                    </div> <!-- row -->
                </div> <!-- card-body -->
            </div> <!-- card -->

            <div class="card card-body">
                <div class="row">
                    <div class="col-md-12 col-12 mb-3">
                        <ul class="nav nav-tabs mb-3 page-creation-tab" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true"><i class="ti ti-file"></i> Page Content</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="false"><i class="fa fa-file-pdf"></i> PDF
                                    Content</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="mb-3" style="max-height: 400px; overflow-y: auto;">
                                    <label class="form-label">Content (English):</label>
                                    <div class="border p-3">
                                        @if ($labs->content)
                                            {!! $labs->content !!}
                                        @else
                                            <span class="text-danger"> No content available</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3" style="max-height: 400px; overflow-y: auto;">
                                    <label class="form-label">Content (हिंदी):</label>
                                    <div class="border p-3">
                                        @if ($labs->content_hi)
                                            {!! $labs->content_hi !!}
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
                                                <th width="8%">S.No.</th>
                                                <th width="20%">File</th>
                                                <th width="20%">Title</th>
                                                <th width="20%">Type</th>
                                                <!-- <th>Description</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($labs->files as $key => $file)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        @if ($file->file_name)
                                                            <a href="{{ $file->file_path }}" target="_blank">
                                                                <i class="fa fa-eye"></i> View File (English)
                                                            </a>
                                                        @endif
                                                        <br>
                                                        <br>
                                                        @if ($file->file_name_hi)
                                                            <a href="{{ $file->file_name_hi }}" target="_blank">
                                                                <i class="fa fa-eye"></i> View File (हिंदी)
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $file->title }}
                                                        <br>
                                                        <br>
                                                        ({{ $file->title_hi }})
                                                    </td>
                                                    <!-- <td>
                                                                        {{ $file->description }}
                                                                        <br> <br>
                                                                        {{ $file->description_hi }}
                                                                    </td> -->
                                                    <td>
                                                        {{ $file->type }}
                                                        <br> <br>
                                                        {{ $file->type }}
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
                            @if ($labs->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($labs->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $labs->is_published
        ? '<span class="badge bg-success">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">
                            {{ $labs->remarks ? $labs->remarks : 'No remarks available' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve laboratories page')
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
                                                <label class="form-label">Remarks</label>
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
                @can('publish laboratories page')
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
    @can('approve laboratories page')
        <script @cspNonce>
            $(document).ready(function () {
                // Approve Form Validation
                $("#approveForm").validate({
                    rules: {
                        is_approved: {
                            required: true,
                        },
                        remarks: {
                            required: function (element) {
                                return $("input[name='is_approved']:checked").val() == "0";
                            },
                            maxlength: 500
                        }
                    },
                    messages: {
                        is_approved: "Please select approval status.",
                        remarks: {
                            required: "Remarks are required when rejecting.",
                            maxlength: "Remarks should not exceed 500 characters."
                        }
                    },
                    submitHandler: function (form) {
                        let formData = new FormData(form);

                        $.ajax({
                            url: "{{ route('labs.approve', $labs->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('labs.index') }}";
                                });
                            },
                            error: function (xhr) {
                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    let errorMsg = Object.values(errors).flat().join("<br>");
                                    Swal.fire("Validation Error", errorMsg, "error");
                                } else {
                                    Swal.fire("Error!", "Something went wrong!", "error");
                                }
                            }
                        });

                        return false;
                    }
                });
            });
        </script>
    @endcan

    @can('publish laboratories page')
        <script @cspNonce>
            $(document).ready(function () {
                // Publish Form Validation
                $("#publishForm").validate({
                    rules: {
                        is_published: {
                            required: true,
                        },
                    },
                    submitHandler: function (form) {
                        let formData = new FormData(form);

                        $.ajax({
                            url: "{{ route('labs.publish', $labs->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('labs.index') }}";
                                });
                            },
                            error: function (xhr) {
                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    let errorMsg = Object.values(errors).flat().join("<br>");
                                    Swal.fire("Validation Error", errorMsg, "error");
                                } else {
                                    Swal.fire("Error!", "Something went wrong!", "error");
                                }
                            }
                        });

                        return false;
                    }
                });
            });
        </script>
    @endcan
@endsection