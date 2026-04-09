<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="{{ asset('css/pages/cart.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <x-header />

    <div class="cart-container">
        <h1 class="cart-title">Shopping Cart</h1>

        @if (session('cart') && count(session('cart')) > 0)
            <div class="cart-wrapper">
                <div class="cart-items">
                    @foreach (session('cart') as $id => $details)
                        <div class="cart-item">
                            <div class="item-img">
                                <img src="{{ $details['image'] ?? 'https://via.placeholder.com/100' }}"
                                    alt="{{ $details['name'] }}">
                            </div>
                            <div class="item-info">
                                <h3>{{ $details['name'] }}</h3>
                                <p class="item-price">{{ number_format($details['price'], 0, ',', '.') }} VNĐ</p>
                                <div class="item-qty">
                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="qty-form">
                                        @csrf
                                        <button type="submit" name="action" value="decrease"
                                            class="qty-btn">-</button>

                                        <input type="text" class="qty-input" value="{{ $details['quantity'] }}"
                                            readonly>

                                        <button type="submit" name="action" value="increase"
                                            class="qty-btn">+</button>
                                    </form>
                                </div>
                            </div>
                            <div class="item-total">
                                {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }} VNĐ
                            </div>
                            <div class="item-remove">
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"><i class="fa-regular fa-trash-can"></i></button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="cart-summary">
                    <div class="summary-box">
                        <h3>Order Summary</h3>
                        <div class="summary-item">
                            <span>Subtotal</span>
                            <span>{{ number_format($totalAmount, 0, ',', '.') }} VNĐ</span>
                        </div>
                        <div class="summary-item">
                            <span>Shipping</span>
                            <span class="free">Free</span>
                        </div>
                        <hr>
                        <div class="summary-total">
                            <span>Total</span>
                            <span>{{ number_format($totalAmount, 0, ',', '.') }} VNĐ</span>
                        </div>
                        <div class="summary-actions">
                            <a href="{{ route('checkout.index') }}" class="btn-checkout">Proceed to Checkout</a>
                            <a href="{{ route('home') }}" class="continue-shopping">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <i class="fa-solid fa-cart-shopping"></i>
                <p>Your cart is empty!</p>
                <a href="/" class="btn-back">Go Shopping</a>
            </div>
        @endif
    </div>
</body>

</html>
