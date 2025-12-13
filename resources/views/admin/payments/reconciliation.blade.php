@extends('layouts.main')

@section('content')
<div class="container py-4">

    <h3 class="mb-4">Payment Reconciliation Report</h3>

    {{-- Daily Report --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Daily Summary</strong>
        </div>
        <div class="card-body">
            <p><strong>Total Payments Today:</strong> {{ $report['daily']['count'] ?? 0 }}</p>
            <p><strong>Total Amount Today:</strong> ${{ number_format($report['daily']['amount'] ?? 0, 2) }}</p>
        </div>
    </div>

    {{-- Monthly Report --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Monthly Summary</strong>
        </div>
        <div class="card-body">
            <p><strong>Total Payments This Month:</strong> {{ $report['monthly']['count'] ?? 0 }}</p>
            <p><strong>Total Amount This Month:</strong> ${{ number_format($report['monthly']['amount'] ?? 0, 2) }}</p>
        </div>
    </div>

    {{-- Total Report --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Overall Totals</strong>
        </div>
        <div class="card-body">
            <p><strong>Total Payments:</strong> {{ $report['total']['count'] ?? 0 }}</p>
            <p><strong>Total Amount:</strong> ${{ number_format($report['total']['amount'] ?? 0, 2) }}</p>
        </div>
    </div>

    {{-- Discrepancies --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Discrepancies</strong>
        </div>
        <div class="card-body">
            @if(!empty($report['discrepancies']))
                <ul>
                    @foreach($report['discrepancies'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No discrepancies found.</p>
            @endif
        </div>
    </div>

</div>
@endsection
