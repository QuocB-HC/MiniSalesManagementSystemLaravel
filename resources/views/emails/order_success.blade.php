<h1>Cảm ơn bạn đã đặt hàng!</h1>
<p>Chào {{ $order->user_name }},</p>
<p>Mã đơn hàng của bạn là: <strong>#{{ $order->id }}</strong></p>
<p>Tổng tiền: {{ number_format($order->total_price) }} VNĐ</p>
<p>Chúng tôi sẽ giao hàng sớm nhất có thể.</p>