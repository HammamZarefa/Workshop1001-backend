<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Symfony\Component\HttpFoundation\Response;

class AuditAdminActions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Execute the request first
        $response = $next($request);

        // Only log if request was successful
        if (! $this->shouldLog($request, $response)) {
            return $response;
        }

        $this->createAuditLog($request);

        return $response;
    }

    /**
     * Determine if this request should be logged.
     */
    protected function shouldLog(Request $request, Response $response): bool
    {
        $user = $request->user();

        if (! $user || ! $user->is_admin) {
            return false;
        }

        if ($request->isMethod('get')) {
            return false;
        }

        if ($response->getStatusCode() >= 400) {
            return false;
        }

        return in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']);
    }


    /**
     * Create audit log entry.
     */
    protected function createAuditLog(Request $request): void
    {
        AuditLog::create([
            'admin_id'   => auth()->id(),
            'action'     => $this->resolveAction($request),
            'resource'   => $this->resolveResource($request),
            'resource_id'=> $this->resolveResourceId($request),
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);
    }

    /**
     * Determine action name.
     */
    protected function resolveAction(Request $request): string
    {
        return match ($request->method()) {
            'POST'   => 'create',
            'PUT',
            'PATCH'  => 'update',
            'DELETE' => 'delete',
            default  => 'unknown',
        };
    }

    /**
     * Determine resource name.
     */
    protected function resolveResource(Request $request): string
    {
        // Example: admin/orders/123 → orders
        return class_basename(
            optional($request->route())->getName()
                ? explode('.', $request->route()->getName())[1] ?? 'unknown'
                : 'unknown'
        );
    }

    /**
     * Resolve resource ID from route parameters.
     */
    protected function resolveResourceId(Request $request): ?int
    {
        foreach ($request->route()->parameters() as $param) {
            if (is_numeric($param)) {
                return (int) $param;
            }

            if (is_object($param) && method_exists($param, 'getKey')) {
                return $param->getKey();
            }
        }

        return null;
    }
}
