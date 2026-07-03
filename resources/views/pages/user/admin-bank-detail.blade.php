@extends('layouts.master')
@section('title', 'Admin Bank Detail')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-university text-primary me-2"></i>ADMIN BANK DETAIL
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Bank Detail Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="bankDetailTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Mode Name</th>
                                    <th>Address</th>
                                    <th>Account No</th>
                                    <th>Bank Name</th>
                                    <th>IFSC Code</th>
                                    <th class="text-center" style="width: 100px;">Image</th>
                                    <th class="text-center" style="width: 100px;">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bankDetails as $index => $bank)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td><span class="fw-semibold">{{ $bank->mode_name ?? 'N/A' }}</span></td>
                                    <td>{{ $bank->address ?? 'N/A' }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $bank->account_no ?? 'N/A' }}</span></td>
                                    <td>{{ $bank->bank_name ?? 'N/A' }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $bank->ifsc_code ?? 'N/A' }}</span></td>
                                    <td class="text-center">
                                        @if($bank->image)
                                            <img src="{{ asset('storage/' . $bank->image) }}" 
                                                 alt="Bank Image" 
                                                 class="img-thumbnail" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#bankModal{{ $bank->id }}"
                                                style="background: #1e3a5f; border: none;">
                                            <i class="las la-eye me-1"></i> View
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                {{-- <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-5">
                                            <i class="las la-university fs-1 text-muted d-block mb-3"></i>
                                            <h5 class="text-muted">No Bank Details Found</h5>
                                            <p class="text-muted mb-0">Admin has not added any bank details yet.</p>
                                        </div>
                                    </td>
                                </tr> --}}
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- View Modal for each bank -->
@foreach($bankDetails as $bank)
<div class="modal fade" id="bankModal{{ $bank->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0 pt-3 px-4" style="background: #1e3a5f; color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title">
                    <i class="las la-university me-2"></i>{{ $bank->mode_name ?? 'Bank Detail' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3 pb-4 px-4">
                <div class="row g-3">
                    <div class="col-12 text-center">
                        @if($bank->image)
                            <img src="{{ asset('storage/' . $bank->image) }}" 
                                 alt="Bank Image" 
                                 class="img-fluid rounded" 
                                 style="max-width: 200px; max-height: 200px;">
                        @else
                            <div class="bg-light rounded p-4">
                                <i class="las la-image fs-1 text-muted"></i>
                                <p class="text-muted mb-0">No Image Available</p>
                            </div>
                        @endif
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded" style="background: #f8f9fa;">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="fw-semibold" style="width: 40%;">Mode Name:</td>
                                    <td>{{ $bank->mode_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Bank Name:</td>
                                    <td>{{ $bank->bank_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Account No:</td>
                                    <td><span class="badge bg-primary">{{ $bank->account_no ?? 'N/A' }}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">IFSC Code:</td>
                                    <td><span class="badge bg-info text-dark">{{ $bank->ifsc_code ?? 'N/A' }}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Address:</td>
                                    <td>{{ $bank->address ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    $('#bankDetailTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries available",
            infoFiltered: "(filtered from _MAX_ total entries)",
            zeroRecords: "No matching records found",
            emptyTable: "No data available in table"
        },
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [6, 7] } // Disable sorting on Image & View columns
        ]
    });
});
</script>
@endpush