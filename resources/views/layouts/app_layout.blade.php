<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>{{ $siteSettings->site_name }}</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <!-- [Favicon] icon -->
    @if ($siteSettings->favicon)
        <link rel="icon"
            href="{{ asset('storage/' . Config::get('file_paths')['SITE_FAVICON_PATH'] . '/' . $siteSettings->favicon) }}"
            type="image/x-icon"> <!-- [Google Font] Family -->
    @endif

    <link @cspNonce rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <!-- Preload Fonts to prevent 'Slow network is detected' intervention -->
    <link @cspNonce rel="preload" href="{{ asset('assets/js/plugins/tabler_icon/tabler-icons.woff2') }}" as="font"
        type="font/woff2" crossorigin="anonymous">
    <link @cspNonce rel="preload" href="{{ asset('assets/fonts/fontawesome/fa-solid-900.woff2') }}" as="font"
        type="font/woff2" crossorigin="anonymous">
    <!-- data tables css -->
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap5.min.css') }}">
    <!-- [Tabler Icons] https://tablericons.com -->

    <link @cspNonce rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <script @cspNonce src="{{ asset('assets/js/plugins/tabler_icon/index.umd.min.js') }}"></script>
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/js/plugins/tabler_icon/tabler-icons.min.css') }}">
    <!-- [Feather Icons] https://feathericons.com -->
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <!-- Toastr css -->
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/css/plugins/toastr.min.css') }}">
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <!-- Nestable css -->
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/js/plugins/nestable/nestable.min.css') }}">
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/css/plugins/sweetalert2.min.css') }}" />

    <!-- Ckeditor5 -->
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/plugins/ckeditor5/ckeditor5/ckeditor5.css') }}">
    <!-- [Template CSS Files] -->
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/css/custom-style.css') }}">

    <!-- Select2 CSS -->
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.min.css') }}" />
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/plugins/select2/select2-bootstrap-5-theme.min.css') }}" />

    <!-- Datepicker CSS -->
    <link @cspNonce rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}">


    <style @cspNonce>
        /* Custom styling for Select2 validation */
        .select2-container .select2-selection--single {
            height: 43px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 41px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {

            top: 8px;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            margin-top: 8px;
        }

        /* Ensure SweetAlert appears above Bootstrap Modals */
        .swal2-container {
            z-index: 9999 !important;
        }

        #notif-bell-btn .ti-bell {
            transition: transform 0.2s;
        }

        #notif-bell-btn:hover .ti-bell {
            transform: scale(1.1);
        }

        .notif-pulse {
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .notif-item:hover {
            background-color: #f8f9fa;
            text-decoration: none;
        }

        #notif-body::-webkit-scrollbar {
            width: 6px;
        }

        #notif-body::-webkit-scrollbar-thumb {
            background: #e3e6ea;
            border-radius: 10px;
        }

        /* Fix sidebar scrolling issue by matching height calc to actual header height */
        .pc-sidebar .navbar-content {
            height: calc(100vh - 120px) !important;
            overflow-y: auto;
        }
    </style>
    @yield('style')

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>
    <!-- Skip to main content link for accessibility -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="loader">
        <div class="loader-content">
            <h6>
                Processing, please wait...
            </h6>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ Sidebar Menu ] start -->
    <nav class="pc-sidebar" role="navigation" aria-label="Admin sidebar navigation">
        <div class="navbar-wrapper">
            <div class="m-header border">
                <a href="{{ route('secure.dashboard') }}" class="b-brand text-primary">
                    <img src="{{ asset('storage/' . Config::get('file_paths')['SITE_ADMIN_PANEL_LOGO_PATH'] . '/' . $siteSettings->admin_panel_logo) }}"
                        class="img-fluid logo-lg navbrand-logo-img" alt="Logo">
                </a>
            </div>
            <div class="navbar-content">
                @include('includes.secure.sidebar')
            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end -->
    <!-- [ Header Topbar ] start -->
    @include('includes.secure.header')
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content" id="main-content" role="main" tabindex="-1">
            <!-- [ Main Content ] start -->
            @yield('content')
        </div>
    </div>
    <!-- [ Main Content ] end -->
    @include('includes.secure.footer')

    <script @cspNonce src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <!-- Required Js -->
    <script @cspNonce src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>


    <script @cspNonce src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script @cspNonce src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script @cspNonce src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script @cspNonce src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script @cspNonce src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>


    <script @cspNonce src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/sweetalert2.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/jquery.validate.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/nestable/jquery.nestable.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/pcoded.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

    <!-- Select2 JS -->
    <script @cspNonce src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/cryptojs/crypto-js.min.js') }}"></script>

    <!-- Datepicker JS -->
    <script @cspNonce src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>

    <!-- Ckeditor -->
    <script @cspNonce>
        const importMap = {
            imports: {
                "ckeditor5": "{{ asset('assets/plugins/ckeditor5/ckeditor5/ckeditor5.js') }}",
                "ckeditor5/": "{{ asset('assets/plugins/ckeditor5/ckeditor5/') }}/"
            }
        };

        document.write(
            `
            <script @cspNonce type="importmap">
            ${JSON.stringify(importMap)}
        <\/script>
    `);
    </script>

    <script @cspNonce type="module"
        src="{{ asset('assets/plugins/ckeditor5/ckeditor_config.js') }}?v={{ filemtime(public_path('assets/plugins/ckeditor5/ckeditor_config.js')) }}">
        </script>
    <script @cspNonce src="{{ asset('assets/js/custom-scripts.js') }}"></script>
    <script @cspNonce>
        function datePickerInit(inputName) {
            const element = document.querySelector(`[name="${inputName}"]`);

            if (element) {
                new Datepicker(element, {
                    buttonClass: 'btn',
                    autohide: true,
                    format: 'dd-mm-yyyy',
                    todayHighlight: true,
                    todayButton: true,
                    clearButton: true,
                    todayButtonMode: 1
                });
            }
        }



        if (window.axios) {
            delete axios.VERSION;
        }

        if (window.bootstrap) {
            Object.keys(bootstrap).forEach(key => {
                if (bootstrap[key]?.VERSION) {
                    delete bootstrap[key].VERSION;
                }
            });
        }

        if ($?.fn) {
            $.fn.jquery = undefined;
        }

        if (window.bootstrap) {
            Object.keys(window.bootstrap).forEach(function (key) {

                if (window.bootstrap[key] &&
                    window.bootstrap[key].VERSION) {

                    try {
                        delete window.bootstrap[key].VERSION;
                    } catch (e) {
                        window.bootstrap[key].VERSION = undefined;
                    }
                }
            });
        }

        async function encryptPassword(password, hexKey) {
            if (!hexKey) {
                console.error("Encryption key is not ready.");
                return null;
            }

            const keyBytes = new Uint8Array(hexKey.match(/.{1,2}/g).map(byte => parseInt(byte, 16)));
            const cryptoKey = await window.crypto.subtle.importKey(
                "raw", keyBytes, {
                name: "AES-GCM"
            }, false, ["encrypt"]
            );

            const iv = window.crypto.getRandomValues(new Uint8Array(12));
            const encoder = new TextEncoder();
            const data = encoder.encode(password);

            const encryptedBuffer = await window.crypto.subtle.encrypt({
                name: "AES-GCM",
                iv: iv,
                tagLength: 128
            },
                cryptoKey, data
            );

            const encryptedBytes = new Uint8Array(encryptedBuffer);
            const payload = new Uint8Array(12 + encryptedBytes.length);
            payload.set(iv, 0);
            payload.set(encryptedBytes, 12);

            let binary = '';
            for (let i = 0; i < payload.byteLength; i++) {
                binary += String.fromCharCode(payload[i]);
            }
            return btoa(binary);
        }
    </script>


    <script @cspNonce>
        let inactivityTimeout = `{{ env('SESSION_INACTIVITY_TIMEOUT') }}`;
        let idleTime = 0;
        let maxIdleTime = inactivityTimeout * 60;
        let sessionInterval;
        let timerInterval;

        function resetIdleTime() {
            idleTime = 0;
        }

        function startIdleTimer() {
            // Increment the idle time counter every second.
            sessionInterval = setInterval(() => {
                idleTime++;

                // Update the countdown display
                updateSessionTimer(maxIdleTime - idleTime);
            }, 1000);
        }

        // Cache the session timer element to avoid redundant DOM queries every second
        let sessionTimerElement;

        function updateSessionTimer(secondsLeft) {
            if (secondsLeft < 0) secondsLeft = 0;
            let minutes = Math.floor(secondsLeft / 60);
            let seconds = secondsLeft % 60;

            if (!sessionTimerElement) {
                sessionTimerElement = document.getElementById("session-timer");
            }

            if (sessionTimerElement) {
                sessionTimerElement.textContent = `${pad(minutes)}:${pad(seconds)}`;
            }
        }

        function pad(num) {
            return num.toString().padStart(2, "0");
        }

        function pingSession() {
            // Pings backend every minute to check last_activity
            setInterval(() => {
                $.ajax({
                    url: "{{ route('session.ping') }}", // Create this route
                    type: "POST",
                    global: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    beforeSend: function () {
                        hideLoader();
                    },
                    complete: function () {
                        hideLoader();
                    },
                    success: function (response) {
                        if (response.logout === true) {
                            toastr.error(response.message, null, {
                                timeOut: 10000
                            });

                            clearInterval(sessionInterval);
                            clearInterval(timerInterval);
                            window.location.href = response
                                .redirect_url; // Redirect to login or home page
                        }
                    },
                });
            }, 60000); // 1 minute
        }

        function fetchNotifications() {
            $.ajax({
                url: "{{ route('notifications.fetch') }}",
                type: "GET",
                global: false,
                beforeSend: function () {
                    // Only show loading if body is empty or we are specifically requested
                },
                success: function (response) {
                    const $badge = $('#notif-badge');
                    const $headerCount = $('#notif-header-count');
                    const $body = $('#notif-body');
                    const $lastUpdated = $('#notif-last-updated');

                    // Update counts
                    if (response.count > 0) {
                        $badge.text(response.count).show();
                        $headerCount.text(response.count);
                        $('#notif-bell-icon').addClass('notif-pulse');
                    } else {
                        $badge.hide();
                        $headerCount.text('0');
                        $('#notif-bell-icon').removeClass('notif-pulse');
                    }

                    // Populate body
                    if (response.notifications && response.notifications.length > 0) {
                        let html = '';
                        response.notifications.forEach(notif => {
                            html += `
                                <a href="${notif.url}" class="dropdown-item py-3 border-bottom d-flex align-items-start gap-2" style="white-space: normal;">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                                            <i class="ti ti-file-text notificationIcon"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <small class="fw-bold text-primary text-uppercase" style="font-size:0.65rem;">${notif.module}</small>
                                            <small class="text-muted" style="font-size:0.65rem;">${notif.created}</small>
                                        </div>
                                        <p class="mb-0 fw-medium small text-dark mt-1">${notif.title}</p>
                                        <div class="mt-1 d-flex align-items-center gap-1">
                                            <span class="badge ${notif.status === 'Rejected' ? 'bg-light-danger text-danger' : 'bg-light-warning text-warning'}" style="font-size:0.6rem;padding:2px 6px;">${notif.status}</span>
                                            <small class="text-muted" style="font-size:0.65rem;">Click to view</small>
                                        </div>
                                    </div>
                                </a>
                            `;
                        });
                        $body.html(html);
                    } else {
                        $body.html(`
                            <div class="text-center text-muted py-5">
                                <i class="ti ti-bell-off fs-1 d-block mb-2 opacity-50"></i>
                                <p class="mb-0 small">No new notifications</p>
                            </div>
                        `);
                    }
                },
                error: function () {
                    $('#notif-body').html(`
                        <div class="text-center text-danger py-3 small">
                            Failed to load notifications
                        </div>
                    `);
                }
            });
        }

        $(document).ready(function () {
            resetIdleTime();
            startIdleTimer();
            pingSession();

            // Notifications initialization
            fetchNotifications();

            // Manual refresh on open
            $('#notif-bell-btn').on('click', function () {
                // If it was already loading or we want to force refresh on click
                fetchNotifications();
            });


            // Sidebar keyboard accessibility - GIGW compliance
            // Caching the links to optimize attribute updates
            const $hasmenuLinks = $('.pc-hasmenu > .pc-link');
            $hasmenuLinks.attr('role', 'menuitem').each(function () {
                const $this = $(this);
                const isExpanded = $this.closest('.pc-hasmenu').hasClass('pc-trigger');
                $this.attr('aria-expanded', isExpanded ? 'true' : 'false');
            });

            // Top-level sidebar menus: Enter/Space to toggle
            $('.pc-navbar > li.pc-hasmenu > .pc-link').on('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    e.stopPropagation();
                    var parentLi = $(this).closest('.pc-hasmenu')[0];
                    var submenu = parentLi.children[1]; // .pc-submenu

                    if (parentLi.classList.contains('pc-trigger')) {
                        // Close this menu
                        parentLi.classList.remove('pc-trigger');
                        slideUp(submenu, 200);
                        $(this).attr('aria-expanded', 'false');
                        window.setTimeout(function () {
                            submenu.removeAttribute('style');
                            submenu.style.display = 'none';
                        }, 200);
                    } else {
                        // Close all other open menus first
                        // Caching sibling search to optimize performance
                        const $allTriggers = $('.pc-navbar > li.pc-trigger');
                        $allTriggers.each(function () {
                            if (this !== parentLi) {
                                this.classList.remove('pc-trigger');
                                var sub = this.children[1];
                                if (sub) {
                                    slideUp(sub, 200);
                                    window.setTimeout(function () {
                                        sub.removeAttribute('style');
                                        sub.style.display = 'none';
                                    }, 200);
                                }
                                $(this).find('> .pc-link').attr('aria-expanded', 'false');
                            }
                        });
                        // Open this menu
                        parentLi.classList.add('pc-trigger');
                        slideDown(submenu, 200);
                        $(this).attr('aria-expanded', 'true');
                        // Focus first submenu link
                        var $firstLink = $(parentLi).find('.pc-submenu .pc-link').first();
                        setTimeout(function () {
                            $firstLink.focus();
                        }, 250);
                    }
                }
                if (e.key === 'Escape') {
                    var parentLi = $(this).closest('.pc-hasmenu')[0];
                    var submenu = parentLi.children[1];
                    if (parentLi.classList.contains('pc-trigger')) {
                        parentLi.classList.remove('pc-trigger');
                        slideUp(submenu, 200);
                        $(this).attr('aria-expanded', 'false');
                        window.setTimeout(function () {
                            submenu.removeAttribute('style');
                            submenu.style.display = 'none';
                        }, 200);
                    }
                }
            });

            // Nested submenus: Enter/Space to toggle
            $('.pc-navbar > li > .pc-submenu li.pc-hasmenu > .pc-link').on('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    e.stopPropagation();
                    var parentLi = $(this).closest('.pc-hasmenu')[0];
                    var submenu = parentLi.children[1];
                    if (!submenu) return;

                    if (parentLi.classList.contains('pc-trigger')) {
                        parentLi.classList.remove('pc-trigger');
                        slideUp(submenu, 200);
                        $(this).attr('aria-expanded', 'false');
                    } else {
                        // Close sibling menus
                        $(parentLi).siblings('.pc-trigger').each(function () {
                            this.classList.remove('pc-trigger');
                            if (this.children[1]) slideUp(this.children[1], 200);
                            $(this).find('> .pc-link').attr('aria-expanded', 'false');
                        });
                        parentLi.classList.add('pc-trigger');
                        submenu.removeAttribute('style');
                        slideDown(submenu, 200);
                        $(this).attr('aria-expanded', 'true');
                        var $firstLink = $(parentLi).find('.pc-submenu .pc-link').first();
                        setTimeout(function () {
                            $firstLink.focus();
                        }, 250);
                    }
                }
            });

            // Escape from any submenu link: close parent and return focus
            $('.pc-submenu .pc-link').on('keydown', function (e) {
                if (e.key === 'Escape') {
                    e.stopPropagation();
                    var $parentHasmenu = $(this).closest('.pc-hasmenu');
                    var parentLi = $parentHasmenu[0];
                    var submenu = parentLi.children[1];
                    parentLi.classList.remove('pc-trigger');
                    if (submenu) {
                        slideUp(submenu, 200);
                        window.setTimeout(function () {
                            submenu.removeAttribute('style');
                            submenu.style.display = 'none';
                        }, 200);
                    }
                    $parentHasmenu.find('> .pc-link').attr('aria-expanded', 'false').focus();
                }
            });
        });

        $.ajaxSetup({
            beforeSend: function () {
                showLoader();
            },
            complete: function () {
                hideLoader();
            },
            error: function () {
                hideLoader();
            }
        });
    </script>
    <!-- Floating Scroll Buttons -->
    <!-- Combined Floating Scroll Control -->
    <div class="scroll-control-unit"
        style="position: fixed; bottom: 20px; right: 20px; z-index: 1050; display: flex; flex-direction: column; background: #007bff; border: 2px solid white; border-radius: 25px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <button id="scrollToTop" class="btn btn-primary border-0 rounded-0 p-0" title="Scroll to Top"
            style="width: 40px; height: 40px; display: none; border-bottom: 1px solid rgba(255,255,255,0.2) !important;">
            <i class="fas fa-chevron-up"></i>
        </button>
        <button id="scrollToBottom" class="btn btn-primary border-0 rounded-0 p-0" title="Scroll to Bottom"
            style="width: 40px; height: 40px;">
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>

    <script @cspNonce>
        $(document).ready(function () {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 300) {
                    $('#scrollToTop').fadeIn();
                } else {
                    $('#scrollToTop').fadeOut();
                }

                // Hide scroll to bottom if at the end of the page
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                    $('#scrollToBottom').fadeOut();
                } else {
                    $('#scrollToBottom').fadeIn();
                }
            });

            $('#scrollToTop').click(function () {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            $('#scrollToBottom').click(function () {
                window.scrollTo({
                    top: document.body.scrollHeight,
                    behavior: 'smooth'
                });
            });


            $('.select2').select2({
                width: '100%',
                placeholder: function () {
                    return $(this).data('placeholder') || 'Select an option';
                },
                allowClear: true
            });
        });
    </script>

    <script @cspNonce>
        $(document).on('click', '.translate-btn', function () {

            let button = $(this);
            let source = button.data('source');
            let target = button.data('target');

            // Find closest container/row to scope search
            let row = button.closest('tr, .row-item, .personnel-row, .activity-row');
            let container = row.length ? row : button.closest('form, .modal, .modal-body, fieldset, .card-body, .card');
            
            // Detect if inputs inside this container have a numeric suffix (e.g., _1, _2) from dynamic rows
            let indexSuffix = '';
            if (container.length) {
                let anyInput = container.find('input, textarea, select').first();
                if (anyInput.length) {
                    let name = anyInput.attr('name') || '';
                    let match = name.match(/_(\d+)$/);
                    if (match) {
                        indexSuffix = '_' + match[1];
                    }
                }
            }

            let sourceName = source + indexSuffix;
            let targetName = target + indexSuffix;

            // Search for English input in closest container/row
            let englishInput = $();
            if (container.length) {
                englishInput = container.find(`input[name$="[${source}]"], textarea[name$="[${source}]"], input[name="${sourceName}"], textarea[name="${sourceName}"], input[name="${source}"], textarea[name="${source}"], .item-${source}-en`);
            }

            if (englishInput.length === 0) {
                englishInput = $(`input[name$="[${source}]"], textarea[name$="[${source}]"], input[name="${sourceName}"], textarea[name="${sourceName}"], input[name="${source}"], textarea[name="${source}"], .item-${source}-en`);
            }

            let englishText = englishInput.first().val();

            if (!englishText || englishText.trim() === '') {
                toastr.warning('Please enter English text first');
                return;
            }

            button.prop('disabled', true).text('Translating...');

            $.ajax({
                url: "{{ route('translate') }}",
                method: 'POST',
                data: {
                    text: englishText,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    if (res.success) {
                        let targetInput = $();
                        if (container.length) {
                            targetInput = container.find(`input[name$="[${target}]"], textarea[name$="[${target}]"], input[name="${targetName}"], textarea[name="${targetName}"], input[name="${target}"], textarea[name="${target}"], .item-${target}-hi`);
                        }
                        if (targetInput.length === 0) {
                            targetInput = $(`input[name$="[${target}]"], textarea[name$="[${target}]"], input[name="${targetName}"], textarea[name="${targetName}"], input[name="${target}"], textarea[name="${target}"], .item-${target}-hi`);
                        }

                        targetInput.val(res.translation);
                    } else {
                        toastr.error('Translation failed');
                    }
                },
                error: function () {
                    toastr.error('Something went wrong');
                    hideLoader();
                },
                complete: function () {
                    hideLoader();
                    button.prop('disabled', false).text('Translate');
                }
            });
        });

    </script>


    @yield('pages-scripts')
</body>
<!-- [Body] end -->

</html>