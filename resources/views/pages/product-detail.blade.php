<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $product->name }} Detail</title>
    <link rel="stylesheet" href="{{ asset('css/pages/product-detail.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <x-header />

    <div class="product-container">
        <div class="product-main">
            <div class="product-gallery">
                <div class="main-image">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" id="currentImage">
                </div>
            </div>

            <div class="product-info">
                <nav class="breadcrumb">
                    <a href="/">Home</a> >
                    <a
                        href="{{ route('products.byCategory', $product->category->id) }}">{{ $product->category->name }}</a>
                    >
                    <a>{{ $product->name }}</a>
                </nav>

                <h1 class="product-title">{{ $product->name }}</h1>

                <div class="product-meta">
                    <span class="sku">SKU: PRO-{{ $product->id }}</span>
                    <span class="stock {{ $product->stock_quantity > 0 ? 'in-stock' : 'out-of-stock' }}">
                        {{ $product->stock_quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}
                    </span>
                </div>

                <div class="product-price">
                    <span class="current-price">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                    {{-- <span class="old-price">1.200.000 VNĐ</span> --}}
                </div>

                <p class="short-description">
                    {{ Str::limit($product->description, 150) }}
                </p>

                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <div class="quantity-selector">
                        <button type="button" onclick="changeQty(-1)">-</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1"
                            max="{{ $product->stock_quantity }}" readonly>
                        <button type="button" onclick="changeQty(1)">+</button>
                    </div>

                    <button type="submit" class="btn-add-cart" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                </form>

                <div class="product-trust-badges">
                    <div class="badge-item"><i class="fas fa-truck"></i> Giao hàng toàn quốc</div>
                    <div class="badge-item"><i class="fas fa-undo"></i> 7 ngày đổi trả</div>
                    <div class="badge-item"><i class="fas fa-shield-alt"></i> Bảo hành chính hãng</div>
                </div>
            </div>
        </div>

        <div class="product-tabs">
            <div class="tab-header">
                Mô tả sản phẩm
            </div>
            <div class="tab-content">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
    </div>

    <x-footer />


    <script>
        function changeQty(amount) {
            const qtyInput = document.getElementById('quantity');
            let currentQty = parseInt(qtyInput.value);
            let maxQty = parseInt(qtyInput.getAttribute('max'));

            currentQty += amount;
            if (currentQty < 1) currentQty = 1;
            if (currentQty > maxQty) currentQty = maxQty;

            qtyInput.value = currentQty;
        }
    </script>
</body>

</html>
