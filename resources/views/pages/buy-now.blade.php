@extends('layouts.master')
@section('title', 'Buy Now')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

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
            {{-- <div class="row">
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
            </div> --}}

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-1"><i class="las la-user me-1"></i> Purchase For</h6>
                            <div class="d-flex align-items-center gap-3 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="purchaseFor" id="purchaseForSelf" value="self" checked>
                                    <label class="form-check-label" for="purchaseForSelf">Myself</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="purchaseFor" id="purchaseForOther" value="other">
                                    <label class="form-check-label" for="purchaseForOther">Someone Else</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="orderForSomeoneSection" class="mt-3 d-none">
                        <hr>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-medium">Enter Track ID / Username</label>
                                <input type="text" id="targetIdentifier" class="form-control" placeholder="e.g. TRACK001 or username">
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="searchUserBtn" class="btn w-100 text-white" style="background: #1e3a5f;">
                                    <i class="las la-search me-1"></i> Search
                                </button>
                            </div>
                        </div>

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
                                            <span class="badge bg-success fs-6">Found</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="searchError" class="mt-3 d-none">
                            <div class="alert alert-danger mb-0">User not found. Please check the identifier and try again.</div>
                        </div>

                        <div id="searchLoading" class="mt-3 d-none">
                            <div class="text-center py-2">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                <p class="text-muted mt-1 mb-0 small">Searching user...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                @php
                    $apiDomain = str_replace('/api', '', env('API_BASE_URL', 'http://127.0.0.1:8001'));
                @endphp

                @forelse($products as $product)
                @php
                    $dp = $product->discount_price ?? $product->price;
                    $gstRate = 6;
                    $gstAmount = $dp * $gstRate / (100 + $gstRate);
                    $unitPrice = $dp - $gstAmount;
                    $packSize = $product->size ?? 1;
                    $unitSellingPrice = $dp / $packSize;
                @endphp
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="mb-3" style="height: 150px; display: flex; align-items: center; justify-content: center;">
                                <img src="{{ $product->image_url ?? '' }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 100%; object-fit: contain;">
                            </div>

                            <h5 class="card-title text-primary">{{ $product->name }}</h5>
                            <p class="text-muted small">{{ Str::limit($product->short_description ?? 'Product Description', 60) }}</p>
                            <span class="badge text-bg-primary">{{ Str::limit($product->category?->name ?? 'N/A', 60) }}</span>

                            <ul class="list-unstyled mb-3 text-start px-3">
                                <li class="d-flex justify-content-between">
                                    <span><strong>MRP:</strong></span>
                                    <span class="text-decoration-line-through">₹{{ number_format($product->price, 2) }}</span>
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
                                    <span><strong>GST ({{ $gstRate }}%):</strong></span>
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
                            <form class="purchase-form" action="#" method="POST"
                                  data-id="{{ $product->id }}"
                                  data-name="{{ $product->name }}"
                                  data-price="{{ $dp }}"
                                  data-mrp="{{ $product->price }}"
                                  data-gst-rate="{{ $gstRate }}"
                                  data-gst-amount="{{ $gstAmount }}"
                                  data-cc="{{ $product->cc_points ?? 0 }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="mb-2">
                                    <label class="form-label small text-start d-block">Quantity</label>
                                    <input type="number" name="quantity" class="form-control form-control-sm"
                                           min="1" max="{{ $product->stock }}" value="1" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Proceed</button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100">Login to Purchase</a>
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

<style>
    .checkout-summary .row-label { color: #6b7280; font-size: 0.88rem; }
    .checkout-summary .row-value { font-weight: 600; font-size: 0.88rem; }
    .checkout-total { font-size: 1.1rem; font-weight: 700; color: #1e3a5f; }
    .bank-card { border: 1.5px solid #e5e7eb; border-radius: 12px; padding: 14px; margin-bottom: 12px; background: #f9fafb; transition: border-color 0.2s; }
    .bank-card:hover { border-color: #284a8a; }
    .bank-card .bank-label { font-size: 0.8rem; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.3px; }
    .bank-card .bank-value { font-weight: 600; font-size: 0.92rem; color: #1f2937; }
    .upload-area { border: 2px dashed #d1d5db; border-radius: 12px; padding: 24px; text-align: center; cursor: pointer; transition: all 0.2s; background: #fafafa; }
    .upload-area:hover { border-color: #284a8a; background: #f0f4ff; }
    .upload-area.has-file { border-color: #059669; background: #f0fdf4; }
    .upload-area .upload-icon { font-size: 2rem; color: #9ca3af; }
    .upload-area.has-file .upload-icon { color: #059669; }
</style>

<div class="modal fade" id="checkoutModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">

            <div class="modal-header" style="border-bottom: 1px solid #e5e7eb; padding: 18px 24px;">
                <h5 class="modal-title fw-bold"><i class="las la-shopping-cart me-2"></i>Checkout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                <div id="modalTargetInfo" class="alert alert-info d-none mb-3">
                    <i class="las la-user me-1"></i> Ordering for: <strong id="modalTargetName">—</strong>
                    <small class="text-muted ms-2" id="modalTargetTrackId">—</small>
                </div>

                <div class="checkout-summary">
                    <h6 class="fw-bold mb-3" style="color: #1e3a5f;"><i class="las la-receipt me-1"></i> Order Summary</h6>
                    <div class="row mb-2">
                        <div class="col-6 row-label">Product</div>
                        <div class="col-6 row-value text-end" id="modalProductName">-</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 row-label">Quantity</div>
                        <div class="col-6 row-value text-end"><span id="modalQuantity"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 row-label">MRP <small class="text-muted">(per unit)</small></div>
                        <div class="col-6 row-value text-end">₹<span id="modalMrp"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 row-label">Discount Price <small class="text-muted">(per unit)</small></div>
                        <div class="col-6 row-value text-end text-success">- ₹<span id="modalDiscount"></span></div>
                    </div>
                    <hr class="my-2">
                    <div class="row mb-2">
                        <div class="col-6 row-label">DP Total</div>
                        <div class="col-6 row-value text-end">₹<span id="modalDpTotal"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 row-label">GST <span id="modalGstLabel">(6%)</span></div>
                        <div class="col-6 row-value text-end">₹<span id="modalGstTotal"></span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 row-label">CC Points</div>
                        <div class="col-6 row-value text-end"><span id="modalCc"></span></div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-6 checkout-total">Total Payable</div>
                        <div class="col-6 checkout-total text-end">₹<span id="modalTotal"></span></div>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3" style="color: #1e3a5f;"><i class="las la-university me-1"></i> Bank Details for Payment</h6>
                <p class="text-muted small mb-3">Please transfer the total amount to any of the following accounts and upload the payment proof below.</p>
                <div id="bankDetailsContainer">
                    @forelse($bankDetails as $bank)
                    <div class="bank-card">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="row g-1">
                                    <div class="col-6">
                                        <div class="bank-label">Bank</div>
                                        <div class="bank-value">{{ $bank->bank_name }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bank-label">Account No</div>
                                        <div class="bank-value">{{ $bank->account_no }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bank-label">IFSC</div>
                                        <div class="bank-value">{{ $bank->ifsc_code }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bank-label">Mode</div>
                                        <div class="bank-value">{{ $bank->mode_name }}</div>
                                    </div>
                                </div>
                                @if($bank->address)
                                <div class="mt-1">
                                    <span class="bank-label">Address:</span>
                                    <span class="bank-value" style="font-weight:400;">{{ $bank->address }}</span>
                                </div>
                                @endif
                            </div>
                            @if($bank->image)
                            <div class="col-md-4 text-center mt-2 mt-md-0">
                                <img src="{{ $apiDomain }}/storage/{{ $bank->image }}" alt="QR" class="img-fluid rounded" style="max-height:100px;">
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-warning">No bank details available. Please contact admin.</div>
                    @endforelse
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3" style="color: #1e3a5f;"><i class="las la-upload me-1"></i> Payment Details</h6>
                <div class="mb-3">
                    <label class="form-label">Transaction / Reference Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="transactionNumber" placeholder="Enter transaction or UTR number" required>
                    <div class="invalid-feedback" id="txnError">Transaction number is required.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Payment Proof <span class="text-danger">*</span></label>
                    <div class="upload-area" id="uploadArea" onclick="document.getElementById('paymentProof').click()">
                        <div class="upload-icon"><i class="las la-cloud-upload-alt"></i></div>
                        <p class="mb-1 fw-medium" id="uploadText">Click to upload screenshot or PDF</p>
                        <p class="text-muted small mb-0">JPG, PNG or PDF (max 5MB)</p>
                        <input type="file" id="paymentProof" accept=".jpg,.jpeg,.png,.pdf" style="display:none;">
                    </div>
                    <div class="invalid-feedback" id="proofError">Payment proof is required.</div>
                </div>

            </div>

            <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 16px 24px;">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="confirmPurchaseBtn" style="background: linear-gradient(135deg, #284a8a 0%, #aece5b 100%); border: none; border-radius: 8px; padding: 10px 28px; font-weight: 600;">
                    <i class="las la-check-circle me-1"></i> Confirm Payment
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let selectedForm = null;
    let selectedFile = null;
    let targetTrackId = null;
    let targetUserData = null;

    const uploadArea = document.getElementById('uploadArea');
    const proofInput = document.getElementById('paymentProof');

    // ── Purchase For Toggle ──
    const purchaseForSelf = document.getElementById('purchaseForSelf');
    const purchaseForOther = document.getElementById('purchaseForOther');
    const orderForSomeoneSection = document.getElementById('orderForSomeoneSection');

    if (purchaseForSelf && purchaseForOther) {
        purchaseForSelf.addEventListener('change', function () {
            orderForSomeoneSection.classList.add('d-none');
            targetTrackId = null;
            targetUserData = null;
        });
        purchaseForOther.addEventListener('change', function () {
            orderForSomeoneSection.classList.remove('d-none');
        });
    }

    // ── Upload ──
    proofInput.addEventListener('change', function () {
        selectedFile = this.files[0];
        if (selectedFile) {
            uploadArea.classList.add('has-file');
            document.getElementById('uploadText').textContent = selectedFile.name;
        } else {
            uploadArea.classList.remove('has-file');
            document.getElementById('uploadText').textContent = 'Click to upload screenshot or PDF';
        }
    });

    // ── User Search ──
    const searchBtn = document.getElementById('searchUserBtn');
    if (searchBtn) {
        searchBtn.addEventListener('click', function () {
            const identifier = document.getElementById('targetIdentifier').value.trim();
            if (!identifier) {
                alert('Please enter a Track ID or Username.');
                return;
            }

            document.getElementById('searchResult').classList.add('d-none');
            document.getElementById('searchError').classList.add('d-none');
            document.getElementById('searchLoading').classList.remove('d-none');

            fetch('{{ env("API_BASE_URL") }}/resolve-identifier?identifier=' + encodeURIComponent(identifier), {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer {{ session("token") }}'
                    }
                })
                .then(res => res.json())
                .then(json => {
                    document.getElementById('searchLoading').classList.add('d-none');

                    if (!json.success || !json.user) {
                        document.getElementById('searchError').classList.remove('d-none');
                        targetTrackId = null;
                        targetUserData = null;
                        return;
                    }

                    const user = json.user;
                    targetTrackId = user.track_id || identifier;
                    targetUserData = user;

                    document.getElementById('targetName').textContent = (user.first_name || '') + ' ' + (user.last_name || '');
                    document.getElementById('targetTrackId').textContent = user.track_id || '—';
                    document.getElementById('targetUsername').textContent = user.user_name || '—';
                    document.getElementById('searchResult').classList.remove('d-none');
                })
                .catch(err => {
                    document.getElementById('searchLoading').classList.add('d-none');
                    document.getElementById('searchError').classList.remove('d-none');
                    targetTrackId = null;
                    targetUserData = null;
                    console.error(err);
                });
        });

        document.getElementById('targetIdentifier').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchBtn.click();
            }
        });
    }

    // ── Product Form Submit ──
    document.querySelectorAll('.purchase-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            selectedForm = form;

            const isForOther = purchaseForOther && purchaseForOther.checked;

            if (isForOther && !targetTrackId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Target User Required',
                    text: 'Please search and select a user to order for.',
                    confirmButtonColor: '#284a8a',
                });
                return;
            }

            const productName = form.dataset.name;
            const price = parseFloat(form.dataset.price);
            const mrp = parseFloat(form.dataset.mrp);
            const gstRate = parseFloat(form.dataset.gstRate);
            const gstAmount = parseFloat(form.dataset.gstAmount);
            const cc = parseInt(form.dataset.cc);
            const quantity = parseInt(form.querySelector('[name="quantity"]').value);

            const dpTotal = price * quantity;
            const totalGst = gstAmount * quantity;
            const discount = mrp - price;
            const totalPayable = dpTotal;

            document.getElementById('modalProductName').innerText = productName;
            document.getElementById('modalQuantity').innerText = quantity;
            document.getElementById('modalMrp').innerText = mrp.toFixed(2);
            document.getElementById('modalDiscount').innerText = discount.toFixed(2);
            document.getElementById('modalDpTotal').innerText = dpTotal.toFixed(2);
            document.getElementById('modalGstLabel').innerText = `(${gstRate}%)`;
            document.getElementById('modalGstTotal').innerText = totalGst.toFixed(2);
            document.getElementById('modalCc').innerText = cc * quantity;
            document.getElementById('modalTotal').innerText = totalPayable.toFixed(2);

            // Target user info in modal
            const targetInfo = document.getElementById('modalTargetInfo');
            if (isForOther && targetUserData) {
                document.getElementById('modalTargetName').textContent =
                    (targetUserData.first_name || '') + ' ' + (targetUserData.last_name || '');
                document.getElementById('modalTargetTrackId').textContent =
                    'Track ID: ' + (targetUserData.track_id || '—');
                targetInfo.classList.remove('d-none');
            } else {
                targetInfo.classList.add('d-none');
            }

            document.getElementById('transactionNumber').value = '';
            document.getElementById('transactionNumber').classList.remove('is-invalid');
            document.getElementById('txnError').style.display = 'none';
            document.getElementById('proofError').style.display = 'none';
            uploadArea.classList.remove('has-file');
            document.getElementById('uploadText').textContent = 'Click to upload screenshot or PDF';
            selectedFile = null;
            proofInput.value = '';

            new bootstrap.Modal(document.getElementById('checkoutModal')).show();
        });
    });

    // ── Confirm Purchase ──
    document.getElementById('confirmPurchaseBtn').addEventListener('click', async function () {
        if (!selectedForm) return;

        const txnInput = document.getElementById('transactionNumber');
        const txn = txnInput.value.trim();
        let valid = true;

        txnInput.classList.remove('is-invalid');
        document.getElementById('txnError').style.display = 'none';
        document.getElementById('proofError').style.display = 'none';

        if (!txn) {
            txnInput.classList.add('is-invalid');
            document.getElementById('txnError').style.display = 'block';
            valid = false;
        }
        if (!selectedFile) {
            document.getElementById('proofError').style.display = 'block';
            valid = false;
        }
        if (!valid) return;

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';

        const formData = new FormData();
        formData.append('user_id', '{{ session("user.id") }}');
        formData.append('product_id', selectedForm.querySelector('[name="product_id"]').value);
        formData.append('quantity', selectedForm.querySelector('[name="quantity"]').value);
        formData.append('transaction_number', txn);
        formData.append('payment_proof', selectedFile);
        if (targetTrackId) {
            formData.append('target_track_id', targetTrackId);
        }

        try {
            const response = await fetch('{{ env("API_BASE_URL") }}/manual-purchase', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ session("token") }}'
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.status) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Order Placed!',
                    text: result.message || 'Your order has been placed. Awaiting admin confirmation.',
                    confirmButtonColor: '#284a8a',
                });
                bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
                location.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: result.message || 'Something went wrong.',
                    confirmButtonColor: '#284a8a',
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Unable to process request. Please try again.',
                confirmButtonColor: '#284a8a',
            });
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="las la-check-circle me-1"></i> Confirm Payment';
        }
    });
});
</script>
@endpush
