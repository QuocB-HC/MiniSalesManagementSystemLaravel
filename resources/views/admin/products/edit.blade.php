<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product: {{ $product->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/admin/products/create.css') }}">
</head>

<body>
    <div class="main-container">
        <x-side-bar />

        <main class="main-content">
            <header>
                <h1>Edit Product</h1>
                <a href="{{ route('admin.products.index') }}" class="view-btn btn-back">Back to List</a>
            </header>

            <div class="form-container">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data" class="product-form">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                                required class="form-input">
                            @error('name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
                                required class="form-input">
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
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}"
                                step="0.01" required class="form-input">
                            @error('price')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="stock_quantity">Stock Quantity</label>
                            <input type="number" name="stock_quantity" id="stock_quantity"
                                value="{{ old('stock_quantity', $product->stock_quantity) }}" required
                                class="form-input">
                            @error('stock_quantity')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" required class="form-input">
                                <option value="available"
                                    {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>
                                    Available</option>
                                <option value="out_of_stock"
                                    {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>
                                    Out of Stock</option>
                                <option value="discontinued"
                                    {{ old('status', $product->status) == 'discontinued' ? 'selected' : '' }}>
                                    Discontinued</option>
                            </select>
                            @error('status')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="5" class="form-input">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="image">Product Image</label>
                        @if ($product->image_url)
                            <div style="margin-bottom: 10px;">
                                <img src="{{ $product->image_url }}" alt="Current Image"
                                    style="max-width: 150px; border-radius: 4px; border: 1px solid #ddd;">
                                <p class="help-text">Current Image</p>
                            </div>
                        @endif
                        <input type="file" name="image" id="image" class="form-input" accept="image/*">
                        <p class="help-text">Leave blank to keep the current image. Max: 2MB.</p>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="view-btn btn-save">Update Product</button>
                        <a href="{{ route('admin.products.index') }}" class="view-btn btn-reset">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>