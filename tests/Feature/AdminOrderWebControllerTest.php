<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderWebControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($this->admin);
    }

    public function test_index_shows_orders_with_pagination_and_filters(): void
    {
        $buyer = User::factory()->create(['email' => 'buyer@example.com']);
        Order::factory()->count(2)->create(['user_id' => $buyer->id, 'status' => 'accepted', 'total' => 150]);
        Order::factory()->count(1)->create(['status' => 'pending', 'total' => 50]);

        $resp = $this->get(route('admin.orders.index', [
            'status' => 'accepted',
            'sort_by' => 'total',
            'sort_dir' => 'desc',
        ]));

        $resp->assertOk();
        $resp->assertSee('Orders');
        $resp->assertSee('accepted');
    }

    public function test_search_by_order_id_and_email(): void
    {
        $buyer = User::factory()->create(['email' => 'buyer2@example.com']);
        $order = Order::factory()->create(['user_id' => $buyer->id]);

        $resp1 = $this->get(route('admin.orders.index', ['q' => (string) $order->id]));
        $resp1->assertOk()->assertSee((string) $order->id);

        $resp2 = $this->get(route('admin.orders.index', ['q' => 'buyer2@example.com']));
        $resp2->assertOk()->assertSee('buyer2@example.com');
    }

    public function test_show_displays_order_details_and_items(): void
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create(['title' => 'Sample Product']);
        $order->items()->create([
            'product_id' => $product->id,
            'price' => 99.95,
            'quantity' => 2,
        ]);

        $resp = $this->get(route('admin.orders.show', $order->id));
        $resp->assertOk();
        $resp->assertSee('Order #'.$order->id);
        $resp->assertSee('Sample Product');
        $resp->assertSee(number_format(99.95, 2));
    }

    public function test_stats_page_shows_totals(): void
    {
        Order::factory()->create(['total' => 100, 'status' => 'pending']);
        Order::factory()->create(['total' => 300, 'status' => 'accepted']);

        $resp = $this->get(route('admin.orders.stats'));
        $resp->assertOk();
        $resp->assertSee('Order Statistics');
        $resp->assertSee((string) 2); // total orders count present somewhere
    }

    public function test_export_downloads_csv(): void
    {
        Order::factory()->count(2)->create();
        $resp = $this->get(route('admin.orders.export'));
        $resp->assertOk();
        $resp->assertHeader('content-type', 'text/csv');
        $resp->assertSee('Order ID');
    }

    public function test_add_item_to_order(): void
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();

        $resp = $this->post(route('admin.orders.addItem', $order->id), [
            'product_id' => $product->id,
            'price' => 10.5,
            'quantity' => 3,
        ]);

        $resp->assertRedirect(route('admin.orders.show', $order->id));
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price' => 10.5,
            'quantity' => 3,
        ]);
    }
}
