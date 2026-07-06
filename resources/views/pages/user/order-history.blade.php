@extends('layouts.master')
@section('title', 'Order History')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-file-alt text-primary me-2"></i>ORDER HISTORY
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('user.order-history') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label text-muted fw-medium">Status</label>
                                <select name="type" class="form-select">
                                    <option value="all" {{ ($type ?? 'all') == 'all' ? 'selected' : '' }}>All</option>
                                    <option value="completed" {{ ($type ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="pending" {{ ($type ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="cancelled" {{ ($type ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted fw-medium">From Date</label>
                                <input type="date" name="from_date" class="form-control" value="{{ $from_date ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted fw-medium">To Date</label>
                                <input type="date" name="to_date" class="form-control" value="{{ $to_date ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn w-100 text-white" style="background: #1e3a5f; border: none;">
                                    <i class="las la-search me-1"></i> Search
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('user.order-history') }}" class="btn btn-outline-secondary w-100">
                                    <i class="las la-undo me-1"></i> Reset
                                </a>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success w-100" id="exportCsvBtn">
                                    <i class="las la-file-csv me-1"></i> Export CSV
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
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="orderTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Invoice No</th>
                                    <th>Order Date</th>
                                    <th>Product Details</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Total Amount</th>
                                    <th class="text-end">CC Earned</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $ordersData = $orders['data'] ?? $orders; @endphp
                                @forelse($ordersData as $order)
                                @php $order = (object) $order; @endphp
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        @if($order->invoice ?? false)
                                            <span class="badge bg-primary">{{ $order->invoice->invoice_number }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('d-m-Y') }}</td>
                                    <td>
                                        @if(($order->items ?? false) && count($order->items) > 0)
                                            @foreach($order->items as $item)
                                                <div class="d-flex justify-content-between">
                                                    <span>{{ $item->product->name ?? 'Product #'.$item->product_id }}</span>
                                                    <span class="text-muted small">₹{{ number_format($item->price, 2) }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($order->items ?? false)
                                            {{ collect($order->items)->sum('quantity') }}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">₹{{ number_format($order->total_amount ?? 0, 2) }}</td>
                                    <td class="text-end">{{ number_format($order->total_cc_points ?? 0, 2) }}</td>
                                    <td class="text-center">
                                        @if(($order->status ?? '') === 'COMPLETED')
                                            <span class="badge bg-success"><i class="las la-check-circle me-1"></i>Completed</span>
                                        @elseif(($order->status ?? '') === 'PENDING')
                                            <span class="badge bg-warning text-dark"><i class="las la-clock me-1"></i>Pending</span>
                                        @else
                                            <span class="badge bg-danger"><i class="las la-times-circle me-1"></i>{{ $order->status ?? 'N/A' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($order->invoice ?? false)
                                            <a href="{{ url('/invoice/' . $order->invoice->public_id . '/download') }}"
                                               class="btn btn-sm btn-primary"
                                               target="_blank">
                                                <i class="las la-download"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="las la-file-alt fs-1 text-muted d-block mb-3"></i>
                                        <h5 class="text-muted">No Orders Found</h5>
                                        <p class="text-muted mb-0">You haven't placed any orders yet.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if(($orders['total'] ?? 0) > ($orders['per_page'] ?? 20))
            <div class="d-flex justify-content-center mt-3">
                <nav>
                    <ul class="pagination">
                        @for($page = 1; $page <= ($orders['last_page'] ?? 1); $page++)
                        <li class="page-item {{ ($orders['current_page'] ?? 1) == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ route('user.order-history', array_merge(request()->query(), ['page' => $page])) }}">{{ $page }}</a>
                        </li>
                        @endfor
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('exportCsvBtn')?.addEventListener('click', function () {
    const rows = document.querySelectorAll('#orderTable tbody tr');
    if (!rows.length || rows[0].querySelector('td[colspan]')) {
        alert('No data to export.');
        return;
    }

    const headers = ['#', 'Invoice No', 'Order Date', 'Product Details', 'Qty', 'Total Amount', 'CC Earned', 'Status'];
    let csv = headers.join(',') + '\n';

    rows.forEach(row => {
        const cols = row.querySelectorAll('td');
        if (cols.length < 8) return;
        const rowData = [
            cols[0].innerText.trim(),
            cols[1].innerText.trim(),
            cols[2].innerText.trim(),
            cols[3].innerText.trim().replace(/,/g, ';').replace(/\n/g, ' '),
            cols[4].innerText.trim(),
            cols[5].innerText.trim(),
            cols[6].innerText.trim(),
            cols[7].innerText.trim(),
        ];
        csv += rowData.join(',') + '\n';
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'order-history-' + new Date().toISOString().slice(0, 10) + '.csv';
    link.click();
    URL.revokeObjectURL(link.href);
});
</script>
@endpush
