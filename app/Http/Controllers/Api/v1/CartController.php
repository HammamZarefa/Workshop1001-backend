<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\CartStoreRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class CartController extends ApiController
{
<<<<<<< HEAD
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
=======
    public function __construct(private PricingService $pricingService)
    {
    }

    public function activeCart(Request $request)
    {
        $cart = $this->getUserCart();
        if ($cart instanceof JsonResponse) return $cart;

        $coupon = $this->resolveCoupon($request, $cart);
        if ($coupon instanceof JsonResponse) return $coupon;

        return $this->cartResponse($cart, $coupon);
    }

    public function show($id, Request $request)
>>>>>>> 5f1b6e179892a2fe1068015be518e592951f5f39
    {
        $cart = Cart::with('items.product')->find($id);
        if (!$cart) return jsonError('Cart not found', [], 404);
        if ($cart->user_id !== auth()->id()) return jsonError('Unauthorized', [], 403);

<<<<<<< HEAD
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
=======
        $coupon = $this->resolveCoupon($request, $cart);
        if ($coupon instanceof JsonResponse) return $coupon;

        return $this->cartResponse($cart, $coupon);
>>>>>>> 5f1b6e179892a2fe1068015be518e592951f5f39
    }

    public function store(CartStoreRequest $request)
    {
        $data = $request->validated();

        $cart = DB::transaction(function () use ($data) {


            $cart = Cart::with(['items.product:id,name,price'])
                ->where('user_id', auth()->id())
                ->where('status', 'pending')
                ->first();

            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => auth()->id(),
                    'status' => 'pending',
                ]);
            }


            $productIds = collect($data['items'])->pluck('product_id')->all();
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');


            $missingIds = array_diff($productIds, $products->keys()->toArray());
            if (!empty($missingIds)) {
                throw ValidationException::withMessages([
                    'items' => "Products not found: " . implode(', ', $missingIds)
                ]);
            }

            $cartItems = [];
            foreach ($data['items'] as $index => $item) {
                $product = $products[$item['product_id']];

                if (isset($item['price']) && $item['price'] != $product->price) {
                    throw ValidationException::withMessages([
                        "items.$index.price" => "Invalid price for product {$product->name}"
                    ]);
                }

                $cartItems[] = [
                    'cart_id'    => $cart->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $product->price,
                ];
            }

            CartItem::upsert(
                $cartItems,
                ['cart_id', 'product_id'],
                ['quantity', 'price']
            );

            return $cart->load('items.product:id,name,price');
        });

        $coupon = $request->get('coupon_code')
            ? Coupon::where('code', $request->coupon_code)->first()
            : null;

        $totals = $this->pricingService->cartTotalsWithTax($cart, $coupon);

        return $this->success(
            'Cart updated successfully',
            (new CartResource($cart))->additional(['meta' => ['pricing' => $totals]]),
            201
        );
    }


    // -----------------------------------------------------
    // Helpers
    // -----------------------------------------------------

    private function getUserCart(): Cart|JsonResponse
    {
        $cart = Cart::with(['items.product:id,name,price'])
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->latest()
            ->first();

        return $cart ?? jsonError('Cart not found', [], 404);
    }

    private function resolveCoupon(Request $request, Cart $cart): Coupon|JsonResponse|null
    {
        $code = $request->get('coupon_code');
        if (!$code) return null;

        $coupon = Coupon::where('code', $code)->first();
        if (!$coupon) return jsonError('Invalid coupon code', [], 422);

        if (!$coupon->isValid($this->pricingService->cartSubtotal($cart), auth()->user())) {
            return jsonError('Coupon is not valid for this cart', [], 422);
        }

        return $coupon;
    }


    private function cartResponse(Cart $cart, ?Coupon $coupon)
    {
        $totals = $this->pricingService->cartTotalsWithTax($cart, $coupon);

        return $this->ok(
            'Cart fetched',
            (new CartResource($cart))->additional(['meta' => ['pricing' => $totals]])
        );
    }

}
