@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="View Information Center Detail" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">

            <!-- BASIC DETAILS -->
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label">Information Center:</label>
                            <p class="mb-0">
                                {{ optional($detail->informationCenter)->title ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label">Type:</label>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ $detail->type }}</span>
                            </p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label">Title (English):</label>
                            <p class="mb-0">{{ $detail->title }}</p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label">Title (हिंदी):</label>
                            <p class="mb-0">{{ $detail->title_hi }}</p>
                        </div>



                        @if ($detail->type === 'URL')
                            <div class="col-md-6 card py-2 bg-light mb-3">
                                <label class="form-label">URL:</label>
                                <p class="mb-0">
                                    <a href="{{ $detail->url }}" target="_blank">
                                        {{ $detail->url }}
                                    </a>
                                </p>
                            </div>
                        @endif

                        @if ($detail->type === 'FILE')
                            <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                                <label class="form-label"><strong>File (English):</strong></label>
                                <p class="mb-0">
                                    @if ($detail->file_name)
                                        <a href="{{ generate_file_view_path_for_backend($detail->file_url) }}" target="_blank">View
                                            Document</a>
                                    @else
                                        <span class="text-muted">No file uploaded.</span>
                                    @endif
                                </p>
                            </div>

                            <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                                <label class="form-label"><strong>File (Hindi):</strong></label>
                                <p class="mb-0">
                                    @if ($detail->file_name_hi)
                                        <a href="{{ generate_file_view_path_for_backend($detail->file_url_hi) }}"
                                            target="_blank">View Document</a>
                                    @else
                                        <span class="text-muted">No file uploaded.</span>
                                    @endif
                                </p>
                            </div>
                        @endif


                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created Date:</label>
                            <p class="mb-0">
                                {{ $detail->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created By</label>
                            <p class="mb-0">
                                {{ $detail->created_by_user ? $detail->created_by_user->name : 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated Date:</label>
                            <p class="mb-0">
                                {{ $detail->updated_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated By</label>
                            <p class="mb-0">
                                {{ $detail->updated_by_user ? $detail->updated_by_user->name : 'N/A' }}
                            </p>
                        </div>


                    </div>
                </div>
            </div>

            <!-- STATUS -->
            <div class="card card-body">
                <div class="row">
                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Approval Status:</strong></label>
                        <p class="mb-0">
                            @if ($detail->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($detail->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $detail->is_published
        ? '<span class="badge bg-success">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">
                            {{ $detail->remarks ? $detail->remarks : 'No remarks available' }}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Remark:</strong></label>
                        <p class="mb-0">
                            {{ $detail->publish_remark ?: 'No publish remarks available' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- APPROVE / PUBLISH -->
            <div class="row">
                @can('approve information center detail')
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fa fa-check"></i> Approval</h5>
                            </div>
                            <div class="card-body">
                                <form id="approveForm">
                                    @csrf @method('PUT')

                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select name="is_approved" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="1">Approve</option>
                                            <option value="2">Reject</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Remarks <span class="text-danger">*</span></label>
                                        <textarea name="remarks" class="form-control"></textarea>
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

                @can('publish information center detail')
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fa fa-paper-plane"></i> Publish</h5>
                            </div>
                            <div class="card-body">
                                <form id="publishForm">
                                    @csrf @method('PUT')
                                    <div class="mb-3">
                                        <select name="is_published" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="1">Publish</option>
                                            <option value="0">Unpublish</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Publish Remark</label>
                                        <textarea name="publish_remark" class="form-control"></textarea>
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
                            url: "{{ route('information-center-details.approve', $detail->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success")
                                    .then(() => window.location.href = "{{ route('information-center-details.index') }}");
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
                            url: "{{ route('information-center-details.publish', $detail->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success")
                                    .then(() => window.location.href = "{{ route('information-center-details.index') }}");
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