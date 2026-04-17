@extends('layouts.admin')

@section('title', 'Categories Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/categories/index.css') }}">
@endpush

@section('content')
    <div class="main-container">
        <header>
            <h1>Categories Management</h1>
            <a href="{{ route('admin.categories.create') }}" class="view-btn btn-add">New Category</a>
        </header>

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
                            <td data-label="ID">#{{ $category->id }}</td>
                            <td data-label="Name"><strong>{{ $category->name }}</strong></td>
                            <td data-label="Slug"><small>{{ $category->slug }}</small></td>
                            <td data-label="Description" class="text-left">
                                {{ Str::limit($category->description, 50, '...') }}
                            </td>
                            <td data-label="Products">
                                <span
                                    class="product-count">{{ $category->products_count ?? $category->products()->count() }}</span>
                            </td>
                            <td data-label="Actions" class="action-btns">
                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                    class="view-btn btn-edit">Edit</a>
                                <form
                                    onsubmit="confirmModal(event, 'Delete Category', 'Are you really sure to delete category {{ $category->name }}?', 'delete')"
                                    action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
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
        </section>

        <div class="pagination-wrapper">
            <div class="pagination-container">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection
