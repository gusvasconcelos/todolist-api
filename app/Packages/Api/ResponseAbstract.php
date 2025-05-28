<?php

namespace App\Packages\Api;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

abstract class ResponseAbstract
{
    protected array $responseBody;

    protected int|null $statusCode;

    public function __construct(
        protected string $url,
        protected array $request,
        protected Response $response
    ) {
        $this->url = $url;

        $this->request = $request;

        $this->response = $response;

        $this->responseBody = $this->response->json() ?? [];

        $this->statusCode = $this->response->status() ?? null;

        Log::info(
            'API Response',
            [
                'url' => $this->url,
                'request' => $this->request,
                'response' => $this->responseBody,
                'statusCode' => $this->statusCode,
            ]
        );

        $this->hydrate();
    }

    abstract protected function hydrate(): void;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getRequest(): array
    {
        return $this->request;
    }

    public function getRequestBodyJsonPretty(): string
    {
        return json_encode($this->request, JSON_PRETTY_PRINT);
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getResponseBody(): array
    {
        return $this->responseBody;
    }

    public function getResponseBodyJsonPretty(): string
    {
        return json_encode($this->responseBody, JSON_PRETTY_PRINT);
    }

    public function getStatusCode(): int|null
    {
        return $this->statusCode;
    }
}
