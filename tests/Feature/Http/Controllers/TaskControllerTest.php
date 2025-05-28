<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Task;
use App\Enums\TaskStatusEnum;
use BackedEnum;
use Database\Factories\TaskFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class TaskControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected string $url = '/api/v1/tasks';

    public function test_index_with_successful(): void
    {
        $user = UserFactory::new()->create();

        TaskFactory::new()->stateUser($user)->count(30)->create();

        $response = $this->actingAsUser($user)->getJson($this->url);

        $response->assertStatus(200);

        $this->assertPagination($response);
    }

    public function test_index_with_filter_from_status_pending(): void
    {
        $user = UserFactory::new()->create();

        TaskFactory::new()->stateUser($user)->stateStatus(TaskStatusEnum::PENDING)->count(10)->create();

        $filter = TaskStatusEnum::PENDING->value;

        $response = $this->actingAsUser($user)->getJson($this->url . '?status=' . $filter);

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

        $response = $this->actingAsUser($user)->getJson($this->url . '?status=' . $filter);

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

        $response = $this->actingAsUser($user)->getJson($this->url . '?status=' . $filter);

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

        $response = $this->actingAsUser($user)->getJson($this->url . '?page=' . $page . '&per_page=' . $perPage);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'current_page' => $page,
                'per_page' => $perPage,
            ]);

        $this->assertPagination($response);
    }

    public function test_all_tasks_with_successful(): void
    {
        $user = UserFactory::new()->create();

        TaskFactory::new()->stateUser($user)->count(30)->create();

        $response = $this->actingAsUser($user)->getJson($this->url . '/all');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            [
                'id',
                'title',
                'description',
                'status',
                'user',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function test_show_with_successful(): void
    {
        $user = UserFactory::new()->create();

        $task = TaskFactory::new()->stateUser($user)->create();

        $response = $this->actingAsUser($user)->getJson("$this->url/$task->id");

        $response->assertStatus(200);

        $response->assertJson([
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status->value,
            'user' => [
                'id' => $task->user->id,
                'name' => $task->user->name,
                'email' => $task->user->email,
            ],
            'created_at' => $task->created_at->toISOString(),
            'updated_at' => $task->updated_at->toISOString(),
        ]);
    }

    public function test_show_with_not_found(): void
    {
        $user = UserFactory::new()->create();

        $response = $this->actingAsUser($user)->getJson("$this->url/9999");

        $response->assertStatus(404);

        $response->assertJson([
            'message' => 'Task not found.',
            'status' => 404,
            'code' => 'RESOURCE_NOT_FOUND',
            'details' => [
                'id' => 9999,
            ],
        ]);
    }

    public function test_store_with_successful(): void
    {
        $user = UserFactory::new()->create();

        $form = TaskFactory::new()->make()->setAppends([])->toArray();

        unset($form['user_id']);

        $response = $this->actingAsUser($user)->postJson($this->url, $form);

        $task = Task::find($response->json('id'));

        $response->assertStatus(201);

        $response->assertJson([
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status->value,
        ]);

        $this->assertEquals($task->user_id, $user->id);
    }

    public function test_store_with_validation_error(): void
    {
        $user = UserFactory::new()->create();

        $form = [
            'status' => 'invalid_status',
        ];

        $response = $this->actingAsUser($user)->postJson($this->url, $form);

        $response->assertStatus(422);

        $response->assertJson([
            'message' => __('errors.validation'),
            'status' => 422,
            'code' => 'VALIDATION',
            'details' => [
                'title' => [
                    __('validation.required', ['attribute' => __('validation.attributes.task.title')]),
                ],
                'status' => [
                    __('validation.enum', ['attribute' => __('validation.attributes.task.status'), 'enum' => TaskStatusEnum::class]),
                ],
            ],
        ]);
    }

    public function test_update_with_successful(): void
    {
        $user = UserFactory::new()->create();

        $task = TaskFactory::new()->stateUser($user)->create();

        $form = TaskFactory::new()->make()->setAppends([])->toArray();

        unset($form['user_id']);

        $response = $this->actingAsUser($user)->putJson("$this->url/$task->id", $form);

        $task = Task::find($response->json('id'));

        $response->assertStatus(200);

        foreach ($form as $key => $value) {
            if ($task->{$key} instanceof BackedEnum) {
                $this->assertEquals($task->{$key}->value, $value);

                continue;
            }

            $this->assertEquals($task->{$key}, $value);
        }
    }

    public function test_update_with_not_found(): void
    {
        $user = UserFactory::new()->create();

        $response = $this->actingAsUser($user)->putJson("$this->url/9999", []);

        $response->assertStatus(404);

        $response->assertJson([
            'message' => 'Task not found.',
            'status' => 404,
            'code' => 'RESOURCE_NOT_FOUND',
            'details' => [
                'id' => 9999,
            ],
        ]);
    }

    public function test_update_without_data_change(): void
    {
        $user = UserFactory::new()->create();

        $task = TaskFactory::new()->stateUser($user)->create();

        $response = $this->actingAsUser($user)->putJson("$this->url/$task->id", $task->toArray());

        $response->assertStatus(200);

        $response->assertJson([
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status->value,
        ]);

        $this->assertEquals(1, DB::transactionLevel());
    }

    public function test_destroy_with_successful(): void
    {
        $user = UserFactory::new()->create();

        $task = TaskFactory::new()->stateUser($user)->create();

        $response = $this->actingAsUser($user)->deleteJson("$this->url/$task->id");

        $response->assertStatus(200);

        $response->assertJson(['message' => 'Task deleted successfully']);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_destroy_with_not_found(): void
    {
        $user = UserFactory::new()->create();

        $response = $this->actingAsUser($user)->deleteJson("$this->url/9999");

        $response->assertStatus(404);

        $response->assertJson([
            'message' => 'Task not found.',
            'status' => 404,
            'code' => 'RESOURCE_NOT_FOUND',
            'details' => [
                'id' => 9999,
            ],
        ]);
    }

    public function test_destroy_task_from_another_user(): void
    {
        $user = UserFactory::new()->create();

        $task = TaskFactory::new()->create();

        $response = $this->actingAsUser($user)->deleteJson("$this->url/$task->id");

        $response->assertStatus(404);

        $response->assertJson([
            'message' => 'Task not found.',
            'status' => 404,
            'code' => 'RESOURCE_NOT_FOUND',
            'details' => [
                'id' => $task->id,
            ],
        ]);
    }
}
