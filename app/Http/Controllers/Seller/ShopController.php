<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\StoreShopRequest;
use App\Http\Requests\Shop\UpdateShopRequest;
use App\Models\Shop;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($shopId = null)
    {
        $userId = auth()->id();
        $shops = Shop::where('user_id', $userId)->get();

        $shop = $shopId ? Shop::find($shopId) : $shops->first();

        return view('seller.shops.index', compact('shops', 'shop'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('seller.shops.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        Shop::create($data);

        return redirect()->route('home')->with('success', 'Shop information saved successfully.');
    }
}
