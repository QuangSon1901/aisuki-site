@extends('admin.layouts.app')

@section('title', 'Create Category Translation')

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
                    <h5 class="mb-0">Create Translation for: {{ $sourceCategory->name }}</h5>
                    <span class="badge bg-primary">
                        {{ $sourceCategory->language->flag }} {{ $sourceCategory->language->name }}
                    </span>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menu-categories.store-translation', $sourceCategory->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="language_id" class="form-label">Language <span class="text-danger">*</span></label>
                            <select class="form-select @error('language_id') is-invalid @enderror" id="language_id" name="language_id" required>
                                @foreach($availableLanguages as $language)
                                    <option value="{{ $language->id }}">
                                        {{ $language->flag }} {{ $language->name }} {{ $language->is_default ? '(Default)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('language_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" name="slug" value="{{ old('slug') }}" required>
                            <div class="form-text">The slug must be unique within the same language. Use only lowercase letters, numbers, and hyphens.</div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            <input type="file" class="form-control image-preview-input @error('image') is-invalid @enderror" 
                                   id="image" name="image" data-preview="image_preview" accept="image/*">
                            <div class="form-text">Recommended image size: 600x400 pixels</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="image_preview" src="" class="image-preview mt-2" style="display: none;">
                            
                            @if($sourceCategory->image)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="use_source_image" name="use_source_image" value="1" checked>
                                    <label class="form-check-label" for="use_source_image">
                                        Use source category image
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Source Image Preview:</label>
                                    <img src="{{ asset($sourceCategory->image) }}" class="image-preview">
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                                <div class="form-text">Inactive categories will not be displayed on the website</div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.menu-categories.edit', $sourceCategory->id) }}" class="btn btn-secondary">
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
        // Auto-generate slug from name
        $('#name').on('input', function() {
            if ($('#slug').val() === '') {
                generateSlug($(this).val());
            }
        });
        
        // Slug input formatting
        $('#slug').on('input', function() {
            generateSlug($(this).val());
        });
        
        function generateSlug(text) {
            // Convert to lowercase
            var slug = text.toLowerCase();
            
            // Replace accented characters
            slug = slug.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            
            // Replace spaces with hyphens
            slug = slug.replace(/\s+/g, '-');
            
            // Remove all remaining non-alphanumeric characters (except hyphens)
            slug = slug.replace(/[^a-z0-9-]/g, '');
            
            // Replace multiple consecutive hyphens with a single one
            slug = slug.replace(/-+/g, '-');
            
            // Remove leading and trailing hyphens
            slug = slug.replace(/^-+|-+$/g, '');
            
            $('#slug').val(slug);
        }
        
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