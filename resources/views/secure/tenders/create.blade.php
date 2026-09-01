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

                    <form id="tenderForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <div class="col-md-12 col-12 mb-3">
                                <label class="form-label">Division</label>
                                <select name="division_id" class="form-control select2">
                                    <option value="">Select Division</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}">{{ $division->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (English): <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" required />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Title (Hindi): <span class="text-danger">*</span>
                                    <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" id="title_hi" class="form-control" />
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Prebid Meeting date/time: <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="publish_date" class="form-control" />
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">Start Date/Time: <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start_date" class="form-control" />
                            </div>

                            <div class="col-md-4 col-12 mb-3">
                                <label class="form-label">End Date/Time: <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="end_date" class="form-control" />
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (English): <span class="text-danger">*</span></label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf" required />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB</small>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">File (Hindi):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf" />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB</small>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Contact Person/Phone No. (English):</label>
                                <textarea name="issuing_authority" id="issuing_authority" class="form-control"
                                    rows="3"></textarea>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label">Contact Person/Phone No. (Hindi): <x-translate-button
                                        source="issuing_authority" target="issuing_authority_hi" /></label>
                                <textarea name="issuing_authority_hi" id="issuing_authority_hi" class="form-control"
                                    rows="3"></textarea>
                            </div>


                            {{-- ================= Corrigendums ================= --}}
                            <div class="col-12">
                                <hr>
                                <h5>Corrigendums</h5>

                                <div class="mb-3">
                                    <h6>
                                        You can upload multiple corrigendum files related to this tender.
                                        Click the button below to add additional file rows.
                                    </h6>
                                    <button class="btn btn-success btn-add-corrigendum-file">
                                        <i class="fa fa-plus"></i> <span>Add Corrigendum Row</span>
                                    </button>
                                </div>
                                <div id="corrigendum-wrapper">
                                </div>
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
@include('components.tender_corrigendum_template')
@section('pages-scripts')
    <script @cspNonce>
        let rowCounter = 1;
        let corrigendumCountArray = [];

        function generateCorrigendumRow() {
            const template = document.getElementById("corrigendumFieldsetTemplate").innerHTML;
            const html = template.replace(/__index__/g, rowCounter);
            corrigendumCountArray.push(rowCounter);
            rowCounter++;
            $('#corrigendum-wrapper').append(html);
        }
        $(document).ready(function () {

            $(document).on('click', '.btn-add-corrigendum-file', function (e) {
                e.preventDefault();
                generateCorrigendumRow();
            });

            // Remove PDF fieldset
            $('#corrigendum-wrapper').on('click', '.btn-remove-corrigendum-file', function (e) {
                e.preventDefault();
                let id = $(this).attr('id');
                $('#fieldset-' + id).remove();
                corrigendumCountArray = corrigendumCountArray.filter((count, index) => {
                    return count !== parseInt(id);
                });
            });


            $("#tenderForm").validate({
                rules: {
                    file_name: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                    title_hi: {
                        required: true
                    },
                    publish_date: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    end_date: {
                        required: true
                    }

                },
                submitHandler: function (form) {
                    let formData = new FormData(form);
                    formData.append('corrigendumCountArray', JSON.stringify(corrigendumCountArray));

                    $.ajax({
                        url: "{{ route('tenders.store') }}",
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
                                        "{{ route('tenders.index') }}";
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
