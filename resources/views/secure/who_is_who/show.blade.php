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
                            <label class="form-label">Name:</label>
                            <p class="mb-0">{{ $whoIsWho->name ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label">Name (Hindi):</label>
                            <p class="mb-0">{{ $whoIsWho->name_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label">Designation:</label>
                            <p class="mb-0">{{ $whoIsWho->designation ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label">Designation (Hindi):</label>
                            <p class="mb-0">{{ $whoIsWho->designation_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label">Division:</label>
                            <p class="mb-0">{{ $whoIsWho->division->title ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label">Mobile Number:</label>
                            <p class="mb-0">{{ $whoIsWho->mobile_number ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label">Email ID:</label>
                            <p class="mb-0">{{ $whoIsWho->email_id ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label">Image:</label>
                            @if ($whoIsWho->image)
                                <div class="mt-2">
                                    <strong>Current Image:</strong><br>
                                    <img src="{{ generate_file_view_path_for_backend($whoIsWho->image_full_path) }}"
                                        alt="Profile Image" class="img-fluid" style="max-height: 70px;">
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label">Address</label>
                            <p class="mb-0">{{ $whoIsWho->address ?? '-' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label">Address (Hindi)</label>
                            <p class="mb-0">{{ $whoIsWho->address_hi ?? '-' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label">Show On Homepage</label>
                            <p class="mb-0">
                                {{ $whoIsWho->show_on_homepage_desc }}
                            </p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label">Hide On Who Is Who Page</label>
                            <p class="mb-0">
                                {{ $whoIsWho->hide_on_who_is_who_desc}}
                            </p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created Date:</label>
                            <p class="mb-0">
                                {{ $whoIsWho->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created By</label>
                            <p class="mb-0">
                                {{ $whoIsWho->created_by_user ? $whoIsWho->created_by_user->name : 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated Date:</label>
                            <p class="mb-0">
                                {{ $whoIsWho->updated_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated By</label>
                            <p class="mb-0">
                                {{ $whoIsWho->updated_by_user ? $whoIsWho->updated_by_user->name : 'N/A' }}
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
                            @if ($whoIsWho->is_approved == 1)
                                <span class="badge bg-success">Approved</span>
                            @elseif ($whoIsWho->is_approved == 2)
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            {!! $whoIsWho->is_published
        ? '<span class="badge bg-primary">Published</span>'
        : '<span class="badge bg-warning text-dark">Not Published</span>' !!}
                        </p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label">Remarks:</label>
                        <p class="mb-0">{{ $whoIsWho->remarks ?? 'No remarks available' }}</p>
                    </div>

                    <div class="col-md-6 card py-2 bg-light mb-3">
                        <label class="form-label">Publish Remark:</label>
                        <p class="mb-0">{{ $whoIsWho->publish_remark ?? 'No publish remarks available' }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve who-is-who')
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
                @endcan

                @can('publish who-is-who')
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
                @endcan
            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
    @can('approve who-is-who')
        <script @cspNonce>
            $(function () {
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
                    submitHandler: function (form) {
                        $.ajax({
                            url: "{{ route('who-is-who.approve', $whoIsWho->id) }}",
                            type: "POST",
                            data: new FormData(form),
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('who-is-who.index') }}";
                                });
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
            });
        </script>
    @endcan

    @can('publish who-is-who')
        <script @cspNonce>
            $(function () {
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
                            url: "{{ route('who-is-who.publish', $whoIsWho->id) }}",
                            type: "POST",
                            data: new FormData(form),
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.href = "{{ route('who-is-who.index') }}";
                                });
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
            });
        </script>
    @endcan
@endsection