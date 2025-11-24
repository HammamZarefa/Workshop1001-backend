<?php

namespace Tests\Unit;

use App\Http\Middleware\IsAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class IsAdminMiddlewareTest extends TestCase
{
    /** @test */
    public function admin_user_can_pass_through_middleware()
    {
        $admin = User::factory()->make(['is_admin' => true]);

        $middleware = new IsAdmin();

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $admin);

        $response = $middleware->handle($request, function () {
            return response('OK', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function non_admin_user_is_blocked()
    {
        $user = User::factory()->make(['is_admin' => false]);

        $middleware = new IsAdmin();

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $user);

        $response = $middleware->handle($request, function () {
            return response('OK', 200);
        });

        $this->assertEquals(400, $response->getStatusCode());
    }

    /** @test */
    public function missing_user_is_blocked()
    {
        $middleware = new IsAdmin();

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => null);

        $response = $middleware->handle($request, function () {
            return response('OK', 200);
        });

        $this->assertEquals(400, $response->getStatusCode());
    }
}
