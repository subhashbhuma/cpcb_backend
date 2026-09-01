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

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Division <span class="text-danger">*</span></label>
                                <select name="division_id" class="form-control" required>
                                    <option value="">-- Select Division --</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}">{{ $division->title }} ({{ $division->title_hi }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category: <span class="text-danger">*</span></label>
                                <select name="category" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($circularCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->name_hi }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title (Hindi): <span class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">File Name (English):</label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf,.doc,.docx">
                                <small class="text-muted">Allowed types: pdf, doc, docx. Max: 5MB</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">File Name (Hindi):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf,.doc,.docx">
                                <small class="text-muted">Allowed types: pdf, doc, docx. Max: 5MB</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Published Date: <span class="text-danger">*</span></label>
                                <input type="text" name="published_date" class="form-control" required>
                            </div>


                            <div class="col-12 text-center mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Save
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
                    file_name: {
                        required: true
                    },
                    published_date: {
                        required: true,
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('circulars.store') }}",
                        method: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
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
