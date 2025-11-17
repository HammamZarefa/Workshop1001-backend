<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends ApiController
{
    public function index()
    {
        $orders = Order::with('items.product', 'user')->latest()->paginate(20);

        return $this->ok('Orders fetched', [
            'orders' => OrderResource::collection($orders),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ]
        ]);
    }


    public function show($id)
    {
        $order = Order::with('items.product', 'user')->find($id);

        if (!$order) {
            return $this->error('Order not found', [], 404);
        }

        return $this->ok('Order fetched', new OrderResource($order));
    }
    public function store(OrderStoreRequest $request)
    {
        $data = $request->validated();

        $order = DB::transaction(function () use ($data) {

            $order = Order::create([
                'user_id' => $data['user_id'],
                'shipping_address' => $data['shipping_address'],
                'coupon_value' => $data['coupon_value'] ?? 0,
                'tax_amount' => $data['tax_amount'] ?? 0,
                'discount_percentage' => $data['discount_percentage'] ?? 0,
                'currency' => $data['currency'],
                'total' => 0
            ]);

            $order->items()->createMany($data['items']);

            $order->update([
                'total' => $order->calculatedTotal()
            ]);

            return $order->load('items.product');
        });

        return $this->success('Order created successfully', new OrderResource($order), 201);
    }


    public function update(OrderUpdateRequest $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->error('Order not found', [], 404);
        }

        $order->update($request->validated());

        $order->update(['total' => $order->calculatedTotal()]);

        return $this->ok('Order updated successfully', new OrderResource($order->load('items.product')));
    }


    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->error('Order not found', [], 404);
        }

        $order->delete();

        return $this->ok('Order deleted successfully');
    }
}
