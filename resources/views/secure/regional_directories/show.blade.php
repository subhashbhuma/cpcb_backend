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

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Zone:</strong></label>
                            <p class="mb-0">{{ $regional_directories->zone ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>State:</strong></label>
                            <p class="mb-0">{{ $regional_directories->state ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Address:</strong></label>
                            <p class="mb-0">{{ $regional_directories->address ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Phone Number:</strong></label>
                            <p class="mb-0">{{ $regional_directories->phone_numbers ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Email:</strong></label>
                            <p class="mb-0">{{ $regional_directories->email_ids ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>jurdiction:</strong></label>
                            <p class="mb-0">{{ $regional_directories->jurdiction ?? '—' }}</p>
                        </div>

                        <div class="col-md-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Location Link:</strong></label>
                            <p class="mb-0">
                                @if ($regional_directories->location_link)
                                    <a href="{{ $regional_directories->location_link }}" target="_blank"
                                        rel="noopener noreferrer">{{ $regional_directories->location_link }}</a>
                                @else
                                    —
                                @endif
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
                            @if ($regional_directories->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($regional_directories->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $regional_directories->is_published
        ? '<span class="badge bg-primary">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">
                            {{ $regional_directories->remarks ? $regional_directories->remarks : 'No remarks available' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve regional directory')
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
                                        <label class="form-label">Remarks</label>
                                        <textarea name="remarks" class="form-control" rows="3" required></textarea>
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

                @can('publish regional directory')
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
    @can('approve regional directory')
        <script @cspNonce>
            $(document).ready(function () {
                $("#approveForm").validate({
                    rules: {
                        is_approved: {
                            required: true
                        },
                        remarks: {
                            required: function () {
                                return $("select[name='is_approved']").val() == "2";
                            },
                            maxlength: 500
                        }
                    },
                    submitHandler: function (form) {
                        let formData = new FormData(form);
                        $.ajax({
                            url: "{{ route('regional_directories.approve', $regional_directories->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('regional_directories.index') }}";
                                });
                            },
                            error: function (xhr) {
                                let errorMsg = xhr.responseJSON?.message || "Something went wrong!";
                                Swal.fire("Error!", errorMsg, "error");
                            }
                        });
                        return false;
                    }
                });
            });
        </script>
    @endcan

    @can('publish regional directory')
        <script @cspNonce>
            $(document).ready(function () {
                $("#publishForm").validate({
                    rules: {
                        is_published: {
                            required: true
                        }
                    },
                    submitHandler: function (form) {
                        let formData = new FormData(form);
                        $.ajax({
                            url: "{{ route('regional_directories.publish', $regional_directories->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('regional_directories.index') }}";
                                });
                            },
                            error: function (xhr) {
                                let errorMsg = xhr.responseJSON?.message || "Something went wrong!";
                                Swal.fire("Error!", errorMsg, "error");
                            }
                        });
                        return false;
                    }
                });
            });
        </script>
    @endcan
@endsection