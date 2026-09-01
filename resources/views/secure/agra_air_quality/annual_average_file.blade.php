@extends('layouts.app_layout')

@section('content')
    <x-page-header title="{{ $pageTitle }}" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Manage Annual Average File</h5>
                    <p class="text-muted">Upload or replace the annual average report file: <strong>{{ $fileName }}</strong>
                    </p>
                </div>
                <div class="card-body">
                    @if($fileExists)
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> File exists and is available for viewing.
                        </div>
                        <div class="mb-3">
                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-info">
                                <i class="fa fa-eye"></i> View Current File
                            </a>
                            <button type="button" class="btn btn-danger" id="deleteFileBtn">
                                <i class="fa fa-trash"></i> Delete File
                            </button>
                        </div>
                        <hr>
                        <h6>Replace Existing File</h6>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i> No file uploaded yet.
                        </div>
                        <h6>Upload New File</h6>
                    @endif

                    <form id="uploadFileForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="file">Select PDF File <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="file" name="file" accept=".pdf" required>
                                    <small class="form-text text-muted">
                                        Allowed format: PDF only (Max: 10MB)<br>
                                        File will be saved as: <strong>{{ $fileName }}</strong>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-upload"></i> {{ $fileExists ? 'Replace File' : 'Upload File' }}
                            </button>
                            <a href="{{ route('agra-air-qualities.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
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
            // File upload form submission
            $('#uploadFileForm').on('submit', function (e) {
                e.preventDefault();

                var fileInput = $('#file')[0];
                var file = fileInput.files[0];

                // Manual validation
                if (!file) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please select a PDF file',
                    });
                    return false;
                }

                // Check file extension
                var fileName = file.name;
                var fileExtension = fileName.split('.').pop().toLowerCase();
                if (fileExtension !== 'pdf') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Only PDF files are allowed',
                    });
                    return false;
                }

                // Check file size (10MB = 10485760 bytes)
                if (file.size > 10485760) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'File size must not exceed 10MB',
                    });
                    return false;
                }

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('agra-air-quality.upload-annual-average-file') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Uploading...',
                            text: 'Please wait while the file is being uploaded',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.close();
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
                        } else if (xhr.status === 500) {
                            errorMessage = 'Server error. Please try again.';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage,
                        });
                    }
                });

                return false;
            });

            // Delete file button
            $('#deleteFileBtn').on('click', function () {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete this file?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('agra-air-quality.delete-annual-average-file') }}",
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: response.message,
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message,
                                    });
                                }
                            },
                            error: function (xhr) {
                                var errorMessage = xhr.responseJSON?.message || 'An error occurred';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMessage,
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection