@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('job-posts.create');
    @endphp
    @can('add job post')
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
                            <div class="col-md-3">
                                <label for="status_filter" class="form-label">Filter by Status:</label>
                                <select id="status_filter" class="form-control selectpicker">
                                    <option value="">All</option>
                                    <option value="published" {{ request()->get('status') == 'published' ? 'selected' : '' }}>
                                        Published</option>
                                    <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                </select>
                            </div>
                        </div>
                        <table id="job-posts-datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Job/Vacancy</th>
                                    <th>Post Title</th>
                                    <th>Is Approved</th>
                                    <th>Is Published</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {
            $('#job-posts-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                scrollX: true,
                ajax: {
                    url: "{{ route('job-posts.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.status = $('#status_filter').val();
                    }
                },
                columns: [
                    {
                        data: null,
                        name: 'id',
                        width: '8%',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'job_title',
                        name: 'job_title'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: null,
                        name: 'is_approved',
                        width: '10%',
                        render: function (data, type, row) {
                            if (data.is_approved == 1) {
                                return '<span class="badge bg-success">' + data.is_approved_desc + '</span>';
                            } else if (data.is_approved == 2) {
                                return '<span class="badge bg-danger">' + data.is_approved_desc + '</span>';
                            } else {
                                return '<span class="badge bg-warning text-dark">' + data.is_approved_desc + '</span>';
                            }
                        }
                    },
                    {
                        data: null,
                        name: 'is_published',
                        width: '10%',
                        render: function (data, type, row) {
                            if (data.is_published == 1) {
                                return '<span class="badge bg-success">' + data.is_published_desc + '</span>';
                            } else {
                                return '<span class="badge bg-warning text-dark">' + data.is_published_desc + '</span>';
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        width: '15%',
                    }
                ]
            });

            $('#status_filter').on('change', function () {
                $('#job-posts-datatable').DataTable().draw();
            });

            $(document).on('click', '.approve-job-post', function () {
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
                        url: "{{ route('job-posts.approve', ':id') }}".replace(':id', id),
                        type: 'PUT',
                        data: {
                            is_approved: status,
                            remarks: result.value || '',
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            Swal.fire('Success', response.message, 'success');
                            $('#job-posts-datatable').DataTable().ajax.reload();
                        }
                    });
                });
            });

            $(document).on('click', '.publish-job-post', function () {
                let id = $(this).data('id');
                let status = $(this).data('status');
                let actionText = status == 1 ? 'Publish' : 'Unpublish';

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to " + actionText + " this post.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, ' + actionText + ' it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('job-posts.publish', ':id') }}".replace(':id', id),
                            type: 'PUT',
                            data: {
                                is_published: status,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                Swal.fire('Success', response.message, 'success');
                                $('#job-posts-datatable').DataTable().ajax.reload();
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.delete-job-post', function () {
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
                            url: "{{ route('job-posts.destroy', ':id') }}".replace(':id', Id),
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
                                $('#job-posts-datatable').DataTable().ajax.reload();
                            },
                            error: function (xhr) {
                                hideLoader();
                                Swal.fire("Error!", "Something went wrong!", "error");
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection