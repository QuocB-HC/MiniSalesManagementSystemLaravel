<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details #{{ $order->id }} - My Mini Store</title>
    <link rel="stylesheet" href="{{ asset('css/pages/order-detail.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <x-header />

    <div class="order-detail-container">
        <div class="header-actions">
            <a href="{{ route('orders.index') }}" class="btn-back">
                <i class="fa-solid fa-chevron-left"></i> Back
            </a>
            <h1>Order Detail #{{ $order->id }}</h1>
        </div>

        <div class="order-info-grid">
            <!-- Delivery information -->
            <div class="info-card">
                <h3><i class="fa-solid fa-location-dot"></i> Delivery information</h3>
                <p><strong>Receiver:</strong> {{ $order->receiver_name }}</p>
                <p><strong>Phone:</strong> {{ $order->receiver_phone }}</p>
                <p><strong>Address:</strong> {{ $order->receiver_address }}</p>
                @if ($order->note)
                    <p><strong>Note:</strong> {{ $order->note }}</p>
                @endif
            </div>

            <!-- Order Status -->
            <div class="info-card">
                <h3><i class="fa-solid fa-circle-info"></i> Order Information</h3>
                <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Status:</strong>
                    <span class="status-badge status-{{ Str::slug($order->status) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                <p><strong>Payment Method:</strong> Cash on Delivery (COD)</p>
            </div>
        </div>

        <!-- Product List -->
        <div class="items-section">
            <h3><i class="fa-solid fa-basket-shopping"></i> Ordered Products</h3>
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td data-label="Product">
                                <div class="product-info">
                                    <img src="{{ $item->product->image_url ?? asset('images/no-image.png') }}" alt="{{ $item->product->name }}">
                                    <span>{{ $item->product->name }}</span>
                                </div>
                            </td>
                            <td data-label="Price">{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                            <td data-label="Quantity">{{ $item->quantity }}</td>
                            <td data-label="Total Price">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="order-summary">
            <div class="summary-row">
                <span>Total Quantity:</span>
                <span>{{ $order->total_quantity }}</span>
            </div>
            @if ($order->discount_code)
                <div class="summary-row">
                    <span>Discount ({{ $order->discount_code }}):</span>
                    <span style="color: #27ae60;">- {{ number_format($order->discount_value, 0, ',', '.') }} VNĐ</span>
                </div>
            @endif
            <div class="summary-row total">
                <span>Total Payment:</span>
                <span>{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</span>
            </div>
        </div>
    </div>

    <x-footer />
</body>

</html>
