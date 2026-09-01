<ul class="pc-navbar">
    @can('view website dashboard')
        <li class="pc-item">
            <a href="{{ route('secure.dashboard') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                <span class="pc-mtext">Dashboard</span>
            </a>
        </li>
    @endcan

    @can('view page')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-file"></i></span><span class="pc-mtext"> Pages
                </span>
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



    @can('view latest cpcb')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-world"></i></span><span class="pc-mtext"> Latest CPCB Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add latest cpcb')
                    <li class="pc-item"><a class="pc-link" href="{{ route('latest-cpcbs.create') }}">Add New</a></li>
                @endcan
                @can('view latest cpcb')
                    <li class="pc-item"><a class="pc-link" href="{{ route('latest-cpcbs.index') }}">All Latest CPCB</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcan


    @can('view media')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-photo"></i></span><span class="pc-mtext"> Medias
                </span>
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

    @can('view homepage about')
        <li class="pc-item">
            <a href="{{ route('home-about.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-pencil"></i></span>
                <span class="pc-mtext">Home About</span>
            </a>
        </li>
    @endcan

    @can('view additional logo')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-live-photo"></i></span><span class="pc-mtext"> Additional Logo Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add additional logo')
                    <li class="pc-item"><a class="pc-link" href="{{ route('additional-logos.create') }}">Add New</a></li>
                @endcan
                @can('view additional logo')
                    <li class="pc-item"><a class="pc-link" href="{{ route('additional-logos.index') }}">All Additional Logo</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcan

    @can('view slider')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-slideshow"></i></span><span class="pc-mtext"> Slider Setup
                </span>
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
                    <li class="pc-item"><a class="pc-link" href="{{ route('announcements.index') }}">All Announcements</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcan

    @can('view government portal')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-world"></i></span><span class="pc-mtext"> Govt. Portal Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add government portal')
                    <li class="pc-item"><a class="pc-link" href="{{ route('government-portals.create') }}">Add New</a></li>
                @endcan
                @can('view government portal')
                    <li class="pc-item"><a class="pc-link" href="{{ route('government-portals.index') }}">All Govt.
                            Portals</a></li>
                @endcan
            </ul>
        </li>
    @endcan
    @can('view cpcb portal')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-world"></i></span><span class="pc-mtext"> CPCB. Portal Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add cpcb portal')
                    <li class="pc-item"><a class="pc-link" href="{{ route('cpcb-portals.create') }}">Add New</a></li>
                @endcan
                @can('view cpcb portal')
                    <li class="pc-item"><a class="pc-link" href="{{ route('cpcb-portals.index') }}">All CPCB. Portals</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcan

    @can('view quick link')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-link"></i></span><span class="pc-mtext"> Quick Link Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add quick link')
                    <li class="pc-item"><a class="pc-link" href="{{ route('quick-links.create') }}">Add New</a></li>
                @endcan
                @can('view quick link')
                    <li class="pc-item"><a class="pc-link" href="{{ route('quick-links.index') }}">All Quick Links</a></li>
                @endcan
            </ul>
        </li>
    @endcan

    @can(abilities: 'view job')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-file-invoice"></i></span><span class="pc-mtext"> Jobs Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add job')
                    <li class="pc-item"><a class="pc-link" href="{{ route('jobs.create') }}">Add New</a></li>
                @endcan
                @can('view job')
                    <li class="pc-item"><a class="pc-link" href="{{ route('jobs.index') }}">All Jobs</a></li>
                @endcan
            </ul>
        </li>
    @endcan

    @can(abilities: 'view regional directory')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-directions"></i></span><span class="pc-mtext"> RD Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add regional directory')
                    <li class="pc-item"><a class="pc-link" href="{{ route('regional_directories.create') }}">Add New</a>
                    </li>
                @endcan
                @can('view regional directory')
                    <li class="pc-item"><a class="pc-link" href="{{ route('regional_directories.index') }}">All
                            Directories</a></li>
                @endcan
            </ul>
        </li>
    @endcan

    @can(abilities: 'view laboratories category')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-flask"></i></span><span class="pc-mtext"> Lab Category Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add laboratories category')
                    <li class="pc-item"><a class="pc-link" href="{{ route('labs_category.create') }}">Add New</a></li>
                @endcan
                @can('view laboratories category')
                    <li class="pc-item"><a class="pc-link" href="{{ route('labs_category.index') }}">All Lab Category</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcan


    @canany(['view technical report', 'view division', 'view subject area'])
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-file-analytics"></i></span><span class="pc-mtext"> Technical
                    Report Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add subject area')
                    <li class="pc-item"><a class="pc-link" href="{{ route('subject-area.create') }}">Add New
                            Subject</a></li>
                @endcan
                @can('view subject area')
                    <li class="pc-item"><a class="pc-link" href="{{ route('subject-area.index') }}">All
                            Subject</a></li>
                @endcan

                <hr style="border-top: 1px solid;" class="m-0 p-0">
                @can('add division')
                    <li class="pc-item"><a class="pc-link" href="{{ route('division.create') }}">Add New
                            Division</a></li>
                @endcan
                @can('view division')
                    <li class="pc-item"><a class="pc-link" href="{{ route('division.index') }}">All
                            Division</a></li>
                @endcan

                <hr style="border-top: 1px solid;" class="m-0 p-0">
                @can('add technical report')
                    <li class="pc-item"><a class="pc-link" href="{{ route('technical_report.create') }}">Add New</a></li>
                @endcan
                @can('view technical report')
                    <li class="pc-item"><a class="pc-link" href="{{ route('technical_report.index') }}">All Technical
                            Report</a></li>
                @endcan
            </ul>
        </li>
    @endcanany


    @canany(['view studies report'])
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-report-analytics"></i></span><span class="pc-mtext"> Studies & Reports
                </span>
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
                    <li class="pc-item"><a class="pc-link" href="{{ route('annual_report.index') }}">All Annual Reports</a>
                    </li>
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
                    <li class="pc-item"><a class="pc-link" href="{{ route('publication_category.index') }}">All
                            Categories</a></li>
                @endcan
                <hr style="border-top: 1px solid;" class="m-0 p-0">
                @can('add publication')
                    <li class="pc-item"><a class="pc-link" href="{{ route('publication.create') }}">Add New Publication</a>
                    </li>
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
                    <li class="pc-item"><a class="pc-link" href="{{ route('tender_category.create') }}">Add New
                            Category</a></li>
                @endcan
                @can('view tender category')
                    <li class="pc-item"><a class="pc-link" href="{{ route('tender_category.index') }}">All
                            Categories</a></li>
                @endcan
                <hr style="border-top: 1px solid;" class="m-0 p-0">
                @can('add zonal office')
                    <li class="pc-item"><a class="pc-link" href="{{ route('zonal_office.create') }}">Add New
                            Zonal Office</a></li>
                @endcan
                @can('view zonal office')
                    <li class="pc-item"><a class="pc-link" href="{{ route('zonal_office.index') }}">All
                            Zonal Offices</a></li>
                @endcan
                <hr style="border-top: 1px solid;" class="m-0 p-0">
                @can('add tender')
                    <li class="pc-item"><a class="pc-link" href="{{ route('tenders.create') }}">Add New Tender</a>
                    </li>
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
                <span class="pc-micon"><i class="ti ti-help-octagon"></i></span><span class="pc-mtext"> FAQ Setup
                </span>
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
                <span class="pc-micon"><i class="ti ti-bell"></i></span><span class="pc-mtext"> Circular Setup
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

    @can('view event')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-calendar"></i></span><span class="pc-mtext"> Event Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add event')
                    <li class="pc-item"><a class="pc-link" href="{{ route('events.create') }}">Add New</a></li>
                @endcan
                @can('view event')
                    <li class="pc-item"><a class="pc-link" href="{{ route('events.index') }}">All Events</a></li>
                @endcan
            </ul>
        </li>
    @endcan




    @canany('view photo gallery')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-photo"></i></span><span class="pc-mtext"> Photo Gallery Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add photo gallery')
                    <li class="pc-item"><a class="pc-link" href="{{ route('photo-gallery.create') }}">Add New</a></li>
                @endcan
                @can('view photo gallery')
                    <li class="pc-item"><a class="pc-link" href="{{ route('photo-gallery.index') }}">All Photo Gallery</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcanany




    @can('view video gallery')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-video"></i></span><span class="pc-mtext"> Video Gallery Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add video gallery')
                    <li class="pc-item"><a class="pc-link" href="{{ route('video-gallery.create') }}">Add New</a></li>
                @endcan
                @can('view video gallery')
                    <li class="pc-item"><a class="pc-link" href="{{ route('video-gallery.index') }}">All Video Gallery</a>
                    </li>
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
                    <li class="pc-item"><a class="pc-link" href="{{ route('contact-details.index') }}">All Contact
                            Detail</a></li>
                @endcan
            </ul>
        </li>
    @endcan

    @can('view directory')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-phone"></i></span><span class="pc-mtext"> Directory Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add directory')
                    <li class="pc-item"><a class="pc-link" href="{{ route('directories.create') }}">Add New</a></li>
                @endcan
                @can('view directory')
                    <li class="pc-item"><a class="pc-link" href="{{ route('directories.index') }}">All Directory
                            </a></li>
                @endcan
            </ul>
        </li>
    @endcan

    @can('view social media')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-social"></i></span><span class="pc-mtext"> Social Media Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add social media')
                    <li class="pc-item"><a class="pc-link" href="{{ route('social-medias.create') }}">Add New</a></li>
                @endcan
                @can('view social media')
                    <li class="pc-item"><a class="pc-link" href="{{ route('social-medias.index') }}">All Social Media</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcan

    @can('view who is who')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-users"></i></span><span class="pc-mtext"> Who Is Who Setup
                </span>
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
                    <li class="pc-item"><a class="pc-link" href="{{ route('regional_directorates.create') }}">Add Regional Directorate</a></li>
                @endcan
                @can('view regional directorate')
                    <li class="pc-item"><a class="pc-link" href="{{ route('regional_directorates.index') }}">All Regional Directorates</a></li>
                @endcan
            </ul>
        </li>
    @endcan



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



    @canany(['view gallery event', 'view photo gallery sub event', 'view page category', 'view query form subject', 'view complaint form subject'])
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-settings"></i></span><span class="pc-mtext">Master Setup
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add gallery event')
                    <li class="pc-item"><a class="pc-link" href="{{ route('gallery-event.create') }}">Add New
                            Gallery Event</a></li>
                @endcan
                @can('view gallery event')
                    <li class="pc-item"><a class="pc-link" href="{{ route('gallery-event.index') }}">All
                            Gallery Events</a></li>
                @endcan

                <hr style="border-top: 1px solid;" class="m-0 p-0">
                @can('add page category')
                    <li class="pc-item"><a class="pc-link" href="{{ route('page_category.create') }}">Add New Page Category</a></li>
                @endcan
                @can('view page category')
                    <li class="pc-item"><a class="pc-link" href="{{ route('page_category.index') }}">All Page Categories</a></li>
                @endcan

                <hr style="border-top: 1px solid;" class="m-0 p-0">
                @can('add query form subject')
                    <li class="pc-item"><a class="pc-link" href="{{ route('query_form_subject.create') }}">Add New Query Form Subject</a></li>
                @endcan
                @can('view query form subject')
                    <li class="pc-item"><a class="pc-link" href="{{ route('query_form_subject.index') }}">All Query Form Subjects</a></li>
                @endcan

                <hr style="border-top: 1px solid;" class="m-0 p-0">
                @can('add complaint form subject')
                    <li class="pc-item"><a class="pc-link" href="{{ route('complaint_form_subject.create') }}">Add New Complaint Form Subject</a></li>
                @endcan
                @can('view complaint form subject')
                    <li class="pc-item"><a class="pc-link" href="{{ route('complaint_form_subject.index') }}">All Complaint Form Subjects</a></li>
                @endcan

                <hr style="border-top: 1px solid;" class="m-0 p-0">
                @can('view feedback')
                    <li class="pc-item"><a class="pc-link" href="{{ route('feedback.index') }}">All Feedbacks</a></li>
                @endcan

                <hr style="border-top: 1px solid;" class="m-0 p-0">
                @can('view complaint')
                    <li class="pc-item"><a class="pc-link" href="{{ route('complaint.index') }}">All Complaints</a></li>
                @endcan
            </ul>
        </li>
    @endcanany


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

    @can('view role')
        <li class="pc-item">
            <a href="{{ route('roles.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                <span class="pc-mtext">Roles Setup</span>
            </a>
        </li>
    @endcan


    <li class="pc-item pc-caption">
        <label>Home Page</label>
        <i class="ti ti-settings"></i>
    </li>
    @can('view environmental regulation')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-social"></i></span><span class="pc-mtext"> Environmental
                    Regulation Tab
                </span>
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
            </ul>
        </li>
    @endcan

     @can('view environmental regulation detail')
        <li class="pc-item pc-hasmenu">
            <a href="#" class="pc-link">
                <span class="pc-micon"><i class="ti ti-social"></i></span><span class="pc-mtext"> Environmental
                    Regulation Detail
                </span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                @can('add environmental regulation detail')
                    <li class="pc-item"><a class="pc-link" href="{{ route('environmental-regulation-details.create') }}">Add New</a>
                    </li>
                @endcan
                @can('view environmental regulation detail')
                    <li class="pc-item"><a class="pc-link" href="{{ route('environmental-regulation-details.index') }}">All Details</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcan



</ul>
