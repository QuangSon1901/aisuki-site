@extends('admin.layouts.app')

@section('title', 'Addon Items')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Addon Items</h5>
                    <a href="{{ route('admin.addon-items.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Addon Item
                    </a>
                </div>
                <div class="card-body">
                    @if(count($addonItems) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="25%">Name</th>
                                        <th width="15%">Price</th>
                                        <th width="15%">Languages</th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th width="10%" class="text-center">Sort Order</th>
                                        <th width="20%" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($addonItems as $itemData)
                                        <tr>
                                            <td class="align-middle">{{ $itemData['item']->id }}</td>
                                            <td class="align-middle">
                                                <strong>{{ $itemData['item']->name }}</strong>
                                            </td>
                                            <td class="align-middle">
                                                {{ number_format($itemData['item']->price, 2) }} {{ setting('currency', '€') }}
                                            </td>
                                            <td class="align-middle">
                                                @foreach($itemData['translations'] as $translation)
                                                    <span class="badge {{ $translation->language->is_default ? 'bg-success' : 'bg-primary' }} me-1">
                                                        {{ strtoupper($translation->language->code) }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($itemData['item']->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                {{ $itemData['item']->sort_order }}
                                            </td>
                                            <td class="align-middle text-end">
                                                <div class="d-flex justify-content-end">
                                                    <a href="{{ route('admin.addon-items.edit', $itemData['item']->id) }}" class="btn btn-sm btn-info me-1" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.addon-items.destroy', $itemData['item']->id) }}" method="POST" class="delete-form">
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
                            <i class="fas fa-info-circle me-1"></i> No addon items found. Create your first addon item by clicking the "Add Addon Item" button.
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
            
            if (confirm('Are you sure you want to delete this addon item? This action cannot be undone.')) {
                this.submit();
            }
        });
    });
</script>
@endpush