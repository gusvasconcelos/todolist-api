<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Services\Task\TaskService;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\PaginationResource;
use App\Http\Requests\Task\TaskIndexRequest;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskUpdateRequest;

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

    public function show(int $id): JsonResponse
    {
        $task = $this->taskService->show($id);

        return response()->json(new TaskResource($task));
    }

    public function store(TaskStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $task = $this->taskService->store(collect($validated));

        return response()->json(new TaskResource($task), Response::HTTP_CREATED);
    }

    public function update(TaskUpdateRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        $task = $this->taskService->update($id, collect($validated));

        return response()->json(new TaskResource($task));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->taskService->destroy($id);

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
