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
                            <label class="form-label"><strong>Division:</strong></label>
                            <p class="mb-0">{{ $tender->division?->title ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Title (English):</strong></label>
                            <p class="mb-0">{{ $tender->title ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Title (Hindi):</strong></label>
                            <p class="mb-0">{{ $tender->title_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Prebid Meeting date/time:</strong></label>
                            <p class="mb-0">{{ $tender->publish_date ? $tender->publish_date->format('d M Y H:i') : '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Start Date:</strong></label>
                            <p class="mb-0">{{ $tender->start_date ? $tender->start_date->format('d M Y H:i') : '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>End Date:</strong></label>
                            <p class="mb-0">{{ $tender->end_date ? $tender->end_date->format('d M Y H:i') : '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>File (English):</strong></label>
                            <p class="mb-0">
                                @if ($tender->file_name)
                                    <a href="{{ generate_file_view_path_for_backend($tender->file_url) }}" target="_blank">View
                                        Document</a>
                                @else
                                    <span class="text-muted">No file uploaded.</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>File (Hindi):</strong></label>
                            <p class="mb-0">
                                @if ($tender->file_name_hi)
                                    <a href="{{ generate_file_view_path_for_backend($tender->file_url_hi) }}"
                                        target="_blank">View Document</a>
                                @else
                                    <span class="text-muted">No file uploaded.</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Contact Person/Phone No. (English):</strong></label>
                            <p class="mb-0">{{ $tender->issuing_authority ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Contact Person/Phone No. (Hindi):</strong></label>
                            <p class="mb-0">{{ $tender->issuing_authority_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created Date:</label>
                            <p class="mb-0">
                                {{ $tender->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created By</label>
                            <p class="mb-0">
                                {{ $tender->created_by_user ? $tender->created_by_user->name : 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated Date:</label>
                            <p class="mb-0">
                                {{ $tender->updated_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated By</label>
                            <p class="mb-0">
                                {{ $tender->updated_by_user ? $tender->updated_by_user->name : 'N/A' }}
                            </p>
                        </div>


                        {{-- ================= Corrigendums ================= --}}
                        <div class="col-12">
                            <hr>
                            <h5>Corrigendums</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="8%">S.No.</th>
                                            <th width="20%">File</th>
                                            <th width="20%">Title</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tender->corrigendumns as $key => $file)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    @if ($file->file_name)
                                                        <a href="{{ generate_file_view_path_for_backend($file->file_path) }}"
                                                            target="_blank">
                                                            <i class="fa fa-eye"></i> View File (English)
                                                        </a>
                                                    @endif
                                                    <br>
                                                    <br>
                                                    @if ($file->file_name_hi)
                                                        <a href="{{ generate_file_view_path_for_backend($file->file_path_hi) }}"
                                                            target="_blank">
                                                            <i class="fa fa-eye"></i> View File (हिंदी)
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $file->title }}
                                                    <br>
                                                    <br>
                                                    {{ $file->title_hi }}
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

                    </div>
                </div>
            </div>

            <hr>

            <div class="card card-body">
                <div class="row">
                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Approval Status:</strong></label>
                        <p class="mb-0">
                            @if ($tender->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($tender->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $tender->is_published
        ? '<span class="badge bg-primary">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">
                            {!! $tender->remarks ? $tender->remarks : '—' !!}
                        </p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Remark:</strong></label>
                        <p class="mb-0">
                            {!! $tender->publish_remark ? $tender->publish_remark : '—' !!}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve tender')
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
                                        <textarea name="remarks" class="form-control" rows="3"
                                            placeholder="Mandatory if rejecting"></textarea>
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

                @can('publish tender')
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
                                        <textarea name="publish_remark" class="form-control" rows="3"
                                            placeholder="Enter publish remark if any"></textarea>
                                    </div>
                                    <div class="alert alert-info" role="alert">
                                        <i class="fa fa-info-circle"></i> Publishing will automatically approve if not already
                                        approved.
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
                        remarks: {
                            required: "Please provide a reason for rejection."
                        }
                    },
                    submitHandler: function (form) {
                        $.ajax({
                            url: "{{ route('tenders.approve', $tender->id) }}",
                            type: "POST",
                            data: $(form).serialize(),
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('tenders.index') }}";
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
                            required: true,
                        },
                        publish_remark: {
                            maxlength: 500
                        }
                    },
                    submitHandler: function (form) {
                        $.ajax({
                            url: "{{ route('tenders.publish', $tender->id) }}",
                            type: "POST",
                            data: $(form).serialize(),
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('tenders.index') }}";
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
