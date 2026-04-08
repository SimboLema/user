@extends('layout.auth')

@section('page-title')
    Insurer Login
@endsection

@section('content')
<style>
    html, body {
        margin: 0;
        height: 100%;
        overflow: visible;
    }

    body {
        background-image: url("{{ asset('assets/dash/board_files/login_page.jpg') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }

    .auth-logo {
        width: 160px;
        height: auto;
    }

    .authentication-bg {
        background: rgba(0, 0, 0, 0.7);
        border-radius: 20px;
        padding: 30px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .row.m-0 {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 100vh;
        overflow: hidden;
    }

    .auth-cover-bg {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
        padding: 2rem;
    }

    .form-section {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    @media (max-width: 991px) {
        html, body {
            height: auto;
            overflow: auto;
        }
        .row.m-0 {
            flex-direction: column;
            min-height: auto;
            overflow: visible;
        }
        .form-section {
            height: auto;
            padding: 40px 0;
        }
        .authentication-bg {
            margin: 20px;
        }
    }
</style>

<?php use App\Models\Setting; $settings = Setting::first(); ?>

<div class="row m-0" style="min-height: 100vh;">
    <!-- Left Side Branding -->
    <div class="d-none d-xl-flex col-xl-8 p-0">
        <div class="auth-cover-bg text-center">
            <h4 class="mb-2 text-white" style="font-size: 32px">
                Welcome to {{ $settings->system_name ?? 'SURETECH Systems' }}
            </h4>
            <p class="text-white" style="font-size: 18px">Your Peace of Mind, Our Priority</p>
            <img src="{{ asset('assets/dash/board_files/insur.png') }}" alt="Insurer Illustration">
        </div>
    </div>

    <!-- Right Side Login Form -->
    <div class="col-12 col-xl-4 form-section">
        <div class="w-px-400 mx-auto">
            <div class="authentication-bg">
                <div class="text-center my-3">
                    @if ($settings && $settings->system_logo)
                        <img src="{{ asset('storage/' . $settings->system_logo) }}" alt="Logo" class="auth-logo" />
                    @else
                        <img src="{{ asset('assets/dash/board_files/logo1.png') }}" alt="Logo" class="auth-logo" />
                    @endif
                </div>
                <p class="text-center text-white fw-bold">Insurer Login</p>

                <form method="POST" action="{{ route('insuarer.login') }}">
                    @csrf
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label text-black">Email</label>
                        <input type="email" id="email" name="email" class="form-control rounded-3" placeholder="Enter your email" required autofocus>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label text-black">Password</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control rounded-3" placeholder="••••••••••••" required>
                            <button type="button" class="btn btn-outline-light" id="togglePassword">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label text-black" for="remember">Remember Me</label>
                        </div>
                        <a href="/forgot_password" class="text-black">Forgot Password?</a>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn w-100 fw-bold text-white" style="background-color:#003153;">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    });
</script>
@endsection
