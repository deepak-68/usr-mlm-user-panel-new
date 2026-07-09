@extends('layouts.master')
@section('title', 'Fund Summary')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-chart-pie text-primary me-2"></i>FUND SUMMARY
                        </h4>
                    </div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="las la-arrow-down fs-2 text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-white-50 mb-1 small text-uppercase fw-semibold">Total Credit</p>
                                    <h4 class="mb-0 text-white fw-bold">₹{{ number_format($totals->credit ?? 0, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-md bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="las la-arrow-up fs-2 text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-white-50 mb-1 small text-uppercase fw-semibold">Total Debit</p>
                                    <h4 class="mb-0 text-white fw-bold">₹{{ number_format($totals->debit ?? 0, 2) }}</h4>
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
                                        <i class="las la-balance-scale fs-2 text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-white-50 mb-1 small text-uppercase fw-semibold">Net Balance</p>
                                    <h4 class="mb-0 text-white fw-bold">₹{{ number_format(($totals->credit ?? 0) - ($totals->debit ?? 0), 2) }}</h4>
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
                                        <i class="las la-hand-holding-usd fs-2 text-dark"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-dark-50 mb-1 small text-uppercase fw-semibold">Total Deductions</p>
                                    <h4 class="mb-0 text-dark fw-bold">₹{{ number_format($totals->deductions ?? 0, 2) }}</h4>
                                    <small class="text-dark-50">Admin debits & transfers</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('user.fund-summary') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">Select Type</label>
                                <select name="type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="ADMIN CREDIT" {{ request('type') == 'ADMIN CREDIT' ? 'selected' : '' }}>ADMIN CREDIT</option>
                                    <option value="ADMIN DEBIT" {{ request('type') == 'ADMIN DEBIT' ? 'selected' : '' }}>ADMIN DEBIT</option>
                                    <option value="Admin Transfer" {{ request('type') == 'Admin Transfer' ? 'selected' : '' }}>Admin Transfer</option>
                                    <option value="Credit Transfer" {{ request('type') == 'Credit Transfer' ? 'selected' : '' }}>Credit Transfer</option>
                                    <option value="Debit Transfer" {{ request('type') == 'Debit Transfer' ? 'selected' : '' }}>Debit Transfer</option>
                                    <option value="Product Purchase" {{ request('type') == 'Product Purchase' ? 'selected' : '' }}>Product Purchase</option>
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
                                    <i class="las la-search me-1"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Fund Summary Table --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="fundSummaryTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 60px;">#</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Particular</th>
                                    <th>Remark</th>
                                    <th class="text-center">Credit (₹)</th>
                                    <th class="text-center">Debit (₹)</th>
                                    <th class="text-center">Type Tag</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fundSummaries as $index => $fund)
                                @php
                                    $isDeduction = in_array($fund->type ?? '', ['ADMIN DEBIT', 'Debit Transfer']);
                                @endphp
                                <tr class="{{ $isDeduction ? 'table-danger' : ($fund->credit > 0 ? 'table-success' : '') }}">
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
                                    <td class="small">{{ $fund->remark ?? '-' }}</td>
                                    <td class="text-center">
                                        @if(($fund->credit ?? 0) > 0)
                                            <span class="text-success fw-bold">₹{{ number_format($fund->credit, 2) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(($fund->debit ?? 0) > 0)
                                            <span class="text-danger fw-bold">₹{{ number_format($fund->debit, 2) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($fund->credit > 0)
                                            <span class="badge bg-success">Credit</span>
                                        @elseif($isDeduction)
                                            <span class="badge bg-danger">Deduction</span>
                                        @elseif($fund->debit > 0)
                                            <span class="badge bg-warning text-dark">Debit</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <td colspan="5" class="text-end fw-bold">Total</td>
                                    <td class="text-center fw-bold text-success">₹{{ number_format($totals->credit ?? 0, 2) }}</td>
                                    <td class="text-center fw-bold text-danger">₹{{ number_format($totals->debit ?? 0, 2) }}</td>
                                    <td></td>
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
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
td, th { white-space: nowrap; }
.table-success { background-color: #f0fff4 !important; }
.table-danger { background-color: #fff5f5 !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    $('#fundSummaryTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries available",
            infoFiltered: "(filtered from _MAX_ total entries)",
            zeroRecords: "No matching records found",
            emptyTable: "No data available in table"
        },
        order: [[1, 'desc']],
        columnDefs: [
            { orderable: false, targets: [0, 5, 6, 7] }
        ],
        footerCallback: function(row, data, start, end, display) {
            var api = this.api();
            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[₹,]/g, '') * 1 :
                    typeof i === 'number' ? i : 0;
            };

            var creditTotal = api.column(5).data().reduce(function(a, b) {
                return intVal(a) + intVal(b);
            }, 0);
            var debitTotal = api.column(6).data().reduce(function(a, b) {
                return intVal(a) + intVal(b);
            }, 0);

            $(api.column(5).footer()).html('₹' + creditTotal.toFixed(2));
            $(api.column(6).footer()).html('₹' + debitTotal.toFixed(2));
        }
    });
});
</script>
@endpush