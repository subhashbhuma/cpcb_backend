<!-- Footer -->
<footer class="bg-primary text-white">
    <!-- Main footer content -->
    <div class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <h4 class="mb-4 h5">About Us</h4>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <a
                                href="about.html#history"
                                class="text-white text-decoration-none ripple-effect d-flex align-items-center">
                                <i class="fas fa-circle me-2 small"></i>
                                History
                            </a>
                        </li>
                        <li class="mb-3">
                            <a
                                href="about-whos-who.html"
                                class="text-white text-decoration-none ripple-effect d-flex align-items-center">
                                <i class="fas fa-circle me-2 small"></i>
                                Who's Who
                            </a>
                        </li>
                        <li class="mb-3">
                            <a
                                href="about.html#vision-mission"
                                class="text-white text-decoration-none ripple-effect d-flex align-items-center">
                                <i class="fas fa-circle me-2 small"></i>
                                Vision & Mission
                            </a>
                        </li>
                        <li class="mb-3">
                            <a
                                href="about.html#functions"
                                class="text-white text-decoration-none ripple-effect d-flex align-items-center">
                                <i class="fas fa-circle me-2 small"></i>
                                Functions
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-6 col-lg-3">
                    <h4 class="mb-4 h5">Quick Links</h4>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <a
                                href="/employees"
                                class="text-white text-decoration-none ripple-effect d-flex align-items-center">
                                <i class="fas fa-circle me-2 small"></i>
                                Employees Corner
                            </a>
                        </li>
                        <li class="mb-3">
                            <a
                                href="/citizens"
                                class="text-white text-decoration-none ripple-effect d-flex align-items-center">
                                <i class="fas fa-circle me-2 small"></i>
                                Citizen Corner
                            </a>
                        </li>
                        <li class="mb-3">
                            <a
                                href="/media"
                                class="text-white text-decoration-none ripple-effect d-flex align-items-center">
                                <i class="fas fa-circle me-2 small"></i>
                                Photo Gallery
                            </a>
                        </li>
                        <li class="mb-3">
                            <a
                                href="/media"
                                class="text-white text-decoration-none ripple-effect d-flex align-items-center">
                                <i class="fas fa-circle me-2 small"></i>
                                Video Gallery
                            </a>
                        </li>
                        <li class="mb-3">
                            <a
                                href="/notifications"
                                class="text-white text-decoration-none ripple-effect d-flex align-items-center">
                                <i class="fas fa-circle me-2 small"></i>
                                Notifications
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-6 col-lg-3">
                    <h4 class="mb-4 h5">Help & Support</h4>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <a
                                href="/contact"
                                class="text-white text-decoration-none ripple-effect d-flex align-items-center">
                                <i class="fas fa-circle me-2 small"></i>
                                Contact Us
                            </a>
                        </li>
                        <li class="mb-3">
                            <a
                                href="/feedback"
                                class="text-white text-decoration-none ripple-effect d-flex align-items-center">
                                <i class="fas fa-circle me-2 small"></i>
                                Feedback
                            </a>
                        </li>
                        <li class="mb-3">
                            <a
                                href="/help"
                                class="text-white text-decoration-none ripple-effect d-flex align-items-center">
                                <i class="fas fa-circle me-2 small"></i>
                                Help
                            </a>
                        </li>
                        <li class="mb-3">
                            <a
                                href="/sitemap"
                                class="text-white text-decoration-none ripple-effect d-flex align-items-center">
                                <i class="fas fa-circle me-2 small"></i>
                                Sitemap
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-6 col-lg-3">
                    <h4 class="mb-4 h5">Contact Us</h4>
                    <ul class="list-unstyled">
                        <li class="d-flex mb-3">
                            <i class="fas fa-map-marker-alt text-white mt-1 me-2"></i>
                            <span>
                                {{ $contactDetail->address ?? '' }}
                            </span>
                        </li>
                        <li class="d-flex mb-3">
                            <i class="fas fa-phone text-white me-2"></i>
                            <span>{{ $contactDetail->phone_numbers ?? '' }}</span>
                        </li>
                        <li class="d-flex mb-3">
                            <i class="fas fa-envelope text-white me-2"></i>
                            <span>{{ $contactDetail->email_ids ?? '' }}</span>
                        </li>
                        <li class="pt-2">
                            <div class="d-flex gap-3">
                                @foreach ($socialLinks as $socialLink)
                                <a
                                    href="{{ $socialLink->url }}"
                                    class="bg-white bg-opacity-10 p-2 rounded-circle elevation-1 ripple-effect btn-only-icon-square text-decoration-none" title="{{ $socialLink->name }}">
                                    <i class="{{ $socialLink->icon_class }} text-white"></i>
                                </a>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom footer -->
    <div class="bg-dark py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <img
                            src="{{ asset('storage' . Config::get('file_paths')['SITE_FOOTER_LOGO_PATH'].'/' . $siteSettings->footer_logo) }}"
                            alt="{{ $siteSettings->site_name }}"
                            height="40"
                            class="me-3" />
                        <div class="small">
                            <p class="mb-0">
                                {{ $siteSettings->copyright_text ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div
                        class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-end">
                        <div class="small me-md-3 mb-2 mb-md-0">
                            <p class="mb-0">
                                {{ $siteSettings->maintained_by_text ?? '' }}
                            </p>
                            <p class="mb-0">Last updated: 03/04/2025</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accessibility declaration -->
            <div class="mt-3 pt-3 border-top border-secondary small text-center">
                <p class="text-white-50 mb-0">
                    {{ $siteSettings->accessibility_text ?? '' }}
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Photo Modals -->
<div
    class="modal fade"
    id="photoModal1"
    tabindex="-1"
    aria-labelledby="photoModal1Label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-3 elevation-5">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModal1Label">
                    Republic Day Celebration
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img
                    src="assets/images/gallery1.jpg"
                    alt="Republic Day Celebration"
                    class="img-fluid rounded-3" />
                <p class="mt-3 text-muted">January 26, 2023</p>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-outline-primary rounded-pill ripple-effect"
                    data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Close
                </button>
                <a
                    href="assets/images/gallery1.jpg"
                    download
                    class="btn btn-primary rounded-pill ripple-effect">
                    <i class="fas fa-download me-2"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Video Modals -->
<div
    class="modal fade"
    id="videoModal1"
    tabindex="-1"
    aria-labelledby="videoModal1Label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-3 elevation-5">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModal1Label">
                    Minister's Address
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-16x9">
                    <video controls class="rounded-3">
                        <source src="assets/videos/video1.mp4" type="video/mp4" />
                        Your browser does not support the video tag.
                    </video>
                </div>
                <p class="mt-3 text-muted">March 15, 2023</p>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-outline-primary rounded-pill ripple-effect"
                    data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>