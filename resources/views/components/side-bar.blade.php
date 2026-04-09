<head>
    <link rel="stylesheet" href="{{ asset('css/components/side-bar.css') }}">
</head>

<aside class="sidebar">
    <div class="logo">
        <h2>Mini Store</h2>
    </div>
    <nav>
        <ul>
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><a
                    href='{{ route('admin.dashboard') }}'>Dashboard</a></li>
            <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><a
                    href='{{ route('admin.categories.index') }}'>Categories</a>
            </li>
            <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><a
                    href='{{ route('admin.products.index') }}'>Products</a></li>
            <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><a
                    href='{{ route('admin.orders.index') }}'>Orders</a></li>
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><a
                    href='{{ route('admin.users.index') }}'>Users</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </li>
        </ul>
    </nav>
</aside>
