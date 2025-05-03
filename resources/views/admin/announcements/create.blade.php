@extends('admin.layouts.app')

@section('title', 'Create Announcement')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor {
        min-height: 300px;
        font-size: 16px;
    }
    .editor-error {
        border: 1px solid #dc3545 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create New Announcement</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.announcements.store') }}" method="POST" id="announcement-form">
                        @csrf
                        
                        <input type="hidden" name="mass_id" value="{{ $nextMassId }}">
                        <input type="hidden" name="content" id="content">
                        
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
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="editor" class="form-label">Content <span class="text-danger">*</span></label>
                            <div id="editor" class="@error('content') editor-error @enderror">{!! old('content') !!}</div>
                            @error('content')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" value="{{ old('start_date') }}">
                                <div class="form-text">Leave empty for no start date restriction</div>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" value="{{ old('end_date') }}">
                                <div class="form-text">Leave empty for no end date restriction</div>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="priority" class="form-label">Priority</label>
                                <input type="number" class="form-control @error('priority') is-invalid @enderror" 
                                    id="priority" name="priority" value="{{ old('priority', 0) }}" min="0">
                                <div class="form-text">Higher priority announcements will be shown first</div>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 hidden">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                    id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                <div class="form-text">Announcements with lower sort order within same priority will be displayed first</div>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                                <div class="form-text">Inactive announcements will not be displayed</div>
                            </div>
                        </div>
                        
                        <div class="mb-3 hidden">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_dismissible" name="is_dismissible" 
                                       {{ old('is_dismissible', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_dismissible">
                                    Dismissible
                                </label>
                                <div class="form-text">Allow users to dismiss this announcement</div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="fas fa-save me-1"></i> Create Announcement
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
        
        // Handle form submission
        $('#announcement-form').on('submit', function(e) {
            // Set content to hidden input before submit
            var content = quill.root.innerHTML;
            if (content.trim() === '<p><br></p>' || content.trim() === '') {
                e.preventDefault();
                $('#editor').addClass('editor-error');
                alert('Content is required');
                return false;
            }
            
            $('#content').val(content);
            
            // Disable submit button to prevent double submission
            $('#submit-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');
        });
        
        // Handle image upload with custom handler
        var toolbar = quill.getModule('toolbar');
        toolbar.addHandler('image', function() {
            var fileInput = document.createElement('input');
            fileInput.setAttribute('type', 'file');
            fileInput.setAttribute('accept', 'image/jpeg,image/png,image/gif,image/jpg,image/webp');
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
                        url: '{{ route("admin.announcements.upload-image") }}',
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
    });
</script>
@endpush