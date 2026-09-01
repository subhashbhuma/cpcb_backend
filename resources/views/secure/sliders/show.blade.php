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
                        <div class="col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Image File:</strong></label>
                            <div>
                                @if ($slider->file_name)
                                    <img src="{{ generate_file_view_path_for_backend($slider->file_url) }}" alt="Slider Image"
                                        class="img-fluid border" style="max-height: 150px;">
                                @else
                                    <p class="text-muted mb-0">No image uploaded.</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Title (English):</strong></label>
                            <p class="mb-0">{{ $slider->title ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Title (Hindi):</strong></label>
                            <p class="mb-0">{{ $slider->title_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Description (English):</strong></label>
                            <p class="mb-0">{{ $slider->description ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Description (Hindi):</strong></label>
                            <p class="mb-0">{{ $slider->description_hi ?? '—' }}</p>
                        </div>

                        <div class="col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Link:</strong></label>
                            <p class="mb-0">
                                @if($slider->link)
                                    <a href="{{ $slider->link }}" target="_blank">{{ $slider->link }}</a>
                                @else
                                    —
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created Date:</label>
                            <p class="mb-0">
                                {{ $slider->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created By</label>
                            <p class="mb-0">
                                {{ $slider->created_by_user ? $slider->created_by_user->name : 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated Date:</label>
                            <p class="mb-0">
                                {{ $slider->updated_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated By</label>
                            <p class="mb-0">
                                {{ $slider->updated_by_user ? $slider->updated_by_user->name : 'N/A' }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <div class="card card-body">
                <div class="row">
                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Approval Status:</strong></label>
                        <p class="mb-0">
                            @if ($slider->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($slider->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $slider->is_published
        ? '<span class="badge bg-primary">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">
                            {{ $slider->remarks ? $slider->remarks : 'No remarks available' }}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Remark:</strong></label>
                        <p class="mb-0">
                            {{ $slider->publish_remark ? $slider->publish_remark : 'No publish remarks available' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve slider')
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
                @can('publish slider')
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

        </div>
    </div>
@endsection


@section('pages-scripts')
    @can('approve slider')
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
                                return $("#approveForm select[name='is_approved']").val() == "2";
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
                            url: "{{ route('sliders.approve', $slider->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('sliders.index') }}";
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

    @can('publish slider')
        <script @cspNonce>
            $(document).ready(function () {
                // Publish Form Validation
                $("#publishForm").validate({
                    rules: {
                        is_published: {
                            required: true,
                        },
                        publish_remark: {
                            maxlength: 500
                        }
                    },
                    submitHandler: function (form) {
                        let formData = new FormData(form);

                        $.ajax({
                            url: "{{ route('sliders.publish', $slider->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('sliders.index') }}";
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