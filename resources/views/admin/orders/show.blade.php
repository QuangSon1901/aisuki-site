@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>
                        <i class="fas fa-shopping-cart me-2"></i> 
                        Order #{{ $order->order_number }}
                    </h5>
                    <div>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Orders
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Order Information</h6>
                            <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : ($order->status == 'cancelled' ? 'danger' : 'info')) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Customer Information</h6>
                            <p><strong>Name:</strong> {{ $order->full_name }}</p>
                            <p><strong>Email:</strong> <a href="mailto:{{ $order->email }}">{{ $order->email }}</a></p>
                            <p><strong>Phone:</strong> <a href="tel:{{ $order->phone }}">{{ $order->phone }}</a></p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold">{{ $order->delivery_method == 'delivery' ? 'Delivery' : 'Pickup' }} Information</h6>
                            @if($order->delivery_method == 'delivery')
                                <p><strong>Address:</strong> {{ $order->street }} {{ $order->house_number }}, {{ $order->postal_code }} {{ $order->city }}</p>
                                <p><strong>Delivery Time:</strong> {{ $order->delivery_time }}</p>
                            @else
                                <p><strong>Pickup Time:</strong> {{ $order->pickup_time }}</p>
                                <p><strong>Pickup Location:</strong> {{ setting('store_address') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($order->notes)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold">Order Notes</h6>
                            <p class="alert alert-info">{{ $order->notes }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold">Order Items</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th class="text-center" style="width: 80px;">Qty</th>
                                            <th class="text-end" style="width: 100px;">Price</th>
                                            <th class="text-end" style="width: 100px;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ $item->item_name }} {{ $item->item_code ? "({$item->item_code})" : "" }}</div>
                                                    @if($item->addons->count() > 0)
                                                        <div class="text-muted small">
                                                            @foreach($item->addons as $addon)
                                                                <div>+ {{ $addon->addon_name }} ({{ setting('currency', '€') }}{{ number_format($addon->price, 2) }})</div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    @if($item->note)
                                                        <div class="text-muted small fst-italic">Note: {{ $item->note }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">{{ setting('currency', '€') }}{{ number_format($item->price, 2) }}</td>
                                                <td class="text-end">{{ setting('currency', '€') }}{{ number_format($item->subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                            <td class="text-end">{{ setting('currency', '€') }}{{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        @if($order->delivery_fee > 0)
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Delivery Fee:</strong></td>
                                                <td class="text-end">{{ setting('currency', '€') }}{{ number_format($order->delivery_fee, 2) }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td class="text-end fw-bold">{{ setting('currency', '€') }}{{ number_format($order->total, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-tasks me-2"></i> Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label">Order Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-print me-2"></i> Actions</h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-outline-secondary w-100 mb-2" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Print Order
                    </button>
                    
                    <a href="mailto:{{ $order->email }}?subject=Order #{{ $order->order_number }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-envelope me-1"></i> Email Customer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .card-header div, .col-md-4, button, .btn, .nav, .footer {
            display: none !important;
        }
    }
</style>
@endpush