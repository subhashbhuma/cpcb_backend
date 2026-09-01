@extends('layouts.app_layout')

@section('content')
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="site-settings-form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="site_name">Site Name (English) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="site_name" name="site_name"
                                        value="{{ $settings->site_name ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="site_name_hi">Site Name (Hindi)</label>
                                    <input type="text" class="form-control" id="site_name_hi" name="site_name_hi"
                                        value="{{ $settings->site_name_hi ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="seo_keywords">SEO Keywords</label>
                            <input type="text" class="form-control" id="seo_keywords" name="seo_keywords"
                                value="{{ $settings->seo_keywords ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="seo_description">SEO Description</label>
                            <textarea class="form-control" id="seo_description"
                                name="seo_description">{{ $settings->seo_description ?? '' }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="header_logo">Header Logo</label>
                                    <input type="file" class="form-control-file form-control" id="header_logo"
                                        name="header_logo">
                                    @if($settings->header_logo)
                                        <div>
                                            <img src="{{ asset('storage/' . Config::get('file_paths')['SITE_HEADER_LOGO_PATH'] . '/' . $settings->header_logo) }}"
                                                alt="Header Logo" class="img-thumbnail" style="width: 150px;" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-wrap">
                                <div class="row" id="header-img-wrapper">
                                    <div class="col-md-4 col-sm-6 col-12" id="fieldset-1">
                                        <div class="form-group fieldset-content-col">
                                            <label class="form-label" for="header_img_1">Header Image 1</label>
                                            <div class="d-flex">
                                                <input type="file" class="form-control-file form-control" id="header_img_1"
                                                    name="header_img_1">
                                            </div>
                                            @if($settings->header_img_1)
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . Config::get('file_paths')['SITE_HEADER_IMAGES_PATH'] . '/' . $settings->header_img_1) }}"
                                                        alt="Header Image 1" class="img-thumbnail" style="width: 150px;" />
                                                </div>
                                            @endif
                                            <div class="mt-2">
                                                <input type="text" class="form-control" name="header_name_1"
                                                    placeholder="Name (English)"
                                                    value="{{ $settings->header_name_1 ?? '' }}">
                                                <input type="text" class="form-control mt-1" name="header_name_1_hi"
                                                    placeholder="Name (Hindi)"
                                                    value="{{ $settings->header_name_1_hi ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12" id="fieldset-2">
                                        <div class="form-group fieldset-content-col">
                                            <label class="form-label" for="header_img_2">Header Image 2</label>
                                            <div class="d-flex">
                                                <input type="file" class="form-control-file form-control" id="header_img_2"
                                                    name="header_img_2">
                                            </div>
                                            @if($settings->header_img_2)
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . Config::get('file_paths')['SITE_HEADER_IMAGES_PATH'] . '/' . $settings->header_img_2) }}"
                                                        alt="Header image 2" class="img-thumbnail" style="width: 150px;" />
                                                </div>
                                            @endif
                                            <div class="mt-2">
                                                <input type="text" class="form-control" name="header_name_2"
                                                    placeholder="Name (English)"
                                                    value="{{ $settings->header_name_2 ?? '' }}">
                                                <input type="text" class="form-control mt-1" name="header_name_2_hi"
                                                    placeholder="Name (Hindi)"
                                                    value="{{ $settings->header_name_2_hi ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12" id="fieldset-3">
                                        <div class="form-group fieldset-content-col">
                                            <label class="form-label" for="header_img_3">Header Image 3</label>
                                            <div class="d-flex">
                                                <input type="file" class="form-control-file form-control" id="header_img_3"
                                                    name="header_img_3">
                                            </div>
                                            @if($settings->header_img_3)
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . Config::get('file_paths')['SITE_HEADER_IMAGES_PATH'] . '/' . $settings->header_img_3) }}"
                                                        alt="Header image 3" class="img-thumbnail" style="width: 150px;" />
                                                </div>
                                            @endif
                                            <div class="mt-2">
                                                <input type="text" class="form-control" name="header_name_3"
                                                    placeholder="Name (English)"
                                                    value="{{ $settings->header_name_3 ?? '' }}">
                                                <input type="text" class="form-control mt-1" name="header_name_3_hi"
                                                    placeholder="Name (Hindi)"
                                                    value="{{ $settings->header_name_3_hi ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="footer_logo">Footer Logo</label>
                                    <input type="file" class="form-control-file form-control" id="footer_logo"
                                        name="footer_logo">
                                    @if($settings->footer_logo)
                                        <div>
                                            <img src="{{ asset('storage/' . Config::get('file_paths')['SITE_FOOTER_LOGO_PATH'] . '/' . $settings->footer_logo) }}"
                                                alt="Footer Logo" class="img-thumbnail" style="width: 150px;" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="favicon">FavIcon</label>
                                    <input type="file" class="form-control-file form-control" id="favicon" name="favicon">
                                    @if($settings->favicon)
                                        <div>
                                            <img src="{{ asset('storage/' . Config::get('file_paths')['SITE_FAVICON_PATH'] . '/' . $settings->favicon) }}"
                                                alt="Favicon Logo" class="img-thumbnail" style="width: 150px;" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="admin_panel_logo">AdminPanel Logo </label>
                                    <input type="file" class="form-control-file form-control" id="admin_panel_logo"
                                        name="admin_panel_logo">
                                    @if($settings->admin_panel_logo)
                                        <div>
                                            <img src="{{ asset('storage/' . Config::get('file_paths')['SITE_ADMIN_PANEL_LOGO_PATH'] . '/' . $settings->admin_panel_logo) }}"
                                                alt="Admin Panel Logo" class="img-thumbnail" style="width: 150px;" />
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="site_address">Site Address (English)</label>
                                        <textarea class="form-control" id="site_address"
                                            name="site_address">{{ $settings->site_address ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="site_address_hi">Site Address (Hindi)</label>
                                        <textarea class="form-control" id="site_address_hi"
                                            name="site_address_hi">{{ $settings->site_address_hi ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="disclaimer">Disclaimer (English)</label>
                                        <textarea class="form-control" id="disclaimer"
                                            name="disclaimer">{{ $settings->disclaimer ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="disclaimer_hi">Disclaimer (Hindi)</label>
                                        <textarea class="form-control" id="disclaimer_hi"
                                            name="disclaimer_hi">{{ $settings->disclaimer_hi ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="copyright_text">Copyright Text (English)</label>
                                        <input type="text" class="form-control" id="copyright_text" name="copyright_text"
                                            value="{{ $settings->copyright_text ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="copyright_text_hi">Copyright Text (Hindi)</label>
                                        <input type="text" class="form-control" id="copyright_text_hi"
                                            name="copyright_text_hi" value="{{ $settings->copyright_text_hi ?? '' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="maintained_by_text">Maintained By Text
                                            (English)</label>
                                        <input type="text" class="form-control" id="maintained_by_text"
                                            name="maintained_by_text" value="{{ $settings->maintained_by_text ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="maintained_by_text_hi">Maintained By Text
                                            (Hindi)</label>
                                        <input type="text" class="form-control" id="maintained_by_text_hi"
                                            name="maintained_by_text_hi"
                                            value="{{ $settings->maintained_by_text_hi ?? '' }}">
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save Settings</button>
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
            $('#site-settings-form').validate({
                rules: {
                    site_name: {
                        required: true,
                        minlength: 3
                    },
                    site_name_hi: {
                        required: false,
                        minlength: 3
                    },
                    seo_keywords: {
                        required: false
                    },
                    seo_description: {
                        required: false
                    }
                },
                messages: {
                    site_name: {
                        required: "Please enter the site name.",
                        minlength: "The site name must be at least 3 characters long."
                    },
                    site_name_hi: {
                        minlength: "The site name (Hindi) must be at least 3 characters long."
                    },
                },
                submitHandler: function (form) {
                    var formData = new FormData(form);
                    formData.append('_method', 'PUT'); // Spoofing the PUT method
                    $.ajax({
                        url: "{{ isset($settings->id) ? route('site-settings.update', $settings->id) : '#' }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = '';
                            $.each(errors, function (key, value) {
                                errorMessages += value + '<br>';
                            });
                            toastr.error(errorMessages);
                        }
                    });
                }
            });
        });
    </script>
@endsection