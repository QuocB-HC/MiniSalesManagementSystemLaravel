<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Get key statistics for the dashboard
        $totalUsers = User::where('role', 'customer')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        // 2. Get the list of pending orders for the table below
        $pendingOrders = Order::where('status', 'pending')->oldest()->get();

        // 3. Return the view with all the data
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'pendingOrders'
        ));
    }
}
