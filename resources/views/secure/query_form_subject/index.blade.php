@extends('layouts.app_layout')

@section('content')
    @php
        $addRoute = route('query_form_subject.create');
    @endphp

    <x-page-header title="Query Form Subject List"
        button='<a href="{{ $addRoute }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>' />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="query-form-subject-datatable" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Sl. No</th>
                                    <th>Title</th>
                                    <th>Division</th>
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
        // --- 1. Helper Function to Fetch All Server-Side Data for Exporting ---
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
            exportData.header.push('English Title');
            exportData.header.push('Hindi Title');
            exportData.header.push('Contact Person');
            exportData.header.push('Email');
            exportData.header.push('Division Id');
            exportData.header.push('Division Name');
            var table = $('#query-form-subject-datatable').DataTable();
            var rawJsonData = table.rows().data();
            for (var i = 0; i < rawJsonData.length; i++) {
                var row = rawJsonData[i];
                var newRow = [];
                newRow.push(row.DT_RowIndex ? row.DT_RowIndex : (i + 1));
                newRow.push(row.title ? row.title : '-');
                newRow.push(row.title_hi ? row.title_hi : '-');
                newRow.push(row.name ? row.name : '-');
                newRow.push(row.email_id ? row.email_id : '-');
                newRow.push(row.division_id ? row.division_id : '-');
                newRow.push(row.division_name ? row.division_name : '-');
                exportData.body.push(newRow);
            }
        }
        // --- 3. DOM Ready Initialization ---
        $(document).ready(function () {

            $('#query-form-subject-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export Excel',
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
                    url: "{{ route('query_form_subject.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'id',
                        width: '5%',
                        render: function (data, type, row, meta) {
                            return data ? data : meta.row + 1;
                        }
                    },
                    { data: 'title', name: 'title' },
                    { data: 'division_name', name: 'division_name' },
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
                    { data: 'action', name: 'action', width: '12%' }
                ]
            });

            // SweetAlert Delete Logic
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
                                $('#query-form-subject-datatable').DataTable().ajax.reload();
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