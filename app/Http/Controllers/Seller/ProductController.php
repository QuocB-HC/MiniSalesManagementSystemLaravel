<?php

namespace App\Http\Controllers\Seller;

use App\Models\Product;
use App\Models\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, $shopId = null)
    {
        $userId = auth()->id();
        
        $shops = Shop::where('user_id', $userId)->get();

        if ($shops->isEmpty()) {
            return redirect()->route('seller.shop.create')->with('error', 'You must create a shop first.');
        }

        $currentShop = $shopId 
            ? $shops->firstWhere('id', $shopId) 
            : $shops->first();

        if (!$currentShop) {
            return abort(403, 'You are not allowed to access this shop.');
        }

        $products = Product::with('category')
            ->where('shop_id', $currentShop->id)
            ->latest()
            ->paginate(10);

        return view('seller.products.index', compact('shops', 'currentShop', 'products'));
    }
}
