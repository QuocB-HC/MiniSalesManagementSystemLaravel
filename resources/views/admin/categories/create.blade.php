<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
    <link rel="stylesheet" href="{{ asset('css/admin/categories/create.css') }}">
</head>

<body>
    <div class="main-container">
        <main class="main-content">
            <header>
                <h1>Add New Category</h1>
                <a href="{{ route('admin.categories.index') }}" class="view-btn btn-back">Back to List</a>
            </header>

            <div class="form-container">
                <form action="{{ route('admin.categories.store') }}" method="POST" class="category-form">
                    @csrf

                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            placeholder="e.g. Electronics, Fashion..." required class="form-input">
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="5" placeholder="Brief description of the category..."
                            class="form-input">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="view-btn btn-save">Create Category</button>
                        <button type="reset" class="view-btn btn-reset">Reset Form</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
