<?php

namespace App\Packages\Api\News;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

abstract class NewsClient
{
    protected string $apiKey;

    protected string $baseUrl;

    protected PendingRequest $client;

    public function __construct (
        
    ) {
        $this->apiKey = config('api.news.api_key');

        $this->baseUrl = config('api.news.base_url');

        $this->client = Http::withHeaders([
            'X-Api-Key' => $this->apiKey,
        ]);
    }
}
