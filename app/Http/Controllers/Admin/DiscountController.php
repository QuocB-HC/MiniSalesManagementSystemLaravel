<?php

namespace App\Http\Controllers\Admin;   
 
use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::oldest()->paginate(10); // Get discounts with pagination
 
        return view('admin.discounts.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show the form for creating a new discount
        return view('admin.discounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiscountRequest $request)
    {
        Discount::create($request->validated());
 
        return redirect()->route('admin.discounts.index')
                         ->with('success', 'Discount created successfully!');
 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        // Show the form for editing the discount
        // You can create a view 'admin.discounts.edit' to display this form
        return view('admin.discounts.edit', compact('discount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        $data = $request->validated();
 
        // Process the is_active field, if the checkbox is not submitted, default to false
        $data['is_active'] = $request->has('is_active');
 
        $discount->update($data);
 
        return redirect()->route('admin.discounts.index')
                         ->with('success', 'Discount updated successfully!');
 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        try {
            $discount->delete();
            return redirect()->route('admin.discounts.index')
                             ->with('success', 'Discount deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error occurred while deleting discount: ' . $e->getMessage());
            return redirect()->route('admin.discounts.index')
                             ->with('error', 'Cannot delete this discount. It might be in use by existing orders.');
        }
    }
}
