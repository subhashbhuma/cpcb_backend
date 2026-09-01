@extends('layouts.auth_layout')

@section('content')
    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ asset('storage/' . Config::get('file_paths')['SITE_ADMIN_PANEL_LOGO_PATH'] . '/' . $siteSettings->admin_panel_logo) }}"
                                alt="{{ $siteSettings->site_name }}" class="w-25">
                        </div>
                        <hr>
                        <div class="mb-3">
                            <h1 class="mb-0 auth-login-title"><b>Login Here</b></h1>
                            <p class="text-secondary">
                                Enter your credentials to login
                            </p>
                        </div>
                        <form action="" id="loginForm">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label" for="email">Email Address or Employee Code</label>
                                <input type="text" name="email" id="email" class="form-control" placeholder="Email Address or Employee Code" autocomplete="off"  oncopy="return false;" onpaste="return false;" oncut="return false;">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password"
                                  autocomplete="off" oncopy="return false;" onpaste="return false;" oncut="return false;">
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
                                <label for="captcha" class="form-label" for="captcha">Captcha</label>
                                <input type="text" class="form-control" maxlength="6" id="captcha" name="captcha"
                                    autocomplete="off" />
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Login</button>
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
            $("#loginForm").validate({
                errorClass: "text-danger validation-error",
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
                var formData = new FormData(document.getElementById('loginForm'));
                const password = await encryptPassword(formData.get('password'), encryptionKey);
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
                            } else if (response.status == 'validation_error') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: response.message
                            })
                        } else if (response.status == 'concurrent_login') {
                            handleConcurrentLogin(response);
                        } else if(response.status == 'error'){
                            encryptionKey = response.key;
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response.message,
                            });
                        }else{
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
                            let errorMessage = "An error occurred. Please try again.";
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
                        }
                    });
                }
            });


       // Disable right-click
        document.addEventListener("contextmenu", function(e) {
            e.preventDefault();
        });

        // Disable Ctrl+U (view source)
        document.addEventListener("keydown", function(e) {
            if (e.ctrlKey && e.key.toLowerCase() === 'u') {
                e.preventDefault();
            }
        });

        // (Optional) Disable Ctrl+Shift+I and F12 (inspect element)
        document.addEventListener("keydown", function(e) {
            if ((e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'i') || e.key === "F12") {
                e.preventDefault();
            }
        });

        // Handle concurrent login scenario
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

        function mkyReloadCaptcha(){
            $('.mky-captcha-refresh').click();
        }
    </script>
@endsection
