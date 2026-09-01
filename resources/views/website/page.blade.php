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
                    <!-- Overview Section -->
                    <section id="overview" class="mb-5">
                        <div class="card shadow border-0">
                            <div class="card-header border-bottom">
                                <h5 class="card-title text-primary h5 text-uppercase d-flex align-items-center">
                                    <i class="fas fa-building me-2"></i>
                                    {{ $pageTitle }}
                                </h5>
                            </div>
                            @if (getLocalizedDataFromObj($pageDetails, 'content'))
                                <div class="card-body p-4">
                                    <div class="editor-content-wrapper ck-content">
                                        {!! getLocalizedDataFromObj($pageDetails, 'content') !!}
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($pageDetails->files && $pageDetails->files->count() > 0)
                            @foreach ($pageDetails->files as $file)
                                <div class="card shadow border-0 mt-3">

                                    @php
                                        $meta = getFileMeta(getLocalizedDataFromObj($file, 'file_path'));
                                    @endphp

                                    <div class="card-body d-flex justify-content-between align-items-center gap-4">
                                        <div class="flex-1">
                                            <div class="d-flex align-items-start gap-2">
                                                <i class="fas fa-chevron-right mt-1 text-sm"></i>
                                                <p class="m-0">
                                                    <strong>{{ getLocalizedDataFromObj($file, 'title') }}</strong>
                                                </p>
                                            </div>
                                            <div class="ps-3">
                                                <div role="region" aria-label="File Information">
                                                    <small class="text-danger" aria-live="polite">
                                                        <strong>Type:</strong> {{ $meta['extension'] }} |
                                                        <strong>Size:</strong>
                                                        {{ $meta['formatted_size'] }}
                                                    </small>
                                                </div>
                                                <p class="my-1">{{ getLocalizedDataFromObj($file, 'description') }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ getLocalizedDataFromObj($file, 'file_path') }}" target="_blank"
                                                class="btn btn-primary btn-sm d-flex align-items-center flex-row gap-2">
                                                <i class="fas fa-download" aria-hidden="true"></i>
                                                <span>Download</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('pages-scripts')
    <script @cspNonce>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-submenu').forEach(button => {
                button.addEventListener('click', function (e) {
                    const submenu = this.closest('.nav-item-wrapper').querySelector('.submenu');
                    submenu.classList.toggle('d-none');

                    // Optional: toggle icon from + to -
                    const icon = this.querySelector('i');
                    if (icon.classList.contains('fa-plus')) {
                        icon.classList.remove('fa-plus');
                        icon.classList.add('fa-minus');
                    } else {
                        icon.classList.remove('fa-minus');
                        icon.classList.add('fa-plus');
                    }

                    e.preventDefault();
                });
            });
        });
    </script>

@endsection