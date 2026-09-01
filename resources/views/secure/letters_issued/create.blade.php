@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="{{ $pageTitle }}" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>All (<span class="text-danger">*</span>) marked fields are required.</p>

                    <form id="lettersIssuedForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <select name="direction_state_id[]" class="form-control select2" multiple>
                                    @foreach ($states as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-control">
                                    <option value="">-- Select Type --</option>
                                    @foreach ($types as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Publish Date: <span class="text-danger">*</span></label>
                                <input type="date" name="publish_date" class="form-control" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <span class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (English): <span class="text-danger">*</span></label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf" required />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB</small>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (Hindi): <span class="text-danger">*</span></label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf" required />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB</small>
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                            </div>
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
            // Initialize Select2 if not automatically initialized
            function initSelect2() {
                if ($('.select2').length > 0) {
                    $('.select2').select2({
                        placeholder: "Select Option",
                        allowClear: true
                    });
                }
            }
            initSelect2();

            // Type Change Event
            $('select[name="type"]').on('change', function () {
                let type = $(this).val();
                let stateSelect = $('select[name="direction_state_id[]"]');

                // Destroy Select2
                if (stateSelect.data('select2')) {
                    stateSelect.select2('destroy');
                }

                // Clear value to avoid mixed mode confusion
                stateSelect.val(null);

                if (type === 'OTHER') {
                    stateSelect.removeAttr('multiple');
                } else {
                    stateSelect.attr('multiple', 'multiple');
                }

                // Re-init Select2
                initSelect2();
            });

            $("#lettersIssuedForm").validate({
                rules: {
                    'direction_state_id[]': { required: true },
                    title: { required: true },
                    title_hi: { required: true },
                    publish_date: { required: true },
                    file_name: { required: true },
                    file_name_hi: { required: true }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('letters-issued.store') }}",
                        method: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('letters-issued.index') }}";
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessages = Object.values(errors).flat().join(
                                    "<br>");
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
    </script>
@endsection