@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('circulars.create');
    @endphp
    @can('add circular')
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
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="status_filter" class="form-label">Filter by Status:</label>
                                <select id="status_filter" class="form-control selectpicker">
                                    <option value="">All</option>
                                    <option value="published"
                                        {{ request()->get('status') == 'published' ? 'selected' : '' }}>
                                        Published</option>
                                    <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                </select>
                            </div>
                        </div>
                        <table id="circulars-datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Type</th>
                                    <th>Division</th>
                                    <th>Title (English)</th>
                                    <th>Title (हिंदी)</th>
                                    <th>Published Date</th>
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
        $(document).ready(function() {
            var table = $('#circulars-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('circulars.fetch-for-datatable-backend') }}",
                    type: "POST",
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.status = $('#status_filter').val();
                    }
                },
                columns: [{
                        data: null,
                        name: 'id',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },

                    {
                        data: 'circular_category_name',
                        name: 'circular_category_name'
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
                        data: 'title_hi',
                        name: 'title_hi'
                    },
                    {
                        data: 'published_date',
                        name: 'published_date'
                    },
                    @if (!Auth::user()->hasRole('Employee'))
                        {
                            data: null,
                            name: 'is_approved',
                            width: '10%',
                            render: function(data, type, row) {
                                if (data.is_approved == 1) {
                                    return '<span class="badge bg-success">' + data
                                        .is_approved_desc + '</span>';
                                } else if (data.is_approved == 2) {
                                    return '<span class="badge bg-danger">' + data
                                        .is_approved_desc + '</span>';
                                } else {
                                    return '<span class="badge bg-warning">' + data
                                        .is_approved_desc + '</span>';
                                }
                            }
                        }, {
                            data: null,
                            name: 'is_published',
                            width: '10%',
                            render: function(data, type, row) {
                                if (data.is_published == 1) {
                                    return '<span class="badge bg-success">' + data
                                        .is_published_desc + '</span>';
                                } else {
                                    return '<span class="badge bg-warning">' + data
                                        .is_published_desc + '</span>';
                                }
                            }
                        },
                    @endif {
                        data: 'action',
                        name: 'action',
                        width: '12%'
                    }
                ]
            });


            $('#status_filter, #filter-archive').on('change', function() {
                table.draw();
            });

            $(document).on('click', '.delete-circular', function() {
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
                            url: "{{ route('circulars.destroy', ':id') }}".replace(':id',
                                id),
                            type: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function() {
                                showLoader();
                            },
                            success: function(response) {
                                hideLoader();
                                Swal.fire("Deleted!", response.message, "success");
                                $('#circulars-datatable').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
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
