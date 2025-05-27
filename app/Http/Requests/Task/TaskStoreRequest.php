<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskStatusEnum;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:4000'],
            'status' => ['nullable', 'string', Rule::enum(TaskStatusEnum::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => __('validation.attributes.task.title'),
            'description' => __('validation.attributes.task.description'),
            'status' => __('validation.attributes.task.status'),
        ];
    }
}
