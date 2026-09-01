@extends('layouts.app_layout')

@section('content')
    @php
        $addRoute = route('recruitment-announcements.create');
    @endphp
    @can('add recruitment announcement')
        @php
            $button = '<a href="' . $addRoute . '" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>';
        @endphp
    @endcan
    <x-page-header title="{{ $pageTitle }}" button="{!! (isset($button)) ? $button : '' !!}" />

    <!-- [ Page Header ] end -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-responsive">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="job_filter" class="form-label">Filter by Job:</label>
                                <select id="job_filter" class="form-control select2">
                                    <option value="">All Jobs</option>
                                    @foreach($jobs as $job)
                                        <option value="{{ $job->id }}">{{ $job->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="status_filter" class="form-label">Filter by Status:</label>
                                <select id="status_filter" class="form-control selectpicker">
                                    <option value="">All Status</option>
                                    <option value="published" {{ request()->get('status') == 'published' ? 'selected' : '' }}>
                                        Published</option>
                                    <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                </select>
                            </div>
                        </div>
                        <table id="recruitment-announcements-datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Job/Vacancy</th>
                                    <th>Post Name</th>
                                    <th>Title</th>
                                    <th>Dates</th>
                                    <th>Files</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Files Modal -->
    <div class="modal fade" id="importFilesModal" tabindex="-1" aria-labelledby="importFilesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="importFilesForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importFilesModalLabel">Import Recruitment Announcements</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Note:</strong> This import will <strong>update</strong> existing records (if `id` is present) and <strong>insert</strong> new records (if `id` is blank).
                            It relies on `post_id` to link to a Job Post.
                        </div>
                        <div class="mb-3">
                            <label for="import_file" class="form-label">Select Excel File (.xlsx, .xls, .csv)</label>
                            <input class="form-control" type="file" id="import_file" name="import_file" accept=".xlsx, .xls, .csv" required>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <p class="mb-0 text-muted"><strong>Download Format / Current Data:</strong></p>
                                <a href="{{ route('recruitment-announcements.import.format') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-download"></i> Download Format
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="importFilesSubmitBtn">
                            <i class="fa fa-upload"></i> Import Data
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
            $('#recruitment-announcements-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                scrollX: false,
                ajax: {
                    url: "{{ route('recruitment-announcements.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.status = $('#status_filter').val();
                        d.job_id = $('#job_filter').val();
                        d.type = $('#type_filter').val();
                    }
                },
                columns: [
                    {
                        data: null,
                        name: 'id',
                        width: '5%',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'job_title',
                        name: 'job_title'
                    },
                    {
                        data: 'post_title',
                        name: 'post_title'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: null,
                        name: 'dates',
                        render: function (row) {
                            let dates = '';
                            if (row.start_date) dates += 'Start: ' + row.start_date + '<br>';
                            if (row.end_date) dates += 'End: ' + row.end_date;
                            return dates || '-';
                        }
                    },
                    {
                        data: 'file',
                        name: 'file'
                    },
                    {
                        data: null,
                        name: 'status',
                        render: function (row) {
                            let statusHtml = '';
                            if (row.is_published == 1) statusHtml += '<br><span class="badge bg-success">Published</span>';
                            else statusHtml += '<span class="badge bg-warning">Draft</span>';
                            return statusHtml;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        width: '15%',
                    }
                ]
            });

            $('#job_filter').select2({
                placeholder: "All Jobs",
                allowClear: true
            });

            $('#status_filter, #type_filter, #job_filter').on('change', function () {
                $('#recruitment-announcements-datatable').DataTable().draw();
            });

            $(document).on('click', '.approve-announcement', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Approval Decision',
                    input: 'textarea',
                    inputLabel: 'Remarks (Optional)',
                    inputPlaceholder: 'Enter your remarks here...',
                    showCancelButton: true,
                    confirmButtonText: 'Approve',
                    cancelButtonText: 'Reject',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545',
                    showDenyButton: true,
                    denyButtonText: 'Close',
                    denyButtonColor: '#6c757d',
                }).then((result) => {
                    let status = 0;
                    if (result.isConfirmed) status = 1;
                    else if (result.dismiss === Swal.DismissReason.cancel) status = 2;
                    else return;

                    $.ajax({
                        url: "{{ route('recruitment-announcements.approve', ':id') }}".replace(':id', id),
                        type: 'PUT',
                        data: {
                            is_approved: status,
                            remarks: result.value || '',
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            Swal.fire('Success', response.message, 'success');
                            $('#recruitment-announcements-datatable').DataTable().ajax.reload();
                        }
                    });
                });
            });

            $(document).on('click', '.publish-announcement', function () {
                let id = $(this).data('id');
                let status = $(this).data('status');
                let actionText = status == 1 ? 'Publish' : 'Unpublish';

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to " + actionText + " this announcement.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, ' + actionText + ' it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('recruitment-announcements.publish', ':id') }}".replace(':id', id),
                            type: 'PUT',
                            data: {
                                is_published: status,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                Swal.fire('Success', response.message, 'success');
                                $('#recruitment-announcements-datatable').DataTable().ajax.reload();
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.delete-recruitment-announcement', function () {
                let Id = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to delete the record.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('recruitment-announcements.destroy', ':id') }}".replace(':id', Id),
                            type: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function () {
                                showLoader();
                            },
                            success: function (response) {
                                hideLoader();
                                Swal.fire("Deleted!", response.message, "success");
                                $('#recruitment-announcements-datatable').DataTable().ajax.reload();
                            },
                            error: function (xhr) {
                                hideLoader();
                                Swal.fire("Error!", "Something went wrong!", "error");
                            }
                        });
                    }
                });
            });

            /**
             * IMPORT FILES EXCEL
             */
            $('#importFilesForm').on('submit', function (e) {
                e.preventDefault();
                let form = this;
                let formData = new FormData(form);
                let submitBtn = $('#importFilesSubmitBtn');
                let originalText = submitBtn.html();

                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Importing...').prop('disabled', true);

                $.ajax({
                    url: "{{ route('recruitment-announcements.import') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        submitBtn.html(originalText).prop('disabled', false);
                        $('#importFilesModal').modal('hide');
                        if (response.success) {
                            Swal.fire({
                                title: "Imported!",
                                text: response.message,
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                $('#recruitment-announcements-datatable').DataTable().ajax.reload();
                            });
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        submitBtn.html(originalText).prop('disabled', false);
                        let message = 'An error occurred during import.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        Swal.fire({
                            title: "Import Error",
                            html: message,
                            icon: "error"
                        });
                    }
                });
            });
        });
    </script>
@endsection