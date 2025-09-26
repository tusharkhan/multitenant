@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-users me-2"></i>Tenant Management</h2>
                <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add Tenant
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
                    @if($tenants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Flat</th>
                                        <th>Building</th>
                                        <th>House Owner</th>
                                        <th>Status</th>
                                        <th>Move In</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tenants as $tenant)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">#{{ $tenant->id }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-success text-white me-2">
                                                        {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                                    </div>
                                                    <strong>{{ $tenant->name }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="fas fa-envelope text-muted me-1"></i>
                                                {{ $tenant->email }}
                                            </td>
                                            <td>
                                                <i class="fas fa-phone text-muted me-1"></i>
                                                {{ $tenant->phone }}
                                            </td>
                                            <td>
                                                @if($tenant->flat)
                                                    <span class="badge bg-info">
                                                        {{ $tenant->flat->flat_number }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tenant->flat && $tenant->flat->building)
                                                    <small class="text-dark">
                                                        {{ $tenant->flat->building->name }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tenant->assignedBy)
                                                    <small class="text-primary">
                                                        {{ $tenant->assignedBy->name }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tenant->is_active)
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
                                                @if($tenant->move_in_date)
                                                    <small class="text-muted">
                                                        {{ $tenant->move_in_date->format('M d, Y') }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">Not set</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.tenants.edit', $tenant) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#viewModal{{ $tenant->id }}"
                                                            title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.tenants.destroy', $tenant) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Are you sure you want to delete this tenant?')"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewModal{{ $tenant->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-user me-2"></i>{{ $tenant->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Email:</strong></div>
                                                            <div class="col-sm-8">{{ $tenant->email }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Phone:</strong></div>
                                                            <div class="col-sm-8">{{ $tenant->phone }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Address:</strong></div>
                                                            <div class="col-sm-8">{{ $tenant->address ?? 'Not provided' }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Date of Birth:</strong></div>
                                                            <div class="col-sm-8">{{ $tenant->date_of_birth ? $tenant->date_of_birth->format('M d, Y') : 'Not provided' }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>ID Proof:</strong></div>
                                                            <div class="col-sm-8">{{ $tenant->id_proof_type ?? 'Not provided' }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>ID Number:</strong></div>
                                                            <div class="col-sm-8">{{ $tenant->id_proof_number ?? 'Not provided' }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Security Deposit:</strong></div>
                                                            <div class="col-sm-8">₹{{ number_format($tenant->security_deposit ?? 0, 2) }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Move In Date:</strong></div>
                                                            <div class="col-sm-8">{{ $tenant->move_in_date ? $tenant->move_in_date->format('F d, Y') : 'Not set' }}</div>
                                                        </div>
                                                        @if($tenant->move_out_date)
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Move Out Date:</strong></div>
                                                            <div class="col-sm-8">{{ $tenant->move_out_date->format('F d, Y') }}</div>
                                                        </div>
                                                        @endif
                                                        @if($tenant->notes)
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Notes:</strong></div>
                                                            <div class="col-sm-8">{{ $tenant->notes }}</div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-primary">
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
                        @if($tenants->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $tenants->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No Tenants Found</h4>
                            <p class="text-muted">Start by adding tenants to manage their information.</p>
                            <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Add First Tenant
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