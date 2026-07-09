@extends('layouts.master')
@section('title', 'Referral Income Log')
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
                            <i class="las la-history text-primary me-2"></i>REFERRAL INCOME LOG
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form id="incomeLogForm">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">Income Type</label>
                                <select name="income_type" id="incomeType" class="form-select">
                                    <option value="all">All Types</option>
                                    <option value="direct">Direct Income</option>
                                    <option value="matching">Matching Income</option>
                                    <option value="level">Level Income</option>
                                    <option value="reward_tour">Reward & Tour</option>
                                    <option value="repurchase">Repurchase Income</option>
                                    <option value="rank">Rank Income</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">From Date</label>
                                <input type="date" name="from_date" id="fromDate" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">To Date</label>
                                <input type="date" name="to_date" id="toDate" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn w-100 text-white" style="background: #1e3a5f; border: none;">
                                    <i class="las la-search me-1"></i> Search
                                </button>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-success w-100" id="exportCsvBtn" title="Export CSV">
                                    <i class="las la-file-csv"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Income Log Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0" style="background: #b9f6ca;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="incomeLogTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Income Type</th>
                                    <th>From User</th>
                                    <th>Order No</th>
                                    <th class="text-end">Purchase CC</th>
                                    <th class="text-end">Income Credited</th>
                                    <th class="text-end">Balance</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Totals:</th>
                                    <th id="totalPurchaseCc" class="text-end">0.00</th>
                                    <th id="totalIncomeCredited" class="text-end">0.00</th>
                                    <th id="totalBalance" class="text-end">—</th>
                                    <th></th>
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
let dataTable;

function initializeTable() {
    dataTable = $('#incomeLogTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            zeroRecords: "No data available in table",
            emptyTable: "No data available in table"
        }
    });
}

function loadData() {
    dataTable.clear().draw();

    const params = new URLSearchParams();
    params.set('income_type', document.getElementById('incomeType').value);
    params.set('from_date', document.getElementById('fromDate').value);
    params.set('to_date', document.getElementById('toDate').value);
    params.set('per_page', '100000');

    fetch('{{ route("user.income-log") }}?' + params.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(json => {
        if (!json.success) return;

        let totalCc = 0, totalCurrency = 0;
        const logs = json.data.data || [];
        const incomeTypeLabels = {
            'direct': 'Direct Income',
            'matching': 'Matching Income',
            'level': 'Level Income',
            'reward_tour': 'Reward & Tour',
            'repurchase': 'Repurchase Income',
            'rank': 'Rank Income',
        };

        logs.forEach((log) => {
            const cc = parseFloat(log.cc_amount || 0);
            const currency = parseFloat(log.currency_amount || 0);
            totalCc += cc;
            totalCurrency += currency;

            const fromUser = log.from_user
                ? (log.from_user.user_name || log.from_user.first_name + ' ' + (log.from_user.last_name || ''))
                : '—';

            dataTable.row.add([
                new Date(log.created_at).toLocaleDateString('en-IN', {day: '2-digit', month: '2-digit', year: 'numeric'}),
                `<span class="badge bg-info text-dark">${incomeTypeLabels[log.income_type] || log.income_type}</span>`,
                fromUser,
                log.order_number || '—',
                cc.toFixed(2),
                `<span class="fw-bold">${currency.toFixed(2)}</span>`,
                parseFloat(log.balance_after || 0).toFixed(2),
                log.remarks || '—'
            ]);
        });

        document.getElementById('totalPurchaseCc').textContent = totalCc.toFixed(2);
        document.getElementById('totalIncomeCredited').textContent = '₹' + totalCurrency.toFixed(2);
        dataTable.draw();
    });
}

document.getElementById('incomeLogForm').addEventListener('submit', function(e) {
    e.preventDefault();
    loadData();
});

document.getElementById('exportCsvBtn').addEventListener('click', function () {
    const allData = dataTable.rows().data().toArray();
    if (!allData.length) { alert('No data to export.'); return; }

    const headers = ['Date', 'Income Type', 'From User', 'Order No', 'Purchase CC', 'Income Credited', 'Balance', 'Remarks'];
    let csv = headers.join(',') + '\n';

    allData.forEach(row => {
        const escaped = row.map(cell => '"' + String(cell).replace(/<[^>]*>/g, '').replace(/"/g, '""') + '"');
        csv += escaped.join(',') + '\n';
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'income-log-' + new Date().toISOString().slice(0, 10) + '.csv';
    link.click();
    URL.revokeObjectURL(link.href);
});

document.addEventListener('DOMContentLoaded', function() {
    initializeTable();
    loadData();
});
</script>
@endpush
