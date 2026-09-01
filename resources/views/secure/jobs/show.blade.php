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
                            <label class="form-label"><strong>Job Type:</strong></label>
                            <p class="mb-0">{{ ucfirst($job->job_type ?? 'regular') }}</p>
                        </div>

                        @if($job->job_type == 'contract')
                            <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                                <label class="form-label"><strong>Online Application URL:</strong></label>
                                <p class="mb-0">
                                    @if($job->online_form_url)
                                        <a href="{{ $job->online_form_url }}" target="_blank">Apply Online</a>
                                    @else
                                        —
                                    @endif
                                </p>
                            </div>
                        @else
                            <div class="col-md-4 col-12 card py-2 bg-light mb-3">
                                <label class="form-label"><strong>Direct Application Method:</strong></label>
                                <p class="mb-0">
                                    @if($job->direct_application == 'online')
                                        <a href="{{ $job->direct_application_url }}" target="_blank">Online Link</a>
                                    @else

                                        @if($job->direct_application_form_name)
                                            <a href="{{ generate_file_view_path_for_backend($job->direct_application_form_url) }}"
                                                target="_blank">View File (EN)</a>
                                            @if($job->direct_application_form_hi_name)
                                                | <a href="{{ generate_file_view_path_for_backend($job->direct_application_form_url_hi) }}"
                                                    target="_blank">View File (HI)</a>
                                            @endif
                                        @else
                                            Offline (No File)
                                        @endif
                                    @endif
                                </p>
                            </div>

                            <div class="col-md-4 col-12 card py-2 bg-light mb-3">
                                <label class="form-label"><strong>Deputation Application Method:</strong></label>
                                <p class="mb-0">
                                    @if($job->deputation_application == 'online')
                                        <a href="{{ $job->deputation_application_url }}" target="_blank">Online Link</a>
                                    @else
                                        @if($job->deputation_application_form_name)
                                            <a href="{{ generate_file_view_path_for_backend($job->deputation_application_form_url) }}"
                                                target="_blank">View File (EN)</a>
                                            @if($job->deputation_application_form_hi_name)
                                                | <a href="{{ generate_file_view_path_for_backend($job->deputation_application_form_url_hi) }}"
                                                    target="_blank">View File (HI)</a>
                                            @endif
                                        @else
                                            Offline (No File)
                                        @endif
                                    @endif
                                </p>
                            </div>
                        @endif

                        @if($job->job_type == 'contract')
                            <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                                <label class="form-label"><strong>Walk-in Interview Date:</strong></label>
                                <p class="mb-0">
                                    {{ $job->walk_in_interview_date ? \Carbon\Carbon::parse($job->walk_in_interview_date)->format('d-m-Y') : '—' }}
                                </p>
                            </div>
                        @endif

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Title (English):</strong></label>
                            <p class="mb-0">{{ $job->title ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Title (Hindi):</strong></label>
                            <p class="mb-0">{{ $job->title_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Start Date:</strong></label>
                            <p class="mb-0">{{ $job->start_date ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>End Date:</strong></label>
                            <p class="mb-0">{{ $job->end_date ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Advertisement File (English):</strong></label>
                            <p class="mb-0">
                                @if($job->advertisement_file_name)
                                    <a href="{{ generate_file_view_path_for_backend($job->advertisement_file_url) }}"
                                        target="_blank">View Document</a>
                                @else
                                    <span class="text-muted">No file uploaded.</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Advertisement File (Hindi):</strong></label>
                            <p class="mb-0">
                                @if($job->advertisement_file_hi_name)
                                    <a href="{{ generate_file_view_path_for_backend($job->advertisement_file_url) }}"
                                        target="_blank">View Document</a>
                                @else
                                    <span class="text-muted">No file uploaded.</span>
                                @endif
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
                            @if ($job->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($job->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $job->is_published
        ? '<span class="badge bg-primary">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">
                            {{ $job->remarks ? $job->remarks : 'No remarks available' }}
                        </p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Remark:</strong></label>
                        <p class="mb-0">
                            {{ $job->publish_remark ?: 'No publish remarks available' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="card card-body">
                <div class="row">
                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Created By:</strong></label>
                        <p class="mb-0">{{ $job->createdBy->name ?? '—' }}</p>
                    </div>
                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Created At:</strong></label>
                        <p class="mb-0">{{ $job->created_at ? $job->created_at->format('d-m-Y') : '—' }}</p>
                    </div>
                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Updated By:</strong></label>
                        <p class="mb-0">{{ $job->updatedBy->name ?? '—' }}</p>
                    </div>
                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Updated At:</strong></label>
                        <p class="mb-0">{{ $job->updated_at ? $job->updated_at->format('d-m-Y') : '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve job')
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
                @can('publish job')
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
                            url: "{{ route('jobs.approve', $job->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success")
                                    .then(() => window.location.href = "{{ route('jobs.index') }}");
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
                            url: "{{ route('jobs.publish', $job->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success")
                                    .then(() => window.location.href = "{{ route('jobs.index') }}");
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