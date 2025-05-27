<?php

namespace App\Helpers\Response;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class ErrorResponse implements Arrayable
{
    public function __construct(
        protected Throwable $exception,
        protected string $message,
        protected int $statusCode,
        protected string $errorCode,
        protected string|array|null $details = null
    ) {
        //
    }

    public function toJson(): JsonResponse
    {
        return response()->json($this->toArray(), $this->statusCode);
    }

    public function toArray(): array
    {
        $response = [
            'message' => $this->message,
            'status' => $this->statusCode,
            'code' => $this->errorCode,
        ];

        if ($this->details) {
            $response['details'] = $this->details;
        }

        if ($sql = $this->getSql()) {
            $response['sql'] = $sql;
        }

        if ($stack = $this->getStack()) {
            $response['stack'] = $stack;
        }

        return $response;
    }

    private function getStack(): array
    {
        if (! config('app.debug')) {
            return [];
        }

        $appPath = app_path();

        $getTrace = $this->exception->getTrace();

        $trace = collect($getTrace)
            ->filter(function ($item) use ($appPath) {
                return Arr::exists($item, 'file') && Str::startsWith($item['file'], $appPath);
            })
            ->values()
            ->toArray();

        return [
            'file' => $this->exception->getFile(),
            'line' => $this->exception->getLine(),
            'message' => $this->exception->getMessage(),
            'trace' => $trace,
        ];
    }

    private function getSql(): array
    {
        if (! $this->exception instanceof QueryException) {
            return [];
        }

        $sql = $this->exception->getSql();

        $bindings = $this->exception->getBindings();

        return collect($bindings)
            ->map(function ($binding) use ($sql) {
                $value = is_numeric($binding) ? $binding : "'$binding'";

                return preg_replace('/\?/', $value, $sql, 1);
            })->toArray();
    }
}
