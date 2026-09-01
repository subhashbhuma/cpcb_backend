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

                    <form id="circularForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Division <span class="text-danger">*</span></label>
                                <select name="division_id" class="form-control" required>
                                    <option value="">-- Select Division --</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}" {{ $circular->division_id == $division->id ? 'selected' : '' }}>
                                            {{ $division->title }} ({{ $division->title_hi }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Category: <span class="text-danger">*</span></label>
                                <select name="category" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($circularCategories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $circular->category ? 'selected' : '' }}>{{ $category->name }} ({{ $category->name_hi }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ $circular->title }}"
                                    required>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <span class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control" value="{{ $circular->title_hi }}">
                            </div>





                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File Name (English):</label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf,.doc,.docx">
                                <small class="text-muted">Allowed types: pdf, doc, docx. Max: 5MB</small>
                                @if ($circular->file_name)
                                    <div class="mt-2">
                                        <strong>Current File:</strong>
                                        <a href="{{ generate_file_view_path_for_backend($circular->file_url) }}"
                                            target="_blank">View Document</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File Name (Hindi):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf,.doc,.docx">
                                <small class="text-muted">Allowed types: pdf, doc, docx. Max: 5MB</small>
                                @if ($circular->file_name_hi)
                                    <div class="mt-2">
                                        <strong>Current Hindi File:</strong>
                                        <a href="{{ generate_file_view_path_for_backend($circular->file_url_hi) }}"
                                            target="_blank">View Document</a>
                                    </div>
                                @endif
                            </div>
                             <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Published Date: <span class="text-danger">*</span></label>
                                <input type="text" name="published_date" class="form-control"
                                    value="{{ $circular->published_date ? $circular->published_date->format('d-m-Y') : null }}" required>
                            </div>

                            <div class="col-12 text-center mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update
                                </button>
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
        datePickerInit('published_date');
            $("#circularForm").validate({
                rules: {
                    title: {
                        required: true
                    },
                    title_hi: {
                        required: true
                    },
                    category: {
                        required: true
                    },
                    division_id: {
                        required: true
                    },
                     published_date: {
                        required: true,
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('circulars.update', $circular->id) }}",
                        method: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Updated!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    window.location.href = "{{ route('circulars.index') }}";
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
