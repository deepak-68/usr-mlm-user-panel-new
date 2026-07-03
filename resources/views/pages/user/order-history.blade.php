@extends('layouts.master')
@section('title', 'Order History')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-file-alt text-primary me-2"></i>ORDER SUMMARY
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('user.order-history') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">Select Type</label>
                                <select name="type" class="form-select">
                                    <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All</option>
                                    <option value="completed" {{ request('type') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="pending" {{ request('type') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="cancelled" {{ request('type') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
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

            <!-- Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0" style="background: #b9f6ca;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="orderTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">S.No</th>
                                    <th>Order No</th>
                                    <th>Total D.P</th>
                                    <th>Payable Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Order Type</th>
                                    <th>Payment Mode</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders['data'] as $index => $order)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge bg-primary">#{{ $order['order_no'] ?? $order['id'] ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <strong>₹{{ number_format($order['total_amount'] ?? 0, 2) }}</strong>
                                    </td>
                                    <td>
                                        <strong>₹{{ number_format($order['total_amount'] ?? 0, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($order['created_at'] ?? false)
                                            {{ \Carbon\Carbon::parse($order['created_at'])->format('d-m-Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if(($order['status'] ?? '') === 'COMPLETED')
                                            <span class="badge bg-success">
                                                <i class="las la-check-circle me-1"></i>Completed
                                            </span>
                                        @elseif(($order['status'] ?? '') === 'PENDING')
                                            <span class="badge bg-warning text-dark">
                                                <i class="las la-clock me-1"></i>Pending
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="las la-times-circle me-1"></i>{{ $order['status'] ?? 'N/A' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $order['order_type'] ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ strtoupper($order['payment_mode'] ?? 'N/A') }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="las la-file-alt fs-1 text-muted d-block mb-3"></i>
                                        <h5 class="text-muted">No Orders Found</h5>
                                        <p class="text-muted mb-0">You haven't placed any orders yet.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

