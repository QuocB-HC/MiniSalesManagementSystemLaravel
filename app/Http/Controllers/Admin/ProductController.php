<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request, $shop_id)
    {
        $query = Product::with('category')
            ->where('shop_id', $shop_id);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        $categories = Category::all();
        $shop = Shop::findOrFail($shop_id);

        return view('admin.products.index', compact('products', 'categories', 'shop'));
    }
    
    /*
        public function create()
        {
            $categories = Category::all();

            return view('admin.products.create', compact('categories'));
        }

        public function store(StoreProductRequest $request)
        {
            $data = $request->validated();

            $data['shop_id'] = 2;

            if ($request->hasFile('image')) {
                // Store the uploaded image in the 'public/products' directory and save the URL in the database
                $path = $request->file('image')->store('products', 'public');
                $data['image_url'] = Storage::url($path);
            }

            Product::create($data);

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
        }

        public function edit(Product $product)
        {
            $categories = Category::all();

            return view('admin.products.edit', compact('product', 'categories'));
        }

        public function update(UpdateProductRequest $request, Product $product)
        {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                // Delete the old image file if it exists
                if ($product->image_url) {
                    $oldPath = str_replace('/storage/', '', $product->image_url);
                    Storage::disk('public')->delete($oldPath);
                }

                // Lưu ảnh mới
                $path = $request->file('image')->store('products', 'public');
                $data['image_url'] = Storage::url($path);
            }

            $product->update($data);

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
        }

        public function destroy(Product $product)
        {
            // Delete the physical image file before deleting the record in the DB
            if ($product->image_url) {
                $path = str_replace('/storage/', '', $product->image_url);
                Storage::disk('public')->delete($path);
            }

            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
        }
    */
}
