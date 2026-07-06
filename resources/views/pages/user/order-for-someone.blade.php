@extends('layouts.master')
@section('title', 'Order for Someone')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Alerts -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-user-plus text-primary me-2"></i>ORDER FOR SOMEONE
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Step 1: Search Target User -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="mb-3"><i class="las la-search me-1"></i> Step 1: Find User</h5>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-medium">Enter Track ID / User ID / Username</label>
                            <input type="text" id="targetIdentifier" class="form-control" placeholder="e.g. TRACK001 or username">
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="searchUserBtn" class="btn w-100 text-white" style="background: #1e3a5f;">
                                <i class="las la-search me-1"></i> Search
                            </button>
                        </div>
                    </div>

                    <!-- Search Result -->
                    <div id="searchResult" class="mt-3 d-none">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-1" id="targetName">—</h5>
                                        <p class="mb-0 text-muted">
                                            Track ID: <strong id="targetTrackId">—</strong> |
                                            Username: <strong id="targetUsername">—</strong>
                                        </p>
                                    </div>
                                    <div>
                                        <span class="badge bg-success fs-6" id="targetStatus">Found</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="searchError" class="mt-3 d-none">
                        <div class="alert alert-danger mb-0">User not found. Please check the identifier and try again.</div>
                    </div>

                    <div id="searchLoading" class="mt-3 d-none">
                        <div class="text-center py-3">
                            <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Searching...</span></div>
                            <p class="text-muted mt-2 mb-0">Searching user...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Select Product & Place Order -->
            <div class="card shadow-sm border-0" id="orderSection" style="opacity: 0.5; pointer-events: none;">
                <div class="card-body">
                    <h5 class="mb-3"><i class="las la-shopping-cart me-1"></i> Step 2: Select Product & Place Order</h5>

                        @if($products->isNotEmpty())
                        <div class="row">
                            @php
                                $apiDomain = str_replace('/api', '', env('API_BASE_URL', 'http://127.0.0.1:8001'));
                            @endphp
                            @foreach($products as $product)
                            <div class="col-xl-3 col-md-6 mb-4">
                                <form method="POST" action="{{ route('user.order-for-someone.place') }}" class="h-100">
                                    @csrf
                                    <input type="hidden" name="target_track_id" id="hiddenTrackId{{ $product->id }}">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        @php
                                            $imgPath = '';
                                            if (!empty($product->images)) {
                                                if (is_array($product->images) && isset($product->images[0])) {
                                                    $imgPath = $product->images[0];
                                                } elseif (is_string($product->images)) {
                                                    $decoded = json_decode($product->images, true);
                                                    $imgPath = (is_array($decoded) && isset($decoded[0])) ? $decoded[0] : $product->images;
                                                }
                                            }
                                        @endphp
                                        <div class="mb-3" style="height: 120px; display: flex; align-items: center; justify-content: center;">
                                            @if($imgPath)
                                                <img src="{{ $apiDomain }}/storage/{{ $imgPath }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 100%; object-fit: contain;">
                                            @else
                                                <i class="las la-box fs-1 text-muted"></i>
                                            @endif
                                        </div>
                                        <h6 class="card-title text-primary">{{ $product->name }}</h6>
                                        <ul class="list-unstyled mb-3 text-start px-2 small">
                                            <li class="d-flex justify-content-between"><span>MRP:</span> <span>₹{{ number_format($product->price, 2) }}</span></li>
                                            <li class="d-flex justify-content-between"><span>DP:</span> <span>₹{{ number_format($product->discount_price ?? $product->price, 2) }}</span></li>
                                            <li class="d-flex justify-content-between"><span>CC:</span> <span>{{ $product->cc_points ?? 0 }}</span></li>
                                            <li class="d-flex justify-content-between"><span>Stock:</span> <span class="badge bg-light text-dark">{{ $product->stock }}</span></li>
                                        </ul>
                                        <div class="d-flex gap-2 align-items-center justify-content-center">
                                            <input type="number" name="quantity" class="form-control form-control-sm w-50" value="1" min="1" max="{{ $product->stock }}">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="las la-shopping-bag"></i> Order
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted text-center py-4">No products available.</p>
                        @endif
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('searchUserBtn').addEventListener('click', function() {
    const identifier = document.getElementById('targetIdentifier').value.trim();
    if (!identifier) {
        alert('Please enter a Track ID, User ID, or Username.');
        return;
    }

    document.getElementById('searchResult').classList.add('d-none');
    document.getElementById('searchError').classList.add('d-none');
    document.getElementById('searchLoading').classList.remove('d-none');

    fetch('{{ env("API_BASE_URL") }}/resolve-identifier?identifier=' + encodeURIComponent(identifier))
        .then(res => res.json())
        .then(json => {
            document.getElementById('searchLoading').classList.add('d-none');

            if (!json.success || !json.user) {
                document.getElementById('searchError').classList.remove('d-none');
                document.getElementById('orderSection').style.opacity = '0.5';
                document.getElementById('orderSection').style.pointerEvents = 'none';
                return;
            }

            const user = json.user;
            document.getElementById('targetName').textContent = (user.first_name || '') + ' ' + (user.last_name || '');
            document.getElementById('targetTrackId').textContent = user.track_id || '—';
            document.getElementById('targetUsername').textContent = user.user_name || '—';
            // Fill hidden track_id on all product forms
            document.querySelectorAll('[id^="hiddenTrackId"]').forEach(el => {
                el.value = user.track_id || identifier;
            });
            document.getElementById('searchResult').classList.remove('d-none');
            document.getElementById('orderSection').style.opacity = '1';
            document.getElementById('orderSection').style.pointerEvents = 'auto';
        })
        .catch(err => {
            document.getElementById('searchLoading').classList.add('d-none');
            document.getElementById('searchError').classList.remove('d-none');
            console.error(err);
        });
});

// Support Enter key in search field
document.getElementById('targetIdentifier').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('searchUserBtn').click();
    }
});
</script>
@endpush
