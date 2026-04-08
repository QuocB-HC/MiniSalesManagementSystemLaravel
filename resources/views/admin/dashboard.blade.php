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
        <aside class="sidebar">
            <div class="logo">
                <h2>MiniStore Admin</h2>
            </div>
            <nav>
                <ul>
                    <li class="active"><a href="#"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
                    <li><a href="#"><i class="fa-solid fa-box"></i> Products</a></li>
                    <li><a href="#"><i class="fa-solid fa-cart-shopping"></i> Orders</a></li>
                    <li><a href="#"><i class="fa-solid fa-users"></i> Users</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i>
                                Logout</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <h1>Dashboard Overview</h1>
                <div class="user-info">
                    <span>Welcome, <strong>{{ auth()->user()->name }}</strong></span>
                </div>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-wallet"></i></div>
                    <div class="stat-info">
                        <h3>Total Revenue</h3>
                        <p>50.000.000 VNĐ</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon pink"><i class="fa-solid fa-bag-shopping"></i></div>
                    <div class="stat-info">
                        <h3>Total Orders</h3>
                        <p>128</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fa-solid fa-user"></i></div>
                    <div class="stat-info">
                        <h3>Customers</h3>
                        <p>1,024</p>
                    </div>
                </div>
            </div>

            <section class="recent-section">
                <h2>Recent Orders</h2>
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
                        <tr>
                            <td>#1001</td>
                            <td>Nguyễn Văn A</td>
                            <td><span class="status pending">Pending</span></td>
                            <td>500.000 VNĐ</td>
                            <td><a href="#" class="view-btn">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>

</html>
