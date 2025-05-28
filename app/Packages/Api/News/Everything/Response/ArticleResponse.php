<?php

namespace App\Packages\Api\News\Everything\Response;

use App\Packages\Api\ResponseAbstract;

class ArticleResponse extends ResponseAbstract
{
    protected string|null $status = null;

    protected string|null $code = null;

    protected string|null $message = null;

    protected int|null $totalResults = null;

    protected array $articles = [];

    protected function hydrate(): void
    {
        $this->status = $this->responseBody['status'] ?? null;

        $this->code = $this->responseBody['code'] ?? null;

        $this->message = $this->responseBody['message'] ?? null;

        $this->totalResults = $this->responseBody['totalResults'] ?? null;

        $this->articles = $this->responseBody['articles'] ?? [];
    }

    public function isSuccess(): bool
    {
        return $this->status === 'ok';
    }

    public function getStatus(): string|null
    {
        return $this->status;
    }

    public function getCode(): string|null
    {
        return $this->code;
    }

    public function getMessage(): string|null
    {
        return $this->message;
    }

    public function getTotalResults(): int|null
    {
        return $this->totalResults;
    }

    public function getArticles(): array
    {
        return $this->articles;
    }
}