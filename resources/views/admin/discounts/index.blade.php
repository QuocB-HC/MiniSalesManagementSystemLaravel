@extends('layouts.admin')

@section('title', 'Discounts Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/discounts/index.css') }}">
@endpush

@section('content')
    <div class="main-container">
        <header>
            <h1>Discounts Management</h1>
            <a href="{{ route('admin.discounts.create') }}" class="view-btn btn-add">New Discount</a>
        </header>

        <section class="recent-section">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Min Order</th>
                        <th>Usage</th>
                        <th>Expires At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($discounts as $discount)
                        <tr>
                            <td>#{{ $discount->id }}</td>
                            <td><strong>{{ $discount->code }}</strong></td>
                            <td>{{ ucfirst($discount->type) }}</td>
                            <td>
                                {{ $discount->type === 'percentage' ? number_format($discount->value, 0) . '%' : number_format($discount->value, 0) . ' VND' }}
                            </td>
                            <td>{{ $discount->min_order_value ? number_format($discount->min_order_value, 0) . ' VND' : '-' }}
                            </td>
                            <td>{{ $discount->used_count }} / {{ $discount->usage_limit ?? '∞' }}</td>
                            <td>{{ $discount->expires_at ? \Carbon\Carbon::parse($discount->expires_at)->format('Y-m-d') : 'No Expiry' }}
                            </td>
                            <td class="text-center">
                                <span class="status {{ $discount->is_active ? 'available' : 'out_of_stock' }}">
                                    {{ $discount->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="action-btns">
                                <a href="{{ route('admin.discounts.edit', $discount->id) }}"
                                    class="view-btn btn-edit">Edit</a>
                                <form
                                    onsubmit="confirmModal(event, 'Delete Discount', 'Are you sure you want to delete this discount code?', 'delete')"
                                    action="{{ route('admin.discounts.destroy', $discount->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="view-btn btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="no-data">No discounts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <div class="pagination-wrapper">
            <div class="pagination-container">
                {{ $discounts->links() }}
            </div>
        </div>
    </div>
@endsection
