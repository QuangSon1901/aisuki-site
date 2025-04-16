@extends('admin.layouts.app')

@section('title', 'Edit Addon Item')

@push('styles')
<style>
    .price-input {
        position: relative;
    }
    .price-input .currency-symbol {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        color: #6c757d;
    }
    .language-nav .nav-link {
        padding: 8px 15px;
        border-radius: 0;
        border-bottom: 2px solid transparent;
        color: #333;
    }
    .language-nav .nav-link.active {
        border-bottom: 2px solid #e61c23;
        color: #e61c23;
        background-color: transparent;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Language Navigation -->
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 bg-light">
                        <h5 class="mb-0">Available Translations</h5>
                        <a href="{{ route('admin.addon-items.create-translation', $addonItem->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Translation
                        </a>
                    </div>
                    <ul class="nav nav-tabs language-nav">
                        <li class="nav-item">
                            <a href="{{ route('admin.addon-items.edit', $addonItem->id) }}" 
                               class="nav-link active">
                                <span class="me-1">{{ $addonItem->language->flag }}</span>
                                {{ $addonItem->language->name }}
                                @if($addonItem->language->is_default)
                                    <span class="badge bg-success ms-1">Default</span>
                                @endif
                            </a>
                        </li>
                        @foreach($translations as $translation)
                            <li class="nav-item">
                                <a href="{{ route('admin.addon-items.edit', $translation->id) }}" 
                                   class="nav-link">
                                    <span class="me-1">{{ $translation->language->flag }}</span>
                                    {{ $translation->language->name }}
                                    @if($translation->language->is_default)
                                        <span class="badge bg-success ms-1">Default</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Edit Addon Item: {{ $addonItem->name }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.addon-items.update', $addonItem->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Addon Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $addonItem->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                        <div class="price-input">
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price', number_format($addonItem->price, 2, '.', '')) }}" required step="0.01" min="0">
                                            <span class="currency-symbol">{{ setting('currency', '€') }}</span>
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="sort_order" class="form-label">Sort Order</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                               id="sort_order" name="sort_order" value="{{ old('sort_order', $addonItem->sort_order) }}" min="0">
                                        <div class="form-text">Items with lower sort order will be displayed first</div>
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               {{ old('is_active', $addonItem->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                        <div class="form-text">Inactive items will not be displayed on the website</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.addon-items.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update Addon Item
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i> Item Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Item ID:</span>
                                    <span class="fw-bold">{{ $addonItem->id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Language:</span>
                                    <span>
                                        {{ $addonItem->language->flag }} {{ $addonItem->language->name }}
                                        @if($addonItem->language->is_default)
                                            <span class="badge bg-success ms-1">Default</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Status:</span>
                                    <span>
                                        @if($addonItem->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Mass ID:</span>
                                    <span>{{ $addonItem->mass_id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Created:</span>
                                    <span>{{ $addonItem->created_at->format('M d, Y H:i') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Last Updated:</span>
                                    <span>{{ $addonItem->updated_at->format('M d, Y H:i') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Format price with 2 decimal places
        $('#price').on('blur', function() {
            const value = parseFloat($(this).val()) || 0;
            $(this).val(value.toFixed(2));
        });
    });
</script>
@endpush