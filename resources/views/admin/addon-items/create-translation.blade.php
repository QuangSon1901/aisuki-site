@extends('admin.layouts.app')

@section('title', 'Create Addon Item Translation')

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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create Translation for: {{ $sourceItem->name }}</h5>
                    <span class="badge bg-primary">
                        {{ $sourceItem->language->flag }} {{ $sourceItem->language->name }}
                    </span>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.addon-items.store-translation', $sourceItem->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="language_id" class="form-label">Language <span class="text-danger">*</span></label>
                            <select class="form-select @error('language_id') is-invalid @enderror" id="language_id" name="language_id" required>
                                <option value="">Select language</option>
                                @foreach($availableLanguages as $language)
                                    <option value="{{ $language->id }}" {{ old('language_id') == $language->id ? 'selected' : '' }}>
                                        {{ $language->flag }} {{ $language->name }} {{ $language->is_default ? '(Default)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('language_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Addon Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                <div class="price-input">
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', number_format($sourceItem->price, 2, '.', '')) }}" 
                                           required step="0.01" min="0" {{ old('use_source_price', true) ? 'disabled' : '' }}>
                                    <span class="currency-symbol">{{ setting('currency', '€') }}</span>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="use_source_price" name="use_source_price" 
                                           {{ old('use_source_price', true) ? 'checked' : '' }} value="1">
                                    <label class="form-check-label" for="use_source_price">
                                        Use same price as source item ({{ number_format($sourceItem->price, 2) }} {{ setting('currency', '€') }})
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       {{ old('is_active', $sourceItem->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                                <div class="form-text">Inactive items will not be displayed on the website</div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> The sort order ({{ $sourceItem->sort_order }}) will be copied from the source item.
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.addon-items.edit', $sourceItem->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create Translation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle price field based on use_source_price checkbox
        $('#use_source_price').on('change', function() {
            if (this.checked) {
                $('#price').prop('disabled', true);
            } else {
                $('#price').prop('disabled', false);
            }
        });
        
        // Format price with 2 decimal places
        $('#price').on('blur', function() {
            if (!$(this).prop('disabled')) {
                const value = parseFloat($(this).val()) || 0;
                $(this).val(value.toFixed(2));
            }
        });
    });
</script>
@endpush