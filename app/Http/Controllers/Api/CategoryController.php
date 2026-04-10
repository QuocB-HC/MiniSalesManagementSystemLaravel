<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_disabled', 0)->get();

        return response()->json(['status' => 'success', 'data' => $categories]);
    }

    public function show($id)
    {
        $category = Category::with('products')->find($id); // Eager loading products

        if (! $category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $category]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_disabled' => 'boolean',
        ]);

        // Auto create slug if not fill it
        $validated['slug'] = Str::slug($request->name);

        $category = Category::create($validated);

        return response()->json(['status' => 'success', 'data' => $category], 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (! $category) {
            return response()->json(['status' => 'fail', 'message' => 'Category not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:categories,slug,'.$id,
            'is_disabled' => 'sometimes|boolean',
        ]);

        if ($request->has('name') && ! $request->has('slug')) {
            $validated['slug'] = Str::slug($request->name);
        }

        $category->update($validated);

        return response()->json(['status' => 'success', 'data' => $category]);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (! $category) {
            return response()->json(['status' => 'fail', 'message' => 'Category not found'], 404);
        }

        if ($category->products()->count() > 0) {
            return response()->json(['status' => 'fail', 'message' => 'Cannot delete category with products'], 400);
        }

        $category->delete();

        return response()->json(['status' => 'success', 'message' => 'Category deleted']);
    }
}
