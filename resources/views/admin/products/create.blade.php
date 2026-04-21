@extends('layouts.admin', ['hideSideBar' => true])

@section('title', 'Add New Product')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/products/create.css') }}">
@endpush

@section('content')
    <div class="main-container create-container">
            <header class="form-header">
                <h1>Add New Product</h1>
                <a href="{{ route('admin.products.index') }}" id="btn-back" class="view-btn btn-back">Back to List</a>
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
                        </div>

                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required
                                class="form-input">
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
                        </div>

                        <div class="form-group">
                            <label for="price">Price (VND)</label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}"
                                step="0.01" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label for="stock_quantity">Stock Quantity</label>
                            <input type="number" name="stock_quantity" id="stock_quantity"
                                value="{{ old('stock_quantity', 0) }}" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" required class="form-input">
                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>
                                    Approved</option>
                                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>
                                    Rejected</option>
                                <option value="hidden" {{ old('status') == 'hidden' ? 'selected' : '' }}>
                                    Hidden</option>
                                <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>
                                    Out of Stock</option>
                            </select>
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
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnBack = document.getElementById('btn-back');

        if (btnBack) {
            btnBack.addEventListener('click', function(e) {
                const fieldIds = [
                    'name', 'sku', 'category_id', 'price', 
                    'stock_quantity', 'description'
                ];

                const isFormDirty = fieldIds.some(id => {
                    const input = document.getElementById(id);
                    if (!input) return false;

                    if (id === 'stock_quantity') {
                        return input.value !== "0" && input.value.trim() !== "";
                    }
                    
                    return input.value.trim() !== "";
                });

                const statusInput = document.getElementById('status');
                const isStatusChanged = statusInput && statusInput.value !== 'pending';

                if (isFormDirty || isStatusChanged) {
                    e.preventDefault();
                    
                    confirmModal(e, 'Unsaved Changes', 'You have unsaved data in the form. Are you sure you want to go back?');
                }
            });
        }
    });
</script>
@endpush
