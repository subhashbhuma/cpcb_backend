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
                    <label class="form-label">File:</label>
                    <input type="file" name="file_name___index__" class="form-control form-file-control" />
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
                    <label class="form-label">Title:</label>
                    <input type="text" name="title___index__" class="form-control" />
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Title (हिंदी):</label>
                    <input type="text" name="title_hi___index__" class="form-control" />
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Date:</label>
                    <input type="date" name="date___index__" class="form-control" />
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Type:</label>
                    <input type="text" name="type___index__" class="form-control" />
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Description:</label>
                    <textarea name="description___index__" class="form-control"></textarea>
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Description (हिंदी):</label>
                    <textarea name="description_hi___index__" class="form-control"></textarea>
                </div>
            </div>
        </div>
    </fieldset>
</div>