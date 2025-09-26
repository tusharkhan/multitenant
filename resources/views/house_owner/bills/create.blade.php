@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-file-invoice-dollar me-2"></i>Create New Bill
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('house_owner.bills.store') }}">
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
                                            onchange="loadFlats(this.value)"
                                            required>
                                        <option value="">Choose Building</option>
                                        @foreach($buildings as $building)
                                            <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
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
                                        <i class="fas fa-home me-1"></i>Select Flat *
                                    </label>
                                    <select class="form-select @error('flat_id') is-invalid @enderror" 
                                            id="flat_id" 
                                            name="flat_id"
                                            required>
                                        <option value="">Choose Flat</option>
                                    </select>
                                    @error('flat_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
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

                            <div class="col-md-6">
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
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">
                                        <i class="fas fa-rupee-sign me-1"></i>Bill Amount *
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" 
                                           name="amount" 
                                           value="{{ old('amount') }}" 
                                           step="0.01"
                                           min="0"
                                           onchange="calculateTotal()"
                                           required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="previous_due" class="form-label">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Previous Due
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('previous_due') is-invalid @enderror" 
                                           id="previous_due" 
                                           name="previous_due" 
                                           value="{{ old('previous_due', 0) }}" 
                                           step="0.01"
                                           min="0"
                                           onchange="calculateTotal()">
                                    @error('previous_due')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="total_amount" class="form-label">
                                        <i class="fas fa-calculator me-1"></i>Total Amount
                                    </label>
                                    <input type="number" 
                                           class="form-control bg-light" 
                                           id="total_amount" 
                                           name="total_amount" 
                                           value="{{ old('total_amount', 0) }}" 
                                           step="0.01"
                                           readonly>
                                </div>
                            </div>
                        </div>

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

                        <div class="mb-3">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Notes
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Any additional notes for this bill">{{ old('notes') }}</textarea>
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
                                               id="send_notification" 
                                               name="send_notification" 
                                               value="1" 
                                               {{ old('send_notification', 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="send_notification">
                                            <i class="fas fa-envelope me-1"></i>Send Email Notification
                                        </label>
                                        <small class="form-text text-muted">
                                            Notify tenant via email about this bill
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-flag me-1"></i>Initial Status
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status">
                                        <option value="unpaid" {{ old('status', 'unpaid') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details (shown if status is paid) -->
                        <div id="paymentDetails" style="display: none;">
                            <hr>
                            <h6><i class="fas fa-credit-card me-1"></i>Payment Details</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="paid_date" class="form-label">Payment Date</label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="paid_date" 
                                               name="paid_date" 
                                               value="{{ old('paid_date', date('Y-m-d')) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Payment Method</label>
                                        <select class="form-select" id="payment_method" name="payment_method">
                                            <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                            <option value="UPI" {{ old('payment_method') == 'UPI' ? 'selected' : '' }}>UPI</option>
                                            <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                            <option value="Online" {{ old('payment_method') == 'Online' ? 'selected' : '' }}>Online</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="transaction_reference" class="form-label">Transaction Reference</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="transaction_reference" 
                                               name="transaction_reference" 
                                               value="{{ old('transaction_reference') }}"
                                               placeholder="Transaction ID, Cheque No, etc.">
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
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Create Bill
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
    flatSelect.innerHTML = '<option value="">Loading...</option>';
    
    if (buildingId) {
        fetch(`/house-owner/buildings/${buildingId}/flats-json`)
            .then(response => response.json())
            .then(flats => {
                flatSelect.innerHTML = '<option value="">Choose Flat</option>';
                flats.forEach(flat => {
                    const tenantInfo = flat.current_tenant ? ` - ${flat.current_tenant.name}` : ' - Vacant';
                    const option = document.createElement('option');
                    option.value = flat.id;
                    option.textContent = flat.flat_number + tenantInfo;
                    flatSelect.appendChild(option);
                });
            })
            .catch(error => {
                flatSelect.innerHTML = '<option value="">Error loading flats</option>';
                console.error('Error:', error);
            });
    } else {
        flatSelect.innerHTML = '<option value="">Choose Flat</option>';
    }
}

function calculateTotal() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const previousDue = parseFloat(document.getElementById('previous_due').value) || 0;
    const total = amount + previousDue;
    document.getElementById('total_amount').value = total.toFixed(2);
}

// Show/hide payment details based on status
document.getElementById('status').addEventListener('change', function() {
    const paymentDetails = document.getElementById('paymentDetails');
    if (this.value === 'paid') {
        paymentDetails.style.display = 'block';
    } else {
        paymentDetails.style.display = 'none';
    }
});

// Load flats on page load if building is selected
document.addEventListener('DOMContentLoaded', function() {
    const buildingId = document.getElementById('building_id').value;
    if (buildingId) {
        loadFlats(buildingId);
    }
    
    // Calculate total on page load
    calculateTotal();
    
    // Show payment details if status is paid
    if (document.getElementById('status').value === 'paid') {
        document.getElementById('paymentDetails').style.display = 'block';
    }
});

</script>
@endsection