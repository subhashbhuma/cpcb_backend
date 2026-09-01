<ul class="pc-navbar">
    @php
        if (!function_exists('getSidebarMenuUrl')) {
            function getSidebarMenuUrl($menuObj) {
                if (!empty($menuObj->file_name)) {
                    $path = \Illuminate\Support\Facades\Config::get('file_paths.MENU_FILE_EN_PATH', 'menu_files/en') . '/' . $menuObj->file_name;
                    return generate_file_view_path_for_backend($path);
                }
                if (!empty($menuObj->url) && $menuObj->url !== '#') {
                    $urlStr = $menuObj->url;
                    if (str_starts_with($urlStr, 'secure/http://') || str_starts_with($urlStr, 'secure/https://')) {
                        $urlStr = substr($urlStr, 7);
                    }
                    return url($urlStr);
                }
                return '#';
            }
        }
        if (!function_exists('isExternalUrl')) {
            function isExternalUrl($url) {
                if (empty($url) || $url === '#' || str_starts_with($url, 'javascript:')) return false;
                if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
                    $host = parse_url($url, PHP_URL_HOST);
                    $appHost = parse_url(config('app.url'), PHP_URL_HOST);
                    if ($host && $appHost && $host !== $appHost) {
                        return true;
                    }
                    // Fallback if app.url is not set properly, check if it's the current host
                    if ($host && $host !== request()->getHost()) {
                        return true;
                    }
                }
                return false;
            }
        }
    @endphp
    @can('view website dashboard')
        <li class="pc-item border-top" style="border-bottom: 1px solid #e0e5e8; margin-bottom: 2px;">
            <a href="{{ route('secure.dashboard') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                <span class="pc-mtext">Dashboard</span>
            </a>
        </li>
    @endcan

    {{-- Dynamic Sidebar Menus Logic --}}
    @php
        $inVirtualGroup = false;
    @endphp

    @if(isset($sidebarDynamicMenus) && $sidebarDynamicMenus->isNotEmpty())
        @foreach($sidebarDynamicMenus as $menu)

            {{-- SEPARATOR --}}
            @if(trim($menu->title) === '---' || strtolower(trim($menu->title)) === 'separator')
                @php $inVirtualGroup = false; @endphp
                <li class="pc-item">
                    <hr style="margin: 15px 20px; border-color: rgba(0,0,0,0.1);" />
                </li>
                @continue
            @endif

            {{-- CAPTION (Original Able Pro Caption) --}}
            @if($menu->is_caption || ($menu->url === '#' && empty($menu->icon_png) && $menu->children->isEmpty()))
                @php $inVirtualGroup = false; @endphp
                <li class="pc-item pc-caption text-primary font-bold">
                    <label class="parent_menu_title">{{ $menu->title }}</label>
                    <i class="ti ti-point"></i>
                </li>
                @continue
            @endif

            {{-- VISUAL PARENT (# URL with icon) --}}
            @if($menu->url === '#' && $menu->children->isEmpty())
                @php $inVirtualGroup = true; @endphp
                <li class="pc-item" style="background: #f8fafc; margin-bottom: 2px;">
                    <a href="javascript:void(0);" class="pc-link" style="cursor: default;">
                        <span class="pc-micon text-primary">
                            @if($menu->icon_type == 'ICON')
                                <i class="{{ $menu->icon_png ?? 'ti ti-circle' }}"></i>
                            @else
                                <img src="{{ asset('storage/' . $menu->icon_png) }}" width="20" height="20" alt="">
                            @endif
                        </span>
                        <span class="pc-mtext text-primary" style="font-weight: 600;">{{ $menu->title }}</span>
                    </a>
                </li>
                @continue
            @endif

            @php
                $hasChildren = $menu->children && $menu->children->isNotEmpty();
                $isActive = $menu->url && $menu->url !== '#' && Request::is(trim($menu->url, '/') . '*');

                // If not directly active, check if any child is active
                if (!$isActive && $hasChildren) {
                    foreach ($menu->children as $child) {
                        if ($child->url && Request::is(trim($child->url, '/') . '*')) {
                            $isActive = true;
                            break;
                        }
                    }
                }

                // Child Visual Styling
                $linkStyle = $inVirtualGroup ? "padding-left: 3rem;" : "";
                $iconStyle = $inVirtualGroup ? "opacity: 0.7;" : "";
            @endphp

            @if($hasChildren)
                <li class="pc-item pc-hasmenu {{ $isActive ? 'active pc-trigger' : '' }}" style="border-bottom: 1px solid #e0e5e8; margin-bottom: 2px;">
                    <a href="#!" class="pc-link" style="{{ $linkStyle }}">
                        <span class="pc-micon" style="{{ $iconStyle }}">
                            @if($menu->icon_type == 'ICON')
                                <i class="{{ $menu->icon_png ?? 'ti ti-circle' }}"></i>
                            @else
                                <img src="{{ asset('storage/' . $menu->icon_png) }}" width="20" height="20" alt="">
                            @endif
                        </span>
                        <span class="pc-mtext">{{ $menu->title }}</span>
                        <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        @foreach($menu->children as $child)
                            @php
                                $childUrl = getSidebarMenuUrl($child);
                                $isExt = isExternalUrl($childUrl);
                            @endphp
                            <li class="pc-item {{ Request::is(trim($child->url, '/') . '*') ? 'active' : '' }}">
                                <a class="pc-link {{ $isExt ? 'external-link' : '' }}" href="{{ $childUrl }}" {!! !empty($child->file_name) || $isExt ? 'target="_blank"' : '' !!}>{{ $child->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @else
                @php
                    $menuUrl = getSidebarMenuUrl($menu);
                    $isExt = isExternalUrl($menuUrl);
                @endphp
                <li class="pc-item {{ $isActive ? 'active' : '' }}" style="border-bottom: 1px solid #e0e5e8; margin-bottom: 2px;">
                    <a href="{{ $menuUrl }}" class="pc-link {{ $isExt ? 'external-link' : '' }}" {!! !empty($menu->file_name) || $isExt ? 'target="_blank"' : '' !!} style="{{ $linkStyle }}">
                        <span class="pc-micon" style="{{ $iconStyle }}">
                            @if($menu->icon_type == 'ICON')
                                <i class="{{ $menu->icon_png ?? 'ti ti-circle' }}"></i>
                            @else
                                <img src="{{ asset('storage/' . $menu->icon_png) }}" width="20" height="20" alt="">
                            @endif
                        </span>
                        <span class="pc-mtext">{{ $menu->title }}</span>
                    </a>
                </li>
            @endif

        @endforeach
    @endif
</ul>

<script @cspNonce>
    document.addEventListener('DOMContentLoaded', function () {
        const externalLinks = document.querySelectorAll('.external-link');
        externalLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                const target = this.getAttribute('target');
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'External Portal Notice',
                        text: "This link will take you to a separate web portal. For any query regarding the content of this portal, please contact the Portal's Administrator.",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (target === '_blank') {
                                window.open(url, '_blank');
                            } else {
                                window.location.href = url;
                            }
                        }
                    });
                } else {
                    if (confirm("External Portal Notice\n\nThis link will take you to a separate web portal. For any query regarding the content of this portal, please contact the Portal's Administrator.")) {
                        if (target === '_blank') {
                            window.open(url, '_blank');
                        } else {
                            window.location.href = url;
                        }
                    }
                }
            });
        });
    });
</script>