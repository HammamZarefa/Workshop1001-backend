<?php

namespace App\Http\Controllers\Admin;

use App\Filters\OrderFilter;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController
{
    public function index(Request $request)
    {
        $base = (new OrderFilter($request))->apply(Order::query());

        $totalSales  = (clone $base)->sum('total');
        $totalOrders = (clone $base)->count();
        $byStatus    = (clone $base)
            ->select('status', DB::raw('COUNT(*) AS count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('dashboard', compact('totalSales', 'totalOrders', 'byStatus'));
    }
}
