<?php

namespace App\Services\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiResponder
{
    protected int $statusCode;

    public function errorWithValue(string $message, $value, int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        return $this->respond([
            'message' => $message . $value
        ]);
    }

    public function success(string $message): JsonResponse
    {
        $this->statusCode = Response::HTTP_OK;
        return $this->respond([
            'message' => $message
        ]);
    }

    public function created(string $message): JsonResponse
    {
        $this->statusCode = Response::HTTP_CREATED;
        return $this->respond([
            'message' => $message
        ]);
    }

    public function updated(string $message): JsonResponse
    {
        $this->statusCode = Response::HTTP_OK;
        return $this->respond([
            'message' => $message
        ]);
    }

    public function badRequest(string $message): JsonResponse
    {
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        return $this->respond([
            'message' => $message
        ]);

    }

    public function destroyed(string $message): JsonResponse
    {
        $this->statusCode = Response::HTTP_OK;
        return $this->respond([
            'message' => $message
        ]);
    }

    public function notFound(string $message = 'Not Found!'): JsonResponse
    {
        $this->statusCode = Response::HTTP_NOT_FOUND;
        return $this->respond([
            'message' => $message
        ]);
    }

    public function internalError(): JsonResponse
    {
        $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        return $this->respond([
            'message' => __('messages.response.internal-error')
        ]);

    }

    public function unauthorizedError(): JsonResponse
    {
        $this->statusCode = Response::HTTP_UNAUTHORIZED;
        return $this->respond([
            'message' => __('messages.response.unauthorizedError'),
        ]);
    }

    private function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    private function respond($data): JsonResponse
    {
        return response()->json(
            $data, $this->getStatusCode()
        );
    }

}
