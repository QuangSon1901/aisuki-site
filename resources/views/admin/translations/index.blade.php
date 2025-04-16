@extends('admin.layouts.app')

@section('title', 'Translation Manager')

@push('styles')
<style>
    .progress {
        height: 10px;
    }
    .translation-group {
        transition: all 0.2s ease;
    }
    .translation-group:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .add-group-btn {
        transition: all 0.2s ease;
    }
    .add-group-btn:hover {
        transform: scale(1.05);
    }
    .language-badge {
        min-width: 45px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-1"></i> Create New Translation Group
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.translations.create-group') }}" method="POST">
                        @csrf
                        <div class="input-group mb-2">
                            <input type="text" name="group_name" class="form-control @error('group_name') is-invalid @enderror" 
                                   placeholder="Enter group name (e.g., customer, admin, validation)" required
                                   pattern="[a-zA-Z0-9_]+" title="Only letters, numbers, and underscores allowed">
                            <button type="submit" class="btn btn-primary add-group-btn">Create Group</button>
                        </div>
                        @error('group_name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Group names should use only letters, numbers, and underscores (no spaces).
                        </small>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 hidden">
            <div class="card">
                <div class="card-header text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-sync me-1"></i> Import/Export Translations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="{{ route('admin.translations.import') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-file-import me-1"></i> Import from Files
                                </button>
                                <small class="form-text text-muted d-block mt-1">
                                    Import translations from language files in resources/lang
                                </small>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('admin.translations.export') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-file-export me-1"></i> Export to Files
                                </button>
                                <small class="form-text text-muted d-block mt-1">
                                    Export translations to language files in resources/lang
                                </small>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Translation Groups</h5>
                </div>
                <div class="card-body">
                    @if($groups->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i> No translation groups found. Create your first group above.
                        </div>
                    @else
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                            @foreach($groups as $group)
                                <div class="col">
                                    <div class="card h-100 translation-group border-hover">
                                        <div class="card-header">
                                            <h5 class="mb-0">
                                                <i class="fas fa-language me-1"></i> {{ $group }}
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            @if(isset($groupStats[$group]))
                                                @foreach($languages as $language)
                                                    @if(isset($groupStats[$group][$language->code]))
                                                        @php
                                                            $stats = $groupStats[$group][$language->code];
                                                            $percentage = $stats['percentage'];
                                                            $color = $percentage >= 90 ? 'success' : ($percentage >= 70 ? 'info' : ($percentage >= 30 ? 'warning' : 'danger'));
                                                        @endphp
                                                        <div class="mb-2">
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <span>
                                                                    <span class="badge bg-secondary language-badge">{{ strtoupper($language->code) }}</span>
                                                                    {{ $language->name }}:
                                                                </span>
                                                                <span>
                                                                    <strong>{{ $stats['translated'] }}/{{ $stats['total'] }}</strong>
                                                                    <small>({{ $stats['percentage'] }}%)</small>
                                                                </span>
                                                            </div>
                                                            <div class="progress">
                                                                <div class="progress-bar bg-{{ $color }}" role="progressbar" 
                                                                     style="width: {{ $stats['percentage'] }}%" 
                                                                     aria-valuenow="{{ $stats['percentage'] }}" 
                                                                     aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="card-footer text-center">
                                            <a href="{{ route('admin.translations.group', $group) }}" class="btn btn-primary">
                                                <i class="fas fa-edit me-1"></i> Edit Translations
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection