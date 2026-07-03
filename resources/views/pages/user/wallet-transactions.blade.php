@extends('layouts.master')
@section('title', 'Wallet Transactions')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-file-invoice-dollar text-primary me-2"></i>CHECK WALLET STATEMENT
                        </h4>
                    </div>
                </div>
            </div>

          <!-- Filters -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('user.wallet.transactions') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-muted fw-medium">Transaction Type</label>
                    <select name="type" class="form-select">
                        <option value="all">All Transactions</option>
                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit (Income)</option>
                        <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit (Withdrawal)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted fw-medium">Reference Type</label>
                    <select name="reference_type" class="form-select">
                        <option value="all">All Types</option>
                        <option value="bonus" {{ request('reference_type') == 'bonus' ? 'selected' : '' }}>Bonus</option>
                        <option value="commission" {{ request('reference_type') == 'commission' ? 'selected' : '' }}>Commission</option>
                        <option value="matching_income" {{ request('reference_type') == 'matching_income' ? 'selected' : '' }}>Matching Income</option>
                        <option value="withdrawal" {{ request('reference_type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                        <option value="fund_request" {{ request('reference_type') == 'fund_request' ? 'selected' : '' }}>Fund Request</option>
                        <option value="fund_transfer" {{ request('reference_type') == 'fund_transfer' ? 'selected' : '' }}>Fund Transfer</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted fw-medium">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted fw-medium">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn w-100 text-white" style="background: #1e3a5f; border: none;">
                        <i class="las la-search me-1"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Wallet Transactions Table -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0" style="background: #b9f6ca;">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0" id="walletTransactionTable">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 50px;">SrNo</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Reference Type</th>
                        <th>Amount</th>
                        <th>Balance After</th>
                        <th>Ref ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $transaction)
                    <tr>
                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y H:i') }}</td>
                        <td>
                            @if($transaction->type === 'credit')
                                <span class="badge bg-success">
                                    <i class="las la-arrow-down me-1"></i>Credit
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="las la-arrow-up me-1"></i>Debit
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ ucfirst(str_replace('_', ' ', $transaction->reference_type)) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($transaction->type === 'credit')
                                <span class="text-success fw-bold">₹{{ number_format($transaction->amount, 2) }}</span>
                            @else
                                <span class="text-danger fw-bold">₹{{ number_format($transaction->amount, 2) }}</span>
                            @endif
                        </td>
                        <td class="text-center fw-bold">₹{{ number_format($transaction->balance_after, 2) }}</td>
                        <td><span class="badge bg-light text-dark">{{ $transaction->reference_id ?? '-' }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="las la-wallet fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">No Transactions Found</h5>
                            <p class="text-muted mb-0">No wallet transactions available for the selected filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total</td>
                        <td class="text-center fw-bold text-success">₹{{ number_format($totals->credit ?? 0, 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total Debits</td>
                        <td class="text-center fw-bold text-danger">₹{{ number_format($totals->debit ?? 0, 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


        </div>
    </div>
</div>

@endsection



