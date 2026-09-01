@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('division.create');
    @endphp

    @can('add division')
        @php
            $button = '
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fa fa-upload"></i> Import Excel
                </button>
                <a href="' . $addRoute . '" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add New
                </a>';
        @endphp
    @endcan

    <x-page-header title="{{ $pageTitle }}" button="{!! $button ?? '' !!}" />
    <!-- [ Page Header ] end -->

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('division.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Divisions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <strong>Warning:</strong> Importing data will <strong>truncate</strong> (delete) all existing divisions and replace them with the data from the Excel file!
                        </div>
                        <div class="mb-3">
                            <label for="import_file" class="form-label">Select Excel File (.xlsx, .xls, .csv)</label>
                            <input class="form-control" type="file" id="import_file" name="import_file" accept=".xlsx, .xls, .csv" required>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <p class="mb-0 text-muted"><strong>Required Headers:</strong></p>
                                <a href="{{ route('division.import.format') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-download"></i> Download Format
                                </a>
                            </div>
                            <ul class="text-muted small mb-0">
                                <li><code>title_english</code></li>
                                <li><code>title_hindi</code> (optional)</li>
                                <li><code>is_new</code> (optional, 0 or 1)</li>
                                <li><code>is_approved</code> (optional, 0 or 1)</li>
                                <li><code>is_published</code> (optional, 0 or 1)</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" id="importSubmitBtn">
                            <i class="fa fa-exclamation-triangle"></i> Truncate & Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="division-datatable" class="table table-striped table-bordered w-100 no-wrap">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Title</th>
                                    <th>Is New</th>
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

            function exportAllData(e, dt, button, config) {
                var self = this;
                var oldStart = dt.settings()[0]._iDisplayStart;
                var oldLength = dt.settings()[0]._iDisplayLength;

                dt.one('preXhr', function (e, s, data) {
                    data.start = 0;
                    data.length = -1; // Request all rows

                    dt.one('preDraw', function (e, settings) {
                        if (button[0].className.indexOf('buttons-excel') >= 0) {
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
                        } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config);
                        }

                        dt.one('preXhr', function (e, s, data) {
                            settings._iDisplayStart = oldStart;
                            data.start = oldStart;
                            data.length = oldLength;
                        });

                        setTimeout(dt.ajax.reload, 0);
                        return false;
                    });
                });

                dt.ajax.reload();
            }

            // --- 2. Updated Custom Function with division_id, email_id, etc. ---
            function manuallyInjectData(exportData) {
                exportData.header = [];
                exportData.body = [];
                exportData.header.push('S.No.');
                exportData.header.push('division_id');
                exportData.header.push('title_english');
                exportData.header.push('title_hindi');
                exportData.header.push('is_new');
                var table = $('#division-datatable').DataTable();
                var rawJsonData = table.rows().data();
                for (var i = 0; i < rawJsonData.length; i++) {
                    var row = rawJsonData[i];
                    var newRow = [];
                    newRow.push(row.DT_RowIndex ? row.DT_RowIndex : (i + 1));
                    newRow.push(row.id);
                    newRow.push(row?.title);
                    newRow.push(row?.title_hi);
                    newRow.push(row?.is_new);
                    exportData.body.push(newRow);
                }
            }



            const table = $('#division-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export Excel',
                        title: null,
                        action: exportAllData,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        customizeData: manuallyInjectData
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export PDF',
                        orientation: 'landscape',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        action: exportAllData,
                        customizeData: manuallyInjectData
                    }
                ],
                ajax: {
                    url: "{{ route('division.fetch-for-datatable') }}",
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
                    data: null,
                    name: 'title',
                    render: function (data, type, row) {
                        return `${row.title} (${row.title_hi})`;
                    }
                },
                {
                    data: 'is_new',
                    name: 'is_new',
                    className: 'text-center',
                    render: function (data) {
                        return data == 1 
                            ? '<span class="badge bg-success">Yes</span>' 
                            : '<span class="badge bg-secondary">No</span>';
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
            $(document).on('click', '.delete-division', function () {
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
                            url: "{{ route('division.destroy', ':id') }}".replace(':id',
                                id),
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
                                table.ajax.reload();
                            },
                            error: function () {
                                hideLoader();
                                Swal.fire("Error!", "Something went wrong!", "error");
                            }
                        });
                    }
                });
            });

            /* ===============================
             * IMPORT EXCEL
             * =============================== */
            $('#importForm').on('submit', function (e) {
                e.preventDefault();
                let form = this;
                let formData = new FormData(form);
                let submitBtn = $('#importSubmitBtn');
                let originalText = submitBtn.html();

                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Importing...').prop('disabled', true);

                $.ajax({
                    url: form.action,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        submitBtn.html(originalText).prop('disabled', false);
                        $('#importModal').modal('hide');
                        if (response.success) {
                            Swal.fire("Imported!", response.message, "success");
                            $('#division-datatable').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        submitBtn.html(originalText).prop('disabled', false);
                        let message = 'An error occurred during import.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        toastr.error(message);
                    }
                });
            });

        });
    </script>
@endsection