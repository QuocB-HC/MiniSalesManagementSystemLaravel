<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        // Get all categories with pagination
        $categories = Category::latest()->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']).'-'.time();

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        // Update slug only if the name has changed
        if ($category->name !== $request->name) {
            $data['slug'] = Str::slug($request->name).'-'.time();
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Check if the category has associated products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete this category because it still has products associated with it!');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
