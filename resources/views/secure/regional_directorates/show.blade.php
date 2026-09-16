@extends('layouts.app_layout')

@section('content')
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Regional Directorate:</strong></label>
                            <p class="mb-0">{{ $regionalDirectorate->regional_directorate ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Regional Directorate (Hindi):</strong></label>
                            <p class="mb-0">{{ $regionalDirectorate->regional_directorate_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Name of Regional Director:</strong></label>
                            <p class="mb-0">{{ $regionalDirectorate->title }}</p>
                        </div>
                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Name of Regional Director (Hindi):</strong></label>
                            <p class="mb-0">{{ $regionalDirectorate->title_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Designation:</strong></label>
                            <p class="mb-0">{{ $regionalDirectorate->designation }}</p>
                        </div>
                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Designation (Hindi):</strong></label>
                            <p class="mb-0">{{ $regionalDirectorate->designation_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Email:</strong></label>
                            <p class="mb-0">{{ $regionalDirectorate->email ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Order:</strong></label>
                            <p class="mb-0">{{ $regionalDirectorate->order ?? '0' }}</p>
                        </div>
                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Profile Picture:</strong></label>
                            <div>
                                @if ($regionalDirectorate->image)
                                    <img src="{{ asset('storage/' . config('file_paths.REGIONAL_DIRECTORATE_IMAGE_PATH') . '/' . $regionalDirectorate->image) }}"
                                        alt="Profile Picture" class="img-thumbnail" style="max-height: 100px;">
                                @else
                                    <p class="mb-0 text-muted">—</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Description (English):</strong></label>
                            <div class="border p-2 bg-white">
                                {!! $regionalDirectorate->description ?? '—' !!}
                            </div>
                        </div>
                        <div class="col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Description (Hindi):</strong></label>
                            <div class="border p-2 bg-white">
                                {!! $regionalDirectorate->description_hi ?? '—' !!}
                            </div>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created Date:</label>
                            <p class="mb-0">
                                {{ $regionalDirectorate->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Created By</label>
                            <p class="mb-0">
                                {{ $regionalDirectorate->created_by_user ? $regionalDirectorate->created_by_user->name : 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated Date:</label>
                            <p class="mb-0">
                                {{ $regionalDirectorate->updated_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 col-12 mb-3 card py-2 bg-light">
                            <label class="form-label">Updated By</label>
                            <p class="mb-0">
                                {{ $regionalDirectorate->updated_by_user ? $regionalDirectorate->updated_by_user->name : 'N/A' }}
                            </p>
                        </div>

                        <x-office-items-show :personnels="$regionalDirectorate->personnels" :profileActivities="$regionalDirectorate->profileActivities" :states="$regionalDirectorate->states" />

                    </div>
                </div>
            </div>

            <hr>


            <div class="card card-body">
                <div class="row">
                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Approval Status:</strong></label>
                        <p class="mb-0">{!! $regionalDirectorate->is_approved_desc !!}</p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Status:</strong></label>
                        <p class="mb-0">
                            <span class="badge {{ $regionalDirectorate->is_published ? 'bg-success' : 'bg-secondary' }}">
                                {{ $regionalDirectorate->is_published_desc }}
                            </span>
                        </p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Remarks:</strong></label>
                        <p class="mb-0">{{ $regionalDirectorate->remarks ?? 'No remarks available' }}</p>
                    </div>

                    <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                        <label class="form-label"><strong>Publish Remark:</strong></label>
                        <p class="mb-0">{{ $regionalDirectorate->publish_remark ?? 'No publish remarks available' }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                @can('approve regional directorate')
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
                                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i>
                                            Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('publish regional directorate')
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
                                        <button type="submit" class="btn btn-success"><i class="fa fa-upload"></i>
                                            Submit</button>
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
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('regional_directorates.approve', $regionalDirectorate->id) }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            Swal.fire("Success!", response.message, "success").then(() => {
                                window.location.href = "{{ route('regional_directorates.index') }}";
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
                        url: "{{ route('regional_directorates.publish', $regionalDirectorate->id) }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            Swal.fire("Success!", response.message, "success").then(() => {
                                window.location.href = "{{ route('regional_directorates.index') }}";
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
@endsection