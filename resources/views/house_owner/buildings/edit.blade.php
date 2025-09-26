@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-building me-2"></i>Edit Building: {{ $building->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('house_owner.buildings.update', $building) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-building me-1"></i>Building Name *
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $building->name) }}" 
                                   placeholder="Enter building name"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Address *
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      placeholder="Enter complete address"
                                      required>{{ old('address', $building->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">
                                        <i class="fas fa-city me-1"></i>City *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('city') is-invalid @enderror" 
                                           id="city" 
                                           name="city" 
                                           value="{{ old('city', $building->city) }}" 
                                           required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="state" class="form-label">
                                        <i class="fas fa-flag me-1"></i>State *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('state') is-invalid @enderror" 
                                           id="state" 
                                           name="state" 
                                           value="{{ old('state', $building->state) }}" 
                                           required>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="postal_code" class="form-label">
                                <i class="fas fa-mail-bulk me-1"></i>Postal Code *
                            </label>
                            <input type="text" 
                                   class="form-control @error('postal_code') is-invalid @enderror" 
                                   id="postal_code" 
                                   name="postal_code" 
                                   value="{{ old('postal_code', $building->postal_code) }}" 
                                   required>
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-info-circle me-1"></i>Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Enter building description, amenities, etc.">{{ old('description', $building->description) }}</textarea>
                            @error('description')
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
                                       {{ old('is_active', $building->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-check-circle me-1"></i>Active Building
                                </label>
                                <small class="form-text text-muted">
                                    Inactive buildings will not be available for flat management
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('house_owner.buildings.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Back to Buildings
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Update Building
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Building Statistics -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-1"></i>Building Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="stat-item">
                                <i class="fas fa-home fa-2x text-primary mb-2"></i>
                                <h5>{{ $building->flats()->count() }}</h5>
                                <small class="text-muted">Total Flats</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <i class="fas fa-users fa-2x text-success mb-2"></i>
                                <h5>{{ $building->flats()->where('is_occupied', true)->count() }}</h5>
                                <small class="text-muted">Occupied</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <i class="fas fa-home fa-2x text-warning mb-2"></i>
                                <h5>{{ $building->flats()->where('is_occupied', false)->count() }}</h5>
                                <small class="text-muted">Vacant</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <i class="fas fa-calendar fa-2x text-info mb-2"></i>
                                <h5>{{ $building->created_at->diffForHumans() }}</h5>
                                <small class="text-muted">Created</small>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('house_owner.flats.index', $building) }}" class="btn btn-success">
                            <i class="fas fa-cog me-1"></i>Manage Flats
                        </a>
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