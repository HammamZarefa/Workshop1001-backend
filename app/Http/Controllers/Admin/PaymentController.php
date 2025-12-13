<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    protected PaymentService $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }

    // List with filters: status, method, date range
    public function index(Request $request)
    {
        $query = Payment::with(['order.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    // Show payment detail
    public function show(Payment $payment)
    {
        $payment->load('order.user');
        return view('admin.payments.show', compact('payment'));
    }

    // Manual refund
    public function refund(Request $request, Payment $payment)
    {
        if (! $request->user() || ! $request->user()->is_admin) {
            return redirect()->route('admin.payments.show', $payment->id)
                ->withErrors(['error' => 'Access denied']);
        }

        $data = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        if ($payment->status !== 'paid') {
            return redirect()->back()->withErrors(['error' => 'Payment is not refundable in its current status.']);
        }

        try {
            $result = $this->service->refund($payment, $data['reason'] ?? null, $request->user());

            Log::info('Admin refund executed', [
                'admin_id' => $request->user()->id,
                'payment_id' => $payment->id,
                'result' => $result,
            ]);

            return redirect()->route('admin.payments.show', $payment->id)
                ->with('success', 'Refund processed successfully');
        } catch (\Throwable $e) {
            Log::error('Refund error', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.payments.show', $payment->id)
                ->withErrors(['error' => 'Refund failed: ' . $e->getMessage()]);
        }
    }

    // failed payments list
    public function failed(Request $request)
    {
        $payments = Payment::where('status', 'failed')
            ->with('order.user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    // statistics
   public function statistics(Request $request)
{
    $byMethod = Payment::select('method')
        ->selectRaw('COUNT(*) as count, SUM(amount) as total')
        ->where('status', 'paid')
        ->groupBy('method')
        ->pluck('count', 'method')
        ->toArray();

    $stats = [
        'total_amount' => Payment::where('status', 'paid')->sum('amount'),
        'refunded_count' => Payment::where('status', 'refunded')->count(),
        'refunded_amount' => Payment::where('status', 'refunded')->sum('amount'),
        'by_method' => $byMethod,
    ];

    return view('admin.payments.statistics', compact('stats'));
}

    public function reconciliation(Request $request)
    {
        $report = $this->service->reconciliationReport($request->all());

        return view('admin.payments.reconciliation', compact('report'));
    }
}
