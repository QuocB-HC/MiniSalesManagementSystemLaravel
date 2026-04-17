<link rel="stylesheet" href="{{ asset('css/components/side-bar.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<button class="mobile-menu-btn" id="openSidebar">
    <i class="fa-solid fa-bars"></i>
</button>

<aside class="sidebar" id="sidebar">
    <div class="logo">
        <h2>Mini Store</h2>
        <button class="close-sidebar" id="closeSidebar">
            <i class="fa-solid fa-xmark"></i>
        </button>
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
            <li class="{{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}"><a
                    href='{{ route('admin.discounts.index') }}'>Discounts</a></li>
            <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><a
                    href='{{ route('admin.orders.index') }}'>Orders</a></li>
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><a
                    href='{{ route('admin.users.index') }}'>Users</a></li>
            <li>
                <form onsubmit="confirmModal(event, 'Logout', 'Are you sure to log out?')"
                    action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </li>
        </ul>
    </nav>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- <aside class="sidebar">
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
            <li class="{{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}"><a
                    href='{{ route('admin.discounts.index') }}'>Discounts</a></li>
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
</aside> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');

        // Hàm mở
        openBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        const closeAll = () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        };

        closeBtn.addEventListener('click', closeAll);
        overlay.addEventListener('click', closeAll);
    });
</script>
