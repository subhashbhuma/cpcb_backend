@extends('layouts.app_layout')
@section('style')
    <style @cspNonce>
        /* Location group header row */
        .group-header td {
            background: #4680ff !important;
            color: #fff !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            font-size: 0.95rem !important;
            padding: 8px 15px !important;
        }

        .no-page-row td:nth-child(2) {
            color: #6c757d;
        }

        #pages-datatable td:first-child {
            white-space: nowrap;
        }

        #pages-datatable td:nth-child(2) {
            white-space: normal;
            word-break: break-word;
        }
    </style>

@endsection
@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('pages.create');
    @endphp
    @can('add page')
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
                        <table id="pages-datatable" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Page Type</th>
                                    <th>Page Title</th>
                                    <th>Page Url</th>
                                    <th>Status</th>
                                    <th>Actions</th>
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
            var table = $('#pages-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pages.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                columns: [
                    { data: 'sl_no', name: 'sl_no', className: 'text-center fw-semibold', width: "10%" },
                    { data: 'type', name: 'type', className: 'text-center', width: "10%" },
                    { data: 'menu_title', name: 'menu_title', width: "60%" },
                    { data: 'page_url', name: 'menu_title', orderable: false, searchable: false, width: "5%" },
                    { data: 'status', name: 'status', className: 'text-center', orderable: false, searchable: false, width: "5%" },
                    { data: 'actions', name: 'actions', className: 'text-center', orderable: false, searchable: false, width: "10%" },
                    { data: 'menu_url', name: 'menu_url', visible: false, searchable: true }
                ],
                ordering: false,
                paging: true,
                pageLength: 10,
                lengthMenu: [[10, 50, 100, 500], [10, 50, 100, 500]],
                language: {
                    search: "Search Pages:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ menu entries",
                    emptyTable: "No pages found."
                },
            });

            /**
             * Delete record
             */
            $(document).on('click', '.delete-page', function () {
                let pageId = $(this).data('id');

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
                            url: "{{ route('pages.destroy', ':id') }}".replace(':id', pageId),
                            type: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function () {
                                showLoader();
                            },
                            success: function (response) {
                                hideLoader();
                                Swal.fire("Deleted!", response.message, "success").then(() => {
                                    location.reload();
                                });
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