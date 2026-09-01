@extends('layouts.website_layout')

@section('content')
<main id="main-content">
    <!-- Main Carousel -->
    <section class="carousel-section">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach ($sliders as $key => $slider)
                <button
                    type="button"
                    data-bs-target="#mainCarousel"
                    data-bs-slide-to="{{ $key }}"
                    class="active rounded-circle"
                    aria-current="true"
                    aria-label="Slide {{ $key+1 }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner rounded-3 elevation-4">
                @foreach ($sliders as $key => $slider)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <img
                        src="{{ $slider->file_url }}"
                        class="d-block w-100"
                        alt="{{ getLocalizedDataFromObj($slider, 'title') }}" />
                </div>
                @endforeach
            </div>
            <button
                class="carousel-control-prev"
                type="button"
                data-bs-target="#mainCarousel"
                data-bs-slide="prev">
                <span
                    class="carousel-control-prev-icon bg-primary bg-opacity-50 rounded-circle p-3 elevation-2"
                    aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button
                class="carousel-control-next"
                type="button"
                data-bs-target="#mainCarousel"
                data-bs-slide="next">
                <span
                    class="carousel-control-next-icon bg-primary bg-opacity-50 rounded-circle p-3 elevation-2"
                    aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Announcements Ticker -->
    <section
        class="announcements-ticker bg-light border-top border-bottom py-2 elevation-1">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="bg-primary text-white px-3 py-1 rounded-pill me-3 elevation-1 d-flex align-items-center gap-1">
                    <i class="fa fa-bell"></i>
                    <span class="fw-medium">Announcements</span>
                </div>
                <div class="ticker-wrapper flex-grow-1 overflow-hidden">
                    <marquee behavior="scroll" direction="left" scrollamount="6">
                        @if ($announcements->count() > 0)
                        @foreach ($announcements as $announcement)
                        <a href="{{ $announcement->file_or_link == 'file'? getLocalizedDataFromObj($announcement, 'file_url') : $announcement->page_link }}" target="_blank" class="text-decoration-none text-dark">
                            <span class="me-4">
                                <i class="fa fa-circle text-danger"></i> {{ getLocalizedDataFromObj($announcement, 'title') }}
                            </span>
                        </a>
                        @endforeach
                        @else
                        <span class="me-4">No announcements available at the moment.</span>
                        @endif
                    </marquee>
                </div>
                <button
                    class="btn btn-sm btn-outline-primary rounded-circle ms-2 ticker-control elevation-1 ripple-effect btn-only-icon-square"
                    aria-label="Pause announcements">
                    <i class="fas fa-pause"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- About Section with 3 columns -->
    <section class="py-5 bg-primary">
        <div class="container">
            <div class="row g-4">
                <!-- About Us Column -->
                <div class="col-lg-4">
                    <div
                        class="card border-0 elevation-4 rounded-3 h-100 hover-elevation-5 ripple-effect">
                        <div class="card-body p-4">
                            @if($homeAbout && $homeAbout->image)
                            <div
                                class="position-relative rounded-3 overflow-hidden elevation-2 mb-4"
                                style="height: 200px">
                                <img
                                    src="{{ $homeAbout->image_path }}"
                                    alt="Image of {{ getLocalizedDataFromObj($homeAbout, 'title') }}"
                                    class="img-fluid w-100 h-100 object-fit-cover hover-scale" />
                            </div>
                            @endif
                            <h4 class="text-primary mb-3">
                                {{ getLocalizedDataFromObj($homeAbout, 'title') }}
                            </h4>
                            <p class="text-muted mb-4">
                                {{ getLocalizedDataFromObj($homeAbout, 'description') }}
                            </p>
                            @if($homeAbout)
                            <a
                                href="{{ $homeAbout->button_link }}"
                                class="btn btn-primary rounded-pill elevation-2 ripple-effect">
                                <i class="fas fa-info-circle me-2"></i> Learn More
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Social Media Column -->
                <div class="col-lg-4">
                    <div
                        class="card border-0 elevation-4 rounded-3 h-100 hover-elevation-5 ripple-effect">
                        <div class="card-body p-4">
                            <div
                                class="position-relative rounded-3 overflow-hidden elevation-2 mb-4"
                                style="height: 120px">
                                <img
                                    src="{{ asset('website/assets/images/social-banner.jpg') }}"
                                    alt="Social Media"
                                    class="img-fluid w-100 h-100 object-fit-cover" />
                                <div
                                    class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50">
                                    <p class="text-white mb-0">Follow us on social media</p>
                                </div>
                            </div>

                            <div>
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    @foreach($socialMedias as $key => $socialMedia)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $key == 0 ? 'active': '' }}" id="pills-{{ $key+1 }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $key+1 }}" type="button" role="tab" aria-controls="pills-{{ $key+1 }}" aria-selected="true">
                                            <i class="{{ $socialMedia->icon_class }}"></i>
                                            <span class="sr-only">{{ $socialMedia->name }}</span>
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content social-media-tab-content" id="pills-tabContent">
                                    @foreach($socialMedias as $key => $socialMedia)
                                    <div class="tab-pane fade {{ $key == 0 ? 'show active': '' }}" id="pills-{{ $key+1 }}" role="tabpanel" aria-labelledby="pills-{{ $key+1 }}-tab" tabindex="0">
                                        <div class="p-3 border rounded-3 elevation-2 hover-elevation-3 ripple-effect">
                                            {{ $socialMedia->embed_code }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hon'ble Leaders Column -->
                <div class="col-lg-4">
                    <div
                        class="card border-0 elevation-4 rounded-3 h-100 hover-elevation-5 ripple-effect">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column gap-4">
                                @foreach ($whoIsWhos as $whoIsWho)
                                <div class="text-center">
                                    <div
                                        class="position-relative mx-auto mb-3 rounded-3 overflow-hidden">
                                        <img
                                            src="{{ $whoIsWho->image_full_path }}"
                                            alt="{{ getLocalizedDataFromObj($whoIsWho, 'name') }}"
                                            class="img-fluid object-fit-cover hover-scale elevation-2 rounded-3"
                                            style="height: 150px; object-fit: contain" />
                                    </div>
                                    <h4 class="h5 text-primary">{{ getLocalizedDataFromObj($whoIsWho, 'name') }}</h4>
                                    <p class="text-muted mb-0">
                                        {{ getLocalizedDataFromObj($whoIsWho, 'designation') }}
                                    </p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Information Center -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-4">
                <span
                    class="badge bg-primary text-white rounded-pill px-3 py-2 mb-1 elevation-1">Stay Updated</span>
                <h2 class="display-6 fw-bold text-primary mb-1">
                    Information Center
                </h2>
                <p class="text-muted">
                    Access the latest updates, notifications, tenders, and events from
                    the Office of JS & CAO
                </p>
            </div>

            <div class="card elevation-4 rounded-3 mb-0 overflow-hidden">
                <div class="card-header p-0">
                    <ul
                        class="nav nav-tabs nav-fill border-0"
                        id="infoTabs"
                        role="tablist">
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link active py-3 border-0 rounded-0 bg-warning bg-opacity-75 text-dark ripple-effect"
                                id="whats-new-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#whats-new"
                                type="button"
                                role="tab"
                                aria-controls="whats-new"
                                aria-selected="true">
                                <i class="fas fa-newspaper me-2"></i> What's New
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link py-3 border-0 rounded-0 bg-primary text-white ripple-effect"
                                id="circulars-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#circulars"
                                type="button"
                                role="tab"
                                aria-controls="circulars"
                                aria-selected="false">
                                <i class="fas fa-file-alt me-2"></i> Circulars/Notifications
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link py-3 border-0 rounded-0 bg-primary text-white ripple-effect"
                                id="tenders-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#tenders"
                                type="button"
                                role="tab"
                                aria-controls="tenders"
                                aria-selected="false">
                                <i class="fas fa-file-contract me-2"></i> Tenders
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link py-3 border-0 rounded-0 bg-primary text-white ripple-effect"
                                id="events-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#events"
                                type="button"
                                role="tab"
                                aria-controls="events"
                                aria-selected="false">
                                <i class="fas fa-calendar-alt me-2"></i> Events
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-0">
                    <div class="tab-content" id="infoTabsContent">
                        <!-- What's New Tab -->
                        <div
                            class="tab-pane fade show active"
                            id="whats-new"
                            role="tabpanel"
                            aria-labelledby="whats-new-tab">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <caption class="sr-only">
                                        Latest Updates and News
                                    </caption>
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col" class="px-4 py-3" width="20%">Type</th>
                                            <th scope="col" class="px-4 py-3" width="50%">Title</th>
                                            <th scope="col" class="px-4 py-3 text-nowrap">
                                                Date
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-nowrap" width="12%">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($whatsNew) > 0)
                                        @foreach ($whatsNew as $update)
                                        <tr class="align-middle">
                                            <td class="px-4 py-3">
                                                @if($update->type == 'tender')
                                                <i class="fas fa-bullhorn me-2"></i> Tender
                                                @endif

                                                @if($update->type == 'event')
                                                <i class="fas fa-calendar-alt me-2"></i> Event
                                                @endif

                                                @if($update->type == 'circular')
                                                <i class="fas fa-file-alt me-2"></i> {{ getLocalizedDataFromObj($update->circularCategory, 'name') }}
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div>
                                                    <h4 class="h6 text-primary mb-1">
                                                        {{ getLocalizedDataFromObj($update, 'title') }}
                                                    </h4>
                                                    <div class="text-muted mb-0 small">
                                                        @if ($update->type == 'circular')
                                                        {!! getLocalizedDataFromObj($update, 'description') !!}
                                                        @endif
                                                        @if ($update->type == 'event')
                                                        {!! getLocalizedDataFromObj($update, 'brief_summary') !!}
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($update->type == 'tender')
                                                {{ $update->publish_date ? $update->publish_date->format('d/m/Y') : '—' }}
                                                @endif

                                                @if($update->type == 'event')
                                                {{ date('d/m/Y', strtotime($update->date)) }}
                                                @endif

                                                @if($update->type == 'circular')
                                                {{ date('d/m/Y', strtotime($update->published_date)) }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @if($update->type == 'tender')
                                                    <a
                                                        href=" {{ getLocalizedDataFromObj($update, 'file_url') }}"
                                                        class="btn btn-sm btn-primary rounded-pill elevation-1 ripple-effect" target="_BLANK">
                                                        <i
                                                            class="fas fa-download me-1"
                                                            aria-hidden="true"></i>
                                                        PDF
                                                    </a>
                                                    @endif

                                                    @if($update->type == 'event')
                                                    <a
                                                        href="#"
                                                        class="btn btn-sm btn-primary rounded-pill elevation-1 ripple-effect" target="_BLANK">
                                                        <i
                                                            class="fas fa-eye me-1"
                                                            aria-hidden="true"></i>
                                                        View
                                                    </a>
                                                    @endif

                                                    @if($update->type == 'circular')
                                                    <a
                                                        href=" {{ getLocalizedDataFromObj($update, 'file_url') }}"
                                                        class="btn btn-sm btn-primary rounded-pill elevation-1 ripple-effect" target="_BLANK">
                                                        <i
                                                            class="fas fa-download me-1"
                                                            aria-hidden="true"></i>
                                                        PDF
                                                    </a>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="align-middle">
                                            <td class="px-4 py-3" colspan="3">
                                                No updates found
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end align-items-center p-4 border-top">
                                <a
                                    href="/whats-new"
                                    class="btn btn-primary rounded-pill elevation-2 ripple-effect">
                                    <i class="fas fa-list me-2"></i> View All Updates
                                </a>
                            </div>
                        </div>

                        <!-- Circulars Tab -->
                        <div
                            class="tab-pane fade"
                            id="circulars"
                            role="tabpanel"
                            aria-labelledby="circulars-tab">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <caption class="sr-only">
                                        Latest Circulars and Notifications
                                    </caption>
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col" class="px-4 py-3" width="20%">Type</th>
                                            <th scope="col" class="px-4 py-3">Title</th>
                                            <th scope="col" class="px-4 py-3 text-nowrap" width="20%">
                                                Date
                                            </th>
                                            <th width="10%" scope="col" class="px-4 py-3 text-nowrap">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($circulars as $circular)
                                        <tr class="align-middle">
                                            <td class="px-4 py-3">{{ getLocalizedDataFromObj($circular->circularCategory, 'name') }}</td>
                                            <td class="px-4 py-3">
                                                <div>
                                                    <h4 class="h6 text-primary mb-1">
                                                        {{ getLocalizedDataFromObj($circular, 'title') }}
                                                    </h4>
                                                    <p class="text-muted mb-0 small">
                                                        {{ getLocalizedDataFromObj($circular, 'description') }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-nowrap text-muted">
                                                <i class="fas fa-calendar-alt text-primary me-2" aria-hidden="true"></i>
                                                {{ date('d/m/Y', strtotime($circular->published_date)) }}
                                            </td>
                                            <td class="px-4 py-3 text-nowrap">
                                                <div class="d-flex gap-2">
                                                    <a
                                                        href=" {{ getLocalizedDataFromObj($circular, 'file_url') }}"
                                                        class="btn btn-sm btn-primary rounded-pill elevation-1 ripple-effect" target="_BLANK">
                                                        <i
                                                            class="fas fa-download me-1"
                                                            aria-hidden="true"></i>
                                                        PDF
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div
                                class="d-flex justify-content-end align-items-center p-4 border-top">
                                <a
                                    href="{{ url()->to('/notifications-circulars/vacancy-circular') }}"
                                    class="btn btn-primary rounded-pill elevation-2 ripple-effect">
                                    <i class="fas fa-list me-2"></i> View All Circulars
                                </a>
                            </div>
                        </div>

                        <!-- Tenders Tab -->
                        <div
                            class="tab-pane fade"
                            id="tenders"
                            role="tabpanel"
                            aria-labelledby="tenders-tab">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <caption class="sr-only">
                                        Active Tenders
                                    </caption>
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col" class="px-4 py-3">Division</th>
                                            <th scope="col" class="px-4 py-3">Title</th>
                                            <th scope="col" class="px-4 py-3 text-nowrap">
                                                Published Date
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-nowrap">
                                                Start & End Date
                                            </th>
                                            <th width="10%" scope="col" class="px-4 py-3 text-nowrap">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tenders as $tender)
                                        <tr class="align-middle">
                                            <td class="px-4 py-3">
                                                {{ getLocalizedDataFromObj($tender->division, 'title') ?? '—' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div>
                                                    <h4 class="h6 text-primary mb-1">
                                                        {{ getLocalizedDataFromObj($tender, 'title') }}
                                                    </h4>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-nowrap text-muted">
                                                <i class="fas fa-calendar-alt text-primary me-2" aria-hidden="true"></i>
                                                {{ $tender->publish_date ? $tender->publish_date->format('d/m/Y') : '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-nowrap text-muted">
                                                <i class="fas fa-calendar-alt text-primary me-2" aria-hidden="true"></i>
                                                {{ $tender->start_date ? $tender->start_date->format('d/m/Y') : '—' }} - {{ $tender->end_date ? $tender->end_date->format('d/m/Y') : '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-nowrap">
                                                <div class="d-flex gap-2">
                                                    <a
                                                        href=" {{ getLocalizedDataFromObj($tender, 'file_url') }}"
                                                        class="btn btn-sm btn-primary rounded-pill elevation-1 ripple-effect" target="_BLANK">
                                                        <i
                                                            class="fas fa-download me-1"
                                                            aria-hidden="true"></i>
                                                        PDF
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div
                                class="d-flex justify-content-end align-items-center p-4 border-top">
                                <a
                                    href="{{ url()->to('/notifications-circulars/tenders') }}"
                                    class="btn btn-primary rounded-pill elevation-2 ripple-effect">
                                    <i class="fas fa-list me-2"></i> View All Tenders
                                </a>
                            </div>
                        </div>

                        <!-- Events Tab -->
                        <div
                            class="tab-pane fade"
                            id="events"
                            role="tabpanel"
                            aria-labelledby="events-tab">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <caption class="sr-only">
                                        Upcoming Events
                                    </caption>
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col" class="px-4 py-3">Title</th>
                                            <th scope="col" class="px-4 py-3 text-nowrap">
                                                Date & Time
                                            </th>
                                            <th scope="col" class="px-4 py-3">Venue</th>
                                            <th width="10%" scope="col" class="px-4 py-3 text-nowrap">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($events as $event)
                                        <tr class="align-middle">
                                            <td class="px-4 py-3">
                                                <div>
                                                    <h4 class="h6 text-primary mb-1">
                                                        {{ getLocalizedDataFromObj($event, 'title') }}
                                                    </h4>
                                                    <p class="text-muted mb-0 small">
                                                        {{ getLocalizedDataFromObj($event, 'brief_summary') }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-nowrap text-muted">
                                                <i class="fas fa-calendar-alt text-primary me-2" aria-hidden="true"></i>
                                                {{ date('d/m/Y', strtotime($event->date)) }} - {{ date('H:i A', strtotime($event->time)) }}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{$event->venue}}
                                            </td>
                                            <td class="px-4 py-3 text-nowrap">
                                                <div class="d-flex gap-2">
                                                    <a
                                                        href="#"
                                                        class="btn btn-sm btn-primary rounded-pill elevation-1 ripple-effect" target="_BLANK">
                                                        <i
                                                            class="fas fa-eye me-1"
                                                            aria-hidden="true"></i>
                                                        View
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end align-items-center p-4 border-top">
                                <a
                                    href="{{url()->to('/events-all/events')}}"
                                    class="btn btn-primary rounded-pill elevation-2 ripple-effect">
                                    <i class="fas fa-list me-2"></i> View All Events
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Media Gallery and Quick Links -->
    <section class="pb-5 pt-4 bg-light">
        <div class="container">
            <div class="row g-4">
                <!-- Media Gallery -->
                <div class="col-lg-8">
                    <div
                        class="card border-0 elevation-4 rounded-3 hover-elevation-5 h-100">
                        <div class="card-header bg-primary text-white rounded-top-3">
                            <div
                                class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0 d-flex align-items-center">
                                    <i class="fas fa-camera me-2"></i> Media Gallery
                                </h3>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <ul
                                class="nav nav-tabs nav-fill"
                                id="mediaTab"
                                role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link text-dark active rounded-0 ripple-effect"
                                        id="photos-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#photos"
                                        type="button"
                                        role="tab"
                                        aria-controls="photos"
                                        aria-selected="true">
                                        <i class="fas fa-image me-1"></i> Photos
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link rounded-0 ripple-effect text-dark"
                                        id="videos-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#videos"
                                        type="button"
                                        role="tab"
                                        aria-controls="videos"
                                        aria-selected="false">
                                        <i class="fas fa-video me-1"></i> Videos
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content p-4" id="mediaTabContent">
                                <!-- Photos Tab -->
                                <div
                                    class="tab-pane fade show active"
                                    id="photos"
                                    role="tabpanel"
                                    aria-labelledby="photos-tab">
                                    @if ($photoGalleries ->count() > 0)
                                    <div class="row g-3">
                                        @foreach ($photoGalleries as $photoGallery)
                                        <div class="col-6 col-md-3">
                                            <a
                                                href="#"
                                                class="gallery-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#photoModal{{ $photoGallery->id }}">
                                                <div
                                                    class="position-relative overflow-hidden rounded-3 elevation-2 hover-elevation-3">
                                                    <img
                                                        src="{{ $photoGallery->featured_image_full_path }}"
                                                        alt="{{ getLocalizedDataFromObj($photoGallery, 'title') }}"
                                                        class="img-fluid w-100 gallery-img" />
                                                    <div
                                                        class="gallery-overlay d-flex align-items-end">
                                                        <div class="p-2 text-white">
                                                            <p class="small mb-0">
                                                                {{ getLocalizedDataFromObj($photoGallery, 'title') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="gallery-action">
                                                        <button
                                                            class="btn btn-sm btn-primary rounded-circle elevation-2 ripple-effect">
                                                            <i class="fas fa-search-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="alert alert-danger">
                                        <strong>No photos found!</strong>
                                    </div>
                                    @endif

                                    <div class="text-center mt-4">
                                        <a
                                            href="/media-gallery/photos"
                                            class="btn btn-primary rounded-pill elevation-2 ripple-effect">
                                            <i class="fas fa-images me-2"></i> Browse All Photos
                                        </a>
                                    </div>
                                </div>

                                <!-- Videos Tab -->
                                <div
                                    class="tab-pane fade"
                                    id="videos"
                                    role="tabpanel"
                                    aria-labelledby="videos-tab">
                                    @if ($videoGalleries->count() > 0)
                                    <div class="row g-3">
                                        @foreach ($videoGalleries as $videoGallery)
                                        <div class="col-6 col-md-3">
                                            <a
                                                href="#"
                                                class="video-item"
                                                data-bs-toggle="modal"
                                                data-bs-target="#videoModal{{ $videoGallery->id }}">
                                                <div
                                                    class="position-relative overflow-hidden rounded-3 elevation-2 hover-elevation-3">
                                                    <img
                                                        src="{{ $videoGallery->thumbnail_image_full_path }}"
                                                        alt="{{ getLocalizedDataFromObj($videoGallery, 'title') }}"
                                                        class="img-fluid w-100" />
                                                    <div
                                                        class="video-play-button elevation-3 ripple-effect">
                                                        <i class="fas fa-play"></i>
                                                    </div>
                                                    <div class="video-duration">5:30</div>
                                                    <div class="video-overlay d-flex align-items-end">
                                                        <div class="p-2 text-white">
                                                            <p class="small mb-0">
                                                                {{ getLocalizedDataFromObj($videoGallery, 'title') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="alert alert-danger">
                                        <strong>No videos found!</strong>
                                    </div>
                                    @endif

                                    <div class="text-center mt-4">
                                        <a
                                            href="/media-gallery/videos"
                                            class="btn btn-primary rounded-pill elevation-2 ripple-effect">
                                            <i class="fas fa-video me-2"></i> Browse All Videos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-4">
                    <div
                        class="card border-0 elevation-4 rounded-3 hover-elevation-5 h-100">
                        <div class="card-header bg-primary text-white rounded-top-3">
                            <h3 class="card-title mb-0">Quick Links</h3>
                        </div>
                        <div class="card-body p-4">
                            @if ($quickLinks->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach ($quickLinks as $quickLink)
                                <li class="list-group item px-0 py-3 border-bottom">
                                    <a
                                        href="{{ $quickLink->link }}"
                                        class="text-decoration-none d-flex align-items-center ripple-effect">
                                        <div
                                            class="bg-primary text-white rounded-circle p-2 me-3 elevation-2">
                                            <i class="{{ $quickLink->icon }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium text-primary">{{ $quickLink->title }}</div>
                                            <small class="text-muted">{{ $quickLink->description }}</small>
                                        </div>
                                        <i
                                            class="fas fa-chevron-right ms-auto text-primary"></i>
                                    </a>
                                </li>
                                @endforeach
                            </ul>

                            <div class="mt-4">
                                <a
                                    href="/quick-links"
                                    class="btn btn-primary w-100 rounded-pill elevation-2 ripple-effect">
                                    <i class="fas fa-link me-2"></i> View All Quick Links
                                </a>
                            </div>
                            @else
                            <div class="alert alert-danger">
                                <strong>No quick links found!</strong>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Government Portals Slider -->
    <section class="py-5 bg-white">
        <div class="container">
            <!-- <h3 class="mb-4 text-primary">Government Portals</h3> -->
            <div class="position-relative">
                <div class="portal-slider">
                    <div
                        class="portal-slider-container d-flex flex-nowrap overflow-hidden">
                        @if ($governmentPortals->count() > 0)
                        @foreach ($governmentPortals as $governmentPortal)
                        <div class="portal-slide px-2">
                            <a
                                href="{{ $governmentPortal->link }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="portal-link">
                                <div
                                    class="card h-100 elevation-2 hover-elevation-3 rounded-3 ripple-effect">
                                    <div class="card-body text-center p-3">
                                        <img
                                            src="{{ $governmentPortal->file_name_full_path }}"
                                            alt="{{ getLocalizedDataFromObj($governmentPortal, 'title') }}"
                                            class="img-fluid mb-2"
                                            style="height: 100px; object-fit: contain" />
                                        <div
                                            class="small fw-medium d-flex align-items-center justify-content-center text-primary">
                                            {{ getLocalizedDataFromObj($governmentPortal, 'title') }}
                                            <i class="fas fa-external-link-alt ms-1 small"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>

                <button
                    class="btn btn-primary rounded-circle position-absolute top-50 start-0 translate-middle-y portal-prev elevation-2 ripple-effect"
                    aria-label="Previous portals">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <button
                    class="btn btn-primary rounded-circle position-absolute top-50 end-0 translate-middle-y portal-next elevation-2 ripple-effect"
                    aria-label="Next portals">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>
</main>
@endsection
