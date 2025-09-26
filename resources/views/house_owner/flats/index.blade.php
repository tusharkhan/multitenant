@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-home me-2"></i>{{ $building->name }} - Flats Management</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('house_owner.buildings.index') }}">Buildings</a></li>
                            <li class="breadcrumb-item active">{{ $building->name }} Flats</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('house_owner.flats.create', $building) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add Flat
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Building Info Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title">
                                <i class="fas fa-building text-primary me-2"></i>{{ $building->name }}
                            </h5>
                            <p class="card-text">
                                <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                {{ $building->address }}, {{ $building->city }}, {{ $building->state }} - {{ $building->postal_code }}
                            </p>
                            @if($building->description)
                                <p class="card-text">
                                    <small class="text-muted">{{ $building->description }}</small>
                                </p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="stat-box">
                                        <h4 class="text-primary">{{ $flats->total() }}</h4>
                                        <small>Total Flats</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-box">
                                        <h4 class="text-success">{{ $flats->where('is_occupied', true)->count() }}</h4>
                                        <small>Occupied</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-box">
                                        <h4 class="text-warning">{{ $flats->where('is_occupied', false)->count() }}</h4>
                                        <small>Vacant</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flats List -->
            <div class="card">
                <div class="card-body">
                    @if($flats->count() > 0)
                        <div class="row">
                            @foreach($flats as $flat)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 {{ $flat->is_occupied ? 'border-success' : 'border-warning' }}">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">
                                                <i class="fas fa-door-open me-2"></i>{{ $flat->flat_number }}
                                            </h5>
                                            <span class="badge {{ $flat->is_occupied ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $flat->is_occupied ? 'Occupied' : 'Vacant' }}
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-2">
                                                <div class="col-sm-5"><strong>Owner:</strong></div>
                                                <div class="col-sm-7">{{ $flat->owner_name }}</div>
                                            </div>
                                            @if($flat->owner_phone)
                                            <div class="row mb-2">
                                                <div class="col-sm-5"><strong>Phone:</strong></div>
                                                <div class="col-sm-7">
                                                    <i class="fas fa-phone text-muted me-1"></i>{{ $flat->owner_phone }}
                                                </div>
                                            </div>
                                            @endif
                                            @if($flat->carpet_area)
                                            <div class="row mb-2">
                                                <div class="col-sm-5"><strong>Area:</strong></div>
                                                <div class="col-sm-7">{{ $flat->carpet_area }} sq ft</div>
                                            </div>
                                            @endif
                                            @if($flat->bedrooms || $flat->bathrooms)
                                            <div class="row mb-2">
                                                <div class="col-sm-5"><strong>Config:</strong></div>
                                                <div class="col-sm-7">
                                                    @if($flat->bedrooms)<i class="fas fa-bed me-1"></i>{{ $flat->bedrooms }}BR @endif
                                                    @if($flat->bathrooms)<i class="fas fa-bath me-1"></i>{{ $flat->bathrooms }}BA @endif
                                                </div>
                                            </div>
                                            @endif
                                            
                                            @if($flat->is_occupied && $flat->currentTenant)
                                            <hr>
                                            <div class="tenant-info">
                                                <h6 class="text-success mb-2">
                                                    <i class="fas fa-user me-1"></i>Current Tenant
                                                </h6>
                                                <div class="row mb-1">
                                                    <div class="col-sm-5"><strong>Name:</strong></div>
                                                    <div class="col-sm-7">{{ $flat->currentTenant->name }}</div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-sm-5"><strong>Phone:</strong></div>
                                                    <div class="col-sm-7">{{ $flat->currentTenant->phone }}</div>
                                                </div>
                                                @if($flat->currentTenant->move_in_date)
                                                <div class="row mb-1">
                                                    <div class="col-sm-5"><strong>Move In:</strong></div>
                                                    <div class="col-sm-7">{{ $flat->currentTenant->move_in_date->format('M d, Y') }}</div>
                                                </div>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group w-100" role="group">
                                                <a href="{{ route('house_owner.flats.edit', [$building, $flat]) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-info btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#viewModal{{ $flat->id }}">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <form method="POST" action="{{ route('house_owner.flats.destroy', [$building, $flat]) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger btn-sm" 
                                                            onclick="return confirm('Are you sure you want to delete this flat?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal{{ $flat->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-home me-2"></i>Flat {{ $flat->flat_number }} Details
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>Building:</strong></div>
                                                    <div class="col-sm-8">{{ $building->name }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>Flat Number:</strong></div>
                                                    <div class="col-sm-8">{{ $flat->flat_number }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>Owner Name:</strong></div>
                                                    <div class="col-sm-8">{{ $flat->owner_name }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>Owner Phone:</strong></div>
                                                    <div class="col-sm-8">{{ $flat->owner_phone ?? 'Not provided' }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>Owner Email:</strong></div>
                                                    <div class="col-sm-8">{{ $flat->owner_email ?? 'Not provided' }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>Carpet Area:</strong></div>
                                                    <div class="col-sm-8">{{ $flat->carpet_area ?? 'Not specified' }} sq ft</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>Bedrooms:</strong></div>
                                                    <div class="col-sm-8">{{ $flat->bedrooms ?? 'Not specified' }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>Bathrooms:</strong></div>
                                                    <div class="col-sm-8">{{ $flat->bathrooms ?? 'Not specified' }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>Status:</strong></div>
                                                    <div class="col-sm-8">
                                                        <span class="badge {{ $flat->is_occupied ? 'bg-success' : 'bg-warning text-dark' }}">
                                                            {{ $flat->is_occupied ? 'Occupied' : 'Vacant' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @if($flat->notes)
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>Notes:</strong></div>
                                                    <div class="col-sm-8">{{ $flat->notes }}</div>
                                                </div>
                                                @endif
                                                <div class="row mb-2">
                                                    <div class="col-sm-4"><strong>Created:</strong></div>
                                                    <div class="col-sm-8">{{ $flat->created_at->format('F d, Y \a\t g:i A') }}</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <a href="{{ route('house_owner.flats.edit', [$building, $flat]) }}" class="btn btn-primary">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($flats->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $flats->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-home fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No Flats Found</h4>
                            <p class="text-muted">Start by adding flats to this building.</p>
                            <a href="{{ route('house_owner.flats.create', $building) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Add First Flat
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-box {
    padding: 10px;
    border-radius: 8px;
    background: #f8f9fa;
}
.tenant-info {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
}
</style>
@endsection