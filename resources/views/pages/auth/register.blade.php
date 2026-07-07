<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>Register | VSR MLM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ url('assets/images/favicon.ico') }}">
    <script src="{{ url('assets/js/layout.js') }}"></script>
    <link href="{{ url('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            background: linear-gradient(135deg, #284a8a 0%, #aece5b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow-x: hidden;
        }
        .auth-wrapper {
            display: flex;
            height: 100vh;
            width: 100%;
            overflow: hidden;
        }
        .auth-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: linear-gradient(135deg, #284a8a 0%, #aece5b 100%);
            height: 100vh;
            position: relative;
            overflow: hidden;
        }
        .auth-left::before {
            content: "";
            position: absolute;
            width: 280px;
            height: 280px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
            top: -70px;
            right: -70px;
        }
        .auth-left::after {
            content: "";
            position: absolute;
            width: 235px;
            height: 235px;
            background: rgba(255, 255, 255, .05);
            border-radius: 50%;
            bottom: -50px;
            left: -40px;
        }
        .auth-left-content {
            position: relative;
            z-index: 1;
            max-width: 440px;
            width: 100%;
            text-align: center;
            margin: auto;
        }
        .auth-left-content h1 {
            color: #fff;
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .auth-left-content h1 span { color: #aece5b; }
        .auth-left-content p {
            color: rgba(255,255,255,0.7);
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 30px;
        }
        .feature-list {
            text-align: left;
            display: inline-block;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            color: rgba(255,255,255,0.85);
            font-size: 0.95rem;
            margin-bottom: 16px;
            padding: 10px 18px;
            background: rgba(255,255,255,0.06);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .feature-item .icon-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
            background: rgba(174, 206, 91, 0.2);
            color: #aece5b;
        }
        .auth-right {
            flex: 1.2;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 40px;
            background: #f8fafc;
            overflow-y: auto;
            height: 100vh;
        }
        .form-card {
            width: 100%;
            /* max-width: 640px; */
            background: #fff;
            border-radius: 24px;
            padding: 40px 44px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.08);
        }
        .form-card .brand {
            text-align: center;
            margin-bottom: 28px;
        }
        .form-card .brand img { height: 52px; }
        .form-card .brand h5 {
            font-weight: 700;
            color: #0f1724;
            margin-top: 12px;
            margin-bottom: 4px;
            font-size: 1.2rem;
        }
        .form-card .brand p {
            color: #6b7280;
            font-size: 0.9rem;
            margin: 0;
        }
        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: #374151;
            margin-bottom: 4px;
        }
        .form-control {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
        }
        .form-control:focus {
            border-color: #284a8a;
            box-shadow: 0 0 0 3px rgba(40, 74, 138, 0.1);
        }
        .form-control.is-invalid {
            border-color: #dc2626;
            box-shadow: none;
        }
        .input-group-custom {
            position: relative;
        }
        .input-group-custom .form-control {
            padding-left: 40px;
        }
        .input-group-custom .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1rem;
            pointer-events: none;
        }
        .password-wrapper {
            position: relative;
        }
        .password-wrapper .form-control { padding-right: 44px; }
        .password-wrapper .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px;
            font-size: 1.1rem;
        }
        .password-wrapper .password-toggle:hover { color: #374151; }
        .btn-primary-custom {
            background: linear-gradient(135deg, #284a8a 0%, #aece5b 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 0.95rem;
            color: #fff;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-primary-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(40, 74, 138, 0.3);
            color: #fff;
        }
        .btn-primary-custom:disabled {
            opacity: 0.7;
            transform: none;
        }
        .divider-text {
            display: flex;
            align-items: center;
            color: #9ca3af;
            font-size: 0.85rem;
            gap: 12px;
            margin: 20px 0;
        }
        .divider-text::before,
        .divider-text::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }
        .form-check-input:checked {
            background-color: #284a8a;
            border-color: #284a8a;
        }
        .invalid-feedback {
            font-size: 0.78rem;
            margin-top: 3px;
        }
        .toast-container { z-index: 9999; }
        .toast-success { background: #059669 !important; }
        .toast-error { background: #dc2626 !important; }
        .toast-warning { background: #d97706 !important; }
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 2px;
        }
        .section-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #284a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        .agree-links {
            cursor: pointer;
            color: #284a8a;
            font-weight: 500;
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        .agree-links:hover { color: #aece5b; }
        .modal-content {
            border-radius: 16px;
            border: none;
        }
        .modal-header {
            border-bottom: 1px solid #e5e7eb;
            padding: 18px 24px;
        }
        .modal-body {
            padding: 24px;
            max-height: 65vh;
            overflow-y: auto;
            line-height: 1.8;
            color: #374151;
            font-size: 0.92rem;
        }
        .modal-body h1, .modal-body h2, .modal-body h3, .modal-body h4 {
            margin-top: 24px;
            margin-bottom: 12px;
            color: #0f1724;
            font-weight: 600;
        }
        .modal-body p { margin-bottom: 14px; }
        .modal-body ul, .modal-body ol { padding-left: 20px; margin-bottom: 16px; }
        .modal-footer { border-top: 1px solid #e5e7eb; padding: 14px 24px; }

        @media (max-width: 991.98px) {
            .auth-left { display: none; }
            .auth-right { padding: 24px 16px; }
            .form-card { padding: 28px 20px; border-radius: 16px; }
        }
    </style>
</head>

<body>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="mainToast" class="toast align-items-center text-white border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <div class="auth-wrapper">
        <div class="auth-left">
            <div class="auth-left-content">
                <img src="{{ url('assets/images/logo.webp') }}" alt="VSR MLM" height="70" style="margin-bottom:24px;filter:brightness(0) inv ert(1);">
                <h1>Build Your <span>Network</span> Today</h1>
                <p>Join VSR MLM and unlock your earning potential with our revolutionary compensation plan.</p>
                <div class="feature-list">
                    <div class="feature-item">
                        <div class="icon-box"><i class="las la-wallet"></i></div>
                        <span>Lucrative income plans & bonuses</span>
                    </div>
                    <div class="feature-item">
                        <div class="icon-box"><i class="las la-sitemap"></i></div>
                        <span>Powerful binary compensation structure</span>
                    </div>
                    <div class="feature-item">
                        <div class="icon-box"><i class="las la-headset"></i></div>
                        <span>24/7 dedicated support team</span>
                    </div>
                    <div class="feature-item">
                        <div class="icon-box"><i class="las la-shield-alt"></i></div>
                        <span>Secure & transparent platform</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-right">
            <div class="form-card">
                <div class="brand">
                    <img src="{{ url('assets/images/logo.webp') }}" alt="VSR MLM">
                    <h5>Create Your Account</h5>
                    <p>Fill in the details below to get started</p>
                </div>

                <form id="registerForm">
                    <div class="section-title"><i class="las la-user-circle me-1"></i> Personal Information</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="user_name" class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="las la-user input-icon"></i>
                                <input type="text" class="form-control" name="user_name" id="user_name" placeholder="Username" required>
                            </div>
                            <div class="invalid-feedback" id="user_name_error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="sponsor" class="form-label">Sponsor ID <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="las la-link input-icon"></i>
                                <input type="text" class="form-control {{ request('sid') ? 'bg-light' : '' }}"
                                    name="sponsor" id="sponsor"
                                    value="{{ request('sid') ?? '' }}"
                                    placeholder="Sponsor ID"
                                    {{ request('sid') ? 'readonly' : '' }} required>
                            </div>
                            <div class="invalid-feedback" id="sponsor_error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="las la-user-tie input-icon"></i>
                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First name" required>
                            </div>
                            <div class="invalid-feedback" id="first_name_error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last name" required>
                            <div class="invalid-feedback" id="last_name_error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="las la-envelope input-icon"></i>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email address" required>
                            </div>
                            <div class="invalid-feedback" id="email_error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="las la-phone input-icon"></i>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone number" required>
                            </div>
                            <div class="invalid-feedback" id="phone_error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="las la-calendar input-icon"></i>
                                <input type="date" class="form-control" name="date_of_birth" id="date_of_birth" required>
                            </div>
                            <div class="invalid-feedback" id="date_of_birth_error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="pan_number" class="form-label">PAN Number <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <i class="las la-id-card input-icon"></i>
                                <input type="text" class="form-control" name="pan_number" id="pan_number" placeholder="PAN number" required>
                            </div>
                            <div class="invalid-feedback" id="pan_error"></div>
                        </div>
                    </div>

                    <div class="section-title"><i class="las la-map-marker me-1"></i> Address Details</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="address_line_1" class="form-label">Address Line 1</label>
                            <div class="input-group-custom">
                                <i class="las la-home input-icon"></i>
                                <input type="text" name="address_line_1" id="address_line_1" class="form-control" placeholder="Address line 1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="address_line_2" class="form-label">Address Line 2</label>
                            <input type="text" name="address_line_2" id="address_line_2" class="form-control" placeholder="Address line 2">
                        </div>
                        <div class="col-md-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="City">
                        </div>
                        <div class="col-md-3">
                            <label for="district" class="form-label">District</label>
                            <input type="text" name="district" id="district" class="form-control" placeholder="District">
                        </div>
                        <div class="col-md-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" name="state" id="state" class="form-control" placeholder="State">
                        </div>
                        <div class="col-md-3">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="text" name="pincode" id="pincode" class="form-control" placeholder="Pincode">
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" name="country" id="country" class="form-control" placeholder="Country" value="India">
                        </div>
                    </div>

                    <div class="section-title"><i class="las la-lock me-1"></i> Security</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control password-input" name="password" id="password" placeholder="Create password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                                    <i class="las la-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="password_error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">
                                    <i class="las la-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="password_confirmation_error"></div>
                        </div>
                    </div>

                    <div class="divider-text">Agreements</div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="privacy_policy" id="privacy_policy" required>
                        <label class="form-check-label" for="privacy_policy" style="font-size:0.9rem;">
                            I have read and agree to the
                            <a class="agree-links" onclick="openModal('privacy')">Privacy Policy</a>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="invalid-feedback" id="privacy_policy_error"></div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="terms_accepted" id="terms_accepted" required>
                        <label class="form-check-label" for="terms_accepted" style="font-size:0.9rem;">
                            I have read and accept the
                            <a class="agree-links" onclick="openModal('terms')">Terms & Conditions</a>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="invalid-feedback" id="terms_accepted_error"></div>
                    </div>

                    <button class="btn btn-primary-custom w-100" type="submit" id="registerBtn">
                        <span id="btnText">Create Account</span>
                        <span id="btnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                    </button>

                    <div class="divider-text">Already have an account?</div>

                    <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100" style="border-radius:10px;padding:11px 24px;font-weight:500;">
                        <i class="las la-sign-in-alt me-1"></i> Sign In
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="las la-shield-alt me-1"></i> Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="privacyModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Loading...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms & Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="las la-file-contract me-1"></i> Terms & Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="termsModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Loading...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

    <script>
        var apiBase = '{{ env("API_BASE_URL", "http://127.0.0.1:8000/api") }}';

        function showToast(message, type) {
            var toast = $('#mainToast');
            toast.removeClass('toast-success toast-error toast-warning');
            if (type === 'success') toast.addClass('toast-success');
            else if (type === 'error') toast.addClass('toast-error');
            else toast.addClass('toast-warning');
            $('#toastMessage').text(message);
            var bsToast = new bootstrap.Toast(toast[0], { delay: 5000 });
            bsToast.show();
        }

        function clearErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }

        function setError(fieldId, message) {
            $('#' + fieldId).addClass('is-invalid');
            $('#' + fieldId + '_error').text(message);
        }

        function togglePassword(id, btn) {
            var input = document.getElementById(id);
            var icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'las la-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'las la-eye';
            }
        }

        function openModal(type) {
            var modalId = type === 'privacy' ? 'privacyModal' : 'termsModal';
            var bodyId = type === 'privacy' ? 'privacyModalBody' : 'termsModalBody';
            var apiUrl = type === 'privacy' ? apiBase + '/privacy-policy' : apiBase + '/terms-conditions';

            var modalEl = document.getElementById(modalId);
            var bodyEl = document.getElementById(bodyId);
            var bsModal = new bootstrap.Modal(modalEl);

            bodyEl.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Loading...</p></div>';
            bsModal.show();

            fetch(apiUrl)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    var content = '';
                    if (data && data.data && data.data.content) {
                        content = data.data.content;
                    } else if (data && data.content) {
                        content = data.content;
                    } else {
                        content = '<div class="alert alert-warning">Content not available.</div>';
                    }
                    bodyEl.innerHTML = content;
                })
                .catch(function() {
                    bodyEl.innerHTML = '<div class="alert alert-danger">Failed to load content. Please try again.</div>';
                });
        }

        $(document).ready(function() {
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
                if (!email) { setError('email', 'Email is required.'); valid = false; }
                else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setError('email', 'Invalid email format.'); valid = false; }
                if (!phone) { setError('phone', 'Phone is required.'); valid = false; }
                else if (!/^\d{10,15}$/.test(phone.replace(/[\s\-\+]/g, ''))) { setError('phone', 'Enter a valid phone number.'); valid = false; }
                if (!dateOfBirth) { setError('date_of_birth', 'Date of Birth is required.'); valid = false; }
                if (!panNumber) { setError('pan_number', 'PAN Number is required.'); valid = false; }
                else if (!/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i.test(panNumber)) { setError('pan_number', 'Enter a valid PAN Number.'); valid = false; }
                if (!password) { setError('password', 'Password is required.'); valid = false; }
                else if (password.length < 6) { setError('password', 'Password must be at least 6 characters.'); valid = false; }
                if (!confirmPassword) { setError('password_confirmation', 'Please confirm your password.'); valid = false; }
                else if (password !== confirmPassword) { setError('password_confirmation', 'Passwords do not match.'); valid = false; }

                if (!$('#privacy_policy').is(':checked')) {
                    $('#privacy_policy_error').text('You must accept the Privacy Policy.');
                    valid = false;
                }
                if (!$('#terms_accepted').is(':checked')) {
                    $('#terms_accepted_error').text('You must accept the Terms & Conditions.');
                    valid = false;
                }

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
                        password_confirmation: confirmPassword,
                        privacy_policy_accepted: $('#privacy_policy').is(':checked') ? 1 : 0,
                        terms_accepted: $('#terms_accepted').is(':checked') ? 1 : 0
                    },
                    success: function(res) {
                        if (res.status) {
                            showToast(res.message || 'Registration successful! Redirecting...', 'success');
                            $('#registerForm')[0].reset();
                            setTimeout(function() {
                                window.location.href = '{{ route("login") }}';
                            }, 2000);
                        } else {
                            showToast(res.message || 'Registration failed.', 'error');
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
                            if (errs.address_line_1) $('[name="address_line_1"]').addClass('is-invalid');
                            if (errs.address_line_2) $('[name="address_line_2"]').addClass('is-invalid');
                            if (errs.city) $('[name="city"]').addClass('is-invalid');
                            if (errs.district) $('[name="district"]').addClass('is-invalid');
                            if (errs.state) $('[name="state"]').addClass('is-invalid');
                            if (errs.country) $('[name="country"]').addClass('is-invalid');
                            if (errs.privacy_policy_accepted) $('#privacy_policy_error').text(errs.privacy_policy_accepted[0]);
                            if (errs.terms_accepted) $('#terms_accepted_error').text(errs.terms_accepted[0]);
                            if (errs.pincode) $('[name="pincode"]').addClass('is-invalid');
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
