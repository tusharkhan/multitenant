@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-2"></i>
        House Owner Dashboard
    </h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">My Buildings</div>
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
        <div class="card stat-card success">
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
        <div class="card stat-card info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Occupied Flats</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $stats['occupied_flats'] }}</div>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-users fa-2x"></i>
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
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Unpaid Bills</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $stats['unpaid_bills'] }}</div>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-file-invoice fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Outstanding Dues -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Total Outstanding Dues
            </div>
            <div class="card-body text-center">
                <h2 class="text-danger">৳{{ number_format($stats['total_dues'], 2) }}</h2>
                <p class="text-muted">Amount pending collection from tenants</p>
            </div>
        </div>
    </div>
</div>

<!-- Buildings Overview -->
@if($buildings->count() > 0)
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-building me-2"></i>
            My Buildings Overview
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($buildings as $building)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border-left-primary">
                            <div class="card-body">
                                <h5 class="card-title">{{ $building->name }}</h5>
                                <p class="card-text text-muted small">
                                    {{ $building->address }}, {{ $building->city }}
                                </p>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="text-primary font-weight-bold">{{ $building->flats->count() }}</div>
                                        <small class="text-muted">Total Flats</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-success font-weight-bold">{{ $building->flats->where('is_occupied', true)->count() }}</div>
                                        <small class="text-muted">Occupied</small>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('house_owner.flats.index', $building) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        View Flats
                                    </a>
                                    <a href="{{ route('house_owner.buildings.edit', $building) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-edit me-1"></i>
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- Recent Bills -->
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
                            <th>Flat</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_bills as $bill)
                            <tr>
                                <td>{{ $bill->formatted_bill_month }}</td>
                                <td>{{ $bill->flat->flat_number }}</td>
                                <td>{{ $bill->billCategory->name }}</td>
                                <td>৳{{ number_format($bill->total_amount, 2) }}</td>
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
                                <td class="table-actions">
                                    <a href="{{ route('house_owner.bills.show', $bill) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($bill->status === 'unpaid')
                                        <a href="{{ route('house_owner.bills.edit', $bill) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                <p class="text-muted">No bills found. Create your first bill to get started.</p>
            </div>
        @endif
    </div>
</div>


@endsection