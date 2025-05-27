<?php

namespace App\Services\Task;

use App\Exceptions\NotFoundException;
use App\Models\Task;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    public function __construct(
        protected Task $task
    ) {
        //
    }

    public function index(Collection $data): LengthAwarePaginator
    {
        $tasksBuilder = $this->task->with('user');

        $statusFilter = $data->get('status');

        if ($statusFilter) {
            $tasksBuilder->whereStatus($statusFilter);
        }

        return $tasksBuilder
            ->paginate(
                perPage: $data->get('per_page', 10),
                page: $data->get('page', 1),
            );
    }

    public function all(): Collection
    {
        return $this->task->with('user')->get();
    }

    public function show(int $id): Task
    {
        $task = $this->task->with('user')->find($id);

        if (!$task) {
            throw new NotFoundException(
                __('errors.resource_not_found', ['resource' => 'Task']),
                'RESOURCE_NOT_FOUND',
                ['id' => $id]
            );
        }

        return $task;
    }

    public function store(Collection $data): Task
    {
        return $this->task->create($data->toArray());
    }

    public function update(int $id, Collection $data): Task
    {
        $task = $this->task->find($id);

        if (!$task) {
            throw new NotFoundException(
                __('errors.resource_not_found', ['resource' => 'Task']),
                'RESOURCE_NOT_FOUND',
                ['id' => $id]
            );
        }

        $data = $data->diff($task->toArray());

        if ($data->isEmpty()) {
            return $task;
        }

        $task->update($data->toArray());

        return $task;
    }

    public function destroy(int $id): bool
    {
        $task = $this->task->find($id);

        if (!$task) {
            throw new NotFoundException(
                __('errors.resource_not_found', ['resource' => 'Task']),
                'RESOURCE_NOT_FOUND',
                ['id' => $id]
            );
        }

        return $task->delete();
    }
}
