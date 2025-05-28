<?php

namespace App\Services\News;

use App\Exceptions\HttpException;
use Illuminate\Support\Collection;
use App\Packages\Api\News\NewsEverythingClient;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Packages\Api\News\Everything\Request\ArticleRequest;

class NewsService
{
    public function __construct(
        protected NewsEverythingClient $newsEverythingClient
    ) {
        $this->newsEverythingClient = new NewsEverythingClient();
    }

    public function getArticles(Collection $request): LengthAwarePaginator
    {
        $requestBuilder = new ArticleRequest();

        $request = $requestBuilder
            ->setQ($request->get('q'))
            ->setSearchIn($request->get('searchIn'))
            ->setSources($request->get('sources'))
            ->setDomains($request->get('domains'))
            ->setExcludeDomains($request->get('excludeDomains'))
            ->setFrom($request->get('from'))
            ->setTo($request->get('to'))
            ->setSortBy($request->get('sortBy'))
            ->setLanguage($request->get('language'))
            ->setPage($request->get('page'))
            ->setPageSize($request->get('pageSize'))
            ->toArray();

        $response = $this->newsEverythingClient->getArticles($request);

        if (! $response->isSuccess()) {
            throw new HttpException(
                $response->getMessage(),
                $response->getStatusCode(),
                $response->getCode()
            );
        }

        $articles = $response->getArticles();

        return new LengthAwarePaginator(
            $articles,
            $response->getTotalResults(),
            $request['pageSize'] ?? 100,
            $request['page'] ?? 1
        );
    }
}
