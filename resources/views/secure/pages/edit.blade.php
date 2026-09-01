@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="Edit Page" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>All (<span class="text-danger">*</span>) marked fields are mandatory.</p>
                    <form id="pageForm" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-12  mb-3">
                                <label class="form-label">Title (English):<span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ old('title', $page->title) }}"
                                    class="form-control" />
                            </div>

                            <div class="col-md-6 col-12  mb-3">
                                <label class="form-label">Title (हिंदी): <span class="text-danger">*</span>
                                    <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" value="{{ old('title_hi', $page->title_hi) }}"
                                    class="form-control" />
                            </div>
                            <!-- <div class="col-md-6 col-12 mb-3">
                                                                                                            <label class="form-label">Featured Image:</label>
                                                                                                            <input type="file" name="featured_image" class="form-control" accept="image/*" />
                                                                                                            @if ($page->featured_image)
                                                                                                                <div class="mt-2">
                                                                                                                    <strong class="me-2">Current:</strong>
                                                                                                                    <a href="{{ asset('storage/' . Config::get('file_paths')['PAGE_FEATURED_IMAGE_PATH'] . '/' . $page->featured_image) }}"
                                                                                                                        target="_blank">
                                                                                                                        <img src="{{ asset('storage/' . Config::get('file_paths')['PAGE_FEATURED_IMAGE_PATH'] . '/' . $page->featured_image) }}"
                                                                                                                            alt="Featured Image" class="img-thumbnail show-preview-image" />
                                                                                                                    </a>
                                                                                                                </div>
                                                                                                            @endif
                                                                                                        </div> -->

                            <div class="col-md-6 col-12  mb-3">
                                <label class="form-label">Page Type: <span class="text-danger">*</span></label>
                                <select name="type" id="page_type" class="form-control select2">
                                    <option value="website" {{ old('type', $page->type) == 'website' ? 'selected' : '' }}>Website Page</option>
                                    <option value="employee" {{ old('type', $page->type) == 'employee' ? 'selected' : '' }}>Employee Page</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-12  mb-3">
                                <label class="form-label">Menu:</label>
                                <select name="menu_id" id="menu_id" class="form-control select2">
                                    <option value="">Select Menu</option>
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->id }}" data-location="{{ $menu->location_code }}" {{ $page->menu_id == $menu->id ? 'selected' : '' }}>
                                            {{ $menu->location_name ? '[' . \Illuminate\Support\Str::headline($menu->location_name) . '] ' : '' }}{!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', strlen($menu->dashes)) !!}{{ $menu->sl_no }}.
                                            {{ $menu->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3" id="default-sidebar-wrapper">
                                <label class="form-label">Default Sidebar Menu:</label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="default_menu" value="0">
                                    <input class="form-check-input" type="checkbox" name="default_menu" id="default_menu"
                                        value="1" {{ old('default_menu', $page->default_menu) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="default_menu">
                                        Enable default sidebar menu instead of menu-based sidebar
                                    </label>
                                </div>
                                <small class="text-muted">When enabled, this page will display menus from the "Default"
                                    location in the sidebar.</small>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Last Updated:</label>
                                <input type="datetime-local" name="updated_at"
                                    value="{{ $page->updated_at ? $page->updated_at->format('Y-m-d\TH:i') : '' }}"
                                    class="form-control" />
                            </div>

                            <div class="col-md-12 col-12 mb-3">
                                <ul class="nav nav-tabs mb-3 page-creation-tab" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home"
                                            role="tab" aria-controls="home" aria-selected="true"><i class="ti ti-file"></i>
                                            Page Content</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                                            aria-controls="profile" aria-selected="false"><i class="fa fa-file-pdf"></i> PDF
                                            Content</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                        aria-labelledby="home-tab">
                                        <div class="mb-3">
                                            <label class="form-label">Content (English):</label>
                                            <textarea name="content" class="form-control" id="page-editor"
                                                contenteditable="true">{!! $page->content !!}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Content (हिंदी): <x-translate-button source="content"
                                                    target="content_hi" /></label>
                                            <textarea name="content_hi" class="form-control" id="hi-page-editor"
                                                contenteditable="true">{!! $page->content_hi !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <div class="mb-3">
                                            <h6>
                                                You can multiple files in the page. Click on the below button to add files
                                                in the page.
                                            </h6>
                                            <div class="d-flex gap-2 flex-wrap mb-2">
                                                <button class="btn btn-success btn-add-page-file">
                                                    <i class="fa fa-plus"></i> <span>Add Files Row</span>
                                                </button>
                                                <a href="{{ route('pages.files.export', $page->id) }}" class="btn btn-info">
                                                    <i class="fa fa-download"></i> <span>Export Excel</span>
                                                </a>
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importFilesModal">
                                                        <i class="fa fa-upload"></i> <span>Import Excel</span>
                                                    </button>
                                            </div>
                                        </div>
                                        <div id="page-files-wrapper">
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th width="8%">S.No.</th>
                                                        <th width="20%">File</th>
                                                        <th width="20%">Title</th>
                                                        <th width="15%">Upload Date</th>
                                                        <th width="8%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($page->files->count() > 0)
                                                        @foreach ($page->files as $key => $file)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>
                                                                    <a href="{{ generate_file_view_path_for_backend($file->file_path) }}"
                                                                        target="_BLANK">
                                                                        <i class="fa fa-eye"></i> View File (English)
                                                                    </a>
                                                                    <br> <br>
                                                                    @if($file->file_path_hi)
                                                                    <a href="{{ generate_file_view_path_for_backend($file->file_path_hi) }}"
                                                                        target="_BLANK">
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
                                                                <td>
                                                                    {{ $file->upload_date?date('d-m-Y', strtotime($file->upload_date)) : 'N/A' }}
                                                                </td>
                                                                <td>
                                                                    <div
                                                                        class="d-flex gap-2 justify-content-center align-items-center">
                                                                        <button class="btn btn-warning btn-edit-page-file"
                                                                            type="button" data-id="{{ $file->id }}"
                                                                            data-upload_date="{{ $file->upload_date }}"
                                                                            data-title="{{ $file->title }}"
                                                                            data-title_hi="{{ $file->title_hi }}"
                                                                            data-order_number="{{ $file->order_number ?? 0 }}">
                                                                            <i class="fa fa-edit"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger btn-delete-page-file"
                                                                            type="button" data-id="{{ $file->id }}">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="5">No data available</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update
                                Page</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @include('components.page_files_template')

    <!-- Edit File Modal -->
    <div class="modal fade" id="editFileModal" tabindex="-1" aria-labelledby="editFileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFileModalLabel">Update File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editFileForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="file_id" id="edit_file_id">

                        <div class="row">
                            <!-- File Uploads -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Replace File (English)</label>
                                <input type="file" class="form-control" name="file_name" accept=".pdf,.jpg,.png,.jpeg">
                                <small class="text-muted">Leave empty to keep existing file</small>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Replace File (Hindi)</label>
                                <input type="file" class="form-control" name="file_name_hi" accept=".pdf,.jpg,.png,.jpeg">
                                <small class="text-muted">Leave empty to keep existing file</small>
                            </div>

                            <!-- Titles -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" id="edit_title">
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi) <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" class="form-control" name="title_hi" id="edit_title_hi">
                            </div>

                            <!-- Upload Date -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Upload Date</label>
                                <input type="date" class="form-control" name="upload_date" id="edit_upload_date">
                            </div>

                            <!-- Order Number -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Order Number</label>
                                <input type="number" class="form-control" name="order_number" id="edit_order_number" min="0"
                                    value="0">
                                <small class="text-muted">Lower numbers appear first</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update File</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Files Modal -->
    <div class="modal fade" id="importFilesModal" tabindex="-1" aria-labelledby="importFilesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="importFilesForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importFilesModalLabel">Import Page Files Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Note:</strong> This import will update metadata (title, title_hi, upload_date, order_number).
                            <ul class="mb-0 mt-1">
                                <li>Rows <strong>with an ID</strong> will <strong>update</strong> the existing record.</li>
                                <li>Rows <strong>without an ID</strong> (leave the id column empty) will be <strong>added as new</strong> records.</li>
                                <li>Actual PDF files are <strong>not</strong> uploaded or replaced via this import.</li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="import_file" class="form-label">Select Excel File (.xlsx, .xls, .csv)</label>
                            <input class="form-control" type="file" id="import_file" name="import_file" accept=".xlsx, .xls, .csv" required>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <p class="mb-0 text-muted"><strong>Required Headers:</strong></p>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('pages.files.import.template') }}" class="btn btn-sm btn-outline-success">
                                        <i class="fa fa-file-excel"></i> Download Blank Template
                                    </a>
                                    @if ($page->files->count() > 0)
                                        <a href="{{ route('pages.files.export', $page->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-download"></i> Download Current Data
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <ul class="text-muted small mb-0">
                                <li><code>id</code> (leave empty to add as new record, provide ID to update existing)</li>
                                <li><code>title</code> (required for new records)</li>
                                <li><code>title_hi</code> (optional)</li>
                                <li><code>upload_date</code> (optional, format: YYYY-MM-DD)</li>
                                <li><code>order_number</code> (optional, integer)</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="importFilesSubmitBtn">
                            <i class="fa fa-upload"></i> Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
    <script @cspNonce>
        let fileCounter = 1;
        let fileCountArray = [];

        function generateFileRow() {
            const template = document.getElementById("fileFieldsetTemplate").innerHTML;
            const html = template.replace(/__index__/g, fileCounter);
            fileCountArray.push(fileCounter);
            fileCounter++;
            $('#page-files-wrapper').append(html);
        }

        $(document).ready(function () {
            $('.select2').select2({
                // theme: 'bootstrap-4',
                width: '100%',
                placeholder: function () {
                    return $(this).data('placeholder') || 'Select an option';
                },
                allowClear: true
            });
            // generateFileRow();

            // Menu filtering based on Page Type
            var allMenuOptions = $('#menu_id option').clone();
            function filterMenus(isInitialLoad) {
                var selectedType = $('#page_type').val(); // 'website' or 'employee'
                
                // Destroy select2 temporarily to manipulate options
                if ($('#menu_id').data('select2')) {
                    $('#menu_id').select2('destroy');
                }
                
                var currentSelectedVal = isInitialLoad ? $('#menu_id').val() : '';
                $('#menu_id').empty();
                
                allMenuOptions.each(function() {
                    var location = $(this).data('location');
                    var clone = $(this).clone();
                    
                    if (!isInitialLoad) {
                        clone.prop('selected', false);
                    }
                    
                    // Add "Select Menu" placeholder
                    if (!location) {
                        $('#menu_id').append(clone);
                        return;
                    }
                    
                    if (selectedType === 'employee') {
                        // Only show employee menus
                        if (location === 'employee') {
                            $('#menu_id').append(clone);
                        }
                    } else {
                        // Show website menus (non-employee)
                        if (location !== 'employee') {
                            $('#menu_id').append(clone);
                        }
                    }
                });
                
                // Toggle default sidebar menu visibility
                if (selectedType === 'employee') {
                    $('#default-sidebar-wrapper').hide();
                    if (!isInitialLoad) {
                        $('#default_menu').prop('checked', false); // Only auto-uncheck when manually changed
                    }
                } else {
                    $('#default-sidebar-wrapper').show();
                }
                
                // Re-initialize select2
                $('#menu_id').select2({ width: '100%', allowClear: true, placeholder: 'Select Menu' });
            }

            // Run on load
            filterMenus(true);

            // Run on change
            $('#page_type').on('change', function() {
                filterMenus(false);
                $('#menu_id').val('').trigger('change');
            });

            // Add new PDF fieldset
            $(document).on('click', '.btn-add-page-file', function (e) {
                e.preventDefault();
                generateFileRow();
            });

            // Remove PDF fieldset
            $('#page-files-wrapper').on('click', '.btn-remove-page-file', function (e) {
                e.preventDefault();
                let id = $(this).attr('id');
                $('#fieldset-' + id).remove();
                fileCountArray = fileCountArray.filter((count, index) => {
                    return count !== parseInt(id);
                });
            });

            /**
             * Delete record
             */
            $(document).on('click', '.btn-delete-page-file', function () {
                let id = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to delete the record.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('pages.files.destroy', ':id') }}".replace(':id',
                                id),
                            type: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function () {
                                showLoader();
                            },
                            complete: function () {
                                hideLoader();
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function (xhr) {
                                hideLoader();
                                Swal.fire("Error!", "Something went wrong!", "error");
                            }
                        });
                    }
                });
            });
        })

        $(document).ready(function () {
            $('#pageForm').validate({
                rules: {
                    type: {
                        required: true,
                    },
                    title: {
                        required: true,
                    },
                    title_hi: {
                        required: true,
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(document.getElementById('pageForm'))
                    // Loop through each editor instance to add its data to FormData
                    window.editors.forEach(({
                        editor,
                        name,
                        id
                    }) => {
                        if (id == 'page-editor') {
                            const editorContent = editor.getData();
                            formData.set(name, editorContent);
                        }
                        if (id == 'hi-page-editor') {
                            const editorContent = editor.getData();
                            formData.set(name, editorContent);
                        }
                    });

                    // Append the file count array to handle the multiple saving in backend
                    formData.append('fileCountArray', JSON.stringify(fileCountArray));

                    // Submit the ajax request
                    $.ajax({
                        url: "{{ route('pages.update', $page->id) }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            showLoader();
                        },
                        complete: function () {
                            hideLoader();
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Updated!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    // window.location.href = response.redirect_url;
                                    window.location.reload();
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessages = Object.values(errors).flat().join(
                                    "<br>");
                                Swal.fire({
                                    title: "Validation Error",
                                    html: errorMessages,
                                    icon: "error"
                                });
                            } else {
                                let errorMsg = "Something went wrong. Please try again.";
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    title: "Error!",
                                    text: errorMsg,
                                    icon: "error"
                                });
                            }
                        }
                    });
                }
            });
        });

        $(document).ready(function () {
            // Edit File Logic
            $(document).on('click', '.btn-edit-page-file', function () {
                let id = $(this).data('id');
                let upload_date = $(this).data('upload_date');
                let title = $(this).data('title');
                let title_hi = $(this).data('title_hi');
                let order_number = $(this).data('order_number') || 0;

                $('#edit_file_id').val(id);
                $('#edit_upload_date').val(upload_date);
                $('#edit_title').val(title);
                $('#edit_title_hi').val(title_hi);
                $('#edit_order_number').val(order_number);

                $('#editFileModal').modal('show');
            });

            $('#editFileForm').on('submit', function (e) {
                e.preventDefault();
                let id = $('#edit_file_id').val();
                let formData = new FormData(this);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ route('pages.files.update', ':id') }}".replace(':id', id),
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function () {
                        showLoader();
                    },
                    complete: function () {
                        hideLoader();
                    },
                    success: function (response) {
                        hideLoader();
                        $('#editFileModal').modal('hide');
                        if (response.success) {
                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = Object.values(errors).flat().join(
                                "<br>");
                            Swal.fire({
                                title: "Validation Error",
                                html: errorMessages,
                                icon: "error"
                            });
                        } else {
                            let errorMsg = "Something went wrong. Please try again.";
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                title: "Error!",
                                text: errorMsg,
                                icon: "error"
                            });
                        }
                    }
                });
            });
        });

        /**
         * IMPORT FILES EXCEL
         */
        $(document).ready(function () {
            $('#importFilesForm').on('submit', function (e) {
                e.preventDefault();
                let form = this;
                let formData = new FormData(form);
                let submitBtn = $('#importFilesSubmitBtn');
                let originalText = submitBtn.html();

                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Importing...').prop('disabled', true);

                $.ajax({
                    url: "{{ route('pages.files.import', $page->id) }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        submitBtn.html(originalText).prop('disabled', false);
                        $('#importFilesModal').modal('hide');
                        if (response.success) {
                            Swal.fire({
                                title: "Imported!",
                                text: response.message,
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        submitBtn.html(originalText).prop('disabled', false);
                        let message = 'An error occurred during import.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        Swal.fire({
                            title: "Import Error",
                            html: message,
                            icon: "error"
                        });
                    }
                });
            });
        });
    </script>
@endsection
