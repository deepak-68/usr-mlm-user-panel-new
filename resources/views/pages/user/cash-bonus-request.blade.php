@extends('layouts.master')
@section('title', 'Cash Bonus Request')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Alert -->
            <div class="alert alert-danger" role="alert">
                Note: Please Claim Your cash bonus reward in 30 Days Otherwise Claim Now Option Will Be Disabled From Your Portal
            </div>

            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-gift text-primary me-2"></i>CASH BONUS REQUEST
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body" style="background: #b9f6ca;">
                    <form id="cashBonusForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">Select Type</label>
                                <select name="type" id="filterType" class="form-select">
                                    <option value="all">All</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="claimed">Claimed</option>
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
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="cashBonusTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">SrNo</th>
                                    <th>UserName</th>
                                    <th>Date</th>
                                    <th>User Status</th>
                                    <th>Admin Status</th>
                                    <th>Rank</th>
                                    <th>Cash Bonus</th>
                                    <th>Remark</th>
                                    <th class="text-center">Claim</th>
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
    dataTable = $('#cashBonusTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: {
            zeroRecords: "No data available in table",
            emptyTable: "No data available in table"
        }
    });
}

function loadData(type = 'all') {
    dataTable.clear().draw();
    
    fetch(`{{ route('user.cash-bonus-request.data') }}?type=${type}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.data.forEach((item, index) => {
                    dataTable.row.add([
                        index + 1,
                        '{{ session("user_name") }}',
                        new Date(item.created_at).toLocaleDateString('en-GB'),
                        `<span class="badge bg-${item.user_status === 'active' ? 'success' : 'danger'}">${item.user_status || 'N/A'}</span>`,
                        `<span class="badge bg-${item.admin_status === 'approved' ? 'success' : item.admin_status === 'rejected' ? 'danger' : 'warning'}">${item.admin_status || 'Pending'}</span>`,
                        item.rank || '-',
                        `<strong>₹${parseFloat(item.amount).toFixed(2)}</strong>`,
                        item.remark || '-',
                        `<button class="btn btn-sm btn-success" ${item.status !== 'approved' ? 'disabled' : ''}>Claim</button>`
                    ]);
                });
                dataTable.draw();
            }
        });
}

document.getElementById('cashBonusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    loadData(document.getElementById('filterType').value);
});

document.addEventListener('DOMContentLoaded', function() {
    initializeTable();
    loadData();
});
</script>
@endpush