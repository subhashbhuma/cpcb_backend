<!-- Accessibility Bar -->
<div class="accessibility-bar border-bottom shadow-sm">
    <div class="container d-flex justify-content-end py-1">
        <div class="d-flex align-items-center gap-2">
            <a
                href="{{ route('language.switch', 'en') }}"
                class="btn btn-sm btn-outline-secondary text-white border-white d-flex align-items-center rounded-pill px-3"
                id="language-toggle"
                aria-label="Switch to Hindi">
                <i class="fas fa-globe me-2 small"></i>
                <span>English</span>
            </a>

            <a
                href="{{ route('language.switch', 'hi') }}"
                class="btn btn-sm btn-outline-secondary text-white border-white d-flex align-items-center rounded-pill px-3"
                id="language-toggle"
                aria-label="Switch to Hindi">
                <i class="fas fa-globe me-2 small"></i>
                <span>हिंदी</span>
            </a>

            <div class="vr mx-1 opacity-25"></div>

            <div class="d-flex align-items-center gap-2">
                <button
                    class="btn btn-sm btn-outline-secondary text-white border-white rounded-circle"
                    id="decrease-font"
                    aria-label="Decrease font size">
                    <i class="fas fa-minus small"></i>
                </button>

                <button
                    class="btn btn-sm btn-outline-secondary text-white border-white rounded-circle"
                    id="increase-font"
                    aria-label="Increase font size">
                    <i class="fas fa-plus small"></i>
                </button>
            </div>

            <div class="vr mx-1 opacity-25"></div>

            <button
                class="btn btn-sm btn-dark text-white border-white rounded-circle th-sr-btns"
                id="dark-mode-toggle"
                aria-label="Toggle dark mode">
                <i class="fas fa-moon small"></i>
            </button>

            <button
                class="btn btn-sm btn-warning text-white border-white rounded-circle th-sr-btns"
                id="orange-mode-toggle"
                aria-label="Toggle orange mode">
                T
            </button>

            <button
                class="btn btn-sm btn-info text-white border-white rounded-circle th-sr-btns"
                id="blue-mode-toggle"
                aria-label="Toggle blue mode">
                T
            </button>

            <button
                class="btn btn-sm btn-success text-white border-white rounded-circle th-sr-btns"
                id="green-mode-toggle"
                aria-label="Toggle green mode">
                T
            </button>

            <button
                class="btn btn-sm btn-outline-secondary text-white border-white rounded-circle th-sr-btns"
                id="high-contrast-toggle"
                aria-label="Toggle high contrast">
                <i class="fas fa-universal-access small"></i>
            </button>

            <button
                class="btn btn-sm btn-outline-secondary text-white border-white rounded-circle th-sr-btns"
                id="screen-reader"
                aria-label="Screen reader">
                <i class="fas fa-audio-description small"></i>
            </button>
        </div>
    </div>
</div>

<!-- Header -->
<header class="sticky-top">
    <!-- Top header with logo and title -->
    <div class="bg-white shadow-sm">
        <div
            class="container d-flex justify-content-between align-items-center py-2">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('homepage') }}" class="d-flex align-items-center text-decoration-none">
                    <img
                        src="{{ asset('storage' . Config::get('file_paths')['SITE_HEADER_LOGO_PATH'].'/' . $siteSettings->header_logo) }}"
                        alt="Government of India Emblem"
                        class="me-3"
                        style="height: 80px; object-fit: contain" />
                </a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Search bar -->
                <div class="d-none d-md-block">
                    <div class="input-group">
                        <input
                            type="search"
                            class="form-control rounded-pill rounded-end"
                            placeholder="Search..."
                            aria-label="Search" />
                        <button
                            class="btn btn-primary rounded-pill rounded-start"
                            type="button">
                            <i class="fas fa-search text-white"></i>
                        </button>
                    </div>
                </div>

                @if ($additionalLogos && $additionalLogos->count() > 0)
                <!-- Additional logos -->
                @foreach ($additionalLogos as $logo)
                @if ($logo->link)
                <a
                    href="{{ $logo->link }}"
                    target="_blank"
                    class="d-none d-md-block">
                    <img
                        src="{{ $logo->file_url }}"
                        alt="{{ getLocalizedDataFromObj($logo, 'title') }}"
                        height="50" />
                </a>
                @else
                <img
                    src="{{ $logo->file_url }}"
                    alt="{{ getLocalizedDataFromObj($logo, 'title') }}"
                    height="50" />
                @endif
                @endforeach
                @endif

                <!-- Mobile menu button -->
                <button
                    class="btn btn-outline-primary d-md-none rounded-circle elevation-2 ripple-effect"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#mobileMenu"
                    aria-controls="mobileMenu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Main navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto">
                    @include('components.website.menu-item', ['items' => $menus])
                </ul>


                <!-- <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 active ripple-effect" href="/">
                            <i class="fas fa-home me-2"></i> Home
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle px-3 py-2 ripple-effect text-white"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fas fa-info-circle me-2"></i> About Us
                        </a>
                        <ul
                            class="dropdown-menu shadow-lg border-0 rounded-3 mt-2 elevation-3">
                            <li>
                                <a
                                    class="dropdown-item py-2 ripple-effect text-white"
                                    href="about.html">Overview</a>
                            </li>
                            <li>
                                <a
                                    class="dropdown-item py-2 ripple-effect text-white"
                                    href="about.html#history">History</a>
                            </li>
                            <li>
                                <a
                                    class="dropdown-item py-2 ripple-effect text-white"
                                    href="about-whos-who.html">Who's Who</a>
                            </li>
                            <li>
                                <a
                                    class="dropdown-item py-2 ripple-effect text-white"
                                    href="about.html#vision-mission">Vision & Mission</a>
                            </li>
                            <li>
                                <a
                                    class="dropdown-item py-2 ripple-effect text-white"
                                    href="about.html#functions">Functions</a>
                            </li>
                        </ul>
                    </li>
                </ul> -->

                <!-- Employee Corner Button -->
                <a
                    href="/employees"
                    class="btn btn-secondary rounded-pill d-flex align-items-center elevation-2 ripple-effect">
                    <i class="fas fa-users me-2"></i>
                    <span>Employee Corner</span>
                    <span class="badge bg-light text-secondary ms-2 rounded-pill">Login</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Offcanvas -->
    <div
        class="offcanvas offcanvas-start"
        tabindex="-1"
        id="mobileMenu"
        aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <button
                        class="btn btn-sm btn-outline-primary rounded-circle me-2 ripple-effect"
                        id="mobile-search-toggle">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="/sitemap" class="text-decoration-none">Sitemap</a>
                </div>
            </div>

            <div class="mobile-search-form mb-3 d-none">
                <div class="input-group">
                    <input
                        type="search"
                        class="form-control rounded-pill rounded-end"
                        placeholder="Search..." />
                    <button
                        class="btn btn-primary rounded-pill rounded-start"
                        type="button">
                        <i class="fas fa-search text-white"></i>
                    </button>
                </div>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link py-3 border-bottom ripple-effect" href="/">
                        <i class="fas fa-home me-2"></i> Home
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link py-3 border-bottom d-flex justify-content-between align-items-center ripple-effect"
                        data-bs-toggle="collapse"
                        href="#aboutCollapse">
                        <span><i class="fas fa-info-circle me-2"></i> About Us</span>
                        <i class="fas fa-chevron-down small"></i>
                    </a>
                    <div class="collapse" id="aboutCollapse">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link py-2 ripple-effect" href="about.html">Overview</a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link py-2 ripple-effect"
                                    href="about.html#history">History</a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link py-2 ripple-effect"
                                    href="about-whos-who.html">Who's Who</a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link py-2 ripple-effect"
                                    href="about.html#vision-mission">Vision & Mission</a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link py-2 ripple-effect"
                                    href="about.html#functions">Functions</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link py-3 border-bottom d-flex justify-content-between align-items-center ripple-effect"
                        data-bs-toggle="collapse"
                        href="#divisionsCollapse">
                        <span><i class="fas fa-layer-group me-2"></i> Divisions</span>
                        <i class="fas fa-chevron-down small"></i>
                    </a>
                    <div class="collapse" id="divisionsCollapse">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link py-2 ripple-effect" href="/divisions/mpr">Manpower Planning & Recruitment</a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link py-2 ripple-effect"
                                    href="/divisions/personnel">Personnel & Legal</a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link py-2 ripple-effect"
                                    href="/divisions/discipline">Discipline, Coord & Welfare</a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link py-2 ripple-effect"
                                    href="/divisions/admin">Administration</a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link py-2 ripple-effect"
                                    href="/divisions/finance">Finance & Materials</a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link py-2 ripple-effect"
                                    href="/divisions/quartering">Quartering</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-3 border-bottom ripple-effect" href="/dhti">
                        <i class="fas fa-book-open me-2"></i> DHTI
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link py-3 border-bottom ripple-effect"
                        href="/citizens">
                        <i class="fas fa-user-check me-2"></i> Citizen Corner
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link py-3 border-bottom ripple-effect"
                        href="/media">
                        <i class="fas fa-camera me-2"></i> Media
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link py-3 border-bottom ripple-effect"
                        href="circulars.html">
                        <i class="fas fa-file-alt me-2"></i> Vacancy
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link py-3 border-bottom ripple-effect"
                        href="/contact">
                        <i class="fas fa-phone me-2"></i> Contact
                    </a>
                </li>

                <li class="nav-item mt-3">
                    <a
                        href="/employees"
                        class="btn btn-secondary rounded-pill w-100 elevation-2 ripple-effect">
                        <i class="fas fa-users me-2"></i> Employee Corner
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>