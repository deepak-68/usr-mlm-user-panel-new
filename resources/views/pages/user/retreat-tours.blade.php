@extends('layouts.master')
@section('title', 'Retreat, Asia, International Tours')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-plane text-primary me-2"></i>RETREAT, ASIA, INTERNATIONAL TOURS
                        </h4>
                    </div>
                </div>
            </div>

            <!-- History Button -->
            <div class="mb-3 text-end">
                <button class="btn btn-success">
                    <i class="las la-history me-1"></i>History
                </button>
            </div>

            <!-- Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0" style="background: #b9f6ca;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="toursTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 40px;">#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Current Left/Right CC</th>
                                    <th>Required Left/Right CC</th>
                                    <th>Status</th>
                                    <th>Zone</th>
                                    <th>Country</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Hotel Pic</th>
                                    <th>QR CODE</th>
                                    <th>Address</th>
                                    <th>FromDate</th>
                                    <th>ToDate</th>
                                    <th>Claim</th>
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
    dataTable = $('#toursTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: {
            zeroRecords: "No data available in table",
            emptyTable: "No data available in table"
        }
    });
}

function loadData() {
    dataTable.clear().draw();
    
    fetch(`{{ route('user.retreat-tours.data') }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.data.forEach((item, index) => {
                    dataTable.row.add([
                        index + 1,
                        item.name || '-',
                        item.description || '-',
                        item.current_cc || '0/0',
                        item.required_cc || '-',
                        `<span class="badge bg-success">${item.status || 'Running'}</span>`,
                        item.zone || '-',
                        item.country || '-',
                        item.state || '-',
                        item.city || '-',
                        item.hotel_pic ? `<img src="/storage/${item.hotel_pic}" alt="Hotel" style="width: 50px; height: 50px; object-fit: cover;">` : '-',
                        item.qr_code ? `<img src="/storage/${item.qr_code}" alt="QR" style="width: 50px; height: 50px;">` : '-',
                        item.address || '-',
                        new Date(item.from_date).toLocaleDateString('en-GB'),
                        new Date(item.to_date).toLocaleDateString('en-GB'),
                        `<button class="btn btn-sm btn-danger" ${item.can_claim ? '' : 'disabled'}>Claim</button>`
                    ]);
                });
                dataTable.draw();
            }
        });
}

document.addEventListener('DOMContentLoaded', function() {
    initializeTable();
    loadData();
});
</script>
@endpush