<?php

namespace App\Services\Task;

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
}
