<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\Task\TaskService;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\PaginationResource;
use App\Http\Requests\Task\TaskIndexRequest;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {
        //
    }

    public function index(TaskIndexRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $tasks = $this->taskService->index(collect($validated));

        return response()->json(new PaginationResource($tasks, TaskResource::class));
    }

    public function all(): JsonResponse
    {
        $tasks = $this->taskService->all();

        return response()->json(TaskResource::collection($tasks));
    }
}
