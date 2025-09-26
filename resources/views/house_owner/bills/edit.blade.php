@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Bill
                        <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : 'danger' }} ms-2">
                            {{ ucfirst($bill->status) }}
                        </span>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Bill Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Building</h6>
                                    <strong>{{ $bill->flat->building->name }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Flat</h6>
                                    <strong>{{ $bill->flat->flat_number }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Tenant</h6>
                                    <strong>{{ $bill->flat->currentTenant->name ?? 'Vacant' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Category</h6>
                                    <strong>{{ $bill->billCategory->name }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('house_owner.bills.update', $bill) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bill_month" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Bill Month *
                                    </label>
                                    <input type="month" 
                                           class="form-control @error('bill_month') is-invalid @enderror" 
                                           id="bill_month" 
                                           name="bill_month" 
                                           value="{{ old('bill_month', date('Y-m', strtotime($bill->bill_month))) }}"
                                           required>
                                    @error('bill_month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">
                                        <i class="fas fa-calendar-alt me-1"></i>Due Date *
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('due_date') is-invalid @enderror" 
                                           id="due_date" 
                                           name="due_date" 
                                           value="{{ old('due_date', $bill->due_date) }}"
                                           required>
                                    @error('due_date')
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
                                           value="{{ old('amount', $bill->amount) }}" 
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
                                           value="{{ old('previous_due', $bill->previous_due) }}" 
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
                                           value="{{ old('total_amount', $bill->total_amount) }}" 
                                           step="0.01"
                                           readonly>
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
                                      placeholder="Any additional notes for this bill">{{ old('notes', $bill->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-flag me-1"></i>Bill Status
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status">
                                        <option value="unpaid" {{ old('status', $bill->status) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        <option value="paid" {{ old('status', $bill->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="send_notification" 
                                               name="send_notification" 
                                               value="1">
                                        <label class="form-check-label" for="send_notification">
                                            <i class="fas fa-envelope me-1"></i>Send Email Notification
                                        </label>
                                        <small class="form-text text-muted d-block">
                                            Notify tenant about bill changes
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div id="paymentDetails" style="{{ $bill->status === 'paid' ? '' : 'display: none;' }}">
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
                                               value="{{ old('paid_date', $bill->paid_date) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Payment Method</label>
                                        <select class="form-select" id="payment_method" name="payment_method">
                                            <option value="Cash" {{ old('payment_method', $bill->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="Bank Transfer" {{ old('payment_method', $bill->payment_method) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                            <option value="UPI" {{ old('payment_method', $bill->payment_method) == 'UPI' ? 'selected' : '' }}>UPI</option>
                                            <option value="Cheque" {{ old('payment_method', $bill->payment_method) == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                            <option value="Online" {{ old('payment_method', $bill->payment_method) == 'Online' ? 'selected' : '' }}>Online</option>
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
                                               value="{{ old('transaction_reference', $bill->transaction_reference) }}"
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
                                    <div>
                                        <button type="submit" class="btn btn-warning me-2">
                                            <i class="fas fa-save me-1"></i>Update Bill
                                        </button>
                                        @if($bill->status === 'unpaid')
                                            <form method="POST" action="{{ route('house_owner.bills.mark_paid', $bill) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success" onclick="return confirm('Mark this bill as paid?')">
                                                    <i class="fas fa-check me-1"></i>Mark as Paid
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bill History Card -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-1"></i>Bill History & Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Created Information</h6>
                            <p class="mb-1"><strong>Created:</strong> {{ $bill->created_at->format('d M Y, h:i A') }}</p>
                            <p class="mb-1"><strong>Last Updated:</strong> {{ $bill->updated_at->format('d M Y, h:i A') }}</p>
                            @if($bill->status === 'paid')
                                <p class="mb-0"><strong>Paid Date:</strong> {{ $bill->paid_date ? Carbon\Carbon::parse($bill->paid_date)->format('d M Y') : 'Not specified' }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Actions</h6>
                            <div class="btn-group-vertical d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="printBill()">
                                    <i class="fas fa-print me-1"></i>Print Bill
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="emailBill()">
                                    <i class="fas fa-envelope me-1"></i>Email Bill
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="duplicateBill()">
                                    <i class="fas fa-copy me-1"></i>Duplicate Bill
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
        // Set paid date to today if not already set
        if (!document.getElementById('paid_date').value) {
            document.getElementById('paid_date').value = new Date().toISOString().split('T')[0];
        }
    } else {
        paymentDetails.style.display = 'none';
    }
});

// Calculate total on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});

function printBill() {
    window.print();
}

function emailBill() {
    alert('Email functionality will be implemented soon!');
}

function duplicateBill() {
    if (confirm('Create a duplicate of this bill?')) {
        window.location.href = '{{ route("house_owner.bills.create") }}?duplicate={{ $bill->id }}';
    }
}
</script>
@endsection