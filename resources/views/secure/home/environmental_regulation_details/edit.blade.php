@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header title="Edit Environmental Regulation Detail" :backButton="true" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>All (<span class="text-danger">*</span>) marked fields are mandatory.</p>

                    <form id="detailForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title (English)<span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ $detail->title }}" class="form-control">
                            </div>

                            <!-- Title HI -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title (हिंदी) <span class="text-danger">*</span> <x-translate-button source="title" target="title_hi" /></label>
                                <input type="text" name="title_hi" value="{{ $detail->title_hi }}" class="form-control">
                            </div>

                            <!-- Regulation -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Regulation <span class="text-danger">*</span></label>
                                <select name="environmental_regulation_id" class="form-control">
                                    <option value="">Select Regulation</option>
                                    @foreach ($regulations as $regulation)
                                        <option value="{{ $regulation->id }}" {{ $detail->environmental_regulation_id == $regulation->id ? 'selected' : '' }}>
                                            {{ $regulation->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Parent -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Parent</label>
                                <select name="parent_id" class="form-control">
                                    <option value="">-- None (Top Level) --</option>
                                    @foreach ($parentItems as $item)
                                        <option value="{{ $item->id }}" {{ $detail->parent_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Order -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Order</label>
                                <input type="number" name="order" class="form-control" value="{{ $detail->order ?? 0 }}"
                                    min="0">
                            </div>

                            <!-- Type -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" id="content_type" class="form-control">
                                    <option value="FILE" {{ $detail->type === 'FILE' ? 'selected' : '' }}>File</option>
                                    <option value="URL" {{ $detail->type === 'URL' ? 'selected' : '' }}>URL</option>
                                </select>
                            </div>

                            <!-- URL -->
                            <div class="col-md-6 mb-3 {{ $detail->type === 'URL' ? '' : 'd-none' }}" id="url-wrapper">
                                <label class="form-label">URL <span class="text-danger">*</span></label>
                                <input type="text" name="url" value="{{ $detail->url }}" class="form-control">
                            </div>
                            <div
                                class="form-group col-md-6 col-12 {{ $detail->type === 'FILE' ? '' : 'd-none' }}  mb-3 file_content">
                                <label class="form-label">File (English): </label>
                                <input type="file" name="file_name" class="form-control" accept=".pdf" />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB</small>
                                @if ($detail->file_name)
                                    <div>
                                        Current File: <a href="{{ generate_file_view_path_for_backend($detail->file_url) }}"
                                            target='_BLANK'>View Document</a>
                                    </div>
                                @endif
                            </div>

                            <div
                                class="form-group col-md-6 col-12 {{ $detail->type === 'FILE' ? '' : 'd-none' }} mb-3  file_content">
                                <label class="form-label">File (Hindi):</label>
                                <input type="file" name="file_name_hi" class="form-control" accept=".pdf" />
                                <small class="text-muted">Allowed types: pdf. Max: 50MB</small>
                                @if ($detail->file_name_hi)
                                    <div>
                                        Current File: <a href="{{ generate_file_view_path_for_backend($detail->file_url_hi) }}"
                                            target='_BLANK'>View Document</a>
                                    </div>
                                @endif
                            </div>


                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Update
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
        function toggleTypeFields(type) {
            if (type === 'URL') {
                $('#url-wrapper').removeClass('d-none');
                $('.file_content').addClass('d-none');
            } else {
                $('#url-wrapper').addClass('d-none');
                $('.file_content').removeClass('d-none');
            }
        }

        $(document).ready(function () {
            toggleTypeFields($('#content_type').val());

            $('#content_type').on('change', function () {
                toggleTypeFields(this.value);
            });

            $('#detailForm').validate({
                rules: {
                    environmental_regulation_id: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                    title_hi: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    url: {
                        required: () => $('#content_type').val() === 'URL',
                    },
                    order: {
                        number: true,
                        min: 0
                    }
                },
                submitHandler: function () {
                    let formData = new FormData(document.getElementById('detailForm'));
                    $.ajax({
                        url: "{{ route('environmental-regulation-details.update', $detail->id) }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    window.location.href = "{{ route('environmental-regulation-details.index') }}";
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                let errors = Object.values(xhr.responseJSON.errors).flat()
                                    .join("<br>");
                                Swal.fire("Validation Error", errors, "error");
                            } else {
                                Swal.fire("Error", "Something went wrong", "error");
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection