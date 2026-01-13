<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function refund(Payment $payment, ?string $reason = null, $admin = null): array
    {
        if ($payment->status !== 'paid') {
            throw new \Exception('Payment not refundable');
        }

        $providerResult = [
            'success' => true,
            'provider_reference' => 'REF-' . uniqid(),
        ];

        if (! $providerResult['success']) {
            throw new \Exception('Provider refund failed');
        }

        $payment->markAsRefunded($reason);

        Log::info('Payment refunded', [
            'payment_id' => $payment->id,
            'admin_id' => $admin->id ?? null,
            'reason' => $reason,
            'provider_ref' => $providerResult['provider_reference'],
        ]);

        return [
            'status' => 'refunded',
            'provider_reference' => $providerResult['provider_reference'],
        ];
    }

   public function reconciliationReport(array $filters = []): array
{
    // Daily
    $dailyCount  = Payment::whereDate('created_at', today())
        ->where('status', 'paid')
        ->count();

    $dailyAmount = Payment::whereDate('created_at', today())
        ->where('status', 'paid')
        ->sum('amount');

    // Monthly
    $monthlyCount  = Payment::whereMonth('created_at', now()->month)
        ->where('status', 'paid')
        ->count();

    $monthlyAmount = Payment::whereMonth('created_at', now()->month)
        ->where('status', 'paid')
        ->sum('amount');

    // Total
    $totalPayments = Payment::where('status', 'paid')
        ->count();

    $totalAmount   = Payment::where('status', 'paid')
        ->sum('amount');

    return [
        'daily' => [
            'count'  => $dailyCount,
            'amount' => $dailyAmount,
        ],
        'monthly' => [
            'count'  => $monthlyCount,
            'amount' => $monthlyAmount,
        ],
        'total' => [
            'count'  => $totalPayments,
            'amount' => $totalAmount,
        ],
         'refunded' => [    
        'count' => Payment::where('status', 'refunded')->count(),
        'amount' => Payment::where('status', 'refunded')->sum('amount'),
    ],
        'discrepancies' => [],
    ];
}
}
