@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>All (<span class="text-danger">*</span>) marked fields are required.</p>

                    <form id="jobPostForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-12 mb-3">
                                <label class="form-label">Job/Vacancy: <span class="text-danger">*</span></label>
                                <select name="job_id" class="form-control select2" required>
                                    <option value="">Select Job</option>
                                    @foreach($jobs as $job)
                                        <option value="{{ $job->id }}">{{ $job->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Post Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control"
                                    placeholder="Enter post title in English" required />
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Post Title (Hindi): <x-translate-button source="title"
                                        target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control"
                                    placeholder="Enter post title in Hindi" />
                            </div>


                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select an option',
                allowClear: true
            });

            $("#jobPostForm").validate({
                rules: {
                    job_id: { required: true },
                    title: { required: true }
                },
                submitHandler: function (form) {
                    let formData = $(form).serialize();

                    $.ajax({
                        url: "{{ route('job-posts.store') }}",
                        method: "POST",
                        data: formData,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href = "{{ route('job-posts.index') }}";
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessages = Object.values(errors).flat().join("<br>");
                                Swal.fire({
                                    title: "Validation Error",
                                    html: errorMessages,
                                    icon: "error"
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Something went wrong. Please try again.",
                                    icon: "error"
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection