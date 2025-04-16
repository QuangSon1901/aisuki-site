@extends('admin.layouts.app')

@section('title', 'Edit Menu Category')

@push('styles')
<style>
    .image-preview {
        max-width: 100%;
        max-height: 200px;
        margin-top: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
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
                        <a href="{{ route('admin.menu-categories.create-translation', $category->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Translation
                        </a>
                    </div>
                    <ul class="nav nav-tabs language-nav">
                        <li class="nav-item">
                            <a href="{{ route('admin.menu-categories.edit', $category->id) }}" 
                               class="nav-link active">
                                <span class="me-1">{{ $category->language->flag }}</span>
                                {{ $category->language->name }}
                                @if($category->language->is_default)
                                    <span class="badge bg-success ms-1">Default</span>
                                @endif
                            </a>
                        </li>
                        @foreach($translations as $translation)
                            <li class="nav-item">
                                <a href="{{ route('admin.menu-categories.edit', $translation->id) }}" 
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
                            <h5 class="mb-0">Edit Menu Category: {{ $category->name }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.menu-categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug', $category->slug) }}" required>
                                    <div class="form-text">The slug must be unique within the same language. Use only lowercase letters, numbers, and hyphens.</div>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">Category Image</label>
                                    <input type="file" class="form-control image-preview-input @error('image') is-invalid @enderror" 
                                           id="image" name="image" data-preview="image_preview" accept="image/*">
                                    <div class="form-text">Recommended image size: 600x400 pixels. Leave empty to keep current image.</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($category->image)
                                        <div class="my-2">
                                            <img id="image_preview" src="{{ asset($category->image) }}" class="image-preview">
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
                                
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                                    <div class="form-text">Categories with lower sort order will be displayed first</div>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                        <div class="form-text">Inactive categories will not be displayed on the website</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.menu-categories.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update Category
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
                                <i class="fas fa-info-circle me-2"></i> Category Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Category ID:</span>
                                    <span class="fw-bold">{{ $category->id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Language:</span>
                                    <span>
                                        {{ $category->language->flag }} {{ $category->language->name }}
                                        @if($category->language->is_default)
                                            <span class="badge bg-success ms-1">Default</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Status:</span>
                                    <span>
                                        @if($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Mass ID:</span>
                                    <span>{{ $category->mass_id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Created:</span>
                                    <span>{{ $category->created_at->format('M d, Y H:i') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Last Updated:</span>
                                    <span>{{ $category->updated_at->format('M d, Y H:i') }}</span>
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
        // Auto-generate slug from name if slug is empty
        $('#name').on('input', function() {
            if ($('#slug').val() === '') {
                makeSlug($(this).val());
            }
        });
        
        // Slug input formatting
        $('#slug').on('input', function() {
            $(this).val(makeSlug($(this).val()));
        });

        function makeSlug(text) {
            // Chuyển đổi tiếng Việt không dấu
            let slug = text.toLowerCase()
                // Convert Vietnamese characters
                .replace(/[áàảãạâấầẩẫậăắằẳẵặ]/g, 'a')
                .replace(/[éèẻẽẹêếềểễệ]/g, 'e')
                .replace(/[íìỉĩị]/g, 'i')
                .replace(/[óòỏõọôốồổỗộơớờởỡợ]/g, 'o')
                .replace(/[úùủũụưứừửữự]/g, 'u')
                .replace(/[ýỳỷỹỵ]/g, 'y')
                .replace(/đ/g, 'd')
                // Remove special characters
                .replace(/[^\w\s-]/g, '')
                // Replace whitespace with dash
                .replace(/\s+/g, '-')
                // Replace multiple dashes with single dash
                .replace(/-+/g, '-')
                // Remove leading/trailing dashes
                .trim('-');
            
            return slug;
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
    });
</script>
@endpush