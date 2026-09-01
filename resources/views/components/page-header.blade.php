<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{!! $title ?? '' !!}</h4>

                    @if(isset($button))
                        {!! $button !!}
                    @endif

                    @if(isset($backButton) && $backButton == true)
                        <a href="#" class="btn btn-secondary" aria-label="Go back to previous page" id="goBackBtn">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back
                        </a>
                        <script @cspNonce>
                            document.getElementById('goBackBtn').addEventListener('click', function (e) {
                                e.preventDefault();
                                history.back();
                            });
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>