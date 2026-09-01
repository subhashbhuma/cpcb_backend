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
                        All (<span class="text-danger">*</span>) marked fields are required.
                    </p>
                    <form id="faqForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Question (English): <span class="text-danger">*</span></label>
                                <input type="text" name="question" id="question" class="form-control" value="{{ $faq->question }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Question (Hindi): <span class="text-danger">*</span> <x-translate-button source="question" target="question_hi" /></label>
                                <input type="text" name="question_hi" id="question_hi" class="form-control"
                                    value="{{ $faq->question_hi }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Answer (English):</label>
                                <textarea name="answer" class="form-control" rows="3"
                                    id="page-editor">{{ $faq->answer }}</textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Answer (Hindi): <x-translate-button source="page-editor" target="hi-page-editor" isEditor="true" /></label>
                                <textarea name="answer_hi" class="form-control" rows="3"
                                    id="hi-page-editor">{{ $faq->answer_hi }}</textarea>
                            </div>




                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
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
            $("#faqForm").validate({
                rules: {
                    question: {
                        required: true,
                    },
                    question_hi: {
                        required: true,
                    },
                    answer: {
                        required: false,
                    },
                    answer_hi: {
                        required: false,
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);
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
                    $.ajax({
                        url: "{{ route('faq.update', $faq->id) }}",
                        method: "POST",
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
                                    window.location.href = "{{ route('faq.index') }}";
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