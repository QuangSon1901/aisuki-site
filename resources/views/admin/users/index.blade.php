@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Users</h5>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New User
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <span>{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                {{ $user->name }}
                                                @if($user->id === Auth::id())
                                                    <span class="badge bg-info ms-1">You</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">{{ $user->email }}</td>
                                    <td class="align-middle">
                                        @if($user->is_admin)
                                            <span class="badge bg-danger">Administrator</span>
                                        @else
                                            <span class="badge bg-secondary">User</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $user->created_at->format('M d, Y') }}</td>
                                    <td class="align-middle text-end">
                                        <div class="d-flex justify-content-end">
                                            @if($user->id === Auth::id())
                                                <a href="{{ route('admin.profile.edit') }}" class="btn btn-sm btn-info me-2">
                                                    <i class="fas fa-user-edit"></i> My Profile
                                                </a>
                                            @else
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info me-2">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                this.submit();
            }
        });
    });
</script>
@endpush