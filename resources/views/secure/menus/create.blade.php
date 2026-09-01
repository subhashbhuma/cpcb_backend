@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />

    <!-- [ Page Header ] end -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST" id="menuForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="title">Title (English)<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="title" id="title" class="form-control">
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="title_hi">Title (हिंदी) <span class="text-danger">*</span> <x-translate-button source="title"
                                                        target="title_hi" />
                                </label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control">
                            </div>


                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="type">Menu Type <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control" required>
                                    @foreach (Config::get('constants.MENU_TYPE') as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-12" id="url_content">
                                <label class="form-label" for="url">URL</label>
                                <input type="text" name="url" id="url" class="form-control">
                                <small class="text-muted"><i class="fas fa-info-circle"></i> Leave blank to auto-generate from title (e.g. <em>/my-menu-title</em> or <em>/parent/my-menu-title</em>)</small>
                            </div>
                            <div class="row m-0 p-0 d-none" id="file_content">
                                <div class="form-group col-md-6 col-12">
                                    <label class="form-label">File (English): <span class="text-danger">*</span></label>
                                    <input type="file" name="file_name" class="form-control" accept=".pdf" required />
                                    <small class="text-muted">Allowed types: pdf. Max: 50MB</small>
                                </div>

                                <div class="form-group col-md-6 col-12">
                                    <label class="form-label">File (Hindi): <span class="text-danger">*</span></label>
                                    <input type="file" name="file_name_hi" class="form-control" accept=".pdf" required />
                                    <small class="text-muted">Allowed types: pdf. Max: 50MB</small>
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="icon_type">Icon Type</label>
                                <select name="icon_type" id="icon_type" class="form-control">
                                    <option value="ICON" selected>Icon (CSS Class)</option>
                                    <option value="IMAGE">Image (Upload)</option>
                                </select>
                            </div>

                            {{-- Icon CSS class input (shown when icon_type = ICON) --}}
                            <div class="form-group col-md-6 col-12" id="icon_text_content">
                                <label class="form-label" for="icon_png_text"> Lucide Icon Name    <span>
        <button type="button"
            class="btn btn-sm btn-outline-primary"
            data-bs-toggle="modal"
            data-bs-target="#lucideIconPickerModal">
            <i class="fa fa-icons"></i> Browse Icons
        </button>
    </span></label>
                                <input type="text" name="icon_png" id="icon_png_text" class="form-control"
                                    placeholder="e.g. UtilityPole">
                                <small class="text-muted">
                                    Enter the exact <strong>Lucide icon name</strong> without spaces. The icon will be displayed in the menu.
                                    Examples: <code>Home</code>, <code>FileText</code>, <code>UserRound</code>, <code>UtilityPole</code>.
                                </small>
                            </div>

                            {{-- Icon image upload input (shown when icon_type = IMAGE) --}}
                            <div class="form-group col-md-6 col-12 d-none" id="icon_image_content">
                                <label class="form-label">Icon Image</label>
                                <input type="file" name="icon_png" id="icon_png_file" class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp" />
                                <small class="text-muted">Allowed: jpg, png, webp. Max: 2MB. Max dimensions: 80×90px</small>
                            </div>

                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="parent_id">Parent Menu <span
                                        class="text-danger">*</span></label>
                                <select name="parent_id" id="parent_id" class="form-control select2">
                                    <option value="">None</option>
                                    @foreach ($menus as $m)
                                        <option value="{{ $m->id }}">
                                            {{ $m->location_name ? '[' . \Illuminate\Support\Str::headline($m->location_name) . '] ' : '' }}{!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', strlen($m->dashes)) !!}{{ $m->sl_no }}.
                                            {{ $m->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="order">Order</label>
                                <input type="number" name="order" id="order" class="form-control" min="0">
                                <small class="text-muted"><i class="fas fa-info-circle"></i> Leave blank to auto-assign (appended after last sibling)</small>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="location">Location <span class="text-danger">*</span></label>
                                <select name="location" id="location" class="form-control" required>
                                    <option value="">Select Location</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->location_code }}">
                                            {{ ucfirst($location->location_name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group d-none col-md-6 col-12">
                                <label class="form-label" for="permission_name">Permission Name</label>
                                <input type="text" name="permission_name" id="permission_name" class="form-control">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('secure.menus.partials.lucide-icon-picker')
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {

            $('.select2').select2({
                // theme: 'bootstrap-4',
                width: '100%',
                placeholder: function () {
                    return $(this).data('placeholder') || 'Select an option';
                },
                allowClear: true
            });

            // Toggle URL vs FILE content
            $('#type').on('change', function () {
                var selectedType = $(this).val();
                if (selectedType === 'URL') {
                    $('#url_content').removeClass('d-none');
                    $('#file_content').addClass('d-none');
                } else {
                    $('#url_content').addClass('d-none');
                    $('#file_content').removeClass('d-none');
                }
            });

            // Toggle Icon Type: ICON (text input) vs IMAGE (file input)
            $('#icon_type').on('change', function () {
                var selectedIconType = $(this).val();
                if (selectedIconType === 'ICON') {
                    $('#icon_text_content').removeClass('d-none');
                    $('#icon_image_content').addClass('d-none');
                    // Enable text input, disable file input
                    $('#icon_png_text').prop('disabled', false).attr('name', 'icon_png');
                    $('#icon_png_file').prop('disabled', true).removeAttr('name');
                } else {
                    $('#icon_text_content').addClass('d-none');
                    $('#icon_image_content').removeClass('d-none');
                    // Enable file input, disable text input
                    $('#icon_png_file').prop('disabled', false).attr('name', 'icon_png');
                    $('#icon_png_text').prop('disabled', true).removeAttr('name');
                }
            });

            // Initialize icon type toggle state
            $('#icon_type').trigger('change');

            // Initialize jQuery Validation
            $('#menuForm').validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    title_hi: {
                        required: true,
                        maxlength: 255
                    },
                    order: {
                        number: true
                    },
                    location: {
                        required: true
                    },
                    permission_name: {
                        maxlength: 255
                    },
                },
                messages: {
                    title: {
                        required: "Please enter a title.",
                        maxlength: "Title cannot exceed 255 characters."
                    },
                    url: {
                        url: "Please enter a valid URL."
                    },
                    order: {
                        number: "Order must be a number."
                    },
                    location: {
                        required: "Please select a location."
                    },
                    permission_name: {
                        maxlength: "Permission name cannot exceed 255 characters."
                    }
                },
                submitHandler: function (form) {
                    // Show loader
                    let formData = new FormData(form);
                    showLoader();

                    // Perform AJAX submission
                    $.ajax({
                        url: "{{ route('menus.store') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            // Show loader
                            showLoader();
                        },
                        success: function (response) {
                            // Hide loader
                            hideLoader();

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
                                // Show error message using Toastr
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            // Hide loader
                            hideLoader();

                            // Parse and display validation errors
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                var errorMessages = [];
                                $.each(errors, function (key, value) {
                                    errorMessages.push(value[0]);
                                });
                                toastr.error(errorMessages.join('<br>'));
                            } else {
                                toastr.error('An unexpected error occurred.');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
