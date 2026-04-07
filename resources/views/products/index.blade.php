<div class="product-grid"
    style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; padding: 20px;">
    @foreach ($products as $product)
        <div class="card" style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; text-align: center;">
            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/150' }}"
                style="width: 100%; border-radius: 5px;">

            <h3 style="margin: 10px 0;">{{ $product->name }}</h3>
            <p style="color: #666; font-size: 0.9em;">Category: {{ $product->category->name }}</p>
            <p style="font-weight: bold; color: #e44d26; font-size: 1.2em;">
                {{ number_format($product->price, 0, ',', '.') }} VNĐ
            </p>

            <p style="font-size: 0.8em; color: {{ $product->stock_quantity > 0 ? 'green' : 'red' }}">
                Kho: {{ $product->stock_quantity }} | SKU: {{ $product->sku }}
            </p>

            <button
                style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                Add to cart
            </button>
        </div>
    @endforeach
</div>

<div style="margin-top: 20px;">
    {{ $products->links() }}
</div>
