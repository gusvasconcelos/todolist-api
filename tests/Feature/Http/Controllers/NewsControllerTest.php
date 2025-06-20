<?php

namespace Tests\Feature\Http\Controllers;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use Tests\Mocks\NewsApiMock;
use Illuminate\Support\Facades\Http;

class NewsControllerTest extends TestCase
{
    public function test_get_articles_with_q()
    {
        $user = User::factory()->create();

        Http::fake([
            '*' => Http::response(NewsApiMock::mockSuccessfulResponse(), 200),
        ]);

        $response = $this->actingAsUser($user)->get('/api/v1/news/articles?q=bitcoin');

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
        $user = User::factory()->create();

        Http::fake([
            '*' => Http::response(NewsApiMock::mockSuccessfulResponse(), 200),
        ]);

        $response = $this->actingAsUser($user)->get('/api/v1/news/articles?sources[]=bbc-news');

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
        $user = User::factory()->create();

        Http::fake([
            '*' => Http::response(NewsApiMock::mockSuccessfulResponse(), 200),
        ]);

        $response = $this->actingAsUser($user)->get('/api/v1/news/articles?domains[]=bbc.co.uk');

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
        $user = User::factory()->create();

        Http::fake([
            '*' => Http::response(NewsApiMock::mockSuccessfulResponse(), 200),
        ]);

        $response = $this->actingAsUser($user)->get('/api/v1/news/articles?q=yamal&excludeDomains[]=espn.com');

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
        $user = User::factory()->create();

        Http::fake([
            '*' => Http::response(NewsApiMock::mockSuccessfulResponse(), 200),
        ]);

        $today = Carbon::now()->format('Y-m-d');

        $response = $this->actingAsUser($user)->get("/api/v1/news/articles?q=bitcoin&from={$today}&to={$today}");

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
        $user = User::factory()->create();

        Http::fake([
            '*' => Http::response(NewsApiMock::mockSuccessfulResponse(), 200),
        ]);

        $response = $this->actingAsUser($user)->get('/api/v1/news/articles?q=bitcoin&sortBy=relevancy');

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
        $user = User::factory()->create();

        Http::fake([
            '*' => Http::response(NewsApiMock::mockSuccessfulResponse(), 200),
        ]);

        $response = $this->actingAsUser($user)->get('/api/v1/news/articles?q=bitcoin&language=pt');

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
        $user = User::factory()->create();

        Http::fake([
            '*' => Http::response(NewsApiMock::mockSuccessfulResponse(), 200),
        ]);

        $response = $this->actingAsUser($user)->get('/api/v1/news/articles?q=bitcoin&page=1&pageSize=2');

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

    public function test_get_articles_with_invalid_api_key()
    {
        $user = User::factory()->create();

        Http::fake([
            '*' => Http::response(NewsApiMock::mockErrorResponse(), 401),
        ]);

        $response = $this->actingAsUser($user)->get('/api/v1/news/articles?q=bitcoin');

        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Your API key is invalid',
            'code' => 'apiKeyInvalid',
        ]);
    }
}
