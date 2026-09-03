@extends('layouts.auth_layout')

@section('content')
<style @cspNonce>
    .otp-input {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
        font-weight: bold;
        border: 2px solid #dee2e6;
        border-radius: 8px;
    }

    .otp-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .otp-input.filled {
        border-color: #198754;
        background-color: #f8f9fa;
    }

    .btn-disabled-countdown {
        position: relative;
        overflow: hidden;
    }

    .btn-disabled-countdown:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">

                {{-- ============================== --}}
                {{-- Login Card --}}
                {{-- ============================== --}}
                <div class="card mb-0" id="loginCard">
                    <div class="card-body">

                        <div class="text-center">
                            <img src="{{ asset('storage/' . Config::get('file_paths')['SITE_ADMIN_PANEL_LOGO_PATH'] . '/' . $siteSettings->admin_panel_logo) }}"
                                alt="{{ $siteSettings->site_name }}" class="w-25">
                        </div>

                        <hr>

                        <div class="mb-3">
                            <h1 class="mb-0 auth-login-title">
                                <b>Login Here</b>
                            </h1>

                            <p class="text-secondary">
                                Enter your credentials to login
                            </p>
                        </div>

                        <form action="" id="loginForm" autocomplete="off">
                            @csrf

                            {{-- Fake fields to stop browser autofill --}}
                            <input type="text" style="display:none">
                            <input type="password" style="display:none">

                            <div class="form-group mb-3">
                                <label class="form-label" for="email">
                                    Email Address or Employee Code
                                </label>

                                <input type="text"
                                    name="email"
                                    id="email"
                                    class="form-control"
                                    placeholder="Email Address or Employee Code"
                                    autocomplete="new-password"
                                    autocorrect="off"
                                    autocapitalize="off"
                                    spellcheck="false">
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label" for="password">
                                    Password
                                </label>

                                <div class="input-group">
                                    <input type="password"
                                        name="password"
                                        id="password"
                                        class="form-control"
                                        placeholder="Password"
                                        autocomplete="new-password"
                                        autocorrect="off"
                                        autocapitalize="off"
                                        spellcheck="false">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                @php
                                    $captchaData = app('mky-captcha')->refresh();
                                @endphp

                                @include('mky-captcha::captcha', [
                                    'image' => $captchaData['image'],
                                    'audio' => $captchaData['audio'],
                                    'audioEnabled' => true,
                                    'id' => 'login_form',
                                ])
                            </div>

                            <div class="col-12">
                                <label for="captcha" class="form-label">
                                    Captcha
                                </label>

                                <input type="text"
                                    class="form-control"
                                    maxlength="6"
                                    id="captcha"
                                    name="captcha"
                                    autocomplete="off" />
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary" id="loginBtn">
                                    Login
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                {{-- ============================== --}}
                {{-- OTP Verification Card --}}
                {{-- ============================== --}}
                <div class="card border-0 elevation-4 rounded-3 overflow-hidden" id="otpCard" style="display: none;">
                    <div class="card-header bg-danger py-3">
                        <h3 class="h6 mb-0 text-center text-uppercase text-white">
                            <i class="fa fa-shield-alt"></i> Two-Factor Authentication
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                <strong>Security Check</strong><br>
                                We've sent a 6-digit OTP to your registered email and mobile number for security.
                            </div>
                            <div id="contactInfo" class="small text-muted mb-3"></div>
                        </div>

                        <form action="" id="otpForm">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="form-label text-center d-block">Enter 6-Digit OTP</label>
                                <div class="otp-input-container d-flex justify-content-center gap-2 mb-3">
                                    <input type="text" class="form-control otp-input text-center" maxlength="1" data-index="0" aria-label="OTP digit 1">
                                    <input type="text" class="form-control otp-input text-center" maxlength="1" data-index="1" aria-label="OTP digit 2">
                                    <input type="text" class="form-control otp-input text-center" maxlength="1" data-index="2" aria-label="OTP digit 3">
                                    <input type="text" class="form-control otp-input text-center" maxlength="1" data-index="3" aria-label="OTP digit 4">
                                    <input type="text" class="form-control otp-input text-center" maxlength="1" data-index="4" aria-label="OTP digit 5">
                                    <input type="text" class="form-control otp-input text-center" maxlength="1" data-index="5" aria-label="OTP digit 6">
                                </div>
                                <input type="hidden" name="otp" id="otpValue">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success fw-semibold">
                                    <i class="fa fa-check"></i> Verify OTP
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-disabled-countdown" id="resendOtpBtn">
                                    <i class="fa fa-refresh"></i> <span id="resendBtnText">Resend OTP</span>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="backToLoginBtn">
                                    <i class="fa fa-arrow-left"></i> Back to Login
                                </button>
                            </div>
                        </form>

                        <!-- OTP Timer -->
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                OTP expires in: <span id="otpTimer" class="fw-bold text-danger">10:00</span>
                            </small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
<script @cspNonce>

    let otpTimer;
    let otpTimeRemaining = 600; // 10 minutes in seconds
    let resendTimer;
    let resendTimeRemaining = 0;

    // =========================
    // Disable copy/paste/cut
    // =========================
    $(document).ready(function () {

        $('#email, #password').on('copy paste cut', function (e) {
            e.preventDefault();
        });

        $('#email, #password').on('drop dragover', function (e) {
            e.preventDefault();
        });

        // Clear autofilled values
        setTimeout(() => {
            $('#email').val('');
            $('#password').val('');
        }, 100);

        // Toggle Password Visibility
        $('#togglePassword').on('click', function () {
            const passwordInput = $('#password');
            const icon = $('#togglePasswordIcon');
            const isPassword = passwordInput.attr('type') === 'password';
            passwordInput.attr('type', isPassword ? 'text' : 'password');
            icon.toggleClass('fa-eye fa-eye-slash');
        });

    });

    // =========================
    // Encryption Key
    // =========================
    var encryptionKey = "{{ \App\Helpers\CustomHelper::setEncryptionKey() }}";

    // =========================
    // Form Validation - Login
    // =========================
    $("#loginForm").validate({

        errorClass: "text-danger validation-error",
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },

        rules: {
            email: {
                required: true,
            },

            password: {
                required: true,
            },

            captcha: {
                required: true
            }
        },

        submitHandler: async function(form, event) {

            event.preventDefault();
            performLogin();
        }
    });

    // =========================
    // Form Validation - OTP
    // =========================
    $("#otpForm").validate({
        rules: {
            otp: {
                required: true,
                minlength: 6,
                maxlength: 6
            }
        },
        messages: {
            otp: {
                required: "Please enter the OTP",
                minlength: "OTP must be 6 digits",
                maxlength: "OTP must be 6 digits"
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            verifyOtp();
        }
    });

    // =========================
    // OTP Input Handling
    // =========================
    $(document).ready(function() {
        // OTP input handling
        $('.otp-input').on('input', function() {
            let value = $(this).val();
            let index = parseInt($(this).data('index'));

            // Only allow numbers
            value = value.replace(/[^0-9]/g, '');
            $(this).val(value);

            if (value) {
                $(this).addClass('filled');
                // Move to next input
                if (index < 5) {
                    $('.otp-input[data-index="' + (index + 1) + '"]').focus();
                }
            } else {
                $(this).removeClass('filled');
            }

            updateOtpValue();
        });

        $('.otp-input').on('keydown', function(e) {
            let index = parseInt($(this).data('index'));

            // Handle backspace
            if (e.keyCode === 8 && !$(this).val() && index > 0) {
                $('.otp-input[data-index="' + (index - 1) + '"]').focus();
            }
        });

        // Handle paste into OTP inputs
        $('.otp-input').on('paste', function(e) {
            e.preventDefault();
            let pastedData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
            pastedData = pastedData.replace(/[^0-9]/g, '').substring(0, 6);

            if (pastedData.length > 0) {
                for (let i = 0; i < pastedData.length && i < 6; i++) {
                    let input = $('.otp-input[data-index="' + i + '"]');
                    input.val(pastedData[i]);
                    input.addClass('filled');
                }
                updateOtpValue();
                // Focus the next empty input or the last one
                let nextIndex = Math.min(pastedData.length, 5);
                $('.otp-input[data-index="' + nextIndex + '"]').focus();
            }
        });

        // Back to login button
        $('#backToLoginBtn').click(function() {
            showLoginForm();
        });

        // Resend OTP button
        $('#resendOtpBtn').click(function() {
            if (!$(this).prop('disabled')) {
                resendOtp();
            }
        });
    });

    // =========================
    // Login AJAX
    // =========================
    async function performLogin() {

        var formData = new FormData(document.getElementById('loginForm'));

        const password = await encryptPassword(
            formData.get('password'),
            encryptionKey
        );

        formData.set('password', password);

        $.ajax({

            url: "{{ route('login.check') }}",

            type: "POST",

            data: formData,

            dataType: "json",

            cache: false,

            processData: false,

            contentType: false,

            beforeSend: function() {
                showLoader();
                let btn = $('#loginBtn');
                btn.data('original-text', btn.html());
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...');
            },

            success: function(response) {

                hideLoader();

                if (response.key) {
                    encryptionKey = response.key;
                }

                if (response.status === "success") {

                    Swal.fire({
                        icon: "success",
                        title: "Login Successful",
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.redirect;
                    });

                } else if (response.status === "otp_required") {

                    showOtpForm();
                    Swal.fire({
                        icon: "success",
                        title: "OTP Sent",
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false
                    });

                } else if (response.status == 'validation_error') {

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: response.message
                    });

                } else if (response.status == 'concurrent_login') {

                    handleConcurrentLogin(response);

                } else if (response.status == 'error') {

                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message,
                    });

                } else {

                    Swal.fire({
                        icon: "error",
                        title: "Login Failed",
                        text: response.message
                    });

                }
            },

            error: function(xhr) {

                hideLoader();

                mkyReloadCaptcha();

                let errorMessage =
                    "An error occurred. Please try again.";

                if (xhr.responseJSON) {

                    if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    if (xhr.responseJSON.key) {
                        encryptionKey = xhr.responseJSON.key;
                    }
                }

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage
                });
            },

            complete: function() {
                let btn = $('#loginBtn');
                btn.prop('disabled', false).html(btn.data('original-text'));
            }
        });
    }

    // =========================
    // Verify OTP AJAX
    // =========================
    function verifyOtp() {
        const formData = new FormData(document.getElementById('otpForm'));
        $.ajax({
            url: "{{ route('login.verify-otp') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                showLoader();
            },
            success: function(response) {
                hideLoader();

                if (response.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Login Successful",
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.redirect;
                    });
                } else {
                    clearOtpInputs();
                    Swal.fire({
                        icon: "error",
                        title: "Verification Failed",
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                hideLoader();
                clearOtpInputs();
                let errorMessage = "An error occurred. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage
                });
            }
        });
    }

    // =========================
    // Resend OTP AJAX
    // =========================
    function resendOtp() {
        $.ajax({
            url: "{{ route('login.resend-otp') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            beforeSend: function() {
                showLoader();
            },
            success: function(response) {
                hideLoader();

                if (response.status === "success") {
                    clearOtpInputs();
                    resetOtpTimer();
                    startResendTimer();
                    Swal.fire({
                        icon: "success",
                        title: "OTP Resent",
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Failed to Resend",
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                hideLoader();
                let errorMessage = "Failed to resend OTP. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage
                });
            }
        });
    }

    // =========================
    // OTP Form Toggle
    // =========================
    function showOtpForm() {
        $('#loginCard').hide();
        $('#otpCard').show();
        $('.otp-input:first').focus();
        startOtpTimer();
        startResendTimer();
    }

    function showLoginForm() {
        $('#otpCard').hide();
        $('#loginCard').show();
        clearOtpInputs();
        stopOtpTimer();
        stopResendTimer();
        $("#loginForm")[0].reset();
        mkyReloadCaptcha();
    }

    // =========================
    // OTP Value Management
    // =========================
    function updateOtpValue() {
        let otp = '';
        $('.otp-input').each(function() {
            otp += $(this).val();
        });
        $('#otpValue').val(otp);
    }

    function clearOtpInputs() {
        $('.otp-input').val('').removeClass('filled');
        $('#otpValue').val('');
    }

    // =========================
    // OTP Expiry Timer (10 min)
    // =========================
    function startOtpTimer() {
        otpTimeRemaining = 600; // Reset to 10 minutes
        updateTimerDisplay();

        otpTimer = setInterval(function() {
            otpTimeRemaining--;
            updateTimerDisplay();

            if (otpTimeRemaining <= 0) {
                stopOtpTimer();
                Swal.fire({
                    icon: "warning",
                    title: "OTP Expired",
                    text: "Your OTP has expired. Please request a new one.",
                    confirmButtonText: "Resend OTP"
                }).then((result) => {
                    if (result.isConfirmed) {
                        resendOtp();
                    }
                });
            }
        }, 1000);
    }

    function stopOtpTimer() {
        if (otpTimer) {
            clearInterval(otpTimer);
        }
    }

    function resetOtpTimer() {
        stopOtpTimer();
        startOtpTimer();
    }

    function updateTimerDisplay() {
        let minutes = Math.floor(otpTimeRemaining / 60);
        let seconds = otpTimeRemaining % 60;
        $('#otpTimer').text(
            minutes.toString().padStart(2, '0') + ':' +
            seconds.toString().padStart(2, '0')
        );
    }

    // =========================
    // Resend Timer (2 min cooldown)
    // =========================
    function startResendTimer() {
        resendTimeRemaining = 120; // 2 minutes in seconds
        updateResendTimerDisplay();

        // Disable the resend button
        $('#resendOtpBtn').prop('disabled', true);

        resendTimer = setInterval(function() {
            resendTimeRemaining--;
            updateResendTimerDisplay();

            if (resendTimeRemaining <= 0) {
                stopResendTimer();
                enableResendButton();
            }
        }, 1000);
    }

    function stopResendTimer() {
        if (resendTimer) {
            clearInterval(resendTimer);
            resendTimer = null;
        }
    }

    function updateResendTimerDisplay() {
        if (resendTimeRemaining > 0) {
            let minutes = Math.floor(resendTimeRemaining / 60);
            let seconds = resendTimeRemaining % 60;
            let timeString = minutes > 0 ?
                `${minutes}:${seconds.toString().padStart(2, '0')}` :
                `${seconds}s`;
            $('#resendBtnText').text(`Resend OTP (${timeString})`);
        } else {
            $('#resendBtnText').text('Resend OTP');
        }
    }

    function enableResendButton() {
        $('#resendOtpBtn').prop('disabled', false);
        $('#resendBtnText').text('Resend OTP');
    }

    // =========================
    // Disable Right Click
    // =========================
    document.addEventListener("contextmenu", function(e) {
        e.preventDefault();
    });

    // =========================
    // Disable Ctrl+U
    // =========================
    document.addEventListener("keydown", function(e) {

        if (e.ctrlKey && e.key.toLowerCase() === 'u') {
            e.preventDefault();
        }

    });

    // =========================
    // Disable Inspect
    // =========================
    document.addEventListener("keydown", function(e) {

        if (
            (e.ctrlKey &&
            e.shiftKey &&
            e.key.toLowerCase() === 'i') ||
            e.key === "F12"
        ) {
            e.preventDefault();
        }

    });

    // =========================
    // Concurrent Login
    // =========================
    function handleConcurrentLogin(response) {

        Swal.fire({

            icon: 'warning',

            title: 'Already Logged In',

            html: `${response.message}`,

            showCancelButton: true,

            confirmButtonText: 'Yes, Logout from Other Device',

            cancelButtonText: 'Cancel',

            confirmButtonColor: '#d33',

            cancelButtonColor: '#3085d6',

            reverseButtons: true

        }).then((result) => {

            if (result.isConfirmed) {

                window.location.href = response.logout_url;

            } else {

                mkyReloadCaptcha();

            }

        });
    }

    // =========================
    // Reload Captcha
    // =========================
    function mkyReloadCaptcha() {
        $('.mky-captcha-refresh').click();
    }

</script>
@endsection
