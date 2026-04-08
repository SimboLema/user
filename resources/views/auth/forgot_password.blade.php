@extends('layout.auth')
@section('page-title')
 Forgot Password
@endsection
@section('content')
<?php use App\Models\Setting; $settings = Setting::first(); ?>

<div class="d-flex col-12 col-xl-2"></div>
<div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
    <div class="w-px-400 mx-auto ">
      <h4 class="mb-1">Forgot Password? 🔒</h4>
      <p class="">Enter your email/phone number and we'll send you OTP to reset your password</p>
        <div class="text-center ">

            @if ( $settings->system_logo)
                <img src="{{ asset('storage/' . $settings->system_logo) }}" alt="Logo" class="auth-logo" />
            @else
                <img src="{{ asset('assets/img/icons/brands/laravel-logo.png') }}" alt="Logo" class="auth-logo" />
            @endif
        </div>
      <form id="forgotPasswordForm" class="mb-6">
        @csrf
        <div class="mb-6 form-control-validation">
          <label for="email" class="form-label">Email/Phone Number</label>
          <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus />
        </div>

          <!-- Message Div -->
          <div id="message" style="display:none; margin-bottom: 5px;"></div>

        <button class="btn btn-primary d-grid w-100" type="submit">Send OTP</button>
      </form>
      <div class="text-center">
        <a href="/login" class="d-flex justify-content-center">
          <i class="icon-base ti tabler-chevron-left scaleX-n1-rtl me-1_5"></i>
          Back to login
        </a>
      </div>
    </div>
  </div>




<script>
$(document).ready(function() {
    $('#forgotPasswordForm').on('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting

        // Call the function to handle the forgot password request
        handleForgotPassword();
    });

    function handleForgotPassword() {
        const email = $('#email').val();

        // Basic validation
        if (email === '') {
            showMessage('Please enter your email.', 'red');
            return;
        }

        // AJAX request to send password reset link
        $.ajax({
            url: '/sendPasswordResetLink', // Replace with your endpoint
            type: 'POST',
            data: {
                email: email,
                _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
            },
            success: function(response) {
                if (response.status === 200) {
                    showMessage(response.message, 'green');

                    setTimeout(function() {
                        window.location.href=response.redirect;
                     }, 3000);
                } else {
                    showMessage(response.message, 'red');
                }
            },
            error: function(jqXHR) {
                // Handle error response
                const response = jqXHR.responseJSON || {};
                showMessage(response.message || 'An error occurred.', 'red');
            }
        });
    }


});
</script>
@endsection
