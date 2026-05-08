<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Product\StoreProductRequest;

class ProductController extends Controller
{
    public function index(Request $request, $shopId = null)
    {
        $userId = auth()->id();

        $shops = Shop::where('user_id', $userId)->get();

        if ($shops->isEmpty()) {
            return redirect()->route('shop.create')->with('error', 'You must create a shop first.');
        }

        $currentShop = $shopId
            ? $shops->firstWhere('id', $shopId)
            : $shops->first();

        if (! $currentShop) {
            return abort(403, 'You are not allowed to access this shop.');
        }

        $products = Product::with('category')
            ->where('shop_id', $currentShop->id)
            ->latest()
            ->paginate(10);

        return view('seller.products.index', compact('shops', 'currentShop', 'products'));
    }

    public function create($shopId)
    {
        $currentShopId = $shopId ? $shopId : null;
        $categories = Category::all();

        if ($currentShopId == null) {
            return redirect()->route('seller.shop.index')->with('error', 'You must select a shop first.');
        }

        return view('seller.products.create', compact('currentShopId', 'categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        Product::create($data);

        return redirect()->route('seller.products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        if ($product->shop->user_id !== auth()->id()) {
            return abort(403, 'You are not allowed to edit this product.');
        }

        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->shop->user_id !== auth()->id()) {
            return abort(403, 'You are not allowed to update this product.');
        }

        $data = $request->validated();

        $product->update($data);

        return redirect()->route('seller.products.index')->with('success', 'Product updated successfully.');
    }
}
