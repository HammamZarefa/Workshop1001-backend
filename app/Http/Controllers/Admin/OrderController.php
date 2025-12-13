<?php

namespace App\Http\Controllers\Admin;

use App\Filters\OrderFilter;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\OrderStatusLog;
use App\Notifications\OrderStatusChanged;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class, 'order');
    }

    protected function filteredQuery(Request $request)
    {
        return (new OrderFilter($request))
            ->apply(Order::query()->with(['user', 'payment']));
    }

    public function index(Request $request)
    {

        $query = $this->filteredQuery($request);

        $allowed = ['id', 'created_at', 'total', 'status'];
        $sortBy  = $request->get('sort_by', 'created_at');

        if (!in_array($sortBy, $allowed)) {
            $sortBy = 'created_at';
        }

        $sortDir = $request->sort_dir === 'asc' ? 'asc' : 'desc';

        $orders = $query->orderBy($sortBy, $sortDir)
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders'  => $orders,
            'filters' => $request->only(['status','from_date','to_date','customer_id','q','sort_by','sort_dir']),
        ]);
    }

    public function show(Order $order)
    {

        $order->load(['items.product', 'user', 'payment']);

        return view('admin.orders.show', compact('order'));
    }

    public function export(Request $request): StreamedResponse
    {
        // custom ability
        $this->authorize('export', Order::class);

        $query = $this->filteredQuery($request)->with('user');
        $filename = 'orders_export_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');

            fputcsv($out, [
                'Order ID','Customer Email','Status','Currency','Total','Created At'
            ]);

            $query->orderBy('id')
                ->chunk(500, function ($orders) use ($out) {
                    foreach ($orders as $order) {
                        fputcsv($out, [
                            $order->id,
                            $order->user?->email,
                            $order->status,
                            $order->currency,
                            $order->total,
                            $order->created_at,
                        ]);
                    }
                });

            fclose($out);
        }, $filename);
    }

    public function addItem(Request $request, Order $order)
    {
        // custom ability
        $this->authorize('update', $order);

        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|numeric|min:1',
            'note'       => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($order, $data) {
            $product = Product::findOrFail($data['product_id']);

            $existing = $order->items()->where('product_id', $product->id)->first();

            if ($existing) {
                $existing->update([
                    'price'    => $product->price,
                    'quantity' => $existing->quantity + $data['quantity'],
                    'note'     => $data['note'] ?? $existing->note,
                ]);
            } else {
                $order->items()->create([
                    'product_id' => $product->id,
                    'price'      => $product->price,
                    'quantity'   => $data['quantity'],
                    'note'       => $data['note'],
                ]);
            }

            $total = $order->items()
                ->selectRaw('SUM(price * quantity) AS total')
                ->value('total');

            $order->update(['total' => $total]);
        });

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Item added successfully.');
    }

    public function productSearch(Request $request)
    {
        // search for UI only — optional protection
        $this->authorize('viewAny', Order::class);

        $q = (string) $request->query('q', '');
        $results = Product::query()
            ->when($q !== '', fn($qq) => $qq->where('title', 'like', "%$q%"))
            ->orderBy('title')
            ->limit(10)
            ->get(['id','title','price']);

        return response()->json($results);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $request->validate([
            'status' => 'required|string',
        ]);

        $newStatus = $request->status;
        $oldStatus = $order->status;

        $allowed = [
            'pending'    => ['processing'],
            'processing' => ['shipped'],
            'shipped'    => ['delivered'],
        ];

        if (!isset($allowed[$oldStatus]) || !in_array($newStatus, $allowed[$oldStatus])) {
            return back()->withErrors(['status' => 'Invalid status transition.']);
        }

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {
            $order->update(['status' => $newStatus]);

            OrderStatusLog::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'admin_id' => auth()->id(),
            ]);

            if ($order->user) {
                $order->user->notify(new OrderStatusChanged(
                    $order,
                    $oldStatus,
                    $newStatus
                ));
            }
        });

        return back()->with('success', 'Order status updated.');
    }

    public function cancel(Request $request, Order $order)
    {
        $this->authorize('cancel', $order);

        if (in_array($order->status, ['cancelled', 'delivered'])) {
            return back()->withErrors(['error' => 'Cannot cancel this order.']);
        }

        $reason = $request->reason ?? 'No reason provided';

        DB::transaction(function () use ($order, $reason) {

            $oldStatus = $order->status;

            $order->update(['status' => 'cancelled']);

            $payment = $order->payment;

            if ($payment && $payment->status === 'paid') {
                if (method_exists($payment, 'markAsRefunded')) {
                    $payment->markAsRefunded($reason);
                } else {
                    $payment->update([
                        'status' => 'refunded',
                        'refund_reason' => $reason,
                        'refunded_at' => now(),
                    ]);
                }
            }

            OrderStatusLog::create([
                'order_id'   => $order->id,
                'old_status' => $oldStatus,
                'new_status' => 'cancelled',
                'admin_id'   => auth()->id(),
            ]);

            if ($order->user) {
                $order->user->notify(new OrderStatusChanged(
                    $order,
                    $oldStatus,
                    'cancelled'
                ));
            }
        });

        return back()->with('success', 'Order cancelled successfully.');
    }
}
