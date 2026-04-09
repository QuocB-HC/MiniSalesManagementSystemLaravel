<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - My Mini Store</title>
    <link rel="stylesheet" href="{{ asset('css/pages/payment.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <x-header />

    <div class="payment-container">
        <form action="{{ route('checkout.placeOrder') }}" method="POST" class="payment-wrapper">
            @csrf

            <div class="shipping-info">
                <h2><i class="fa-solid fa-truck"></i> Shipping Information</h2>

                <div class="info-card">
                    <div class="input-group">
                        <label>Receiver Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" required>
                    </div>

                    <div class="input-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="{{ $user->phone }}"
                            placeholder="Enter phone number" required>
                    </div>

                    <div class="input-group">
                        <label>Shipping Address</label>
                        <textarea name="address" rows="3" required placeholder="Enter your full address">{{ $user->address }}</textarea>
                    </div>
                </div>

                <div class="payment-methods">
                    <h2><i class="fa-solid fa-credit-card"></i> Payment Method</h2>
                    <div class="method-options">
                        <label class="method-item">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <span class="checkmark"></span>
                            <i class="fa-solid fa-money-bill-wave"></i> Cash on Delivery (COD)
                        </label>
                        <label class="method-item">
                            <input type="radio" name="payment_method" value="bank">
                            <span class="checkmark"></span>
                            <i class="fa-solid fa-building-columns"></i> Bank Transfer
                        </label>
                    </div>
                </div>
            </div>

            <div class="order-summary">
                <div class="summary-card">
                    <h3>Order Summary</h3>
                    <div class="item-list">
                        @foreach ($cartItems as $item)
                            <div class="summary-item">
                                <span>{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                                <span>{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} VNĐ</span>
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>{{ number_format($totalAmount, 0, ',', '.') }} VNĐ</span>
                    </div>
                    <div class="total-row">
                        <span>Total Quantity</span>
                        <span>{{ array_sum(array_column($cartItems, 'quantity')) }}</span>
                    </div>
                    <div class="total-row">
                        <span>Shipping Fee</span>
                        <span class="free">Free</span>
                    </div>
                    <div class="total-row final">
                        <span>Total Pay</span>
                        <span>{{ number_format($totalAmount, 0, ',', '.') }} VNĐ</span>
                    </div>

                    <button type="submit" class="btn-confirm">Place Order Now</button>
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>
</body>

</html>
