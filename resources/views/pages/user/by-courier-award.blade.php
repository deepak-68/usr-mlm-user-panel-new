@extends('layouts.master')
@section('title', 'By Courier Award/Reward List')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-box-open text-primary me-2"></i>BY COURIER AWARD/REWARD LIST
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form id="courierAwardForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">Select Type</label>
                                <select name="type" id="filterType" class="form-select">
                                    <option value="all">All</option>
                                    <option value="pending">Pending</option>
                                    <option value="delivered">Delivered</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn w-100 text-white" style="background: #1e3a5f; border: none;">
                                    <i class="las la-search me-1"></i> Search
                                </button>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">Status</label>
                                <select name="status" id="filterStatus" class="form-select">
                                    <option value="">Select</option>
                                    <option value="pending">Pending</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn w-100 text-white" style="background: #1e3a5f; border: none;" onclick="loadDataWithStatus()">
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
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="courierAwardTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 40px;">#</th>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>OrderDate</th>
                                    <th>Status</th>
                                    <th>Delivery Status</th>
                                    <th>Date Of Supply</th>
                                    <th>Delivery By</th>
                                    <th>Tracking ID</th>
                                    <th>Invoice</th>
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
    dataTable = $('#courierAwardTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: {
            zeroRecords: "No data available in table",
            emptyTable: "No data available in table"
        }
    });
}

function loadData(type = 'all', status = '') {
    dataTable.clear().draw();
    
    const url = `{{ route('user.by-courier-award.data') }}?type=${type}&status=${status}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.data.forEach((item, index) => {
                    dataTable.row.add([
                        index + 1,
                        item.order_no || '-',
                        item.customer_name || '-',
                        new Date(item.order_date).toLocaleDateString('en-GB'),
                        `<span class="badge bg-${item.status === 'delivered' ? 'success' : 'warning'}">${item.status || 'Pending'}</span>`,
                        item.delivery_status || '-',
                        item.date_of_supply ? new Date(item.date_of_supply).toLocaleDateString('en-GB') : '-',
                        item.delivery_by || '-',
                        item.tracking_id || '-',
                        `<button class="btn btn-sm btn-info">View</button>`
                    ]);
                });
                dataTable.draw();
            }
        });
}

function loadDataWithStatus() {
    loadData(document.getElementById('filterType').value, document.getElementById('filterStatus').value);
}

document.getElementById('courierAwardForm').addEventListener('submit', function(e) {
    e.preventDefault();
    loadData(document.getElementById('filterType').value);
});

document.addEventListener('DOMContentLoaded', function() {
    initializeTable();
    loadData();
});
</script>
@endpush