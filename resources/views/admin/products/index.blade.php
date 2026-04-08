<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Products Management</title>
    <link rel="stylesheet" href="{{ asset('css/admin/products/index.css') }}">
</head>

<body>
    <div class="main-container">
        <x-side-bar />

        <main class="main-content">
            <header>
                <h1>Products Management</h1>
                <a href="{{ route('admin.products.create') }}" class="view-btn btn-add">New Product</a>
            </header>

            <!-- Category Filter Tabs -->
            <div class="category-filters">
                <a href="{{ route('admin.products.index') }}" 
                   class="view-btn filter-btn {{ !request('category_id') ? 'active' : '' }}">
                    All
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('admin.products.index', ['category_id' => $cat->id]) }}" 
                       class="view-btn filter-btn {{ request('category_id') == $cat->id ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            @if (session('success'))
                <div class="alert-container">
                    <div class="alert-success">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <section class="recent-section">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>SKU</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>#{{ $product->id }}</td>
                                <td>
                                    <img src="{{ $product->image_url ?? asset('images/default-product.png') }}"
                                        alt="{{ $product->name }}"
                                        class="product-img">
                                </td>
                                <td><small>{{ $product->sku }}</small></td>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>
                                    <span class="status {{ $product->status }}">{{ ucfirst($product->status) }}</span>
                                </td>
                                <td class="action-btns">
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                        class="view-btn btn-edit">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="view-btn btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="no-data">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="pagination-wrapper">
                    <div class="pagination-container">
                        {{ $products->links() }}
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
