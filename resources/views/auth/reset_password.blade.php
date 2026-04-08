{{-- resources/views/auth/reset_password.blade.php --}}
@extends('layout.auth')
@section('page-title')
 Reset Password
@endsection
@section('content')
<?php use App\Models\Setting; $settings = Setting::first(); ?>

<div class="d-flex col-12 col-xl-2"></div>
<div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-6 p-sm-12">
    <div class="w-px-400 mx-auto ">
      <h4 class="mb-1">Reset Password 🔒</h4>
      <p class=""><span class="fw-medium">Your new password must be different from previously used passwords</span></p>
      <div class="text-center my-3">

        @if ( $settings->system_logo)
            <img src="{{ asset('storage/' . $settings->system_logo) }}" alt="Logo" class="auth-logo" />
        @else
            <img src="{{ asset('assets/img/icons/brands/laravel-logo.png') }}" alt="Logo" class="auth-logo" />
        @endif
    </div>

        <form  id="resetPasswordForm" class="mb-6" >
            @csrf


            {{-- <input type="hidden" name="token" value="{{ $token }}"> --}}
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-6 form-password-toggle form-control-validation">

                <label class="form-label" for="token">Token</label>
                <div class="input-group">
                    <input type="text" id="token" class="form-control" name="token" placeholder="Enter OTP Token"/>
                </div>
            </div>

            <div class="mb-6 form-password-toggle form-control-validation">

                <label class="form-label" for="password">New Password</label>
                <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
                </div>
            </div>
            <div class="mb-6 form-password-toggle form-control-validation">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <div class="input-group input-group-merge">
                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
                </div>
            </div>

            <!-- Message Div -->
            <div id="message" style="display:none; margin-bottom: 5px;"></div>

            <button type="submit" class="btn btn-primary d-grid w-100 mb-6">Set new password</button>
            <div class="text-center">
            <a href="/login" class="d-flex justify-content-center">
                <i class="icon-base ti tabler-chevron-left scaleX-n1-rtl me-1_5"></i>
                Back to login
            </a>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#resetPasswordForm').on('submit', function (event) {
            event.preventDefault(); // Stop default form submission

            let form = document.getElementById('resetPasswordForm');
            let formData = new FormData(form);

            $.ajax({
                url: '/resetPassword',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    showMessage(response.message, response.status === 200 ? 'green' : 'red');

                    if (response.redirect) {
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 3000);
                    }
                },
                error: function (jqXHR) {
                    const response = jqXHR.responseJSON || {};
                    showMessage(response.message || 'An error occurred.', 'red');
                }
            });
        });

    });
    </script>


@endsection
