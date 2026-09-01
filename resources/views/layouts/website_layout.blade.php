<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $siteSettings->site_name }}</title>
    <meta name="description" content="Official website of the Office of JS & CAO, Government of India" />
    <meta name="keywords" content="Government of India, JS & CAO, Chief Administrative Officer, Ministry of Defence" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('website/css/editor-styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('website/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('website/css/material.css') }}" />
    <link rel="stylesheet" href="{{ asset('website/css/accessibility.css') }}" />

    <!-- Favicon -->
    @if($siteSettings->favicon)
        <link rel="icon"
            href="{{ asset('storage' . Config::get('file_paths')['SITE_FAVICON_PATH'] . '/' . $siteSettings->favicon) }}"
            type="image/x-icon"> <!-- [Google Font] Family -->
    @endif
</head>

<body class="font-size-md">
    <!-- Skip to main content link for accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- [ Header Topbar ] start -->
    @include('includes.website.header')
    <!-- [ Header ] end -->

    @yield('content')

    <!-- [ Main Content ] end -->
    @include('includes.website.footer')


    <!-- Bootstrap JS Bundle with Popper -->
    <script @cspNonce src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script @cspNonce src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Custom JS -->
    <script @cspNonce src="{{asset('website/js/main.js')}}"></script>
    <script @cspNonce src="{{asset('website/js/accessibility.js')}}"></script>
    <script @cspNonce src="{{asset('website/js/material.js')}}"></script>

    @yield('pages-scripts')
</body>
<!-- [Body] end -->

</html>