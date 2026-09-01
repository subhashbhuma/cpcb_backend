@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('jobs.create');
    @endphp
    @can('add job')
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
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="status_filter" class="form-label">Filter by Status:</label>
                                <select id="status_filter" class="form-control selectpicker">
                                    <option value="">All</option>
                                    <option value="published" {{ request()->get('status') == 'published' ? 'selected' : '' }}>
                                        Published</option>
                                    <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                </select>
                            </div>
                        </div>
                        <table id="jobs-datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Job Vacancy</th>
                                    <th>Timeline</th>
                                    <th>Advt.</th>
                                    <th>Direct Form</th>
                                    <th>Deputation Form</th>
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
            $('#jobs-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                scrollX: false,
                ajax: {
                    url: "{{ route('jobs.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.status = $('#status_filter').val();
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
                    data: 'vacancy_details',
                    name: 'vacancy_details'
                },
                {
                    data: 'timeline',
                    name: 'timeline',
                    width: '15%'
                },
                {
                    data: 'advt',
                    name: 'advt',
                    className: 'text-center',
                    width: '10%'
                },
                {
                    data: 'direct_form',
                    name: 'direct_form',
                    className: 'text-center',
                    width: '10%'
                },
                {
                    data: 'deputation_form',
                    name: 'deputation_form',
                    className: 'text-center',
                    width: '12%'
                },
                {
                    data: 'status',
                    name: 'status',
                    width: '12%'
                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'text-center',
                    width: '12%',
                }
                ]
            });

            $('#status_filter').on('change', function () {
                $('#jobs-datatable').DataTable().draw();
            });

            /**
             * Delete record
             */
            $(document).on('click', '.delete-jobs', function () {
                let Id = $(this).data('id');

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
                            url: "{{ route('jobs.destroy', ':id') }}".replace(':id', Id),
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
                                $('#jobs-datatable').DataTable().ajax.reload();
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