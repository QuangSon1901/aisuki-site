@extends('admin.layouts.app')

@section('title', 'Edit Language')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Language: {{ $language->name }}</h5>
                    <span class="badge bg-info">{{ strtoupper($language->code) }}</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.languages.update', $language->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Language Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code', $language->code) }}" required maxlength="2">
                                    <div class="form-text">2-letter ISO code (e.g., en, de, fr)</div>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="flag" class="form-label">Flag Emoji <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('flag') is-invalid @enderror" 
                                           id="flag" name="flag" value="{{ old('flag', $language->flag) }}" required>
                                    <div class="form-text">Flag emoji code (e.g., 🇺🇸, 🇩🇪, 🇫🇷)</div>
                                    @error('flag')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Language Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $language->name) }}" required>
                                    <div class="form-text">Name in English (e.g., English, German, French)</div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="native_name" class="form-label">Native Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('native_name') is-invalid @enderror" 
                                           id="native_name" name="native_name" value="{{ old('native_name', $language->native_name) }}" required>
                                    <div class="form-text">Name in its own language (e.g., English, Deutsch, Français)</div>
                                    @error('native_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $language->sort_order) }}" min="0">
                                    <div class="form-text">Lower numbers display first</div>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 d-flex flex-column h-100 justify-content-end pt-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default" 
                                               {{ old('is_default', $language->is_default) ? 'checked' : '' }}
                                               {{ $language->is_default ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="is_default">
                                            Set as default language
                                        </label>
                                        @if($language->is_default)
                                            <div class="form-text text-success">This is currently the default language</div>
                                            <input type="hidden" name="is_default" value="1">
                                        @endif
                                    </div>
                                    
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               {{ old('is_active', $language->is_active) ? 'checked' : '' }}
                                               {{ $language->is_default ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                        @if($language->is_default)
                                            <div class="form-text text-success">The default language must be active</div>
                                            <input type="hidden" name="is_active" value="1">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Language
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
        // Auto-convert language code to lowercase
        $('#code').on('input', function() {
            $(this).val($(this).val().toLowerCase());
        });
        
        // Prevent more than 2 characters in language code
        $('#code').on('keyup', function() {
            if ($(this).val().length > 2) {
                $(this).val($(this).val().substring(0, 2));
            }
        });
    });
</script>
@endpush