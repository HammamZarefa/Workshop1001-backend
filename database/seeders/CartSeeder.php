<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartSeeder extends Seeder
{
    /**
     * @throws \Exception
     */
    public function run(): void
    {
        // if you already have users & products in DB, remove these
        $users = User::take(3)->get();
        $products = Product::take(5)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            throw new \Exception('Users or products missing. Seed them first.');
        }

        foreach ($users as $user) {
            // create or get user cart
            $cart = Cart::firstOrCreate([
                'user_id' => $user->id,
            ]);

            // add random items
            foreach ($products->random(min(3, $products->count())) as $product) {
                CartItem::updateOrCreate(
                    [
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                    ],
                    [
                        'price' => $product->price ?? 100,
                        'quantity' => rand(1, 3),
                    ]
                );
            }
        }
    }
}
