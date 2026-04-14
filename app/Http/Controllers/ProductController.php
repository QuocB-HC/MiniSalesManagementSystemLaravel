<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

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
            ->paginate(15); // Paginate with 15 products per page
        $categories = Category::all();

        return view('pages.product-list', compact('products', 'categories'));
    }

    /**
     * Display a listing of the search result.
     */
    public function search(Request $request)
    {
        // Initialize the query but do not execute
        $query = Product::query()->where('is_disabled', 0);

        // Search by name or sku (Use 'search' key)
        $query->when($request->search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });

        $products = $query->paginate(15);
        $categories = Category::all();

        return view('pages.product-list', compact('products', 'categories'));
    }

    public function searchAjax(Request $request)
    {
        $query = $request->get('query');
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('sku', 'LIKE', "%{$query}%")
            ->take(5)
            ->get();

        return response()->json($products);
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
