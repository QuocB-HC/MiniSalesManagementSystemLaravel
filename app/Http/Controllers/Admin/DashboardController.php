<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Get key statistics for the dashboard
        $totalUsers = User::where('role', 'customer')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->where('role', 'customer')
            ->count();

        // Sales statistics for the last 7 days (Data for the chart)
        $revenueLast7Days = Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Order Status Rate (Data for pie chart)
        $orderStatusStats = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // 2. Get the list of pending orders for the table below
        $pendingOrders = Order::whereIn('status', ['pending', 'processing'])->oldest()->paginate(5);

        // 3. Return the view with all the data
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'newUsersThisMonth',
            'revenueLast7Days',
            'orderStatusStats',
        ));
    }
}
