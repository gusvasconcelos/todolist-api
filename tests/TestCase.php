<?php

namespace Tests;

use App\Models\User;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function actingAsUser(User $user, $guard = null): self
    {
        $token = auth('api')->tokenById($user->id);

        return $this->withHeaders([
            'Authorization' => 'Bearer' . $token
        ]);
    }

    public function assertPagination(TestResponse $response, array $data = [])
    {
        $response
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => $data,
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ]);

        return $response;
    }
}
