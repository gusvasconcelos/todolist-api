<?php

namespace App\Packages\Api\News;

use App\Packages\Api\News\Everything\Response\ArticleResponse;

class NewsEverythingClient extends NewsClient
{
    public function getArticles(array $request): ArticleResponse
    {
        $url = "$this->baseUrl/everything";

        $response = $this->client->get($url, $request);

        return new ArticleResponse($url, $request, $response);
    }
}
