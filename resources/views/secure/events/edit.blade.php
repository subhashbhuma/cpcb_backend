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

                    <form id="eventForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ $event->title }}" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <span class="text-danger">*</span></label>
                                <input type="text" name="title_hi" class="form-control" value="{{ $event->title_hi }}"
                                    required />
                            </div>



                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Venue (English):</label>
                                <input type="text" name="venue" class="form-control" value="{{ $event->venue }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Venue (Hindi):</label>
                                <input type="text" name="venue_hi" class="form-control" value="{{ $event->venue_hi }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Date: <span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control" value="{{ $event->date }}" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Time: <span class="text-danger">*</span></label>
                                <input type="time" name="time" class="form-control" value="{{ $event->time }}" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Brief Summary (English):</label>
                                <textarea name="brief_summary" class="form-control"
                                    rows="2">{{ $event->brief_summary }}</textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Brief Summary (Hindi):</label>
                                <textarea name="brief_summary_hi" class="form-control"
                                    rows="2">{{ $event->brief_summary_hi }}</textarea>
                            </div>

                            <div class="col-md-12 col-12 mb-3">
                                <label class="form-label">Description (English):</label>
                                <textarea name="description" class="form-control" id="page-editor"
                                    rows="3">{{ $event->description }}</textarea>
                            </div>

                            <div class="col-md-12 col-12 mb-3">
                                <label class="form-label">Description (Hindi):</label>
                                <textarea name="description_hi" class="form-control" id="hi-page-editor"
                                    rows="3">{{ $event->description_hi }}</textarea>
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
            $("#eventForm").validate({
                rules: {
                    title: {
                        required: true
                    },
                    title_hi: {
                        required: true
                    },
                    date: {
                        required: true
                    },
                    time: {
                        required: true
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);
                    window.editors.forEach(({
                        editor,
                        name,
                        id
                    }) => {
                        const content = editor.getData();
                        formData.set(name, content);
                    });

                    $.ajax({
                        url: "{{ route('events.update', $event->id) }}",
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
                                    window.location.href = "{{ route('events.index') }}";
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