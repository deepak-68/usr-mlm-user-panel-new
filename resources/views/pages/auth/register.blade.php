<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">


<head>

    <meta charset="utf-8" />
    <title>Register | User Panel</title>
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
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 2px;
        }
        .toast-success { background: #059669; }
        .toast-error { background: #dc2626; }
        .toast-warning { background: #d97706; }
    </style>

</head>

<body class="auth-bg 100-vh">
    <div class="bg-overlay bg-light"></div>

    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="mainToast" class="toast align-items-center text-white border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <div class="account-pages">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="auth-full-page-content d-flex min-vh-100 py-sm-5 py-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100 py-0 py-xl-4">

                                <div class="card my-auto overflow-hidden shadow-lg">
                                    <div class="row g-0">
                                        <div class="col-lg-12">
                                            <div class="p-lg-5 p-4">
                                                <div class="text-center mb-4">
                                                    <a href="{{ route('dashboard') }}">
                                                        <span class="logo-lg">
                                                            <img src="{{ url ('assets/images/logo.webp')}}" alt="" height="60">
                                                        </span>
                                                    </a>
                                                </div>
                                                <div class="text-center">
                                                    <h5 class="mb-0">Create Account</h5>
                                                    <p class="text-muted mt-2">Join VSR MLM today.</p>
                                                </div>

                                                <div class="mt-3">
                                                    <form id="registerForm" class="auth-input">
                                                        <div class="row g-3">
                                                            <div class="col-md-4">
                                                                <label for="user_name" class="form-label">Username <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="user_name" id="user_name" placeholder="Enter username" required>
                                                                <div class="invalid-feedback" id="user_name_error"></div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="sponsor" class="form-label">Sponsor ID <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control {{ request('sid') ? 'bg-light' : '' }}"
                                                                    name="sponsor" id="sponsor"
                                                                    value="{{ request('sid') ?? '' }}"
                                                                    placeholder="Enter sponsor ID"
                                                                    {{ request('sid') ? 'readonly' : '' }} required>
                                                                <div class="invalid-feedback" id="sponsor_error"></div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter first name" required>
                                                                <div class="invalid-feedback" id="first_name_error"></div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter last name" required>
                                                                <div class="invalid-feedback" id="last_name_error"></div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email address" required>
                                                                <div class="invalid-feedback" id="email_error"></div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter phone number" required>
                                                                <div class="invalid-feedback" id="phone_error"></div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="date_of_birth" class="form-label">Date Of Birth <span class="text-danger">*</span></label>
                                                                <input type="date" class="form-control" name="date_of_birth" id="date_of_birth" required>
                                                                <div class="invalid-feedback" id="date_of_birth_error"></div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="pan_number" class="form-label">PAN Number <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="pan_number" id="pan_number" placeholder="Enter PAN number" required>
                                                                <div class="invalid-feedback" id="pan_error"></div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="address_line_1" class="form-label">Address Line 1</label>
                                                                <input type="text" name="address_line_1" id="address_line_1" class="form-control"
                                                                    placeholder="Enter address line 1"
                                                                    value="{{ $user['detail']['address_line_1'] ?? '' }}">
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="address_line_2" class="form-label">Address Line 2</label>
                                                                <input type="text" name="address_line_2" id="address_line_2" class="form-control"
                                                                    placeholder="Enter address line 2"
                                                                    value="{{ $user['detail']['address_line_2'] ?? '' }}">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="city" class="form-label">City</label>
                                                                <input type="text" name="city" id="city" class="form-control"
                                                                    placeholder="Enter city"
                                                                    value="{{ $user['detail']['city'] ?? '' }}">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="district" class="form-label">District</label>
                                                                <input type="text" name="district" id="district" class="form-control"
                                                                    placeholder="Enter district"
                                                                    value="{{ $user['detail']['district'] ?? '' }}">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="state" class="form-label">State</label>
                                                                <input type="text" name="state" id="state" class="form-control"
                                                                    placeholder="Enter state"
                                                                    value="{{ $user['detail']['state'] ?? '' }}">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="country" class="form-label">Country</label>
                                                                <input type="text" name="country" id="country" class="form-control"
                                                                    placeholder="Enter country"
                                                                    value="{{ $user['detail']['country'] ?? 'India' }}">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="pincode" class="form-label">Pincode</label>
                                                                <input type="text" name="pincode" id="pincode" class="form-control"
                                                                    placeholder="Enter pincode"
                                                                    value="{{ $user['detail']['pincode'] ?? '' }}">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                                                <div class="position-relative auth-pass-inputgroup">
                                                                    <input type="password" class="form-control pe-5 password-input"
                                                                        name="password" id="password"
                                                                        placeholder="Enter password" required>
                                                                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button">
                                                                        <i class="las la-eye align-middle fs-18"></i>
                                                                    </button>
                                                                    <div class="invalid-feedback" id="password_error"></div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                                                <input type="password" class="form-control"
                                                                    name="password_confirmation"
                                                                    id="password_confirmation"
                                                                    placeholder="Confirm your password"
                                                                    required>
                                                                <div class="invalid-feedback" id="password_confirmation_error"></div>
                                                            </div>
                                                        </div>

                                                        <div class="mt-4">
                                                            <button class="btn btn-primary w-100" type="submit" id="registerBtn">
                                                                <span id="btnText">Create Account</span>
                                                                <span id="btnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                                                            </button>
                                                        </div>

                                                        <div class="mt-3 text-center">
                                                            <p class="mb-0">Already have an account?
                                                                <a href="{{ route('login') }}" class="fw-medium text-primary text-decoration-underline">
                                                                    Sign in
                                                                </a>
                                                            </p>
                                                        </div>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 text-center">
                                    <p class="mb-0 text-muted">©
                                        <script>document.write(new Date().getFullYear())</script> VSR - MLM <i class="mdi mdi-heart text-danger"></i> by Vibrantick Infotech Solutions
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ url('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ url('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins.js') }}"></script>
    <script src="{{ url('assets/js/pages/password-addon.init.js') }}"></script>

    <script>
        function showToast(message, type) {
            var toast = $('#mainToast');
            toast.removeClass('toast-success toast-error toast-warning');
            if (type === 'success') toast.addClass('toast-success');
            else if (type === 'error') toast.addClass('toast-error');
            else toast.addClass('toast-warning');
            $('#toastMessage').text(message);
            toast.show();
            setTimeout(function() { toast.hide(); }, 5000);
        }

        function clearErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }

        function setError(fieldId, message) {
            $('#' + fieldId).addClass('is-invalid');
            $('#' + fieldId + '_error').text(message);
        }

        $(document).ready(function() {
            var apiBase = '{{ env("API_BASE_URL", "http://127.0.0.1:8000/api") }}';

            $('#registerForm').on('submit', function(e) {
                e.preventDefault();
                clearErrors();

                var userName = $('#user_name').val().trim();
                var sponsor = $('#sponsor').val().trim();
                var firstName = $('#first_name').val().trim();
                var lastName = $('#last_name').val().trim();
                var email = $('#email').val().trim();
                var phone = $('#phone').val().trim();

                var panNumber = $('#pan_number').val().trim();
                var dateOfBirth = $('#date_of_birth').val().trim();
                var addressLine1 = $('[name="address_line_1"]').val().trim();
                var addressLine2 = $('[name="address_line_2"]').val().trim();
                var city = $('[name="city"]').val().trim();
                var district = $('[name="district"]').val().trim();
                var state = $('[name="state"]').val().trim();
                var country = $('[name="country"]').val().trim();
                var pincode = $('[name="pincode"]').val().trim();

                var password = $('#password').val();
                var confirmPassword = $('#password_confirmation').val();

                var valid = true;

                if (!userName) { setError('user_name', 'Username is required.'); valid = false; }
                if (!sponsor) { setError('sponsor', 'Sponsor is required.'); valid = false; }
                if (!firstName) { setError('first_name', 'First name is required.'); valid = false; }
                if (!lastName) { setError('last_name', 'Last name is required.'); valid = false; }
                if (!email) { 
                    setError('email', 'Email is required.'); valid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { 
                    setError('email', 'Invalid email format.'); valid = false; 
                }
                if (!phone) { 
                    setError('phone', 'Phone is required.'); valid = false; 
                }
                if (!dateOfBirth) { 
                    setError('data_of_birth', 'Date or Birth is required.'); valid = false; 
                }
                if (!panNumber) {
                    setError('pan_number', 'PAN Number is required.');
                    valid = false;
                } else if (!/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i.test(panNumber)) {
                    setError('pan_number', 'Enter a valid PAN Number.');
                    valid = false;
                }
                else if (!/^\d{10,15}$/.test(phone.replace(/[\s\-\+]/g, ''))) { setError('phone', 'Enter a valid phone number.'); valid = false; }
                if (!password) { setError('password', 'Password is required.'); valid = false; }
                else if (password.length < 6) { setError('password', 'Password must be at least 6 characters.'); valid = false; }
                if (!confirmPassword) { setError('password_confirmation', 'Please confirm your password.'); valid = false; }
                else if (password !== confirmPassword) { setError('password_confirmation', 'Passwords do not match.'); valid = false; }

                if (!valid) return;

                $('#btnText').text('Please wait...');
                $('#btnSpinner').removeClass('d-none');
                $('#registerBtn').prop('disabled', true);

                $.ajax({
                    url: apiBase + '/user-register',
                    method: 'POST',
                    data: {
                        user_name: userName,
                        sponsor: sponsor,
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        phone: phone,
                        pan_number: panNumber,
                        date_of_birth: dateOfBirth,
                        address_line_1: addressLine1,
                        address_line_2: addressLine2,
                        city: city,
                        district: district,
                        state: state,
                        country: country,
                        pincode: pincode,
                        password: password,
                        password_confirmation: confirmPassword
                    },
                    success: function(res) {
                        console.log(res);
                        if(res.status){
                            showToast(res.message || 'Registration successful! Redirecting to login...', 'success');
                            $('#registerForm')[0].reset();
                            setTimeout(function() {
                                // window.location.href = '{{ route("login") }}';
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        var msg = 'Registration failed. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errs = xhr.responseJSON.errors;
                            if (errs.user_name) setError('user_name', errs.user_name[0]);
                            if (errs.sponsor) setError('sponsor', errs.sponsor[0]);
                            if (errs.first_name) setError('first_name', errs.first_name[0]);
                            if (errs.last_name) setError('last_name', errs.last_name[0]);
                            if (errs.email) setError('email', errs.email[0]);
                            if (errs.phone) setError('phone', errs.phone[0]);
                            if (errs.password) setError('password', errs.password[0]);
                            if (errs.password_confirmation) setError('password_confirmation', errs.password_confirmation[0]);
                            if (errs.pan_number) setError('pan_number', errs.pan_number[0]);
                            if (errs.date_of_birth) setError('date_of_birth', errs.date_of_birth[0]);

                            if (errs.address_line_1)
                                $('[name="address_line_1"]').addClass('is-invalid');

                            if (errs.address_line_2)
                                $('[name="address_line_2"]').addClass('is-invalid');

                            if (errs.city)
                                $('[name="city"]').addClass('is-invalid');

                            if (errs.district)
                                $('[name="district"]').addClass('is-invalid');

                            if (errs.state)
                                $('[name="state"]').addClass('is-invalid');

                            if (errs.country)
                                $('[name="country"]').addClass('is-invalid');

                            if (errs.pincode)
                                $('[name="pincode"]').addClass('is-invalid');
                        }
                        showToast(msg, 'error');
                    },
                    complete: function() {
                        $('#btnText').text('Create Account');
                        $('#btnSpinner').addClass('d-none');
                        $('#registerBtn').prop('disabled', false);
                    }
                });
            });
        });
    </script>

</body>

</html>
