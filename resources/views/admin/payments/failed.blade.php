@extends('layouts.main')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Failed Payments</h1>

    <a href="{{ route('admin.payments.index') }}" class="text-blue-500 underline">← Back to Payments</a>

    <div class="bg-white shadow rounded p-4 mt-4">
        <table class="min-w-full text-left text-sm">
            <thead>
                <tr class="border-b">
                    <th class="py-2 px-3">ID</th>
                    <th class="py-2 px-3">Order</th>
                    <th class="py-2 px-3">User</th>
                    <th class="py-2 px-3">Method</th>
                    <th class="py-2 px-3">Amount</th>
                    <th class="py-2 px-3">Failed At</th>
                    <th class="py-2 px-3">Reason</th>
                </tr>
            </thead>

            <tbody>
                @foreach($payments as $payment)
                    <tr class="border-b">
                        <td class="py-2 px-3">{{ $payment->id }}</td>
                        <td class="py-2 px-3">
                            <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="text-blue-500 underline">
                                #{{ $payment->order_id }}
                            </a>
                        </td>
                        <td class="py-2 px-3">{{ optional($payment->user)->name ?? 'N/A' }}</td>
                        <td class="py-2 px-3">{{ $payment->method }}</td>
                        <td class="py-2 px-3">${{ number_format($payment->amount, 2) }}</td>
                        <td class="py-2 px-3">{{ $payment->failed_at }}</td>
                        <td class="py-2 px-3 text-red-600">{{ $payment->failure_reason ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
