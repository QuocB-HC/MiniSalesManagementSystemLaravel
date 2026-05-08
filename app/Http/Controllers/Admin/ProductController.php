<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

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

    public function changeStatusToApproved(Product $product)
    {
        if ($product->status === ProductStatus::HIDDEN || $product->status === ProductStatus::PENDING || $product->status === ProductStatus::OUT_OF_STOCK) {
            $product->status = ProductStatus::APPROVED;
            $product->save();

            return redirect()->back()->with('success', 'Product status changed to approved successfully!');
        }

        return redirect()->back()->with('error', 'Invalid product status.');
    }

    public function changeStatusToHidden(Product $product)
    {
        if ($product->status === ProductStatus::APPROVED || $product->status === ProductStatus::OUT_OF_STOCK) {
            $product->status = ProductStatus::HIDDEN;
            $product->save();

            return redirect()->back()->with('success', 'Product status changed to hidden successfully!');
        }

        return redirect()->back()->with('error', 'Invalid product status.');
    }
}
