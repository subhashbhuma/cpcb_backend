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

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Act Type:</strong></label>
                            <p class="mb-0">{{ $direction->directionActType->title ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Direction Type:</strong></label>
                            <p class="mb-0">{{ $direction->directionType->title ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Subject:</strong></label>
                            <p class="mb-0">{{ $direction->directionSubject->title ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Publish Date:</strong></label>
                            <p class="mb-0">{{ $direction->publish_date ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>States:</strong></label>
                            <p class="mb-0">
                                @if($direction->states->count() > 0)
                                    {{ $direction->states->pluck('title')->implode(', ') }}
                                @else
                                    —
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Categories:</strong></label>
                            <p class="mb-0">
                                @if($direction->categories->count() > 0)
                                    {{ $direction->categories->pluck('title')->implode(', ') }}
                                @else
                                    —
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Issued To:</strong></label>
                            <p class="mb-0">
                                @if($direction->issuedTos->count() > 0)
                                    {{ $direction->issuedTos->pluck('title')->implode(', ') }}
                                @else
                                    —
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Title (English):</strong></label>
                            <p class="mb-0">{{ $direction->title ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Title (Hindi):</strong></label>
                            <p class="mb-0">{{ $direction->title_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>File (English):</strong></label>
                            <p class="mb-0">
                                @if ($direction->file_name)
                                    <a href="{{ generate_file_view_path_for_backend($direction->file_url) }}"
                                        target="_blank">View Document</a>
                                @else
                                    <span class="text-muted">No file uploaded.</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>File (Hindi):</strong></label>
                            <p class="mb-0">
                                @if ($direction->file_name_hi)
                                    <a href="{{ generate_file_view_path_for_backend($direction->file_url_hi) }}"
                                        target="_blank">View Document</a>
                                @else
                                    <span class="text-muted">No file uploaded.</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created Date:</label>
                            <p class="mb-0">
                                {{ $direction->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created By</label>
                            <p class="mb-0">
                                {{ $direction->created_by_user ? $direction->created_by_user->name : 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated Date:</label>
                            <p class="mb-0">
                                {{ $direction->updated_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated By</label>
                            <p class="mb-0">
                                {{ $direction->updated_by_user ? $direction->updated_by_user->name : 'N/A' }}
                            </p>
                        </div>



                    </div>
                </div>
            </div>

            <hr>

            <div class="card card-body">
                <div class="row">
                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Approval Status:</strong></label>
                        <p class="mb-0">
                            @if ($direction->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($direction->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $direction->is_published
        ? '<span class="badge bg-primary">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">
                            {!! $direction->remarks ? $direction->remarks : '—' !!}
                        </p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Remark:</strong></label>
                        <p class="mb-0">
                            {!! $direction->publish_remark ? $direction->publish_remark : '—' !!}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve direction')
                    <div class="col-md-6 col-12 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fa fa-check-circle"></i> Approval Form
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
                                        <textarea name="remarks" class="form-control" rows="3" placeholder="Mandatory if rejecting"></textarea>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-check"></i> Submit Approval
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('publish direction')
                    <div class="col-md-6 col-12 mb-3">
                        <div class="card h-100">
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
                                        <textarea name="publish_remark" class="form-control" rows="3" placeholder="Enter publish remark if any"></textarea>
                                    </div>
                                    <div class="alert alert-info" role="alert">
                                        <i class="fa fa-info-circle"></i> Publishing will automatically approve if not already approved.
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-upload"></i> Submit Publish
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
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
                            required: function () {
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
                            url: "{{ route('direction.approve', $direction->id) }}",
                            type: "POST",
                            data: $(form).serialize(),
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('direction.index') }}";
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
                            url: "{{ route('direction.publish', $direction->id) }}",
                            type: "POST",
                            data: $(form).serialize(),
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('direction.index') }}";
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