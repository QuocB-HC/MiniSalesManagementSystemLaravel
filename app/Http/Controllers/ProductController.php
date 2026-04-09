<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get products that are not disabled, sorted by latest
        $products = Product::with('category')
            ->where('is_disabled', false)
            ->latest()
            ->paginate(12); // Paginate with 12 products per page

        return view('pages.home', compact('products'));
    }
}
