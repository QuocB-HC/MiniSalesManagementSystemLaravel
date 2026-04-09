<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if (!$request->filled('search')) {
            // Return empty result if search is empty to avoid loading all orders
            $orders = Order::whereRaw('1 = 0')->paginate(10);
        } else {
            // Search by ID, customer email, or receiver phone number
            $orders = Order::with('user')
                ->where(function($query) use ($search) {
                    $query->where('id', $search)
                          ->orWhere('receiver_phone', 'like', "%{$search}%")
                          ->orWhereHas('user', function($q) use ($search) {
                              $q->where('email', 'like', "%{$search}%");
                          });
                })
                ->oldest()
                ->paginate(10)
                ->withQueryString();
        }

        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Update order status #' . $order->id . ' successfully!');
    }
}