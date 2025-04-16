<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Order Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #e61c23;
            color: white;
        }
        .content {
            padding: 20px;
        }
        .customer-details, .order-details {
            margin: 20px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
        }
        .order-summary {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .footer {
            text-align: center;
            font-size: 0.8em;
            color: #777;
            padding: 20px 0;
            border-top: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $site_name }}</h1>
        <p>New Order Notification</p>
    </div>
    
    <div class="content">
        <p>A new order has been received!</p>
        
        <div class="customer-details">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $order->full_name }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            <p><strong>Phone:</strong> {{ $order->phone }}</p>
        </div>
        
        <div class="order-details">
            <h3>Order Details</h3>
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y, g:i a') }}</p>
            <p><strong>Delivery Method:</strong> {{ ucfirst($order->delivery_method) }}</p>
            @if($order->delivery_method == 'delivery')
                <p><strong>Delivery Address:</strong> {{ $order->street }} {{ $order->house_number }}, {{ $order->postal_code }} {{ $order->city }}</p>
                <p><strong>Delivery Time:</strong> {{ $order->delivery_time }}</p>
            @else
                <p><strong>Pickup Time:</strong> {{ $order->pickup_time }}</p>
            @endif
            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
            @if($order->notes)
                <p><strong>Notes:</strong> {{ $order->notes }}</p>
            @endif
        </div>
        
        <div class="order-items">
            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                {{ $item->item_name }} {{ $item->item_code ? "({$item->item_code})" : "" }}
                                @if($item->addons->count() > 0)
                                    <br>
                                    <small>
                                        @foreach($item->addons as $addon)
                                            + {{ $addon->addon_name }} ({{ setting('currency', '€') }}{{ number_format($addon->price, 2) }})<br>
                                        @endforeach
                                    </small>
                                @endif
                                @if($item->note)
                                    <br>
                                    <small><em>Note: {{ $item->note }}</em></small>
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ setting('currency', '€') }}{{ number_format($item->price, 2) }}</td>
                            <td>{{ setting('currency', '€') }}{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="order-summary">
            <p><strong>Subtotal:</strong> {{ setting('currency', '€') }}{{ number_format($order->subtotal, 2) }}</p>
            @if($order->delivery_fee > 0)
                <p><strong>Delivery Fee:</strong> {{ setting('currency', '€') }}{{ number_format($order->delivery_fee, 2) }}</p>
            @endif
            <p class="total-row"><strong>Total:</strong> {{ setting('currency', '€') }}{{ number_format($order->total, 2) }}</p>
        </div>
        
        <p>Please process this order as soon as possible.</p>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} {{ $site_name }}. All rights reserved.</p>
    </div>
</body>
</html>