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
use Illuminate\Validation\ValidationException;

class CartController extends ApiController
{
    private PricingService $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    public function activeCart(Request $request)
    {
        $cart = $this->getUserCart();

        $coupon = $this->resolveCoupon($request, $cart);

        return $this->cartResponse($cart, $coupon);
    }

    public function show($id, Request $request)
    {
        $cart = Cart::with('items.product')->find($id);

        abort_unless($cart, 404, 'Cart not found');
        abort_unless($cart->user_id === auth()->id(), 403, 'Unauthorized');

        $coupon = $this->resolveCoupon($request, $cart);

        return $this->cartResponse($cart, $coupon);
    }


    public function store(CartStoreRequest $request)
    {
        $data = $request->validated();

        $cart = DB::transaction(function () use ($data) {


            $cart = Cart::where('user_id', auth()->id())
                ->where('status', 'active')
                ->first();


            if (! $cart) {
                $cart = Cart::create([
                    'user_id' => auth()->id(),
                    'status'  => 'active',
                ]);
            }

            foreach ($data['items'] as $index => $item) {
                $product = Product::find($item['product_id']);


                if (! $product) {
                    throw ValidationException::withMessages([
                        "items.$index.product_id" => "Product with ID {$item['product_id']} not found"
                    ]);
                }


                if (round($item['price'], 2) !== round($product->price, 2)) {
                    throw ValidationException::withMessages([
                        "items.$index.price" => "Invalid price for product ID {$product->id}"
                    ]);
                }


                $cartItem = $cart->items()->firstOrNew([
                    'product_id' => $product->id,
                ]);


                $currentQty = $cartItem->exists ? (int) $cartItem->quantity : 0;
                $cartItem->quantity = $currentQty + (int) $item['quantity'];
                $cartItem->price = $product->price;

                $cartItem->save();
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
            ->where('status', 'active')
            ->latest()
            ->first();

        abort_unless($cart, 404, 'Cart not found');

        return $cart;
    }

    private function resolveCoupon(Request $request, Cart $cart) : ?Coupon
    {
        if (! $code = $request->get('coupon_code')) {
            return null;
        }

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            abort(422, 'Invalid coupon code');
        }

        if (! $coupon->isValid($this->pricingService->cartSubtotal($cart), auth()->user())) {
            abort(422, 'Coupon is not valid for this cart');
        }

        return $coupon;
    }

    private function cartResponse(Cart $cart, $coupon)
    {
        $totals = $this->pricingService->cartTotalsWithTax($cart, $coupon);

        $resource = (new CartResource($cart))
            ->additional(['meta' => ['pricing' => $totals]]);

        return $this->ok('Cart fetched', $resource);
    }
}
