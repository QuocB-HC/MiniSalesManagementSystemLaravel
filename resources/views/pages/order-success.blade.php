<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="stylesheet" href="{{ asset('css/pages/order-success.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="success-container">
        <div class="success-card">
            <div class="icon-box">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <h1>Thank you for your purchase!</h1>
            <p>Your order has been received and is being processed.</p>

            <div class="order-details">
                <div class="detail-item">
                    <span>Order ID:</span>
                    <strong>#ORD-{{ $order->id }}</strong>
                </div>
                <div class="detail-item">
                    <span>Total Amount:</span>
                    <strong>{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</strong>
                </div>
                <div class="detail-item">
                    <span>Payment Method:</span>
                    <strong>{{ $order->payment_method == 'cod' ? 'Cash on Delivery (COD)' : 'Bank Transfer' }}</strong>
                </div>
            </div>

            <div class="shipping-address">
                <h3><i class="fa-solid fa-location-dot"></i> Shipping Address:</h3>
                <p>{{ $order->receiver_name }} | {{ $order->receiver_phone }}</p>
                <p>{{ $order->receiver_address }}</p>
            </div>

            <div class="action-buttons">
                <a href="/" class="btn-home">Confirm</a>
            </div>
        </div>
    </div>
</body>

</html>
