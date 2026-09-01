@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card card-body border-0 shadow mb-4">
                <p>All (<span class="text-danger">*</span>) marked fields are mandatory.</p>

                <form id="addDirectionTypeForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="direction_act_type_id">Act Type</label>
                            <select name="direction_act_type_id" id="direction_act_type_id" class="form-control">
                                <option value="">Select Act Type</option>
                                @foreach($actTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required maxlength="255">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="title_hi">Title (Hindi) <span
                                    class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                            <input type="text" class="form-control" id="title_hi" name="title_hi" required maxlength="255">
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fa fa-save"></i> Save Direction Type
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {
            $("#addDirectionTypeForm").validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    title_hi: {
                        required: true,
                        maxlength: 255
                    },
                    direction_act_type_id: {
                        required: false
                    },
                },
                submitHandler: function (form) {
                    var formData = new FormData(form);
                    $('#submitBtn').prop('disabled', true);

                    $.ajax({
                        url: "{{ route('direction-types.store') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#submitBtn').prop('disabled', false);
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                }).then(() => {
                                    window.location.href = response.redirect_url;
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            $('#submitBtn').prop('disabled', false);
                            if (xhr.status === 422) {
                                let errors = Object.values(xhr.responseJSON.errors).flat().join("<br>");
                                Swal.fire("Validation Error", errors, "error");
                            } else {
                                Swal.fire("Error!", "Something went wrong. Please try again.", "error");
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection