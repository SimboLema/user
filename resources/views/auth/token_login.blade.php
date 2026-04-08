{{-- resources/views/auth/token_login.blade.php --}}
@extends('layout.auth')
@section('page-title')
Token Login
@endsection
@section('content')
<?php use App\Models\Setting; $settings = Setting::first(); ?>

 <!-- Two Steps Verification -->
 <div class="d-flex col-12 col-xl-2"></div>
 <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-6 p-sm-12">
    <div class="w-px-400 mx-auto ">
      <h4 class="mb-1">Two Step Verification 💬</h4>
      <p class="text-start mb-6">
        We sent a verification code to your mobile. Enter the code from the mobile in the field below.
        <span class="fw-medium d-block mt-1 text-heading">{{$email}}</span>
      </p>
      <p class="">Type your 6 digit security code</p>
      <div class="text-center my-3">

        @if ( $settings->system_logo)
            <img src="{{ asset('storage/' . $settings->system_logo) }}" alt="Logo" class="auth-logo" />
        @else
            <img src="{{ asset('assets/img/icons/brands/laravel-logo.png') }}" alt="Logo" class="auth-logo" />
        @endif
    </div>

      <form id="tokenLoginForm">
        @csrf

        <input  type="hidden" name="email" id="email"  class="form-control signin-email" placeholder="Enter your email" value="{{$email}}" >

        <div class="mb-6 form-control-validation">
          <div class="auth-input-wrapper d-flex align-items-center justify-content-between numeral-mask-wrapper">
            <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2" maxlength="1" autofocus />
            <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2" maxlength="1" />
            <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2" maxlength="1" />
            <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2" maxlength="1" />
            <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2" maxlength="1" />
            <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2" maxlength="1" />
          </div>
          <!-- Create a hidden field which is combined by 3 fields above -->
          <input type="hidden" name="token" id="token" />
        </div>

         <!-- Message Div -->
         <div id="message" style="display:none; margin-bottom: 5px;"></div>

        <button class="btn btn-primary d-grid w-100 mb-6"  type="submit">Verify my account</button>
        <div class="text-center">
          Didn't get the code?
          <a href="javascript:void(0);"  id="resendTokenBtn" > Resend </a>
        </div>
      </form>
    </div>
  </div>
  <!-- /Two Steps Verification -->


<script>
$(document).ready(function() {

    $('#tokenLoginForm').on('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting

        // Call the function to handle the token login
        handleTokenLogin();
    });

    $('#resendTokenBtn').on('click', function(event) {
        event.preventDefault(); // Prevent default button action

        // Call the function to resend the token
        resendToken();
    });

    function handleTokenLogin() {
        const token = $('#token').val();
        const email = $('#email').val(); // Get the hidden email

        // Basic validation
        if (token === '') {
            showMessage('Please enter the token.', 'red');
            return;
        }

        showMessage("Checking ...", 'blue');
        $.ajax({
            url: '/verifyToken', // Replace with your token verification endpoint
            type: 'POST',
            data: {
                email: email,
                token: token,
                _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
            },
            success: function(response) {
                if (response.status === 200) {
                    showMessage(response.message, 'green');

                    // Redirect to dashboard after successful token verification
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

    function resendToken() {
        const email = $('#email').val(); // Get the hidden email

        showMessage("Resending ...", 'blue');
        $.ajax({
            url: '/resendToken', // Replace with your resend token endpoint
            type: 'POST',
            data: {
                email: email,
                _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
            },
            success: function(response) {
                showMessage(response.message, 'green');
            },
            error: function(jqXHR) {
                const response = jqXHR.responseJSON || {};
                showMessage(response.message || 'An error occurred while resending the token.', 'red');
            }
        });
    }



});
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
      const inputs = document.querySelectorAll('.auth-input');
      const tokenField = document.getElementById('token');

      function updateToken() {
        let token = '';
        inputs.forEach(input => token += input.value.trim());
        tokenField.value = token;
      }

      inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
          if (input.value && index < inputs.length - 1) {
            inputs[index + 1].focus();
          }
          updateToken();
        });

        input.addEventListener('keydown', (e) => {
          if (e.key === 'Backspace' && !input.value && index > 0) {
            inputs[index - 1].focus();
          }
        });
      });
    });
    </script>


@endsection
