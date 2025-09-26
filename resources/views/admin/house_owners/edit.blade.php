@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>Edit House Owner: {{ $houseOwner->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.house_owners.update', $houseOwner) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user me-1"></i>Full Name *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $houseOwner->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>Email Address *
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $houseOwner->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-1"></i>Phone Number
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $houseOwner->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-1"></i>New Password
                                    </label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password">
                                    <small class="form-text text-muted">
                                        Leave blank to keep current password
                                    </small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Address
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      placeholder="Enter full address">{{ old('address', $houseOwner->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $houseOwner->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-check-circle me-1"></i>Active Account
                                </label>
                                <small class="form-text text-muted">
                                    Unchecked accounts will not be able to login
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.house_owners.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Back to List
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Update House Owner
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-1"></i>Account Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="stat-item">
                                <i class="fas fa-building fa-2x text-primary mb-2"></i>
                                <h5>{{ $houseOwner->buildings()->count() }}</h5>
                                <small class="text-muted">Buildings</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <i class="fas fa-home fa-2x text-success mb-2"></i>
                                <h5>{{ $houseOwner->buildings()->withCount('flats')->get()->sum('flats_count') }}</h5>
                                <small class="text-muted">Total Flats</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <i class="fas fa-users fa-2x text-warning mb-2"></i>
                                <h5>
                                    {{ \App\Models\Tenant::whereHas('flat.building', function($query) use ($houseOwner) {
                                        $query->where('owner_id', $houseOwner->id);
                                    })->where('is_active', true)->count() }}
                                </h5>
                                <small class="text-muted">Active Tenants</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <i class="fas fa-calendar fa-2x text-info mb-2"></i>
                                <h5>{{ $houseOwner->created_at->diffForHumans() }}</h5>
                                <small class="text-muted">Member Since</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-item {
    padding: 15px;
    border-radius: 8px;
    background: #f8f9fa;
}
</style>
@endsection