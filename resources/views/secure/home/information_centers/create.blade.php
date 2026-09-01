@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="Create Information Center" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>All (<span class="text-danger">*</span>) marked fields are mandatory.</p>

                    <form id="informationCenterForm" method="POST" enctype="multipart/form-data"
                        action="javascript:void(0)">
                        @csrf

                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="title" class="form-control">
                            </div>

                            <!-- Title HI -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Title (Hindi) <span class="text-danger">*</span> <x-translate-button source="title"
                                        target="title_hi" />
                                </label>
                                <input type="text" name="title_hi" class="form-control">
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {
            $('#informationCenterForm').validate({
                rules: {
                    title: {
                        required: true
                    },
                    title_hi: {
                        required: true
                    }
                },
                messages: {
                    title: 'Title is required',
                    title_hi: 'Title (Hindi) is required'
                },
                submitHandler: function () {
                    let formData = new FormData(document.getElementById('informationCenterForm'));

                    $.ajax({
                        url: "{{ route('information-centers.store') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('information-centers.index') }}";
                                }
                            });
                        },
                        error: function (response) {
                            if (response.responseJSON && response.responseJSON.message) {
                                toastr.error(response.responseJSON.message);
                            } else {
                                toastr.error('An error occurred while creating the record.');
                            }
                        }
                    });

                    return false;
                }
            });
        });
    </script>
@endsection