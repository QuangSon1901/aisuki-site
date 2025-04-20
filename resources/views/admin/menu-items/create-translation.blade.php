@extends('admin.layouts.app')

@section('title', 'Create Menu Item Translation')

@push('styles')
<style>
    .image-preview {
        max-width: 100%;
        max-height: 200px;
        margin-top: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
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
                    <form action="{{ route('admin.menu-items.store-translation', $sourceItem->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
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

                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                    <option value="">Select language first</option>
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
                                    id="code" name="code" value="{{ old('code') }}" required>
                                <div class="form-text">Must be unique per language</div>
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

                        <div class="mb-3">
                            <label for="image" class="form-label">Item Image</label>
                            <input type="file" class="form-control image-preview-input @error('image') is-invalid @enderror"
                                id="image" name="image" data-preview="image_preview" accept="image/*">
                            <div class="form-text">Recommended image size: 800x600 pixels</div>
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="image_preview" src="" class="image-preview mt-2" style="display: none;">

                            @if($sourceItem->image)
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="use_source_image" name="use_source_image" value="1" checked>
                                <label class="form-check-label" for="use_source_image">
                                    Use source item image
                                </label>
                            </div>
                            <div class="mt-2">
                                <label class="form-label">Source Image Preview:</label>
                                <img src="{{ asset($sourceItem->image) }}" class="image-preview">
                            </div>
                            @endif
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> The price ({{ number_format($sourceItem->price, 2) }} {{ setting('currency', '€') }}),
                            featured status, and sort order will be copied from the source item.
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                                <div class="form-text">Inactive items will not be displayed on the website</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Addon Items</label>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="use_source_addons" name="use_source_addons" value="1" checked>
                                <label class="form-check-label" for="use_source_addons">
                                    <strong>Use source item addon items</strong>
                                </label>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i> This will automatically attach the equivalent addons from the source item
                                </div>
                            </div>

                            <div id="addon_selection_container" style="display: none;">
                                <div class="card">
                                    <div class="card-body bg-light">
                                        <div class="addon-items-container">
                                            <div class="alert alert-info mb-3">
                                                <i class="fas fa-info-circle me-1"></i> Select language first to see available addon items
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.menu-items.edit', $sourceItem->id) }}" class="btn btn-secondary">
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
        $('#use_source_addons').on('change', function() {
            if ($(this).is(':checked')) {
                $('#addon_selection_container').hide();
            } else {
                $('#addon_selection_container').show();
            }
        });
        // Load categories when language changes
        $('#language_id').on('change', function() {
            const languageId = $(this).val();

            if (!$('#use_source_addons').is(':checked') && languageId) {
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

        $('#language_id').on('change', function() {
    const languageId = $(this).val();
    
    if (languageId) {
        // Update categories dropdown
        $.ajax({
            url: "{{ route('admin.menu-items.get-categories') }}",
            type: "GET",
            data: {
                language_id: languageId
            },
            success: function(response) {
                const $categorySelect = $('#category_id');
                $categorySelect.empty();
                
                if (response.categories && response.categories.length > 0) {
                    $categorySelect.append('<option value="">Select category</option>');
                    
                    response.categories.forEach(function(category) {
                        $categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
                    });
                } else {
                    $categorySelect.append('<option value="">No categories available</option>');
                }
            },
            error: function(xhr) {
                console.error('Error loading categories:', xhr.responseText);
                $('#category_id').html('<option value="">Failed to load categories</option>');
            }
        });
        
        // Your existing addon loading code will still run here
    } else {
        $('#category_id').html('<option value="">Select language first</option>');
    }
});

        // Auto-generate code from name
        $('#name').on('input', function() {
            if ($('#code').val() === '') {
                const name = $(this).val().toUpperCase();
                // Convert to alphanumeric only
                const code = name.replace(/[^A-Z0-9]/g, '').substring(0, 5);
                if (code) {
                    $('#code').val(code + Math.floor(Math.random() * 1000).toString().padStart(3, '0'));
                }
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

                // Uncheck use source image if a new image is selected
                $('#use_source_image').prop('checked', false);
            } else {
                $preview.hide();

                // Check use source image if available
                if ($('#use_source_image').length) {
                    $('#use_source_image').prop('checked', true);
                }
            }
        });

        // Handle use source image checkbox
        $('#use_source_image').change(function() {
            if (this.checked) {
                // Clear file input
                $('#image').val('');
                // Hide preview
                $('#image_preview').hide();
            }
        });
    });
</script>
@endpush