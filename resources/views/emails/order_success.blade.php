<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f4f4;
            padding-bottom: 40px;
        }

        .main-table {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            color: #333333;
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            background-color: #28a745;
            padding: 40px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
        }

        .content {
            padding: 40px;
        }

        .order-box {
            background-color: #f9f9f9;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }

        .order-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .label {
            display: table-cell;
            color: #888888;
            font-size: 14px;
        }

        .value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
        }

        .total-price {
            font-size: 20px;
            color: #28a745;
        }

        .button-wrapper {
            text-align: center;
            padding-top: 30px;
        }

        .btn {
            background-color: #28a745;
            color: #ffffff;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #999999;
        }
    </style>
</head>

<body>
    <center class="wrapper">
        <table class="main-table">
            <tr>
                <td class="header">
                    <h1>My Mini Store</h1>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <h2 style="margin-top: 0;">Thank you for your order!</h2>
                    <p>Hello <strong>{{ $order->receiver_name }}</strong>,</p>
                    <p>Your order has been successfully received. Here are your order details:</p>

                    <div class="order-box">
                        <div class="order-row">
                            <span class="label">Order ID:</span>
                            <span class="value">#{{ $order->id }}</span>
                        </div>
                        <div class="order-row">
                            <span class="label">Payment Method:</span>
                            <span class="value">{{ strtoupper($order->payment_method->value) }}</span>
                        </div>
                        <div class="order-row" style="margin-top: 15px;">
                            <span class="label" style="vertical-align: middle;">Total Price:</span>
                            <span class="value total-price">{{ number_format($order->total_price, 0, ',', '.') }}
                                VND</span>
                        </div>
                    </div>

                    <p>We will contact you soon by phone <strong>{{ $order->receiver_phone }}</strong>.
                    </p>

                    <div class="button-wrapper">
                        <a href="{{ route('orders.index') }}" class="btn">Check Order History</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <p>© 2026 My Mini Store. All rights reserved.</p>
                    <p>Your Address: 123 ABC, TP. Hồ Chí Minh</p>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>
