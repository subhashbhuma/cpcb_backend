@extends('layouts.app_layout')
@section('style')
 <style @cspNonce>
    .checklist-wrapper {
        border-color: #dee2e6 !important;
        overflow: hidden;
    }

    .checklist-item {
        transition: background-color 0.2s;
        padding: 6px 10px;
        border-radius: 4px;
        margin-bottom: 2px;
        display: flex;
        align-items: flex-start;
    }

    .checklist-item:hover {
        background-color: #f8f9fa;
    }

    .checklist-item.selected {
        background-color: #e9ecef;
    }

    #postChecklist::-webkit-scrollbar {
        width: 6px;
    }

    #postChecklist::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
</style>
@endsection
@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>All (<span class="text-danger">*</span>) marked fields are required.</p>

                    <form id="recruitmentAnnouncementForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Type: <span class="text-danger">*</span></label>
                                <select name="type" class="form-control" required>
                                    <!-- <option value="result">Result</option> -->
                                    <option value="notification" selected>Notification</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Job/Vacancy: <span class="text-danger">*</span></label>
                                <select name="job_id" id="job_id" class="form-control select2" required>
                                    <option value="">Select Job</option>
                                    @foreach($jobs as $job)
                                        <option value="{{ $job->id }}">{{ $job->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 col-12 mb-3">
                                <label class="form-label">Select Job Posts: <span class="text-danger">*</span></label>
                                <div class="checklist-wrapper border rounded shadow-sm bg-light">
                                    <div
                                        class="checklist-header d-flex align-items-center justify-content-between p-2 border-bottom bg-white sticky-top">
                                        <div class="input-group input-group-sm w-50">
                                            <span class="input-group-text bg-white border-0"><i
                                                    class="fa fa-search text-muted"></i></span>
                                            <input type="text" id="postSearch" class="form-control border-0 ps-0"
                                                placeholder="Search posts...">
                                        </div>
                                        <div class="form-check me-2">
                                            <input class="form-check-input mx-2" type="checkbox" id="selectAllChecklist">
                                            <label class="form-check-label fw-bold cursor-pointer"
                                                for="selectAllChecklist">Select All</label>
                                        </div>
                                    </div>
                                    <div id="postChecklist" class="p-2" style="max-height: 250px; overflow-y: auto;">
                                        <p class="text-muted text-center my-3">Select a Job/Vacancy first to see available
                                            posts.</p>
                                    </div>
                                </div>
                                <div id="post-selection-error" class="text-danger small mt-1"></div>
                            </div>



                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" required />
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <x-translate-button source="title"
                                        target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control" />
                            </div>


                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (English):</label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf" />
                                <small class="text-muted d-block mt-1">PDF only (Max 50MB)</small>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (हिंदी):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf" />
                                <small class="text-muted d-block mt-1">PDF only (Max 50MB)</small>
                            </div>
                            <div class="col-md-6 col-6 mb-3">
                                <label class="form-label">Issue Date:</label>
                                <input type="text" name="start_date" class="form-control" />
                            </div>
                            <!-- <div class="col-md-6 col-6 mb-3">
                                <label class="form-label">End Date:</label>
                                <input type="text" name="end_date" class="form-control" />
                            </div> -->
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save me-2"></i>
                                    Save</button>
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
                datePickerInit('start_date');
                datePickerInit('end_date');
                $('.select2').select2({
                    width: '100%',
                    placeholder: function () {
                        return $(this).data('placeholder') || 'Select an option';
                    },
                    allowClear: true
                });

                $('#job_id').on('change', function () {
                    const jobId = $(this).val();
                    const $checklist = $('#postChecklist');

                    $checklist.html('<p class="text-muted text-center my-3"><i class="fa fa-spinner fa-spin me-2"></i>Loading posts...</p>');
                    $('#selectAllChecklist').prop('checked', false);
                    $('#postSearch').val('').trigger('input');

                    if (jobId) {
                        $.ajax({
                            url: "{{ route('recruitment-announcements.get-posts') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                job_id: jobId
                            },
                            success: function (response) {
                                $checklist.empty();
                                if (response.success && response.data.length > 0) {
                                    response.data.forEach(post => {
                                        $checklist.append(`
                                                                                            <div class="checklist-item form-check d-flex align-items-start gap-2">
                                                                                                <input class="form-check-input post-checkpoint mt-1" type="checkbox" name="job_post_id[]" value="${post.id}" id="post_${post.id}">
                                                                                                <label class="form-check-label cursor-pointer flex-grow-1" for="post_${post.id}">
                                                                                                    ${post.title}
                                                                                                    <span class="d-none">${post.title_hi || ''}</span>
                                                                                                </label>
                                                                                            </div>
                                                                                        `);
                                    });
                                    // Re-apply search filter if there's an existing value
                                    filterJobPosts();
                                } else {
                                    $checklist.html('<p class="text-muted text-center my-3">No posts found for this job.</p>');
                                }
                            },
                            error: function () {
                                $checklist.html('<p class="text-danger text-center my-3">Failed to load posts.</p>');
                            }
                        });
                    } else {
                        $checklist.html('<p class="text-muted text-center my-3">Select a Job/Vacancy first to see available posts.</p>');
                    }
                });

                // Search filter function for reuse
                function filterJobPosts() {
                    const value = $('#postSearch').val().toLowerCase().trim();
                    const items = $("#postChecklist .checklist-item");

                    if (!value) {
                        items.addClass('d-flex').removeClass('d-none').show();
                        return;
                    }

                    items.each(function () {
                        const labelText = $(this).find('label').text().toLowerCase();
                        const isMatch = labelText.indexOf(value) > -1;

                        if (isMatch) {
                            $(this).addClass('d-flex').removeClass('d-none').show();
                        } else {
                            $(this).addClass('d-none').removeClass('d-flex').hide();
                        }
                    });
                }

                // Bind to multiple events for maximum compatibility
                $(document).on('input keyup change', '#postSearch', filterJobPosts);
                $('#postSearch').on('input keyup change', filterJobPosts);

                // Trigger initial filter
                filterJobPosts();

                // Select All logic
                $('#selectAllChecklist').on('click', function () {
                    const isChecked = $(this).is(':checked');
                    $("#postChecklist .checklist-item:visible .post-checkpoint").prop('checked', isChecked);
                });

                // Individual checkbox change affects "Select All" state
                $(document).on('change', '.post-checkpoint', function () {
                    const total = $('.post-checkpoint:visible').length;
                    const checked = $('.post-checkpoint:visible:checked').length;
                    $('#selectAllChecklist').prop('checked', total > 0 && total === checked);
                });

                $("#recruitmentAnnouncementForm").validate({
                    rules: {
                        job_id: { required: true },
                        "job_post_id[]": { required: true, minlength: 1 },
                        type: { required: true },
                        title: { required: true }
                    },
                    messages: {
                        "job_post_id[]": "Please select at least one post."
                    },
                    errorPlacement: function (error, element) {
                        if (element.attr("name") == "job_post_id[]") {
                            error.appendTo("#post-selection-error");
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    submitHandler: function (form) {
                        let formData = new FormData(form);

                        $.ajax({
                            url: "{{ route('recruitment-announcements.store') }}",
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
                                        window.location.href = "{{ route('recruitment-announcements.index') }}";
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
