@extends('admin.layouts.app')

@section('title', 'Languages')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Available Languages</h5>
                    <a href="{{ route('admin.languages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Language
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">Flag</th>
                                    <th width="10%">Code</th>
                                    <th width="20%">Name</th>
                                    <th width="20%">Native Name</th>
                                    <th width="10%" class="text-center">Default</th>
                                    <th width="10%" class="text-center">Status</th>
                                    <th width="10%" class="text-center">Sort Order</th>
                                    <th width="15%" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($languages as $language)
                                <tr>
                                    <td class="align-middle">
                                        <span class="fs-5">{{ $language->flag }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-secondary">{{ strtoupper($language->code) }}</span>
                                    </td>
                                    <td class="align-middle">{{ $language->name }}</td>
                                    <td class="align-middle">{{ $language->native_name }}</td>
                                    <td class="align-middle text-center">
                                        @if($language->is_default)
                                            <span class="badge bg-success">Default</span>
                                        @else
                                            <form action="{{ route('admin.languages.set-default', $language->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    Set Default
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <form action="{{ route('admin.languages.toggle-active', $language->id) }}" method="POST">
                                            @csrf
                                            @if($language->is_active)
                                                <button type="submit" class="btn btn-sm {{ $language->is_default ? 'btn-success disabled' : 'btn-success' }}" 
                                                        {{ $language->is_default ? 'disabled' : '' }}>
                                                    <i class="fas fa-check-circle me-1"></i> Active
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-times-circle me-1"></i> Inactive
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                    <td class="align-middle text-center">{{ $language->sort_order }}</td>
                                    <td class="align-middle text-end">
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('admin.languages.edit', $language->id) }}" class="btn btn-sm btn-info me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">No languages found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection