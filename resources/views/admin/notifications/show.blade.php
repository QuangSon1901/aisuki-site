@extends('admin.layouts.app')

@section('title', 'Notification Details')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas {{ $notification->getIconClass() }} me-2"></i> {{ $notification->title }}</h5>
            <div>
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Notifications
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold">Notification Details</h6>
                            <span class="badge bg-{{ $notification->getColorClass() }}">{{ ucfirst($notification->type) }}</span>
                        </div>
                        <p>{{ $notification->content }}</p>
                        <div class="text-muted small">
                            <span><i class="fas fa-clock me-1"></i> {{ $notification->created_at->format('M d, Y H:i') }}</span>
                            <span class="ms-3"><i class="fas fa-check{{ $notification->is_read ? '-double' : '' }} me-1"></i> {{ $notification->is_read ? 'Read' : 'Unread' }}</span>
                            <span class="ms-3"><i class="fas fa-tasks me-1"></i> {{ $notification->is_processed ? 'Processed' : 'Pending' }}</span>
                        </div>
                    </div>

                    @if($notification->type == 'order' && $notification->notifiable)
                        <div class="mb-4">
                            <h6 class="fw-bold">Order Information</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 200px;">Order Number</th>
                                        <td>{{ $notification->notifiable->order_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Customer</th>
                                        <td>{{ $notification->notifiable->full_name }} ({{ $notification->notifiable->email }})</td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <td>{{ setting('currency', '€') }}{{ number_format($notification->notifiable->total, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge bg-{{ $notification->notifiable->status == 'completed' ? 'success' : ($notification->notifiable->status == 'pending' ? 'warning' : ($notification->notifiable->status == 'cancelled' ? 'danger' : 'info')) }}">
                                                {{ ucfirst($notification->notifiable->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <a href="{{ route('admin.orders.show', $notification->notifiable) }}" class="btn btn-primary">
                                <i class="fas fa-eye me-1"></i> View Order Details
                            </a>
                        </div>
                    @endif

                    @if($notification->type == 'reservation' && $notification->notifiable)
                        <!-- Reservation details would go here -->
                    @endif

                    @if($notification->type == 'contact' && $notification->notifiable)
                        <!-- Contact form details would go here -->
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-cog me-2"></i> Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if(!$notification->is_processed)
                                    <form action="{{ route('admin.notifications.mark-processed', $notification) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100 mb-2">
                                            <i class="fas fa-check-circle me-1"></i> Mark as Processed
                                        </button>
                                    </form>
                                @endif

                                @if(!$notification->is_read)
                                    <form action="{{ route('admin.notifications.mark-read', $notification) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary w-100 mb-2">
                                            <i class="fas fa-check me-1"></i> Mark as Read
                                        </button>
                                    </form>
                                @endif

                                @if($notification->type == 'order' && $notification->notifiable)
                                    <a href="mailto:{{ $notification->notifiable->email }}?subject=Order #{{ $notification->notifiable->order_number }}" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-envelope me-1"></i> Email Customer
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection