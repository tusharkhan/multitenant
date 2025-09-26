@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>Edit Tenant: {{ $tenant->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.tenants.update', $tenant) }}">
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
                                           value="{{ old('name', $tenant->name) }}" 
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
                                           value="{{ old('email', $tenant->email) }}" 
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
                                        <i class="fas fa-phone me-1"></i>Phone Number *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $tenant->phone) }}" 
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Date of Birth
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" 
                                           name="date_of_birth" 
                                           value="{{ old('date_of_birth', $tenant->date_of_birth ? $tenant->date_of_birth->format('Y-m-d') : '') }}">
                                    @error('date_of_birth')
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
                                      placeholder="Enter full address">{{ old('address', $tenant->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_proof_type" class="form-label">
                                        <i class="fas fa-id-card me-1"></i>ID Proof Type
                                    </label>
                                    <select class="form-select @error('id_proof_type') is-invalid @enderror" 
                                            id="id_proof_type" 
                                            name="id_proof_type">
                                        <option value="">Select ID Proof Type</option>
                                        <option value="Aadhar Card" {{ old('id_proof_type', $tenant->id_proof_type) == 'Aadhar Card' ? 'selected' : '' }}>Aadhar Card</option>
                                        <option value="PAN Card" {{ old('id_proof_type', $tenant->id_proof_type) == 'PAN Card' ? 'selected' : '' }}>PAN Card</option>
                                        <option value="Driving License" {{ old('id_proof_type', $tenant->id_proof_type) == 'Driving License' ? 'selected' : '' }}>Driving License</option>
                                        <option value="Passport" {{ old('id_proof_type', $tenant->id_proof_type) == 'Passport' ? 'selected' : '' }}>Passport</option>
                                        <option value="Voter ID" {{ old('id_proof_type', $tenant->id_proof_type) == 'Voter ID' ? 'selected' : '' }}>Voter ID</option>
                                    </select>
                                    @error('id_proof_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_proof_number" class="form-label">
                                        <i class="fas fa-hashtag me-1"></i>ID Proof Number
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('id_proof_number') is-invalid @enderror" 
                                           id="id_proof_number" 
                                           name="id_proof_number" 
                                           value="{{ old('id_proof_number', $tenant->id_proof_number) }}">
                                    @error('id_proof_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="building_id" class="form-label">
                                        <i class="fas fa-building me-1"></i>Select Building
                                    </label>
                                    <select class="form-select @error('building_id') is-invalid @enderror" 
                                            id="building_id" 
                                            name="building_id"
                                            onchange="loadFlats(this.value)">
                                        <option value="">Select Building</option>
                                        @foreach($buildings as $building)
                                            <option value="{{ $building->id }}" 
                                                    {{ old('building_id', $tenant->flat?->building_id) == $building->id ? 'selected' : '' }}>
                                                {{ $building->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('building_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="flat_id" class="form-label">
                                        <i class="fas fa-home me-1"></i>Select Flat
                                    </label>
                                    <select class="form-select @error('flat_id') is-invalid @enderror" 
                                            id="flat_id" 
                                            name="flat_id">
                                        <option value="">Select Flat</option>
                                    </select>
                                    @error('flat_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="move_in_date" class="form-label">
                                        <i class="fas fa-calendar-plus me-1"></i>Move In Date
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('move_in_date') is-invalid @enderror" 
                                           id="move_in_date" 
                                           name="move_in_date" 
                                           value="{{ old('move_in_date', $tenant->move_in_date ? $tenant->move_in_date->format('Y-m-d') : '') }}">
                                    @error('move_in_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="move_out_date" class="form-label">
                                        <i class="fas fa-calendar-minus me-1"></i>Move Out Date
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('move_out_date') is-invalid @enderror" 
                                           id="move_out_date" 
                                           name="move_out_date" 
                                           value="{{ old('move_out_date', $tenant->move_out_date ? $tenant->move_out_date->format('Y-m-d') : '') }}">
                                    @error('move_out_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="security_deposit" class="form-label">
                                        <i class="fas fa-rupee-sign me-1"></i>Security Deposit
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('security_deposit') is-invalid @enderror" 
                                           id="security_deposit" 
                                           name="security_deposit" 
                                           value="{{ old('security_deposit', $tenant->security_deposit) }}" 
                                           step="0.01"
                                           min="0">
                                    @error('security_deposit')
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
                                      placeholder="Any additional notes or comments">{{ old('notes', $tenant->notes) }}</textarea>
                            @error('notes')
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
                                       {{ old('is_active', $tenant->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-check-circle me-1"></i>Active Tenant
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Back to List
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Update Tenant
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadFlats(buildingId) {
    const flatSelect = document.getElementById('flat_id');
    flatSelect.innerHTML = '<option value="">Select Flat</option>';
    
    if (buildingId) {
        // Load flats for the selected building
        const buildings = @json($buildings);
        const building = buildings.find(b => b.id == buildingId);
        
        if (building && building.flats) {
            building.flats.forEach(flat => {
                const option = document.createElement('option');
                option.value = flat.id;
                option.textContent = flat.flat_number;
                flatSelect.appendChild(option);
            });
        }
    }
}

// Load flats on page load if building is selected
document.addEventListener('DOMContentLoaded', function() {
    const buildingId = document.getElementById('building_id').value;
    if (buildingId) {
        loadFlats(buildingId);
        // Restore selected flat
        const selectedFlat = "{{ old('flat_id', $tenant->flat_id) }}";
        if (selectedFlat) {
            document.getElementById('flat_id').value = selectedFlat;
        }
    }
});
</script>
@endsection