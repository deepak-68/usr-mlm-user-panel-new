@extends('layouts.master')
@section('title', 'Buy Now')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Alerts -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            

            @if(session('logged_in'))
            <!-- Top Balance Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="text-muted text-uppercase mb-2">Available Balance</h6>
                                    <h2 class="mb-0 text-primary">₹ {{ number_format($walletBalance, 2) }}</h2>
                                </div>
                                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                    <h6 class="text-muted text-uppercase mb-2">Total Products Purchased</h6>
                                    <h2 class="mb-0 text-success">{{ $totalPurchased ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif


            <!-- Products List -->
            <div class="row">
                @php
                    // API ka main domain nikal lein (http://127.0.0.1:8001) taaki images wahan se load hon
                    $apiDomain = str_replace('/api', '', env('API_BASE_URL', 'http://127.0.0.1:8001'));
                @endphp

                {{-- @dump($products) --}}

                @forelse($products as $product)
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="mb-3" style="height: 150px; display: flex; align-items: center; justify-content: center;">
                                <img src="{{ $product->image_url ?? '<i class="las la-box fs-1 text-muted"></i>' }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 100%; object-fit: contain;">                                
                            </div>

                            <h5 class="card-title text-primary">{{ $product->name }}</h5>
                            <p class="text-muted small">{{ Str::limit($product->short_description ?? 'Product Description', 60) }}</p>
                            <span class="badge text-bg-primary">{{ Str::limit($product->category?->name ?? 'N/A', 60) }}</span>
                            
                            @php
                                $dp = $product->discount_price ?? $product->price;
                                $gstRate = 6;

                                // GST Amount (included in DP)
                                $gstAmount = $dp * $gstRate / (100 + $gstRate);

                                // Price excluding GST
                                $unitPrice = $dp - $gstAmount;

                                // Pack Size
                                $packSize = $product->size ?? 1;

                                // Selling price per unit
                                $unitSellingPrice = $dp / $packSize;
                            @endphp

                            <ul class="list-unstyled mb-3 text-start px-3">
                                <li class="d-flex justify-content-between">
                                    <span><strong>MRP:</strong></span>
                                    <span class="text-decoration-line-through">
                                        ₹{{ number_format($product->price, 2) }}
                                    </span>
                                </li>

                                <li class="d-flex justify-content-between">
                                    <span><strong>DP:</strong></span>
                                    <span>₹{{ number_format($dp, 2) }}</span>
                                </li>

                                <li class="d-flex justify-content-between">
                                    <span><strong>UNIT PRICE:</strong></span>
                                    <span>₹{{ number_format($unitPrice, 2) }}</span>
                                </li>

                                <li class="d-flex justify-content-between">
                                    <span><strong>GST PRICE (5%):</strong></span>
                                    <span>₹{{ number_format($gstAmount, 2) }}</span>
                                </li>

                                <li class="d-flex justify-content-between">
                                    <span><strong>PACK SIZE:</strong></span>
                                    <span>{{ $packSize ?? 0 }} Capsule</span>
                                </li>

                                <li class="d-flex justify-content-between">
                                    <span><strong>UNIT SELLING PRICE:</strong></span>
                                    <span>₹{{ number_format($unitSellingPrice, 3) }}/Capsule</span>
                                </li>

                                <li class="d-flex justify-content-between">
                                    <span><strong>CC:</strong></span>
                                    <span>{{ $product->cc_points ?? 0 }}</span>
                                </li>
                            </ul>

                            @if($product->stock > 0)
                            @if(session('logged_in'))
                            <form class="purchase-form" action="#" method="POST" data-name="{{ $product->name }}" data-price="{{ $product->discount_price ?? $product->price }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="user_id" value="{{ session('user_id') }}">

                                <div class="mb-2">
                                    <label class="form-label small text-start d-block">Quantity</label>
                                    <input
                                        type="number"
                                        name="quantity"
                                        class="form-control form-control-sm"
                                        min="1"
                                        max="{{ $product->stock }}"
                                        value="1"
                                        required
                                    >
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    Proceed
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                Login to Purchase
                            </a>
                            @endif
                            @else
                            <button class="btn btn-secondary w-100" disabled>Out of Stock</button>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <h4>No products available right now.</h4>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="las la-shopping-cart me-2"></i>
                    Checkout
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="card bg-light border-0 mb-3">
                    <div class="card-body">

                        <h6 id="modalProductName"></h6>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Quantity</span>
                            <strong id="modalQuantity"></strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Price Per Unit</span>
                            <strong>₹<span id="modalPrice"></span></strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <h5>Total Payable</h5>
                            <h5 class="text-primary">
                                ₹<span id="modalTotal"></span>
                            </h5>
                        </div>

                    </div>
                </div>

                {{-- Future Payment Methods --}}
                <div class="mb-3 d-none">
                    <label class="form-label">Payment Method</label>

                    <div class="form-check">
                        <input class="form-check-input" type="radio"
                               name="payment_method"
                               value="wallet"
                               checked
                               disabled>

                        <label class="form-check-label">
                            Wallet Payment
                            <small class="text-muted">(Coming Soon)</small>
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input"
                               type="radio"
                               name="payment_method"
                               value="online"
                               disabled>

                        <label class="form-check-label">
                            Online Payment
                            <small class="text-muted">(Coming Soon)</small>
                        </label>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button class="btn btn-primary" id="confirmPurchaseBtn">
                    Confirm Purchase
                </button>
            </div>

        </div>
    </div>
</div>


@endsection

@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let selectedForm = null;

            document.querySelectorAll('.purchase-form').forEach(form => {

                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    selectedForm = form;

                    const productName = form.dataset.name;
                    const price = parseFloat(form.dataset.price);
                    const quantity = parseInt(
                        form.querySelector('[name="quantity"]').value
                    );

                    const total = price * quantity;

                    document.getElementById('modalProductName').innerText = productName;
                    document.getElementById('modalQuantity').innerText = quantity;
                    document.getElementById('modalPrice').innerText = price.toFixed(2);
                    document.getElementById('modalTotal').innerText = total.toFixed(2);

                    const modal = new bootstrap.Modal(
                        document.getElementById('checkoutModal')
                    );

                    modal.show();
                });

            });

            document.getElementById('confirmPurchaseBtn')
                .addEventListener('click', async function () {

                    if (!selectedForm) return;

                    const btn = this;

                    btn.disabled = true;
                    btn.innerHTML = `
                        <span class="spinner-border spinner-border-sm"></span>
                        Processing...
                    `;

                    const formData = {
                        product_id: selectedForm.querySelector('[name="product_id"]').value,
                        quantity: selectedForm.querySelector('[name="quantity"]').value,
                        user_id: selectedForm.querySelector('[name="user_id"]').value
                    };

                    try {

                        const response = await fetch(
                            '{{ config("services.api.base_url") }}/purchase',
                            {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'Authorization': `Bearer {{ session("token") }}`
                                },
                                body: JSON.stringify(formData)
                            }
                        );

                        const result = await response.json();

                        if (response.ok && result.status) {

                            await Swal.fire({
                                icon: 'success',
                                title: 'Purchase Successful',
                                text: result.message
                            });

                            location.reload();

                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Purchase Failed',
                                text: result.message || 'Something went wrong'
                            });

                        }

                    } catch (error) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Unable to process request'
                        });

                    } finally {

                        btn.disabled = false;
                        btn.innerHTML = 'Confirm Purchase';

                    }

                });
        });
    </script>
@endpush