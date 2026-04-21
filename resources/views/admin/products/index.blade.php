@extends('layouts.admin')

@section('title', 'Products Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/products/index.css') }}">
@endpush

@section('content')
    <div class="main-container">
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
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-img">
                            </td>
                            <td><small>{{ $product->sku }}</small></td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td>
                                <span class="status {{ $product->status->value }}">{{ ucfirst(str_replace('_', ' ', $product->status->value)) }}</span>
                            </td>
                            <td class="action-btns">
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                    class="view-btn btn-edit">Edit</a>
                                <form
                                    onsubmit="confirmModal(event, 'Delete Product', 'Are you sure you want to delete this product?', 'delete')"
                                    action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
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
        </section>

        <div class="pagination-wrapper">
            <div class="pagination-container">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
