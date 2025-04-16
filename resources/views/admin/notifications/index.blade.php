<!-- resources/views/admin/notifications/index.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-bell me-2"></i> Notifications</h5>
            <div>
                <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-check-double me-1"></i> Mark All as Read
                    </button>
                </form>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.notifications.index') }}" class="btn {{ !request()->has('type') ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                    <a href="{{ route('admin.notifications.index', ['type' => 'order']) }}" class="btn {{ request()->get('type') == 'order' ? 'btn-primary' : 'btn-outline-primary' }}">Orders</a>
                    <a href="{{ route('admin.notifications.index', ['type' => 'reservation']) }}" class="btn {{ request()->get('type') == 'reservation' ? 'btn-primary' : 'btn-outline-primary' }}">Reservations</a>
                    <a href="{{ route('admin.notifications.index', ['type' => 'contact']) }}" class="btn {{ request()->get('type') == 'contact' ? 'btn-primary' : 'btn-outline-primary' }}">Contact</a>
                </div>
                <div class="btn-group ms-2" role="group">
                    <a href="{{ route('admin.notifications.index', ['read' => 0]) }}" class="btn {{ request()->get('read') === '0' ? 'btn-primary' : 'btn-outline-primary' }}">Unread</a>
                    <a href="{{ route('admin.notifications.index', ['processed' => 0]) }}" class="btn {{ request()->get('processed') === '0' ? 'btn-primary' : 'btn-outline-primary' }}">Unprocessed</a>
                </div>
            </div>

            @if($notifications->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No notifications found
                </div>
            @else
                <div class="list-group">
                    @foreach($notifications as $notification)
                        <a href="{{ route('admin.notifications.show', $notification) }}" class="list-group-item list-group-item-action {{ $notification->is_read ? '' : 'list-group-item-light' }}">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="badge rounded-pill bg-{{ $notification->getColorClass() }}" style="height: 45px; width: 45px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas {{ $notification->getIconClass() }} fa-lg"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 {{ $notification->is_read ? '' : 'fw-bold' }}">{{ $notification->title }}</h6>
                                        <p class="mb-1 text-muted">{{ Str::limit($notification->content, 100) }}</p>
                                        <small class="text-muted">
                                            {{ $notification->created_at->diffForHumans() }}
                                            @if(!$notification->is_read)
                                                <span class="badge bg-danger ms-2">New</span>
                                            @endif
                                            @if($notification->is_processed)
                                                <span class="badge bg-success ms-2">Processed</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection