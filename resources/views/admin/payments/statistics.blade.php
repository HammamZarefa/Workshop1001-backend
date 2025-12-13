@extends('layouts.main')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Payment Statistics</h1>

    <a href="{{ route('admin.payments.index') }}" class="text-blue-500 underline">← Back to Payments</a>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">

        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Payments By Method</h3>

            <ul class="mt-2">
                @foreach($stats['by_method'] as $method => $total)
                    <li class="py-1 border-b">
                        <strong>{{ $method }}:</strong> {{ $total }} payments
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Total Volume</h3>
            <p class="text-xl font-bold mt-2">
                ${{ number_format($stats['total_amount'] ?? 0, 2) }}
            </p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">Refunded Count</h3>
            <p class="text-xl font-bold mt-2">
                {{ $stats['refunded_count'] ?? 0 }}
            </p>
        </div>

    </div>
</div>
@endsection
