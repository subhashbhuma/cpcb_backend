@extends('layouts.app_layout')

@section('content')
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>All (<span class="text-danger">*</span>) marked fields are required.</p>

                    <form id="createForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="regional_directorate" class="form-label">Regional Directorate</label>
                                <input type="text" class="form-control" id="regional_directorate" name="regional_directorate"
                                    value="{{ old('regional_directorate') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="regional_directorate_hi" class="form-label">Regional Directorate (Hindi) <x-translate-button source="regional_directorate" target="regional_directorate_hi" /></label>
                                <input type="text" class="form-control" id="regional_directorate_hi" name="regional_directorate_hi"
                                    value="{{ old('regional_directorate_hi') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Name of Regional Director <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="{{ old('title') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="title_hi" class="form-label">Name of Regional Director (Hindi) <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" class="form-control" id="title_hi" name="title_hi"
                                    value="{{ old('title_hi') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="designation" class="form-label">Designation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="designation" name="designation"
                                    value="{{ old('designation') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="designation_hi" class="form-label">Designation (Hindi) <x-translate-button source="designation" target="designation_hi" /></label>
                                <input type="text" class="form-control" id="designation_hi" name="designation_hi"
                                    value="{{ old('designation_hi') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="order" class="form-label">Order <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="order" name="order"
                                    value="{{ old('order', 0) }}" min="0" required>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description (English)</label>
                                <textarea class="form-control" id="page-editor" name="description">{{ old('description') }}</textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="description_hi" class="form-label">Description (Hindi) <x-translate-button source="description" target="description_hi" isRichText="true" /></label>
                                <textarea class="form-control" id="hi-page-editor" name="description_hi">{{ old('description_hi') }}</textarea>
                            </div>
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
    <script @cspNonce type="text/javascript">
        $(document).ready(function() {
            // Validation
            $('#createForm').validate({
                rules: {
                    title: {
                        required: true
                    },
                    designation: {
                        required: true
                    },
                    order: {
                        required: true,
                        digits: true,
                        min: 0
                    }
                },
                submitHandler: function(form) {
                    let formData = new FormData(form);

                    // Sync CKEditor data
                    if (window.editors) {
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
                    }

                    $.ajax({
                        url: "{{ route('regional_directorates.store') }}",
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href = "{{ route('regional_directorates.index') }}";
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
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
                    return false;
                }
            });
        });
    </script>
@endsection
