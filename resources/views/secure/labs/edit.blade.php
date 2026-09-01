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
                    <form id="labsForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-12  mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ $labs->title }}" class="form-control" />
                            </div>

                            <div class="col-md-6 col-12  mb-3">
                                <label class="form-label">Title (हिंदी): <span class="text-danger">*</span></label>
                                <input type="text" name="title_hi" value="{{ $labs->title_hi }}" class="form-control" />
                            </div>

                            <div class="col-md-6 col-12  mb-3">
                                <label class="form-label">Public Comment: <span class="text-danger">*</span></label>
                                <input type="text" name="public_comments" value="{{ $labs->public_comments }}"
                                    class="form-control" />
                            </div>

                            <div class="col-md-6 col-12  mb-3">
                                <label class="form-label">Public Comment (हिंदी): <span class="text-danger">*</span></label>
                                <input type="text" name="public_comments_hi" value="{{ $labs->public_comments_hi }}"
                                    class="form-control" />
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Featured Image:</label>
                                <input type="file" name="featured_image" class="form-control" accept="image/*" />
                                @if($labs->featured_image)
                                    <div class="mt-2">
                                        <strong class="me-2">Current:</strong>
                                        <a href="{{ asset('storage/' . Config::get('file_paths')['LABORATORIES_FEATURED_IMAGE_PATH'] . '/' . $labs->featured_image) }}"
                                            target="_blank">
                                            <img src="{{ asset('storage/' . Config::get('file_paths')['LABORATORIES_FEATURED_IMAGE_PATH'] . '/' . $labs->featured_image) }}"
                                                alt="Featured Image" class="img-thumbnail show-preview-image" />
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 col-12  mb-3">
                                <label class="form-label">Category:</label>
                                <select name="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $labs->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->title }}
                                        </option>
                                    @endforeach
                                </select>
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
                                                contenteditable="true">{!! $labs->content !!}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Content (हिंदी):</label>
                                            <textarea name="content_hi" class="form-control" id="hi-page-editor"
                                                contenteditable="true">{!! $labs->content_hi !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <div class="mb-3">
                                            <h6>
                                                You can multiple files in the page. Click on the below button to add files
                                                in the page.
                                            </h6>
                                            <button class="btn btn-success btn-add-page-file">
                                                <i class="fa fa-plus"></i> <span>Add Files Row</span>
                                            </button>
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
                                                        <th>Description</th>
                                                        <th>Date</th>
                                                        <th>Type</th>
                                                        <th width="8%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($labs->files->count() > 0)
                                                        @foreach ($labs->files as $key => $file)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>
                                                                    <a href="{{ $file->file_path }}" target="_BLANK">
                                                                        <i class="fa fa-eye"></i> View File (English)
                                                                    </a>
                                                                    <br> <br>
                                                                    <a href="{{ $file->file_path_hi }}" target="_BLANK">
                                                                        <i class="fa fa-eye"></i> View File (हिंदी)
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    {{ $labs->title }}
                                                                    <br>
                                                                    <br>
                                                                    ({{ $labs->title_hi }})
                                                                </td>
                                                                <td>
                                                                    {{ $labs->description }}
                                                                    <br>
                                                                    <br>
                                                                    {{ $labs->description_hi }}
                                                                </td>
                                                                <td>
                                                                    {{ $labs->date }}
                                                                    <br>
                                                                </td>
                                                                <td>
                                                                    {{ $labs->type }}
                                                                    <br>
                                                                <td>
                                                                    <button class="btn btn-danger btn-delete-page-file"
                                                                        type="button" data-id="{{ $file->id }}">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="4">No data available</td>
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
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update Page</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('components.labs_page_files_template')
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
            // generateFileRow();

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
                            url: "{{ route('labs.files.destroy', ':id') }}".replace(':id', id),
                            type: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function () {
                                showLoader();
                            },
                            success: function (response) {
                                hideLoader();
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
            $('#labsForm').validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    title_hi: {
                        required: true,
                        maxlength: 255
                    },
                    category_id: {
                        required: true
                    },
                },
                submitHandler: function (form) {
                    let formData = new FormData(document.getElementById('labsForm'))
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
                        url: "{{ route('labs.update', $labs->id) }}",
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
                                    title: "Updated!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href = response.redirect_url;
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