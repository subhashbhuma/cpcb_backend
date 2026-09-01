<!-- Template to create the multiple file -->
<div id="fileFieldsetTemplate" class="d-none">
    <fieldset class="fieldset mb-3" id="fieldset-__index__">
        <legend class="legend bg-primary">
            File
        </legend>
        <div class="fieldset-content-col">
            <button class="btn btn-danger btn-remove-page-file" id="__index__">
                <i class="fa fa-trash"></i> <span class="sr-only">Remove</span>
            </button>
            <div class="row">
                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">File (English): <span class="text-danger">*</span></label>
                    <input type="file" name="file_name___index__" class="form-control form-file-control" required />
                    <span class="text-danger">
                        Allowed file types (.pdf, .jpg, .png, .jpeg)
                    </span>
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">File (हिंदी):</label>
                    <input type="file" name="file_name_hi___index__" class="form-control form-file-control" />
                    <span class="text-danger">
                        Allowed file types (.pdf, .jpg, .png, .jpeg)
                    </span>
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Title (English):</label>
                    <input type="text" name="title___index__" class="form-control" />
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Title (हिंदी): <x-translate-button source="title" target="title_hi" /></label>
                    <input type="text" name="title_hi___index__" class="form-control" />
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Upload Date:</label>
                    <input type="date" name="upload_date___index__" class="form-control"
                        value="{{ date('Y-m-d') }}" />
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Order Number:</label>
                    <input type="number" name="order_number___index__" class="form-control" min="0" value="0" />
                    <small class="text-muted">Lower numbers appear first (default: 0)</small>
                </div>
            </div>
        </div>
    </fieldset>
</div>
