@extends('layouts.app_layout')

@section('content')
    @php
        $addRoute = route('direction_issued_to.create');
    @endphp

    <x-page-header title="Direction Issued To List"
        button='<a href="{{ $addRoute }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>' />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="direction-issued-to-datatable" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Sl. No</th>
                                    <th>Act Type</th>
                                    <th>Title</th>
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
            $('#direction-issued-to-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('direction_issued_to.fetch-for-datatable') }}",
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
                        data: 'direction_act_type_id', // Note: Using ID or title? Relationship is eager loaded.
                        render: function (data, type, row) {
                            return row.direction_act_type ? row.direction_act_type.title : '—';
                        },
                        name: 'direction_act_type.title',
                    },
                    {
                        data: 'title',
                        name: 'title',
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
            })

            $(document).on('click', '.delete-btn', function () {
                let url = $(this).data('url');

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
                            url: url,
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
                                $('#direction-issued-to-datatable').DataTable().ajax.reload();
                            },
                            error: function (xhr) {
                                hideLoader();
                                Swal.fire("Error!", "Something went wrong!", "error");
                            }
                        });
                    }
                });
            });
        })

    </script>
@endsection