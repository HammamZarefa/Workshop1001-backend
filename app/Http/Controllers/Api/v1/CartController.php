<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\CartStoreRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends ApiController
{
    public function index(Request $request)
    {
        $cart = Cart::with('items.product')
            ->firstOrCreate(['user_id' => auth()->id()], ['total' => 0]);

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

        // Validate prices
        foreach ($data['items'] as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            if (! $product) {
                return $this->error("Product with ID {$item['product_id']} not found", [], 404);
            }

            $expected = round((float) $product->price, 2);
            $provided = round((float) $item['price'], 2);
            if ($provided !== $expected) {
                return $this->error("Invalid price for product ID {$item['product_id']}", 422);
            }
        }

        $cart = DB::transaction(function () use ($data) {
            $cart = Cart::firstOrCreate([
                'user_id' => auth()->id(),
            ], [
                'total' => 0,
            ]);

            foreach ($data['items'] as $item) {
                $existing = $cart->items()->where('product_id', $item['product_id'])->first();
                if ($existing) {
                    $existing->update([
                        'price' => $item['price'],
                        'quantity' => $existing->quantity + $item['quantity'],
                    ]);
                } else {
                    $cart->items()->create($item);
                }
            }

            $cart->update([
                'total' => $cart->items()->sum(DB::raw('price * quantity')),
            ]);

            return $cart->load('items.product');
        });

        return $this->success('Cart updated successfully', new CartResource($cart), 201);
    }
}
