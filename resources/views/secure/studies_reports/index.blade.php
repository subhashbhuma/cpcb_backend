@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('studies_reports.create');
    @endphp
    @can('add studies report')
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
                        <table id="studies-reports-datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Title</th>
                                    <th>Division</th>
                                    <th>Year</th>
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
            $('#studies-reports-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                scrollX: true,
                ajax: {
                    url: "{{ route('studies_reports.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                columns: [{
                    data: null,
                    name: 'id',
                    width: '5%',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'division.title',
                    name: 'division.title'
                },
                {
                    data: 'report_year',
                    name: 'report_year'
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
                    width: '15%',
                }
                ]
            });

            $(document).on('click', '.delete-btn', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to delete this record.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('studies_reports.destroy', ':id') }}".replace(':id', id),
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
                                $('#studies-reports-datatable').DataTable().ajax.reload();
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