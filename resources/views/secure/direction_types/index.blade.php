@extends('layouts.app_layout')
@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('direction-types.create');
    @endphp

    @can('add direction type')
        @php
            $button =
                '<a href="' .
                $addRoute .
                '" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New
                       </a>';
        @endphp
    @endcan

    <x-page-header title="{{ $pageTitle }}" button="{!! (isset($button)) ? $button : '' !!}" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="direction-type-datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
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
            const table = $('#direction-type-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('direction-types.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                columns: [{
                    data: null,
                    width: '8%',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'direction_act_type_id',
                    render: function (data, type, row) {
                        return row.direction_act_type ? row.direction_act_type.title : '—';
                    },
                    name: 'direction_act_type.title',
                },
                {
                    data: null,
                    name: 'title',
                    render: function (data, type, row) {
                        return `${row.title} (${row.title_hi})`;
                    }
                },
                {
                    data: null,
                    width: '10%',
                    render: function (data) {
                        if (data.is_approved == 1) {
                            return '<span class="badge bg-success">' + data.is_approved_desc +
                                '</span>';
                        } else if (data.is_approved == 2) {
                            return '<span class="badge bg-danger">' + data.is_approved_desc +
                                '</span>';
                        }
                        return '<span class="badge bg-warning">' + data.is_approved_desc +
                            '</span>';
                    }
                },
                {
                    data: null,
                    width: '10%',
                    render: function (data) {
                        if (data.is_published == 1) {
                            return '<span class="badge bg-success">' + data.is_published_desc +
                                '</span>';
                        }
                        return '<span class="badge bg-warning">' + data.is_published_desc +
                            '</span>';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    width: '12%'
                }
                ]
            });

            /* ===============================
             * DELETE EVENT
             * =============================== */
            $(document).on('click', '.delete-btn', function () { // Class from my controller is delete-btn
                let id = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to delete this event.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('secure/direction-types') }}/" + id,
                            type: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function () {
                                // showLoader(); // Assuming global function
                            },
                            success: function (response) {
                                // hideLoader();
                                Swal.fire("Deleted!", response.message, "success");
                                table.ajax.reload();
                            },
                            error: function () {
                                // hideLoader();
                                Swal.fire("Error!", "Something went wrong!", "error");
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection