<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(10);

        return view('pages.order-history', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);

        return view('pages.order-detail', compact('order'));
    }
}
