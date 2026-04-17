@extends('layouts.admin', ['hideSideBar' => true])

@section('title', 'Edit Category: ' . $category->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/categories/create.css') }}">
@endpush

@section('content')
    <div class="main-container">
        <header>
            <h1>Edit Category</h1>
            <a href="{{ route('admin.categories.index') }}" class="view-btn btn-back">Back to List</a>
        </header>

        <div class="form-container">
            <form onsubmit="confirmModal(event, 'Update Category', 'Are you sure to update category {{ $category->name }}?')"
               action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="category-form">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                        placeholder="e.g. Electronics, Fashion..." required class="form-input">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="5" placeholder="Brief description of the category..."
                        class="form-input">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="view-btn btn-save">Update Category</button>
                    <a href="{{ route('admin.categories.index') }}" class="view-btn btn-reset">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
