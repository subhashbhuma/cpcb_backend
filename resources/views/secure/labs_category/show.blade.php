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
                            <label class="form-label"><strong>Title (English):</strong></label>
                            <p class="mb-0">{{ $labCategory->title ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Title (Hindi):</strong></label>
                            <p class="mb-0">{{ $labCategory->title ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Slogan (English):</strong></label>
                            <p class="mb-0">{{ $labCategory->slogan ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Slogan (Hindi):</strong></label>
                            <p class="mb-0">{{ $labCategory->slogan_hi ?? '—' }}</p>
                        </div>


                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Description (English):</strong></label>
                            <p class="mb-0">{{ $labCategory->description ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Description (Hindi):</strong></label>
                            <p class="mb-0">{{ $labCategory->description ?? '—' }}</p>
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
                            @if ($labCategory->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($labCategory->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $labCategory->is_published
        ? '<span class="badge bg-primary">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">
                            {{ $labCategory->remarks ? $labCategory->remarks : 'No remarks available' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve laboratories category')
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

                @can('publish laboratories category')
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
    @can('approve laboratories category')
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
                            url: "{{ route('labs_category.approve', $labCategory->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('labs_category.index') }}";
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

    @can('publish laboratories category')
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
                            url: "{{ route('labs_category.publish', $labCategory->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('labs_category.index') }}";
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