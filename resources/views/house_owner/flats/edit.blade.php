@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-home me-2"></i>Edit Flat {{ $flat->flat_number }} in {{ $building->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('house_owner.flats.update', [$building, $flat]) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="flat_number" class="form-label">
                                        <i class="fas fa-door-open me-1"></i>Flat Number *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('flat_number') is-invalid @enderror" 
                                           id="flat_number" 
                                           name="flat_number" 
                                           value="{{ old('flat_number', $flat->flat_number) }}" 
                                           placeholder="e.g., 101, A-502, etc."
                                           required>
                                    @error('flat_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="owner_name" class="form-label">
                                        <i class="fas fa-user me-1"></i>Owner Name *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('owner_name') is-invalid @enderror" 
                                           id="owner_name" 
                                           name="owner_name" 
                                           value="{{ old('owner_name', $flat->owner_name) }}" 
                                           required>
                                    @error('owner_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="owner_phone" class="form-label">
                                        <i class="fas fa-phone me-1"></i>Owner Phone
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('owner_phone') is-invalid @enderror" 
                                           id="owner_phone" 
                                           name="owner_phone" 
                                           value="{{ old('owner_phone', $flat->owner_phone) }}">
                                    @error('owner_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="owner_email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>Owner Email
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('owner_email') is-invalid @enderror" 
                                           id="owner_email" 
                                           name="owner_email" 
                                           value="{{ old('owner_email', $flat->owner_email) }}">
                                    @error('owner_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="owner_address" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Owner Address
                            </label>
                            <textarea class="form-control @error('owner_address') is-invalid @enderror" 
                                      id="owner_address" 
                                      name="owner_address" 
                                      rows="3" 
                                      placeholder="Owner's permanent address">{{ old('owner_address', $flat->owner_address) }}</textarea>
                            @error('owner_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="carpet_area" class="form-label">
                                        <i class="fas fa-ruler me-1"></i>Carpet Area (sq ft)
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('carpet_area') is-invalid @enderror" 
                                           id="carpet_area" 
                                           name="carpet_area" 
                                           value="{{ old('carpet_area', $flat->carpet_area) }}" 
                                           step="0.01"
                                           min="0">
                                    @error('carpet_area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="bedrooms" class="form-label">
                                        <i class="fas fa-bed me-1"></i>Bedrooms
                                    </label>
                                    <select class="form-select @error('bedrooms') is-invalid @enderror" 
                                            id="bedrooms" 
                                            name="bedrooms">
                                        <option value="">Select Bedrooms</option>
                                        <option value="1" {{ old('bedrooms', $flat->bedrooms) == '1' ? 'selected' : '' }}>1 BHK</option>
                                        <option value="2" {{ old('bedrooms', $flat->bedrooms) == '2' ? 'selected' : '' }}>2 BHK</option>
                                        <option value="3" {{ old('bedrooms', $flat->bedrooms) == '3' ? 'selected' : '' }}>3 BHK</option>
                                        <option value="4" {{ old('bedrooms', $flat->bedrooms) == '4' ? 'selected' : '' }}>4 BHK</option>
                                        <option value="5" {{ old('bedrooms', $flat->bedrooms) == '5' ? 'selected' : '' }}>5+ BHK</option>
                                    </select>
                                    @error('bedrooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="bathrooms" class="form-label">
                                        <i class="fas fa-bath me-1"></i>Bathrooms
                                    </label>
                                    <select class="form-select @error('bathrooms') is-invalid @enderror" 
                                            id="bathrooms" 
                                            name="bathrooms">
                                        <option value="">Select Bathrooms</option>
                                        <option value="1" {{ old('bathrooms', $flat->bathrooms) == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ old('bathrooms', $flat->bathrooms) == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ old('bathrooms', $flat->bathrooms) == '3' ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ old('bathrooms', $flat->bathrooms) == '4' ? 'selected' : '' }}>4+</option>
                                    </select>
                                    @error('bathrooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Notes
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Any additional notes about this flat">{{ old('notes', $flat->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_occupied" 
                                               name="is_occupied" 
                                               value="1" 
                                               {{ old('is_occupied', $flat->is_occupied) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_occupied">
                                            <i class="fas fa-user-check me-1"></i>Currently Occupied
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1" 
                                               {{ old('is_active', $flat->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            <i class="fas fa-check-circle me-1"></i>Active Flat
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('house_owner.flats.index', $building) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Back to Flats
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Update Flat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Tenant Information -->
            @if($flat->is_occupied && $flat->currentTenant)
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-user me-1"></i>Current Tenant Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Name:</strong> {{ $flat->currentTenant->name }}<br>
                            <strong>Phone:</strong> {{ $flat->currentTenant->phone }}<br>
                            <strong>Email:</strong> {{ $flat->currentTenant->email }}
                        </div>
                        <div class="col-md-6">
                            <strong>Move In:</strong> {{ $flat->currentTenant->move_in_date ? $flat->currentTenant->move_in_date->format('M d, Y') : 'Not set' }}<br>
                            <strong>Security Deposit:</strong> ₹{{ number_format($flat->currentTenant->security_deposit ?? 0, 2) }}<br>
                            <strong>Status:</strong> <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Building Information -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-building me-1"></i>Building Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Building Name:</strong> {{ $building->name }}<br>
                            <strong>Address:</strong> {{ $building->address }}<br>
                            <strong>City:</strong> {{ $building->city }}, {{ $building->state }}
                        </div>
                        <div class="col-md-6">
                            <strong>Total Flats:</strong> {{ $building->flats()->count() }}<br>
                            <strong>Occupied:</strong> {{ $building->flats()->where('is_occupied', true)->count() }}<br>
                            <strong>Vacant:</strong> {{ $building->flats()->where('is_occupied', false)->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection