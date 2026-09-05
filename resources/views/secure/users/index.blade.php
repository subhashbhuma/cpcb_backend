@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('users.create');
    @endphp
    @can('add user')
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
                        <table id="users-datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile Number</th>
                                    <th>Division</th>
                                    <th>Roles</th>
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
            var usersDatatable = $('#users-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                ajax: {
                    url: "{{ route('users.fetch-for-datatable') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                    }
                },
                columns: [{
                    data: null,
                    name: 'id',
                    width: '8%',
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // Serial number (starting from 1)
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
                    data: 'division',
                    name: 'division.title'
                },
                {
                    data: 'roles',
                    name: 'roles.name'
                },
                {
                    data: 'action',
                    name: 'action',
                    width: '10%',
                    orderable: false,
                    searchable: false
                }
                ]
            });


            $(document).on('click', '.delete-user', function () {
                let userId = $(this).data('id');

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
                            url: "{{ route('users.destroy', ':id') }}".replace(':id', userId),
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
                                    usersDatatable.ajax.reload();
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

             $(document).on('click', '.btn-reset-password', function() {
                let id = $(this).data('id');

                if (!id) return;

                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to reset this user's password?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Reset",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('users.reset-password', ':id') }}".replace(':id', id),
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: "Success!",
                                        text: response.message,
                                        icon: "success",
                                        confirmButtonText: "OK"
                                    }).then(() => {
                                        window.location.href = "{{ route('users.index') }}";
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: response.message,
                                        icon: "error"
                                    });
                                }
                            },
                            error: function(xhr) {
                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    let errorMessages = Object.values(errors).flat().join("<br>");

                                    Swal.fire({
                                        title: "Validation Error",
                                        html: errorMessages,
                                        icon: "error"
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: "Something went wrong. Please try again.",
                                        icon: "error"
                                    });
                                }
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.btn-unlock-account', function() {
                let id = $(this).data('id');

                if (!id) return;

                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to unlock this user's account?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Unlock",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('users.unlock-account', ':id') }}".replace(':id', id),
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            beforeSend: function () {
                                showLoader();
                            },
                            success: function(response) {
                                hideLoader();
                                if (response.success) {
                                    Swal.fire({
                                        title: "Success!",
                                        text: response.message,
                                        icon: "success",
                                        confirmButtonText: "OK"
                                    }).then(() => {
                                        usersDatatable.ajax.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: response.message,
                                        icon: "error"
                                    });
                                }
                            },
                            error: function(xhr) {
                                hideLoader();
                                Swal.fire({
                                    title: "Error!",
                                    text: xhr.responseJSON?.message || "Something went wrong. Please try again.",
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection