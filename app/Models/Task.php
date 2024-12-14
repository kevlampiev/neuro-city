<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

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

}
