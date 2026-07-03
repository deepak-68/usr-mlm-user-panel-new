@extends('layouts.master')
@section('title', 'Fund Request Status')
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
                            <i class="las la-clipboard-list text-primary me-2"></i>FUND REQUEST STATUS
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('user.fund-request-status') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-medium">Filter by Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn w-100 text-white" style="background: #1e3a5f; border: none;">
                                    <i class="las la-filter me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Fund Request Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        {{-- @dump($fundRequests) --}}
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="fundRequestTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Bank Name</th>
                                    <th>Amount</th>
                                    <th>Mode</th>
                                    <th>Transaction No</th>
                                    <th>Date</th>
                                    <th class="text-center">Status</th>
                                    <th>Admin Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fundRequests as $index => $request)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $request->bank_detail['bank_name'] ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $request->bank_detail['mode_name'] ?? '' }}</small>
                                    </td>
                                    <td><span class="badge bg-primary fs-6">₹{{ number_format($request->amount, 2) }}</span></td>
                                    <td>{{ $request->mode_of_payment ?? 'N/A' }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $request->transaction_no ?? 'N/A' }}</span></td>
                                    <td>
                                        @if($request->deposit_date)
                                            {{ \Carbon\Carbon::parse($request->deposit_date)->format('d-m-Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($request->status === 'pending')
                                            <span class="badge bg-warning text-dark">
                                                <i class="las la-clock me-1"></i>Pending
                                            </span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge bg-success">
                                                <i class="las la-check-circle me-1"></i>Approved
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="las la-times-circle me-1"></i>Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $request->admin_remark ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="las la-clipboard-list fs-1 text-muted d-block mb-3"></i>
                                        <h5 class="text-muted">No Fund Requests Found</h5>
                                        <p class="text-muted mb-0">You haven't submitted any fund requests yet.</p>
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

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    $('#fundRequestTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[5, 'desc']],
        columnDefs: [
            { orderable: false, targets: [6] }
        ]
    });
});
</script>
@endpush