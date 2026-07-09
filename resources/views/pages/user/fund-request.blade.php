@extends('layouts.master')
@section('title', 'Fund Request')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-hand-holding-usd text-primary me-2"></i>FUND REQUEST
                        </h4>
                    </div>
                </div>
            </div>

            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                <div id="mainToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage"></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>

            @if(!$userBank)
            <div class="alert alert-warning">
                <i class="las la-exclamation-triangle me-2"></i>
                You must <a href="{{ route('user.wallet') }}" class="alert-link">add your bank account</a> first before making a withdrawal request.
            </div>
            @else
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form id="withdrawalForm">
                        @csrf
                        <input type="hidden" name="type" value="withdrawal">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-medium">Your Bank Account</label>
                                <input type="hidden" name="user_bank_id" value="{{ $userBank['id'] }}">
                                <div class="p-3 bg-light rounded">
                                    <p class="mb-1 fw-semibold">{{ $userBank['account_holder_name'] }}</p>
                                    <p class="mb-1 small">{{ $userBank['bank_name'] }} - {{ $userBank['account_number'] }}</p>
                                    <p class="mb-0 small text-muted">IFSC: {{ $userBank['ifsc_code'] }}</p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-medium">Withdrawal Amount (₹)</label>
                                <input type="number" class="form-control" name="amount" placeholder="Enter amount" required min="1" step="0.01">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-medium">Mode of Payment</label>
                                <select class="form-select" name="mode_of_payment" required>
                                    <option value="">Select</option>
                                    <option value="IMPS">IMPS</option>
                                    <option value="NEFT">NEFT</option>
                                    <option value="RTGS">RTGS</option>
                                    <option value="UPI">UPI</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-medium">Remark (optional)</label>
                                <input type="text" class="form-control" name="remark" placeholder="Any remarks">
                            </div>

                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary px-4" style="background: #1e3a5f; border: none;">
                                    <i class="las la-paper-plane me-2"></i>Submit Withdrawal Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let bankDetails = [];

function showToast(message, type) {
    var toast = document.getElementById('mainToast');
    var toastMessage = document.getElementById('toastMessage');
    toast.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info');
    toast.classList.add(type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-warning');
    toastMessage.textContent = message;
    new bootstrap.Toast(toast, { delay: 4000 }).show();
}

// Withdrawal form submit
document.getElementById('withdrawalForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Submitting...';

    fetch('{{ route("user.fund-request.submit") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Withdrawal request submitted successfully!', 'success');
            this.reset();
        } else {
            showToast(data.message || 'Failed to submit', 'error');
        }
    })
    .catch(() => showToast('An error occurred', 'error'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="las la-paper-plane me-2"></i>Submit Withdrawal Request';
    });
});


</script>
@endpush