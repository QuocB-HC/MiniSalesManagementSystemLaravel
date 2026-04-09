<header>
    <link rel="stylesheet" href="{{ asset('css/components/header.css') }}">
</header>

<div class="header-container">
    <div class="logo">
        <a href="/">My Mini Store</a>
    </div>
    <nav>
        <ul class="page-list">
            <li><a href="/">Home</a></li>
            <li><a href="/cart">Cart</a></li>
            @auth
                <li class="custom-dropdown" id="userDropdown">
                    <div class="dropdown-trigger" onclick="toggleUserMenu(event)">
                        <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                            alt="Avatar" class="avatar-img">
                    </div>

                    <div class="dropdown-content" id="dropdownMenu">
                        <div class="dropdown-header">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                        </div>
                        <a href="{{ route('profile.index') }}" class="dropdown-item">
                            Information
                        </a>
                        <a href="{{ route('orders.index') }}" class="dropdown-item">
                            Orders
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item logout-btn">
                                Logout
                            </button>
                        </form>
                    </div>
                </li>
            @endauth
            @guest
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @endguest
        </ul>
    </nav>
</div>

<script>
    function toggleUserMenu(event) {
        event.stopPropagation(); // Ngăn sự kiện click lan ra ngoài
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('show');
    }

    // Đóng menu khi click bất kỳ đâu bên ngoài
    window.onclick = function(event) {
        const menu = document.getElementById('dropdownMenu');
        if (menu && menu.classList.contains('show')) {
            if (!event.target.closest('#userDropdown')) {
                menu.classList.remove('show');
            }
        }
    }
</script>
