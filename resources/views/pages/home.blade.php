<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mini Store - Trang chủ</title>
    <link rel="stylesheet" href="{{ asset('css/pages/home.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <x-header />

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Elevate Your Lifestyle with Mini Store</h1>
                <p>Discover a collection of top-tier tech and fashion products at unbeatable prices.</p>
                <a href="{{ route('products.index') }}" class="btn-primary">Buy Now <i
                        class="fas fa-arrow-right"></i></a>
            </div>
        </section>

        <!-- Categories -->
        <section class="categories container">
            <div class="section-header">
                <h2>Categories</h2>
            </div>

            <div class="category-grid">
                @foreach ($categories as $category)
                    <a href="{{ route('products.byCategory', $category->id) }}"class="category-item">
                        <span>{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Featured Products -->
        <section id="shop" class="featured-products container">
            <div class="section-header">
                <h2>Newest Products</h2>
                <a href="{{ route('products.index') }}" class="view-all">View All</a>
            </div>
            <div class="product-grid">
                @foreach ($products as $product)
                    <div class="product-card">
                        <div class="product-image">
                            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/150' }}"
                                alt="{{ $product->name }}">
                        </div>
                        <div class="product-info">
                            <div class="product-top">
                                <span class="product-cat">{{ $product->category->name }}</span> <br />
                                <a href="{{ route('products.detail', $product->id) }}"
                                    class="product-name">{{ $product->name }}</a>
                                <p class="product-desc">{{ $product->description }}</p>
                            </div>
                            <div class="product-bottom">
                                <span class="price">{{ number_format($product->price, 0, ',', '.') }} VND</span>

                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">

                                    <button type="submit" class="add-to-cart"
                                        {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>

    <x-footer />
</body>

</html>
