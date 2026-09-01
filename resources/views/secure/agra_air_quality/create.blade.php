@extends('layouts.app_layout')

@section('content')
    <x-page-header title="{{ $pageTitle }}" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="agraAirQualityForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quality_zone_id">Name of the Zone <span class="text-danger">*</span></label>
                                    <select class="form-control" id="quality_zone_id" name="quality_zone_id" required>
                                        <option value="">Select Quality Zone</option>
                                        @foreach($qualityZones as $zone)
                                            <option value="{{ $zone->id }}">{{ $zone->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="for_date">For Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="for_date" name="for_date" required>
                                </div>
                            </div>

                            <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Title (English) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" name="title" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title_hi">Title (Hindi) <span class="text-danger">*</span>
                                                <x-translate-button source="title" target="title_hi" /></label>
                                            <input type="text" class="form-control" id="title_hi" name="title_hi" required>
                                        </div>
                                    </div> -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file_name">File (English) <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="file_name" name="file_name"
                                        accept=".pdf,.doc,.docx" required>
                                    <small class="form-text text-muted">Allowed formats: PDF, DOC, DOCX (Max: 10MB)</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file_name_hi">File (Hindi)</label>
                                    <input type="file" class="form-control" id="file_name_hi" name="file_name_hi"
                                        accept=".pdf,.doc,.docx">
                                    <small class="form-text text-muted">Allowed formats: PDF, DOC, DOCX (Max: 10MB)</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('agra-air-qualities.index') }}" class="btn btn-secondary">Cancel</a>
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
            datePickerInit('for_date');
            $('#agraAirQualityForm').validate({
                rules: {
                    quality_zone_id: {
                        required: true
                    },
                    // title: {
                    //     required: true,
                    //     maxlength: 255
                    // },
                    // title_hi: {
                    //     required: true,
                    //     maxlength: 255
                    // },
                    file_name: {
                        required: true,
                    },
                    for_date: {
                        required: true,
                        date: true
                    }
                },
                submitHandler: function (form) {
                    var formData = new FormData(form);

                    console.log('Form submission started');
                    console.log('Form action URL:', "{{ route('agra-air-qualities.store') }}");

                    $.ajax({
                        url: "{{ route('agra-air-qualities.store') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function () {
                            console.log('AJAX request starting...');
                        },
                        success: function (response) {
                            console.log('Success response:', response);
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                }).then(() => {
                                    window.location.href = "{{ route('agra-air-qualities.index') }}";
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Error:', {
                                status: xhr.status,
                                statusText: xhr.statusText,
                                responseText: xhr.responseText,
                                error: error
                            });

                            var errorMessage = 'An error occurred';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMessage = '';
                                $.each(xhr.responseJSON.errors, function (key, value) {
                                    errorMessage += value[0] + '<br>';
                                });
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.status === 419) {
                                errorMessage = 'CSRF token mismatch. Please refresh the page and try again.';
                            } else if (xhr.status === 403) {
                                errorMessage = 'You do not have permission to perform this action.';
                            } else if (xhr.status === 500) {
                                errorMessage = 'Server error. Please check the logs.';
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: errorMessage,
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
