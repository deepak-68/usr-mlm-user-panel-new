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
                            <i class="las la-history text-primary me-2"></i>WithdrawalHistory
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-0">
                    {{-- <form method="GET" action="{{ route('user.fund-history') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">Type</label>
                                <select name="type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="ADMIN CREDIT" {{ request('type') == 'ADMIN CREDIT' ? 'selected' : '' }}>ADMIN CREDIT</option>
                                    <option value="ADMIN DEBIT" {{ request('type') == 'ADMIN DEBIT' ? 'selected' : '' }}>ADMIN DEBIT</option>
                                    <option value="Credit Transfer" {{ request('type') == 'Credit Transfer' ? 'selected' : '' }}>Credit Transfer</option>
                                    <option value="Debit Transfer" {{ request('type') == 'Debit Transfer' ? 'selected' : '' }}>Debit Transfer</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">Date From</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted fw-medium">Date To</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn w-100 text-white" style="background: #1e3a5f; border: none;">
                                    <i class="las la-filter me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form> --}}
                </div>
            </div>


            <!-- Fund History Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="fundHistoryTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Sender</th>
                                    <th>Sender UserName</th>
                                    <th>Ammount</th>
                                    <th>Remark</th>
                                    <th class="text-center">Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="fundHistoryBody">
                                <tr>
                                    <td colspan="7" class="text-center">Loading...</td>
                                </tr>
                            </tbody>

                            {{-- <tfoot class="table-dark">
                                <tr>
                                    <td colspan="5" class="text-end fw-bold">Total</td>
                                    <td class="text-center fw-bold text-success" id="totalCredit">₹0.00</td>
                                    <td class="text-center fw-bold text-danger" id="totalDebit">₹0.00</td>
                                </tr>
                            </tfoot> --}}
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
// $(document).ready(function() {
//     $('#fundHistoryTable').DataTable({
//         responsive: true,
//         pageLength: 10,
//         order: [[1, 'desc']]
//         language: {
//             emptyTable: "No Fund History Found"
//         }
//     });
// });


    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            title: 'Loading...',
            text: 'Please wait',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const apiBaseUrl = "{{ config('services.api.base_url') }}";
        const userId = "{{ session('user_id') }}";

        fetch(`${apiBaseUrl}/withdrawal-history?user_id=${encodeURIComponent(userId)}`)  
            .then(response => response.json())
            .then(data => {

                let tbody = document.getElementById('fundHistoryBody');
                tbody.innerHTML = '';
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Data loaded successfully',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });

                if (data.data.length > 0) {

                    data.data.forEach((fund, index) => {

                        tbody.innerHTML += `
                            <tr>
                                <td class="text-center fw-bold">${index + 1}</td>
                                <td>${fund.user?.name ?? 'N/A'}</td>
                                <td>${fund.sender_username ?? 'N/A'}</td>
                                <td>₹${fund.amount ?? '0.00'}</td>
                                <td>${fund.remark ?? '-'}</td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark">
                                        ${fund.status ?? 'N/A'}
                                    </span>
                                </td>
                                <td>${formatDate(fund.created_at)}</td>
                            </tr>
                        `;
                    });

                } else {

                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                No Fund History Found
                            </td>
                        </tr>
                    `;
                }

                // Totals
                // document.getElementById('totalCredit').innerText =
                //     '₹' + parseFloat(data.totals.credit || 0).toFixed(2);

                // document.getElementById('totalDebit').innerText =
                //     '₹' + parseFloat(data.totals.debit || 0).toFixed(2);

            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load data!',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });

                document.getElementById('fundHistoryBody').innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            Failed to load data
                        </td>
                    </tr>
                `;
            });

        function formatDate(dateString) {
            let date = new Date(dateString);

            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let year = date.getFullYear();

            return `${day}-${month}-${year}`;
        }

    });
</script>
@endpush