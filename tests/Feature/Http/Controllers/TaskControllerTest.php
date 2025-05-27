<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\TaskStatusEnum;
use Tests\TestCase;
use Database\Factories\TaskFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TaskControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected string $url = '/api/v1/tasks';

    public function test_index_with_successful(): void
    {
        $user = UserFactory::new()->create();

        TaskFactory::new()->stateUser($user)->count(30)->create();

        $response = $this->actingAs($user)->getJson($this->url);

        $response->assertStatus(200);

        $this->assertPagination($response);
    }

    public function test_index_with_filter_from_status_pending(): void
    {
        $user = UserFactory::new()->create();

        TaskFactory::new()->stateUser($user)->stateStatus(TaskStatusEnum::PENDING)->count(10)->create();

        $filter = TaskStatusEnum::PENDING->value;

        $response = $this->actingAs($user)->getJson($this->url . '?status=' . $filter);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'status' => $filter,
            ]);

        $this->assertPagination($response);
    }

    public function test_index_with_filter_from_status_in_progress(): void
    {
        $user = UserFactory::new()->create();

        TaskFactory::new()->stateUser($user)->stateStatus(TaskStatusEnum::IN_PROGRESS)->count(10)->create();

        $filter = TaskStatusEnum::IN_PROGRESS->value;

        $response = $this->actingAs($user)->getJson($this->url . '?status=' . $filter);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'status' => $filter,
            ]);

        $this->assertPagination($response);
    }

    public function test_index_with_filter_from_status_concluded(): void
    {
        $user = UserFactory::new()->create();

        TaskFactory::new()->stateUser($user)->stateStatus(TaskStatusEnum::CONCLUDED)->count(10)->create();

        $filter = TaskStatusEnum::CONCLUDED->value;

        $response = $this->actingAs($user)->getJson($this->url . '?status=' . $filter);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'status' => $filter,
            ]);

        $this->assertPagination($response);
    }

    public function test_index_with_set_page_and_per_page(): void
    {
        $user = UserFactory::new()->create();

        TaskFactory::new()->stateUser($user)->count(30)->create();

        $page = 2;

        $perPage = 10;

        $response = $this->actingAs($user)->getJson($this->url . '?page=' . $page . '&per_page=' . $perPage);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'current_page' => $page,
                'per_page' => $perPage,
            ]);

        $this->assertPagination($response);
    }
}
