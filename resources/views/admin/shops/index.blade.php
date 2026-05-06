@extends('layouts.admin')

@section('title', 'Shops Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/shops/index.css') }}">
@endpush

@section('content')
    <div class="main-container">
        <header>
            <h1>Shops Management</h1>
            {{-- <a href="{{ route('admin.shops.create') }}" class="view-btn btn-add">New Shop</a> --}}
        </header>

        <section class="recent-section">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Logo</th>
                        <th>User Name</th>
                        <th>Shop Name</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Social Media</th>
                        <th>Status</th>
                        <th>Products</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shops as $shop)
                        <tr>
                            <td>#{{ $shop->id }}</td>
                            <td>
                                <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" class="product-img">
                            </td>
                            <td><small>{{ $shop->user->name }}</small></td>
                            <td><strong>{{ $shop->name }}</strong></td>
                            <td>{{ $shop->address }}</td>
                            <td>{{ $shop->phone_number }}</td>
                            <td>
                                @php
                                    $platforms = [
                                        'facebook' => 'fab fa-facebook',
                                        'instagram' => 'fab fa-instagram',
                                        'twitter' => 'fab fa-twitter',
                                    ];
                                    $hasSocial = false;
                                @endphp

                                @foreach ($platforms as $key => $icon)
                                    @if ($url = $shop->{$key . '_url'})
                                        <a href="{{ $url }}" target="_blank" class="social-link">
                                            <i class="{{ $icon }}"></i>
                                        </a>
                                        @php $hasSocial = true; @endphp
                                    @endif
                                @endforeach

                                @if (!$hasSocial)
                                    <span class="no-data">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="status {{ $shop->status->value }}">{{ ucfirst(str_replace('_', ' ', $shop->status->value)) }}</span>
                            </td>
                            <td class="action-btns">
                                <a href="{{ route('admin.products.index', $shop->id) }}" class="view-btn btn-edit">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="no-data">No shops found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <div class="pagination-wrapper">
            <div class="pagination-container">
                {{ $shops->links() }}
            </div>
        </div>
    </div>
@endsection
