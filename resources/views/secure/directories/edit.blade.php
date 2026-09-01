@extends('layouts.app_layout')

@section('content')
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>All (<span class="text-danger">*</span>) marked fields are required.</p>

                    <form id="directoryEditForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Image</label>
                                @if ($directory->image)
                                    <div class="mb-2">
                                        <img src="{{ generate_file_view_path_for_backend($directory->image_url) }}"
                                            alt="Current Image"
                                            style="max-width: 200px; max-height: 200px; border-radius: 5px;">
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control" accept="image/*" />
                                <small class="text-muted">Accepted formats: JPEG, PNG, JPG, GIF, WebP (Max 5MB)</small>
                            </div>

                            <div class="col-md-3 col-12 mb-3">
                                <label class="form-label">Division Order</label>
                                <select name="order_no" class="form-control select2">
                                    @foreach ($divisionOrders as $divisionOrder)
                                        <option value="{{ $divisionOrder->order_no }}" {{ $directory->order_no == $divisionOrder->order_no ? 'selected' : '' }}>
                                            {{ $divisionOrder->order_no }} - {{ $divisionOrder->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 col-12 mb-3">
                                <label class="form-label">Show Order</label>
                                <input type="number" name="show_order" class="form-control" value="{{ $directory->show_order }}"
                                    placeholder="e.g. 1" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Name (English) <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $directory->name }}"
                                    required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Name (Hindi) <span class="text-danger">*</span> <x-translate-button source="name" target="name_hi" /></label>
                                <input type="text" name="name_hi" id="name_hi" class="form-control" value="{{ $directory->name_hi }}"
                                    required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">CPCB No.</label>
                                <input type="text" name="cpcb_no" class="form-control" value="{{ $directory->cpcb_no }}"
                                    placeholder="e.g. 12345" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Designation (English) <span class="text-danger">*</span></label>
                                <input type="text" name="designation" id="designation" class="form-control"
                                    value="{{ $directory->designation }}" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Designation (Hindi) <span class="text-danger">*</span> <x-translate-button source="designation" target="designation_hi" /></label>
                                <input type="text" name="designation_hi" id="designation_hi" class="form-control"
                                    value="{{ $directory->designation_hi }}" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Assigned Work (English)</label>
                                <textarea name="assigned_work" id="assigned_work" class="form-control"
                                    rows="2">{{ $directory->assigned_work }}</textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Assigned Work (Hindi) <x-translate-button source="assigned_work" target="assigned_work_hi" /></label>
                                <textarea name="assigned_work_hi" id="assigned_work_hi" class="form-control"
                                    rows="2">{{ $directory->assigned_work_hi }}</textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Division <span class="text-danger">*</span></label>
                                <select name="division_id" class="form-control select2" required>
                                    <option value="">-- Select Division --</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}" {{ $directory->division_id == $division->id ? 'selected' : '' }}>
                                            {{ $division->title }} ({{ $division->title_hi }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="text" name="email" class="form-control" value="{{ $directory->email }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Extension Number</label>
                                <input type="text" name="ext_number" class="form-control" maxlength="20"
                                    value="{{ $directory->ext_number }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Office Phone No.</label>
                                <input type="text" name="office_ph_no" class="form-control" maxlength="20"
                                    value="{{ $directory->office_ph_no }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile_no" class="form-control" maxlength="20"
                                    value="{{ $directory->mobile_no }}" />
                            </div>

                            <div class="col-12 text-center mt-3">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update
                                    Entry</button>
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
            $("#directoryEditForm").validate({
                rules: {
                    name: {
                        required: true
                    },
                    name_hi: {
                        required: true
                    },
                    designation: {
                        required: true
                    },
                    designation_hi: {
                        required: true
                    },
                    division_id: {
                        required: true
                    },
                    order_no: {
                        number: true
                    },
                    show_order: {
                        number: true
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('directories.update', $directory->id) }}",
                        method: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            showLoader();
                        },
                        success: function (response) {
                            hideLoader();
                            if (response.success) {
                                Swal.fire({
                                    title: "Updated!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('directories.index') }}";
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            hideLoader();
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessages = Object.values(errors).flat().join(
                                    "<br>");
                                Swal.fire({
                                    title: "Validation Error",
                                    html: errorMessages,
                                    icon: "error"
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Something went wrong.",
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
