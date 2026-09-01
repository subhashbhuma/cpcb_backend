@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="Edit Direction Category" :backButton="true" />
    <!-- [ Page Header ] end -->


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>
                        All (<span class="text-danger">*</span>) marked fields are required.
                    </p>
                    <form id="directionCategoryForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 col-12 mb-3">
                                <label class="form-label">Act Type:</label>
                                <select name="direction_act_type_id" class="form-control">
                                    <option value="">Select Act Type</option>
                                    @foreach($actTypes as $type)
                                        <option value="{{ $type->id }}" {{ $directionCategory->direction_act_type_id == $type->id ? 'selected' : '' }}>{{ $type->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control"
                                    value="{{ $directionCategory->title }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <span class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control"
                                    value="{{ $directionCategory->title_hi }}" />
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
            $("#directionCategoryForm").validate({
                rules: {
                    title: {
                        required: true
                    },
                    title_hi: {
                        required: true
                    },
                    direction_act_type_id: {
                        required: false
                    },
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: "{{ route('direction_category.update', $directionCategory->id) }}",
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
                                    window.location.href = "{{ route('direction_category.index') }}";
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