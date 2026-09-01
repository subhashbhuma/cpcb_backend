@once
    <link rel="stylesheet" href="{{ asset('vendor/mky-captcha/css/mky-captcha.css') }}">
@endonce

<div class="mky-captcha-container" id="mky-captcha-{{ $id ?? 'default' }}">
    <div class="mky-captcha-image-wrapper">
        <img src="{{ $image }}" alt="CAPTCHA" class="mky-captcha-image" id="mky-captcha-img-{{ $id ?? 'default' }}">
        <button type="button" class="mky-captcha-refresh" data-captcha-id="{{ $id ?? 'default' }}"
            title="Refresh CAPTCHA">
            ↻
        </button>
    </div>

    @if($audioEnabled ?? config('mky-captcha.audio_enabled', true))
        <div class="mky-captcha-audio-wrapper">
            <button type="button" class="mky-captcha-audio-btn" data-captcha-id="{{ $id ?? 'default' }}"
                title="Play Audio CAPTCHA">
                🔊
            </button>
        </div>
    @endif

    <input type="hidden" class="mky-captcha-audio-data" id="mky-captcha-audio-{{ $id ?? 'default' }}"
        value="{{ json_encode($audio ?? []) }}" data-captcha-id="{{ $id ?? 'default' }}">

    <input type="hidden" data-mky-captcha-refresh-url="{{ route('mky-captcha.refresh') }}">
</div>

@once
    <script @cspNonce src="https://cdn.jsdelivr.net/npm/axios@1.16.0/dist/axios.min.js"></script>
    <!-- <script @cspNonce src="{{ asset('vendor/mky-captcha/js/axios.min.js') }}"></script> -->
    <script @cspNonce src="{{ asset('vendor/mky-captcha/js/mky-captcha.js') }}"></script>
@endonce