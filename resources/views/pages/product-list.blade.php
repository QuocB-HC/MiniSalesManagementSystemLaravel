<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product List</title>
    <link rel="stylesheet" href="{{ asset('css/pages/product-list.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <x-header />

    <div class="main-container">
        <div class="category-slider-container">
            <button class="nav-btn prev" onclick="scrollSlider(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="category-list" id="categorySlider">
                @foreach ($categories as $category)
                    <a href="{{ route('products.byCategory', $category->id) }}" class="category-item">
                        {{ $category->name }}</a>
                @endforeach
            </div>

            <button class="nav-btn next" onclick="scrollSlider(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="product-grid">
            @foreach ($products as $product)
                <div class="product-card">
                    <div style="justify-content: space-between; display: flex; flex-direction: column; height: 100%;">
                        <img src="{{ $product->image_url ?? 'https://via.placeholder.com/150' }}" class="product-image"
                            alt="{{ $product->name }}">

                        <div class="product-top">
                            <a href="{{ route('products.detail', $product->id) }}"
                                class="product-name">{{ $product->name }}</a>
                            <p class="product-category">Category: {{ $product->category->name }}</p>
                        </div>

                        <div class="product-bottom">
                            <p class="product-stock"
                                style="color: {{ $product->stock_quantity > 0 ? 'green' : 'red' }}">
                                Kho: {{ $product->stock_quantity }} | SKU: {{ $product->sku }}
                            </p>

                            <div class="product-price-container">
                                <p class="product-price">
                                    {{ number_format($product->price, 0, ',', '.') }} VNĐ
                                </p>
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">

                                    <button type="submit" class="btn-add-cart"
                                        {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination-wrapper">
            <div class="pagination-container">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <x-footer />

    <script>
        function scrollSlider(direction) {
            const slider = document.getElementById('categorySlider');
            // Tính toán khoảng cách cuộn (khoảng 2 item mỗi lần nhấp)
            const scrollAmount = 360;

            if (direction === -1) {
                slider.scrollLeft -= scrollAmount;
            } else {
                slider.scrollLeft += scrollAmount;
            }
        }
    </script>
</body>

</html>
