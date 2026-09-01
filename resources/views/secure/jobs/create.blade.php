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

                    <form id="jobForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <div class="col-md-12 col-12 mb-3">
                                <ul class="nav nav-tabs mb-3" id="jobTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="details-tab" data-bs-toggle="tab" href="#details"
                                            role="tab"><i class="ti ti-file"></i> Job Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="posts-tab" data-bs-toggle="tab" href="#posts" role="tab"><i
                                                class="fa fa-briefcase"></i> Posts</a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3" id="jobTabContent">
                                    <div class="tab-pane fade show active" id="details" role="tabpanel">

                                        <div class="row">
                                            <!-- Basic Details -->
                                            <div class="col-md-4 col-12 mb-3">
                                                <label class="form-label">Job Type: <span
                                                        class="text-danger">*</span></label>
                                                <select name="job_type" id="job_type" class="form-control" required>
                                                    <option value="regular">Regular</option>
                                                    <option value="contract">Contract</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-12 mb-3">
                                                <label class="form-label">Title (English): <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="title" class="form-control"
                                                    placeholder="Enter Job Title in English" required />
                                            </div>
                                            <div class="col-md-4 col-12 mb-3">
                                                <label class="form-label">Title (Hindi): <span
                                                        class="text-danger">*</span> <x-translate-button source="title"
                                                        target="title_hi" /></label>
                                                <input type="text" name="title_hi" id="title_hi" class="form-control"
                                                    placeholder="हिंदी में पद का नाम दर्ज करें" />
                                            </div>

                                            <!-- Dates -->
                                            <div class="col-md-4 col-12 mb-3">
                                                <label class="form-label">Start Date: <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="start_date" class="form-control" />
                                            </div>
                                            <div class="col-md-4 col-12 mb-3">
                                                <label class="form-label">End Date: <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="end_date" class="form-control" />
                                            </div>
                                            <div id="walk_in_section" class="col-md-4 col-12 mb-3" style="display: none;">
                                                <label class="form-label text-primary fw-bold">Walk-in Interview Date: <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="walk_in_interview_date"
                                                    class="form-control border-primary" />
                                            </div>

                                            <div class="col-12 regular_section">
                                                <hr class="my-2">
                                            </div>

                                            <!-- Conditional Application Fields (Regular) -->
                                            <div class="col-md-6 col-12 mb-3 regular_section">
                                                <label class="form-label">Direct Application Type:</label>
                                                <select name="direct_application" id="direct_application"
                                                    class="form-control">
                                                    <option value="offline">Offline (File)</option>
                                                    <option value="online">Online (URL)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-12 mb-3 regular_section deputation_section">
                                                <label class="form-label">Deputation Application Type:</label>
                                                <select name="deputation_application" id="deputation_application"
                                                    class="form-control">
                                                    <option value="offline">Offline (File)</option>
                                                    <option value="online">Online (URL)</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 col-12 mb-3 regular_section direct_url_section"
                                                style="display: none;">
                                                <label class="form-label">Direct Application URL:</label>
                                                <input type="url" name="direct_application_url" class="form-control"
                                                    placeholder="https://example.com/apply" />
                                            </div>
                                            <div class="col-md-6 col-12 mb-3 regular_section deputation_url_section"
                                                style="display: none;">
                                                <label class="form-label">Deputation Application URL:</label>
                                                <input type="url" name="deputation_application_url" class="form-control"
                                                    placeholder="https://example.com/apply-deputation" />
                                            </div>

                                            <!-- Conditional Application Fields (Contract) -->
                                            <div class="col-md-12 col-12 mb-3 contract_section" style="display: none;">
                                                <label class="form-label text-primary fw-bold">Online Application
                                                    URL:</label>
                                                <input type="url" name="online_form_url" class="form-control border-primary"
                                                    placeholder="https://example.com/apply" />
                                            </div>

                                            <!-- Removed toggles as requested -->

                                            <!-- Advertisement Uploads -->
                                            <div id="advertisement_section_wrapper" class="col-12">
                                                <div class="row">
                                                    <div class="col-md-6 col-12 mb-3">
                                                        <label class="form-label">Advertisement (English): <span
                                                                class="text-danger">*</span></label>
                                                        <input type="file" name="advertisement_file_name"
                                                            class="form-control" accept=".pdf" />
                                                        <small class="text-muted">PDF only. Max 50MB</small>
                                                    </div>
                                                    <div class="col-md-6 col-12 mb-3">
                                                        <label class="form-label">Advertisement (Hindi): </label>
                                                        <input type="file" name="advertisement_file_hi_name"
                                                            class="form-control" accept=".pdf" />
                                                        <small class="text-muted">PDF only. Max 50MB</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Direct Form Uploads -->
                                            <div id="direct_application_section_wrapper" class="col-12"
                                                style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6 col-12 mb-3">
                                                        <label class="form-label">Direct Form (English):</label>
                                                        <input type="file" name="direct_application_form_name"
                                                            class="form-control" accept=".pdf" />
                                                        <small class="text-muted">PDF only. Max 50MB</small>
                                                    </div>
                                                    <div class="col-md-6 col-12 mb-3">
                                                        <label class="form-label">Direct Form (Hindi):</label>
                                                        <input type="file" name="direct_application_form_hi_name"
                                                            class="form-control" accept=".pdf" />
                                                        <small class="text-muted">PDF only. Max 50MB</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Deputation Form Uploads -->
                                            <div id="deputation_application_section_wrapper"
                                                class="col-12 deputation_section" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6 col-12 mb-3">
                                                        <label class="form-label">Deputation Form (English):</label>
                                                        <input type="file" name="deputation_application_form_name"
                                                            class="form-control" accept=".pdf" />
                                                        <small class="text-muted">PDF only. Max 50MB</small>
                                                    </div>
                                                    <div class="col-md-6 col-12 mb-3">
                                                        <label class="form-label">Deputation Form (Hindi):</label>
                                                        <input type="file" name="deputation_application_form_hi_name"
                                                            class="form-control" accept=".pdf" />
                                                        <small class="text-muted">PDF only. Max 50MB</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="posts" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="text-primary mb-0"><i class="fa fa-users me-2"></i> Designated Posts
                                            </h6>
                                            <button type="button" class="btn btn-outline-success btn-sm btn-add-post">
                                                <i class="fa fa-plus-circle me-1"></i> Add Post
                                            </button>
                                        </div>
                                        <div id="posts-wrapper" class="row"></div>
                                    </div>
                                </div>
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
    @include('components.job_posts_template')
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {
            datePickerInit('start_date');
            datePickerInit('end_date');
            datePickerInit('walk_in_interview_date');
            let postCounter = 0;

            function addPostRow() {
                const template = $('#jobPostTemplate').html();
                const html = template.replace(/__index__/g, postCounter);
                $('#posts-wrapper').append(html);
                postCounter++;
            }

            $('.btn-add-post').click(function () {
                addPostRow();
            });

            $(document).on('click', '.btn-remove-post', function () {
                $(this).closest('.job-post-row').remove();
            });

            // Initialize with one row
            addPostRow();

            function toggleSections() {
                let jobType = $('#job_type').val();
                let directAppType = $('#direct_application').val();
                let deputationAppType = $('#deputation_application').val();

                // Always show advertisement by default, or manage based on job type if needed
                $('#advertisement_section_wrapper').show();

                if (jobType === 'contract') {
                    // Show contract fields
                    $('.contract_section').show();
                    $('#walk_in_section').show();

                    // Hide regular fields
                    $('.regular_section').hide();
                    $('.deputation_section').hide();
                    $('#direct_application_section_wrapper').hide();
                    $('#deputation_application_section_wrapper').hide();
                    $('.direct_url_section').hide();
                    $('.deputation_url_section').hide();
                } else {
                    // Show regular fields
                    $('.contract_section').hide();
                    $('#walk_in_section').hide();

                    $('.regular_section').show();
                    $('.deputation_section').show();

                    // Direct Application Toggle (Regular Only)
                    if (directAppType === 'online') {
                        $('.direct_url_section').show();
                        $('#direct_application_section_wrapper').hide();
                    } else {
                        $('.direct_url_section').hide();
                        $('#direct_application_section_wrapper').show();
                    }

                    // Deputation Application Toggle (Regular Only)
                    if (deputationAppType === 'online') {
                        $('.deputation_url_section').show();
                        $('#deputation_application_section_wrapper').hide();
                    } else {
                        $('.deputation_url_section').hide();
                        $('#deputation_application_section_wrapper').show();
                    }
                }
            }

            $('#job_type, #direct_application, #deputation_application').change(function () {
                toggleSections();
            });
            toggleSections();

            $("#jobForm").validate({
                rules: {
                    job_type: { required: true },
                    title: { required: true },
                    title_hi: { required: true },
                    start_date: { required: true },
                    advertisement_file_name: {
                        required: function () { return $('#has_advertisement').is(':checked'); }
                    },
                    walk_in_interview_date: {
                        required: function () {
                            return $('#job_type').val() === 'contract';
                        }
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('jobs.store') }}",
                        method: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href = "{{ route('jobs.index') }}";
                                });
                            } else {
                                console.log('response', response);

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
