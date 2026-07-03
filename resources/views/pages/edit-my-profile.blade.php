@extends('layouts.master')
@section("title", "Update Profile")
@section("content")
<div class="main-content">
    <div class="page-content">
        <div class="row">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">My Profile</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">VSR</a></li>
                                <li class="breadcrumb-item active">My Profile</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- @if($user && $user->profile_update_count >= 1)
                <div class="alert alert-warning">
                    <strong>(YOU UPDATED YOUR PROFILE {{ $user->profile_update_count }} TIME, SECOND TIME YOU CAN UPDATE YOUR PROFILE BY ADMIN)</strong>
                </div>
            @else
                <div class="alert alert-info">
                    <strong>Note:</strong> 
                    You can update your profile only <b>1 time</b>. Fill in carefully!
                </div>
            @endif --}}
            {{-- @dump($user) --}}

             <!-- Toast -->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                <div id="mainToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage"></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <form id="updateProfileForm" method="POST" action="#" enctype="multipart/form-data">
                    @csrf
    
                    <!-- PERSONAL DETAIL -->
                    <div class="card mb-4">
                        <div class="card-header"><h5 class="mb-0">PERSONAL DETAIL</h5></div>
                        <div class="card-body">
                            <div class="row g-3">
                                <input type="hidden" id="user_id" value="{{ $user['id'] }}">
                                <div class="col-md-4">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="{{ $user['first_name'] }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user['last_name']) }}">
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control bg-light" value="{{ $user['email'] }}" disabled readonly>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Mobile</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $user['phone'] }}">
                                </div>
                                <!-- Profile Image -->
                                {{-- <div class="col-md-4">
                                    <label class="form-label">Profile Image</label>
                                    <input type="file" name="profile_image" class="form-control">
                                </div> --}}
    
                                <!-- Date of Birth -->
                                <div class="col-md-4">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control"
                                        value="{{ $user['detail']['date_of_birth'] ?? '' }}">
                                </div>
    
                                <!-- Gender -->
                                <div class="col-md-4">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ ($user['detail']['gender'] ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ ($user['detail']['gender'] ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ ($user['detail']['gender'] ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
    
                                <!-- Father Name -->
                                <div class="col-md-4">
                                    <label class="form-label">Father Name</label>
                                    <input type="text" name="father_name" class="form-control"
                                        value="{{ $user['detail']['father_name'] ?? '' }}">
                                </div>
    
                                <!-- Mother Name -->
                                <div class="col-md-4">
                                    <label class="form-label">Mother Name</label>
                                    <input type="text" name="mother_name" class="form-control"
                                        value="{{ $user['detail']['mother_name'] ?? '' }}">
                                </div>
    
                                <!-- Address Line 1 -->
                                <div class="col-md-6">
                                    <label class="form-label">Address Line 1</label>
                                    <input type="text" name="address_line_1" class="form-control"
                                        value="{{ $user['detail']['address_line_1'] ?? '' }}">
                                </div>
    
                                <!-- Address Line 2 -->
                                <div class="col-md-6">
                                    <label class="form-label">Address Line 2</label>
                                    <input type="text" name="address_line_2" class="form-control"
                                        value="{{ $user['detail']['address_line_2'] ?? '' }}">
                                </div>
    
                                <!-- City -->
                                <div class="col-md-4">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control"
                                        value="{{ $user['detail']['city'] ?? '' }}">
                                </div>
    
                                <!-- District -->
                                <div class="col-md-4">
                                    <label class="form-label">District</label>
                                    <input type="text" name="district" class="form-control"
                                        value="{{ $user['detail']['district'] ?? '' }}">
                                </div>
    
                                <!-- State -->
                                <div class="col-md-4">
                                    <label class="form-label">State</label>
                                    <input type="text" name="state" class="form-control"
                                        value="{{ $user['detail']['state'] ?? '' }}">
                                </div>
    
                                <!-- Country -->
                                <div class="col-md-4">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="country" class="form-control"
                                        value="{{ $user['detail']['country'] ?? 'India' }}">
                                </div>
    
                                <!-- Pincode -->
                                <div class="col-md-4">
                                    <label class="form-label">Pincode</label>
                                    <input type="text" name="pincode" class="form-control"
                                        value="{{ $user['detail']['pincode'] ?? '' }}">
                                </div>
    
                                <!-- Nominee Name -->
                                <div class="col-md-4">
                                    <label class="form-label">Nominee Name</label>
                                    <input type="text" name="nominee_name" class="form-control"
                                        value="{{ $user['detail']['nominee_name'] ?? '' }}">
                                </div>
    
                                <!-- Nominee Relation -->
                                <div class="col-md-4">
                                    <label class="form-label">Nominee Relation</label>
                                    <input type="text" name="nominee_relation" class="form-control"
                                        value="{{ $user['detail']['nominee_relation'] ?? '' }}">
                                </div>
    
                              
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <input type="text" class="form-control bg-light" value="{{ $user['is_active'] == 1 ? 'Active' : 'Inactive' }}" readonly>
                                </div>
                                
                            </div>
    
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" id="updateProfileBtn" class="btn btn-primary">
                                Update Profile
                            </button>
                        </div>
                    </div>
                </form>

                <!-- ADMISSION DETAIL (Read Only) -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">ADMISSION DETAIL</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">UserName</label>
                                <input type="text" class="form-control" value="{{ $user['user_name'] }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">GW No.</label>
                                <input type="text" class="form-control" value="{{ $user['track_id'] }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sponsor ID</label>
                                <input type="text" class="form-control" value="{{ $user['sponsor']['user_name'] ?? 'N/A' }}" readonly>
                                {{-- @if($user->sponsor)
                                    <small class="text-muted">{{ $user->sponsor->first_name ?? '' }} {{ $user->sponsor->last_name ?? '' }}</small>
                                @endif --}}
                            </div>
                            {{-- <div class="col-md-4">
                                <label class="form-label">Upline ID</label>
                                <input type="text" class="form-control" value="{{ $user->upline->user_name ?? 'N/A' }}" readonly>
                                @if($user->upline)
                                    <small class="text-muted">{{ $user->upline->first_name }} {{ $user->upline->last_name }}</small>
                                @endif
                            </div> --}}
                            <div class="col-md-4">
                                <label class="form-label">Date of Joining</label>
                                <input type="text" class="form-control" value="{{ date('d-m-Y',strtotime($user['created_at'])) }}" readonly>
                            </div>
                             
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                 

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">EDIT PROFILE IMAGE</h4>
                    </div>
                    <div class="card-body">
                        <form id="updateProfileImageForm" method="POST" action="#" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row align-items-center">
                                <!-- Current Image Preview -->
                                <div class="col-md-4 text-center mb-3 mb-md-0">
                                    <div class="border p-2 rounded" style="max-width: 200px;">
                                        @if(!empty($user['detail']['profile_image']))
                                            <img src="{{ $user['detail']['profile_image'] }}"
                                                alt="Profile Image"
                                                class="img-fluid rounded"
                                                id="currentImage"
                                                style="max-height: 200px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('assets/images/user-placeholder.png') }}"
                                                alt="Default Profile"
                                                class="img-fluid rounded bg-white"
                                                id="currentImage"
                                                style="max-height: 200px; object-fit: cover;">
                                        @endif
                                    </div>
                                </div>

                                <!-- Upload Section -->
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="profile_image" class="form-label">Choose Profile Image</label>
                                        <input type="hidden" id="user_id" name="user_id" value="{{ $user['id'] }}">
                                        <input type="file" 
                                            name="profile_image" 
                                            id="profile_image" 
                                            class="form-control @error('profile_image') is-invalid @enderror"
                                            accept="image/*"
                                            onchange="previewImage(this)">
                                        @error('profile_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <strong>Note:</strong> make sure that your image size is below 2MB.
                                            <br>Allowed formats: JPG, JPEG, PNG, GIF
                                        </div>
                                    </div>

                                    <!-- New Image Preview -->
                                    <div class="mb-3" id="newImagePreview" style="display: none;">
                                        <label class="form-label">New Image Preview:</label>
                                        <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 150px;">
                                    </div>

                                    <button type="submit" id="saveProfileImageBtn" class="btn btn-primary">
                                        <i class="las la-save me-2"></i>Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">UPDATE PASSWORD</h5></div>
                    <div class="card-body">
                       <form action="{{ route('user.change-password.update') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="old_password" class="form-label">Old Password</label>
                                <input type="password" 
                                        name="old_password" 
                                        id="old_password" 
                                        class="form-control @error('old_password') is-invalid @enderror"
                                        placeholder="Old New Password"
                                        value="{{ old('old_password') }}"
                                        required>
                                @error('old_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" 
                                        name="new_password" 
                                        id="new_password" 
                                        class="form-control @error('new_password') is-invalid @enderror"
                                        placeholder="Enter New Password"
                                        required>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                {{-- <div class="form-text">
                                    Minimum 6 characters required
                                </div> --}}
                            </div>

                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" 
                                        name="new_password_confirmation" 
                                        id="new_password_confirmation" 
                                        class="form-control"
                                        placeholder="Enter Confirm Password"
                                        required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-key me-2"></i>Change Password
                            </button> 
                        </form>
                    </div>
                    
                </div>
            </div>



               

                <!-- BANK DETAIL -->
                {{-- <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">BANK DETAIL</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $user->bank_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Branch Name</label>
                                <input type="text" name="branch_name" class="form-control" value="{{ old('branch_name', $user->branch_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Account Type</label>
                                <input type="text" name="account_type" class="form-control" value="{{ old('account_type', $user->account_type) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Account Number</label>
                                <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $user->account_number) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Account Holder Name</label>
                                <input type="text" name="account_holder_name" class="form-control" value="{{ old('account_holder_name', $user->account_holder_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">IFSC Code</label>
                                <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code', $user->ifsc_code) }}">
                            </div>
                        </div>
                    </div>
                </div> --}}

                 
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        const API_BASE_URL = "{{ config('services.api.base_url') }}";
        const UPDATE_IMAGE_URL = `${API_BASE_URL}/update-image`; 

        function previewImage(input) {
            // if (input.files && input.files[0]) {

            //     let reader = new FileReader();

            //     reader.onload = function (e) {
            //         $('#previewImg').attr('src', e.target.result);
            //         $('#newImagePreview').show();
            //     };

            //     reader.readAsDataURL(input.files[0]);
            // }
        }

        $(document).ready(function () {

            $('#updateProfileForm').on('submit', function (e) {
                e.preventDefault();

                $('.invalid-feedback.ajax-error').remove();
                $('.is-invalid').removeClass('is-invalid');

                let formData = new FormData(this);

                // append user id
                formData.append('user_id', $('#user_id').val());

                $('#updateProfileBtn')
                    .prop('disabled', true)
                    .html('<i class="las la-spinner la-spin"></i> Updating...');

                $.ajax({
                    url: `${API_BASE_URL}/profile/update`,
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    processData: false,
                    contentType: false,

                    success: function (response) {

                        if (response.status) {

                            showToast(
                                response.message || 'Profile updated successfully!',
                                'success'
                            );

                        } else {

                            showToast(
                                response.message || 'Something went wrong.',
                                'error'
                            );
                        }
                    },

                    error: function (xhr) {

                        if (xhr.status === 422) {

                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function (field, messages) {

                                let input = $('[name="' + field + '"]');

                                input.addClass('is-invalid');

                                input.after(
                                    '<div class="invalid-feedback ajax-error">' +
                                    messages[0] +
                                    '</div>'
                                );

                                showToast(messages[0], 'error');
                            });

                        } else {

                            let message = xhr.responseJSON?.message ??
                                'Server error occurred.';

                            showToast(message, 'error');
                        }
                    },

                    complete: function () {

                        $('#updateProfileBtn')
                            .prop('disabled', false)
                            .html('Update Profile');
                    }
                });
            });

            $('#updateProfileImageForm').on('submit', function (e) {
                e.preventDefault();

                let imageInput = $('#profile_image')[0];
                let file = imageInput.files[0];

                $('#profile_image').removeClass('is-invalid');
                $('.invalid-feedback.ajax-error').remove();

                if (!file) {
                    showToast('Please select a profile image.', 'error');
                    return;
                }

                let allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

                if (!allowedTypes.includes(file.type)) {
                    showToast('Only JPG, JPEG, PNG and GIF files are allowed.', 'error');
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    showToast('Image size must be less than 2MB.', 'error');
                    return;
                }

                let formData = new FormData(this);


                $('#saveProfileImageBtn')
                    .prop('disabled', true)
                    .html('<i class="las la-spinner la-spin me-2"></i>Uploading...');

                $.ajax({
                    url: `${API_BASE_URL}/profile/update-image`,
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    processData: false,
                    contentType: false,

                    success: function (response) {

                        if (response.status) {

                            showToast('Profile image updated successfully!', 'success');

                            // if (response.image_url) {
                            //     $('#currentImage').attr('src', response.image_url);
                            // }

                            // $('#newImagePreview').hide();
                            // $('#profile_image').val('');

                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            showToast(
                                response.message || 'Something went wrong.',
                                'error'
                            );
                        }
                    },

                    error: function (xhr) {

                        if (xhr.status === 422) {

                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function (field, messages) {

                                $('#' + field).addClass('is-invalid');

                                $('#' + field).after(
                                    '<div class="invalid-feedback ajax-error">' +
                                    messages[0] +
                                    '</div>'
                                );

                                showToast(messages[0], 'error');
                            });

                        } else {

                            let message = xhr.responseJSON?.message ??
                                'Server error occurred. Please try again.';

                            showToast(message, 'error');
                        }
                    },

                    complete: function () {
                        $('#saveProfileImageBtn')
                            .prop('disabled', false)
                            .html('<i class="las la-save me-2"></i>Save');
                    }
                });

            });

        });
    </script>
@endpush