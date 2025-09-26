@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-invoice-dollar me-2"></i>Bills Management</h2>
                <div class="btn-group" role="group">
                    <a href="{{ route('house_owner.bills.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Bill
                    </a>
                    <a href="{{ route('house_owner.bill_categories.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-tags me-1"></i>Manage Categories
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Bills Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $bills->count() }}</h4>
                                    <p class="mb-0">Total Bills</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-file-invoice fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $bills->where('status', 'paid')->count() }}</h4>
                                    <p class="mb-0">Paid Bills</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $bills->where('status', 'unpaid')->count() }}</h4>
                                    <p class="mb-0">Unpaid Bills</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $bills->where('status', 'overdue')->count() }}</h4>
                                    <p class="mb-0">Overdue Bills</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('house_owner.bills.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="building_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Buildings</option>
                                    @foreach($buildings as $building)
                                        <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                            {{ $building->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="month" name="bill_month" class="form-control" value="{{ request('bill_month') }}" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('house_owner.bills.index') }}'">
                                    <i class="fas fa-times me-1"></i>Clear Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bills Table -->
            <div class="card">
                <div class="card-body">
                    @if($bills->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Bill ID</th>
                                        <th>Flat</th>
                                        <th>Building</th>
                                        <th>Category</th>
                                        <th>Month</th>
                                        <th>Amount</th>
                                        <th>Total</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bills as $bill)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">#{{ $bill->id }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $bill->flat->flat_number }}</strong>
                                                @if($bill->flat->currentTenant)
                                                    <br><small class="text-muted">{{ $bill->flat->currentTenant->name }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $bill->flat->building->name }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $bill->billCategory->name }}</span>
                                            </td>
                                            <td>{{ $bill->bill_month }}</td>
                                            <td>₹{{ number_format($bill->amount, 2) }}</td>
                                            <td>
                                                <strong>₹{{ number_format($bill->total_amount, 2) }}</strong>
                                                @if($bill->previous_due > 0)
                                                    <br><small class="text-danger">Prev Due: ₹{{ number_format($bill->previous_due, 2) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $bill->due_date->format('M d, Y') }}
                                                @if($bill->due_date->isPast() && $bill->status !== 'paid')
                                                    <br><small class="text-danger">{{ $bill->due_date->diffForHumans() }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($bill->status === 'paid')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Paid
                                                    </span>
                                                    @if($bill->paid_date)
                                                        <br><small class="text-muted">{{ $bill->paid_date->format('M d, Y') }}</small>
                                                    @endif
                                                @elseif($bill->status === 'overdue')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i>Unpaid
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#viewModal{{ $bill->id }}"
                                                            title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($bill->status !== 'paid')
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-success" 
                                                                onclick="markAsPaid({{ $bill->id }})"
                                                                title="Mark as Paid">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('house_owner.bills.edit', $bill) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewModal{{ $bill->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-file-invoice me-2"></i>Bill #{{ $bill->id }} Details
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Bill ID:</strong></div>
                                                            <div class="col-sm-8">#{{ $bill->id }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Flat:</strong></div>
                                                            <div class="col-sm-8">{{ $bill->flat->flat_number }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Building:</strong></div>
                                                            <div class="col-sm-8">{{ $bill->flat->building->name }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Category:</strong></div>
                                                            <div class="col-sm-8">{{ $bill->billCategory->name }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Month:</strong></div>
                                                            <div class="col-sm-8">{{ $bill->bill_month }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Amount:</strong></div>
                                                            <div class="col-sm-8">₹{{ number_format($bill->amount, 2) }}</div>
                                                        </div>
                                                        @if($bill->previous_due > 0)
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Previous Due:</strong></div>
                                                            <div class="col-sm-8">₹{{ number_format($bill->previous_due, 2) }}</div>
                                                        </div>
                                                        @endif
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Total Amount:</strong></div>
                                                            <div class="col-sm-8"><strong>₹{{ number_format($bill->total_amount, 2) }}</strong></div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Due Date:</strong></div>
                                                            <div class="col-sm-8">{{ $bill->due_date->format('F d, Y') }}</div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Status:</strong></div>
                                                            <div class="col-sm-8">
                                                                @if($bill->status === 'paid')
                                                                    <span class="badge bg-success">Paid</span>
                                                                @elseif($bill->status === 'overdue')
                                                                    <span class="badge bg-danger">Overdue</span>
                                                                @else
                                                                    <span class="badge bg-warning text-dark">Unpaid</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if($bill->paid_date)
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Paid Date:</strong></div>
                                                            <div class="col-sm-8">{{ $bill->paid_date->format('F d, Y') }}</div>
                                                        </div>
                                                        @endif
                                                        @if($bill->payment_method)
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Payment Method:</strong></div>
                                                            <div class="col-sm-8">{{ $bill->payment_method }}</div>
                                                        </div>
                                                        @endif
                                                        @if($bill->transaction_reference)
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Transaction Ref:</strong></div>
                                                            <div class="col-sm-8">{{ $bill->transaction_reference }}</div>
                                                        </div>
                                                        @endif
                                                        @if($bill->notes)
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Notes:</strong></div>
                                                            <div class="col-sm-8">{{ $bill->notes }}</div>
                                                        </div>
                                                        @endif
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4"><strong>Created:</strong></div>
                                                            <div class="col-sm-8">{{ $bill->created_at->format('F d, Y \a\t g:i A') }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        @if($bill->status !== 'paid')
                                                            <button type="button" class="btn btn-success" onclick="markAsPaid({{ $bill->id }})">
                                                                <i class="fas fa-check me-1"></i>Mark as Paid
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('house_owner.bills.edit', $bill) }}" class="btn btn-primary">
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
                        @if($bills->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $bills->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-invoice fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No Bills Found</h4>
                            <p class="text-muted">Start by creating bills for your tenants.</p>
                            <a href="{{ route('house_owner.bills.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Create First Bill
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mark as Paid Modal -->
<div class="modal fade" id="markPaidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Mark Bill as Paid
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="markPaidForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="paid_date" class="form-label">Payment Date</label>
                        <input type="date" class="form-control" id="paid_date" name="paid_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="UPI">UPI</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Online">Online</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="transaction_reference" class="form-label">Transaction Reference</label>
                        <input type="text" class="form-control" id="transaction_reference" name="transaction_reference" placeholder="Transaction ID, Cheque No, etc.">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Mark as Paid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function markAsPaid(billId) {
    const form = document.getElementById('markPaidForm');
    form.action = `/house-owner/bills/${billId}/mark-paid`;
    
    const modal = new bootstrap.Modal(document.getElementById('markPaidModal'));
    modal.show();
}
</script>
@endsection