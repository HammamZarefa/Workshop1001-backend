<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class ApiController extends Controller
{
    use ApiResponses;

    protected int $defaultPerPage = 15;
    protected int $maxPerPage = 100;

    protected function perPage(Request $request, ?int $default = null, ?int $max = null): int
    {
        $default = $default ?? $this->defaultPerPage;
        $max = $max ?? $this->maxPerPage;

        $perPage = (int) $request->query('per_page', $default);

        if ($perPage <= 0) {
            $perPage = $default;
        }

        return min($perPage, $max);
    }

    protected function paginationMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'last_page'    => $paginator->lastPage(),
            'from'         => $paginator->firstItem(),
            'to'           => $paginator->lastItem(),
            'has_more'     => $paginator->hasMorePages(),
        ];
    }

    protected function paginatedResponse(LengthAwarePaginator $paginator, string $message = 'OK'): JsonResponse
    {
        return $this->success(
            $paginator->items(),          // data
            $message,                     // message
            200,                          // status
            $this->paginationMeta($paginator) // meta
        );
    }
    protected function isAuthorized(string $ability, $model = null): bool
    {
        return auth()->check() && auth()->user()->can($ability, $model);
    }

    protected function ensureAuthorized(string $ability, $model = null): ?JsonResponse
    {
        if (! $this->isAuthorized($ability, $model)) {
            return $this->notAuthorized('You are not authorized to perform this action.');
        }

        return null;
    }
    protected function tryCall(callable $callback, string $errorMessage = 'Something went wrong', int $status = 500): JsonResponse
    {
        try {
            $result = $callback();

            // Case 1: Controller already returned JSON
            if ($result instanceof JsonResponse) {
                return $result;
            }

            // Case 2: API Resource / Resource Collection
            if ($result instanceof JsonResource || $result instanceof AnonymousResourceCollection) {
                return $this->success($result);
            }

            // Case 3: Any array / model / primitive
            return $this->success($result);

        } catch (Throwable $e) {

            Log::error('API exception: '.$e->getMessage(), [
                'exception' => $e,
                'url'       => request()->fullUrl(),
                'method'    => request()->method(),
            ]);

            return $this->error($errorMessage, $status);
        }
    }
}
