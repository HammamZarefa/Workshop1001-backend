@extends('layouts.main')

@section('title', 'Orders')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Orders</h1>

            <a href="{{ route('admin.orders.export', request()->query()) }}"
               class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition">
                Export CSV
            </a>
        </div>

        <!-- Filters -->
        <form method="GET"
              class="grid grid-cols-1 md:grid-cols-6 gap-3 bg-white p-5 rounded-lg border border-gray-200 shadow-sm">

            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="Order ID or Customer Email"
                   class="input">

            <select name="status" class="input">
                <option value="">All Statuses</option>
                @foreach(['pending','accepted','rejected'] as $st)
                    <option value="{{ $st }}" @selected(request('status')==$st)>
                        {{ ucfirst($st) }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="from_date" value="{{ request('from_date') }}" class="input">
            <input type="date" name="to_date"   value="{{ request('to_date') }}"   class="input">

            <select name="sort_by" class="input">
                @foreach(['created_at'=>'Created','total'=>'Total','status'=>'Status','id'=>'ID'] as $key=>$label)
                    <option value="{{ $key }}" @selected(request('sort_by','created_at')==$key)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <select name="sort_dir" class="input">
                @foreach(['desc'=>'Desc','asc'=>'Asc'] as $dir=>$label)
                    <option value="{{ $dir }}" @selected(request('sort_dir','desc')==$dir)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <div class="md:col-span-6 flex items-center gap-2 pt-1">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Apply Filters
                </button>

                <a href="{{ route('admin.orders.index') }}"
                   class="px-4 py-2 bg-gray-100 border rounded-md hover:bg-gray-200 transition">
                    Reset
                </a>
            </div>
        </form>

        <!-- Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="th">ID</th>
                    <th class="th">Customer</th>
                    <th class="th">Status</th>
                    <th class="th">Currency</th>
                    <th class="th">Total</th>
                    <th class="th">Created</th>
                    <th class="th">Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse($orders as $order)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="td">#{{ $order->id }}</td>
                        <td class="td">{{ $order->user->email ?? '-' }}</td>

                        <td class="td">
                            @php
                                $color = match ($order->status) {
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'accepted' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            @endphp

                            <span class="px-2 py-1 text-xs rounded {{ $color }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        </td>

                        <td class="td">{{ $order->currency }}</td>
                        <td class="td">{{ number_format($order->total, 2) }}</td>
                        <td class="td">{{ $order->created_at->format('Y-m-d H:i') }}</td>

                        <td class="td">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="text-blue-600 hover:underline">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div>{{ $orders->links() }}</div>
    </div>

    <style>
        .input {
            @apply border border-gray-300 rounded-md p-2 w-full text-sm focus:ring focus:ring-blue-200 focus:border-blue-400;
        }
        .th {
            @apply text-left px-3 py-2 font-medium text-gray-600;
        }
        .td {
            @apply px-3 py-2 text-gray-700;
        }
    </style>
@endsection
