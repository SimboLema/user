<!DOCTYPE html>
<html
  lang="en"
  class=" layout-wide  customizer-hide"
  dir="ltr"
  data-skin="default"
  data-assets-path="{{asset('assets/')}}/"
  data-template="vertical-menu-template"
  data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" />

    <!-- ? PROD Only: Google Tag Manager (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
    <script>
        (function (w, d, s, l, i) {
          w[l] = w[l] || [];
          w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
          var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
          j.async = true;
          j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
          f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-5J3LMKC');
    </script>
    <!-- End Google Tag Manager -->


    <title>@yield('page-title')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <style>
        #message {
            display: none;
        }

        .animated-message {
            animation: bounce 1s ease infinite;
        }
        .logo {
            max-width: 50%; /* Ensures the image doesn't exceed the width of the container */
            height: auto; /* Maintains the aspect ratio */
            object-fit: contain; /* Ensures the image fits well within its dimensions */
            display: block; /* Centers the image in the container */
            margin: 0 auto; /* Centers the image horizontally */
        }
        .auth-logo {
            width: 150px;
            height: auto;
        }
        .icon-image {
  width: 48px;
  height: 48px;
  object-fit: contain; /* Or 'cover' if you want to fill the box */
  display: block;
  margin: 0 auto;
}

        .text-center {
            text-align: center;
        }

    </style>
  @include('layout.top-script')
</head>

<body>
<?php
use App\Models\Setting;

 $settings = Setting::first();

?>


<div class="authentication-wrapper authentication-cover authentication-bg">
    <!-- Logo -->
    {{-- <a href="/" class="app-brand auth-cover-brand">
      <span class="app-brand-logo demo">
            <span class="text-primary">

                @if($settings && $settings->system_logo)
                    <img class="logo" src="{{ $settings && $settings->system_logo ? asset('storage/'.$settings->system_logo) : asset('assets/images/app-logo.svg') }}" alt="logo">

                @else
                    <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="currentColor" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="currentColor" />
                    </svg>
                @endif
            </span>
        </span>
      <span class="app-brand-text demo text-heading fw-bold">{{ $settings->system_name ?? "TakaLink"}}</span>
    </a> --}}
    <!-- /Logo -->
    <div class="authentication-inner row">
      <!-- Left Text -->
      <div class="d-none d-lg-flex col-lg-2 align-items-center justify-content-center p-5 position-relative auth-multisteps-bg-height">
        {{-- <img src="{{asset('assets/img/illustrations/auth-register-multisteps-illustration.png')}}" alt="auth-register-multisteps" class="img-fluid" width="280" />
        <img src="{{asset('assets/img/illustrations/auth-register-multisteps-shape-light.png')}}" alt="auth-register-multisteps" class="platform-bg" data-app-light-img="illustrations/auth-register-multisteps-shape-light.png" data-app-dark-img="illustrations/auth-register-multisteps-shape-dark.png" /> --}}
      </div>
      <!-- /Left Text -->

      @yield('content')

      <div class="d-none d-lg-flex col-lg-2 align-items-center justify-content-center p-5 position-relative auth-multisteps-bg-height">
        {{-- <img src="{{asset('assets/img/illustrations/auth-register-multisteps-illustration.png')}}" alt="auth-register-multisteps" class="img-fluid" width="280" />
        <img src="{{asset('assets/img/illustrations/auth-register-multisteps-shape-light.png')}}" alt="auth-register-multisteps" class="platform-bg" data-app-light-img="illustrations/auth-register-multisteps-shape-light.png" data-app-dark-img="illustrations/auth-register-multisteps-shape-dark.png" /> --}}
      </div>
    </div>
</div>


    {{-- <div class="authentication-wrapper authentication-cover">
            <!-- Logo -->
            <a href="/" class="app-brand auth-cover-brand">
            <span class="app-brand-logo demo">

                @if($settings && $settings->system_logo)
                    <img class="logo" src="{{ $settings && $settings->system_logo ? asset('storage/'.$settings->system_logo) : asset('assets/images/app-logo.svg') }}" alt="logo">

                @else
                    <span class="text-primary">
                        <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="currentColor" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="currentColor" />
                        </svg>
                    </span>
                @endif
            </span>
            <span class="app-brand-text demo text-heading fw-bold">{{ $settings->system_name ?? "TakaLink"}}</span>
            </a>
            <!-- /Logo -->
        <div class="authentication-inner row m-0">
            <!-- /Left Text -->
            <div class="d-none d-xl-flex col-xl-8 p-0">
                <div class="auth-cover-bg d-flex justify-content-center align-items-center">
                <img src="{{asset('assets/img/illustrations/auth-login-illustration-light.png')}}" alt="auth-login-cover" class="my-5 auth-illustration" data-app-light-img="illustrations/auth-login-illustration-light.png" data-app-dark-img="illustrations/auth-login-illustration-dark.png" />
                <img src="{{asset('assets/img/illustrations/bg-shape-image-light.png')}}" alt="auth-login-cover" class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.png" data-app-dark-img="illustrations/bg-shape-image-dark.png" />
                </div>
            </div>
            <!-- /Left Text -->

            @yield('content')

        </div>
    </div> --}}



  <script>
    function showMessage(message, bgColor) {
        $('#message')
            .css({
                'background-color': bgColor,
                'color': 'white',
                'padding': '5px 5px',
                'margin' : '5px',
                'border-radius': '5px',
                'font-size': '14px',
                'font-weight': 'bold',
                'width': '100%',
                'z-index': '1',
                'text-align': 'center',
            })
            .text(message)
            .addClass('animated-message')
            .show();

        setTimeout(function () {
            $('#message').fadeOut();
        }, 5000);
    }
  </script>

  @include('layout.script')
</body>

</html>
