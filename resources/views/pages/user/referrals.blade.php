@extends('layouts.user-master')
@section('content')

<div class="container mt-4">
    <h3>My Direct Referrals</h3>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total Referrals</h5>
                    <h2>{{ $stats->total ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Active</h5>
                    <h2>{{ $stats->active ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total CC</h5>
                    <h2>{{ $stats->total_cc ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Referrals Table -->
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>CC Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrals as $ref)
                        <tr>
                            <td>{{ $ref->first_name }} {{ $ref->last_name }}</td>
                            <td>{{ $ref->email }}</td>
                            <td>
                                <span class="badge bg-{{ $ref->is_active ? 'success' : 'danger' }}">
                                    {{ $ref->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $ref->payout_balance->cc_balance ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">No referrals found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection