@extends('layouts.master')
@section('title', 'Fund Receive List')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-inbox text-primary me-2"></i>FUND RECEIVE LIST
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('user.fund-receive-list') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">Date From</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">Date To</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
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

            <!-- Fund Receive List Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="fundReceiveTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Sender</th>
                                    <th>Amount</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transfers as $index => $transfer)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td><strong>{{ $transfer->sender_username }}</strong></td>
                                    <td><span class="badge bg-success fs-6">+₹{{ number_format($transfer->amount, 2) }}</span></td>
                                    <td>{{ $transfer->remark ?? '-' }}</td>
                                    <td>
                                        @if($transfer->status === 'completed')
                                            <span class="badge bg-success">
                                                <i class="las la-check-circle me-1"></i>Completed
                                            </span>
                                        @elseif($transfer->status === 'pending')
                                            <span class="badge bg-warning text-dark">
                                                <i class="las la-clock me-1"></i>Pending
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="las la-times-circle me-1"></i>Failed
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($transfer->created_at)->format('d-m-Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="las la-inbox fs-1 text-muted d-block mb-3"></i>
                                        <h5 class="text-muted">No Received Funds Found</h5>
                                        <p class="text-muted mb-0">You haven't received any funds yet.</p>
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
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#fundReceiveTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[5, 'desc']]
    });
});
</script>
@endpush