<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthControllerTest extends TestCase
{
    use RefreshDatabase;
  protected function setUp(): void
    {
        parent::setUp();
        $this->withSession([]);
        $this->withoutExceptionHandling();
    }
    /** @test */
    public function admin_can_login_successfully()
    {
        $password = 'password123';

        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => $password,
            'is_admin' => true,
            'is_active' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' =>$password ,
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function non_admin_cannot_login_as_admin()
    {
        $password = 'password123';

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make($password),
            'is_admin' => false,
            'is_active' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'user@example.com',
            'password' => $password,
        ]);


        $response->assertSessionHasErrors(['error']);

        $this->assertGuest();
    }

    /** @test */
    public function wrong_credentials_cannot_login()
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('correctpassword'),
            'is_admin' => true,
            'is_active' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['error']);
        $this->assertGuest();
    }

    /** @test */
    public function admin_can_logout_successfully()
    {
        $password = 'password123';

        $admin = User::factory()->create([
            'is_admin' => true,
            'is_active' => true,
            'password' => $password,
        ]);

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => $password,
        ]);

        $this->assertAuthenticated();


        $response = $this->post('/admin/logout');

        $response->assertRedirect(route('admin.login.form'));


        $this->assertGuest();
    }

   
}
