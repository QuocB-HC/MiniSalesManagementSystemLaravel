@extends('layouts.user')

@section('title', 'Shop Information - ' . $shop->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/shops/index.css') }}">
@endpush

@section('content')
    <div class="main-container">
        <header class="mb-4">
            <h1>Shop Details</h1>
            
            @if ($shops->count() > 1)
                <div class="shop-card">
                    <label>Switch Shop:</label>
                    <div class="shop-list">
                        @foreach ($shops as $item)
                            <a href="{{ route('seller.shop.index', $item->id) }}"
                                class="shop-item {{ $item->id == $shop->id ? 'active' : '' }}">
                                {{ $item->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </header>

        <div class="info-container">
            <div class="shop-sidebar">
                <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" class="shop-logo-large">
                <h2 class="mb-2">{{ $shop->name }}</h2>
                <span class="status-badge {{ $shop->status->value }}">
                    {{ ucfirst($shop->status->value) }}
                </span>

                <div class="mt-4 social-links">
                    @if ($shop->facebook_url)
                        <a href="{{ $shop->facebook_url }}" target="_blank"><i class="fab fa-facebook"></i></a>
                    @endif
                    @if ($shop->instagram_url)
                        <a href="{{ $shop->instagram_url }}" target="_blank"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if ($shop->twitter_url)
                        <a href="{{ $shop->twitter_url }}" target="_blank"><i class="fab fa-twitter"></i></a>
                    @endif
                </div>
            </div>

            <div class="shop-details">
                <h3 class="border-bottom pb-2 mb-4">Basic Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Owner</label>
                        <p>{{ $shop->user->name }}</p>
                    </div>
                    <div class="info-item">
                        <label>Contact Email</label>
                        <p>{{ $shop->user->email }}</p>
                    </div>
                    <div class="info-item">
                        <label>Phone Number</label>
                        <p>{{ $shop->phone }}</p>
                    </div>
                    <div class="info-item">
                        <label>Registration Date</label>
                        <p>{{ $shop->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div class="info-item" style="grid-column: span 2;">
                        <label>Address</label>
                        <p>{{ $shop->address }}</p>
                    </div>
                </div>

                <div class="btn-group">
                    @if ($shop->status->value === 'pending')
                        <form action="{{ route('admin.shops.approve', $shop->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="view-btn btn-add">Approve Shop</button>
                        </form>
                    @endif

                    <a href="{{ route('seller.products.index', $shop->id) }}" class="view-btn btn-edit">
                        View Products ({{ $shop->products_count ?? $shop->products->count() }})
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
