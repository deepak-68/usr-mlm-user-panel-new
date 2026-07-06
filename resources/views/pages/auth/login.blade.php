<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">


<head>

    <meta charset="utf-8" />
    <title>Login | User Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ url('assets/images/favicon.ico') }}')}}">

    <!-- Layout config Js -->
    <script src="{{ url('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ url('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ url('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ url('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .bg-primary-gradient{
            background: linear-gradient(135deg, #284a8a 0%, #aece5b 100%)
        }
         
        .authx-login-welcome {
            width: 100%;
            background: linear-gradient(135deg, #284a8a 0%, #aece5b 100%);
            border-radius: 20px;
            padding: 45px 35px;
            color: #fff;
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .authx-login-welcome::before {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
            top: -70px;
            right: -70px;
        }

        .authx-login-welcome::after {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, .05);
            border-radius: 50%;
            bottom: -50px;
            left: -40px;
        }

        .authx-login-content {
            position: relative;
            z-index: 2;
        }

        .authx-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, .15);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 14px;
            margin-bottom: 18px;
        }

        .authx-login-content h4 {
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 18px;
            color: white;
        }

        .authx-login-content p {
            font-size: 15px;
            line-height: 1.8;
            color: rgba(255, 255, 255, .92);
            margin-bottom: 30px;
        }

        .authx-feature-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .authx-feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 15px;
        }

        .authx-feature-item i {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        @media(max-width:991px) {

            .authx-login-welcome {
                padding: 35px 25px;
                margin-bottom: 25px;
                text-align: center;
            }

            .authx-feature-item {
                justify-content: center;
            }

            .authx-login-content h4 {
                font-size: 28px;
            }
        }

        @media(max-width:576px) {

            .authx-login-welcome {
                padding: 30px 20px;
                border-radius: 16px;
            }

            .authx-login-content h4 {
                font-size: 24px;
            }

            .authx-login-content p {
                font-size: 14px;
            }

            .authx-feature-item {
                font-size: 14px;
            }

            .authx-feature-item i {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
        }

        .auth-bg-section {
            position: relative;
            background: url("assets/images/login-bg.jpg") center center/cover no-repeat;
            min-height: 100vh;
        }

        .auth-bg-section .bg-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            /* Black with 65% opacity */
            z-index: 1;
        }

        .auth-bg-section .content {
            position: relative;
            z-index: 2;
        }
    </style>

</head>

<body class="auth-bg auth-bg-section 100-vh">
    <div class="bg-overlay bg-light"></div>

    <div class="account-pages content position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="auth-full-page-content d-flex min-vh-100 py-sm-5 py-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100 py-0 py-xl-4">



                                <div class="card my-auto overflow-hidden" style="border-radius: 20px;">
                                    <div class="row g-0">
                                        <div class="col-lg-6">
                                            <div class="p-lg-5 p-4">
                                                <div class="text-center mb-5">
                                                    <a href="{{ route('dashboard')}}>
                                                        <span class="logo-lg">
                                                            <img src="{{ url ('assets/images/logo.webp')}}" alt=""
                                                                height="60">
                                                        </span>
                                                    </a>
                                                </div>
                                                <div class="text-center">
                                                    <h5 class="mb-0">Welcome Back !</h5>
                                                    <p class="text-muted mt-2">Sign in to continue to VSR MLM.</p>
                                                </div>

                                                <div class="mt-4">
                                                    @if (session('success'))
                                                    <div class="alert alert-success alert-dismissible fade show"
                                                        role="alert">
                                                        {{ session('success')}}
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="alert"></button>
                                                    </div>
                                                    @endif

                                                    @if ($errors->any())
                                                    <div class="alert alert-danger alert-dismissible fade show"
                                                        role="alert">
                                                        <ul class="mb-0">
                                                            @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="alert"></button>
                                                    </div>
                                                    @endif
                                                    <form action="{{ route('login')}}" method="POST" class="auth-input">
                                                        @csrf

                                                        <div class="mb-3">
                                                            <label for="username" class="form-label">Username/Email/Phone</label>

                                                            <input
                                                                type="text"
                                                                class="form-control @error('username') is-invalid @enderror"
                                                                id="username"
                                                                name="username"
                                                                value="{{ old('username') }}"
                                                                placeholder="Enter username, email or phone"
                                                                required
                                                                autofocus
                                                            >

                                                            @error('username')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-2">
                                                            <label for="userpassword"
                                                                class="form-label">Password</label>
                                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                                <input type="password"
                                                                    class="form-control pe-5 password-input @error('password') is-invalid @enderror"
                                                                    placeholder="Enter password" id="password-input"
                                                                    name="password" required>
                                                                <button
                                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                                    type="button" id="password-addon">
                                                                    <i class="las la-eye align-middle fs-18"></i>
                                                                </button>
                                                                @error('password')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>  
                                                         <div class="mb-4">
                                                            <!-- Hidden input to store the token -->
                                                            <input type="hidden" name="cf-turnstile-response" id="cf-turnstile-response">
                                                            
                                                            <!-- Turnstile Widget -->
                                                            <div class="cf-turnstile" 
                                                                data-sitekey="{{ env('TURNSTILE_SITEKEY') }}" 
                                                                data-callback="onCaptchaSuccess"
                                                                data-expired-callback="onCaptchaExpired"
                                                                data-size="normal">
                                                            </div>
                                                            <x-input-error :messages="$errors->get('captcha')" class="mt-2 text-danger" />
                                                        </div>

                                                        <div class="mt-2">
                                                            <button class="btn btn-primary w-100" id="loginButton" type="submit">Log
                                                                In</button>
                                                        </div>

                                                        <div class="mt-3 text-center">
                                                            <p class="mb-0">Don't have an account? 
                                                                <a href="{{ route('register') }}" class="fw-medium text-primary text-decoration-underline">
                                                                    Sign up now
                                                                </a>
                                                            </p>
                                                        </div>

                                                         
                                                    </form>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="authx-login-welcome">
                                                <div class="authx-login-content">
                                                    <span class="authx-badge">
                                                        <i class="ri-shield-user-line"></i> Secure Login
                                                    </span>

                                                    <h4>Welcome Back!</h4>

                                                    <p>
                                                        Sign in to access your dashboard, manage your account,
                                                        track your activities, and enjoy a seamless experience.
                                                    </p>

                                                    <div class="authx-feature-list">
                                                        <div class="authx-feature-item">
                                                            <i class="ri-lock-password-line"></i>
                                                            <span>Secure Authentication</span>
                                                        </div>

                                                        <div class="authx-feature-item">
                                                            <i class="ri-dashboard-line"></i>
                                                            <span>Easy Dashboard Access</span>
                                                        </div>

                                                        <div class="authx-feature-item">
                                                            <i class="ri-notification-3-line"></i>
                                                            <span>Stay Updated Instantly</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- end card -->

                                <div class="mt-5 text-center">
                                    <p class="mb-0 text-muted " style="color: #fff">©
                                        <script>
                                            document.write(new Date().getFullYear())
                                        </script> <a href="/" class="btn btn-link p-0" style="color: #fff">VSR - MLM</a> <i
                                            class="mdi mdi-heart text-danger" ></i> by
                                        <a href="https://vibrantick.in/" class="btn btn-link p-0" target="blank" style="color: #fff"> Vibrantick
                                            Infotech Solutions</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ url('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ url('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins.js') }}"></script>

    <!-- password-addon init -->
    <script src="{{ url('assets/js/pages/password-addon.init.js') }}"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    <script>
        // ✅ Store token when CAPTCHA is solved
        function onCaptchaSuccess(token) {
            console.log('CAPTCHA Success:', token); // Debug log
            document.getElementById('cf-turnstile-response').value = token;
            document.getElementById('loginButton').disabled = false;
        }

        // ✅ Reset if token expires
        function onCaptchaExpired() {
            console.log('CAPTCHA Expired'); // Debug log
            document.getElementById('cf-turnstile-response').value = '';
            document.getElementById('loginButton').disabled = true;
            if (window.turnstile) {
                turnstile.reset();
            }
        }

        // ✅ Handle render error
        function onCaptchaError() {
            console.error('CAPTCHA Error'); // Debug log
        }

        // Password toggle
        // const togglePassword = document.getElementById("togglePassword");
        // const password = document.getElementById("password");
        // const icon = togglePassword.querySelector("i");

        // if (togglePassword && password) {
        //     togglePassword.addEventListener("click", function() {
        //         const isPassword = password.type === "password";
        //         password.type = isPassword ? "text" : "password";
        //         icon.classList.toggle("bi-eye", !isPassword);
        //         icon.classList.toggle("bi-eye-slash", isPassword);
        //     });
        // }



        // $('#loginForm').on('submit', function (e) {
        //     e.preventDefault();

        //     // Remove previous errors
        //     $('.is-invalid').removeClass('is-invalid');
        //     $('.invalid-feedback.api-error').remove();
        //     $('#loginError').addClass('d-none').html('');

        //     let btn = $('#loginBtn');
        //     let originalText = btn.html();

        //     btn.prop('disabled', true)
        //         .html('<span class="spinner-border spinner-border-sm"></span> Logging in...');

        //     $.ajax({
        //         url: "{{ config('services.api.base_url') }}", // Your API route
        //         type: "POST",
        //         data: {
        //             username: $('#username').val(),
        //             password: $('#password-input').val(),
        //             cf_turnstile_response: $('#cf-turnstile-response').val()
        //         },
        //         headers: {
        //             "Accept": "application/json"
        //         },

        //         success: function (response) {

        //             // Store token
        //             localStorage.setItem('token', response.token);

        //             // Store user
        //             localStorage.setItem('user', JSON.stringify(response.user));

        //             toastr.success(response.message);

        //             setTimeout(function () {
        //                 window.location.href = "{{ route('dashboard') }}";
        //             }, 800);
        //         },

        //         error: function (xhr) {

        //             if (xhr.status === 422) {

        //                 let errors = xhr.responseJSON.errors;

        //                 $.each(errors, function (key, value) {

        //                     let input;

        //                     if (key === 'password') {
        //                         input = $('[name="password"]');
        //                     } else if (key === 'cf_turnstile_response') {
        //                         $('#loginError')
        //                             .removeClass('d-none')
        //                             .html(value[0]);
        //                         return;
        //                     } else {
        //                         input = $('[name="' + key + '"]');
        //                     }

        //                     input.addClass('is-invalid');

        //                     input.after(
        //                         '<div class="invalid-feedback api-error">' +
        //                         value[0] +
        //                         '</div>'
        //                     );
        //                 });

        //             } else if (xhr.status === 401) {

        //                 $('#loginError')
        //                     .removeClass('d-none')
        //                     .html(xhr.responseJSON.message);

        //             } else {

        //                 $('#loginError')
        //                     .removeClass('d-none')
        //                     .html('Something went wrong. Please try again.');
        //             }

        //             // Reset Turnstile
        //             if (typeof turnstile !== 'undefined') {
        //                 turnstile.reset();
        //             }
        //         },

        //         complete: function () {
        //             btn.prop('disabled', false).html(originalText);
        //         }

        //     });
        // });
    </script>

</body>


</html>
