<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Categories Management</title>
    <link rel="stylesheet" href="{{ asset('css/admin/categories/index.css') }}">
</head>

<body>
    <div class="main-container">
        <x-side-bar />

        <main class="main-content">
            <header>
                <h1>Categories Management</h1>
                <a href="{{ route('admin.categories.create') }}" class="view-btn btn-add">New Category</a>
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
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>#{{ $category->id }}</td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td><small>{{ $category->slug }}</small></td>
                                <td class="text-left">
                                    {{ Str::limit($category->description, 50, '...') }}
                                </td>
                                <td>
                                    <span
                                        class="product-count">{{ $category->products_count ?? $category->products()->count() }}</span>
                                </td>
                                <td class="action-btns">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                        class="view-btn btn-edit">Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="view-btn btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="no-data">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="pagination-wrapper">
                    <div class="pagination-container">
                        {{ $categories->links() }}
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
