<!-- resources/views/secure/pages/create.blade.php -->

@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>
                        All (<span class="text-danger">*</span>) marked fields are mandatory.
                    </p>
                    <form id="pageForm" action="" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (हिंदी): <span class="text-danger">*</span>
                                    <x-translate-button source="title" target="title_hi" /></label>

                                <input type="text" name="title_hi" class="form-control" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Page Type: <span class="text-danger">*</span></label>
                                <select name="type" id="page_type" class="form-control select2">
                                    <option value="website">Website Page</option>
                                    <option value="employee">Employee Page</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Menu:</label>
                                <select name="menu_id" id="menu_id" class="form-control select2">
                                    <option value="">Select Menu</option>
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->id }}" data-location="{{ $menu->location_code }}">
                                            {{ $menu->location_name ? '[' . \Illuminate\Support\Str::headline($menu->location_name) . '] ' : '' }}{!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', strlen($menu->dashes)) !!}{{ $menu->sl_no }}.
                                            {{ $menu->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3" id="default-sidebar-wrapper">
                                <label class="form-label">Default Sidebar Menu:</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="default_menu" id="default_menu"
                                        value="1">
                                    <label class="form-check-label" for="default_menu">
                                        Enable default sidebar menu instead of menu-based sidebar
                                    </label>
                                </div>
                                <small class="text-muted">When enabled, this page will display menus from the "Default"
                                    location in the sidebar.</small>
                            </div>
                        </div>



                        <div class="col-md-12 col-12 mb-3">
                            <ul class="nav nav-tabs mb-3 page-creation-tab" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                                        aria-controls="home" aria-selected="true"><i class="ti ti-file"></i> Page
                                        Content</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                                        aria-controls="profile" aria-selected="false"><i class="fa fa-file-pdf"></i>
                                        Files</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="mb-3">
                                        <label class="form-label">Content (English):</label>
                                        <textarea name="content" class="form-control" id="page-editor"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Content (हिंदी): <x-translate-button source="content"
                                                target="content_hi" /></label>
                                        <textarea name="content_hi" class="form-control" id="hi-page-editor"></textarea>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="mb-3">
                                        <h6>
                                            You can multiple files in the page. Click on the below button to add files in
                                            the page.
                                        </h6>
                                        <div class="d-flex gap-2 flex-wrap mb-2">
                                            <button class="btn btn-success btn-add-page-file">
                                                <i class="fa fa-plus"></i> <span>Add Files Row</span>
                                            </button>
                                            <a href="{{ route('pages.files.import.template') }}" class="btn btn-info">
                                                <i class="fa fa-download"></i> <span>Export Excel</span>
                                            </a>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importFilesModal">
                                                <i class="fa fa-upload"></i> <span>Import Excel</span>
                                            </button>
                                        </div>
                                    <div id="page-files-wrapper">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Create Page</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('components.page_files_template')

    <!-- Import Files Modal -->
    <div class="modal fade" id="importFilesModal" tabindex="-1" aria-labelledby="importFilesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importFilesModalLabel">Import Page Files Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Note:</strong> The Excel file will be imported <strong>after</strong> the page is created.
                        <ul class="mb-0 mt-1">
                            <li>All rows will be <strong>added as new</strong> records to the page.</li>
                            <li>The <code>id</code> column can be left empty (IDs are auto-generated).</li>
                            <li>Actual PDF files are <strong>not</strong> uploaded via this import.</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <label for="import_file" class="form-label">Select Excel File (.xlsx, .xls, .csv)</label>
                        <input class="form-control" type="file" id="import_file" name="import_file" accept=".xlsx, .xls, .csv">
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <p class="mb-0 text-muted"><strong>Required Headers:</strong></p>
                            <a href="{{ route('pages.files.import.template') }}" class="btn btn-sm btn-outline-success">
                                <i class="fa fa-file-excel"></i> Download Blank Template
                            </a>
                        </div>
                        <ul class="text-muted small mb-0">
                            <li><code>id</code> (leave empty for new records)</li>
                            <li><code>title</code> (required)</li>
                            <li><code>title_hi</code> (optional)</li>
                            <li><code>upload_date</code> (optional, format: YYYY-MM-DD)</li>
                            <li><code>order_number</code> (optional, integer)</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmImportBtn">
                        <i class="fa fa-check"></i> Attach for Import
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
    <script @cspNonce>
        let fileCounter = 1;
        let fileCountArray = [];
        let pendingImportFile = null; // Store the Excel file to import after page creation

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
            function filterMenus() {
                var selectedType = $('#page_type').val(); // 'website' or 'employee'
                
                // Destroy select2 temporarily to manipulate options
                if ($('#menu_id').data('select2')) {
                    $('#menu_id').select2('destroy');
                }
                
                $('#menu_id').empty();
                
                allMenuOptions.each(function() {
                    var location = $(this).data('location');
                    // Add "Select Menu" placeholder
                    if (!location) {
                        $('#menu_id').append($(this).clone());
                        return;
                    }
                    
                    if (selectedType === 'employee') {
                        // Only show employee menus
                        if (location === 'employee') {
                            $('#menu_id').append($(this).clone());
                        }
                    } else {
                        // Show website menus (non-employee)
                        if (location !== 'employee') {
                            $('#menu_id').append($(this).clone());
                        }
                    }
                });
                
                // Toggle default sidebar menu visibility
                if (selectedType === 'employee') {
                    $('#default-sidebar-wrapper').hide();
                    $('#default_menu').prop('checked', false); // Optional: Uncheck it when hidden
                } else {
                    $('#default-sidebar-wrapper').show();
                }
                
                // Re-initialize select2
                $('#menu_id').select2({ width: '100%', allowClear: true, placeholder: 'Select Menu' });
            }

            // Run on load
            filterMenus();

            // Run on change
            $('#page_type').on('change', function() {
                filterMenus();
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

            // Import Excel: Attach file for import after creation
            $('#confirmImportBtn').on('click', function () {
                let fileInput = document.getElementById('import_file');
                if (fileInput.files.length > 0) {
                    pendingImportFile = fileInput.files[0];
                    toastr.success('Excel file attached. It will be imported after the page is created.');
                } else {
                    pendingImportFile = null;
                    toastr.warning('No file selected.');
                }
                $('#importFilesModal').modal('hide');
            });
        })

        /**
         * Import Excel file to a newly created page.
         */
        function importExcelToPage(pageId) {
            if (!pendingImportFile) return Promise.resolve();

            let importFormData = new FormData();
            importFormData.append('import_file', pendingImportFile);

            return $.ajax({
                url: "{{ url('secure/pages') }}/" + pageId + "/files/import",
                type: "POST",
                data: importFormData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        $(document).ready(function () {
            // Initialize jQuery Validation
            $('#pageForm').validate({
                rules: {
                    type: {
                        required: true,
                    },
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    title_hi: {
                        required: true,
                        maxlength: 255
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

                    $.ajax({
                        url: "{{ route('pages.store') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.success) {
                                // If an Excel file is pending, import it now
                                if (pendingImportFile && response.page_id) {
                                    importExcelToPage(response.page_id).then(function (importResponse) {
                                        let msg = response.message;
                                        if (importResponse && importResponse.message) {
                                            msg += ' ' + importResponse.message;
                                        }
                                        Swal.fire({
                                            title: "Success!",
                                            text: msg,
                                            icon: "success",
                                            confirmButtonText: "OK"
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    }).catch(function (xhr) {
                                        Swal.fire({
                                            title: "Page Created!",
                                            html: response.message + '<br><br><span class="text-warning">Excel import failed: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error') + '</span>',
                                            icon: "warning",
                                            confirmButtonText: "OK"
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Success!",
                                        text: response.message,
                                        icon: "success",
                                        confirmButtonText: "OK"
                                    }).then(() => {
                                        // window.location.href = response.redirect_url;
                                        window.location.reload();
                                    });
                                }
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
    </script>
@endsection