@extends('admin.layouts.app')

@section('title', 'Menu Categories')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Menu Categories</h5>
                    <a href="{{ route('admin.menu-categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Category
                    </a>
                </div>
                <div class="card-body">
                    @if(count($categories) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="15%">Image</th>
                                        <th width="20%">Name</th>
                                        <th width="15%">Slug</th>
                                        <th width="15%">Languages</th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th width="10%" class="text-center">Sort Order</th>
                                        <th width="10%" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $categoryData)
                                        <tr>
                                            <td class="align-middle">{{ $categoryData['category']->id }}</td>
                                            <td class="align-middle">
                                                @if($categoryData['category']->image)
                                                    <img src="{{ asset($categoryData['category']->image) }}" alt="{{ $categoryData['category']->name }}" class="img-thumbnail" style="max-height: 70px;">
                                                @else
                                                    <div class="text-muted"><i class="fas fa-image"></i> No image</div>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <strong>{{ $categoryData['category']->name }}</strong>
                                            </td>
                                            <td class="align-middle">
                                                <code>{{ $categoryData['category']->slug }}</code>
                                            </td>
                                            <td class="align-middle">
                                                @foreach($categoryData['translations'] as $translation)
                                                    <span class="badge {{ $translation->language->is_default ? 'bg-success' : 'bg-primary' }} me-1">
                                                        {{ strtoupper($translation->language->code) }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($categoryData['category']->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                {{ $categoryData['category']->sort_order }}
                                            </td>
                                            <td class="align-middle text-end">
                                                <div class="d-flex justify-content-end">
                                                    <a href="{{ route('admin.menu-categories.edit', $categoryData['category']->id) }}" class="btn btn-sm btn-info me-1" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.menu-categories.destroy', $categoryData['category']->id) }}" method="POST" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i> No menu categories found. Create your first category by clicking the "Add Category" button.
                        </div>
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
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this menu category? This action cannot be undone.')) {
                this.submit();
            }
        });
    });
</script>
@endpush