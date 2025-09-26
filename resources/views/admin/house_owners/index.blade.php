@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-users-cog me-2"></i>House Owners Management</h2>
                <a href="{{ route('admin.house_owners.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add House Owner
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if($houseOwners->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Buildings</th>
                                        <th>Flats</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($houseOwners as $owner)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">#{{ $owner->id }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white me-2">
                                                        {{ strtoupper(substr($owner->name, 0, 2)) }}
                                                    </div>
                                                    <strong>{{ $owner->name }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="fas fa-envelope text-muted me-1"></i>
                                                {{ $owner->email }}
                                            </td>
                                            <td>
                                                @if($owner->phone)
                                                    <i class="fas fa-phone text-muted me-1"></i>
                                                    {{ $owner->phone }}
                                                @else
                                                    <span class="text-muted">Not provided</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($owner->is_active)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times me-1"></i>Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $owner->buildings_count ?? 0 }} Buildings
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">
                                                    {{ $owner->flats_count ?? 0 }} Flats
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $owner->created_at->format('M d, Y') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.house_owners.edit', $owner) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#viewModal{{ $owner->id }}"
                                                            title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewModal{{ $owner->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-user me-2"></i>{{ $owner->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-sm-4"><strong>Email:</strong></div>
                                                            <div class="col-sm-8">{{ $owner->email }}</div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-sm-4"><strong>Phone:</strong></div>
                                                            <div class="col-sm-8">{{ $owner->phone ?? 'Not provided' }}</div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-sm-4"><strong>Address:</strong></div>
                                                            <div class="col-sm-8">{{ $owner->address ?? 'Not provided' }}</div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-sm-4"><strong>Status:</strong></div>
                                                            <div class="col-sm-8">
                                                                @if($owner->is_active)
                                                                    <span class="badge bg-success">Active</span>
                                                                @else
                                                                    <span class="badge bg-danger">Inactive</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-sm-4"><strong>Joined:</strong></div>
                                                            <div class="col-sm-8">{{ $owner->created_at->format('F d, Y \a\t g:i A') }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <a href="{{ route('admin.house_owners.edit', $owner) }}" class="btn btn-primary">
                                                            <i class="fas fa-edit me-1"></i>Edit
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($houseOwners->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $houseOwners->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No House Owners Found</h4>
                            <p class="text-muted">Start by adding your first house owner to manage properties.</p>
                            <a href="{{ route('admin.house_owners.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Add First House Owner
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}
</style>
@endsection