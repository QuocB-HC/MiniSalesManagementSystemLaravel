<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category: {{ $category->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/admin/categories/create.css') }}">
</head>

<body>
    <div class="main-container">
        <main class="main-content">
            <header>
                <h1>Edit Category</h1>
                <a href="{{ route('admin.categories.index') }}" class="view-btn btn-back">Back to List</a>
            </header>

            <div class="form-container">
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                    class="category-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                            placeholder="e.g. Electronics, Fashion..." required class="form-input">
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="5" placeholder="Brief description of the category..."
                            class="form-input">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="view-btn btn-save">Update Category</button>
                        <a href="{{ route('admin.categories.index') }}" class="view-btn btn-reset">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
