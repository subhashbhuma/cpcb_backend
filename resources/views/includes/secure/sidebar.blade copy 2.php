<ul class="pc-navbar">

    <li class="pc-item pc-caption">
        <label>Main</label>
    </li>

    @can('view website dashboard')
    <li class="pc-item">
        <a href="{{ route('secure.dashboard') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-layout-dashboard"></i></span>
            <span class="pc-mtext">Dashboard</span>
        </a>
    </li>
    @endcan

    <li class="pc-item pc-caption">
        <label>Content Manager</label>
    </li>

    @can('view page')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-files"></i></span><span class="pc-mtext">Pages</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add page') <li class="pc-item"><a class="pc-link" href="{{ route('pages.create') }}">Add New</a></li> @endcan
            @can('view page') <li class="pc-item"><a class="pc-link" href="{{ route('pages.index') }}">All Pages</a></li> @endcan
        </ul>
    </li>
    @endcan

    @can('view media')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-folders"></i></span><span class="pc-mtext">Media Library</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('add media') <li class="pc-item"><a class="pc-link" href="{{ route('medias.create') }}">Upload Media</a></li> @endcan
            @can('view media') <li class="pc-item"><a class="pc-link" href="{{ route('medias.index') }}">All Medias</a></li> @endcan
        </ul>
    </li>
    @endcan

    @canany(['view photo gallery', 'view video gallery'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-photo-video"></i></span><span class="pc-mtext">Galleries</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('view photo gallery') <li class="pc-item"><a class="pc-link" href="{{ route('photo-gallery.index') }}">Photo Gallery</a></li> @endcan
            @can('view video gallery') <li class="pc-item"><a class="pc-link" href="{{ route('video-gallery.index') }}">Video Gallery</a></li> @endcan
        </ul>
    </li>
    @endcanany

    <li class="pc-item pc-caption">
        <label>Resources & Reports</label>
    </li>

    @canany(['view technical report', 'view annual report', 'view studies report'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-file-text"></i></span><span class="pc-mtext">Reports Center</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('view technical report') <li class="pc-item"><a class="pc-link" href="{{ route('technical_report.index') }}">Technical Reports</a></li> @endcan
            @can('view annual report') <li class="pc-item"><a class="pc-link" href="{{ route('annual_report.index') }}">Annual Reports</a></li> @endcan
            @can('view studies report') <li class="pc-item"><a class="pc-link" href="{{ route('studies_reports.index') }}">Studies & Reports</a></li> @endcan
        </ul>
    </li>
    @endcanany

    @canany(['view publication', 'view tender'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-news"></i></span><span class="pc-mtext">Publications & Tenders</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('view publication') <li class="pc-item"><a class="pc-link" href="{{ route('publication.index') }}">Publications</a></li> @endcan
            @can('view tender') <li class="pc-item"><a class="pc-link" href="{{ route('tenders.index') }}">Tenders</a></li> @endcan
        </ul>
    </li>
    @endcanany

    @can('view job')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-briefcase"></i></span><span class="pc-mtext">Careers / Jobs</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('jobs.create') }}">Post New Job</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('jobs.index') }}">Active Listings</a></li>
        </ul>
    </li>
    @endcan

    <li class="pc-item pc-caption">
        <label>Portal Management</label>
    </li>

    @canany(['view government portal', 'view cpcb portal', 'view latest cpcb'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-world-latitude"></i></span><span class="pc-mtext">Govt Portals</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('view government portal') <li class="pc-item"><a class="pc-link" href="{{ route('government-portals.index') }}">Govt. Portals</a></li> @endcan
            @can('view cpcb portal') <li class="pc-item"><a class="pc-link" href="{{ route('cpcb-portals.index') }}">CPCB Portals</a></li> @endcan
            @can('view latest cpcb') <li class="pc-item"><a class="pc-link" href="{{ route('latest-cpcbs.index') }}">Latest CPCB Setup</a></li> @endcan
        </ul>
    </li>
    @endcanany

    <li class="pc-item pc-caption">
        <label>Directories</label>
    </li>

    @can('view who is who')
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span><span class="pc-mtext">Organization Structure</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('who-is-who.index') }}">Who is Who</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('head_offices.index') }}">Head Offices</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('regional_directorates.index') }}">Regional Directorates</a></li>
        </ul>
    </li>
    @endcan

    @canany(['view contact detail', 'view directory', 'view regional directory'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-address-book"></i></span><span class="pc-mtext">Contact Directories</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('view contact detail') <li class="pc-item"><a class="pc-link" href="{{ route('contact-details.index') }}">General Contacts</a></li> @endcan
            @can('view directory') <li class="pc-item"><a class="pc-link" href="{{ route('directories.index') }}">Staff Directory</a></li> @endcan
            @can('view regional directory') <li class="pc-item"><a class="pc-link" href="{{ route('regional_directories.index') }}">RD Directory</a></li> @endcan
        </ul>
    </li>
    @endcanany

    <li class="pc-item pc-caption">
        <label>Home & UI Setup</label>
    </li>

    @canany(['view slider', 'view announcement', 'view event', 'view circular'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-browser-check"></i></span><span class="pc-mtext">Dynamic UI</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('view slider') <li class="pc-item"><a class="pc-link" href="{{ route('sliders.index') }}">Homepage Sliders</a></li> @endcan
            @can('view announcement') <li class="pc-item"><a class="pc-link" href="{{ route('announcements.index') }}">Announcements</a></li> @endcan
            @can('view circular') <li class="pc-item"><a class="pc-link" href="{{ route('circulars.index') }}">Circulars</a></li> @endcan
            @can('view event') <li class="pc-item"><a class="pc-link" href="{{ route('events.index') }}">Events</a></li> @endcan
        </ul>
    </li>
    @endcanany

    @canany(['view environmental regulation', 'view environmental regulation detail'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-leaf"></i></span><span class="pc-mtext">Env. Regulations</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('environmental-regulation.index') }}">Regulation Tabs</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('environmental-regulation-details.index') }}">Regulation Details</a></li>
        </ul>
    </li>
    @endcanany

    <li class="pc-item pc-caption">
        <label>System Masters</label>
    </li>

    @canany(['view gallery event', 'view page category', 'view feedback', 'view complaint'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-database"></i></span><span class="pc-mtext">Master Data</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('view gallery event') <li class="pc-item"><a class="pc-link" href="{{ route('gallery-event.index') }}">Gallery Events</a></li> @endcan
            @can('view page category') <li class="pc-item"><a class="pc-link" href="{{ route('page_category.index') }}">Page Categories</a></li> @endcan
            @can('view feedback') <li class="pc-item"><a class="pc-link" href="{{ route('feedback.index') }}">User Feedbacks</a></li> @endcan
            @can('view complaint') <li class="pc-item"><a class="pc-link" href="{{ route('complaint.index') }}">Complaints</a></li> @endcan
        </ul>
    </li>
    @endcanany

    <li class="pc-item pc-caption">
        <label>Settings & Logs</label>
    </li>

    @canany(['view user', 'view role'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-shield-lock"></i></span><span class="pc-mtext">Access Control</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('view user') <li class="pc-item"><a class="pc-link" href="{{ route('users.index') }}">Users Management</a></li> @endcan
            @can('view role') <li class="pc-item"><a class="pc-link" href="{{ route('roles.index') }}">Roles & Permissions</a></li> @endcan
        </ul>
    </li>
    @endcanany

    @canany(['view audit log', 'view authentication log'])
    <li class="pc-item pc-hasmenu">
        <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-history"></i></span><span class="pc-mtext">System Logs</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="pc-submenu">
            @can('view audit log') <li class="pc-item"><a class="pc-link" href="{{ route('audit-logs.index') }}">Activity Logs</a></li> @endcan
            @can('view authentication log') <li class="pc-item"><a class="pc-link" href="{{ route('authentication-logs.index') }}">Login History</a></li> @endcan
        </ul>
    </li>
    @endcanany

    @can('edit site setting')
    <li class="pc-item">
        <a href="{{ route('site-settings.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-settings-automation"></i></span>
            <span class="pc-mtext">Global Site Settings</span>
        </a>
    </li>
    @endcan

</ul>