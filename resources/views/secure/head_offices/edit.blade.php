@extends('layouts.app_layout')

@section('content')
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Edit Head Office</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('head_offices.update', $headOffice->id) }}" method="POST"
                        enctype="multipart/form-data" id="editForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Division <span class="text-danger">*</span></label>
                                <select name="division_id" class="form-control" required>
                                    <option value="">-- Select Division --</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}" {{ $headOffice->division_id == $division->id ? 'selected' : '' }}>
                                            {{ $division->title }} ({{ $division->title_hi }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Divisional Head & Designation </label>
                                <input type="text" name="title" class="form-control" value="{{ $headOffice->title }}"
                                    >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Divisional Head & Designation (Hindi) <x-translate-button
                                        source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" class="form-control" value="{{ $headOffice->title_hi }}">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    value="{{ old('email', $headOffice->email) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ext_number" class="form-label">Extension Number</label>
                                <input type="text" class="form-control" id="ext_number" name="ext_number"
                                    value="{{ old('ext_number', $headOffice->ext_number) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="order" class="form-label">Order</label>
                                <input type="number" class="form-control" id="order" name="order"
                                    value="{{ old('order', $headOffice->order) }}">
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description (English)</label>
                                <textarea class="form-control" id="page-editor"
                                    name="description">{{ old('description', $headOffice->description) }}</textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="description_hi" class="form-label">Description (Hindi) <x-translate-button
                                        source="description" target="description_hi" isRichText="true" /></label>
                                <textarea class="form-control" id="hi-page-editor"
                                    name="description_hi">{{ old('description_hi', $headOffice->description_hi) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-3 text-center">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
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
            // Validation
            $('#editForm').validate({
                rules: {
                    division_id: 'required',
                },
                messages: {
                    division_id: 'Please select Division',
                },
                submitHandler: function (form) {
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
                        url: "{{ route('head_offices.update', $headOffice->id) }}",
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href = "{{ route('head_offices.index') }}";
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
                    return false;
                }
            });
        });
    </script>
@endsection
