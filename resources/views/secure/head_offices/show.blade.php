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
                            <label class="form-label"><strong>Division Name:</strong></label>
                            <p class="mb-0">{{ $headOffice->division?->title ?? '—' }} /
                                {{ $headOffice->division?->title_hi ?? '—' }}
                            </p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Divisional Head & Designation (English):</strong></label>
                            <p class="mb-0">{{ $headOffice->title ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Divisional Head & Designation (Hindi):</strong></label>
                            <p class="mb-0">{{ $headOffice->title_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>E-Mail ID:</strong></label>
                            <p class="mb-0">{{ $headOffice->email ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Ext. No.:</strong></label>
                            <p class="mb-0">{{ $headOffice->ext_number ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Order:</strong></label>
                            <p class="mb-0">{{ $headOffice->order ?? '0' }}</p>
                        </div>

                        <div class="col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Description (English):</strong></label>
                            <div class="mb-0 border p-2 bg-white">
                                {!! $headOffice->description ?? '—' !!}
                            </div>
                        </div>

                        <div class="col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Description (Hindi):</strong></label>
                            <div class="mb-0 border p-2 bg-white">
                                {!! $headOffice->description_hi ?? '—' !!}
                            </div>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created Date:</label>
                            <p class="mb-0">
                                {{ $headOffice->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created By</label>
                            <p class="mb-0">
                                {{ $headOffice->created_by_user ? $headOffice->created_by_user->name : 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated Date:</label>
                            <p class="mb-0">
                                {{ $headOffice->updated_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated By</label>
                            <p class="mb-0">
                                {{ $headOffice->updated_by_user ? $headOffice->updated_by_user->name : 'N/A' }}
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
                            @if ($headOffice->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($headOffice->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $headOffice->is_published
        ? '<span class="badge bg-primary">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">
                            {{ $headOffice->remarks ? $headOffice->remarks : 'No remarks available' }}
                        </p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Remark:</strong></label>
                        <p class="mb-0">
                            {{ $headOffice->publish_remark ? $headOffice->publish_remark : 'No publish remarks available' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve head office')
                    <div class="col-md-6 col-12 col-12">
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
                @can('publish head office')
                    <div class="col-md-6 col-12 col-12">
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
    @can('approve head office')
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
                            url: "{{ route('head_offices.approve', $headOffice->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href =
                                        "{{ route('head_offices.index') }}";
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

    @can('publish head office')
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
                            url: "{{ route('head_offices.publish', $headOffice->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href =
                                        "{{ route('head_offices.index') }}";
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