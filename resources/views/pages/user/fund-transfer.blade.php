@extends('layouts.master')
@section('title', 'Fund Transfer')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Toast Notification -->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                <div id="mainToast" class="toast align-items-center text-white border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage"></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>

            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-exchange-alt text-primary me-2"></i>FUND TRANSFER
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Wallet Balance Card -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 opacity-75">Available Balance</p>
                                    <h3 class="mb-0 fw-bold" id="walletBalance">₹0.00</h3>
                                </div>
                                <div>
                                    <i class="las la-wallet fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transfer Form -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form id="fundTransferForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Receiver Username -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-medium">Receiver Username</label>
                                    <input type="text" class="form-control" name="receiver_username" 
                                           placeholder="Enter receiver's username" required>
                                </div>

                                <!-- Amount -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-medium">Amount</label>
                                    <input type="number" class="form-control" name="amount" 
                                           placeholder="Enter amount" required min="1" step="0.01">
                                </div>

                                <!-- Transaction Password -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-medium">Transaction Password</label>
                                    <input type="password" class="form-control" name="transaction_password" 
                                           placeholder="Enter transaction password" required>
                                    <small class="text-muted">Your transaction password from your profile</small>
                                </div>

                                <!-- Remark -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-medium">Remark (Optional)</label>
                                    <textarea class="form-control" name="remark" rows="3" 
                                              placeholder="Enter remark"></textarea>
                                </div>

                                <!-- Submit Button -->
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4" 
                                            style="background: #1e3a5f; border: none;">
                                        <i class="las la-paper-plane me-2"></i>Transfer Fund
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Toast function
function showToast(message, type = 'success') {
    const toast = document.getElementById('mainToast');
    const toastMessage = document.getElementById('toastMessage');
    
    toast.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info');
    
    switch(type) {
        case 'success': toast.classList.add('bg-success'); break;
        case 'error': toast.classList.add('bg-danger'); break;
        case 'warning': toast.classList.add('bg-warning'); break;
        default: toast.classList.add('bg-info');
    }
    
    toastMessage.textContent = message;
    new bootstrap.Toast(toast, { delay: 4000 }).show();
}

// Load wallet balance on page load
document.addEventListener('DOMContentLoaded', function() {
    loadWalletBalance();
});

function loadWalletBalance() {
    fetch('{{ route("user.wallet-balance") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('walletBalance').textContent = '₹' + parseFloat(data.balance).toFixed(2);
            }
        })
        .catch(error => {
            console.error('Error loading balance:', error);
        });
}

// Form submission
document.getElementById('fundTransferForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Processing...';
    
    fetch('{{ route("user.fund-transfer.transfer") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Fund transferred successfully!', 'success');
            document.getElementById('fundTransferForm').reset();
            loadWalletBalance(); // Refresh balance
        } else {
            showToast(data.message || 'Transfer failed', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="las la-paper-plane me-2"></i>Transfer Fund';
    });
});
</script>
@endpush