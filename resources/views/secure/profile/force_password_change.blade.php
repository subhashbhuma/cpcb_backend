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
                    <div class="mb-3 text-center">
                        <h3 class="mb-0 text-danger">
                            <b>Password Update Required</b>
                        </h3>
                        <p class="text-secondary mt-2">
                            {{ session('error') ?? 'Your password has expired or you are required to change it. Please update your password to continue.' }}
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('force-password-change.update') }}" method="POST" id="forcePasswordForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="old_password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" required>
                            <small class="text-muted">Must contain at least 8 characters, one uppercase, one lowercase, one number, and one special character.</small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="new_password_confirmation" required>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary" id="changePwdBtn">
                                Change Password
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-secondary">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pages-scripts')
<script @cspNonce>
    var encryptionKey = "{{ \App\Helpers\CustomHelper::setEncryptionKey() }}";

    $(document).ready(function() {
        $('#forcePasswordForm').on('submit', async function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = $('#changePwdBtn');
            let originalText = btn.html();

            // Basic validation
            let oldPwd = $('input[name="old_password"]').val();
            let newPwd = $('input[name="new_password"]').val();
            let confirmPwd = $('input[name="new_password_confirmation"]').val();

            if (!oldPwd || !newPwd || !confirmPwd) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'All fields are required.' });
                return;
            }

            if (newPwd !== confirmPwd) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'New passwords do not match.' });
                return;
            }

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...');

            try {
                let formData = new FormData(this);

                // Encrypt passwords
                formData.set('old_password', await encryptPassword(oldPwd, encryptionKey));
                formData.set('new_password', await encryptPassword(newPwd, encryptionKey));
                formData.set('new_password_confirmation', await encryptPassword(confirmPwd, encryptionKey));

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        btn.prop('disabled', false).html(originalText);

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                $('#logout-form').submit();
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: response.message || 'Error updating password.' });
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html(originalText);
                        
                        let errorMessage = 'Something went wrong!';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                // Extract first validation error
                                errorMessage = Object.values(xhr.responseJSON.errors)[0][0];
                            } else if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                        }
                        
                        Swal.fire({ icon: 'error', title: 'Error', text: errorMessage });
                    }
                });
            } catch (err) {
                btn.prop('disabled', false).html(originalText);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Encryption failed. Please try again.' });
            }
        });
    });
</script>
@endsection
