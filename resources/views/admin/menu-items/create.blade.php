@extends('admin.layouts.app')

@section('title', 'Add Menu Item')

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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add New Menu Item</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menu-items.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="mass_id" value="{{ $nextMassId }}">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="language_id" class="form-label">Language <span class="text-danger">*</span></label>
                                <select class="form-select @error('language_id') is-invalid @enderror" id="language_id" name="language_id" required>
                                    @foreach($languages as $language)
                                    <option value="{{ $language->id }}" {{ $language->id == $defaultLanguage->id ? 'selected' : '' }}>
                                        {{ $language->flag }} {{ $language->name }} {{ $language->is_default ? '(Default)' : '' }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('language_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    @if($categories->isEmpty())
                                    <option value="">No categories available in this language</option>
                                    @else
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="code" class="form-label">Item Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                    id="code" name="code" value="{{ old('code', $nextCode) }}" required>
                                <div class="form-text">Unique code for this menu item</div>
                                @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                <div class="price-input">
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" value="{{ old('price', '0.00') }}" required step="0.01" min="0">
                                    <span class="currency-symbol">{{ setting('currency', '€') }}</span>
                                </div>
                                @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                    id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
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
                            <div class="form-text">Recommended image size: 800x600 pixels</div>
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="image_preview" src="" class="image-preview mt-2" style="display: none;">
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                    <div class="form-text">Inactive items will not be displayed on the website</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" {{ old('is_featured', false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Featured
                                    </label>
                                    <div class="form-text">Featured items will be displayed in the featured section</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Addon Items</label>
                            <div class="card">
                                <div class="card-body bg-light">
                                    <div class="addon-items-container">
                                        @if($addons->isEmpty())
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle me-1"></i> No addon items available in this language.
                                        </div>
                                        @else
                                        <div class="row">
                                            @foreach($addons as $addon)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="addon_ids[]" id="addon_{{ $addon->id }}"
                                                        value="{{ $addon->id }}" {{ in_array($addon->id, old('addon_ids', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="addon_{{ $addon->id }}">
                                                        {{ $addon->name }} ({{ number_format($addon->price, 2) }} {{ setting('currency', '€') }})
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-text">Select addon items that can be added to this menu item</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.menu-items.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Menu Item
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
        // Update categories when language changes
        $('#language_id').on('change', function() {
            const languageId = $(this).val();
            
            // Fetch addon items for the selected language
            if (languageId) {
                $.ajax({
                    url: "{{ route('admin.menu-items.get-addons') }}",
                    type: "GET",
                    data: {
                        language_id: languageId
                    },
                    success: function(response) {
                        const $container = $('.addon-items-container');
                        $container.empty();
                        
                        if (response.addons && response.addons.length > 0) {
                            let html = '<div class="row">';
                            
                            response.addons.forEach(function(addon) {
                                html += `
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                name="addon_ids[]" id="addon_${addon.id}" 
                                                value="${addon.id}">
                                            <label class="form-check-label" for="addon_${addon.id}">
                                                ${addon.name} (${parseFloat(addon.price).toFixed(2)} {{ setting('currency', '€') }})
                                            </label>
                                        </div>
                                    </div>
                                `;
                            });
                            
                            html += '</div>';
                            $container.html(html);
                        } else {
                            $container.html('<div class="alert alert-info mb-0"><i class="fas fa-info-circle me-1"></i> No addon items available in this language.</div>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading addon items:', xhr.responseText);
                        $('.addon-items-container').html('<div class="alert alert-danger mb-0"><i class="fas fa-exclamation-circle me-1"></i> Failed to load addon items for this language.</div>');
                    }
                });
            }
        });

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
            } else {
                $preview.hide();
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