@extends('layouts.app_layout')

@section('content')
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @if ($directory->image)
                            <div class="col-md-12 col-12 card py-3 bg-light mb-3 text-center">
                                <label class="form-label"><strong>Image:</strong></label>
                                <img src="{{ generate_file_view_path_for_backend($directory->image_url) }}"
                                    alt="Directory Image"
                                    style="max-width: 300px; max-height: 300px; border-radius: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            </div>
                        @endif

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Name (English):</strong></label>
                            <p class="mb-0 text-primary fw-bold">{{ $directory->name ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Name (Hindi):</strong></label>
                            <p class="mb-0">{{ $directory->name_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>CPCB No.:</strong></label>
                            <p class="mb-0">{{ $directory->cpcb_no ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Designation:</strong></label>
                            <p class="mb-0">{{ $directory->designation ?? '—' }} ({{ $directory->designation_hi ?? '—' }})
                            </p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Division:</strong></label>
                            <p class="mb-0">{{ $directory->division?->title ?? '—' }} /
                                {{ $directory->division?->title_hi ?? '—' }}
                            </p>
                        </div>

                        <div class="col-md-4 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Email:</strong></label>
                            <p class="mb-0">{{ $directory->email ?? '—' }}</p>
                        </div>

                        <div class="col-md-4 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Mobile No:</strong></label>
                            <p class="mb-0">{{ $directory->mobile_no ?? '—' }}</p>
                        </div>

                        <div class="col-md-4 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Office/Ext No:</strong></label>
                            <p class="mb-0">{{ $directory->office_ph_no ?? '—' }} (Ext:
                                {{ $directory->ext_number ?? '—' }})
                            </p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Assigned Work (English):</strong></label>
                            <p class="mb-0">{{ $directory->assigned_work ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Assigned Work (Hindi):</strong></label>
                            <p class="mb-0">{{ $directory->assigned_work_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created Date:</label>
                            <p class="mb-0">
                                {{ $directory->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created By</label>
                            <p class="mb-0">
                                {{ $directory->created_by_user ? $directory->created_by_user->name : 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated Date:</label>
                            <p class="mb-0">
                                {{ $directory->updated_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated By</label>
                            <p class="mb-0">
                                {{ $directory->updated_by_user ? $directory->updated_by_user->name : 'N/A' }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <div class="card card-body">
                <div class="row">
                    <div class="col-md-4 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Approval Status:</strong></label>
                        <p class="mb-0">
                            @if ($directory->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($directory->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-4 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $directory->is_published
        ? '<span class="badge bg-primary">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-4 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Order:</strong></label>
                        <p class="mb-0">
                            {{ $directory->division_order?->title ?? $directory->order_no }}
                            / {{ $directory->show_order }}
                        </p>
                    </div>

                    <div class="col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">
                            {!! $directory->remarks ? $directory->remarks : '—' !!}
                        </p>
                    </div>

                    <div class="col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Remark:</strong></label>
                        <p class="mb-0">
                            {!! $directory->publish_remark ? $directory->publish_remark : '—' !!}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve directory')
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

                @can('publish directory')
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
                            url: "{{ route('directories.approve', $directory->id) }}",
                            type: "POST",
                            data: $(form).serialize(),
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('directories.index') }}";
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
                            url: "{{ route('directories.publish', $directory->id) }}",
                            type: "POST",
                            data: $(form).serialize(),
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('directories.index') }}";
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
