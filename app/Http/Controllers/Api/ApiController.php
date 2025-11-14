<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Throwable;

class ApiController extends Controller
{
    protected int $defaultPerPage = 15;
    protected int $maxPerPage = 100;

    protected function respondSuccess(mixed $data = null, string $message = 'OK', int $status = 200, array $meta = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => (object) $meta,
        ], $status);
    }

    protected function respondCreated(mixed $data = null, string $message = 'Created'): JsonResponse
    {
        return $this->respondSuccess($data, $message, 201);
    }

    protected function respondNoContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    protected function respondError(string $message = 'Error', int $status = 400, mixed $errors = null, array $meta = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'meta' => (object) $meta,
        ], $status);
    }

    protected function respondUnauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->respondError($message, 401);
    }

    protected function respondForbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->respondError($message, 403);
    }

    protected function respondNotFound(string $message = 'Not Found'): JsonResponse
    {
        return $this->respondError($message, 404);
    }

    protected function respondUnprocessable(mixed $errors = null, string $message = 'Unprocessable Entity'): JsonResponse
    {
        return $this->respondError($message, 422, $errors);
    }

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
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'has_more' => $paginator->hasMorePages(),
        ];
    }

    protected function paginatedResponse(LengthAwarePaginator $paginator, string $message = 'OK'): JsonResponse
    {
        return $this->respondSuccess(
            $paginator->items(),
            $message,
            200,
            $this->paginationMeta($paginator)
        );
    }

    protected function resourceResponse(JsonResource|AnonymousResourceCollection $resource, string $message = 'OK', int $status = 200, array $meta = []): JsonResponse
    {
        // Ensure any additional meta on the resource is merged under top-level meta
        if (method_exists($resource, 'additional')) {
            $resource->additional([]);
        }
        $response = $resource->response()->setStatusCode($status);

        // Normalize structure to match success payload shape
        $original = $resource->resolve(request());
        $payload = [
            'success' => true,
            'message' => $message,
            'data' => $original,
            'meta' => (object) $meta,
        ];

        $response->setData($payload);
        return $response;
    }

    protected function safe(callable $callback, string $errorMessage = 'Something went wrong', int $status = 500): JsonResponse
    {
        try {
            $result = $callback();
            // Allow controllers to return a JsonResponse directly
            if ($result instanceof JsonResponse) {
                return $result;
            }
            // Or return plain arrays/resources
            if ($result instanceof JsonResource || $result instanceof AnonymousResourceCollection) {
                return $this->resourceResponse($result);
            }
            return $this->respondSuccess($result);
        } catch (Throwable $e) {
            Log::error('API exception: '.$e->getMessage(), [
                'exception' => $e,
                'url' => request()->fullUrl(),
                'method' => request()->method(),
            ]);
            return $this->respondError($errorMessage, $status);
        }
    }
}
