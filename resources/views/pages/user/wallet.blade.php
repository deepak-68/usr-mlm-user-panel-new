@extends('layouts.master')
@section('title', 'My Wallet')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-wallet text-primary me-2"></i>MY WALLET
                        </h4>
                    </div>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($walletData)
            {{-- Summary Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="las la-coins fs-2 text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-white-50 mb-1 small text-uppercase fw-semibold">Lifetime CC</p>
                                    <h4 class="mb-0 text-white fw-bold">{{ number_format($walletData['total_cc'], 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="las la-rupee-sign fs-2 text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-white-50 mb-1 small text-uppercase fw-semibold">Lifetime Earnings</p>
                                    <h4 class="mb-0 text-white fw-bold">₹{{ number_format($walletData['converted_amount'], 2) }}</h4>
                                    <small class="text-white-50">1 CC = ₹{{ number_format($walletData['conversion_rate'], 2) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-info text-white shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="las la-wallet fs-2 text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-white-50 mb-1 small text-uppercase fw-semibold">Wallet Balance</p>
                                    <h4 class="mb-0 text-white fw-bold">₹{{ number_format($walletData['fund_wallet_balance'], 2) }}</h4>
                                    <small class="text-white-50">Available balance</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-dark shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="las la-history fs-2 text-dark"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-dark-50 mb-1 small text-uppercase fw-semibold">Total Withdrawn</p>
                                    <h4 class="mb-0 text-dark fw-bold">₹{{ number_format($walletData['total_withdrawn'] ?? 0, 2) }}</h4>
                                    <small class="text-dark-50">Lifetime withdrawals</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Wallet Details & Bank Account --}}
            <div class="row g-3 mb-4">
                {{-- Left: CC Breakdown --}}
                <div class="col-xl-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="las la-chart-pie me-2 text-primary"></i>CC Breakdown
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">Direct Income CC</td>
                                        <td class="text-end fw-bold text-primary">{{ number_format($walletData['direct_cc'] ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Payout/Commission CC</td>
                                        <td class="text-end fw-bold text-success">{{ number_format($walletData['payout_cc'], 2) }}</td>
                                    </tr>
                                    <tr class="border-top">
                                        <td class="fw-bold fs-5">Total Lifetime CC</td>
                                        <td class="text-end fw-bold fs-5 text-dark">{{ number_format($walletData['total_cc'], 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Right: Bank Account Management --}}
                <div class="col-xl-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">
                                <i class="las la-university me-2 text-primary"></i>My Bank Account
                            </h5>
                            @if($bankData && $bankData['id'] ?? false)
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteBank()">
                                    <i class="las la-trash me-1"></i> Delete
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($bankData && $bankData['account_holder_name'] ?? false)
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted">Account Holder</td>
                                            <td class="fw-semibold text-end">{{ $bankData['account_holder_name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Bank Name</td>
                                            <td class="fw-semibold text-end">{{ $bankData['bank_name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Account Number</td>
                                            <td class="fw-semibold text-end">{{ $bankData['account_number'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">IFSC Code</td>
                                            <td class="fw-semibold text-end">{{ $bankData['ifsc_code'] }}</td>
                                        </tr>
                                        @if($bankData['bank_attachment'] ?? false)
                                        <tr>
                                            <td class="text-muted">Attachment</td>
                                            <td class="text-end">
                                                <a href="{{ asset('storage/' . $bankData['bank_attachment']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="las la-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center py-3">
                                    <i class="las la-university fs-1 text-muted d-block mb-3"></i>
                                    <p class="text-muted mb-2">No bank account added yet.</p>
                                    <p class="text-muted small mb-3">Add your bank account to receive withdrawals.</p>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addBankForm">
                                        <i class="las la-plus me-1"></i> Add Bank Account
                                    </button>
                                </div>
                            @endif

                            {{-- Add/Edit Bank Form (collapsible) --}}
                            <div class="collapse {{ $bankData && !($bankData['account_holder_name'] ?? false) ? 'show' : '' }}" id="addBankForm">
                                <hr>
                                <form id="bankForm">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted fw-medium small">Account Holder Name</label>
                                            <input type="text" class="form-control form-control-sm" name="account_holder_name"
                                                value="{{ $bankData['account_holder_name'] ?? '' }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted fw-medium small">Bank Name</label>
                                            <input type="text" class="form-control form-control-sm" name="bank_name"
                                                value="{{ $bankData['bank_name'] ?? '' }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted fw-medium small">Account Number</label>
                                            <input type="text" class="form-control form-control-sm" name="account_number"
                                                value="{{ $bankData['account_number'] ?? '' }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted fw-medium small">IFSC Code</label>
                                            <input type="text" class="form-control form-control-sm" name="ifsc_code"
                                                value="{{ $bankData['ifsc_code'] ?? '' }}" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label text-muted fw-medium small">Bank Attachment (optional)</label>
                                            <input type="file" class="form-control form-control-sm" name="bank_attachment" accept="image/*">
                                        </div>
                                        <div class="col-12 mt-2">
                                            <button type="submit" class="btn btn-primary btn-sm px-4">
                                                <i class="las la-save me-1"></i> {{ $bankData && ($bankData['account_holder_name'] ?? false) ? 'Update' : 'Save' }}
                                            </button>
                                            @if($bankData && ($bankData['account_holder_name'] ?? false))
                                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#addBankForm">
                                                    Cancel
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Transactions --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">
                        <i class="las la-history me-2 text-primary"></i>Recent Activity
                    </h5>
                    <a href="{{ route('user.fund-summary') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Particular</th>
                                    <th>Remark</th>
                                    <th class="text-center">Credit</th>
                                    <th class="text-center">Debit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $txn)
                                <tr>
                                    <td class="small">{{ \Carbon\Carbon::parse($txn['transaction_date'] ?? $txn['created_at'])->format('d-m-Y') }}</td>
                                    <td>{{ $txn['particular'] ?? 'N/A' }}</td>
                                    <td class="small text-muted">{{ $txn['remark'] ?? '-' }}</td>
                                    <td class="text-center">
                                        @if(($txn['credit'] ?? 0) > 0)
                                            <span class="text-success fw-bold">₹{{ number_format($txn['credit'], 2) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(($txn['debit'] ?? 0) > 0)
                                            <span class="text-danger fw-bold">₹{{ number_format($txn['debit'], 2) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No recent transactions</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @else
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            <i class="las la-wallet fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">No wallet data available</h5>
                            <p class="text-muted mb-0">Unable to load wallet information at this time.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // Bank Form Submit (Add/Edit)
    $('#bankForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Saving...');

        $.ajax({
            url: '{{ route("user.bank-detail.save") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(res) {
                if (res.success) {
                    showToast('Bank details saved successfully!', 'success');
                    setTimeout(function() { location.reload(); }, 1500);
                } else {
                    showToast(res.message || 'Failed to save', 'error');
                }
            },
            error: function(xhr) {
                showToast(xhr.responseJSON?.message || 'Failed to save bank details', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="las la-save me-1"></i> Save');
            }
        });
    });

});

function confirmDeleteBank() {
    Swal.fire({
        title: 'Delete Bank Account?',
        text: 'Are you sure you want to delete your bank account details?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("user.bank-detail.delete") }}',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(res) {
                    if (res.success) {
                        showToast('Bank account deleted successfully', 'success');
                        setTimeout(function() { location.reload(); }, 1500);
                    } else {
                        showToast(res.message || 'Failed to delete', 'error');
                    }
                },
                error: function() {
                    showToast('Failed to delete bank account', 'error');
                }
            });
        }
    });
}

function showToast(message, type) {
    var bg = type === 'success' ? 'bg-success' : 'bg-danger';
    var toast = '<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">';
    toast += '<div class="toast align-items-center text-white border-0 ' + bg + '" role="alert">';
    toast += '<div class="d-flex"><div class="toast-body">' + message + '</div>';
    toast += '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>';
    toast += '</div></div></div>';
    var $toast = $(toast);
    $('body').append($toast);
    var bsToast = new bootstrap.Toast($toast.find('.toast')[0], { delay: 3000 });
    bsToast.show();
    setTimeout(function() { $toast.remove(); }, 3500);
}
</script>
@endpush