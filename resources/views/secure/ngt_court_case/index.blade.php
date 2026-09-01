@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('ngt-court-cases.create');
    @endphp
    @can('add ngt court case')
        @php
            $button = '<a href="' . $addRoute . '" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>';
        @endphp
    @endcan
    <x-page-header title="{{ $pageTitle }}" button="{!! isset($button) ? $button : '' !!}" />

    <!-- [ Page Header ] end -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="datatableList" class="table table-striped table-bordered w-full">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Publish Date</th>
                                    <th>File (English)</th>
                                    <th>File (हिंदी)</th>
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
            $('#datatableList').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('ngt-court-cases.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                columns: [{
                    data: null,
                    name: 'id',
                    width: '8%',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'publish_date',
                    name: 'publish_date'
                },
                {
                    data: 'file_name',
                    name: 'file_name'
                },
                {
                    data: 'file_name_hi',
                    name: 'file_name_hi'
                },
                {
                    data: null,
                    name: 'is_approved',
                    width: '10%',
                    render: function (data, type, row) {
                        if (data.is_approved == 1) {
                            return '<span class="badge bg-success">' + data.is_approved_desc +
                                '</span>';
                        } else if (data.is_approved == 2) {
                            return '<span class="badge bg-danger">' + data.is_approved_desc +
                                '</span>';
                        } else {
                            return '<span class="badge bg-warning">' + data.is_approved_desc +
                                '</span>';
                        }
                    }
                },
                {
                    data: null,
                    name: 'is_published',
                    width: '10%',
                    render: function (data, type, row) {
                        if (data.is_published == 1) {
                            return '<span class="badge bg-success">' + data.is_published_desc +
                                '</span>';
                        } else {
                            return '<span class="badge bg-warning">' + data.is_published_desc +
                                '</span>';
                        }
                    }
                },

                {
                    data: 'action',
                    name: 'action',
                    width: '12%',
                }
                ]
            });

            /**
             * Delete record
             */
            $(document).on('click', '.delete-record', function () {
                let roleId = $(this).data('id');

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
                            url: "{{ route('ngt-court-cases.destroy', ':id') }}".replace(':id',
                                roleId),
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
                                $('#datatableList').DataTable().ajax.reload();
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