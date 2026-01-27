<?php
namespace App\Listeners;

use App\Events\OrderPaid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UpdateProductStock
{
    /**
     * Handle the event.
     */
    public function handle(OrderPaid $event): void
    {
        $order = $event->order;

        if ($order->stock_deducted_at) {
            return;
        }

        DB::transaction(function () use ($order) {
            $order->load('items');

            foreach ($order->items as $item) {

                $product = DB::table('products')->where('id', $item->product_id)->lockForUpdate()->first();

                if (!$product) continue;

                if ($product->stock < $item->quantity) {
                    throw new Exception("Product {$product->id} is out of stock");
                }

                DB::table('products')->where('id', $product->id)->decrement('stock', $item->quantity);
            }

            $order->stock_deducted_at = Carbon::now('UTC');
            $order->save();
        });


        $order->refresh();

        Log::info('Stock deducted at: ' . $order->stock_deducted_at);
    }
}
