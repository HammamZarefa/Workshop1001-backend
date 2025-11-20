<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\CartStoreRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends ApiController
{
    public function index(Request $request, PricingService $pricingService)
    {
        $cart = Cart::with('items.product')
            ->firstOrCreate(['user_id' => auth()->id()]);

        $cart->load('items.product');
        $coupon = null;
        if ($code = $request->input('coupon_code')) {
            $coupon = Coupon::where('code', $code)->first();

            if (! $coupon) {
                return $this->error('Invalid coupon code', [], 422);
            }

            $subtotal = $pricingService->cartSubtotal($cart);
            if (! $coupon->isValid($subtotal, auth()->user())) {
                return $this->error('Coupon is not valid for this cart', [], 422);
            }
        }

        $totals = $pricingService->cartTotalsWithTax($cart, $coupon);

        $resource = (new CartResource($cart))
            ->additional(['meta' => ['pricing' => $totals]]);

        return $this->ok('Cart fetched', $resource);
    }

    public function show($id, PricingService $pricingService, Request $request)
    {
        $cart = Cart::with('items.product')->find($id);
        if (! $cart) {
            return $this->error('Cart not found', [], 404);
        }
        if ($cart->user_id !== auth()->id()) {
            return $this->error('Unauthorized', [], 403);
        }

        $cart->load('items.product');

        $coupon = null;
        if ($code = $request->input('coupon_code')) {
            $coupon = Coupon::where('code', $code)->first();

            if (! $coupon) {
                return $this->error('Invalid coupon code', [], 422);
            }

            $subtotal = $pricingService->cartSubtotal($cart);
            if (! $coupon->isValid($subtotal, auth()->user())) {
                return $this->error('Coupon is not valid for this cart', [], 422);
            }
        }

        $totals = $pricingService->cartTotalsWithTax($cart, $coupon);

        $resource = (new CartResource($cart))
            ->additional(['meta' => ['pricing' => $totals]]);

        return $this->ok('Cart fetched', $resource);
    }

    public function store(CartStoreRequest $request)
    {
        $data = $request->validated();

        $cart = DB::transaction(function () use ($data) {

            $cart = Cart::firstOrCreate(
                ['user_id' => auth()->id()]
            );

            foreach ($data['items'] as $item) {

                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception("Product with ID {$item['product_id']} not found");
                }

                if (round($item['price'], 2) !== round($product->price, 2)) {
                    throw new \Exception("Invalid price for product ID {$item['product_id']}");
                }


                $existing = $cart->items()->where('product_id', $product->id)->first();
                if ($existing) {

                    $existing->update([
                        'quantity' => $existing->quantity + $item['quantity'],
                        'price' => $product->price,
                    ]);
                } else {

                    $cart->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);
                }
            }


            


            return $cart->load('items.product');
        });

        return $this->success('Cart updated successfully', new CartResource($cart), 201);
    }
}
