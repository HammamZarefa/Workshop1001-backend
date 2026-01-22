@extends('layouts.main')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">Payments</h1>

    {{-- ======= Buttons row ======= --}}
    <div class="mb-6">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.payments.index') }}" class="px-3 py-2 bg-gray-200 rounded">All</a>
            <a href="{{ route('admin.payments.failed') }}" class="px-3 py-2 bg-red-200 rounded">Failed</a>
            <a href="{{ route('admin.payments.statistics') }}" class="px-3 py-2 bg-blue-200 rounded">Statistics</a>
            <a href="{{ route('admin.payments.reconciliation') }}" class="px-3 py-2 bg-green-200 rounded">Reconciliation</a>
        </div>
    </div>

    {{-- ======= Filters in 2 rows ======= --}}
    <div class="mb-6 bg-white p-4 rounded shadow">
        <form method="GET">

            {{-- Row 1 --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <input type="text"
                       name="method"
                       placeholder="Method"
                       value="{{ request('method') }}"
                       class="border rounded p-2 w-full">

                <select name="status" class="border rounded p-2 w-full">
                    <option value="">All statuses</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                </select>
            </div>

            {{-- Row 2 --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <input type="date"
                       name="from"
                       value="{{ request('from') }}"
                       class="border rounded p-2 w-full">

                <input type="date"
                       name="to"
                       value="{{ request('to') }}"
                       class="border rounded p-2 w-full">

                <button class="bg-blue-600 text-white px-4 py-2 rounded w-full md:w-auto">
                    Filter
                </button>
            </div>

        </form>
    </div>

    <div class="bg-white rounded shadow p-4">
        <table class="w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">#</th>
                    <th class="p-2 text-left">Order</th>
                    <th class="p-2 text-left">Customer</th>
                    <th class="p-2 text-left">Method</th>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Amount</th>
                    <th class="p-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $p)
                <tr class="border-b">
                    <td class="p-2">{{ $p->id }}</td>
                    <td class="p-2">#{{ $p->order_id }}</td>
                    <td class="p-2">{{ $p->order->user->name ?? '—' }}</td>
                    <td class="p-2">{{ $p->method }} / {{ $p->provider }}</td>
                    <td class="p-2">{{ ucfirst($p->status) }}</td>
                    <td class="p-2">{{ $p->amount }} {{ $p->currency }}</td>
                    <td class="p-2 text-right">
                        <a href="{{ route('admin.payments.show', $p->id) }}" class="text-blue-600 mr-2">View</a>
                        @if($p->status === 'paid')
                            <form action="{{ route('admin.payments.refund', $p->id) }}"
                                  method="POST"class="inline">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Process manual refund?')"
                                        class="text-red-600">
                                    Refund
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $payments->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
