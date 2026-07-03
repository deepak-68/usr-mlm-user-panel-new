@extends('layouts.master')
@section('title', 'Matching Income')
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
                            <i class="las la-handshake text-primary me-2"></i>MATCHING INCOME
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Date Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form id="matchingIncomeForm">
                        @csrf
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

            <!-- Matching Income Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered w-100" id="directIncomeTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">SrNo</th>
                                    <th>User Name</th>
                                    <th>Date</th>
                                    <th>Particular</th>
                                    <th>Invoice No.</th>
                                    <th class="text-center">Credit</th>
                                    <th class="text-center">Debit</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody> 
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total:</th>
                                    <th id="totalCredit">0.00</th>
                                    <th id="totalDebit">0.00</th>
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

@push('scripts')
    <script>
        let directIncomeTable = $('#directIncomeTable').DataTable({
            processing: true,
            serverSide: true,
             ajax: {
                url: "{{ route('user.matching-income') }}",
                dataSrc: function(json) {

                    $('#totalCredit').html('₹ ' + json.total_credit);
                    $('#totalDebit').html('₹ ' + json.total_debit);

                    return json.data;
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { 
                    data: 'user.user_name',
                    name: 'user.user_name',
                    defaultContent: '-'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                     
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function() {
                        return 'N/A';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function() {
                        return 'N/A';
                    }
                },
                {
                    data: null,
                    name: 'credit',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return row.type === 'credit'
                            ? '₹ ' + parseFloat(row.amount).toFixed(2)
                            : '₹ 0.00';
                    }
                },

                {
                    data: null,
                    name: 'debit',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return row.type === 'debit'
                            ? '₹ ' + parseFloat(row.amount).toFixed(2)
                            : '₹ 0.00';
                    }
                }
            ],
             
        });
    </script>
@endpush