@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>All (<span class="text-danger">*</span>) marked fields are mandatory.</p>
                    <!-- Employee Form -->
                    <form id="employeeForm" action="{{ route('employee.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Mobile Number -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile_number" class="form-control">
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Employee Information</h5>

                        <div class="row">
                            <!-- Designation -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Designation <span class="text-danger">*</span></label>
                                <select name="designation_id" class="form-control" required>
                                    <option value="">-- Select Designation --</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Employee Code -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Employee Code <span class="text-danger">*</span></label>
                                <input type="text" name="emp_code" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Level -->
                            <div class="col-md-3 col-12 mb-3">
                                <label class="form-label">Level</label>
                                <input type="number" name="level" class="form-control">
                            </div>

                            <!-- Cell -->
                            <div class="col-md-3 col-12 mb-3">
                                <label class="form-label">Cell</label>
                                <input type="number" name="cell" class="form-control">
                            </div>

                            <!-- Posted At -->
                            <div class="col-md-3 col-12 mb-3">
                                <label class="form-label">Posted At <span class="text-danger">*</span></label>
                                <input type="text" name="posted_at" class="form-control" required>
                            </div>

                            <!-- PAN No -->
                            <div class="col-md-3 col-12 mb-3">
                                <label class="form-label">PAN No <span class="text-danger">*</span></label>
                                <input type="text" name="pan_no" class="form-control" placeholder="Enter PAN (ABCDE1234F)"
                                    required style="text-transform: uppercase;">
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Create Employee
                            </button>
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

            var encryptionKey = "{{ \App\Helpers\CustomHelper::setEncryptionKey() }}";
            $('#employeeForm').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    },
                    designation_id: {
                        required: true
                    },
                    emp_code: {
                        required: true,
                        maxlength: 50
                    },
                    level: {
                        number: true
                    },
                    cell: {
                        number: true
                    },
                    posted_at: {
                        required: true
                    },
                    pan_no: {
                        required: true,
                        maxlength: 10
                    }
                },

                submitHandler: async function (form, event) {
                    event.preventDefault();
                    let formData = new FormData(form);
                    const pan_no = await encryptPassword(formData.get('pan_no'), encryptionKey);
                    formData.set('pan_no', pan_no);

                    $.ajax({
                        url: "{{ route('employee.store') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function () {
                            showLoader();
                        },
                        success: function (response) {
                            encryptionKey = response.key;
                            hideLoader();
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    window.location.href = response.redirect_url;
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },

                        error: function (xhr) {
                            hideLoader();
                            if (xhr.responseJSON.key) {
                                encryptionKey = xhr.responseJSON.key;
                            }
                            if (xhr.status === 422) {
                                let errors = Object.values(xhr.responseJSON.errors).flat()
                                    .join("<br>");
                                Swal.fire("Validation Error", errors, "error");
                            } else {
                                Swal.fire("Error!",
                                    "Something went wrong. Please try again.", "error");
                            }
                        }
                    });
                }
            });

        });
    </script>
@endsection