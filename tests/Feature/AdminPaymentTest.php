<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\PaymentService;
use Mockery;

class AdminPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['is_admin' => true]);
        $this->actingAs($user);
    }


    public function test_show_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->get(route('admin.payments.show', $payment->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.payments.show');
        $response->assertSee((string) $payment->amount);
    }

    public function test_refund_denied_for_non_admin()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $nonAdmin = User::factory()->create(['is_admin' => false]);

        $payment = Payment::factory()->create(['status' => 'paid']);

        // act as non-admin
        $this->actingAs($nonAdmin);

        $response = $this->post(route('admin.payments.refund', $payment->id), [
            'reason' => 'test'
        ]);

        // middleware redirects non-admins to the admin login form with an error
        $response->assertRedirect(route('admin.login.form'));
        $response->assertSessionHasErrors('error');
    }

    public function test_refund_not_paid_returns_error()
    {
        $payment = Payment::factory()->create(['status' => 'pending']);

        $response = $this->post(route('admin.payments.refund', $payment->id), [
            'reason' => 'test'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('error');
    }

    public function test_refund_success_calls_service_and_redirects()
    {
        $payment = Payment::factory()->create(['status' => 'paid']);

        $mock = Mockery::mock(PaymentService::class);
        $mock->shouldReceive('refund')->once()->withArgs(function ($p, $reason, $user) use ($payment) {
            return $p->id === $payment->id && $reason === 'manual' && $user->is_admin;
        })->andReturn([
            'status' => 'refunded',
            'provider_reference' => 'REF-MOCK',
        ]);

        $this->app->instance(PaymentService::class, $mock);

        $response = $this->post(route('admin.payments.refund', $payment->id), [
            'reason' => 'manual'
        ]);

        $response->assertRedirect(route('admin.payments.show', $payment->id));
        $response->assertSessionHas('success');
    }

    public function test_refund_updates_totals()
    {
        $p1 = Payment::factory()->create(['status' => 'paid', 'amount' => 100]);
        $p2 = Payment::factory()->create(['status' => 'paid', 'amount' => 50]);

        $totalPaidBefore = Payment::where('status', 'paid')->sum('amount');
        $this->assertEquals(150, $totalPaidBefore);

        $response = $this->post(route('admin.payments.refund', $p1->id), [
            'reason' => 'store credit'
        ]);

        $response->assertRedirect(route('admin.payments.show', $p1->id));
        $response->assertSessionHas('success');

        $totalPaidAfter = Payment::where('status', 'paid')->sum('amount');
        $refundedAmount = Payment::where('status', 'refunded')->sum('amount');

        $this->assertEquals(50, $totalPaidAfter);
        $this->assertEquals(100, $refundedAmount);
    }
}
