@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('contact-details.create');
    @endphp
    @can('add contact detail')
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
                        <table id="contact-details-datatable" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Title</th>
                                    <th>Department</th>
                                    <th>Phone Numbers</th>
                                    <th>Email IDs</th>
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
    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Approve Contact Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="approveForm">
                    <div class="modal-body">
                        <input type="hidden" id="approve_id" name="id">
                        <div class="form-group">
                            <label for="is_approved">Status</label>
                            <select class="form-control" id="is_approved" name="is_approved" required>
                                <option value="1">Approve</option>
                                <option value="2">Reject</option>
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Publish Modal -->
    <div class="modal fade" id="publishModal" tabindex="-1" role="dialog" aria-labelledby="publishModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="publishModalLabel">Publish Contact Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="publishForm">
                    <div class="modal-body">
                        <input type="hidden" id="publish_id" name="id">
                        <div class="form-group">
                            <label for="is_published">Status</label>
                            <select class="form-control" id="is_published" name="is_published" required>
                                <option value="1">Publish</option>
                                <option value="0">Unpublish</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {
            $('#contact-details-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                scrollX: true,
                ajax: {
                    url: "{{ route('contact-details.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                columns: [
                    {
                        data: null,
                        name: 'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        },
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'phone_numbers',
                        name: 'phone_numbers',
                    },
                    {
                        data: 'email_ids',
                        name: 'email_ids',
                    },

                    {
                        render: function (data, type, row) {
                            let status = '';
                            if (row.is_approved == 1) {
                                status = '<span class="badge bg-success change-approval" data-id="' + row.id + '" data-status="' + row.is_approved + '" style="cursor:pointer;">Approved</span>';
                            } else if (row.is_approved == 2) {
                                status = '<span class="badge bg-danger change-approval" data-id="' + row.id + '" data-status="' + row.is_approved + '" style="cursor:pointer;">Rejected</span>';
                            } else {
                                status = '<span class="badge bg-warning change-approval" data-id="' + row.id + '" data-status="' + row.is_approved + '" style="cursor:pointer;">Pending</span>';
                            }
                            return status;
                        }
                    },
                    {
                        render: function (data, type, row) {
                            return row.is_published == 1 ?
                                '<span class="badge bg-success change-publish" data-id="' + row.id + '" data-status="' + row.is_published + '" style="cursor:pointer;">Published</span>' :
                                '<span class="badge bg-warning change-publish" data-id="' + row.id + '" data-status="' + row.is_published + '" style="cursor:pointer;">Unpublished</span>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Delete contact detail
            $(document).on('click', '.delete-contact-detail', function () {
                let contactDetailId = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to delete this contact detail.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('contact-details.destroy', ':id') }}".replace(':id', contactDetailId),
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
                                $('#contact-details-datatable').DataTable().ajax.reload();
                            },
                            error: function () {
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