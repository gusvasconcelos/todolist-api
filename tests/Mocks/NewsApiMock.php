<?php

namespace Tests\Mocks;

class NewsApiMock
{
    public static function mockSuccessfulResponse(
        int $totalResults = null
    ): array {
        $totalResults = $totalResults ?? count(self::getDefaultArticles());

        return [
            'status' => 'ok',
            'totalResults' => $totalResults,
            'articles' => self::getDefaultArticles(),
        ];
    }

    public static function mockErrorResponse(
        string $code = 'apiKeyInvalid',
        string $message = 'Your API key is invalid'
    ): array {
        return [
            'status' => 'error',
            'code' => $code,
            'message' => $message,
        ];
    }

    private static function getDefaultArticles(): array
    {
        $faker = \Faker\Factory::create();

        return [
            [
                'source' => [
                    'id' => $faker->word,
                    'name' => $faker->word
                ],
                'author' => $faker->name,
                'title' => $faker->sentence,
                'description' => $faker->sentence,
                'url' => $faker->url,
                'urlToImage' => $faker->imageUrl,
                'publishedAt' => $faker->dateTime,
                'content' => $faker->paragraph
            ],
            [
                'source' => [
                    'id' => $faker->word,
                    'name' => $faker->word
                ],
                'author' => $faker->name,
                'title' => $faker->sentence,
                'description' => $faker->sentence,
                'url' => $faker->url,
                'urlToImage' => $faker->imageUrl,
                'publishedAt' => $faker->dateTime,
                'content' => $faker->paragraph
            ],
            [
                'source' => [
                    'id' => $faker->word,
                    'name' => $faker->word
                ],
                'author' => $faker->name,
                'title' => $faker->sentence,
                'description' => $faker->sentence,
                'url' => $faker->url,
                'urlToImage' => $faker->imageUrl,
                'publishedAt' => $faker->dateTime,
                'content' => $faker->paragraph
            ]
        ];
    }
}