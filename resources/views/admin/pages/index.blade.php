@extends('admin.layouts.app')

@section('title', 'Manage Pages')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>All Pages</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Languages</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pages as $pageData)
                                <tr>
                                    <td class="align-middle">
                                        <strong>{{ $pageData['page']->title }}</strong>
                                    </td>
                                    <td class="align-middle">
                                        <code>{{ $pageData['page']->slug }}</code>
                                    </td>
                                    <td class="align-middle">
                                        @foreach($pageData['translations'] as $translation)
                                            <span class="badge {{ $translation->language->is_default ? 'bg-success' : 'bg-primary' }} me-1">
                                                {{ strtoupper($translation->language->code) }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="align-middle">
                                        @if($pageData['page']->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $pageData['page']->updated_at->format('M d, Y') }}</td>
                                    <td class="align-middle text-end">
                                        <a href="{{ route('admin.pages.edit', $pageData['page']->id) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection