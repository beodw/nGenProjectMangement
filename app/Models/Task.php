<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Validation\Rule;
use App\Enums\TaskEnum;

class Task extends Model
{
    use HasFactory;

    public static $task_states = [
        'done',
        'hold',
        'enqueue',
        'design',
        'open',
        'completed',
        'closed',
    ];

    protected static $rules = [
        'title' => ['bail', 'required', 'string'],
        'description' => ['bail', 'nullable'],
        'deadline' => ['bail', 'required', 'date_format:Y-m-d'],
        'completion_date' => ['bail', 'prohibited'],
        'parent_task_id' => ['bail', 'nullable', 'numeric', 'integer'],
    ];

    public static function getValidationRulesForCreation(){
        return [
            'title' => ['bail', 'required', 'string'],
            'description' => ['string', 'nullable'],
            'deadline' => ['bail', 'required', 'date_format:Y-m-d'],
            'completion_date' => ['bail', 'prohibited'],
            'parent_task_id' => ['bail', 'nullable', 'numeric', 'integer', 'exists:tasks,id'],
            'task_state' => ['bail', 'prohibited'],
            'deleted' => 'nullable'
        ];
    }

    public static function getValidationRulesForUpdate(){
        return [
        'title' => 'bail|string',
        'description' => 'bail|string',
        'deadline' => ['bail', 'date'],
        'completion_date' => ['bail', 'date'],
        'task_state' => ['string', Rule::in(Static::$task_states)],
        ];
    }

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'completion_date',
        'parent_task_id',
        'task_state',
    ];

    protected $casts = [
        'deleted' => 'boolean',
    ];
}
