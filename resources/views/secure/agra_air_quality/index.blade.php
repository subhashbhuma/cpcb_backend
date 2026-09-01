@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('agra-air-qualities.create');
    @endphp
    @can('add agra air quality')
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
                                    <th>Name of the Zone</th>
                                    <!-- <th>Title</th> -->
                                    <th>Date</th>
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
                ordering: true,
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('agra-air-qualities.fetch-for-datatable') }}",
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
                    },
                    class: "text-center",
                },
                {
                    data: 'quality_zone',
                    name: 'quality_zone',
                    class: "text-center",
                },
                // {
                //     data: 'title',
                //     name: 'title',
                //     class: "text-center",
                // },
                {
                    data: 'for_date',
                    name: 'for_date',
                    class: "text-center",

                },
                {
                    data: 'file_name',
                    name: 'file_name',
                    class: "text-center",
                },
                {
                    data: 'file_name_hi',
                    name: 'file_name_hi',
                    class: "text-center",
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
                            return '<span class="badge bg-warning">' + data.is_approved_desc + '</span>';
                        }
                    },
                    class: "text-center",
                },
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
                    },
                    class: "text-center",
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    class: "text-center",
                }
                ]
            });

            // Delete record
            $(document).on('click', '.delete-record', function () {
                var id = $(this).data('id');
                var url = "{{ route('agra-air-qualities.destroy', ':id') }}".replace(':id', id);

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
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    );
                                    $('#datatableList').DataTable().ajax.reload();
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function (xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong!',
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