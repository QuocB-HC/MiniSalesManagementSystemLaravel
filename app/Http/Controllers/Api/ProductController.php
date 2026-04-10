<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        if ($products->count() === 0) {
            return response()->json(['message' => 'No products found'], 404);
        }

        // Return JSON with status code 200 (Success)
        return response()->json([
            'status' => 'success',
            'count' => $products->count(),
            'data' => $products,
        ], 200);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $product], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'nullable|string',
        ]);

        $product = Product::create($validated);

        return response()->json(['status' => 'success', 'data' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'sku' => 'sometimes|string|unique:products,sku,'.$id,
            'is_disabled' => 'sometimes|boolean',
        ]);

        $product->update($validated);

        return response()->json(['status' => 'success', 'data' => $product], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (! $product) {
            return response()->json(['status' => 'fail', 'message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['status' => 'success', 'message' => 'Product deleted successfully'], 200);
    }
}
