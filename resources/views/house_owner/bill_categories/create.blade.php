@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Create Bill Category
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('house_owner.bill_categories.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-tag me-1"></i>Category Name *
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="e.g., Maintenance, Electricity, Water, Internet"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Brief description of this bill category">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <i class="fas fa-check-circle me-1"></i>Active Category
                            </label>
                            <small class="form-text text-muted d-block">
                                Only active categories will be available for creating new bills
                            </small>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('house_owner.bill_categories.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Back to Categories
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Create Category
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Common Categories Suggestions -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-1"></i>Common Bill Categories
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Here are some commonly used bill categories you might want to create:</p>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-tools text-primary me-2"></i><strong>Maintenance</strong> - Building upkeep and repairs</li>
                                <li><i class="fas fa-bolt text-warning me-2"></i><strong>Electricity</strong> - Power consumption charges</li>
                                <li><i class="fas fa-tint text-info me-2"></i><strong>Water</strong> - Water usage and supply</li>
                                <li><i class="fas fa-fire text-danger me-2"></i><strong>Gas</strong> - Gas supply charges</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-wifi text-success me-2"></i><strong>Internet</strong> - Internet connection fees</li>
                                <li><i class="fas fa-shield-alt text-secondary me-2"></i><strong>Security</strong> - Security services</li>
                                <li><i class="fas fa-broom text-primary me-2"></i><strong>Cleaning</strong> - Cleaning services</li>
                                <li><i class="fas fa-car text-dark me-2"></i><strong>Parking</strong> - Parking space fees</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection