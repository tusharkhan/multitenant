@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Header Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0">
                                <i class="fas fa-tags me-2"></i>Bill Categories Management
                            </h4>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                <i class="fas fa-plus me-1"></i>Add Category
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">
                        Manage different types of bills for your properties. Categories help organize and track different billing types.
                    </p>
                </div>
            </div>

            <!-- Categories Grid -->
            <div class="row">
                @forelse($billCategories as $category)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-{{ $category->is_active ? 'success' : 'secondary' }} text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ $category->name }}</h6>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-light" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button class="dropdown-item" onclick="editCategory({{ $category->id }})">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" onclick="toggleStatus({{ $category->id }})">
                                                    <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }} me-1"></i>
                                                    {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button class="dropdown-item text-danger" onclick="deleteCategory({{ $category->id }})">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($category->description)
                                    <p class="card-text text-muted">{{ $category->description }}</p>
                                @else
                                    <p class="card-text text-muted fst-italic">No description provided</p>
                                @endif
                                
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="border-end">
                                            <h5 class="text-primary mb-0">{{ $category->bills_count ?? 0 }}</h5>
                                            <small class="text-muted">Total Bills</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="text-success mb-0">৳{{ number_format($category->total_amount ?? 0, 2) }}</h5>
                                        <small class="text-muted">Total Amount</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Created {{ $category->created_at->diffForHumans() }}
                                    </small>
                                    <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Bill Categories Found</h5>
                                <p class="text-muted">Create your first bill category to get started with organizing your bills.</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                    <i class="fas fa-plus me-1"></i>Create First Category
                                </button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Quick Stats Card -->
            @if($billCategories->count() > 0)
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-bar me-1"></i>Quick Statistics
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="border-end">
                                    <h4 class="text-primary mb-0">{{ $billCategories->count() }}</h4>
                                    <small class="text-muted">Total Categories</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border-end">
                                    <h4 class="text-success mb-0">{{ $billCategories->where('is_active', true)->count() }}</h4>
                                    <small class="text-muted">Active Categories</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border-end">
                                    <h4 class="text-warning mb-0">{{ $billCategories->sum('bills_count') ?? 0 }}</h4>
                                    <small class="text-muted">Total Bills</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-info mb-0">৳{{ number_format($billCategories->sum('total_amount') ?? 0, 2) }}</h4>
                                <small class="text-muted">Total Revenue</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-1"></i>Create New Bill Category
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('house_owner.bill_categories.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name *</label>
                        <input type="text" 
                               class="form-control" 
                               id="name" 
                               name="name" 
                               placeholder="e.g., Maintenance, Electricity, Water, etc."
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Brief description of this bill category"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1" 
                               checked>
                        <label class="form-check-label" for="is_active">
                            Active Category
                        </label>
                        <small class="form-text text-muted d-block">
                            Only active categories will be available for new bills
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-1"></i>Edit Bill Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Category Name *</label>
                        <input type="text" 
                               class="form-control" 
                               id="edit_name" 
                               name="name" 
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" 
                                  id="edit_description" 
                                  name="description" 
                                  rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="edit_is_active" 
                               name="is_active" 
                               value="1">
                        <label class="form-check-label" for="edit_is_active">
                            Active Category
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i>Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCategory(categoryId) {
    fetch(`/house-owner/bill-categories/${categoryId}/edit`)
        .then(response => response.json())
        .then(category => {
            document.getElementById('edit_name').value = category.name;
            document.getElementById('edit_description').value = category.description || '';
            document.getElementById('edit_is_active').checked = category.is_active;
            document.getElementById('editCategoryForm').action = `/house-owner/bill-categories/${categoryId}`;
            
            const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
            editModal.show();
        })
        .catch(error => {
            alert('Error loading category data');
            console.error('Error:', error);
        });
}

function toggleStatus(categoryId) {
    if (confirm('Are you sure you want to change the status of this category?')) {
        fetch(`/house-owner/bill-categories/${categoryId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating category status');
            }
        })
        .catch(error => {
            alert('Error updating category status');
            console.error('Error:', error);
        });
    }
}

function deleteCategory(categoryId) {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/house-owner/bill-categories/${categoryId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Handle form submission success messages
@if(session('success'))
    setTimeout(() => {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show';
        alert.innerHTML = `
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.container').prepend(alert);
    }, 100);
@endif
</script>
@endsection