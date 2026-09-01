<script type="text/template" id="jobPostTemplate">
    <div class="col-md-6 mb-3 job-post-row" id="post-row-__index__">
        <div class="card border shadow-none mb-0 h-100">
            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-light">
                <span class="fw-bold text-muted small uppercase">Post #__index__</span>
                <button type="button" class="btn btn-link text-danger p-0 btn-remove-post" data-index="__index__">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>
            <div class="card-body py-3">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Post Title (English): <span class="text-danger">*</span></label>
                    <input type="hidden" name="posts[__index__][id]" value="">
                    <input type="text" name="posts[__index__][title]" class="form-control form-control-sm" placeholder="e.g. Scientist B" required />
                </div>
                <div class="mb-0">
                    <label class="form-label small fw-bold">Post Title (Hindi): <x-translate-button source="posts[__index__][title]" target="posts[__index__][title_hi]" /></label>
                    <input type="text" name="posts[__index__][title_hi]" class="form-control form-control-sm" placeholder="e.g. वैज्ञानिक बी" />
                </div>
            </div>
        </div>
    </div>
</script>
