@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('environmental-regulation-details.create');
    @endphp

    @can('add environmental regulation')
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
                        <table id="environmental-regulation-details-datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Environmental Tab</th>
                                    <th>Title</th>
                                    <th>Parent</th>
                                    <th>Order</th>
                                    <th>URL</th>
                                    <th>Type</th>
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
            $('#environmental-regulation-details-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('environmental-regulation-details.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
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
                        data: 'tab_name',
                        name: 'title'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'parent_title',
                        name: 'parent_title'
                    },
                    {
                        data: 'order',
                        name: 'order',
                        width: '6%'
                    },
                    {
                        data: 'url',
                        name: 'url'
                    },
                    {
                        data: 'type',
                        name: 'type',
                        width: '8%'
                    },
                    {
                        data: null,
                        name: 'is_approved',
                        width: '10%',
                        render: function (data) {
                            if (data.is_approved == 1) {
                                return '<span class="badge bg-success">' + data.is_approved_desc + '</span>';
                            } else if (data.is_approved == 2) {
                                return '<span class="badge bg-danger">' + data.is_approved_desc + '</span>';
                            } else {
                                return '<span class="badge bg-warning">' + data.is_approved_desc + '</span>';
                            }
                        }
                    },
                    {
                        data: null,
                        name: 'is_published',
                        width: '10%',
                        render: function (data) {
                            if (data.is_published == 1) {
                                return '<span class="badge bg-success">' + data.is_published_desc + '</span>';
                            } else {
                                return '<span class="badge bg-warning">' + data.is_published_desc + '</span>';
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        width: '12%'
                    }
                ]
            });

            /* -----------------------------
             | Delete Record
             |-----------------------------*/
            $(document).on('click', '.delete-record', function () {
                let id = $(this).data('id');

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
                            url: "{{ route('environmental-regulation-details.destroy', ':id') }}".replace(':id', id),
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
                                $('#environmental-regulation-details-datatable').DataTable().ajax.reload();
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