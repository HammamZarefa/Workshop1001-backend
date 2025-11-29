<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // If there are already orders, skip to avoid duplicates
        if (Order::query()->exists()) {
            return;
        }

        // Ensure there are users and products
        if (!User::query()->exists()) {
            User::factory()->count(5)->create();
        }
        if (!Product::query()->exists()) {
            // In case product seeder didn't run yet, create some products
            Product::factory()->count(10)->create();
        }

        DB::transaction(function () {
            // Create 20 orders with 1-4 items each
            Order::factory()
                ->count(20)
                ->create()
                ->each(function (Order $order) {
                    $itemsCount = fake()->numberBetween(1, 4);
                    $products = Product::inRandomOrder()->limit($itemsCount)->get();

                    foreach ($products as $product) {
                        OrderItem::factory()->create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                        ]);
                    }

                    // Update order total = sum(price * qty)
                    $total = $order->items()
                        ->selectRaw('SUM(price * quantity) AS total')
                        ->value('total') ?? 0;

                    $order->update(['total' => $total]);
                });
        });
    }
}
