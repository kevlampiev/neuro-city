<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'task_performer_id');
    }

    public function subTasks(bool $hideClosedTasks = true): HasMany
    {
        $result = $this->hasMany(Task::class, 'parent_task_id');
        if ($hideClosedTasks) {
            return $result->where('terminate_date', '=', null);
        } else {
            return $result;
        }
    }

    public function parentTask(): HasOne
    {
        return $this->HasOne(Task::class, 'parent_task_id', 'id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getAllInterestedUsers(): Collection
    {
        $userIds = collect();

        // Добавляем автора задачи
        $userIds->push($this->user_id);

        // Добавляем исполнителя задачи
        $userIds->push($this->task_performer_id);

        // Добавляем всех подписчиков
        $followerIds = $this->followers()->pluck('user_id');
        $userIds = $userIds->merge($followerIds);

        // Убираем дубли
        return $userIds->unique();
    }

    public function agreements(): BelongsToMany
    {
        return $this->belongsToMany(Agreement::class, 'agreement_task');
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_task');
    }

}
