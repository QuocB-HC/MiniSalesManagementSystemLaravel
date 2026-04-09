<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="{{ asset('css/admin/products/create.css') }}">
</head>

<body>
    <div class="main-container">
        <x-side-bar />

        <main class="main-content">
            <header>
                <h1>Add New Product</h1>
                <a href="{{ route('admin.products.index') }}" class="view-btn btn-back">Back to List</a>
            </header>

            <div class="form-container">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                    class="product-form">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="form-input">
                            @error('name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required
                                class="form-input">
                            @error('sku')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select name="category_id" id="category_id" required class="form-input">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="price">Price (VNĐ)</label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}"
                                step="0.01" required class="form-input">
                            @error('price')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="stock_quantity">Stock Quantity</label>
                            <input type="number" name="stock_quantity" id="stock_quantity"
                                value="{{ old('stock_quantity', 0) }}" required class="form-input">
                            @error('stock_quantity')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" required class="form-input">
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>
                                    Available</option>
                                <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>
                                    Out of Stock</option>
                                <option value="discontinued" {{ old('status') == 'discontinued' ? 'selected' : '' }}>
                                    Discontinued</option>
                            </select>
                            @error('status')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="5" class="form-input">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="image">Product Image</label>
                        <input type="file" name="image" id="image" class="form-input" accept="image/*">
                        <p class="help-text">Recommended size: 800x800px. Max: 2MB.</p>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="view-btn btn-save">Create Product</button>
                        <button type="reset" class="view-btn btn-reset">Reset Form</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
