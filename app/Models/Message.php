<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parentMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'id', 'reply_to_message_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Message::class, 'reply_to_message_id', 'id');
    }

    // Аксессор для вычисляемого поля
    public function getRootTaskAttribute()
    {
        $message = $this;

        // Поднимаемся по цепочке сообщений до тех пор, пока не найдем task_id
        while ($message && !$message->task_id) {
            $message = $message->parentMessage;
        }

        // Если нашли сообщение с task_id, возвращаем связанную модель Task
        return $message ? $message->task : null;
    }


    /**
     * Связь с документами.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'document_message');
    }
}
