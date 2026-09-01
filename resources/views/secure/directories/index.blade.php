@extends('layouts.app_layout')

@section('content')
    @php
        $addRoute = route('directories.create');
    @endphp
    @can('add directory')
        @php
            $button = '<a href="' . $addRoute . '" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>';
        @endphp
    @endcan
    <x-page-header title="{{ $pageTitle }}" button="{!! (isset($button)) ? $button : '' !!}" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="directory-datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>CPCB No.</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Division</th>
                                    <!-- <th>Contact Details</th> -->
                                    <th>Order</th>
                                    <th>Show Order</th>
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
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {
            $('#directory-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('directories.fetch-for-datatable')}}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },

                columns: [
                    {
                        data: 'section_serial',
                        width: '8%',
                        render: function (data) {
                            return data;
                        }
                    },

                    {
                        data: 'cpcb_no',
                        name: 'cpcb_no'
                    },
                    {
                        data: 'name', // Shows English name by default
                        name: 'name'
                    },
                    {
                        data: 'designation',
                        name: 'designation'
                    },
                    {
                        data: 'division.title',
                        name: 'division.title',
                        render: function (data) {
                            return data ? data : '<span class="text-muted">N/A</span>';
                        }
                    },
                    // {
                    //     data: null,
                    //     name: 'email',
                    //     render: function (data) {
                    //         let contact = '';
                    //         if (data.email) contact += '<div><i class="fa fa-envelope small"></i> ' + data.email + '</div>';
                    //         if (data.mobile_no) contact += '<div><i class="fa fa-phone small"></i> ' + data.mobile_no + '</div>';
                    //         if (data.ext_number) contact += '<div><i class="fa fa-phone-square small"></i> Ext: ' + data.ext_number + '</div>';
                    //         return contact ? contact : '<span class="text-muted">No Contact</span>';
                    //     }
                    // },
                    {
                        data: 'division_order.title',
                        name: 'division_order.title',
                        className: 'text-center'
                    },
                    {
                        data: 'show_order',
                        name: 'show_order',
                        className: 'text-center'
                    },
                    // {
                    //     data: null,
                    //     name: 'is_approved',
                    //     width: '10%',
                    //     render: function (data, type, row) {
                    //         if (data.is_approved == 1) {
                    //             return '<span class="badge bg-success">' + data.is_approved_desc + '</span>';
                    //         } else if (data.is_approved == 2) {
                    //             return '<span class="badge bg-danger">' + data.is_approved_desc + '</span>';
                    //         } else {
                    //             return '<span class="badge bg-warning">' + data.is_approved_desc + '</span>';
                    //         }
                    //     }
                    // },
                    {
                        data: null,
                        name: 'is_published',
                        width: '10%',
                        render: function (data, type, row) {
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
                        width: '15%',
                    }
                ]
            });

            $(document).on('click', '.delete-btn', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to delete this directory entry.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('directories.destroy', ':id') }}".replace(':id', id),
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
                                $('#directory-datatable').DataTable().ajax.reload();
                            },
                            error: function (xhr) {
                                hideLoader();
                                Swal.fire("Error!", "Something went wrong while deleting.", "error");
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
