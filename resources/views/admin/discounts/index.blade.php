<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Discounts Management</title>
    <link rel="stylesheet" href="{{ asset('css/admin/discounts/index.css') }}">
</head>

<body>
    <div class="main-container">
        <x-side-bar />

        <main class="main-content">
            <header>
                <h1>Discounts Management</h1>
                <a href="{{ route('admin.discounts.create') }}" class="view-btn btn-add">New Discount</a>
            </header>

            @if (session('success'))
                <div class="alert-container alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert-container alert-error">
                    {{ session('error') }}
                </div>
            @endif

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
                                <td>
                                    <span class="status {{ $discount->is_active ? 'available' : 'out_of_stock' }}">
                                        {{ $discount->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="action-btns">
                                    <a href="{{ route('admin.discounts.edit', $discount->id) }}"
                                        class="view-btn btn-edit">Edit</a>
                                    <form action="{{ route('admin.discounts.destroy', $discount->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this discount code?')">
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
        </main>
    </div>
</body>

</html>
