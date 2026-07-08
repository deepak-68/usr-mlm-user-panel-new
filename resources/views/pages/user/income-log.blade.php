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
                                    <th class="text-center" style="width: 50px;">#</th>
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
                            <tbody id="logTableBody">
                                <tr id="loadingRow">
                                    <td colspan="9" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="text-muted mt-2 mb-0">Loading income logs...</p>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Totals:</th>
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

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small" id="tableInfo"></div>
                <nav>
                    <ul class="pagination mb-0" id="paginationLinks"></ul>
                </nav>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentPage = 1;
let lastPage = 1;

function loadLogs(page) {
    const form = document.getElementById('incomeLogForm');
    const formData = new FormData(form);

    const params = new URLSearchParams();
    params.set('income_type', document.getElementById('incomeType').value);
    params.set('from_date', document.getElementById('fromDate').value);
    params.set('to_date', document.getElementById('toDate').value);
    params.set('page', page || 1);

    document.getElementById('loadingRow').style.display = '';

    fetch('{{ route("user.income-log") }}?' + params.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(json => {
        document.getElementById('loadingRow').style.display = 'none';

        if (!json.success) {
            document.getElementById('logTableBody').innerHTML =
                '<tr><td colspan="9" class="text-center py-4 text-danger">Failed to load data.</td></tr>';
            return;
        }

        const data = json.data;
        const logs = data.data || [];
        const tbody = document.getElementById('logTableBody');
        tbody.innerHTML = '';

        if (!logs.length) {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-muted"><i class="las la-inbox fs-1 d-block mb-2"></i>No income logs found.</td></tr>';
            document.getElementById('tableInfo').textContent = '0 entries';
            document.getElementById('paginationLinks').innerHTML = '';
            document.getElementById('totalPurchaseCc').textContent = '0.00';
            document.getElementById('totalIncomeCredited').textContent = '0.00';
            document.getElementById('totalBalance').textContent = '—';
            return;
        }

        logs.forEach((log, i) => {
            const tr = document.createElement('tr');
            const fromUser = log.from_user
                ? (log.from_user.user_name || log.from_user.first_name + ' ' + (log.from_user.last_name || ''))
                : '—';
            const incomeTypeLabels = {
                'direct': 'Direct Income',
                'matching': 'Matching Income',
                'level': 'Level Income',
                'reward_tour': 'Reward & Tour',
                'repurchase': 'Repurchase Income',
                'rank': 'Rank Income',
            };

            tr.innerHTML = `
                <td class="text-center fw-bold">${(data.from || 0) + i}</td>
                <td>${new Date(log.created_at).toLocaleDateString('en-IN', {day: '2-digit', month: '2-digit', year: 'numeric'})}</td>
                <td><span class="badge bg-info text-dark">${incomeTypeLabels[log.income_type] || log.income_type}</span></td>
                <td>${fromUser}</td>
                <td>${log.order_number || '—'}</td>
                <td class="text-end">${parseFloat(log.cc_amount).toFixed(2)}</td>
                <td class="text-end fw-bold">${parseFloat(log.currency_amount).toFixed(2)}</td>
                <td class="text-end">${parseFloat(log.balance_after).toFixed(2)}</td>
                <td><small class="text-muted">${log.remarks || '—'}</small></td>
            `;
            tbody.appendChild(tr);
        });

        // Totals
        if (json.totals) {
            document.getElementById('totalPurchaseCc').textContent = parseFloat(json.totals.total_cc).toFixed(2);
            document.getElementById('totalIncomeCredited').textContent = parseFloat(json.totals.total_currency).toFixed(2);
        }

        // Pagination
        currentPage = data.current_page || 1;
        lastPage = data.last_page || 1;
        document.getElementById('tableInfo').textContent = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total || 0} entries`;

        const pagination = document.getElementById('paginationLinks');
        pagination.innerHTML = '';
        for (let p = 1; p <= lastPage; p++) {
            const li = document.createElement('li');
            li.className = `page-item ${p === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#" data-page="${p}">${p}</a>`;
            li.querySelector('a').addEventListener('click', e => {
                e.preventDefault();
                loadLogs(p);
            });
            pagination.appendChild(li);
        }
    })
    .catch(err => {
        document.getElementById('loadingRow').style.display = 'none';
        document.getElementById('logTableBody').innerHTML =
            '<tr><td colspan="9" class="text-center py-4 text-danger">Error loading data.</td></tr>';
        console.error(err);
    });
}

document.getElementById('incomeLogForm').addEventListener('submit', function(e) {
    e.preventDefault();
    loadLogs(1);
});

document.getElementById('exportCsvBtn').addEventListener('click', function () {
    const rows = document.querySelectorAll('#incomeLogTable tbody tr');
    if (!rows.length || rows[0].querySelector('td[colspan]')) {
        alert('No data to export.');
        return;
    }

    const headers = ['#', 'Date', 'Income Type', 'From User', 'Order No', 'Purchase CC', 'Income Credited', 'Balance', 'Remarks'];
    let csv = headers.join(',') + '\n';

    rows.forEach(row => {
        const cols = row.querySelectorAll('td');
        if (cols.length < 9) return;
        const rowData = [
            cols[0].innerText.trim(),
            cols[1].innerText.trim(),
            cols[2].innerText.trim().replace(/<[^>]*>/g, ''),
            cols[3].innerText.trim(),
            cols[4].innerText.trim(),
            cols[5].innerText.trim(),
            cols[6].innerText.trim(),
            cols[7].innerText.trim(),
            cols[8].innerText.trim(),
        ];
        csv += rowData.join(',') + '\n';
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'income-log-' + new Date().toISOString().slice(0, 10) + '.csv';
    link.click();
    URL.revokeObjectURL(link.href);
});

// Initial load
loadLogs(1);
</script>
@endpush
