@extends('layouts.admin', ['hideSideBar' => true])

@section('title', 'Add New Category')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/categories/create.css') }}">
@endpush

@section('content')
    <div class="main-container">
        <header>
            <h1>Add New Category</h1>
            <a id="btn-back" href="{{ route('admin.categories.index') }}" class="view-btn btn-back">Back to List</a>
        </header>

        <div class="form-container">
            <form onsubmit="confirmModal(event, 'Create Category', 'Are you sure to create category?')"
                action="{{ route('admin.categories.store') }}" method="POST" class="category-form">
                @csrf

                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        placeholder="e.g. Electronics, Fashion..." required class="form-input">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="5" placeholder="Brief description of the category..."
                        class="form-input">{{ old('description') }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="view-btn btn-save">Create Category</button>
                    <button type="reset" class="view-btn btn-reset" id="btn-reset">Reset Form</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('btn-back').addEventListener('click', function(e) {
            const nameInput = document.getElementById('name');
            const name = nameInput ? nameInput.value : '';
            const descriptionInput = document.getElementById('description');
            const description = descriptionInput ? descriptionInput.value : '';

            if (name && name.trim() !== '' || description && description.trim() !== '') {
                e.preventDefault();

                confirmModal(e, 'Unsaved Changes', 'You have unsaved data in the form. Are you sure you want to go back?');
            }
        });
    </script>
@endpush
