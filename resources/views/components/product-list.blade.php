<head>
    <link rel="stylesheet" href="{{ asset('css/components/product-list.css') }}">
</head>

<div class="product-grid">
    @foreach ($products as $product)
        <div class="product-card">
            <div style="justify-content: space-between; display: flex; flex-direction: column; height: 100%;">
                <img src="{{ $product->image_url ?? 'https://via.placeholder.com/150' }}" class="product-image"
                    alt="{{ $product->name }}">

                <h3 class="product-name">{{ $product->name }}</h3>

                <div>
                    <p class="product-category">Category: {{ $product->category->name }}</p>
                    <p class="product-price">
                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                    </p>
                </div>
            </div>

            <div>
                <p class="product-stock" style="color: {{ $product->stock_quantity > 0 ? 'green' : 'red' }}">
                    Kho: {{ $product->stock_quantity }} | SKU: {{ $product->sku }}
                </p>

                <button class="btn-add-cart">
                    Add to cart
                </button>
            </div>
        </div>
    @endforeach
</div>

<div class="pagination-container">
    {{ $products->links() }}
</div>
