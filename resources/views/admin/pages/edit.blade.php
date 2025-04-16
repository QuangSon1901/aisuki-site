@extends('admin.layouts.app')

@section('title', 'Edit Page')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-editor {
            min-height: 400px;
            font-size: 16px;
        }
        
        .image-preview {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .language-tabs {
            border-bottom: 1px solid #dee2e6;
            padding: 0;
            margin-bottom: 20px;
        }
        
        .language-tabs .nav-link {
            border: none;
            padding: 10px 20px;
            border-radius: 0;
            position: relative;
            font-weight: 500;
            color: #333;
        }
        
        .language-tabs .nav-link:hover {
            background-color: #f8f9fa;
        }
        
        .language-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: transparent;
        }
        
        .language-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        .form-section {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        
        .form-section h5 {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
            color: var(--primary-color);
        }
        
        .active-switch .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .editor-error {
            border: 1px solid #dc3545 !important;
        }
        
        .slug-feedback {
            display: none;
            font-size: 0.875em;
            color: #dc3545;
            margin-top: 0.25rem;
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
                    <ul class="nav language-tabs">
                        @foreach($translations as $translation)
                            <li class="nav-item">
                                <a href="{{ route('admin.pages.edit', $translation->id) }}" 
                                   class="nav-link {{ $page->id == $translation->id ? 'active' : '' }}">
                                    <i class="fas fa-globe-asia me-2"></i>
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
            
            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data" id="page-form">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Main Content Column -->
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Page Content</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $page->title) }}" required
                                           maxlength="255">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="title-feedback slug-feedback"></div>
                                    <div class="text-muted mt-1 small"><span id="title-counter">0</span>/255 characters</div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text text-muted">/</span>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                               id="slug" name="slug" value="{{ old('slug', $page->slug) }}" required
                                               maxlength="255">
                                    </div>
                                    @error('slug')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="slug-feedback"></div>
                                    <small class="text-muted">The URL-friendly version of the name. Only lowercase letters, numbers, and hyphens are allowed.</small>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="editor" class="form-label">Content <span class="text-danger">*</span></label>
                                    <div id="editor" class="shadow-sm">{!! old('content', $page->content) !!}</div>
                                    <input type="hidden" name="content" id="content">
                                    <div class="editor-feedback slug-feedback"></div>
                                    @error('content')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- SEO Section -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-search me-2"></i> SEO Options
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                           id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}"
                                           maxlength="255">
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="text-muted mt-1 small"><span id="meta-title-counter">0</span>/255 characters</div>
                                    <small class="text-muted">If left empty, the page title will be used</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                              id="meta_description" name="meta_description" rows="3"
                                              maxlength="500">{{ old('meta_description', $page->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="text-muted mt-1 small"><span id="meta-desc-counter">0</span>/500 characters</div>
                                    <small class="text-muted">Brief description for search engines. Ideally 150-160 characters.</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                           id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}"
                                           maxlength="255">
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Comma-separated keywords</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar Column -->
                    <div class="col-lg-4">
                        <!-- Status Card -->
                        <div class="card mb-4">
                            <div class="card-header text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-cog me-2"></i> Page Settings
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4 active-switch">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="is_active">
                                            Page Status: 
                                            <span class="text-success" id="status-text">Active</span>
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        Toggle to make this page visible on the website.
                                    </small>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="sort_order" class="form-label fw-bold">Display Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $page->sort_order) }}"
                                           min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Lower numbers display first</small>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary" id="submit-btn">
                                        <i class="fas fa-save me-2"></i> Save Changes
                                    </button>
                                    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Back to Pages
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Featured Image Card -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-image me-2"></i> Featured Image
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($page->featured_image)
                                    <div class="text-center mb-3">
                                        <img id="image_preview" src="{{ asset($page->featured_image) }}" 
                                             class="image-preview mb-2" alt="Featured Image">
                                    </div>
                                @else
                                    <div class="text-center mb-3">
                                        <img id="image_preview" src="" class="image-preview mb-2" 
                                             style="display: none;" alt="Featured Image Preview">
                                        <div id="image_placeholder" class="border rounded p-5 text-center text-muted">
                                            <i class="fas fa-image fa-3x mb-3"></i>
                                            <p>No image uploaded</p>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="mb-0">
                                    <label for="featured_image" class="form-label d-block">Upload New Image</label>
                                    <input type="file" class="form-control image-preview-input @error('featured_image') is-invalid @enderror" 
                                           id="featured_image" name="featured_image" data-preview="image_preview"
                                           accept="image/jpeg,image/png,image/gif,image/jpg">
                                    @error('featured_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Recommended size: 1200 x 630 pixels (JPEG, PNG, GIF)</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Information Card -->
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i> Page Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span class="text-muted">Language:</span>
                                        <span class="fw-bold">{{ $page->language->name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span class="text-muted">Created:</span>
                                        <span>{{ $page->created_at->format('M d, Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span class="text-muted">Last Updated:</span>
                                        <span>{{ $page->updated_at->format('M d, Y H:i') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Quill editor
            var quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        [{ 'align': [] }],
                        ['link', 'image', 'video'],
                        ['blockquote', 'code-block'],
                        ['clean']
                    ]
                }
            });
            
            // Character counters
            updateCharCounter('#title', '#title-counter');
            updateCharCounter('#meta_title', '#meta-title-counter');
            updateCharCounter('#meta_description', '#meta-desc-counter');
            
            $('#title').on('input', function() {
                updateCharCounter('#title', '#title-counter');
            });
            
            $('#meta_title').on('input', function() {
                updateCharCounter('#meta_title', '#meta-title-counter');
            });
            
            $('#meta_description').on('input', function() {
                updateCharCounter('#meta_description', '#meta-desc-counter');
            });
            
            function updateCharCounter(inputSelector, counterSelector) {
                var length = $(inputSelector).val().length;
                $(counterSelector).text(length);
            }
            
            // Form validation before submit
            $('#page-form').on('submit', function(e) {
                var isValid = true;
                
                // Validate title
                if ($('#title').val().trim() === '') {
                    $('#title').addClass('is-invalid');
                    $('.title-feedback').text('Title is required').show();
                    isValid = false;
                } else {
                    $('#title').removeClass('is-invalid');
                    $('.title-feedback').hide();
                }
                
                // Validate slug
                var slug = $('#slug').val().trim();
                if (slug === '') {
                    $('#slug').addClass('is-invalid');
                    $('.slug-feedback').text('Slug is required').show();
                    isValid = false;
                } else if (!/^[a-z0-9-]+$/.test(slug)) {
                    $('#slug').addClass('is-invalid');
                    $('.slug-feedback').text('Slug can only contain lowercase letters, numbers, and hyphens').show();
                    isValid = false;
                } else {
                    $('#slug').removeClass('is-invalid');
                    $('.slug-feedback').hide();
                }
                
                // Validate content
                var content = $('#editor .ql-editor').html();
                if (content.trim() === '<p><br></p>' || content.trim() === '') {
                    $('#editor').addClass('editor-error');
                    $('.editor-feedback').text('Content is required').show();
                    isValid = false;
                } else {
                    $('#editor').removeClass('editor-error');
                    $('.editor-feedback').hide();
                }
                
                // Validate sort order
                var sortOrder = $('#sort_order').val();
                if (sortOrder !== '' && (isNaN(sortOrder) || parseInt(sortOrder) < 0)) {
                    $('#sort_order').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#sort_order').removeClass('is-invalid');
                }
                
                if (!isValid) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first, .editor-error:first').offset().top - 100
                    }, 200);
                    return false;
                }
                
                // Set content to hidden input
                $('#content').val(content);
                
                // Disable submit button to prevent double submission
                $('#submit-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Saving...');
            });
            
            // Handle image upload with custom handler
            var toolbar = quill.getModule('toolbar');
            toolbar.addHandler('image', function() {
                var fileInput = document.createElement('input');
                fileInput.setAttribute('type', 'file');
                fileInput.setAttribute('accept', 'image/jpeg,image/png,image/gif,image/jpg');
                fileInput.click();
                
                fileInput.onchange = function() {
                    var file = fileInput.files[0];
                    if (file) {
                        // Check file size (max 2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Image size exceeds 2MB limit. Please choose a smaller image.');
                            return;
                        }
                        
                        var formData = new FormData();
                        formData.append('image', file);
                        formData.append('_token', '{{ csrf_token() }}');
                        
                        // Show loading indicator
                        var range = quill.getSelection();
                        quill.insertText(range.index, 'Uploading image...');
                        
                        $.ajax({
                            url: '{{ route("admin.pages.upload-image") }}',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                // Remove loading text
                                quill.deleteText(range.index, 'Uploading image...'.length);
                                
                                if (response.success) {
                                    quill.insertEmbed(range.index, 'image', response.url);
                                } else {
                                    alert('Upload failed: ' + response.message);
                                }
                            },
                            error: function(xhr) {
                                // Remove loading text
                                quill.deleteText(range.index, 'Uploading image...'.length);
                                
                                var errorMsg = 'Upload failed. Please try again.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                alert(errorMsg);
                            }
                        });
                    }
                };
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
            
            // Auto-slug from title if slug is empty
            $('#title').on('keyup', function() {
                if ($('#slug').val() === '') {
                    var title = $(this).val();
                    var slug = makeSlug(title);
                    $('#slug').val(slug);
                }
            });
            
            // Validate slug field for allowed characters
            $('#slug').on('input', function() {
                var slug = $(this).val();
                var validSlug = makeSlug(slug);
                
                if (slug !== validSlug) {
                    $(this).val(validSlug);
                }
            });
            
            // Validate numeric fields
            $('#sort_order').on('input', function() {
                var value = $(this).val();
                if (value && !/^\d+$/.test(value)) {
                    $(this).val(value.replace(/[^\d]/g, ''));
                }
            });
            
            // Image preview functionality
            $('#featured_image').change(function() {
                if (this.files && this.files[0]) {
                    // Check file size (max 2MB)
                    if (this.files[0].size > 2 * 1024 * 1024) {
                        alert('Image size exceeds 2MB limit. Please choose a smaller image.');
                        this.value = '';
                        return;
                    }
                    
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                        $('#image_preview').attr('src', e.target.result).show();
                        $('#image_placeholder').hide();
                    };
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            // Status toggle text update
            $('#is_active').change(function() {
                $('#status-text').text(this.checked ? 'Active' : 'Inactive');
                $('#status-text').removeClass('text-success text-danger');
                $('#status-text').addClass(this.checked ? 'text-success' : 'text-danger');
            });
            
            // Trigger initial status text
            $('#is_active').trigger('change');
        });
    </script>
@endpush