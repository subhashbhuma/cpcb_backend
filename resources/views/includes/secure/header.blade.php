<header class="pc-header" role="banner" aria-label="Admin panel header">
    <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <!-- ======= Menu collapse Icon ===== -->
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0" id="sidebar-hide" aria-label="Toggle sidebar navigation">
                        <i class="ti ti-menu-2" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0" id="mobile-collapse" aria-label="Toggle mobile menu">
                        <i class="ti ti-menu-2" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="dropdown pc-h-item d-inline-flex d-md-none">
                    <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-search"></i>
                    </a>
                    <div class="dropdown-menu pc-h-dropdown drp-search">
                        <form class="px-3">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <i data-feather="search"></i>
                                <input type="search" class="form-control border-0 shadow-none"
                                    placeholder="Search here. . .">
                            </div>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
        <!-- [Mobile Media Block end] -->
        <div class="ms-auto">
            <ul class="list-unstyled">
                <li class="pc-h-item">
                    {{-- Session Timer Display --}}
                    <div class="d-flex align-items-center gap-2 px-3 py-1 rounded" style="background-color:#f3f4f6;">
                        <i id="timer-icon" class="ti ti-alarm" style="color: #00c951;"></i>
                        <span class="fw-medium small" style="color: #4a5565;">Session:</span>
                        <span id="session-timer" class="small font-monospace" style="color: #00c951;"></span>
                    </div>

                </li>

                @if(Auth::user()->roles->first()->name !== 'EMPLOYEE')
                {{-- Notification Bell --}}
                <li class="dropdown pc-h-item" id="notif-li">
                    <a class="pc-head-link dropdown-toggle arrow-none m-0 position-relative" data-bs-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false"
                        id="notif-bell-btn" aria-label="Notifications" title="Pending approvals">
                        <i class="ti ti-bell fs-5" id="notif-bell-icon"></i>
                        <span class="badge bg-danger rounded-pill position-absolute" id="notif-badge"
                            style="top:2px;right:2px;font-size:0.6rem;min-width:18px;display:none;">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown shadow-lg" id="notif-dropdown"
                        style="width:360px;max-width:95vw;padding:0;border-radius:12px;overflow:hidden;border:none;">

                        {{-- Header --}}
                        <div class="d-flex align-items-center justify-content-between px-3 py-3"
                            style="background:linear-gradient(135deg,#4680ff 0%,#6610f2 100%);">
                            <div>
                                <h6 class="text-white mb-0"><i class="ti ti-bell me-2"></i>Notifications</h6>
                                <small class="text-white-50">Pending approvals & updates</small>
                            </div>
                            <span class="badge bg-white text-primary fw-bold px-2 py-1" id="notif-header-count">0</span>
                        </div>

                        {{-- Body --}}
                        <div id="notif-body" style="max-height:420px;overflow-y:auto;background:#fff;">
                            <div class="text-center text-muted py-5" id="notif-loading">
                                <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                                <span class="small">Fetching updates...</span>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="text-center py-2 border-top" style="background:#f8f9fa;">
                            <small class="text-muted" style="font-size: 0.7rem;" id="notif-last-updated"></small>
                        </div>
                    </div>
                </li>
                @endif

                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                        <img src="{{ asset('storage' . Config::get('file_paths')['USER_PROFILE_IMAGE_PATH'] . '/' . auth()->user()->profile_image) }}"
                            alt="user-image" class="user-avtar">
                        <span>{{ auth()->user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex mb-1">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('storage' . Config::get('file_paths')['USER_PROFILE_IMAGE_PATH'] . '/' . auth()->user()->profile_image) }}"
                                        alt="user-image" class="user-avtar wid-35">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ auth()->user()->name }}</h6>
                                    <span>
                                        {{ auth()->user()->designation->title ?? "NA" }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <ul class="nav drp-tabs nav-fill nav-tabs" id="mydrpTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="drp-t1" data-bs-toggle="tab"
                                    data-bs-target="#drp-tab-1" type="button" role="tab" aria-controls="drp-tab-1"
                                    aria-selected="true"><i class="ti ti-user"></i> Profile</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="drp-t2" data-bs-toggle="tab" data-bs-target="#drp-tab-2"
                                    type="button" role="tab" aria-controls="drp-tab-2" aria-selected="false"><i
                                        class="ti ti-settings"></i> Other</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="mysrpTabContent">
                            <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel"
                                aria-labelledby="drp-t1" tabindex="0">
                                <a href="{{ route('profile.index') }}" class="dropdown-item">
                                    <i class="ti ti-edit-circle"></i>
                                    <span>Edit Profile</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="ti ti-power text-danger"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="drp-tab-2" role="tabpanel" aria-labelledby="drp-t2"
                                tabindex="0">
                                <a href="#" class="dropdown-item">
                                    <i class="ti ti-lock"></i>
                                    <span>Visit Website</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>