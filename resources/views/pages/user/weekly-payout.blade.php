@extends('layouts.master')
@section('title', 'Weekly Payout')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-calendar-week text-primary me-2"></i>PAYOUT STATUS
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Import Button -->
            <div class="mb-3">
                <button class="btn btn-primary">
                    <i class="las la-file-excel me-1"></i>ImportToExcel
                </button>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body" style="background: #b9f6ca;">
                    <form id="payoutForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-medium">Select Closing No</label>
                                <select name="closing_no" id="closingNo" class="form-select">
                                    <option value="">All</option>
                                    <!-- Options will be loaded dynamically -->
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
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="payoutTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 40px;">#</th>
                                    <th>closing No</th>
                                    <th>User</th>
                                    <th>Dual Team Turnover Bonus</th>
                                    <th>Generation Advancement Bonus</th>
                                    <th>Direct Bonus</th>
                                    <th>Amount</th>
                                    <th>TDS(2%)</th>
                                    <th>Payout</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
let dataTable;

function initializeTable() {
    dataTable = $('#payoutTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: {
            zeroRecords: "No data available in table",
            emptyTable: "No data available in table"
        }
    });
}

function loadData(closingNo = '') {
    dataTable.clear().draw();
    
    const url = `{{ route('user.weekly-payout.data') }}?closing_no=${closingNo}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.data.forEach((item, index) => {
                    const tds = parseFloat(item.amount || 0) * 0.02;
                    const payout = parseFloat(item.amount || 0) - tds;
                    
                    dataTable.row.add([
                        index + 1,
                        `<span class="badge bg-primary">${item.closing_no || '-'}</span>`,
                        item.username || '{{ session("user_name") }}',
                        item.dual_team_bonus || '0',
                        item.generation_bonus || '0',
                        item.direct_bonus || '0',
                        `₹${parseFloat(item.amount || 0).toFixed(2)}`,
                        `₹${tds.toFixed(2)}`,
                        `₹${payout.toFixed(2)}`,
                        `<button class="btn btn-sm btn-primary">Invoice</button>`
                    ]);
                });
                dataTable.draw();
            }
        });
}

document.getElementById('payoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    loadData(document.getElementById('closingNo').value);
});

document.addEventListener('DOMContentLoaded', function() {
    initializeTable();
    loadData();
});
</script>
@endpush