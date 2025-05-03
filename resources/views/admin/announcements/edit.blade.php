@extends('admin.layouts.app')

@section('title', 'Edit Announcement')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .note-editor {
        width: 100%;
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
                        <a href="{{ route('admin.announcements.create-translation', $announcement->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Translation
                        </a>
                    </div>
                    <ul class="nav nav-tabs language-nav">
                        <li class="nav-item">
                            <a href="{{ route('admin.announcements.edit', $announcement->id) }}" 
                               class="nav-link active">
                                <span class="me-1">{{ $announcement->language->flag }}</span>
                                {{ $announcement->language->name }}
                                @if($announcement->language->is_default)
                                    <span class="badge bg-success ms-1">Default</span>
                                @endif
                            </a>
                        </li>
                        @foreach($translations as $translation)
                            <li class="nav-item">
                                <a href="{{ route('admin.announcements.edit', $translation->id) }}" 
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
                            <h5 class="mb-0">Edit Announcement: {{ $announcement->title }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.announcements.update', $announcement->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $announcement->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control summernote @error('content') is-invalid @enderror" 
                                              id="content" name="content">{{ old('content', $announcement->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                               id="start_date" name="start_date" value="{{ old('start_date', $announcement->start_date ? $announcement->start_date->format('Y-m-d') : '') }}">
                                        <div class="form-text">Leave empty for no start date restriction</div>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                               id="end_date" name="end_date" value="{{ old('end_date', $announcement->end_date ? $announcement->end_date->format('Y-m-d') : '') }}">
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
                                            id="priority" name="priority" value="{{ old('priority', $announcement->priority) }}" min="0">
                                        <div class="form-text">Higher priority announcements will be shown first</div>
                                        @error('priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="sort_order" class="form-label">Sort Order</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                            id="sort_order" name="sort_order" value="{{ old('sort_order', $announcement->sort_order) }}" min="0">
                                        <div class="form-text">Announcements with lower sort order within same priority will be displayed first</div>
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                        <div class="form-text">Inactive announcements will not be displayed</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_dismissible" name="is_dismissible" 
                                               {{ old('is_dismissible', $announcement->is_dismissible) ? 'checked' : '' }}>
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
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update Announcement
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
                                <i class="fas fa-info-circle me-2"></i> Announcement Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">ID:</span>
                                    <span class="fw-bold">{{ $announcement->id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Language:</span>
                                    <span>
                                        {{ $announcement->language->flag }} {{ $announcement->language->name }}
                                        @if($announcement->language->is_default)
                                            <span class="badge bg-success ms-1">Default</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Status:</span>
                                    <span>
                                        @if($announcement->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Period:</span>
                                    <span>
                                        @if($announcement->start_date && $announcement->end_date)
                                            {{ $announcement->start_date->format('M d, Y') }} - {{ $announcement->end_date->format('M d, Y') }}
                                        @elseif($announcement->start_date)
                                            From {{ $announcement->start_date->format('M d, Y') }}
                                        @elseif($announcement->end_date)
                                            Until {{ $announcement->end_date->format('M d, Y') }}
                                        @else
                                            Always
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Dismissible:</span>
                                    <span>
                                        @if($announcement->is_dismissible)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-warning">No</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Mass ID:</span>
                                    <span>{{ $announcement->mass_id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Created:</span>
                                    <span>{{ $announcement->created_at->format('M d, Y H:i') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Last Updated:</span>
                                    <span>{{ $announcement->updated_at->format('M d, Y H:i') }}</span>
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush