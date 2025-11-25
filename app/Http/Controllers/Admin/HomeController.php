<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController
{
    public function index(Request $request)
    {
        $base = Order::query();

        if ($request->filled('from_date')) {
            $base->whereDate('created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $base->whereDate('created_at', '<=', $request->input('to_date'));
        }

        $totalSales  = (clone $base)->sum('total');
        $totalOrders = (clone $base)->count();
        $byStatus    = (clone $base)
            ->select('status', DB::raw('COUNT(*) AS count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('dashboard', compact('totalSales', 'totalOrders', 'byStatus'));
    }
}
