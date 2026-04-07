<header>
    <link rel="stylesheet" href="{{ asset('css/components/header.css') }}">
</header>

<div class="container">
    <div class="logo">
        My Mini Store
    </div>
    <nav>
        <ul class="page-list">
            <li><a href="/">Home</a></li>
            <li><a href="/cart">Cart</a></li>
            @auth
                <li class="user-menu">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                            Logout
                        </a>
                    </form>

                    <a href="{{ route('profile') }}" class="user-avatar">
                        <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                            alt="Avatar"
                            style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #555;">
                    </a>
                </li>
            @endauth
            @guest
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @endguest
        </ul>
    </nav>
</div>
