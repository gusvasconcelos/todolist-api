<?php

namespace Tests\Feature\Http\Controllers;

use Carbon\Carbon;
use Tests\TestCase;

class NewsControllerTest extends TestCase
{
    public function test_get_articles_with_q()
    {
        $response = $this->get('/api/v1/news/articles?q=bitcoin');

        dd($response->getContent());

        $response->assertStatus(200);

        $this->assertPagination($response, [
            'source',
            'author',
            'title',
            'description',
            'url',
            'urlToImage',
            'publishedAt',
            'content',
        ]);
    }

    public function test_get_articles_with_sources()
    {
        $response = $this->get('/api/v1/news/articles?sources[]=bbc-news');

        $response->assertStatus(200);

        $this->assertPagination($response, [
            'source',
            'author',
            'title',
            'description',
            'url',
            'urlToImage',
            'publishedAt',
            'content',
        ]);
    }

    public function test_get_articles_with_domains()
    {
        $response = $this->get('/api/v1/news/articles?domains[]=bbc.co.uk');

        $response->assertStatus(200);

        $this->assertPagination($response, [
            'source',
            'author',
            'title',
            'description',
            'url',
            'urlToImage',
            'publishedAt',
            'content',
        ]);
    }

    public function test_get_articles_with_exclude_domains()
    {
        $response = $this->get('/api/v1/news/articles?q=yamal&excludeDomains[]=espn.com');

        $response->assertStatus(200);

        $this->assertPagination($response, [
            'source',
            'author',
            'title',
            'description',
            'url',
            'urlToImage',
            'publishedAt',
            'content',
        ]);

        $response->assertJsonMissing([
            'source' => [
                'id' => 'espn',
            ],
        ]);
    }

    public function test_get_articles_with_from_and_to()
    {
        $today = Carbon::now()->format('Y-m-d');

        $response = $this->get("/api/v1/news/articles?q=bitcoin&from={$today}&to={$today}");

        $response->assertStatus(200);

        $this->assertPagination($response, [
            'source',
            'author',
            'title',
            'description',
            'url',
            'urlToImage',
            'publishedAt',
            'content',
        ]);
    }

    public function test_get_articles_with_sort_by()
    {
        $response = $this->get('/api/v1/news/articles?q=bitcoin&sortBy=relevancy');

        $response->assertStatus(200);

        $this->assertPagination($response, [
            'source',
            'author',
            'title',
            'description',
            'url',
            'urlToImage',
            'publishedAt',
            'content',
        ]);
    }

    public function test_get_articles_with_language()
    {
        $response = $this->get('/api/v1/news/articles?q=bitcoin&language=pt');

        $response->assertStatus(200);

        $this->assertPagination($response, [
            'source',
            'author',
            'title',
            'description',
            'url',
            'urlToImage',
            'publishedAt',
            'content',
        ]);
    }

    public function test_get_articles_with_page_and_page_size()
    {
        $response = $this->get('/api/v1/news/articles?q=bitcoin&page=1&pageSize=2');

        $response->assertStatus(200);

        $this->assertPagination($response, [
            'source',
            'author',
            'title',
            'description',
            'url',
            'urlToImage',
            'publishedAt',
            'content',
        ]);

        $response->assertJson([
            'current_page' => 1,
            'per_page' => 2,
        ]);
    }
}
