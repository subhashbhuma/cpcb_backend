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
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row g-4">
            @include('includes.website.left-sidebar')

            <!-- Main Content -->
            <div class="col-lg-9">
                <table class="table table-hover mb-0">
                    <caption class="sr-only">
                        Senior Officials Contact Information
                    </caption>
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="px-4 py-3" width="30%">Name and Designation</th>
                            <th scope="col" class="px-4 py-3" width="20%">Contact</th>
                            <th scope="col" class="px-4 py-3">Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($whoIsWhos as $whoIsWho)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-medium text-dark">{{ getLocalizedDataFromObj($whoIsWho, 'name') }}</div>
                                <div class="text-muted small">
                                    {{ getLocalizedDataFromObj($whoIsWho, 'designation') }}
                                </div>
                                @if (getLocalizedDataFromObj($whoIsWho->division, 'name'))
                                <div class="text-muted small mt-1">
                                    <strong>Division:</strong> {{ getLocalizedDataFromObj($whoIsWho->division, 'name') }}
                                </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if (getLocalizedDataFromObj($whoIsWho, 'mobile_number'))
                                <div class="text-dark d-flex align-items-center mb-1">
                                    <i
                                        class="fas fa-phone text-primary me-2"
                                        aria-hidden="true"></i>
                                    <span>
                                        {{ getLocalizedDataFromObj($whoIsWho, 'mobile_number') }}
                                    </span>
                                </div>
                                @endif

                                @if (getLocalizedDataFromObj($whoIsWho, 'email_id'))
                                <div class="text-dark d-flex align-items-center">
                                    <i
                                        class="fas fa-envelope text-primary me-2"
                                        aria-hidden="true"></i>
                                    <span>
                                        {{ getLocalizedDataFromObj($whoIsWho, 'email_id') }}
                                    </span>
                                </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-dark">
                                    {{ getLocalizedDataFromObj($whoIsWho, 'address') }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection