<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - My Mini Store</title>
    <link rel="stylesheet" href="{{ asset('css/pages/order-history.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <x-header />

    <div class="order-history-container">
        <h1><i class="fa-solid fa-clock-rotate-left"></i> Your Order History</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($orders->isEmpty())
            <p class="no-orders">You haven't placed any orders yet.</p>
        @else
            <div class="order-list">
                @foreach ($orders as $order)
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-id">Order #{{ $order->id }}</span>
                            <span class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="order-body">
                            <p><strong>Total Items:</strong> {{ $order->total_quantity }}</p>
                            <p><strong>Total Price:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VNĐ
                            </p>
                            <p><strong>Status:</strong> <span
                                    class="order-status status-{{ Str::slug($order->status) }}">{{ ucfirst($order->status) }}</span>
                            </p>
                            @if ($order->discount_code)
                                <p><strong>Discount Applied:</strong> {{ $order->discount_code }}
                                    ({{ number_format($order->discount_value, 0, ',', '.') }} VNĐ)
                                </p>
                            @endif
                        </div>
                        <div class="order-footer">
                            <a href="{{ route('orders.detail', $order->id) }}" class="btn-view-details">View
                                Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="pagination-wrapper">
                <div class="pagination-container">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>

    <x-footer />
</body>

</html>
