@extends('layouts.master')
@section('title', 'Withdrawal History')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-history text-primary me-2"></i>Withdrawal History
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Withdrawal History Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="withdrawalTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Request Date</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">Charges</th>
                                    <th class="text-end">Payable</th>
                                    <th class="text-center">Status</th>
                                    <th>Transaction Number</th>
                                    <th>Payment Date</th>
                                </tr>
                            </thead>
                            <tbody id="withdrawalBody">
                                <tr>
                                    <td colspan="8" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const apiBaseUrl = "{{ config('services.api.base_url') }}";
        const userId = "{{ session('user_id') }}";

        if (!userId) {
            document.getElementById('withdrawalBody').innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-danger">Session expired. Please login again.</td>
                </tr>
            `;
            return;
        }

        let table = $('#withdrawalTable').DataTable({
            processing: true,
            pageLength: 10,
            dom: 'Bfrtip',
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="las la-download me-1"></i> Export CSV',
                className: 'btn btn-sm btn-outline-secondary',
                title: 'Withdrawal_History',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            }],
            order: [[1, 'desc']],
            language: {
                emptyTable: "No withdrawal history found",
                processing: "Loading..."
            },
            columnDefs: [
                { orderable: false, targets: [0] }
            ],
            columns: [
                { data: 'serial' },
                { data: 'created_at' },
                { data: 'amount' },
                { data: 'charges' },
                { data: 'payable' },
                { data: 'status' },
                { data: 'transaction_number' },
                { data: 'payment_date' }
            ]
        });

        fetch(`${apiBaseUrl}/withdrawal-history?user_id=${encodeURIComponent(userId)}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.data && data.data.length > 0) {
                    let rows = data.data.map((item, index) => ({
                        serial: index + 1,
                        created_at: formatDate(item.created_at),
                        amount: '₹' + parseFloat(item.amount || 0).toFixed(2),
                        charges: '₹' + parseFloat(item.charges || 0).toFixed(2),
                        payable: '₹' + parseFloat(item.payable || 0).toFixed(2),
                        status: getStatusBadge(item.status),
                        transaction_number: item.transaction_number || '-',
                        payment_date: item.payment_date ? formatDate(item.payment_date) : '-'
                    }));
                    table.clear();
                    table.rows.add(rows);
                    table.draw();
                } else {
                    table.clear().draw();
                }
            })
            .catch(error => {
                console.error(error);
                table.clear().draw();
                $('#withdrawalTable tbody').html(`
                    <tr>
                        <td colspan="8" class="text-center text-danger">Failed to load data</td>
                    </tr>
                `);
            });

        function getStatusBadge(status) {
            let badgeClass = 'bg-secondary';
            if (status === 'Pending') badgeClass = 'bg-warning text-dark';
            else if (status === 'Approved') badgeClass = 'bg-success';
            else if (status === 'Rejected') badgeClass = 'bg-danger';
            else if (status === 'Paid') badgeClass = 'bg-info text-dark';
            return `<span class="badge ${badgeClass}">${status || 'N/A'}</span>`;
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            let date = new Date(dateString);
            if (isNaN(date.getTime())) return '-';
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }
    });
</script>
@endpush
