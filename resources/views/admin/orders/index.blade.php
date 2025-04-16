<!-- resources/views/admin/orders/index.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-shopping-cart me-2"></i> Orders</h5>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.orders.index') }}" class="btn {{ !request()->has('status') ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn {{ request()->get('status') == 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">Pending</a>
                    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="btn {{ request()->get('status') == 'processing' ? 'btn-primary' : 'btn-outline-primary' }}">Processing</a>
                    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="btn {{ request()->get('status') == 'completed' ? 'btn-primary' : 'btn-outline-primary' }}">Completed</a>
                    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="btn {{ request()->get('status') == 'cancelled' ? 'btn-primary' : 'btn-outline-primary' }}">Cancelled</a>
                </div>
            </div>

            @if($orders->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No orders found
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>
                                        <div>{{ $order->full_name }}</div>
                                        <div class="small text-muted">{{ $order->email }}</div>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ setting('currency', '€') }}{{ number_format($order->total, 2) }}</td>
                                    <td>{{ ucfirst($order->delivery_method) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : ($order->status == 'cancelled' ? 'danger' : 'info')) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection