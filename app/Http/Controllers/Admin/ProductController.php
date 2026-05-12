<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductStatus;
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

        $products = (clone $query)
            ->whereNot('status', ProductStatus::PENDING)
            ->latest()->paginate(10)->withQueryString();

        $pendingProducts = (clone $query)
            ->where('status', ProductStatus::PENDING)
            ->latest()->paginate(10)->withQueryString();

        $categories = Category::all();
        $shop = Shop::findOrFail($shop_id);

        return view('admin.products.index', compact('products', 'pendingProducts', 'categories', 'shop'));
    }

    public function updateStatusToApproved(Product $product)
    {
        if ($product->status === ProductStatus::HIDDEN || $product->status === ProductStatus::PENDING || $product->status === ProductStatus::OUT_OF_STOCK) {
            $product->status = ProductStatus::APPROVED;
            $product->save();

            return redirect()->back()->with('success', 'Product status updated to approved successfully!');
        }

        return redirect()->back()->with('error', 'Invalid product status.');
    }

    public function updateStatusToRejected(Product $product)
    {
        if ($product->status === ProductStatus::HIDDEN || $product->status === ProductStatus::PENDING || $product->status === ProductStatus::OUT_OF_STOCK) {
            $product->status = ProductStatus::REJECTED;
            $product->save();

            return redirect()->back()->with('success', 'Product status updated to rejected successfully!');
        }

        return redirect()->back()->with('error', 'Invalid product status.');
    }

    public function updateStatusToHidden(Product $product)
    {
        if ($product->status === ProductStatus::APPROVED || $product->status === ProductStatus::OUT_OF_STOCK) {
            $product->status = ProductStatus::HIDDEN;
            $product->save();

            return redirect()->back()->with('success', 'Product status updated to hidden successfully!');
        }

        return redirect()->back()->with('error', 'Invalid product status.');
    }
}
