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

                    <form id="publicationForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Series: <span class="text-danger">*</span></label>
                                <select name="category_id" id="publication-cat" class="form-control select2" required>
                                    <option value="">-- Select Series --</option>
                                    @foreach ($publication_categories as $categories)
                                        <option value="{{ $categories->id }}" {{ $publication->category_id == $categories->id ? 'selected' : '' }}>
                                            {{ $categories->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ $publication->title }}"
                                    required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control"
                                    value="{{ $publication->title_hi }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Price:</label>
                                <input type="number" name="price" class="form-control" value="{{ $publication->price??"0" }}"
                                    required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Published Date:</label>
                                <input type="text" name="published_date" class="form-control"
                                    value="{{ $publication->published_date?date('d-m-Y', strtotime($publication->published_date)) : null }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (English):</label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf" />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB</small>
                                @if($publication->file_name)
                                    <div class="mt-1">
                                        Current File:
                                        <a href="{{ generate_file_view_path_for_backend($publication->file_url) }}"
                                            target="_blank">View Document</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (Hindi):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf" />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB</small>
                                @if($publication->file_name_hi)
                                    <div class="mt-1">
                                        Current File:
                                        <a href="{{  generate_file_view_path_for_backend($publication->file_url_hi) }}"
                                            target="_blank">View Document</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-12 text-center">
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
            $("#publicationForm").validate({
                rules: {
                    title: {
                        required: true
                    },
                    // add these only if they are required
                    // title_hi: {
                    //     required: true
                    // },
                    price: {
                        required: true
                    },
                    category_id: {
                        required: true
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('publication.update', $publication->id) }}",
                        method: "POST", // @method('PUT') will spoof PUT
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Updated!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href = "{{ route('publication.index') }}";
                                });
                            } else {
                                toastr.error(response.message || "Something went wrong.");
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
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
