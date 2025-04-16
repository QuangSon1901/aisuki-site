@extends('admin.layouts.app')

@section('title', "Edit Translations: {$group}")

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .translation-key {
        font-family: 'Courier New', Courier, monospace;
        font-weight: 600;
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .translation-key:hover {
        white-space: normal;
        word-break: break-all;
    }
    .translation-container {
        position: relative;
    }
    .copy-key {
        position: absolute;
        top: 0;
        right: 0;
        background: rgba(0,0,0,0.05);
        border: none;
        border-radius: 4px;
        padding: 2px 5px;
        font-size: 0.8rem;
        cursor: pointer;
        display: none;
    }
    .translation-key-cell:hover .copy-key {
        display: block;
    }
    .translation-controls {
        min-width: 100px;
    }
    .textarea-container {
        position: relative;
    }
    .char-count {
        position: absolute;
        bottom: 2px;
        right: 5px;
        font-size: 0.75rem;
        color: #6c757d;
        background: rgba(255,255,255,0.8);
        padding: 0 4px;
        border-radius: 2px;
    }
    .empty-translation {
        border-color: #ffcccc !important;
        background-color: #fff8f8 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.translations.index') }}" class="btn btn-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="mb-0">
                    <i class="fas fa-language me-2"></i>
                    Translations for Group: <span class="text-primary">{{ $group }}</span>
                </h4>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addKeyModal">
                <i class="fas fa-plus-circle me-1"></i> Add Translation Key
            </button>
        </div>
    </div>

    <!-- Add Key Modal -->
    <div class="modal fade" id="addKeyModal" tabindex="-1" aria-labelledby="addKeyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.translations.add-key') }}" method="POST">
                    @csrf
                    <input type="hidden" name="group" value="{{ $group }}">
                    
                    <div class="modal-header">
                        <h5 class="modal-title" id="addKeyModalLabel">Add New Translation Key</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="key" class="form-label">Translation Key <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="key" name="key" required 
                                   placeholder="Enter translation key (e.g., welcome_message, errors.not_found)">
                            <div class="form-text">
                                Use dot notation for nested keys (e.g., menu.items.home)
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Key</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <h5 class="mb-0">Edit Translations</h5>
                        </div>
                        <div class="col-md-6 text-end hidden">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary" id="expandAll">
                                    <i class="fas fa-expand-arrows-alt me-1"></i> Expand All
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="collapseAll">
                                    <i class="fas fa-compress-arrows-alt me-1"></i> Collapse All
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="showEmpty">
                                    <i class="fas fa-filter me-1"></i> Show Empty
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(empty($translations))
                        <div class="alert alert-info m-3">
                            <i class="fas fa-info-circle me-1"></i> No translations found for this group.
                        </div>
                    @else
                        <form action="{{ route('admin.translations.update') }}" method="POST" id="translationsForm">
                            @csrf
                            <input type="hidden" name="group" value="{{ $group }}">
                            
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="30%">Translation Key</th>
                                            @foreach($languages as $language)
                                                <th>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ $language->flag }}</span>
                                                        {{ $language->name }}
                                                        @if($language->is_default)
                                                            <span class="badge bg-success ms-1">Default</span>
                                                        @endif
                                                    </div>
                                                </th>
                                            @endforeach
                                            <th class="translation-controls"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($translations as $key => $values)
                                            <tr class="translation-row">
                                                <td class="translation-key-cell">
                                                    <div class="translation-container">
                                                        <div class="translation-key" title="{{ $key }}">{{ $key }}</div>
                                                        <button type="button" class="copy-key" data-key="{{ $key }}">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                @foreach($languages as $language)
                                                    <td>
                                                        <div class="textarea-container">
                                                            <textarea 
                                                                name="translations[{{ $key }}][{{ $language->code }}]" 
                                                                rows="2" 
                                                                class="form-control translation-value {{ empty($values[$language->code]) ? 'empty-translation' : '' }}"
                                                                placeholder="Translation for {{ $language->name }}"
                                                            >{{ $values[$language->code] }}</textarea>
                                                            <div class="char-count">0</div>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="p-3 border-top">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save All Translations
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Character count for textareas
        $('.translation-value').each(function() {
            const length = $(this).val().length;
            $(this).siblings('.char-count').text(length);
        });
        
        $('.translation-value').on('input', function() {
            const length = $(this).val().length;
            $(this).siblings('.char-count').text(length);
            
            // Update empty class
            if ($(this).val().trim() === '') {
                $(this).addClass('empty-translation');
            } else {
                $(this).removeClass('empty-translation');
            }
        });
        
        // Copy key to clipboard
        $('.copy-key').on('click', function(e) {
            e.preventDefault();
            
            const key = $(this).data('key');
            const tempInput = $('<input>');
            
            $('body').append(tempInput);
            tempInput.val(key).select();
            document.execCommand('copy');
            tempInput.remove();
            
            // Show feedback
            const originalHtml = $(this).html();
            $(this).html('<i class="fas fa-check"></i>');
            
            setTimeout(() => {
                $(this).html(originalHtml);
            }, 1000);
        });
        
        // Expand all textareas
        $('#expandAll').on('click', function() {
            $('.translation-value').each(function() {
                $(this).attr('rows', 5);
            });
        });
        
        // Collapse all textareas
        $('#collapseAll').on('click', function() {
            $('.translation-value').each(function() {
                $(this).attr('rows', 2);
            });
        });
        
        // Show only empty translations
        $('#showEmpty').on('click', function() {
            const showing = $(this).hasClass('active');
            
            if (!showing) {
                $('.translation-row').each(function() {
                    const hasEmpty = $(this).find('.empty-translation').length > 0;
                    if (!hasEmpty) {
                        $(this).hide();
                    }
                });
                $(this).addClass('active').html('<i class="fas fa-times me-1"></i> Clear Filter');
            } else {
                $('.translation-row').show();
                $(this).removeClass('active').html('<i class="fas fa-filter me-1"></i> Show Empty');
            }
        });
        
        // Auto-resize textareas
        $('textarea').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>
@endpush