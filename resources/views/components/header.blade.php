<link rel="stylesheet" href="{{ asset('css/components/header.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="header-container">
    <ul class="header-list">
        <div class="logo">
            <a href="/">My Mini Store</a>
        </div>

        <div class="menu-list">
            <div class="page-list">
                <li><a href="{{ route('home') }}">
                        <i class="fa-solid fa-house"></i> Home</a>
                </li>
                <li><a href="{{ route('products.index') }}">
                        <i class="fa-solid fa-bag-shopping"></i> Products
                    </a></li>
                <li><a href="{{ route('cart.index') }}" class="nav-link">
                        <i class="fa-solid fa-cart-shopping"></i> Cart
                        <span id="cart-count" class="cart-badge {{ $cartCount > 0 ? '' : 'd-none' }}">
                            {{ $cartCount }}
                        </span>
                    </a></li>
            </div>

            <div class="search-header">
                <form action="{{ route('products.search') }}" method="GET" class="search-form">
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                        placeholder="Search products by name, SKU..." autocomplete="off">
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
                <div id="search-results" class="search-results-container" style="display: none;"></div>
            </div>
        </div>

        <div class="user-list">
            @auth
                @if (auth()->user()->role->value == 'seller')
                    <button type="button" onclick="window.location.href='{{ route('shop.index') }}'"
                        class="create-shop-btn">Your Shop</button>
                @endif

                @if (auth()->user()->role->value == 'customer')
                    <button type="button" onclick="window.location.href='{{ route('shop.create') }}'"
                        class="create-shop-btn">Create Shop</button>
                @endif

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
                        <form onsubmit="confirmModal(event, 'Logout', 'Are you sure to log out?')"
                            action="{{ route('logout') }}" method="POST" style="margin: 0;">
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

    document.getElementById('searchInput').addEventListener('input', function() {
        let query = this.value;
        let resultsBox = document.getElementById('search-results');

        if (query.length >= 2) {
            fetch("{{ route('products.searchAjax') }}?query=" + query)
                .then(res => res.json())
                .then(data => {
                    resultsBox.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(product => {
                            let detailUrl = "{{ route('products.detail', ['id' => ':id']) }}"
                                .replace(':id', product.id);

                            resultsBox.innerHTML += `
                            <a href="${detailUrl}" class="search-item">
                                <img src="${product.image_url}" width="40">
                                <div class="info">
                                    <span class="name">${product.name}</span>
                                    <span class="price">${new Intl.NumberFormat('vi-VN').format(product.price)} VND</span>
                                </div>
                            </a>
                        `;
                        });
                        resultsBox.style.display = 'block';
                    } else {
                        resultsBox.innerHTML = '<div class="no-result">No products found</div>';
                        resultsBox.style.display = 'block';
                    }
                });
        } else {
            resultsBox.style.display = 'none';
        }
    });

    document.addEventListener('click', function(e) {
        if (!document.querySelector('.search-header').contains(e.target)) {
            document.getElementById('search-results').style.display = 'none';
        }
    });
</script>
