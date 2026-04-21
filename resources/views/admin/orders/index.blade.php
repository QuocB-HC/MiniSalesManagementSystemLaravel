@extends('layouts.admin')

@section('title', 'Orders Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/orders/index.css') }}">
@endpush

@section('content')
    <div class="main-container">
        <header>
            <h1>Orders Management</h1>
        </header>

        <div class="search-box">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="search-form">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Enter Order ID, Email or Phone number..." class="search-input">
                <button type="submit" class="view-btn btn-search">Search Order</button>
            </form>
        </div>

        <section class="recent-section">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>${{ number_format($order->total_price, 2) }}</td>
                            <td><span class="status {{ $order->status->value }}">{{ ucfirst($order->status->value) }}</span></td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                @if ($order->status->value !== 'cancelled')
                                    <form
                                        onsubmit="confirmModal(event, 'Chage Order Status', 'Are you sure to change status of this order?')"
                                        action="{{ route('admin.orders.updateStatus', $order) }}" method="POST"
                                        class="action-btns">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="status-select">
                                            <option value="pending" {{ $order->status->value === 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="processing"
                                                {{ $order->status->value === 'processing' ? 'selected' : '' }}>Processing
                                            </option>
                                            <option value="shipping" {{ $order->status->value === 'shipping' ? 'selected' : '' }}>
                                                Shipping
                                            </option>
                                            <option value="completed"
                                                {{ $order->status->value === 'completed' ? 'selected' : '' }}>Completed
                                            </option>
                                            <option value="cancelled"
                                                {{ $order->status->value === 'cancelled' ? 'selected' : '' }}>Cancelled
                                            </option>
                                        </select>
                                        <button type="submit" class="update-button">Update</button>
                                    </form>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="no-data">
                                {{ request('search') ? 'No orders found matching your search.' : 'Please enter information to search for orders.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-wrapper">
                <div class="pagination-container">
                    {{ $orders->links() }}
                </div>
            </div>
        </section>
    </div>
@endsection
