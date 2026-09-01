@extends('layouts.website_layout')

@section('content')
<main id="main-content">
    <!-- Hero Section -->
    <div class="position-relative bg-primary text-white overflow-hidden">
        <div class="container py-5 position-relative">
            <div>
                @if($parentPageTitle)
                <span class="badge bg-white text-primary mb-2">{{ $parentPageTitle }}</span>
                @endif
                <h3 class="display-6 fw-bold mb-1">
                    {{ $pageTitle }}
                </h3>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="input-group elevation-2">
                            <span class="input-group-text bg-white border-0">
                                <i class="fas fa-search text-muted"
                                    aria-hidden="true"></i>
                            </span>
                            <input
                                type="search"
                                class="form-control bg-white border-0 py-3"
                                placeholder="Search by keywords..."
                                id="circularSearch"
                                aria-label="Search circulars" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select
                            class="form-select bg-white border-0 py-3 elevation-2"
                            id="categoryFilter"
                            aria-label="Filter by category">
                            <option value="all">All Categories</option>
                            <option value="circular">Circulars</option>
                            <option value="notification">Notifications</option>
                            <option value="order">Office Orders</option>
                            <option value="memo">Memorandums</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button
                            class="btn btn-primary w-100 py-3 rounded-pill elevation-2 ripple-effect">
                            <i class="fas fa-filter me-2" aria-hidden="true"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row g-4">
            @include('includes.website.left-sidebar')

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="card elevation-4 rounded-3 mb-5">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap justify-content-start gap-3">
                                    <div class="d-flex align-items-center">
                                        <label for="sortBy" class="text-muted me-2 small">Sort by:</label>
                                        <select
                                            class="form-select form-select-sm"
                                            id="sortBy"
                                            style="width: 180px"
                                            aria-label="Sort circulars by">
                                            <option value="published-desc">
                                                Published Date (Newest)
                                            </option>
                                            <option value="published-asc">
                                                Published Date (Oldest)
                                            </option>
                                            <option value="title-asc">Title (A-Z)</option>
                                            <option value="title-desc">Title (Z-A)</option>
                                        </select>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <label for="showCount" class="text-muted me-2 small">Show:</label>
                                        <select
                                            class="form-select form-select-sm"
                                            id="showCount"
                                            style="width: 80px"
                                            aria-label="Number of circulars to show">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">

                        <div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <caption class="sr-only">
                                        All Circulars and Notifications
                                    </caption>
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col" class="px-4 py-3" width="40%">Title</th>
                                            <th scope="col" class="px-4 py-3 text-nowrap" width="12%">
                                                Category
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-nowrap" width="12%">
                                                Published Date
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-nowrap" width="12%">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if ($circulars->count() > 0)
                                        @foreach ($circulars as $circular)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div>
                                                    @if($circular->published_date > now()->subDays(15))
                                                    <div class="mt-1">
                                                        <span class="badge bg-primary text-white rounded-pill">New</span>
                                                    </div>
                                                    @endif
                                                    {{ getLocalizedDataFromObj($circular, 'title') }}
                                                    <div class="text-muted mb-0 small">
                                                        {!! getLocalizedDataFromObj($circular, 'description') !!}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-muted">
                                                {{ getLocalizedDataFromObj($circular->circularCategory, 'name') }}
                                            </td>
                                            <td class="px-4 py-3 text-nowrap text-muted">
                                                @if($circular->published_date)
                                                <i class="fas fa-calendar-alt text-primary me-2" aria-hidden="true"></i>
                                                {{ $circular->published_date->format('d M Y') }}
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-nowrap">
                                                <div class="d-flex gap-2">
                                                    <a
                                                        href="{{ getLocalizedDataFromObj($circular, 'file_url') }}"
                                                        class="btn btn-sm btn-primary rounded-pill elevation-1 ripple-effect">
                                                        <i
                                                            class="fas fa-download me-1"
                                                            aria-hidden="true"></i>
                                                        Download
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end align-items-center p-4 border-top">
                                <div class="pagination-wrapper">
                                    {{ $circulars->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection