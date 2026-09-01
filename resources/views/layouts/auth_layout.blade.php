<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>{{ $siteSettings->site_name }}</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <!-- [Favicon] icon -->
    @if ($siteSettings->favicon)
        <link rel="icon"
            href="{{ asset('storage/' . Config::get('file_paths')['SITE_FAVICON_PATH'] . '/' . $siteSettings->favicon) }}"
            type="image/x-icon"> <!-- [Google Font] Family -->
    @endif

    <!-- [Google Font] Family -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <!-- Toastr css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/toastr.min.css') }}">
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/sweetalert2.min.css') }}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-style.css') }}">

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    @yield('content')

    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    <script @cspNonce src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/toastr.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/sweetalert2.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/jquery.validate.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/pcoded.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script @cspNonce src="{{ asset('assets/js/plugins/cryptojs/crypto-js.min.js') }}"></script>

    <script @cspNonce>
        if (window.axios) {
            delete axios.VERSION;
        }

        if (window.bootstrap) {
            Object.keys(bootstrap).forEach(key => {
                if (bootstrap[key]?.VERSION) {
                    delete bootstrap[key].VERSION;
                }
            });
        }

        if ($?.fn) {
            $.fn.jquery = undefined;
        }

     if (window.bootstrap) {
        Object.keys(window.bootstrap).forEach(function (key) {

            if (window.bootstrap[key] &&
                window.bootstrap[key].VERSION) {

                try {
                    delete window.bootstrap[key].VERSION;
                } catch (e) {
                    window.bootstrap[key].VERSION = undefined;
                }
            }
        });
    }

        layout_change('light');
        change_box_container('false');
        layout_rtl_change('false');
        preset_change("preset-6");
        font_change("Public-Sans");

        function showLoader() {
            $(".loader").show();
        }

        function hideLoader() {
            $(".loader").hide();
        }

        async function encryptPassword(password, hexKey) {
            if (!hexKey) {
                console.error("Encryption key is not ready.");
                return null;
            }

            const keyBytes = new Uint8Array(hexKey.match(/.{1,2}/g).map(byte => parseInt(byte, 16)));
            const cryptoKey = await window.crypto.subtle.importKey(
                "raw", keyBytes, {
                    name: "AES-GCM"
                }, false, ["encrypt"]
            );

            const iv = window.crypto.getRandomValues(new Uint8Array(12));
            const encoder = new TextEncoder();
            const data = encoder.encode(password);

            const encryptedBuffer = await window.crypto.subtle.encrypt({
                    name: "AES-GCM",
                    iv: iv,
                    tagLength: 128
                },
                cryptoKey, data
            );

            const encryptedBytes = new Uint8Array(encryptedBuffer);
            const payload = new Uint8Array(12 + encryptedBytes.length);
            payload.set(iv, 0);
            payload.set(encryptedBytes, 12);

            let binary = '';
            for (let i = 0; i < payload.byteLength; i++) {
                binary += String.fromCharCode(payload[i]);
            }
            return btoa(binary);
        }
    </script>

    @yield('pages-scripts')

</body>
<!-- [Body] end -->

</html>
