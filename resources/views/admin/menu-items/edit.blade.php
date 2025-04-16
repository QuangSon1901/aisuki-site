@extends('admin.layouts.app')

@section('title', 'Edit Menu Item')

@push('styles')
<style>
    .image-preview {
        max-width: 100%;
        max-height: 200px;
        margin-top: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
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
                        <a href="{{ route('admin.menu-items.create-translation', $menuItem->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Translation
                        </a>
                    </div>
                    <ul class="nav nav-tabs language-nav">
                        <li class="nav-item">
                            <a href="{{ route('admin.menu-items.edit', $menuItem->id) }}" 
                               class="nav-link active">
                                <span class="me-1">{{ $menuItem->language->flag }}</span>
                                {{ $menuItem->language->name }}
                                @if($menuItem->language->is_default)
                                    <span class="badge bg-success ms-1">Default</span>
                                @endif
                            </a>
                        </li>
                        @foreach($translations as $translation)
                            <li class="nav-item">
                                <a href="{{ route('admin.menu-items.edit', $translation->id) }}" 
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
                            <h5 class="mb-0">Edit Menu Item: {{ $menuItem->name }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.menu-items.update', $menuItem->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                            @if($categories->isEmpty())
                                                <option value="">No categories available in this language</option>
                                            @else
                                                <option value="">Select a category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id', $menuItem->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="code" class="form-label">Item Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                               id="code" name="code" value="{{ old('code', $menuItem->code) }}" required>
                                        <div class="form-text">Unique code for this menu item</div>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $menuItem->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $menuItem->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                        <div class="price-input">
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price', number_format($menuItem->price, 2, '.', '')) }}" required step="0.01" min="0">
                                            <span class="currency-symbol">{{ setting('currency', '€') }}</span>
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="sort_order" class="form-label">Sort Order</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                               id="sort_order" name="sort_order" value="{{ old('sort_order', $menuItem->sort_order) }}" min="0">
                                        <div class="form-text">Items with lower sort order will be displayed first</div>
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">Item Image</label>
                                    <input type="file" class="form-control image-preview-input @error('image') is-invalid @enderror" 
                                           id="image" name="image" data-preview="image_preview" accept="image/*">
                                    <div class="form-text">Recommended image size: 800x600 pixels. Leave empty to keep current image.</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($menuItem->image)
                                        <div class="my-2">
                                            <img id="image_preview" src="{{ asset($menuItem->image) }}" class="image-preview">
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                            <label class="form-check-label" for="remove_image">
                                                Remove current image
                                            </label>
                                        </div>
                                    @else
                                        <img id="image_preview" src="" class="image-preview mt-2" style="display: none;">
                                    @endif
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   {{ old('is_active', $menuItem->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active
                                            </label>
                                            <div class="form-text">Inactive items will not be displayed on the website</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                                   {{ old('is_featured', $menuItem->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                Featured
                                            </label>
                                            <div class="form-text">Featured items will be displayed in the featured section</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.menu-items.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update Menu Item
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
                                    <span class="fw-bold">{{ $menuItem->id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Language:</span>
                                    <span>
                                        {{ $menuItem->language->flag }} {{ $menuItem->language->name }}
                                        @if($menuItem->language->is_default)
                                            <span class="badge bg-success ms-1">Default</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Category:</span>
                                    <span>
                                        @if($menuItem->category)
                                            {{ $menuItem->category->name }}
                                        @else
                                            <span class="text-danger">No category</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Status:</span>
                                    <span>
                                        @if($menuItem->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Featured:</span>
                                    <span>
                                        @if($menuItem->is_featured)
                                            <span class="badge bg-warning">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Mass ID:</span>
                                    <span>{{ $menuItem->mass_id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Created:</span>
                                    <span>{{ $menuItem->created_at->format('M d, Y H:i') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Last Updated:</span>
                                    <span>{{ $menuItem->updated_at->format('M d, Y H:i') }}</span>
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
        // Image preview
        $('.image-preview-input').change(function() {
            const previewId = $(this).data('preview');
            const $preview = $('#' + previewId);
            
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    $preview.attr('src', e.target.result).show();
                };
                
                reader.readAsDataURL(this.files[0]);
                
                // Uncheck remove image if a new image is selected
                $('#remove_image').prop('checked', false);
            }
        });
        
        // Handle remove image checkbox
        $('#remove_image').change(function() {
            if (this.checked) {
                // Clear file input
                $('#image').val('');
                // Hide preview
                $('#image_preview').hide();
            } else {
                // Show preview again if exists
                if ($('#image_preview').attr('src') != '') {
                    $('#image_preview').show();
                }
            }
        });
        
        // Format price with 2 decimal places
        $('#price').on('blur', function() {
            const value = parseFloat($(this).val()) || 0;
            $(this).val(value.toFixed(2));
        });
    });
</script>
@endpush