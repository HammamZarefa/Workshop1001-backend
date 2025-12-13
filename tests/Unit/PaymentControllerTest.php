<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\Admin\PaymentController;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_refund_success_redirects_with_success_message()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $payment = Payment::factory()->create(['status' => 'paid']);

        $mock = Mockery::mock(PaymentService::class);
        $mock->shouldReceive('refund')->once()->andReturnTrue();

        $controller = new PaymentController($mock);

        $request = Request::create('/admin/payments/'.$payment->id.'/refund', 'POST', ['reason' => 'ok']);
        $request->setUserResolver(fn() => $admin);

        $response = $controller->refund($request, $payment);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString(route('admin.payments.show', $payment->id), $response->headers->get('Location'));
    }

    public function test_refund_exception_returns_error_redirect()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $payment = Payment::factory()->create(['status' => 'paid']);

        $mock = Mockery::mock(PaymentService::class);
        $mock->shouldReceive('refund')->once()->andThrow(new \Exception('boom'));

        $controller = new PaymentController($mock);

        $request = Request::create('/admin/payments/'.$payment->id.'/refund', 'POST', ['reason' => 'ok']);
        $request->setUserResolver(fn() => $admin);

        $response = $controller->refund($request, $payment);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString(route('admin.payments.show', $payment->id), $response->headers->get('Location'));
    }
}
