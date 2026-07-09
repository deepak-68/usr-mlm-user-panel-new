@extends('layouts.master')
@section('title', 'Schedule Callback')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">Schedule Callback</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Left: Callback Form --}}
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('user.callback.submit') }}" method="POST" id="callbackForm">
                                @csrf

                                <div class="mb-3">
                                    <label for="preferred_date" class="form-label">Preferred Date <span class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control @error('preferred_date') is-invalid @enderror"
                                        id="preferred_date"
                                        name="preferred_date"
                                        value="{{ old('preferred_date') }}"
                                        required>
                                    @error('preferred_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="preferred_time" class="form-label">Preferred Time <span class="text-danger">*</span></label>
                                    <input type="time"
                                        class="form-control @error('preferred_time') is-invalid @enderror"
                                        id="preferred_time"
                                        name="preferred_time"
                                        value="{{ old('preferred_time') }}"
                                        required>
                                    @error('preferred_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="issue_summary" class="form-label">Issue Summary</label>
                                    <textarea class="form-control @error('issue_summary') is-invalid @enderror"
                                            id="issue_summary"
                                            name="issue_summary"
                                            rows="4"
                                            placeholder="Briefly describe your issue...">{{ old('issue_summary') }}</textarea>
                                    @error('issue_summary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                    <i class="las la-phone me-1"></i> Request Callback
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Right: WhatsApp Support Card --}}
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="lab la-whatsapp" style="font-size: 4rem; color: #25D366;"></i>
                            </div>
                            <h5 class="card-title">WhatsApp Support</h5>
                            <p class="text-muted mb-3">Get instant support via WhatsApp</p>
                            <a href="https://wa.me/{{ $whatsappNumber ?? '' }}?text=Hi%20I%20need%20support"
                            target="_blank"
                            class="btn btn-success w-100"
                            id="whatsappLink">
                                <i class="lab la-whatsapp me-1"></i> Chat on WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('callbackForm')?.addEventListener('submit', function (e) {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';
});
</script>
@endpush
