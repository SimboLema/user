@extends('layout.auth')
@section('page-title')
    Login
@endsection

@section('content')
    <style>
        html,
        body {
            margin: 0;
            /* padding: 0; */
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

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .auth-logo {
            width: 160px;
            height: auto;
        }

        .authentication-bg {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Layout kuu: full height bila scroll desktop */
        .row.m-0 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 100vh;
            overflow: hidden;
        }

        /* Hii inahakikisha form haikatwi */
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

        .row.m-0 {
            height: 100vh;
            overflow: hidden;
        }

        /* Simu na tablet: ruhusu scroll */
        @media (max-width: 991px) {

            html,
            body {
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




    <?php use App\Models\Setting;

    $settings = Setting::first(); ?>

    {{--    <div class="d-flex col-12 col-xl-2"></div> --}}
    <div class="row m-0" style="min-height: 100vh;">

        <!-- /Left Text -->
        <div class="d-none d-xl-flex col-xl-8 p-0">
            <div class="auth-cover-bg d-flex flex-column justify-content-center align-items-center text-center">
                <h4 class="mb-2 text-white" style="font-size: 32px">
                    Welcome to {{ $settings->system_name ?? 'SURETECH Systems' }}
                </h4>
                <p class="text-white" style="font-size: 18px">Your Peace of Mind, Our Priority</p>
                <div class="img">
                    <img src="{{ asset('assets/dash/board_files/insur.png') }}">
                </div>
            </div>
        </div>

        <!-- /Left Text -->

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
                    <p class="text-center">Login</p>


                    <form class="mb-6 login-form" id="loginForm">
                        <div class="mb-6 form-control-validation">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email-username"
                                placeholder="Enter your email" autofocus />
                        </div>

                        <div class="mb-6 form-password-toggle form-control-validation">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="••••••••••••" />
                                <span class="input-group-text cursor-pointer"><i
                                        class="icon-base ti tabler-eye-off"></i></span>
                            </div>
                        </div>

                        <div class="my-8">
                            <div class="d-flex justify-content-between">
                                <div class="form-check mb-0 ms-2">
                                    <input class="form-check-input" type="checkbox" name="remember" value="true"
                                        id="rememberMe" />
                                    <label class="form-check-label" for="remember-me">Remember Me</label>
                                </div>
                                <a href="/forgot_password">
                                    <p class="mb-0" style="color: #003153;">Forgot Password?</p>
                                </a>
                            </div>
                        </div>


                        <div id="message" style="display:none; margin-bottom: 5px;"></div>

                        <button class="btn d-grid w-100"
                            style="background-color: #003153; border-color: #003153;color:white">Login</button>
                    </form>


                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            if (localStorage.getItem('rememberMe') === 'true') {
                $('#email').val(localStorage.getItem('email'));
                $('#password').val(localStorage.getItem('password'));
                $('#rememberMe').prop('checked', true);
            }

            $('#loginForm').on('submit', function(event) {
                event.preventDefault();
                handleLogin();
            });

            function handleLogin() {
                const email = $('#email').val();
                const password = $('#password').val();
                const rememberMe = $('#rememberMe').is(':checked');

                if (email === '' || password === '') {
                    showMessage('Please fill in all fields.', 'red');
                    return;
                }

                showMessage("Validating ...", 'blue');
                $.ajax({
                    url: '/validateAccount',
                    type: 'POST',
                    data: {
                        email: email,
                        password: password,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            showMessage(response.message, 'green');
                            setTimeout(() => {
                                window.location.href = response.redirect;
                            }, 3000);

                            if (rememberMe) {
                                localStorage.setItem('rememberMe', 'true');
                                localStorage.setItem('email', email);
                                localStorage.setItem('password', password);
                            } else {
                                localStorage.clear();
                            }
                        } else {
                            showMessage(response.message, 'red');
                        }
                    },
                    error: function(jqXHR) {
                        const response = jqXHR.responseJSON || {};
                        showMessage(response.message || 'An error occurred.', 'red');
                    }
                });
            }

            $('#showRoleModal').on('click', function() {
                $('#roleModal').modal('show');
            });

        });
    </script>
@endsection
