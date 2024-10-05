<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id'; // Это по умолчанию, но можно указать явно
    public $incrementing = true; // Автоинкремент
    protected $fillable = ['user_id', 'description']; // Поля, которые заполняются

    public function agreements(): BelongsToMany
    {
        return $this->belongsToMany(Agreement::class, 'agreement_note', 'note_id', 'agreement_id');
    }


    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'note_project', 'note_id', 'project_id');
    }
    
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
