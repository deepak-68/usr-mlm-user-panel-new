@extends('layouts.master')
@section('title', 'Generation Income')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-chart-line text-primary me-2"></i>GENERATION INCOME
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Date Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form id="generationIncomeForm">
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
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="generationIncomeTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">SrNo</th>
                                    <th>UserName</th>
                                    <th>Date</th>
                                    <th>Particular</th>
                                    <th>Invoice No.</th>
                                    <th class="text-center">Credit</th>
                                    <th class="text-center">Debit</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <td colspan="5" class="text-end fw-bold">Total</td>
                                    <td class="text-center fw-bold text-success" id="totalCredit">₹0.00</td>
                                    <td class="text-center fw-bold text-danger" id="totalDebit">₹0.00</td>
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
    dataTable = $('#generationIncomeTable').DataTable({
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
    
    const url = `{{ route('user.generation-income.data') }}?date_from=${dateFrom}&date_to=${dateTo}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let totalCredit = 0, totalDebit = 0;
                
                data.data.forEach((item, index) => {
                    const credit = item.type === 'credit' ? parseFloat(item.amount) : 0;
                    const debit = item.type === 'debit' ? parseFloat(item.amount) : 0;
                    totalCredit += credit;
                    totalDebit += debit;
                    
                    dataTable.row.add([
                        index + 1,
                        '{{ session("user_name") }}',
                        new Date(item.created_at).toLocaleDateString('en-GB'),
                        item.particular || 'Generation Income',
                        `INV-${item.id || '000'}`,
                        item.type === 'credit' ? `<span class="text-success fw-bold">₹${credit.toFixed(2)}</span>` : '-',
                        item.type === 'debit' ? `<span class="text-danger fw-bold">₹${debit.toFixed(2)}</span>` : '-'
                    ]);
                });
                
                document.getElementById('totalCredit').textContent = '₹' + totalCredit.toFixed(2);
                document.getElementById('totalDebit').textContent = '₹' + totalDebit.toFixed(2);
                dataTable.draw();
            }
        });
}

document.getElementById('generationIncomeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    loadData(document.getElementById('dateFrom').value, document.getElementById('dateTo').value);
});

document.addEventListener('DOMContentLoaded', function() {
    initializeTable();
    loadData();
});
</script>
@endpush