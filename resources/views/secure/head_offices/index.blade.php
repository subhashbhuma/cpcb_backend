@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('head_offices.create');
    @endphp
    @can('add head office')
        @php
            $button = '<a href="' . $addRoute . '" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>';
        @endphp
    @endcan
    <x-page-header title="{{ $pageTitle }}" button="{!! isset($button) ? $button : '' !!}" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="head-office-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Division</th>
                                    <th>Title</th>
                                    <th>Email</th>
                                    <th>Ext. No.</th>
                                    <th>Order</th>
                                    <th>Approval Status</th>
                                    <th>Publish Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
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
            var table = $('#head-office-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('head_offices.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'division.title',
                    name: 'division.title'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'ext_number',
                    name: 'ext_number'
                },
                {
                    data: 'order',
                    name: 'order'
                },
                {
                    data: 'is_approved',
                    name: 'is_approved'
                },
                {
                    data: 'is_published',
                    name: 'is_published'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                ]
            });

            // Delete Action
            $(document).on('click', '.delete-btn', function () {
                var url = $(this).data('url');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    );
                                    table.ajax.reload();
                                }
                            },
                            error: function (xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection