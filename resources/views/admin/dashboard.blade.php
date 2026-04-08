<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
</head>

<body>
    <div class="admin-container">
        <x-side-bar />

        <main class="main-content">
            <header>
                <h1>Overview</h1>
                <div class="user-info">
                    <span>Welcome, <strong>{{ auth()->user()->name }}</strong></span>
                </div>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-wallet"></i></div>
                    <div class="stat-info">
                        <h3>Total Revenue</h3>
                        <p>{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon pink"><i class="fa-solid fa-bag-shopping"></i></div>
                    <div class="stat-info">
                        <h3>Total Orders</h3>
                        <p>{{ number_format($totalOrders, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fa-solid fa-user"></i></div>
                    <div class="stat-info">
                        <h3>Customers</h3>
                        <p>{{ number_format($totalUsers, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <section class="recent-section">
                <h2>Pending Orders</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->receiver_name ?? 'Guest' }}</td>
                                <td><span class="status pending">{{ ucfirst($order->status) }}</span></td>
                                <td>{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</td>
                                <td><a href="#" class="view-btn">View</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center;">No pending orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>

</html>
