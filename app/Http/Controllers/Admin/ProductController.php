<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        // Filter by category if category_id is provided in the request
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:pending,approved,rejected,hidden,out_of_stock',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'sku', 'category_id', 'price', 'stock_quantity', 'status', 'description']);
        $data['slug'] = Str::slug($request->name).'-'.time();

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

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:pending,approved,rejected,hidden,out_of_stock',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'sku', 'category_id', 'price', 'stock_quantity', 'status', 'description']);

        // Update slug only if the name has changed to avoid breaking existing links
        if ($product->name !== $request->name) {
            $data['slug'] = Str::slug($request->name).'-'.time();
        }

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
}
