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

                    <form id="employeeForm" action="{{ route('employee.update', $employee->id) }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $employee->name) }}" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" name="email" class="form-control"
                                    value="{{ old('email', $employee->email) }}">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Mobile Number -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile_number" class="form-control"
                                    value="{{ old('mobile_number', $employee->mobile_number) }}">
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
                                        <option value="{{ $designation->id }}"
                                            {{ old('designation_id', $employee->designation_id) == $designation->id ? 'selected' : '' }}>
                                            {{ $designation->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Employee Code -->
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Employee Code <span class="text-danger">*</span></label>
                                <input type="text" name="emp_code" class="form-control"
                                    value="{{ old('emp_code', $employee->emp_code) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Level -->
                            <div class="col-md-3 col-12 mb-3">
                                <label class="form-label">Level</label>
                                <input type="number" name="level" class="form-control"
                                    value="{{ old('level', $employee->level) }}">
                            </div>

                            <!-- Cell -->
                            <div class="col-md-3 col-12 mb-3">
                                <label class="form-label">Cell</label>
                                <input type="number" name="cell" class="form-control"
                                    value="{{ old('cell', $employee->cell) }}">
                            </div>

                            <!-- Posted At -->
                            <div class="col-md-3 col-12 mb-3">
                                <label class="form-label">Posted At <span class="text-danger">*</span></label>
                                <input type="text" name="posted_at" class="form-control"
                                    value="{{ old('posted_at', $employee->posted_at) }}" required>
                            </div>

                            <!-- PAN No -->
                          <div class="col-md-3 col-12 mb-3">
    <label class="form-label">
        PAN No <span class="text-danger">*</span>
    </label>

    @if(isset($employee->pan_no))
        <small class="text-muted d-block mb-1">
            Current PAN: {{ substr($employee->pan_no, 0, 5) . '****' . substr($employee->pan_no, -1) }}
        </small>
    @endif
    <input
        type="text"
        name="pan_no"
        class="form-control"
        placeholder="Enter new PAN (leave blank if no change)"
        style="text-transform: uppercase;"
    >
</div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Update Employee
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
        var encryptionKey = "{{ \App\Helpers\CustomHelper::setEncryptionKey() }}";
        $(document).ready(function() {

            $('#employeeForm').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    },
                    mobile_number: {
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
                        required: false,
                        maxlength: 10
                    }
                },

                submitHandler: async function (form, event) {
                    event.preventDefault();
                    let formData = new FormData(form);
                    const pan_no = await encryptPassword(formData.get('pan_no'), encryptionKey);
                    formData.set('pan_no', pan_no);

                    $.ajax({
                        url: form.action,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            showLoader();
                        },
                        success: function(response) {
                            hideLoader();
                            encryptionKey = response.key;
                            console.log('encryptionKey', encryptionKey);

                            if (response.success) {
                                Swal.fire("Updated!", response.message, "success")
                                    .then(() => window.location.href = response.redirect_url);
                            } else {
                                toastr.error(response.message);
                            }
                        },

                        error: function(xhr) {
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

                    return false;
                }
            });

        });
    </script>
@endsection
