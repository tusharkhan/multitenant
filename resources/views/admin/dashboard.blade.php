@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-2"></i>
        Admin Dashboard
    </h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">House Owners</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $stats['total_house_owners'] }}</div>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Buildings</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $stats['total_buildings'] }}</div>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Flats</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $stats['total_flats'] }}</div>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-home fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Tenants</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $stats['total_tenants'] }}</div>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-user-friends fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bills Overview -->
<div class="row mb-4">
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Unpaid Bills
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h3 class="text-warning">{{ $stats['unpaid_bills'] }}</h3>
                    <p class="text-muted">Bills pending payment</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-dollar-sign me-2"></i>
                Total Outstanding Dues
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h3 class="text-danger">₹{{ number_format($stats['total_dues'], 2) }}</h3>
                    <p class="text-muted">Amount pending collection</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bills Table -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-file-invoice-dollar me-2"></i>
        Recent Bills
    </div>
    <div class="card-body">
        @if($recent_bills->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Bill Month</th>
                            <th>Building</th>
                            <th>Flat</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_bills as $bill)
                            <tr>
                                <td>{{ $bill->formatted_bill_month }}</td>
                                <td>{{ $bill->flat->building->name }}</td>
                                <td>{{ $bill->flat->flat_number }}</td>
                                <td>{{ $bill->billCategory->name }}</td>
                                <td>₹{{ number_format($bill->total_amount, 2) }}</td>
                                <td>
                                    @if($bill->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($bill->status === 'overdue')
                                        <span class="badge bg-danger">Overdue</span>
                                    @else
                                        <span class="badge bg-warning">Unpaid</span>
                                    @endif
                                </td>
                                <td>{{ $bill->due_date->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                <p class="text-muted">No bills found.</p>
            </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i>
                Quick Actions
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.house_owners.create') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-user-plus me-2"></i>
                            Add House Owner
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.tenants.create') }}" class="btn btn-outline-success btn-lg w-100">
                            <i class="fas fa-user-friends me-2"></i>
                            Add Tenant
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.house_owners.index') }}" class="btn btn-outline-info btn-lg w-100">
                            <i class="fas fa-list me-2"></i>
                            View All Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection