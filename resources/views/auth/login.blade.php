@extends('layouts.auth_layout')

@section('content')
    <style @cspNonce>
        /* Container & Card Styling */
        .auth-main .auth-wrapper .auth-form .auth-portal-card {
            width: 100%;
            max-width: 530px;
            border: 0;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.08), 0 4px 12px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .auth-top-accent {
            height: 5px;
            background: linear-gradient(90deg, #ff9933 0%, #0d6efd 50%, #198754 100%);
            width: 100%;
        }

        .auth-top-accent-otp {
            background: linear-gradient(90deg, #198754 0%, #0d6efd 100%);
        }

        .auth-otp-card {
            display: none;
        }

        .auth-hidden-field {
            display: none !important;
        }

        /* Branding Section */
        .auth-brand-logo {
            max-height: 70px;
            width: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .auth-brand-logo:hover {
            transform: scale(1.02);
        }

        .portal-gov-tag {
            display: inline-flex;
            align-items: center;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 20px;
            background: #eef2f6;
            color: #344767;
            border: 1px solid #dbe2ea;
        }

        .portal-header-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .portal-header-subtitle {
            font-size: 0.82rem;
            color: #64748b;
            margin-bottom: 0;
        }

        /* Tab Switcher (Segmented Control) */
        .auth-tab-nav {
            display: flex;
            background: #eef2f6;
            padding: 6px;
            border-radius: 14px;
            position: relative;
            border: 2px solid #e2e8f0;
            margin-bottom: 1.25rem;
            gap: 6px;
        }

        .auth-tab-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 14px;
            border: 2px solid transparent;
            background: #ffffff;
            color: #64748b;
            font-weight: 600;
            font-size: 0.92rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
            position: relative;
        }

        .auth-tab-btn i {
            font-size: 1.05rem;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .auth-tab-btn:hover:not(.active) {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        /* Employee Tab - Highly Highlighted State */
        .auth-tab-btn#tabEmployeeBtn.active {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%) !important;
            color: #ffffff !important;
            font-weight: 700;
            border-color: #0d6efd !important;
            box-shadow: 0 6px 18px rgba(13, 110, 253, 0.38) !important;
            transform: translateY(-1px);
        }

        .auth-tab-btn#tabEmployeeBtn.active i {
            color: #ffffff !important;
            transform: scale(1.15);
        }

        /* Nodal Officer Tab - Highly Highlighted State */
        .auth-tab-btn#tabNodalBtn.active {
            background: linear-gradient(135deg, #198754 0%, #0f5132 100%) !important;
            color: #ffffff !important;
            font-weight: 700;
            border-color: #198754 !important;
            box-shadow: 0 6px 18px rgba(25, 135, 84, 0.38) !important;
            transform: translateY(-1px);
        }

        .auth-tab-btn#tabNodalBtn.active i {
            color: #ffffff !important;
            transform: scale(1.15);
        }

        /* Tab Badges */
        .auth-tab-btn .tab-badge {
            font-size: 0.68rem;
            padding: 3px 8px;
            border-radius: 20px;
            background: #e2e8f0;
            color: #475569;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            transition: all 0.25s ease;
        }

        .auth-tab-btn.active .tab-badge {
            background: rgba(255, 255, 255, 0.25) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
        }

        /* Informative Tab Helper Alert */
        .tab-info-banner {
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.81rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.45;
            animation: fadeInTab 0.3s ease;
        }

        .tab-info-banner.employee-banner {
            background-color: #f0f7ff;
            border-left: 4px solid #0d6efd;
            color: #1e40af;
        }

        .tab-info-banner.nodal-banner {
            background-color: #f0fdf4;
            border-left: 4px solid #198754;
            color: #166534;
        }

        @keyframes fadeInTab {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Input Groups & Form Controls */
        .auth-form-label {
            font-weight: 600;
            font-size: 0.82rem;
            color: #334155;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .auth-input-group .input-group-text {
            background-color: #f8fafc;
            border-color: #dbe2ea;
            color: #64748b;
            padding-left: 14px;
            padding-right: 14px;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .auth-input-group .form-control {
            border-color: #dbe2ea;
            font-size: 0.9rem;
            padding: 10px 14px;
            border-radius: 8px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .auth-input-group .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
        }

        .auth-toggle-pwd-btn {
            border-color: #dbe2ea;
            background: #f8fafc;
            color: #64748b;
            border-top-right-radius: 8px !important;
            border-bottom-right-radius: 8px !important;
        }

        .auth-toggle-pwd-btn:hover {
            background: #e2e8f0;
            color: #334155;
        }

        /* Captcha Wrapper */
        .captcha-card-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 12px;
        }

        /* Submit Button */
        .btn-auth-submit {
            padding: 11px 20px;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.25);
            transition: all 0.25s ease;
        }

        .btn-auth-submit:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(13, 110, 253, 0.3);
        }

        /* OTP Inputs */
        .otp-input {
            width: 52px;
            height: 54px;
            font-size: 1.55rem;
            font-weight: 700;
            border: 2px solid #cbd5e1;
            border-radius: 10px;
            color: #0f172a;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .otp-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.2);
            background-color: #ffffff;
        }

        .otp-input.filled {
            border-color: #198754;
            background-color: #f0fdf4;
            color: #166534;
        }

        @media (max-width: 480px) {
            .otp-input {
                width: 42px;
                height: 48px;
                font-size: 1.25rem;
            }
        }

        .btn-disabled-countdown {
            position: relative;
            overflow: hidden;
        }

        .btn-disabled-countdown:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        /* Security Footer & Badges */
        .auth-footer-badges {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px dashed #e2e8f0;
        }

        .sec-badge-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.73rem;
            color: #64748b;
            font-weight: 500;
        }

        .sec-badge-item i {
            font-size: 0.85rem;
            color: #198754;
        }

        .help-guideline-link {
            font-size: 0.8rem;
            color: #0d6efd;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .help-guideline-link:hover {
            background: #eef2ff;
            color: #0443a0;
        }
    </style>

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">

                {{-- ========================================== --}}
                {{-- MAIN LOGIN CARD (TAB-WISE LOGIN) --}}
                {{-- ========================================== --}}
                <div class="card auth-portal-card mb-0" id="loginCard">
                    {{-- Tricolor / Portal Accent Line --}}
                    <div class="auth-top-accent"></div>

                    <div class="card-body p-4 p-md-4">

                        {{-- Portal Logo & Header --}}
                        <div class="text-center mb-3">
                            @if (!empty($siteSettings->admin_panel_logo))
                                <img src="{{ asset('storage/' . Config::get('file_paths')['SITE_ADMIN_PANEL_LOGO_PATH'] . '/' . $siteSettings->admin_panel_logo) }}"
                                    alt="{{ $siteSettings->site_name ?? 'CPCB Logo' }}" class="auth-brand-logo mb-2">
                            @endif

                            <h1 class="portal-header-title">
                                {{ $siteSettings->site_name ?? 'CPCB Portal' }}
                            </h1>
                        </div>

                        {{-- Role Switcher Tabs: Employee vs Nodal Officer --}}
                        <div class="auth-tab-nav" role="tablist">
                            <button type="button" class="auth-tab-btn active" id="tabEmployeeBtn" role="tab"
                                aria-selected="true" aria-controls="loginForm">
                                <i class="fa fa-user"></i>
                                <span>Employee Login</span>
                            </button>

                            <button type="button" class="auth-tab-btn" id="tabNodalBtn" role="tab" aria-selected="false"
                                aria-controls="loginForm">
                                <i class="fa fa-user-shield"></i>
                                <span>Nodal Officer Login</span>
                            </button>
                        </div>

                        <form action="" id="loginForm" autocomplete="off">
                            @csrf

                            {{-- Hidden login type to preserve role awareness --}}
                            <input type="hidden" name="user_type" id="userTypeInput" value="employee">

                            {{-- Fake fields to prevent intrusive browser autofill --}}
                            <input type="text" class="auth-hidden-field" tabindex="-1">
                            <input type="password" class="auth-hidden-field" tabindex="-1">

                            {{-- Email / Employee / Nodal ID Field --}}
                            <div class="form-group mb-3">
                                <label class="auth-form-label" for="email">
                                    <span id="emailFieldLabel">Employee Code <span
                                            class="text-danger fw-bold">*</span></span>

                                </label>

                                <div class="input-group auth-input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-id-card" id="emailFieldIcon"></i>
                                    </span>
                                    <input type="text" name="email" id="email" class="form-control"
                                        placeholder="e.g. CPCB**** (8 characters)" autocomplete="new-password"
                                        autocorrect="off" autocapitalize="off" spellcheck="false">
                                </div>
                            </div>

                            {{-- Password Field --}}
                            <div class="form-group mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="auth-form-label mb-0" for="password">
                                        <span id="passwordFieldLabel">Password (PAN in Uppercase) <span
                                                class="text-danger fw-bold">*</span></span>

                                    </label>
                                </div>

                                <div class="input-group auth-input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Enter your password / PAN" autocomplete="new-password"
                                        autocorrect="off" autocapitalize="off" spellcheck="false">
                                    <button class="btn btn-outline-secondary auth-toggle-pwd-btn" type="button"
                                        id="togglePassword" title="Show / Hide Password">
                                        <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Captcha Section --}}
                            <div class="captcha-card-box">
                                <div class="col-12 mb-2">
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
                                    <label for="captcha" class="auth-form-label">
                                        <span>Security Captcha Code <span class="text-danger fw-bold">*</span></span>

                                    </label>

                                    <div class="input-group auth-input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-shield"></i>
                                        </span>
                                        <input type="text" class="form-control" maxlength="6" id="captcha" name="captcha"
                                            placeholder="Enter the 6-character code shown above" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary btn-auth-submit" id="loginBtn">
                                    <i class="fa fa-sign-in-alt me-1"></i>
                                    <span id="loginBtnText">Sign In as Employee</span>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                {{-- ========================================== --}}
                {{-- TWO-FACTOR OTP VERIFICATION CARD --}}
                {{-- ========================================== --}}
                <div class="card auth-portal-card border-0 mb-0 auth-otp-card" id="otpCard">
                    <div class="auth-top-accent auth-top-accent-otp">
                    </div>

                    <div class="card-header bg-light border-0 py-3 text-center">
                        <div class="d-inline-flex p-2 rounded-circle bg-primary-subtle text-primary mb-2">
                            <i class="fa fa-shield-alt fa-2x"></i>
                        </div>
                        <h2 class="h5 mb-0 fw-bold text-dark">
                            Two-Factor Authentication
                        </h2>
                    </div>

                    <div class="card-body p-4 pt-2">
                        <div class="text-center mb-3">
                            <div
                                class="alert alert-success py-2 px-3 small border-0 text-start d-flex align-items-center gap-2 mb-2">
                                <i class="fa fa-check-circle fa-2x text-success"></i>
                                <div>
                                    <strong>OTP Sent Successfully</strong><br>
                                    A 6-digit one-time password has been sent successfully to your registered mobile
                                    number and email.
                                </div>
                            </div>
                            <div id="contactInfo" class="small fw-semibold text-secondary mb-2"></div>
                        </div>

                        <form action="" id="otpForm" autocomplete="off">
                            @csrf
                            <div class="form-group mb-3 text-center">
                                <label class="auth-form-label justify-content-center d-block mb-2">
                                    Enter 6-Digit OTP Code
                                </label>

                                <div class="otp-input-container d-flex justify-content-center gap-2 mb-2">
                                    <input type="text" class="form-control otp-input text-center" maxlength="1"
                                        data-index="0" inputmode="numeric" pattern="[0-9]*" aria-label="OTP digit 1"
                                        autocomplete="off">
                                    <input type="text" class="form-control otp-input text-center" maxlength="1"
                                        data-index="1" inputmode="numeric" pattern="[0-9]*" aria-label="OTP digit 2"
                                        autocomplete="off">
                                    <input type="text" class="form-control otp-input text-center" maxlength="1"
                                        data-index="2" inputmode="numeric" pattern="[0-9]*" aria-label="OTP digit 3"
                                        autocomplete="off">
                                    <input type="text" class="form-control otp-input text-center" maxlength="1"
                                        data-index="3" inputmode="numeric" pattern="[0-9]*" aria-label="OTP digit 4"
                                        autocomplete="off">
                                    <input type="text" class="form-control otp-input text-center" maxlength="1"
                                        data-index="4" inputmode="numeric" pattern="[0-9]*" aria-label="OTP digit 5"
                                        autocomplete="off">
                                    <input type="text" class="form-control otp-input text-center" maxlength="1"
                                        data-index="5" inputmode="numeric" pattern="[0-9]*" aria-label="OTP digit 6"
                                        autocomplete="off">
                                </div>
                                <input type="hidden" name="otp" id="otpValue">
                            </div>

                            {{-- OTP Expiry Timer --}}
                            <div class="text-center mb-3">
                                <span class="badge bg-light text-dark border px-3 py-2">
                                    <i class="fa fa-clock text-danger me-1"></i> OTP expires in:
                                    <span id="otpTimer" class="fw-bold text-danger">02:00</span>
                                </span>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success fw-semibold py-2" id="verifyOtpBtn">
                                    <i class="fa fa-check-circle me-1"></i> <span id="verifyOtpBtnText">Verify OTP &amp;
                                        Continue</span>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-disabled-countdown py-2"
                                    id="resendOtpBtn">
                                    <i class="fa fa-refresh me-1"></i> <span id="resendBtnText">Resend OTP</span>
                                </button>
                                <button type="button" class="btn btn-link text-secondary text-decoration-none mt-1"
                                    id="backToLoginBtn">
                                    <i class="fa fa-arrow-left me-1"></i> Back to Credentials Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection

@section('pages-scripts')
    <script @cspNonce>
        let otpTimer;
        let otpTimeRemaining = 120; // 2 minutes in seconds
        let resendTimer;
        let resendTimeRemaining = 0;
        let activeTab = 'employee'; // 'employee' or 'nodal'

        // =========================
        // Disable copy/paste/cut & init
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

            // Open Login Instructions Popup on "View Info" button click
            $('#viewInstructionBtn').on('click', function () {
                const modalEl = document.getElementById('loginInstructionModal');
                if (modalEl) {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const instructionModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        instructionModal.show();
                    } else if (typeof $ !== 'undefined' && $.fn && $.fn.modal) {
                        $('#loginInstructionModal').modal('show');
                    }
                }
            });

            // ==========================================
            // Tab Switcher Handling: Employee vs Nodal
            // ==========================================
            function clearTabInputs() {
                $('#email, #password, #captcha').val('');
                $('#password').attr('type', 'password');
                $('#togglePasswordIcon').removeClass('fa-eye-slash').addClass('fa-eye');
                if ($.fn.validate && $('#loginForm').data('validator')) {
                    $('#loginForm').data('validator').resetForm();
                }
                $('#loginForm').find('.is-invalid').removeClass('is-invalid');
            }

            $('#tabEmployeeBtn').on('click', function () {
                if (activeTab === 'employee') return;
                activeTab = 'employee';
                clearTabInputs();

                $('#tabEmployeeBtn').addClass('active').attr('aria-selected', 'true');
                $('#tabNodalBtn').removeClass('active').attr('aria-selected', 'false');

                $('#employeeInfoBanner').slideDown(200);
                $('#nodalInfoBanner').slideUp(200);

                $('#userTypeInput').val('employee');
                $('#emailFieldLabel').text('Employee Code');
                $('#email').attr('placeholder', 'e.g. CPCB**** (8 characters)');
                $('#emailFieldIcon').attr('class', 'fa fa-id-card');

                $('#passwordFieldLabel').text('Password (PAN in Uppercase)');
                $('#password').attr('placeholder', 'Enter your password / PAN');

                $('#loginBtnText').text('Sign In as Employee');
                $('#loginBtn').removeClass('btn-success').addClass('btn-primary');

                $('#email').focus();
            });

            $('#tabNodalBtn').on('click', function () {
                if (activeTab === 'nodal') return;
                activeTab = 'nodal';
                clearTabInputs();

                $('#tabNodalBtn').addClass('active').attr('aria-selected', 'true');
                $('#tabEmployeeBtn').removeClass('active').attr('aria-selected', 'false');

                $('#employeeInfoBanner').slideUp(200);
                $('#nodalInfoBanner').slideDown(200);

                $('#userTypeInput').val('nodal_officer');
                $('#emailFieldLabel').html('Username <span class="text-danger fw-bold">*</span>');
                $('#email').attr('placeholder', 'Enter your username');
                $('#emailFieldIcon').attr('class', 'fa fa-user-shield');

                $('#passwordFieldLabel').html('Password <span class="text-danger fw-bold">*</span>');
                $('#password').attr('placeholder', 'Enter your password');

                $('#loginBtnText').text('Sign In as Nodal Officer');
                $('#loginBtn').removeClass('btn-primary').addClass('btn-success');

                $('#email').focus();
            });

        });

        // =========================
        // Encryption Key & Request State Flags
        // =========================
        var encryptionKey = "{{ \App\Helpers\CustomHelper::setEncryptionKey() }}";
        var isSubmittingLogin = false;
        var isVerifyingOtp = false;
        var isResendingOtp = false;

        // =========================
        // Form Validation - Login
        // =========================
        $("#loginForm").validate({
            errorClass: "text-danger validation-error small mt-1 d-block",
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
            messages: {
                email: {
                    required: "Please enter your registered Email or Employee / Officer Code"
                },
                password: {
                    required: "Please enter your password"
                },
                captcha: {
                    required: "Please enter the security captcha code"
                }
            },
            submitHandler: async function (form, event) {
                event.preventDefault();
                if (isSubmittingLogin || $('#loginBtn').prop('disabled')) {
                    return;
                }
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
                    required: "Please enter the 6-digit OTP",
                    minlength: "OTP must be 6 digits",
                    maxlength: "OTP must be 6 digits"
                }
            },
            submitHandler: function (form, event) {
                event.preventDefault();
                if (isVerifyingOtp || $('#verifyOtpBtn').prop('disabled')) {
                    return;
                }
                verifyOtp();
            }
        });

        // =========================
        // OTP Input Handling
        // =========================
        $(document).ready(function () {
            // OTP input handling
            $('.otp-input').on('input', function () {
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

            $('.otp-input').on('keydown', function (e) {
                let index = parseInt($(this).data('index'));

                // Handle backspace
                if (e.keyCode === 8 && !$(this).val() && index > 0) {
                    $('.otp-input[data-index="' + (index - 1) + '"]').focus();
                }
            });

            // Handle paste into OTP inputs
            $('.otp-input').on('paste', function (e) {
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
                    let nextIndex = Math.min(pastedData.length, 5);
                    $('.otp-input[data-index="' + nextIndex + '"]').focus();
                }
            });

            // Back to login button
            $('#backToLoginBtn').click(function () {
                showLoginForm();
            });

            // Resend OTP button
            $('#resendOtpBtn').click(function () {
                if (!$(this).prop('disabled') && !isResendingOtp && !isVerifyingOtp) {
                    resendOtp();
                }
            });
        });

        // =========================
        // Login AJAX
        // =========================
        async function performLogin() {
            if (isSubmittingLogin || $('#loginBtn').prop('disabled')) {
                return;
            }

            isSubmittingLogin = true;
            let btn = $('#loginBtn');
            let label = activeTab === 'nodal' ? 'Sign In as Nodal Officer' : 'Sign In as Employee';

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Please wait...');

            try {
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
                    beforeSend: function () {
                        showLoader();
                    },
                    success: function (response) {
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
                                title: "Success",
                                text: "OTP has been sent successfully to your registered mobile number and email.",
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
                                title: "Authentication Failed",
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
                    error: function (xhr) {
                        hideLoader();
                        mkyReloadCaptcha();

                        let errorMessage = "An error occurred during authentication. Please try again.";

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
                    complete: function () {
                        isSubmittingLogin = false;
                        if (!$('#otpCard').is(':visible')) {
                            let label = activeTab === 'nodal' ? 'Sign In as Nodal Officer' : 'Sign In as Employee';
                            let btn = $('#loginBtn');
                            btn.prop('disabled', false).html('<i class="fa fa-sign-in-alt me-1"></i> <span id="loginBtnText">' + label + '</span>');
                        }
                    }
                });
            } catch (err) {
                isSubmittingLogin = false;
                btn.prop('disabled', false).html('<i class="fa fa-sign-in-alt me-1"></i> <span id="loginBtnText">' + label + '</span>');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Failed to process login request. Please try again."
                });
            }
        }

        // =========================
        // Verify OTP AJAX
        // =========================
        function verifyOtp() {
            if (isVerifyingOtp || $('#verifyOtpBtn').prop('disabled')) {
                return;
            }

            const otpVal = $('#otpValue').val();
            if (!otpVal || otpVal.length !== 6) {
                return;
            }

            isVerifyingOtp = true;
            const formData = new FormData(document.getElementById('otpForm'));
            const verifyBtn = $('#verifyOtpBtn');

            $.ajax({
                url: "{{ route('login.verify-otp') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    showLoader();
                    verifyBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Verifying OTP...');
                    $('#resendOtpBtn').prop('disabled', true);
                    $('#backToLoginBtn').prop('disabled', true);
                },
                success: function (response) {
                    hideLoader();

                    if (response.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = response.redirect;
                        });
                    } else {
                        isVerifyingOtp = false;
                        verifyBtn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> <span id="verifyOtpBtnText">Verify OTP &amp; Continue</span>');
                        $('#backToLoginBtn').prop('disabled', false);
                        if (resendTimeRemaining <= 0) {
                            $('#resendOtpBtn').prop('disabled', false);
                        }
                        clearOtpInputs();
                        Swal.fire({
                            icon: "error",
                            title: "Verification Failed",
                            text: response.message
                        });
                    }
                },
                error: function (xhr) {
                    hideLoader();
                    isVerifyingOtp = false;
                    verifyBtn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> <span id="verifyOtpBtnText">Verify OTP &amp; Continue</span>');
                    $('#backToLoginBtn').prop('disabled', false);
                    if (resendTimeRemaining <= 0) {
                        $('#resendOtpBtn').prop('disabled', false);
                    }
                    clearOtpInputs();
                    let errorMessage = "An error occurred during OTP verification. Please try again.";
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
            if (isResendingOtp || $('#resendOtpBtn').prop('disabled')) {
                return;
            }

            isResendingOtp = true;
            const resendBtn = $('#resendOtpBtn');

            $.ajax({
                url: "{{ route('login.resend-otp') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                beforeSend: function () {
                    showLoader();
                    resendBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending OTP...');
                    $('#verifyOtpBtn').prop('disabled', true);
                },
                success: function (response) {
                    hideLoader();

                    if (response.status === "success") {
                        clearOtpInputs();
                        resetOtpTimer();
                        startResendTimer();
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: "OTP has been sent successfully to your registered mobile number and email.",
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Failed to Resend",
                            text: response.message
                        });
                        resendBtn.prop('disabled', false).html('<i class="fa fa-refresh me-1"></i> <span id="resendBtnText">Resend OTP</span>');
                    }
                },
                error: function (xhr) {
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
                    resendBtn.prop('disabled', false).html('<i class="fa fa-refresh me-1"></i> <span id="resendBtnText">Resend OTP</span>');
                },
                complete: function () {
                    isResendingOtp = false;
                    if (!isVerifyingOtp) {
                        $('#verifyOtpBtn').prop('disabled', false);
                    }
                }
            });
        }

        // =========================
        // OTP Form Toggle
        // =========================
        function showOtpForm() {
            $('#loginCard').hide();
            $('#otpCard').fadeIn(200);
            clearOtpInputs();
            isVerifyingOtp = false;
            isResendingOtp = false;
            $('#verifyOtpBtn').prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> <span id="verifyOtpBtnText">Verify OTP &amp; Continue</span>');
            $('#backToLoginBtn').prop('disabled', false);
            $('.otp-input:first').focus();
            startOtpTimer();
            startResendTimer();
        }

        function showLoginForm() {
            $('#otpCard').hide();
            $('#loginCard').fadeIn(200);
            clearOtpInputs();
            stopOtpTimer();
            stopResendTimer();
            $("#loginForm")[0].reset();
            isSubmittingLogin = false;
            isVerifyingOtp = false;
            isResendingOtp = false;
            let label = activeTab === 'nodal' ? 'Sign In as Nodal Officer' : 'Sign In as Employee';
            $('#loginBtn').prop('disabled', false).html('<i class="fa fa-sign-in-alt me-1"></i> <span id="loginBtnText">' + label + '</span>');
            $('#verifyOtpBtn').prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> <span id="verifyOtpBtnText">Verify OTP &amp; Continue</span>');
            $('#resendOtpBtn').prop('disabled', false).html('<i class="fa fa-refresh me-1"></i> <span id="resendBtnText">Resend OTP</span>');
            $('#backToLoginBtn').prop('disabled', false);
            mkyReloadCaptcha();
        }

        // =========================
        // OTP Value Management
        // =========================
        function updateOtpValue() {
            let otp = '';
            $('.otp-input').each(function () {
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
            otpTimeRemaining = 120; // Reset to 10 minutes
            updateTimerDisplay();

            otpTimer = setInterval(function () {
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

            $('#resendOtpBtn').prop('disabled', true);

            resendTimer = setInterval(function () {
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
                $('#resendOtpBtn').prop('disabled', true).html('<i class="fa fa-refresh me-1"></i> <span id="resendBtnText">Resend OTP (' + timeString + ')</span>');
            } else {
                $('#resendOtpBtn').html('<i class="fa fa-refresh me-1"></i> <span id="resendBtnText">Resend OTP</span>');
            }
        }

        function enableResendButton() {
            if (!isResendingOtp && !isVerifyingOtp) {
                $('#resendOtpBtn').prop('disabled', false).html('<i class="fa fa-refresh me-1"></i> <span id="resendBtnText">Resend OTP</span>');
            }
        }

        // =========================
        // Security Controls
        // =========================
        document.addEventListener("contextmenu", function (e) {
            e.preventDefault();
        });

        document.addEventListener("keydown", function (e) {
            if (e.ctrlKey && e.key.toLowerCase() === 'u') {
                e.preventDefault();
            }
        });

        document.addEventListener("keydown", function (e) {
            if (
                (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'i') ||
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