<link rel="stylesheet" href="{{ asset('css/components/header.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="header-container">
    <ul class="header-list">
        <div class="logo">
            <a href="/">My Mini Store</a>
        </div>

        <div class="menu-list">
            <div class="page-list">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('products.index') }}">Products</a></li>
                <li><a href="{{ route('cart.index') }}">Cart</a></li>
            </div>

            <div class="search-header">
                <form action="{{ route('products.search') }}" method="GET" class="search-form">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search products by name, SKU...">
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i> </button>
                </form>
            </div>
        </div>

        <div class="user-list">
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
                <li>/</li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @endguest
        </div>
    </ul>
</div>

<script>
    function toggleUserMenu(event) {
        event.stopPropagation(); // Prevent event bubbling
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('show');
    }

    // Close dropdown menu when clicking outside of it
    window.onclick = function(event) {
        const menu = document.getElementById('dropdownMenu');
        if (menu && menu.classList.contains('show')) {
            if (!event.target.closest('#userDropdown')) {
                menu.classList.remove('show');
            }
        }
    }
</script>
