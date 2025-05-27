<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskStatusEnum;
use Illuminate\Validation\Rule;
use App\Http\Requests\PaginationRequestAbstract;

class TaskIndexRequest extends PaginationRequestAbstract
{
    public function setAdditionalRules(): array
    {
        return [
            'status' => ['nullable', 'string', Rule::enum(TaskStatusEnum::class)],
        ];
    }
}
