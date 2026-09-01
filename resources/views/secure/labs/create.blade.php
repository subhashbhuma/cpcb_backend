<!-- resources/views/secure/labs/create.blade.php -->

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
                    <form id="labsForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title: <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (हिंदी): <span class="text-danger">*</span></label>
                                <input type="text" name="title_hi" class="form-control" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Featured Image: <span class="text-danger">*</span></label>
                                <input type="file" name="featured_image" class="form-control" accept="image/*" />
                            </div>



                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Labs Category:</label>
                                <select name="category_id" class="form-control">
                                    <option value="">Select Labs Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Public Comment (English): <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="public_comments" class="form-control" />
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Public Comment (हिंदी): <span class="text-danger">*</span></label>
                                <input type="text" name="public_comments_hi" class="form-control" />
                            </div>
                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Public Comment URL: <span class="text-danger">*</span></label>
                                <input type="text" name="public_comments_url" class="form-control" />
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
                                        <label class="form-label">Content (हिंदी):</label>
                                        <textarea name="content_hi" class="form-control" id="hi-page-editor"></textarea>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="mb-3">
                                        <h6>
                                            You can multiple files in the page. Click on the below button to add files in
                                            the page.
                                        </h6>
                                        <button class="btn btn-success btn-add-page-file">
                                            <i class="fa fa-plus"></i> <span>Add Files Row</span>
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
        })
        $.validator.addMethod("public_comments_url", function (value, element) {
            return this.optional(element) || /^(https?:\/\/)?([\w\-\.]+)(:\d+)?(\/[\w\-\.]*)*\/?$/.test(value);
        }, "Please enter a valid URL.");
        $(document).ready(function () {
            // Initialize jQuery Validation
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
                    public_comments: {
                        maxlength: 255
                    },
                    public_comments_hi: {
                        maxlength: 255
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

                    $.ajax({
                        url: "{{ route('labs.store') }}",
                        type: "POST",
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