@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />

    <!-- [ Page Header ] end -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="menuForm" action="{{ route('employee-menus.update', $menu->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- Hidden location field — always 'employee' --}}
                        <input type="hidden" name="location" value="employee">
                        <div class="row">
                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="title">Title (English)</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ $menu->title }}"
                                    required>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="title_hi">Title (हिंदी) <span class="text-danger">*</span><x-translate-button source="title"
                                                        target="title_hi" />
                                </label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control"
                                    value="{{ $menu->title_hi }}" required>
                            </div>

                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="type">Menu Type <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control" required>
                                    @foreach (Config::get('constants.MENU_TYPE') as $key => $value)
                                        <option value="{{ $key }}" @selected($menu->type == $key)>{{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-12" id="url_content">
                                <label class="form-label" for="url">URL</label>
                                <input type="text" name="url" id="url" class="form-control" value="{{ $menu->url }}">
                                <small class="text-muted"><i class="fas fa-info-circle"></i> Leave blank to auto-generate from title (e.g. <em>/my-menu-title</em> or <em>/parent/my-menu-title</em>)</small>
                            </div>
                            <div class="row m-0 p-0 d-none" id="file_content">
                                <div class="from-group col-md-6 col-12 mb-3">
                                    <label class="form-label">File (English):</label>
                                    <input type="file" name="file_name" class="form-control" accept=".pdf" />
                                    @if ($menu->file_name)
                                        <div>
                                            Current File: <a
                                                href="{{ asset('storage/' . Config::get('file_paths')['MENU_FILE_EN_PATH'] . '/' . $menu->file_name) }}"
                                                target='_BLANK'>View Document</a>
                                        </div>
                                    @endif
                                </div>

                                <div class="from-group col-md-6 col-12 mb-3">
                                    <label class="form-label">File (Hindi):</label>
                                    <input type="file" name="file_name_hi" class="form-control" accept=".pdf" />
                                    @if ($menu->file_name_hi)
                                        <div>
                                            Current File: <a
                                                href="{{ asset('storage/' . Config::get('file_paths')['MENU_FILE_HI_PATH'] . '/' . $menu->file_name_hi) }}"
                                                target='_BLANK'>View Document</a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="icon_type">Icon Type</label>
                                <select name="icon_type" id="icon_type" class="form-control">
                                    <option value="ICON" @selected(($menu->icon_type ?? 'ICON') == 'ICON')>Icon (CSS Class)
                                    </option>
                                    <option value="IMAGE" @selected(($menu->icon_type ?? 'ICON') == 'IMAGE')>Image (Upload)
                                    </option>
                                </select>
                            </div>

                            {{-- Icon CSS class input (shown when icon_type = ICON) --}}
                            <div class="form-group col-md-6 col-12" id="icon_text_content">
                                <label class="form-label" for="icon_png_text"> Tabler Icon Class    <span>
        <button type="button"
            class="btn btn-sm btn-outline-primary"
            data-bs-toggle="modal"
            data-bs-target="#tablerIconPickerModal">
            <i class="fa fa-icons"></i> Browse Icons
        </button>
    </span></label>
                                <input type="text" name="icon_png" id="icon_png_text" class="form-control"
                                    value="{{ ($menu->icon_type ?? 'ICON') == 'ICON' ? $menu->icon_png : '' }}"
                                    placeholder="e.g. ti ti-home">
                                <small class="text-muted">
                                    Enter the exact <strong>Tabler icon class</strong>. The icon will be displayed in the menu.
                                    Examples: <code>ti ti-home</code>, <code>ti ti-file-text</code>, <code>ti ti-user</code>.
                                </small>
                            </div>

                            {{-- Icon image upload input (shown when icon_type = IMAGE) --}}
                            <div class="form-group col-md-6 col-12 d-none" id="icon_image_content">
                                <label class="form-label">Icon Image</label>
                                <input type="file" name="icon_png" id="icon_png_file" class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp" />
                                <small class="text-muted">Allowed: jpg, png, webp. Max: 2MB. Max dimensions: 80×90px</small>
                                @if (($menu->icon_type ?? 'ICON') == 'IMAGE' && $menu->icon_png)
                                    <div class="mt-2">
                                        <span class="text-muted">Current Icon:</span>
                                        <img src="{{ asset('storage/' . Config::get('file_paths')['MENU_ICON_IMAGE_PATH'] . '/' . $menu->icon_png) }}"
                                            alt="Current Icon" class="img-thumbnail" style="max-height: 80px; max-width: 80px;">
                                    </div>
                                @endif
                            </div>

                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="parent_id">Parent Menu</label>
                                <select name="parent_id" id="parent_id" class="form-control select2">
                                    <option value="">None</option>
                                    @foreach ($menus as $m)
                                        <option value="{{ $m->id }}" @selected($menu->parent_id == $m->id)>
                                            {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', strlen($m->dashes)) !!}{{ $m->sl_no }}.
                                            {{ $m->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="order">Order</label>
                                <input type="number" name="order" id="order" class="form-control" min="0"
                                    value="{{ $menu->order }}">
                                <small class="text-muted"><i class="fas fa-info-circle"></i> Leave blank to auto-assign (appended after last sibling)</small>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label class="form-label" for="location_display">Location</label>
                                <input type="text" id="location_display" class="form-control" value="Employee" readonly disabled>
                            </div>
                            <div class="form-group d-none col-md-6 col-12">
                                <label class="form-label" for="permission_name">Permission Name</label>
                                <input type="text" name="permission_name" id="permission_name" class="form-control"
                                    value="{{ $menu->permission_name }}">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-edit"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('secure.employee-menus.partials.tabler-icon-picker')
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

            // Initialize icon type and menu type toggle state
            $('#icon_type').trigger('change');
            $('#type').trigger('change');

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
                    permission_name: {
                        maxlength: 255
                    }
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
                    permission_name: {
                        maxlength: "Permission name cannot exceed 255 characters."
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);
                    // Show loader
                    showLoader();

                    // Perform AJAX submission
                    $.ajax({
                        url: "{{ route('employee-menus.update', $menu->id) }}",
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
                                    window.location.href = response.redirect_url;
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
