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
    public function activeCart(Request $request, PricingService $pricingService)
    {
        $cart = $this->getUserCart();

        $coupon = $this->resolveCoupon($request, $pricingService, $cart);

        return $this->cartResponse($cart, $coupon, $pricingService);
    }

    public function show($id, PricingService $pricingService, Request $request)
    {
        $cart = Cart::with('items.product')->find($id);

        abort_unless($cart, 404, 'Cart not found');
        abort_unless($cart->user_id === auth()->id(), 403, 'Unauthorized');

        $coupon = $this->resolveCoupon($request, $pricingService, $cart);

        return $this->cartResponse($cart, $coupon, $pricingService);
    }

    public function store(CartStoreRequest $request)
    {
        $data = $request->validated();

        $cart = DB::transaction(function () use ($data) {

            $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);

                if (! $product) {
                    throw new \Exception("Product with ID {$item['product_id']} not found");
                }

                if (round($item['price'], 2) !== round($product->price, 2)) {
                    throw new \Exception("Invalid price for product ID {$item['product_id']}");
                }

                $cart->items()->updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'quantity' => DB::raw("quantity + {$item['quantity']}"),
                        'price'    => $product->price
                    ]
                );
            }

            return $cart->load('items.product');
        });

        return $this->success('Cart updated successfully', new CartResource($cart), 201);
    }

    // -----------------------------------------------------
    // Helpers
    // -----------------------------------------------------

    private function getUserCart()
    {
        $cart = Cart::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        abort_unless($cart, 404, 'Cart not found');

        return $cart;
    }

    private function resolveCoupon(Request $request, PricingService $pricingService, Cart $cart) : ?Coupon
    {
        if (! $code = $request->get('coupon_code')) {
            return null;
        }

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            abort(422, 'Invalid coupon code');
        }

        if (! $coupon->isValid($pricingService->cartSubtotal($cart), auth()->user())) {
            abort(422, 'Coupon is not valid for this cart');
        }

        return $coupon;
    }

    private function cartResponse(Cart $cart, $coupon, PricingService $pricingService)
    {
        $totals = $pricingService->cartTotalsWithTax($cart, $coupon);

        $resource = (new CartResource($cart))
            ->additional(['meta' => ['pricing' => $totals]]);

        return $this->ok('Cart fetched', $resource);
    }
}
