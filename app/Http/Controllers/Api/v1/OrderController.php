<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends ApiController
{
    public function index(Request $request)
    {
        $perPage = $this->perPage($request);

        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate($perPage);
        return $this->success(
            OrderResource::collection($orders),
            'Orders fetched',
            200,
            $this->paginationMeta($orders)
        );
    }


    public function show($id)
    {
        $order = Order::with('items.product', 'user')->find($id);

        if (!$order) {
            return $this->error('Order not found', [], 404);
        }
        if ($order->user_id !== auth()->id()) {
            return $this->error('Unauthorized', [], 403);
        }

        return $this->ok('Order fetched', new OrderResource($order));
    }

    public function store(OrderStoreRequest $request)
    {
        $data = $request->validated();
        foreach ($data['items'] as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            if (! $product) {
                return $this->error("Product with ID {$item['product_id']} not found", [], 404);
            }
        }


        $order = DB::transaction(function () use ($data) {

            $order = Order::create([
                'user_id' => auth()->id(),
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


}
