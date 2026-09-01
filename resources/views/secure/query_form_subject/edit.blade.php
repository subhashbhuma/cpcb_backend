@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="Edit Query Form Subject" :backButton="true" />
    <!-- [ Page Header ] end -->


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>
                        All (<span class="text-danger">*</span>) marked fields are required.
                    </p>
                    <form id="queryFormSubjectForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label" for="title">Subject Title (English): <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control"
                                    value="{{ $record->title }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label" for="title_hi">Subject Title (हिंदी): <span class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control"
                                    value="{{ $record->title_hi }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label" for="division_id">Division:</label>
                                <select name="division_id" id="division_id" class="form-control">
                                    <option value="">-- Select Division --</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}" {{ $record->division_id == $division->id ? 'selected' : '' }}>
                                            {{ $division->title }} ({{ $division->title_hi }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label" for="name">Officer Name (English):</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $record->name }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label" for="name_hi">Officer Name (हिंदी): <x-translate-button source="name" target="name_hi" /></label>
                                <input type="text" name="name_hi" id="name_hi" class="form-control"
                                    value="{{ $record->name_hi }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label" for="email_id">Officer Email ID:</label>
                                <input type="email" name="email_id" id="email_id" class="form-control"
                                    value="{{ $record->email_id }}" />
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
            $("#queryFormSubjectForm").validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    title_hi: {
                        required: true,
                        maxlength: 255
                    },
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: "{{ route('query_form_subject.update', $record->id) }}",
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
                                    window.location.href = "{{ route('query_form_subject.index') }}";
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