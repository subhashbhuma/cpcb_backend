@extends('layouts.app_layout')

@section('content')
    <x-page-header title="Edit Complaint" :backButton="true" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>
                        All (<span class="text-danger">*</span>) marked fields are required.
                    </p>
                    <form id="ComplaintForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Full Name: <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control" value="{{ $Complaint->full_name }}"
                                    required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Email: <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ $Complaint->email }}"
                                    required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Phone:</label>
                                <input type="text" name="phone" class="form-control" value="{{ $Complaint->phone }}" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Subject: <span class="text-danger">*</span></label>
                                <select name="complaint_subject_id" class="form-control" required>
                                    <option value="">Select Subject</option>
                                    @foreach(\App\Models\ComplaintFormSubject::where('is_published', 1)->get() as $subject)
                                        <option value="{{ $subject->id }}" {{ $Complaint->complaint_subject_id == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Status: <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="pending" {{ $Complaint->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="in_progress" {{ $Complaint->status == 'in_progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                    <option value="resolved" {{ $Complaint->status == 'resolved' ? 'selected' : '' }}>Resolved
                                    </option>
                                    <option value="closed" {{ $Complaint->status == 'closed' ? 'selected' : '' }}>Closed
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Message: <span class="text-danger">*</span></label>
                                <textarea name="message" class="form-control" rows="5"
                                    required>{{ $Complaint->message }}</textarea>
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
            $("#ComplaintForm").validate({
                rules: {
                    full_name: { required: true },
                    email: { required: true, email: true },
                    complaint_subject_id: { required: true },
                    status: { required: true },
                    message: { required: true }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: "{{ route('complaint.update', $Complaint->id) }}",
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
                                    window.location.href = "{{ route('complaint.index') }}";
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