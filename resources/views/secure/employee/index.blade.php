@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('employee.create');
    @endphp

    @can('add employee')
        @php
            $button =
                '<div class="btn-group" role="group">' .
                '<a href="' .
                $addRoute .
                '" class="btn btn-primary me-2">
                                                    <i class="fa fa-plus"></i> Add New
                                               </a>' .
                '<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkImportModal">
                                                    <i class="fa fa-upload"></i> Bulk Import
                                                </button></div>';
        @endphp
    @endcan

    <x-page-header title="{{ $pageTitle }}" button="{!! (isset($button)) ? $button : '' !!}" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="employee-datatable" class="table table-striped table-bordered w-100 no-wrap">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Designation</th>
                                    <th>Emp Code</th>
                                    <th>Level</th>
                                    <th>Cell</th>
                                    <th>PAN No</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Import Modal -->
    @can('add employee')
        <div class="modal fade" id="bulkImportModal" tabindex="-1" aria-labelledby="bulkImportModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bulkImportModalLabel">Bulk Import Employees</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="bulkImportForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Select Excel File <span class="text-danger">*</span> <a
                                        href="{{ route('employee.download-format') }}"><i class="fa fa-download"
                                            aria-hidden="true"></i>Download Format</a> </label>
                                <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submitImport">Import</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {

            var table = $('#employee-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                scrollX: true,
                ajax: {
                    url: "{{ route('employee.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                columns: [{
                    data: null,
                    width: '5%',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'mobile_number',
                    name: 'mobile_number'
                },
                {
                    data: 'designation',
                    name: 'designation'
                },
                {
                    data: 'emp_code',
                    name: 'emp_code'
                },
                {
                    data: 'level',
                    name: 'level',
                    className: 'text-center'
                },
                {
                    data: 'cell',
                    name: 'cell',
                    className: 'text-center'
                },
                {
                    data: 'pan_no',
                    name: 'pan_no'
                },
                {
                    data: 'action',
                    name: 'action',
                    width: '12%'
                }
                ]
            });

            /* ===============================
             * DELETE EMPLOYEE
             * =============================== */
            $(document).on('click', '.delete-employee', function () {
                let id = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to delete this employee.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('employee.destroy', ':id') }}".replace(':id', id),
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
             * BULK IMPORT
             * =============================== */
            $('#submitImport').click(function () {
                let formData = new FormData($('#bulkImportForm')[0]);

                $.ajax({
                    url: "{{ route('employee.bulk-import') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        showLoader();
                    },
                    success: function (response) {
                        hideLoader();
                        if (response.success) {
                            table.ajax.reload();
                            formData.get('file').value = "";
                            $('#bulkImportModal').modal('hide');
                            Swal.fire("Success!", response.message, "success");
                        }
                    },
                    error: function (xhr) {
                        hideLoader();
                        table.ajax.reload();
                        formData.get('file').value = "";
                        if (xhr.status === 422) {
                            let errors = Object.values(xhr.responseJSON.errors).flat().join("<br>");
                            Swal.fire("Validation Error", errors, "error");
                        } else {
                            Swal.fire("Error!", xhr.responseJSON.message ||
                                "Something went wrong!", "error");
                        }
                    }
                });
            });
        });
    </script>
@endsection