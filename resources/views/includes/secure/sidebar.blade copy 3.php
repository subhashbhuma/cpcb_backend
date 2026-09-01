<ul class="pc-navbar">
    @can('view website dashboard')
    <li class="pc-item">
        <a href="{{ route('secure.dashboard') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
            <span class="pc-mtext">Dashboard</span>
        </a>
    </li>
    @endcan

    @if(!Auth::user()->hasRole('Employee'))
    <li class="pc-item pc-caption">
        <label>Website Setup</label>
    </li>
    @endif

    {{-- Dynamic Sidebar Menus --}}
    @if(isset($sidebarDynamicMenus) && $sidebarDynamicMenus->isNotEmpty())
    @foreach($sidebarDynamicMenus as $menu)
    @if($menu->url === '#' && empty($menu->icon_png) && $menu->children->isEmpty())
    <li class="pc-item pc-caption">
        <label>{{ $menu->title }}</label>
    </li>
    @continue
    @endif

    @php
    $isActive = $menu->url && $menu->url != '#' && Request::is(trim($menu->url, '/') . '*');
    $hasActiveChild = $menu->children->contains(function($child) {
    return $child->url && $child->url != '#' && Request::is(trim($child->url, '/')) ;
    });
    @endphp
    <li
        class="pc-item {{ $menu->children->isNotEmpty() ? 'pc-hasmenu' : '' }} {{ ($isActive || $hasActiveChild) ? 'active pc-trigger' : '' }}">
        <a href="{{ $menu->children->isNotEmpty() ? '#' : ($menu->url ? url($menu->url) : '#') }}" class="pc-link">
            <span class="pc-micon">
                @if($menu->icon_type == 'ICON')
                <i class="{{ $menu->icon_png ?? 'ti ti-circle' }}"></i>
                @else
                <img src="{{ asset('storage/' . $menu->icon_png) }}" width="20" height="20" alt="">
                @endif
            </span>
            <span class="pc-mtext">{{ $menu->title }}</span>
            @if($menu->children->isNotEmpty())
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            @endif
        </a>
        @if($menu->children->isNotEmpty())
        <ul class="pc-submenu" style="{{ $hasActiveChild ? 'display: block;' : '' }}">
            @foreach($menu->children as $child)
            @if($child->userCanView())
            <li class="pc-item {{ ($child->url && Request::is(trim($child->url, '/'))) ? 'active' : '' }}">
                <a class="pc-link" href="{{ $child->url ? url($child->url) : '#' }}">{{ $child->title }}</a>
            </li>
            @endif
            @endforeach
        </ul>
        @endif
    </li>
    @endforeach
    @endif

    @can('view page')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-file"></i></span><span class="pc-mtext"> Pages </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add page')
            <li class="pc-item"><a class="pc-link" href="{{ route('pages.create') }}">Add New</a></li>
            @endcan
            @can('view page')
            <li class="pc-item"><a class="pc-link" href="{{ route('pages.index') }}">All Pages</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('view media')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-photo"></i></span><span class="pc-mtext"> Medias </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add media')
            <li class="pc-item"><a class="pc-link" href="{{ route('medias.create') }}">Add New</a></li>
            @endcan
            @can('view media')
            <li class="pc-item"><a class="pc-link" href="{{ route('medias.index') }}">All Medias</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('view latest cpcb')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-world"></i></span><span class="pc-mtext"> Latest CPCB Setup </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add latest cpcb')
            <li class="pc-item"><a class="pc-link" href="{{ route('latest-cpcbs.create') }}">Add New</a></li>
            @endcan
            @can('view latest cpcb')
            <li class="pc-item"><a class="pc-link" href="{{ route('latest-cpcbs.index') }}">All Latest CPCB</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('view slider')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-slideshow"></i></span><span class="pc-mtext"> Slider Setup </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add slider')
            <li class="pc-item"><a class="pc-link" href="{{ route('sliders.create') }}">Add New</a></li>
            @endcan
            @can('view slider')
            <li class="pc-item"><a class="pc-link" href="{{ route('sliders.index') }}">All Sliders</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('view announcement')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-speakerphone"></i></span><span class="pc-mtext"> Announcement Setup
            </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add announcement')
            <li class="pc-item"><a class="pc-link" href="{{ route('announcements.create') }}">Add New</a></li>
            @endcan
            @can('view announcement')
            <li class="pc-item"><a class="pc-link" href="{{ route('announcements.index') }}">All Announcements</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('view who is who')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span><span class="pc-mtext"> Who Is Who Setup </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add who is who')
            <li class="pc-item"><a class="pc-link" href="{{ route('who-is-who.create') }}">Add New</a></li>
            @endcan
            @can('view who is who')
            <li class="pc-item"><a class="pc-link" href="{{ route('who-is-who.index') }}">All Who Is Who</a></li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add head office')
            <li class="pc-item"><a class="pc-link" href="{{ route('head_offices.create') }}">Add Head Office</a></li>
            @endcan
            @can('view head office')
            <li class="pc-item"><a class="pc-link" href="{{ route('head_offices.index') }}">All Head Offices</a></li>
            @endcan
            @can('add regional directorate')
            <li class="pc-item"><a class="pc-link" href="{{ route('regional_directorates.create') }}">Add Regional
                    Directorate</a></li>
            @endcan
            @can('view regional directorate')
            <li class="pc-item"><a class="pc-link" href="{{ route('regional_directorates.index') }}">All Regional
                    Directorates</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @canany(['view environmental regulation', 'view environmental regulation detail', 'view environmental regulation
    tab', 'view environmental regulation tab detail'])
    @can('view environmental regulation detail')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-social"></i></span><span class="pc-mtext">Environmental
                Regulation</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add environmental regulation')
            <li class="pc-item"><a class="pc-link" href="{{ route('environmental-regulation.create') }}">Add New</a>
            </li>
            @endcan
            @can('view environmental regulation')
            <li class="pc-item"><a class="pc-link" href="{{ route('environmental-regulation.index') }}">All Tabs</a>
            </li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add environmental regulation detail')
            <li class="pc-item"><a class="pc-link" href="{{ route('environmental-regulation-details.create') }}">Add
                    New</a></li>
            @endcan
            @can('view environmental regulation detail')
            <li class="pc-item"><a class="pc-link" href="{{ route('environmental-regulation-details.index') }}">All
                    Details</a></li>
            @endcan
        </ul>
    </li>
    @endcan
    @endcanany

    @canany(['view information centers', 'add information centers'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-info-circle"></i></span><span class="pc-mtext">Information Centers
            </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add information centers')
            <li class="pc-item"><a class="pc-link" href="{{ route('information-centers.create') }}">Add New Tab</a></li>
            @endcan
            @can('view information centers')
            <li class="pc-item"><a class="pc-link" href="{{ route('information-centers.index') }}">All Centers Tabs</a>
            </li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add information center detail')
            <li class="pc-item"><a class="pc-link" href="{{ route('information-center-details.create') }}">Add New
                    Detail</a></li>
            @endcan
            @can('view information center detail')
            <li class="pc-item"><a class="pc-link" href="{{ route('information-center-details.index') }}">All Centers
                    Details</a></li>
            @endcan
        </ul>
    </li>
    @endcanany


    @canany(['view gallery event'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-calendar"></i></span><span class="pc-mtext">Gallery Event Setup
            </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add gallery event')
            <li class="pc-item"><a class="pc-link" href="{{ route('gallery-event.create') }}">Add New</a></li>
            @endcan
            @can('view gallery event')
            <li class="pc-item"><a class="pc-link" href="{{ route('gallery-event.index') }}">All Events</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @canany('view photo gallery')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-camera"></i></span>
            <span class="pc-mtext">Gallery Setup</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add photo gallery')
            <li class="pc-item"><a class="pc-link" href="{{ route('photo-gallery.create') }}">Add New</a></li>
            @endcan
            @can('view photo gallery')
            <li class="pc-item"><a class="pc-link" href="{{ route('photo-gallery.index') }}">All Photo Gallery</a></li>
            @endcan
        </ul>
    </li>
    @endcanany

    @can('view video gallery')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-video"></i></span><span class="pc-mtext"> Video Gallery Setup </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add video gallery')
            <li class="pc-item"><a class="pc-link" href="{{ route('video-gallery.create') }}">Add New</a></li>
            @endcan
            @can('view video gallery')
            <li class="pc-item"><a class="pc-link" href="{{ route('video-gallery.index') }}">All Video Gallery</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('view social media')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-social"></i></span><span class="pc-mtext"> Social Media Setup </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add social media')
            <li class="pc-item"><a class="pc-link" href="{{ route('social-medias.create') }}">Add New</a></li>
            @endcan
            @can('view social media')
            <li class="pc-item"><a class="pc-link" href="{{ route('social-medias.index') }}">All Social Media</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('view government portal')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-world"></i></span><span class="pc-mtext"> Govt. Portal Setup </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add government portal')
            <li class="pc-item"><a class="pc-link" href="{{ route('government-portals.create') }}">Add New</a></li>
            @endcan
            @can('view government portal')
            <li class="pc-item"><a class="pc-link" href="{{ route('government-portals.index') }}">All Govt. Portals</a>
            </li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('view cpcb portal')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-building-community"></i></span><span class="pc-mtext"> CPCB. Portal
                Setup </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add cpcb portal')
            <li class="pc-item"><a class="pc-link" href="{{ route('cpcb-portals.create') }}">Add New</a></li>
            @endcan
            @can('view cpcb portal')
            <li class="pc-item"><a class="pc-link" href="{{ route('cpcb-portals.index') }}">All CPCB. Portals</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @canany(['view job', 'add job', 'view job post', 'add job post', 'view recruitment announcement', 'add recruitment
    announcement'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-file-invoice"></i></span><span class="pc-mtext"> Jobs Setup </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('view job')
            <li class="pc-item"><a class="pc-link" href="{{ route('jobs.index') }}">All Jobs</a></li>
            @endcan
            @can('add job')
            <li class="pc-item"><a class="pc-link" href="{{ route('jobs.create') }}">Add New Job</a></li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('view job post')
            <li class="pc-item"><a class="pc-link" href="{{ route('job-posts.index') }}">All Job Posts</a></li>
            @endcan
            @can('add job post')
            <li class="pc-item"><a class="pc-link" href="{{ route('job-posts.create') }}">Add New Job Post</a></li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('view recruitment announcement')
            <li class="pc-item"><a class="pc-link" href="{{ route('recruitment-announcements.index') }}">Recruitment
                    Announcements</a></li>
            @endcan
            @can('add recruitment announcement')
            <li class="pc-item"><a class="pc-link" href="{{ route('recruitment-announcements.create') }}">Add New
                    Announcement</a></li>
            @endcan
        </ul>
    </li>
    @endcan



    @canany(['view technical report', 'view subject area'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-file-analytics"></i></span><span class="pc-mtext"> Technical Report
                Setup </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add subject area')
            <li class="pc-item"><a class="pc-link" href="{{ route('subject-area.create') }}">Add New Subject</a></li>
            @endcan
            @can('view subject area')
            <li class="pc-item"><a class="pc-link" href="{{ route('subject-area.index') }}">All Subject</a></li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add technical report')
            <li class="pc-item"><a class="pc-link" href="{{ route('technical_report.create') }}">Add New</a></li>
            @endcan
            @can('view technical report')
            <li class="pc-item"><a class="pc-link" href="{{ route('technical_report.index') }}">All Technical Report</a>
            </li>
            @endcan
        </ul>
    </li>
    @endcanany

    @canany(['view studies report'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-report-analytics"></i></span><span class="pc-mtext"> Studies &
                Reports </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add studies report')
            <li class="pc-item"><a class="pc-link" href="{{ route('studies_reports.create') }}">Add New</a></li>
            @endcan
            @can('view studies report')
            <li class="pc-item"><a class="pc-link" href="{{ route('studies_reports.index') }}">All Records</a></li>
            @endcan
        </ul>
    </li>
    @endcanany

    @can(abilities: 'view annual report')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-calendar"></i></span><span class="pc-mtext"> Annual Report Setup
            </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add annual report')
            <li class="pc-item"><a class="pc-link" href="{{ route('annual_report.create') }}">Add New</a></li>
            @endcan
            @can('view annual report')
            <li class="pc-item"><a class="pc-link" href="{{ route('annual_report.index') }}">All Annual Reports</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @canany(['view publication', 'view publication category'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-clipboard"></i></span>
            <span class="pc-mtext">Publication Setup</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add publication category')
            <li class="pc-item"><a class="pc-link" href="{{ route('publication_category.create') }}">Add New
                    Category</a></li>
            @endcan
            @can('view publication category')
            <li class="pc-item"><a class="pc-link" href="{{ route('publication_category.index') }}">All Categories</a>
            </li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add publication')
            <li class="pc-item"><a class="pc-link" href="{{ route('publication.create') }}">Add New Publication</a></li>
            @endcan
            @can('view publication')
            <li class="pc-item"><a class="pc-link" href="{{ route('publication.index') }}">All Publications</a></li>
            @endcan
        </ul>
    </li>
    @endcanany

    @canany(['view tender', 'view tender category', 'view zonal office'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-clipboard"></i></span>
            <span class="pc-mtext">Tender Setup</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add tender category')
            <li class="pc-item"><a class="pc-link" href="{{ route('tender_category.create') }}">Add New Category</a>
            </li>
            @endcan
            @can('view tender category')
            <li class="pc-item"><a class="pc-link" href="{{ route('tender_category.index') }}">All Categories</a></li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add zonal office')
            <li class="pc-item"><a class="pc-link" href="{{ route('zonal_office.create') }}">Add New Zonal Office</a>
            </li>
            @endcan
            @can('view zonal office')
            <li class="pc-item"><a class="pc-link" href="{{ route('zonal_office.index') }}">All Zonal Offices</a></li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add tender')
            <li class="pc-item"><a class="pc-link" href="{{ route('tenders.create') }}">Add New Tender</a></li>
            @endcan
            @can('view tender')
            <li class="pc-item"><a class="pc-link" href="{{ route('tenders.index') }}">All Tenders</a></li>
            @endcan
        </ul>
    </li>
    @endcanany

    @can('view faq')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-messages"></i></span><span class="pc-mtext"> FAQ Setup </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add faq')
            <li class="pc-item"><a class="pc-link" href="{{ route('faq.create') }}">Add New</a></li>
            @endcan
            @can('view faq')
            <li class="pc-item"><a class="pc-link" href="{{ route('faq.index') }}">All FAQ</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('view circular')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-bell"></i></span><span class="pc-mtext">
                @if(Auth::user()->hasRole("Employee"))
                Circular
                @else
                Circular Setup
                @endif
            </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add circular')
            <li class="pc-item"><a class="pc-link" href="{{ route('circulars.create') }}">Add New</a></li>
            @endcan
            @can('view circular')
            <li class="pc-item"><a class="pc-link" href="{{ route('circulars.index') }}">All Circulars</a></li>
            @endcan
        </ul>
    </li>
    @endcan



    @can('view contact detail')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-phone"></i></span><span class="pc-mtext"> Contact Detail Setup
            </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add contact detail')
            <li class="pc-item"><a class="pc-link" href="{{ route('contact-details.create') }}">Add New</a></li>
            @endcan
            @can('view contact detail')
            <li class="pc-item"><a class="pc-link" href="{{ route('contact-details.index') }}">All Contact Detail</a>
            </li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('view directory')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-phone"></i></span><span class="pc-mtext"> Directory Setup </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add directory')
            <li class="pc-item"><a class="pc-link" href="{{ route('directories.create') }}">Add New</a></li>
            @endcan
            @can('view directory')
            <li class="pc-item"><a class="pc-link" href="{{ route('directories.index') }}">All Directory</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @canany(['add direction category', 'view direction category', 'add direction state', 'view direction state', 'add
    direction', 'view direction', 'add direction issued to', 'view direction issued to', 'add direction issued by',
    'view direction issued by', 'add direction type', 'view direction type', 'add direction subject', 'view direction
    subject'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-directions"></i></span><span class="pc-mtext">Direction Setup</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>


        <ul class="pc-submenu">
            @can('add direction act type')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction_act_type.create') }}">Add Direction
                    Act Type</a></li>
            @endcan
            @can('view direction act type')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction_act_type.index') }}">All Direction
                    Act Types</a></li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add direction_type')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction-types.create') }}">Add Direction Type</a>
            </li>
            @endcan
            @can('view direction_type')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction-types.index') }}">Direction Type</a></li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add direction subject')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction_subject.create') }}">Add New Direction
                    Subject</a></li>
            @endcan
            @can('view direction subject')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction_subject.index') }}">All Direction
                    Subjects</a></li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add direction state')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction_state.create') }}">Add New Direction
                    State</a></li>
            @endcan
            @can('view direction state')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction_state.index') }}">All Direction States</a>
            </li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add direction category')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction_category.create') }}">Add New Direction
                    Category</a></li>
            @endcan
            @can('view direction category')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction_category.index') }}">All Direction
                    Categories</a></li>
            @endcan

            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add direction issued to')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction_issued_to.create') }}">Add New Direction
                    Issued To</a></li>
            @endcan
            @can('view direction issued to')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction_issued_to.index') }}">All Direction Issued
                    Tos</a></li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add direction')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction.create') }}">Add New Direction</a></li>
            @endcan
            @can('view direction')
            <li class="pc-item"><a class="pc-link" href="{{ route('direction.index') }}">All Directions</a></li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add letters_issued')
            <li class="pc-item"><a class="pc-link" href="{{ route('letters-issued.create') }}">Add New Letter Issued</a>
            </li>
            @endcan
            @can('view letters_issued')
            <li class="pc-item"><a class="pc-link" href="{{ route('letters-issued.index') }}">All Letters Issued</a>
            </li>
            @endcan
        </ul>
    </li>
    @endcan


    @canany(['view comment report', 'add comment report'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-messages"></i></span>
            <span class="pc-mtext">Comment Reports</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add comment report')
            <li class="pc-item">
                <a class="pc-link" href="{{ route('comment-reports.create') }}">Add New</a>
            </li>
            @endcan

            @can('view comment report')
            <li class="pc-item">
                <a class="pc-link" href="{{ route('comment-reports.index') }}">All Reports</a>
            </li>
            @endcan
        </ul>
    </li>
    @endcanany

    @canany(['view ngt court case', 'add ngt court case'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-report"></i></span>
            <span class="pc-mtext">NGT Court Cases</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add ngt court case')
            <li class="pc-item">
                <a class="pc-link" href="{{ route('ngt-court-cases.create') }}">Add New Case</a>
            </li>
            @endcan

            @can('view ngt court case')
            <li class="pc-item">
                <a class="pc-link" href="{{ route('ngt-court-cases.index') }}">All Court Cases</a>
            </li>
            @endcan
        </ul>
    </li>
    @endcanany


    @canany(['view fortnightly report', 'add fortnightly report'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-calendar-stats"></i></span>
            <span class="pc-mtext">Fortnightly Reports</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add fortnightly report')
            <li class="pc-item">
                <a class="pc-link" href="{{ route('fortnightly-reports.create') }}">Add New</a>
            </li>
            @endcan

            @can('view fortnightly report')
            <li class="pc-item">
                <a class="pc-link" href="{{ route('fortnightly-reports.index') }}">All Reports</a>
            </li>
            @endcan
        </ul>
    </li>
    @endcanany


    @canany(['view agra air quality', 'add agra air quality', 'add quality zone', 'view quality zone', ''])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-wind"></i></span>
            <span class="pc-mtext">Agra Air Quality</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">

            @can('add quality zone')
            <li class="pc-item"><a class="pc-link" href="{{ route('quality-zones.create') }}">Add Zone</a></li>
            @endcan
            @can('view quality zone')
            <li class="pc-item"><a class="pc-link" href="{{ route('quality-zones.index') }}">All Zones</a></li>
            @endcan

            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add agra air quality')
            <li class="pc-item">
                <a class="pc-link" href="{{ route('agra-air-qualities.create') }}">Add New Data</a>
            </li>
            @endcan

            @can('view agra air quality')
            <li class="pc-item">
                <a class="pc-link" href="{{ route('agra-air-qualities.index') }}">All Air Quality</a>
            </li>
            @endcan

            <!-- @can('view agra air quality')
                        <li class="pc-item"><a class="pc-link" href="{{ route('agra-air-quality.annual-average-file') }}">Agra
                                Report File</a></li>
                    @endcan -->
        </ul>
    </li>
    @endcanany




    @can('view feedback')
    <li class="pc-item">
        <a href="{{ route('feedback.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-book"></i></span>
            <span class="pc-mtext">Feedback</span>
        </a>
    </li>
    @endcan

    @can('view complaint')
    <li class="pc-item">
        <a href="{{ route('complaint.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
            <span class="pc-mtext">Complaint</span>
        </a>
    </li>
    @endcan

    @canany(['view gallery event', 'view photo gallery sub event', 'view page category', 'view query form subject',
    'view complaint form subject', 'view quality zone', 'view agra air quality', 'view direction category', 'view
    fortnightly report', 'view comment report'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-settings"></i></span><span class="pc-mtext">Master Setup </span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">

            @can('add division')
            <li class="pc-item"><a class="pc-link" href="{{ route('division.create') }}">Add New Division</a></li>
            @endcan
            @can('view division')
            <li class="pc-item"><a class="pc-link" href="{{ route('division.index') }}">All Division</a></li>
            @endcan

            <hr style="border-top: 1px solid;" class="m-0 p-0">

            @can('add designation')
            <li class="pc-item"><a class="pc-link" href="{{ route('designation.create') }}">Add New Designation</a></li>
            @endcan
            @can('view designation')
            <li class="pc-item"><a class="pc-link" href="{{ route('designation.index') }}">All Designation</a></li>
            @endcan

            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add query form subject')
            <li class="pc-item"><a class="pc-link" href="{{ route('query_form_subject.create') }}">Add New Query Form
                    Subject</a></li>
            @endcan
            @can('view query form subject')
            <li class="pc-item"><a class="pc-link" href="{{ route('query_form_subject.index') }}">All Query Form
                    Subjects</a></li>
            @endcan
            <hr style="border-top: 1px solid;" class="m-0 p-0">
            @can('add complaint form subject')
            <li class="pc-item"><a class="pc-link" href="{{ route('complaint_form_subject.create') }}">Add New Complaint
                    Form Subject</a></li>
            @endcan
            @can('view complaint form subject')
            <li class="pc-item"><a class="pc-link" href="{{ route('complaint_form_subject.index') }}">All Complaint Form
                    Subjects</a></li>
            @endcan
        </ul>
    </li>
    @endcanany


    @if(!Auth::user()->hasRole('Employee'))
    <li class="pc-item pc-caption">
        <label>Logs</label>
        <i class="ti ti-settings"></i>
    </li>
    @endif

    @can('view audit log')
    <li class="pc-item">
        <a href="{{ route('audit-logs.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-book"></i></span>
            <span class="pc-mtext">Audit Log</span>
        </a>
    </li>
    @endcan

    @can('view authentication log')
    <li class="pc-item">
        <a href="{{ route('authentication-logs.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-login"></i></span>
            <span class="pc-mtext">Authentication Log</span>
        </a>
    </li>
    @endcan

    @can('edit site setting')
    <li class="pc-item pc-caption">
        <label>Site Settings</label>
        <i class="ti ti-settings"></i>
    </li>
    <li class="pc-item">
        <a href="{{ route('site-settings.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
            <span class="pc-mtext">Site Settings</span>
        </a>
    </li>
    @endcan

    @can('menu setup')
    <li class="pc-item">
        <a href="{{ route('menus.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-lock"></i></span>
            <span class="pc-mtext">Menu Setup</span>
        </a>
    </li>
    @endcan

    @canany(['view user', 'view role'])
    <li class="pc-item pc-caption">
        <label>Authentication Setup</label>
        <i class="ti ti-news"></i>
    </li>
    @endcan

    @can('view user')
    <li class="pc-item">
        <a href="{{ route('users.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-lock"></i></span>
            <span class="pc-mtext">Users Setup</span>
        </a>
    </li>
    @endcan

    @can('view employee')
    <li class="pc-item">
        <a href="{{ route('employee.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span>
            <span class="pc-mtext">Employee Setup</span>
        </a>
    </li>
    @endcan

    @can('view role')
    <li class="pc-item">
        <a href="{{ route('roles.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
            <span class="pc-mtext">Roles Setup</span>
        </a>
    </li>
    @endcan
</ul>