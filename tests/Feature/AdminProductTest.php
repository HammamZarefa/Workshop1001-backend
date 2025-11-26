<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use PHPUnit\Framework\Attributes\Test;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdminUser()
    {
        return User::factory()->admin()->create();
    }

    protected function createNormalUser()
    {
        return User::factory()->create();
    }

    /** @test */
    public function admin_can_view_products_index()
    {
        $admin = $this->createAdminUser();
        Product::factory()->count(3)->create();
        $response = $this->actingAs($admin)->get('/admin/products');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_create_page()
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get('/admin/products/create');

        $response->assertStatus(200);

    }


    /** @test */
    public function admin_can_view_edit_page()
    {
        $admin = $this->createAdminUser();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)->get("/admin/products/{$product->id}/edit");

        $response->assertStatus(200);

    }

    /** @test */
    public function admin_can_update_product()
    {
        Storage::fake('public');

        $admin = $this->createAdminUser();
        $product = Product::factory()->create(['title' => 'Old name', 'stock' => 5]);

        $payload = [
            'title' => 'New name',
            'price' => 1500,
            'stock' => 7,
        ];

        $response = $this->actingAs($admin)->put("/admin/products/{$product->id}", $payload);

        $response->assertRedirect('/admin/products');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'title' => 'New name', 'stock' => 7]);
    }

    /** @test */
    public function admin_can_delete_product()
    {
        $admin = $this->createAdminUser();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/products/{$product->id}");

        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $response->assertRedirect(route('admin.products.index'));

    }

    /** @test */
    public function admin_can_update_stock()
    {
        $admin = $this->createAdminUser();
        $product = Product::factory()->create(['stock' => 5]);

        $response = $this->actingAs($admin)->patch("/admin/products/{$product->id}/stock", [
            'delta' => 2
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 7
        ]);

        $response = $this->actingAs($admin)->patch("/admin/products/{$product->id}/stock", [
            'stock' => 3
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3
        ]);
    }

    /** @test */
public function non_admin()
{
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/admin/products');
    $response->assertStatus(403);
}





}
