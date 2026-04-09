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
                <i class="fa-solid fa-chevron-left"></i> Quay lại lịch sử
            </a>
            <h1>Chi tiết đơn hàng #{{ $order->id }}</h1>
        </div>

        <div class="order-info-grid">
            <!-- Thông tin giao hàng -->
            <div class="info-card">
                <h3><i class="fa-solid fa-location-dot"></i> Thông tin nhận hàng</h3>
                <p><strong>Người nhận:</strong> {{ $order->receiver_name }}</p>
                <p><strong>Điện thoại:</strong> {{ $order->receiver_phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->receiver_address }}</p>
                @if ($order->note)
                    <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
                @endif
            </div>

            <!-- Trạng thái đơn hàng -->
            <div class="info-card">
                <h3><i class="fa-solid fa-circle-info"></i> Thông tin đơn hàng</h3>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Trạng thái:</strong>
                    <span class="status-badge status-{{ Str::slug($order->status) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                <p><strong>Hình thức:</strong> Thanh toán khi nhận hàng (COD)</p>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="items-section">
            <h3><i class="fa-solid fa-basket-shopping"></i> Sản phẩm đã đặt</h3>
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="{{ $item->product->image_url ?? asset('images/no-image.png') }}" alt="{{ $item->product->name }}">
                                    <span>{{ $item->product->name }}</span>
                                </div>
                            </td>
                            <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tổng kết -->
        <div class="order-summary">
            <div class="summary-row">
                <span>Tổng số lượng:</span>
                <span>{{ $order->total_quantity }}</span>
            </div>
            @if ($order->discount_code)
                <div class="summary-row">
                    <span>Giảm giá ({{ $order->discount_code }}):</span>
                    <span style="color: #27ae60;">- {{ number_format($order->discount_value, 0, ',', '.') }} VNĐ</span>
                </div>
            @endif
            <div class="summary-row total">
                <span>Tổng thanh toán:</span>
                <span>{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</span>
            </div>
        </div>
    </div>
</body>

</html>
