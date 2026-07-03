@extends('layouts.master')
@section('title', 'Claim Cash Request')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-donate text-primary me-2"></i>CASH BONUS
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Date Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form id="claimCashForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <input type="date" name="date_from" id="dateFrom" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="date_to" id="dateTo" class="form-control">
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
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="claimCashTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 40px;">#</th>
                                    <th>User</th>
                                    <th>Bank Name</th>
                                    <th>IFSC</th>
                                    <th>Account</th>
                                    <th>Account Holder Name</th>
                                    <th>Amount</th>
                                    <th>Payout</th>
                                    <th>Closing Date</th>
                                    <th>Remark</th>
                                    <th>Status</th>
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
    dataTable = $('#claimCashTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: {
            zeroRecords: "No data available in table",
            emptyTable: "No data available in table"
        }
    });
}

function loadData(dateFrom = '', dateTo = '') {
    dataTable.clear().draw();
    
    const url = `{{ route('user.claim-cash-request.data') }}?date_from=${dateFrom}&date_to=${dateTo}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.data.forEach((item, index) => {
                    dataTable.row.add([
                        index + 1,
                        '{{ session("user_name") }}',
                        item.bank_name || '-',
                        item.ifsc_code || '-',
                        item.account_number || '-',
                        item.account_holder_name || '-',
                        `₹${parseFloat(item.amount).toFixed(2)}`,
                        item.payout_mode || '-',
                        new Date(item.closing_date).toLocaleDateString('en-GB'),
                        item.remark || '-',
                        `<span class="badge bg-${item.status === 'approved' ? 'success' : item.status === 'rejected' ? 'danger' : 'warning'}">${item.status || 'Pending'}</span>`
                    ]);
                });
                dataTable.draw();
            }
        });
}

document.getElementById('claimCashForm').addEventListener('submit', function(e) {
    e.preventDefault();
    loadData(document.getElementById('dateFrom').value, document.getElementById('dateTo').value);
});

document.addEventListener('DOMContentLoaded', function() {
    initializeTable();
    loadData();
});
</script>
@endpush