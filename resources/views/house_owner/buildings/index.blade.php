@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-building me-2"></i>My Buildings</h2>
                <a href="{{ route('house_owner.buildings.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add Building
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                @if($buildings->count() > 0)
                    @foreach($buildings as $building)
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-building me-2"></i>{{ $building->name }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6><i class="fas fa-map-marker-alt text-muted me-1"></i>Address</h6>
                                        <p class="text-muted mb-1">{{ $building->address }}</p>
                                        <p class="text-muted mb-0">{{ $building->city }}, {{ $building->state }} - {{ $building->postal_code }}</p>
                                    </div>
                                    
                                    @if($building->description)
                                    <div class="mb-3">
                                        <h6><i class="fas fa-info-circle text-muted me-1"></i>Description</h6>
                                        <p class="text-muted">{{ Str::limit($building->description, 100) }}</p>
                                    </div>
                                    @endif

                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="stat-item">
                                                <h4 class="text-primary">{{ $building->flats_count ?? 0 }}</h4>
                                                <small class="text-muted">Total Flats</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="stat-item">
                                                <h4 class="text-success">{{ $building->occupied_flats_count ?? 0 }}</h4>
                                                <small class="text-muted">Occupied</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="stat-item">
                                                <h4 class="text-warning">{{ ($building->flats_count ?? 0) - ($building->occupied_flats_count ?? 0) }}</h4>
                                                <small class="text-muted">Vacant</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-6">
                                            <a href="{{ route('house_owner.flats.index', $building) }}" class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fas fa-home me-1"></i>Manage Flats
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <div class="btn-group w-100" role="group">
                                                <a href="{{ route('house_owner.buildings.edit', $building) }}" 
                                                   class="btn btn-outline-secondary btn-sm" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-info btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#viewModal{{ $building->id }}"
                                                        title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- View Modal -->
                        <div class="modal fade" id="viewModal{{ $building->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="fas fa-building me-2"></i>{{ $building->name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>Name:</strong></div>
                                            <div class="col-sm-8">{{ $building->name }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>Address:</strong></div>
                                            <div class="col-sm-8">{{ $building->address }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>City:</strong></div>
                                            <div class="col-sm-8">{{ $building->city }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>State:</strong></div>
                                            <div class="col-sm-8">{{ $building->state }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>Postal Code:</strong></div>
                                            <div class="col-sm-8">{{ $building->postal_code }}</div>
                                        </div>
                                        @if($building->description)
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>Description:</strong></div>
                                            <div class="col-sm-8">{{ $building->description }}</div>
                                        </div>
                                        @endif
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>Status:</strong></div>
                                            <div class="col-sm-8">
                                                <span class="badge {{ $building->is_active ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $building->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4"><strong>Created:</strong></div>
                                            <div class="col-sm-8">{{ $building->created_at->format('F d, Y \a\t g:i A') }}</div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <a href="{{ route('house_owner.buildings.edit', $building) }}" class="btn btn-primary">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <a href="{{ route('house_owner.flats.index', $building) }}" class="btn btn-success">
                                            <i class="fas fa-home me-1"></i>Manage Flats
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-building fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No Buildings Found</h4>
                            <p class="text-muted">Start by adding your first building to manage properties.</p>
                            <a href="{{ route('house_owner.buildings.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Add First Building
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($buildings->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $buildings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.stat-item {
    padding: 10px;
    border-radius: 8px;
    background: #f8f9fa;
    margin-bottom: 10px;
}
</style>
@endsection