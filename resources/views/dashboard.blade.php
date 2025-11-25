@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold">Order Statistics</h2>
        <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">Back to Orders</a>
    </div>

    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 bg-white p-4 rounded shadow mb-6">
        <input type="date" name="from_date" value="{{ request('from_date') }}" class="border rounded p-2" />
        <input type="date" name="to_date" value="{{ request('to_date') }}" class="border rounded p-2" />
        <div class="md:col-span-2 flex items-center">
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Filter</button>
            <a href="{{ route('admin.home') }}" class="ml-2 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Reset</a>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total Sales Card -->
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Total Sales</div>
            <div class="text-2xl font-bold">{{ number_format($totalSales, 2) }}</div>
        </div>

        <!-- Total Orders Card -->
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Total Orders</div>
            <div class="text-2xl font-bold">{{ $totalOrders }}</div>
        </div>

        <!-- Orders by Status Card -->
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500 mb-2">Orders by Status</div>
            <ul class="space-y-1">
                @foreach($byStatus as $status => $count)
                    <li class="flex justify-between">
                        <span class="capitalize">{{ $status }}</span>
                        <span class="font-semibold">{{ $count }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

@endsection
