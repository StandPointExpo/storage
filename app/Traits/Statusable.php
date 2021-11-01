<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

trait Statusable
{

    /**
     * @param $data
     * @return JsonResponse
     */
    protected function success($data = [], $statusCode = 200): JsonResponse
    {
        return Response::json($this->getStatusWithPayload(true, $data), $statusCode);
    }

    /**
     * @param \Throwable $exception
     * @return JsonResponse
     */
    protected function fail(\Throwable $exception)
    {
        report($exception);
        return Response::json($this->getStatusWithPayload(false, $exception->getMessage()), $exception->getCode());
    }

    /**
     * @param bool $status
     * @param $data
     * @return array
     */
    protected function getStatusWithPayload($status = true, $data = [])
    {
        return compact('status', 'data');
    }

    /**
     * @param \Throwable $exception
     * @return JsonResponse
     */
    protected function errorMessageResponse(\Throwable $exception): JsonResponse
    {
        report($exception);
        return response()->json(['message' => $exception->getMessage()], (!is_int($exception->getCode())) ? 500 : $exception->getCode());
    }

    /**
     * @param string $messageKey
     * @param string $messageContent
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function message($messageKey = 'message', $messageContent = '', $statusCode = 200)
    {
        return response()->json([$messageKey => $messageContent], $statusCode);
    }
}
