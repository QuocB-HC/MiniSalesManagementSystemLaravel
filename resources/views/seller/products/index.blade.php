@extends('layouts.user')

@section('title', 'Products - ' . $currentShop->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/products/index.css') }}">
@endpush

@section('content')
    <div class="main-container">
        <div class="header-wrapper">
            <div>
                <h1>Products of: {{ $currentShop->name }}</h1>
                <p>Manage your inventory and pricing.</p>
            </div>
            <a href="#" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Product
            </a>
        </div>

        @if ($shops->count() > 1)
            <div class="shop-card">
                <label>Switch Shop:</label>
                <div class="shop-list">
                    @foreach ($shops as $item)
                        <a href="{{ route('seller.products.index', $item->id) }}"
                            class="shop-item {{ $item->id == $currentShop->id ? 'active' : '' }}">
                            {{ $item->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th style="text-align: right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-img">
                                    <div>
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="product-sku">SKU: {{ $product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td>
                                <span class="badge {{ $product->stock_quantity > 0 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="#" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
                                    <form action="#" method="POST" style="margin:0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete"
                                            onclick="return confirm('Delete?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: #999;">
                                No products found in this shop.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-wrapper">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
