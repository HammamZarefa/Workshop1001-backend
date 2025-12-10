@extends('layouts.main')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Payment #{{ $payment->id }}</h2>

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold">Payment Info</h3>
            <p>Method: {{ $payment->method }} ({{ $payment->provider }})</p>
            <p>Status: {{ $payment->status }}</p>
            <p>Amount: {{ $payment->amount }} {{ $payment->currency }}</p>
            <p>Reference: {{ $payment->reference }}</p>
            <p>Paid at: {{ $payment->paid_at }}</p>
            <p>Meta: <pre class="text-sm">{{ json_encode($payment->meta, JSON_PRETTY_PRINT) }}</pre></p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold">Order / Customer</h3>
            <p>Order: #{{ $payment->order->id ?? '—' }}</p>
            <p>Customer: {{ $payment->order->user->name ?? '—' }}</p>
            <p>Email: {{ $payment->order->user->email ?? '—' }}</p>
        </div>
    </div>

    <div class="mt-4">
        @if($payment->status === 'paid')
            <form action="{{ route('admin.payments.refund', $payment->id) }}" method="POST">
                @csrf
                <label>Reason (optional)</label>
                <input type="text" name="reason" class="border rounded p-2 w-full mt-2">
                <button class="mt-3 bg-red-600 text-white px-4 py-2 rounded">Process Refund</button>
            </form>
        @endif
    </div>

</div>
@endsection
