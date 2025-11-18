<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\CartStoreRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends ApiController
{
    public function index(Request $request)
    {
        $cart = Cart::with('items.product')
            ->firstOrCreate(['user_id' => auth()->id()]);

        return $this->ok('Cart fetched', new CartResource($cart->load('items.product')));
    }

    public function show($id)
    {
        $cart = Cart::with('items.product')->find($id);
        if (! $cart) {
            return $this->error('Cart not found', [], 404);
        }
        if ($cart->user_id !== auth()->id()) {
            return $this->error('Unauthorized', [], 403);
        }

        return $this->ok('Cart fetched', new CartResource($cart));
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
