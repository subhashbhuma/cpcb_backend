@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />
    <!-- [ Page Header ] end -->


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Full Name:</strong></label>
                            <p class="mb-0">{{ $feedback->full_name }}</p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Email:</strong></label>
                            <p class="mb-0">{{ $feedback->email }}</p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Phone:</strong></label>
                            <p class="mb-0">{{ $feedback->phone ?? 'N/A' }}</p>
                        </div>



                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Status:</strong></label>
                            <p class="mb-0">{!! $feedback->status_badge !!}</p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Submitted On:</strong></label>
                            <p class="mb-0">{{ $feedback->created_at->format('d M Y, h:i A') }}</p>
                        </div>

                        <div class="col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Message:</strong></label>
                            <p class="mb-0">{{ $feedback->message }}</p>
                        </div>

                        <div class="col-md-6 col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Attachment:</strong></label>
                            <p class="mb-0">
                                @if ($feedback->file_name)
                                    <a href="{{ generate_file_view_path_for_backend($feedback->file_full_path) }}"
                                        target="_blank">View Document</a>
                                @else
                                    <span class="text-muted">No file uploaded.</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Response History -->
            @if($feedback->histories->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-history"></i> Response History</h5>
                    </div>
                    <div class="card-body">
                        @foreach($feedback->histories as $history)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $history->respondedBy->name ?? 'System' }}</strong>
                                    <small class="text-muted">{{ $history->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                                <p class="mt-2 mb-1">{{ $history->revert_message }}</p>
                                <small class="text-muted">
                                    <i class="fa fa-envelope"></i> Email:
                                    @if($history->email_sent)
                                        <span class="text-success">Sent on {{ $history->email_sent_at->format('d M Y, h:i A') }}</span>
                                    @else
                                        <span class="text-danger">Not Sent</span>
                                    @endif
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
                <hr>
            @endif

            <!-- Send Response Form -->
            @can('respond to feedback')
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-reply"></i> Send Response</h5>
                    </div>
                    <div class="card-body">
                        <form id="revertForm">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Response Message <span class="text-danger">*</span></label>
                                <textarea name="revert_message" class="form-control" rows="5" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Update Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Keep Current Status</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success" id="sendResponseBtn">
                                    <i class="fa fa-paper-plane" id="sendIcon"></i>
                                    <span class="spinner-border spinner-border-sm d-none" id="sendSpinner" role="status"
                                        aria-hidden="true"></span>
                                    <span id="btnText">Send Response & Email</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection

@section('pages-scripts')
    @can('respond to feedback')
        <script @cspNonce>
            $(document).ready(function () {
                $("#revertForm").validate({
                    rules: {
                        revert_message: {
                            required: true
                        }
                    },
                    submitHandler: function (form) {
                        let formData = new FormData(form);
                        let $btn = $('#sendResponseBtn');
                        let $icon = $('#sendIcon');
                        let $spinner = $('#sendSpinner');
                        let $btnText = $('#btnText');

                        $.ajax({
                            url: "{{ route('feedback.send-revert', $feedback->id) }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            beforeSend: function () {
                                // Show loading state
                                $btn.prop('disabled', true);
                                $icon.addClass('d-none');
                                $spinner.removeClass('d-none');
                                $btnText.text('Sending...');
                            },
                            success: function (response) {
                                Swal.fire("Success!", response.message, "success").then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function (xhr) {
                                let errorMsg = xhr.responseJSON?.message || "Something went wrong!";
                                Swal.fire("Error!", errorMsg, "error");
                            },
                            complete: function () {
                                // Reset button state
                                $btn.prop('disabled', false);
                                $icon.removeClass('d-none');
                                $spinner.addClass('d-none');
                                $btnText.text('Send Response & Email');
                            }
                        });
                        return false;
                    }
                });
            });
        </script>
    @endcan
@endsection