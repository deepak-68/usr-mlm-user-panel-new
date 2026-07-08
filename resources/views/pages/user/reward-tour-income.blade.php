@extends('layouts.master')
@section('title', 'Reward & Tour Income')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-trophy text-primary me-2"></i>REWARD & TOUR INCOME
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Date Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form id="rewardTourForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-medium">Date From</label>
                                <input type="date" name="date_from" id="dateFrom" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-medium">Date To</label>
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
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="rewardTourTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">SrNo</th>
                                    <th>Reward Name</th>
                                    <th>Date</th>
                                    <th class="text-center">CC Amount</th>
                                    <th class="text-center">Income Credited</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td class="text-center fw-bold" id="totalCc">0.00</td>
                                    <td class="text-center fw-bold text-success" id="totalCurrency">₹0.00</td>
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
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
let dataTable;

function initializeTable() {
    dataTable = $('#rewardTourTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[2, 'desc']],
        language: {
            zeroRecords: "No data available in table",
            emptyTable: "No data available in table"
        }
    });
}

function loadData(dateFrom = '', dateTo = '') {
    dataTable.clear().draw();

    const url = `{{ route('user.reward-tour-income.data') }}?date_from=${dateFrom}&date_to=${dateTo}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let totalCc = 0, totalCurrency = 0;

                (data.data || []).forEach((item, index) => {
                    const cc = parseFloat(item.cc_amount || 0);
                    const currency = parseFloat(item.currency_amount || 0);
                    totalCc += cc;
                    totalCurrency += currency;

                    dataTable.row.add([
                        index + 1,
                        item.remarks || 'Reward & Tour',
                        new Date(item.created_at).toLocaleDateString('en-GB'),
                        cc.toFixed(2),
                        `<span class="text-success fw-bold">₹${currency.toFixed(2)}</span>`,
                        `<span class="badge bg-success">Credited</span>`
                    ]);
                });

                document.getElementById('totalCc').textContent = totalCc.toFixed(2);
                document.getElementById('totalCurrency').textContent = '₹' + totalCurrency.toFixed(2);
                dataTable.draw();
            }
        });
}

document.getElementById('rewardTourForm').addEventListener('submit', function(e) {
    e.preventDefault();
    loadData(document.getElementById('dateFrom').value, document.getElementById('dateTo').value);
});

document.addEventListener('DOMContentLoaded', function() {
    initializeTable();
    loadData();
});
</script>
@endpush
