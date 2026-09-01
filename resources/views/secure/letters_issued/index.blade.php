@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('letters-issued.create');
    @endphp
    @can('add letters_issued')
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
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Filter by Type</label>
                            <select id="type_filter" class="form-control selectpicker">
                                <option value="">All</option>
                                @foreach (\Config::get('constants.LETTER_ISSUED_TYPE') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status_filter" class="form-label">Filter by Status:</label>
                            <select id="status_filter" class="form-control selectpicker">
                                <option value="">All</option>
                                <option value="published" {{ request()->get('status') == 'published' ? 'selected' : '' }}>
                                    Published</option>
                                <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="dt-responsive">
                        <table id="letters-issued-datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Publish Date</th>
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
            $('#letters-issued-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                scrollX: true,
                ajax: {
                    url: "{{ route('letters-issued.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.type_filter = $('#type_filter').val();
                        d.status = $('#status_filter').val();
                    }
                },
                columns: [{
                    data: null,
                    name: 'id',
                    width: '5%',
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // Serial number (starting from 1)
                    }
                },
                {
                    data: 'type',
                    name: 'type',
                    defaultContent: '-'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'publish_date',
                    name: 'publish_date'
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
                            return '<span class="badge bg-success">' + (data.is_approved_desc || 'Approved') + '</span>';
                        } else if (data.is_approved == 2) {
                            return '<span class="badge bg-danger">' + (data.is_approved_desc || 'Rejected') + '</span>';
                        } else {
                            return '<span class="badge bg-warning">' + (data.is_approved_desc || 'Pending') + '</span>';
                        }
                    }
                },
                {
                    data: null,
                    name: 'is_published',
                    width: '10%',
                    render: function (data, type, row) {
                        if (data.is_published == 1) {
                            return '<span class="badge bg-success">' + (data.is_published_desc || 'Published') + '</span>';
                        } else {
                            return '<span class="badge bg-warning">' + (data.is_published_desc || 'Draft') + '</span>';
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

            /**
             * Delete record
             */
            $(document).on('click', '.delete-letter', function () {
                let recordId = $(this).data('id');

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
                            url: "{{ route('letters-issued.destroy', ':id') }}".replace(':id', recordId),
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
                                $('#letters-issued-datatable').DataTable().ajax.reload();
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

        $('#type_filter, #status_filter').on('change', function () {
            $('#letters-issued-datatable').DataTable().ajax.reload();
        });
    </script>
@endsection