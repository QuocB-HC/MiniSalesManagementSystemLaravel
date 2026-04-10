<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
            ->paginate(15); // Paginate with 12 products per page
        $categories = Category::all();

        return view('pages.product-list', compact('products', 'categories'));
    }

    public function homePage()
    {
        $products = Product::latest()
            ->where('is_disabled', false)
            ->With('category')
            ->take(4)
            ->get();
        $categories = Category::all();

        return view('pages.home', compact('products', 'categories'));
    }

    public function showByCategory($category)
    {
        $products = Product::with('category')
            ->where('category_id', $category)
            ->where('is_disabled', false)
            ->latest()
            ->paginate(15);
        $categories = Category::all();

        return view('pages.product-list', compact('products', 'categories'));
    }

    public function detail($id)
    {
        $product = Product::findOrFail($id);

        return view('pages.product-detail', compact('product'));
    }
}
