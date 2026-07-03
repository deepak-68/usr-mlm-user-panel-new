@extends('layouts.master')
@section('title', 'Fund History')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-history text-primary me-2"></i>FUND HISTORY
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('user.fund-history') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">Type</label>
                                <select name="type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="ADMIN CREDIT" {{ request('type') == 'ADMIN CREDIT' ? 'selected' : '' }}>ADMIN CREDIT</option>
                                    <option value="ADMIN DEBIT" {{ request('type') == 'ADMIN DEBIT' ? 'selected' : '' }}>ADMIN DEBIT</option>
                                    <option value="Credit Transfer" {{ request('type') == 'Credit Transfer' ? 'selected' : '' }}>Credit Transfer</option>
                                    <option value="Debit Transfer" {{ request('type') == 'Debit Transfer' ? 'selected' : '' }}>Debit Transfer</option>
                                </select>
                            </div>
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

            <!-- Fund History Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="fundHistoryTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Particular</th>
                                    <th>Remark</th>
                                    <th class="text-center">Credit</th>
                                    <th class="text-center">Debit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fundSummaries as $index => $fund)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        @if($fund->transaction_date)
                                            {{ \Carbon\Carbon::parse($fund->transaction_date)->format('d-m-Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td><span class="badge bg-info text-dark">{{ $fund->type ?? 'N/A' }}</span></td>
                                    <td>{{ $fund->particular ?? 'N/A' }}</td>
                                    <td>{{ $fund->remark ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($fund->credit > 0)
                                            <span class="text-success fw-bold">₹{{ number_format($fund->credit, 2) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($fund->debit > 0)
                                            <span class="text-danger fw-bold">₹{{ number_format($fund->debit, 2) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                {{-- <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="las la-history fs-1 text-muted d-block mb-3"></i>
                                        <h5 class="text-muted">No Fund History Found</h5>
                                        <p class="text-muted mb-0">No transactions available for the selected filters.</p>
                                    </td>
                                </tr> --}}
                                @endforelse
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <td colspan="5" class="text-end fw-bold">Total</td>
                                    <td class="text-center fw-bold text-success">₹{{ number_format($totals->credit ?? 0, 2) }}</td>
                                    <td class="text-center fw-bold text-danger">₹{{ number_format($totals->debit ?? 0, 2) }}</td>
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

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#fundHistoryTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[1, 'desc']]
    });
});
</script>
@endpush