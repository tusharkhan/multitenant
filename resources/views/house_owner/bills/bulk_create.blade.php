@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-layer-group me-2"></i>Bulk Bill Creation
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Bulk Bill Creation:</strong> Create bills for all flats in a selected building at once. 
                        This is useful for monthly recurring bills like maintenance, electricity, etc.
                    </div>

                    <form method="POST" action="{{ route('house_owner.bills.bulk_store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="building_id" class="form-label">
                                        <i class="fas fa-building me-1"></i>Select Building *
                                    </label>
                                    <select class="form-select @error('building_id') is-invalid @enderror" 
                                            id="building_id" 
                                            name="building_id"
                                            onchange="loadBuildingInfo(this.value)"
                                            required>
                                        <option value="">Choose Building</option>
                                        @foreach($buildings as $building)
                                            <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                                {{ $building->name }} ({{ $building->flats->count() }} flats)
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
                                    <label for="bill_category_id" class="form-label">
                                        <i class="fas fa-tags me-1"></i>Bill Category *
                                    </label>
                                    <select class="form-select @error('bill_category_id') is-invalid @enderror" 
                                            id="bill_category_id" 
                                            name="bill_category_id"
                                            required>
                                        <option value="">Choose Category</option>
                                        @foreach($billCategories as $category)
                                            <option value="{{ $category->id }}" {{ old('bill_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bill_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="bill_month" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Bill Month *
                                    </label>
                                    <input type="month" 
                                           class="form-control @error('bill_month') is-invalid @enderror" 
                                           id="bill_month" 
                                           name="bill_month" 
                                           value="{{ old('bill_month', date('Y-m')) }}"
                                           required>
                                    @error('bill_month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">
                                        <i class="fas fa-rupee-sign me-1"></i>Bill Amount per Flat *
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" 
                                           name="amount" 
                                           value="{{ old('amount') }}" 
                                           step="0.01"
                                           min="0"
                                           required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">
                                        <i class="fas fa-calendar-alt me-1"></i>Due Date *
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('due_date') is-invalid @enderror" 
                                           id="due_date" 
                                           name="due_date" 
                                           value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}"
                                           required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Notes (Optional)
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Any additional notes for these bills">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="send_notifications" 
                                           name="send_notifications" 
                                           value="1" 
                                           {{ old('send_notifications', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="send_notifications">
                                        <i class="fas fa-envelope me-1"></i>Send Email Notifications
                                    </label>
                                    <small class="form-text text-muted d-block">
                                        Notify tenants via email about new bills
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="skip_existing" 
                                           name="skip_existing" 
                                           value="1" 
                                           {{ old('skip_existing', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="skip_existing">
                                        <i class="fas fa-skip-forward me-1"></i>Skip Existing Bills
                                    </label>
                                    <small class="form-text text-muted d-block">
                                        Skip flats that already have bills for this month
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Building Information Card (hidden initially) -->
                        <div id="buildingInfo" class="card bg-light mb-4" style="display: none;">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-1"></i>Building Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <h5 class="text-primary mb-0" id="totalFlats">0</h5>
                                        <small class="text-muted">Total Flats</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h5 class="text-success mb-0" id="occupiedFlats">0</h5>
                                        <small class="text-muted">Occupied Flats</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h5 class="text-warning mb-0" id="vacantFlats">0</h5>
                                        <small class="text-muted">Vacant Flats</small>
                                    </div>
                                    <div class="col-md-3">
                                        <h5 class="text-info mb-0" id="totalAmount">৳0</h5>
                                        <small class="text-muted">Total Amount</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('house_owner.bills.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Back to Bills
                                    </a>
                                    <button type="submit" class="btn btn-success" id="createBillsBtn">
                                        <i class="fas fa-layer-group me-1"></i>Create Bulk Bills
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Instructions Card -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-question-circle me-1"></i>Bulk Bill Creation Instructions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>How it works:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Select a building and bill category</li>
                                <li><i class="fas fa-check text-success me-2"></i>Set the amount and due date</li>
                                <li><i class="fas fa-check text-success me-2"></i>Bills will be created for all flats in the building</li>
                                <li><i class="fas fa-check text-success me-2"></i>Previous dues will be automatically calculated</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Important Notes:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-info text-info me-2"></i>Only occupied flats will receive bills</li>
                                <li><i class="fas fa-info text-info me-2"></i>Existing bills for the same month will be skipped</li>
                                <li><i class="fas fa-info text-info me-2"></i>Email notifications are optional</li>
                                <li><i class="fas fa-info text-info me-2"></i>You can edit individual bills later</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadBuildingInfo(buildingId) {
    const buildingInfoCard = document.getElementById('buildingInfo');
    const amountInput = document.getElementById('amount');
    
    if (buildingId) {
        // Find the selected building from the dropdown
        const buildingSelect = document.getElementById('building_id');
        const selectedOption = buildingSelect.options[buildingSelect.selectedIndex];
        const buildingText = selectedOption.text;
        
        // Extract flat count from the option text (assuming format: "Building Name (X flats)")
        const flatCountMatch = buildingText.match(/\((\d+) flats\)/);
        const totalFlats = flatCountMatch ? parseInt(flatCountMatch[1]) : 0;
        
        // Update building info (you can make an AJAX call here for real data)
        document.getElementById('totalFlats').textContent = totalFlats;
        document.getElementById('occupiedFlats').textContent = Math.floor(totalFlats * 0.8); // Assume 80% occupied
        document.getElementById('vacantFlats').textContent = Math.floor(totalFlats * 0.2); // Assume 20% vacant
        
        // Calculate total amount
        calculateTotalAmount();
        
        buildingInfoCard.style.display = 'block';
    } else {
        buildingInfoCard.style.display = 'none';
    }
}

function calculateTotalAmount() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const occupiedFlats = parseInt(document.getElementById('occupiedFlats').textContent) || 0;
    const totalAmount = amount * occupiedFlats;
    
    document.getElementById('totalAmount').textContent = '৳' + totalAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Update total amount when amount changes
document.getElementById('amount').addEventListener('input', calculateTotalAmount);

// Load building info on page load if building is selected
document.addEventListener('DOMContentLoaded', function() {
    const buildingId = document.getElementById('building_id').value;
    if (buildingId) {
        loadBuildingInfo(buildingId);
    }
});

// Form submission confirmation
document.getElementById('createBillsBtn').addEventListener('click', function(e) {
    const buildingSelect = document.getElementById('building_id');
    const selectedBuilding = buildingSelect.options[buildingSelect.selectedIndex].text;
    const amount = document.getElementById('amount').value;
    const occupiedFlats = document.getElementById('occupiedFlats').textContent;
    
    if (!confirm(`Are you sure you want to create bills for ${occupiedFlats} flats in "${selectedBuilding}" with amount ৳${amount} each?`)) {
        e.preventDefault();
    }
});
</script>
@endsection