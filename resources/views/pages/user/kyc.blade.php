@extends('layouts.master')
@section('title', 'KYC Verification')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-id-card-alt text-primary me-2"></i>KYC VERIFICATION
                        </h4>

                        @php $status = $kycData['status'] ?? null; @endphp

                        @if($status === 'approved')
                            <span class="badge fs-13 px-3 py-2 bg-success">
                                <i class="las la-check-circle me-1"></i> KYC Approved
                            </span>
                        @elseif($status === 'pending')
                            <span class="badge fs-13 px-3 py-2 bg-warning text-dark">
                                <i class="las la-clock me-1"></i> KYC Pending Review
                            </span>
                        @elseif($status === 'rejected')
                            <span class="badge fs-13 px-3 py-2 bg-danger">
                                <i class="las la-times-circle me-1"></i> KYC Rejected
                            </span>
                        @else
                            <span class="badge fs-13 px-3 py-2 bg-secondary">
                                <i class="las la-file-alt me-1"></i> KYC Not Submitted
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Rejection Reason Alert --}}
            @if($status === 'rejected' && !empty($kycData['reject_reason']))
            <div class="alert alert-danger d-flex align-items-start gap-2 mb-4" role="alert">
                <i class="las la-exclamation-triangle fs-4 mt-1 flex-shrink-0"></i>
                <div>
                    <strong>Rejection Reason:</strong> {{ $kycData['reject_reason'] }}
                    <div class="mt-1 small text-danger">Please correct the issue and re-submit your KYC documents.</div>
                </div>
            </div>
            @endif

            {{-- Approved Banner --}}
            @if($status === 'approved')
            <div class="alert alert-success d-flex align-items-start gap-2 mb-4" role="alert">
                <i class="las la-check-circle fs-4 mt-1 flex-shrink-0"></i>
                <div>
                    <strong>Your KYC is Verified!</strong>
                    <div class="mt-1 small">Your identity has been successfully verified. No further action is required.</div>
                </div>
            </div>
            @endif

            {{-- AJAX Alert Container --}}
            <div id="kycAlert" class="d-none mb-4"></div>

            {{-- KYC Form Card --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-semibold text-dark">
                        <i class="las la-clipboard-check text-primary me-2"></i>
                        {{ $status === 'approved' ? 'KYC Information' : 'Submit KYC Documents' }}
                    </h5>
                </div>
                <div class="card-body">
                    {{-- action + data-submit-url both set so AJAX reads from data attribute, not from Blade inside JS --}}
                    <form id="kycForm"
                          action="{{ route('user.kyc.submit') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          data-submit-url="{{ route('user.kyc.submit') }}"
                          novalidate>
                        @csrf

                        {{-- Identity Details --}}
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted fw-bold small mb-3 border-bottom pb-2">
                                <i class="las la-user me-1"></i>Identity Details
                            </h6>
                            <div class="row g-3">

                                <div class="col-md-6 col-lg-4">
                                    <label for="pan_number" class="form-label fw-medium text-muted">
                                        PAN Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           id="pan_number"
                                           name="pan_number"
                                           class="form-control text-uppercase"
                                           placeholder="e.g. ABCDE1234F"
                                           maxlength="10"
                                           value="{{ strtoupper($kycData['pan_number'] ?? '') }}"
                                           {{ $status === 'approved' ? 'readonly' : '' }}
                                           required>
                                    <div class="invalid-feedback">Please enter a valid 10-character PAN number.</div>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="aadhaar_number" class="form-label fw-medium text-muted">
                                        Aadhaar Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           id="aadhaar_number"
                                           name="aadhaar_number"
                                           class="form-control"
                                           placeholder="e.g. 1234 5678 9012"
                                           maxlength="14"
                                           value="{{ $kycData['aadhaar_number'] ?? '' }}"
                                           {{ $status === 'approved' ? 'readonly' : '' }}
                                           required>
                                    <div class="invalid-feedback">Please enter a valid 12-digit Aadhaar number.</div>
                                </div>

                            </div>
                        </div>

                        {{-- Document Uploads --}}
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted fw-bold small mb-3 border-bottom pb-2">
                                <i class="las la-file-image me-1"></i>Document Uploads
                                @if($status !== 'approved')
                                    <span class="text-danger ms-1">*</span>
                                @endif
                            </h6>

                            <div class="row g-4">
                                @php
                                    $isApproved = $status === 'approved';
                                    $uploadDocs = [
                                        ['id' => 'pan_image',           'label' => 'PAN Card Image',               'icon' => 'las la-id-card',       'existing' => $kycData['pan_image'] ?? ''],
                                        ['id' => 'aadhaar_front_image', 'label' => 'Aadhaar Front',                'icon' => 'las la-address-card',   'existing' => $kycData['aadhaar_front_image'] ?? ''],
                                        ['id' => 'aadhaar_back_image',  'label' => 'Aadhaar Back',                 'icon' => 'las la-address-card',   'existing' => $kycData['aadhaar_back_image'] ?? ''],
                                        ['id' => 'bank_document_image', 'label' => 'Cancelled Cheque / Passbook', 'icon' => 'las la-university',     'existing' => $kycData['bank_document_image'] ?? ''],
                                    ];
                                @endphp

                                @foreach($uploadDocs as $doc)
                                @php $hasExisting = !empty($doc['existing']); @endphp
                                <div class="col-md-6 col-lg-3">
                                    <div class="kyc-upload-card {{ $hasExisting ? 'has-preview' : '' }} {{ $isApproved ? 'approved' : '' }}"
                                         data-field="{{ $doc['id'] }}"
                                         data-label="{{ $doc['label'] }}"
                                         data-icon="{{ $doc['icon'] }}">

                                        @if(!$isApproved)
                                        <input type="file"
                                               id="{{ $doc['id'] }}"
                                               name="{{ $doc['id'] }}"
                                               class="kyc-file-input"
                                               accept=".jpg,.jpeg,.png,.gif,.pdf">
                                        @endif

                                        <label class="form-label fw-medium text-muted small mb-1 d-block px-2 pt-2">
                                            {{ $doc['label'] }}
                                            @if(!$isApproved)<span class="text-danger">*</span>@endif
                                        </label>

                                        <div class="upload-placeholder">
                                            @if($hasExisting)
                                                @php $isPdf = str_ends_with(strtolower($doc['existing']), '.pdf'); @endphp
                                                <div class="position-relative w-100 p-2">
                                                    @if($isPdf)
                                                        <div class="text-center py-2">
                                                            <i class="las la-file-pdf text-danger" style="font-size:3rem;"></i>
                                                            <div class="text-muted small mt-1">PDF Document</div>
                                                        </div>
                                                    @else
                                                        <div class="kyc-img-wrap">
                                                            <img src="{{ $doc['existing'] }}"
                                                                 class="kyc-preview-img"
                                                                 alt="{{ $doc['label'] }}"
                                                                 title="Click to enlarge">
                                                        </div>
                                                    @endif
                                                    @if(!$isApproved)
                                                    <button type="button" class="btn btn-danger btn-sm btn-remove-img" title="Remove">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                    @endif
                                                    <div class="text-success small text-center mt-2 fw-medium">
                                                        <i class="las la-check-circle me-1"></i>{{ $isApproved ? 'Verified' : 'Uploaded' }}
                                                    </div>
                                                </div>
                                            @else
                                                <i class="{{ $doc['icon'] }} upload-icon"></i>
                                                <span class="fw-medium small text-dark">{{ $doc['label'] }}</span>
                                                <span class="text-muted" style="font-size:11px;">Click to browse</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <p class="text-muted small mt-3 mb-0">
                                <i class="las la-info-circle me-1"></i>
                                Accepted formats: JPG, PNG, PDF. Max size: 2 MB per file.
                            </p>
                        </div>

                        {{-- Submit Button --}}
                        @if($status !== 'approved')
                        <div class="d-flex align-items-center gap-3 mt-3">
                            <button type="submit" id="kycSubmitBtn"
                                    class="btn btn-primary px-5"
                                    style="background:#1e3a5f; border:none;">
                                <span id="kycBtnText">
                                    <i class="las la-paper-plane me-2"></i>
                                    {{ $status === 'rejected' ? 'Re-Submit KYC' : 'Submit KYC' }}
                                </span>
                                <span id="kycBtnSpinner" class="d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Submitting...
                                </span>
                            </button>
                            <small class="text-muted">
                                <i class="las la-shield-alt me-1 text-success"></i>
                                Your data is encrypted and secure.
                            </small>
                        </div>
                        @endif

                    </form>
                </div>
            </div>

            {{-- Inline submit handler — runs immediately after form is in DOM, no jQuery ready needed --}}
            <script>
            (function() {
                // Poll until the form exists (handles any edge case)
                var pollInterval = setInterval(function() {
                    var form = document.getElementById('kycForm');
                    if (!form) return;
                    clearInterval(pollInterval);

                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        e.stopImmediatePropagation();

                        var pan     = document.getElementById('pan_number') ? document.getElementById('pan_number').value.trim() : '';
                        var aadhaar = document.getElementById('aadhaar_number') ? document.getElementById('aadhaar_number').value.replace(/\s/g, '') : '';
                        var url     = form.getAttribute('data-submit-url') || form.getAttribute('action');
                        var csrf    = (document.querySelector('meta[name="csrf-token"]') || {}).content
                                   || (document.querySelector('input[name="_token"]') || {}).value || '';

                        // Simple validation
                        if (!/^[A-Z]{5}[0-9]{4}[A-Z]$/.test(pan)) {
                            kycShowAlert('warning', 'Please enter a valid PAN number (e.g. ABCDE1234F).');
                            return false;
                        }
                        if (!/^\d{12}$/.test(aadhaar)) {
                            kycShowAlert('warning', 'Please enter a valid 12-digit Aadhaar number.');
                            return false;
                        }

                        kycSetLoading(true);

                        // Strip spaces from Aadhaar before building FormData
                        var aadhaarField = document.getElementById('aadhaar_number');
                        if (aadhaarField) {
                            aadhaarField.value = aadhaar; // already stripped above
                        }

                        var formData = new FormData(form);

                        // Restore display-formatted value after FormData is built
                        if (aadhaarField) {
                            aadhaarField.value = aadhaar.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
                        }

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', url, true);
                        xhr.setRequestHeader('X-CSRF-TOKEN', csrf);
                        xhr.setRequestHeader('Accept', 'application/json');

                        xhr.onreadystatechange = function() {
                            if (xhr.readyState !== 4) return;
                            kycSetLoading(false);
                            try {
                                var res = JSON.parse(xhr.responseText);
                                if (xhr.status >= 200 && xhr.status < 300 && res.success) {
                                    kycShowAlert('success', res.message || 'KYC submitted successfully!');
                                } else {
                                    var msg = (res && res.message) ? res.message : 'Submission failed. Please try again.';
                                    if (res && res.errors) {
                                        Object.values(res.errors).forEach(function(v) { msg += ' ' + (Array.isArray(v) ? v.join(' ') : v); });
                                    }
                                    kycShowAlert('danger', msg);
                                }
                            } catch(ex) {
                                kycShowAlert('danger', xhr.status === 419 ? 'Session expired. Please refresh and try again.' : 'An error occurred. Please try again.');
                            }
                        };

                        xhr.send(formData);
                        return false;
                    });
                }, 50);

                function kycSetLoading(on) {
                    var btnText    = document.getElementById('kycBtnText');
                    var btnSpinner = document.getElementById('kycBtnSpinner');
                    var btn        = document.getElementById('kycSubmitBtn');
                    if (!btn) return;
                    if (on) {
                        if (btnText)    btnText.classList.add('d-none');
                        if (btnSpinner) btnSpinner.classList.remove('d-none');
                        btn.disabled = true;
                    } else {
                        if (btnText)    btnText.classList.remove('d-none');
                        if (btnSpinner) btnSpinner.classList.add('d-none');
                        btn.disabled = false;
                    }
                }

                function kycShowAlert(type, msg) {
                    var el = document.getElementById('kycAlert');
                    if (!el) return;
                    el.className = 'alert alert-' + type + ' alert-dismissible fade show mb-4';
                    el.innerHTML = msg + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                }
            })();
            </script>

            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3 text-dark">
                        <i class="las la-lightbulb text-warning me-2"></i>KYC Guidelines
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2 d-flex gap-2">
                                    <i class="las la-check-circle text-success mt-1 flex-shrink-0"></i>
                                    <span>Upload clear, readable scans or photos of original documents.</span>
                                </li>
                                <li class="mb-2 d-flex gap-2">
                                    <i class="las la-check-circle text-success mt-1 flex-shrink-0"></i>
                                    <span>Ensure all 4 corners of documents are visible in images.</span>
                                </li>
                                <li class="mb-2 d-flex gap-2">
                                    <i class="las la-check-circle text-success mt-1 flex-shrink-0"></i>
                                    <span>PAN card must match the name registered in your profile.</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2 d-flex gap-2">
                                    <i class="las la-times-circle text-danger mt-1 flex-shrink-0"></i>
                                    <span>Blurred, cropped, or edited images will be rejected.</span>
                                </li>
                                <li class="mb-2 d-flex gap-2">
                                    <i class="las la-times-circle text-danger mt-1 flex-shrink-0"></i>
                                    <span>Do not upload password-protected PDF files.</span>
                                </li>
                                <li class="mb-2 d-flex gap-2">
                                    <i class="las la-times-circle text-danger mt-1 flex-shrink-0"></i>
                                    <span>Each file must not exceed 2 MB in size.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Image Preview Modal --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-semibold" id="previewModalLabel">Document Preview</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="previewModalImg" src="" alt="Preview" class="img-fluid rounded" style="max-height:75vh; max-width:100%;">
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .page-title-box {
        background: #fff;
        padding: 16px 20px;
        border-radius: 8px;
    }
    .kyc-upload-card {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        background: #fafbfc;
        transition: border-color 0.2s, background 0.2s;
        min-height: 185px;
        overflow: hidden;
    }
    .kyc-upload-card:not(.approved):hover {
        border-color: #1e3a5f;
        background: #f0f4ff;
    }
    .kyc-upload-card.has-preview {
        border: 2px solid #198754;
        background: #f0fff5;
    }
    .kyc-upload-card.approved {
        border: 2px solid #0d6efd;
        background: #f0f7ff;
    }
    .kyc-upload-card .upload-placeholder {
        cursor: pointer;
        padding: 20px 12px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 155px;
    }
    .kyc-upload-card.approved .upload-placeholder {
        cursor: default;
    }
    .kyc-upload-card .upload-icon {
        font-size: 2.4rem;
        color: #9ca3af;
        line-height: 1;
    }
    .kyc-img-wrap {
        width: 100%;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 6px;
        background: #f3f4f6;
    }
    .kyc-preview-img {
        max-width: 100%;
        max-height: 120px;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 6px;
        cursor: zoom-in;
        display: block;
    }
    .btn-remove-img {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        font-size: 11px;
        padding: 0;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }
    .kyc-file-input { display: none; }
</style>
@endpush

@push('scripts')
<script> 
$(function () {

    var $form      = $('#kycForm');
    var SUBMIT_URL = $form.data('submit-url') || $form.attr('action');

    if (!SUBMIT_URL) {
        console.error('KYC: submit URL not found on form');
    }

    $(document).on('click', '.upload-placeholder', function () {
        var $card = $(this).closest('.kyc-upload-card');
        if ($card.hasClass('approved')) return;
        $card.find('.kyc-file-input').trigger('click');
    });

    $(document).on('click', '.btn-remove-img', function (e) {
        e.stopPropagation();
        var $card = $(this).closest('.kyc-upload-card');
        resetUploadCard($card, $card.data('field'));
    });

    $(document).on('change', '.kyc-file-input', function () {
        var file  = this.files[0];
        var $card = $(this).closest('.kyc-upload-card');
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            showAlert('danger', '<strong>' + $card.data('label') + ':</strong> File size must not exceed 2 MB.');
            $(this).val('');
            return;
        }

        var allowed = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        if ($.inArray(file.type, allowed) === -1) {
            showAlert('danger', '<strong>' + $card.data('label') + ':</strong> Only JPG, PNG, GIF or PDF allowed.');
            $(this).val('');
            return;
        }

        if (file.type === 'application/pdf') {
            renderPdfCard($card, file.name);
        } else {
            var reader = new FileReader();
            reader.onload = function (ev) { renderImageCard($card, ev.target.result); };
            reader.readAsDataURL(file);
        }
    });

    $(document).on('click', '.kyc-preview-img', function (e) {
        e.stopPropagation();
        $('#previewModalLabel').text($(this).closest('.kyc-upload-card').data('label'));
        $('#previewModalImg').attr('src', $(this).attr('src'));
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    });

    $(document).on('input', '#pan_number', function () {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });

    $(document).on('input', '#aadhaar_number', function () {
        var raw = this.value.replace(/\D/g, '').substring(0, 12);
        this.value = raw.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
    });

    $form.on('submit', function (e) {
        e.preventDefault();
        return false;
    });


    function validateFields() {
        var pan     = $('#pan_number').val().trim();
        var aadhaar = $('#aadhaar_number').val().replace(/\s/g, '');

        if (!/^[A-Z]{5}[0-9]{4}[A-Z]$/.test(pan)) {
            showAlert('warning', 'Please enter a valid PAN number (e.g. ABCDE1234F).');
            $('#pan_number').focus();
            return false;
        }

        if (!/^\d{12}$/.test(aadhaar)) {
            showAlert('warning', 'Please enter a valid 12-digit Aadhaar number.');
            $('#aadhaar_number').focus();
            return false;
        }

        return true;
    }

    function renderImageCard($card, src) {
        $card.addClass('has-preview');
        $card.find('.upload-placeholder').html(
            '<div class="position-relative w-100 p-2">' +
                '<div class="kyc-img-wrap">' +
                    '<img src="' + src + '" class="kyc-preview-img" alt="Preview" title="Click to enlarge">' +
                '</div>' +
                '<button type="button" class="btn btn-danger btn-sm btn-remove-img" title="Remove">' +
                    '<i class="las la-times"></i>' +
                '</button>' +
                '<div class="text-success small text-center mt-2 fw-medium">' +
                    '<i class="las la-check-circle me-1"></i>Ready to upload' +
                '</div>' +
            '</div>'
        );
    }

    function renderPdfCard($card, filename) {
        $card.addClass('has-preview');
        $card.find('.upload-placeholder').html(
            '<div class="position-relative w-100 p-3 text-center">' +
                '<i class="las la-file-pdf text-danger" style="font-size:3rem;"></i>' +
                '<button type="button" class="btn btn-danger btn-sm btn-remove-img" title="Remove">' +
                    '<i class="las la-times"></i>' +
                '</button>' +
                '<div class="text-muted small mt-2 text-truncate px-3" title="' + filename + '">' + filename + '</div>' +
                '<div class="text-success small fw-medium mt-1">' +
                    '<i class="las la-check-circle me-1"></i>Ready to upload' +
                '</div>' +
            '</div>'
        );
    }

    function resetUploadCard($card, fieldId) {
        $card.removeClass('has-preview');
        $card.find('#' + fieldId).val('');
        $card.find('.upload-placeholder').html(
            '<i class="' + ($card.data('icon') || 'las la-cloud-upload-alt') + ' upload-icon"></i>' +
            '<span class="fw-medium small text-dark">' + ($card.data('label') || 'Upload Document') + '</span>' +
            '<span class="text-muted" style="font-size:11px;">Click to browse</span>'
        );
    }

    function setLoading(on) {
        if (on) {
            $('#kycBtnText').addClass('d-none');
            $('#kycBtnSpinner').removeClass('d-none');
            $('#kycSubmitBtn').prop('disabled', true);
        } else {
            $('#kycBtnText').removeClass('d-none');
            $('#kycBtnSpinner').addClass('d-none');
            $('#kycSubmitBtn').prop('disabled', false);
        }
    }

    function showAlert(type, msg) {
        $('#kycAlert')
            .removeClass('d-none alert-success alert-danger alert-warning alert-info')
            .addClass('alert alert-' + type + ' alert-dismissible fade show')
            .html('<i class="las la-' + (type === 'success' ? 'check' : 'exclamation-triangle') + '-circle me-1"></i>' +
                  msg +
                  '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
    }

    function hideAlert() {
        $('#kycAlert')
            .addClass('d-none')
            .removeClass('alert alert-success alert-danger alert-warning alert-info')
            .html('');
    }

}); // end $(function)
</script>
@endpush
