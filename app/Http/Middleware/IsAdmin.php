<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Traits\ApiResponses;

class IsAdmin
{
    use ApiResponses;
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->is_admin) {
             return redirect()->route('admin.login.form')
                ->withErrors(['email' => 'Access denied']);
        }

        return $next($request);
    }
}
