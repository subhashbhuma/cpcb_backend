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
                            <label class="form-label">Title (English):</label>
                            <p class="mb-0">{{ $subjectArea->title }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Title (हिंदी):</label>
                            <p class="mb-0">{{ $subjectArea->title_hi }}</p>
                        </div>
                    </div>
                </div> <!-- card-body -->
            </div> <!-- card -->



            <div class="card card-body">
                <div class="row">
                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Approval Status:</strong></label>
                        <p class="mb-0">
                            @if ($subjectArea->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($subjectArea->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $subjectArea->is_published
        ? '<span class="badge bg-primary">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">
                            {!! $subjectArea->remarks ? $subjectArea->remarks : 'No remarks available' !!}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Remark:</strong></label>
                        <p class="mb-0">
                            {!! $subjectArea->publish_remark ? $subjectArea->publish_remark : 'No publish remarks available' !!}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve subject area')
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fa fa-check"></i> Approval Form
                                </h5>
                            </div>
                            <div class="card-body">
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
                                        <textarea name="remarks" class="form-control" rows="3"
                                            placeholder="Mandatory if rejecting"></textarea>
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
                @endcan
                @can('publish subject area')
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fa fa-paper-plane"></i> Publish Form
                                </h5>
                            </div>
                            <div class="card-body">
                                <form id="publishForm">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">Publish Status <span class="text-danger">*</span></label>
                                        <select name="is_published" class="form-control" required>
                                            <option value="">-- Select Option --</option>
                                            <option value="1">Publish</option>
                                            <option value="0">Unpublish</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Publish Remark</label>
                                        <textarea name="publish_remark" class="form-control" rows="3"
                                            placeholder="Enter publish remark if any"></textarea>
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
                        is_approved: {
                            required: true
                        },
                        remarks: {
                            required: function (element) {
                                return $("#approveForm select[name='is_approved']").val() == "2";
                            },
                            maxlength: 500
                        }
                    },
                    messages: {
                        remarks: {
                            required: "Please provide a reason for rejection."
                        }
                    },
                    submitHandler: function (form) {
                        $.ajax({
                            url: "{{ route('subject-area.approve', $subjectArea->id) }}",
                            type: "POST",
                            data: $(form).serialize(),
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('subject-area.index') }}";
                                });
                            },
                            error: function (xhr) {
                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    let errorMessages = Object.values(errors).flat().join("<br>");
                                    Swal.fire("Validation Error", errorMessages, "error");
                                } else {
                                    Swal.fire("Error!", xhr.responseJSON.message || "Something went wrong.", "error");
                                }
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
                        is_published: {
                            required: true
                        },
                        publish_remark: {
                            maxlength: 500
                        }
                    },
                    submitHandler: function (form) {
                        $.ajax({
                            url: "{{ route('subject-area.publish', $subjectArea->id) }}",
                            type: "POST",
                            data: $(form).serialize(),
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('subject-area.index') }}";
                                });
                            },
                            error: function (xhr) {
                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    let errorMessages = Object.values(errors).flat().join("<br>");
                                    Swal.fire("Validation Error", errorMessages, "error");
                                } else {
                                    Swal.fire("Error!", xhr.responseJSON.message || "Something went wrong.", "error");
                                }
                            }
                        });
                        return false;
                    }
                });
            }
        });
    </script>
@endsection