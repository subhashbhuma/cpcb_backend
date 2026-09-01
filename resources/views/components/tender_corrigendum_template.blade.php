<!-- Template to create the multiple file -->
<div id="corrigendumFieldsetTemplate" class="d-none">
    <fieldset class="fieldset mb-3" id="fieldset-__index__">
        <legend class="legend bg-primary">
            Corrigendum Detail
        </legend>
        <div class="fieldset-content-col">
            <button class="btn btn-danger btn-remove-corrigendum-file btn-remove-page-file" id="__index__">
                <i class="fa fa-trash"></i> <span class="sr-only">Remove</span>
            </button>
            <div class="row">
                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">File:</label>
                    <input type="file" name="file_name___index__" class="form-control form-file-control"
                        accept=".pdf" />
                    <span class="form-text">
                        Allowed types: pdf. Max: 50MB
                    </span>
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">File (हिंदी):</label>
                    <input type="file" name="file_name_hi___index__" class="form-control form-file-control"
                        accept=".pdf" />
                    <span class="form-text">
                        Allowed types: pdf. Max: 50MB
                    </span>
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Title (English):</label>
                    <input type="text" name="title___index__" class="form-control" />
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Title (हिंदी): <x-translate-button
                                        source="title___index__" target="title_hi___index__" />
                                    </label>
                    <input type="text" name="title_hi___index__" class="form-control" />
                </div>
            </div>
        </div>
    </fieldset>
</div>
