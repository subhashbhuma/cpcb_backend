@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('who-is-who.create');
    @endphp
    @can('add who is who')
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
                        <table id="who-is-who-datatable" class="table table-striped table-bordered nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Order</th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Division</th>
                                    <th>Email Id</th>
                                    <th>Mobile Number</th>
                                    <th>Show On Homepage</th>
                                    <th>Hide on Who is Who</th>
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
            $('#who-is-who-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                scrollX: true,
                ajax: {
                    url: "{{ route('who-is-who.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                columns: [{
                    data: null,
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                    width: '8%',
                },
                {
                    data: 'order',
                    name: 'order',
                    width: '8%',
                },
                {
                    data: 'image',
                    name: 'image',
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'designation',
                    name: 'designation',
                },
                {
                    data: 'division.title',
                    name: 'division.title'
                },
                {
                    data: 'email_id',
                    name: 'email_id',
                },
                {
                    data: 'mobile_number',
                    name: 'mobile_number',
                },
                {
                    data: null,
                    name: 'show_on_homepage',
                    render: function (data) {
                        return data.show_on_homepage == 1 ? '<span class="badge bg-success">' + data.show_on_homepage_desc + '</span>' : '<span class="badge bg-warning">' + data.show_on_homepage_desc + '</span>';
                    }
                },
                {
                    data: null,
                    name: 'hide_on_who_is_who',
                    render: function (data) {
                        return data.hide_on_who_is_who == 1 ? '<span class="badge bg-success">' + data.show_on_homepage_desc + '</span>' : '<span class="badge bg-warning">' + data.show_on_homepage_desc + '</span>';
                    }
                },

                {
                    data: null,
                    name: 'is_approved',
                    render: function (data) {
                        if (data.is_approved == 1) {
                            return '<span class="badge bg-success">Approved</span>';
                        } else if (data.is_approved == 2) {
                            return '<span class="badge bg-danger">Rejected</span>';
                        } else {
                            return '<span class="badge bg-warning">Pending</span>';
                        }
                    }
                },
                {
                    data: null,
                    name: 'is_published',
                    render: function (data) {
                        return data.is_published == 1 ? '<span class="badge bg-success">Published</span>' : '<span class="badge bg-warning">Unpublished</span>';
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
            $(document).on('click', '.delete-who-is-who', function () {
                let contactDetailId = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to delete this detail.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('who-is-who.destroy', ':id') }}".replace(':id', contactDetailId),
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
                                $('#who-is-who-datatable').DataTable().ajax.reload();
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