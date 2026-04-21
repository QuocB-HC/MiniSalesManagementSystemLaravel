<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();

        if (! $discounts) {
            return response()->json([
                'status' => 'success',
                'message' => 'No discounts found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'count' => $discounts->count(),
            'data' => $discounts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discounts,code',
            'name' => 'required|string',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:1',
            'min_order_value' => 'numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $discount = Discount::create($validated);

        return response()->json(['status' => 'success', 'data' => $discount], 201);
    }

    public function check($code)
    {
        $discount = Discount::where('code', $code)->where('is_active', true)->first();

        if (! $discount) {
            return response()->json(['status' => 'fail', 'message' => 'Mã giảm giá không tồn tại'], 404);
        }

        // Check expire date
        $now = Carbon::now();
        if ($discount->expires_at && $now > $discount->expires_at) {
            return response()->json(['status' => 'success', 'message' => 'The discount has expired'], 400);
        }

        // Check usage limit
        if ($discount->usage_limit && $discount->used_count >= $discount->usage_limit) {
            return response()->json(['status' => 'success', 'message' => 'This discount has reached its usage limit'], 400);
        }

        return response()->json(['status' => 'success', 'data' => $discount]);
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::find($id);
        if (! $discount) {
            return response()->json(['status' => 'fail', 'message' => 'Not found'], 404);
        }

        $discount->update($request->all());

        return response()->json(['status' => 'success', 'data' => $discount]);
    }

    public function destroy($id)
    {
        Discount::destroy($id);

        return response()->json(['status' => 'success', 'message' => 'The discount has been deleted']);
    }
}
