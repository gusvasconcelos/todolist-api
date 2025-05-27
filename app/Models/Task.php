<?php

namespace App\Models;

use App\Traits\UserRelation;
use App\Enums\TaskStatusEnum;
use App\Models\ModelAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends ModelAbstract
{
    use UserRelation;
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
    ];

    protected $casts = [
        'status' => TaskStatusEnum::class,
        'user_id' => 'integer',
    ];

    protected $attributes = [
        'status' => TaskStatusEnum::PENDING,
    ];

    public function scopeStatus(Builder $query, TaskStatusEnum $status): Builder
    {
        return $query->where('status', $status->value);
    }
}
