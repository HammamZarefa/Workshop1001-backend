<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
   // app/Http/Middleware/IsAdmin.php
public function handle(Request $request, Closure $next)
{
    if (!auth()->check() || auth()->user()->is_admin != 1) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    return $next($request);
}

}
