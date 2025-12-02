<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Cart;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends ApiController
{
    public function myOrders(Request $request)
    {
        $perPage = $this->perPage($request);

        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate($perPage);
        return $this->success(
        'Orders fetched',                     // الرسالة
        OrderResource::collection($orders),   // البيانات
        200
    );
    }


    public function show($id)
    {
        $order = Order::with('items.product', 'user')->find($id);

        if (!$order) {
            return $this->error('Order not found', 404);
        }
        if ($order->user_id !== auth()->id()) {
            return $this->error('Unauthorized', 403);
        }

        return $this->ok('Order fetched', new OrderResource($order));
    }

    public function store(OrderStoreRequest $request, PricingService $pricingService)
    {
        $data = $request->validated();
        foreach ($data['items'] as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            if (! $product) {
                return $this->error("Product with ID {$item['product_id']} not found", [], 404);
            }
        }

        $subtotal = collect($data['items'])->sum(fn ($item) => $item['price'] * $item['quantity']);

        $couponId = null;
        $couponValue = 0;

        if (!empty($data['coupon_code'])) {
            $coupon = Coupon::where('code', $data['coupon_code'])->first();

            if (! $coupon) {
                return $this->error('Invalid coupon code', [], 422);
            }

            if (! $coupon->isValid($subtotal, auth()->user())) {
                return $this->error('Coupon is not valid for this order', [], 422);
            }

            $couponId = $coupon->id;
            $couponValue = $coupon->calculateDiscount($subtotal);
        }


        $order = DB::transaction(function () use ($data, $couponId, $couponValue, $pricingService) {

            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_address' => $data['shipping_address'],
                'coupon_id' => $couponId,
                'coupon_value' => $couponValue,
                'tax_amount' => $data['tax_amount'] ?? 0,
                'discount_percentage' => $data['discount_percentage'] ?? 0,
                'currency' => $data['currency'],
                'total' => 0
            ]);


            $order->items()->createMany($data['items']);

            $order->load('items.product');

            $order->update([
                'total' => $pricingService->orderTotal($order),
            ]);

            // Mark the current pending cart as completed
            $cart = Cart::where('user_id', auth()->id())
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($cart) {
                $cart->update(['status' => 'completed']);
            }

            return $order;
        });

        return $this->success('Order created successfully', new OrderResource($order), 201);
    }


}
