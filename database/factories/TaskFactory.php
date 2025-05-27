<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(TaskStatusEnum::cases()),
            'user_id' => User::factory(),
        ];
    }

    public function stateUser(User|null $user = null): self
    {
        return $this->state([
            'user_id' => $user->id ?? User::factory(),
        ]);
    }

    public function stateStatus(TaskStatusEnum $status): self
    {
        return $this->state([
            'status' => $status->value,
        ]);
    }
}
