@extends('admin.layouts.app')

@section('title', 'Create Menu Category')

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
                <div class="card-header">
                    <h5 class="mb-0">Create New Menu Category</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menu-categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="mass_id" value="{{ $nextMassId }}">
                        
                        <div class="mb-3">
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
                        </div>
                        
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                   id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                            <div class="form-text">Categories with lower sort order will be displayed first</div>
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <a href="{{ route('admin.menu-categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create Category
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
                makeSlug($(this).val());
            }
        });
        
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
            } else {
                $preview.hide();
            }
        });
    });
</script>
@endpush