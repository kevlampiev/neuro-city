<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Carbon;

abstract class TasksMessageTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;
    protected $task;
    protected $route;

    // Инициализация задачи
    protected function setUp(): void
    {
        parent::setUp();
        $this->createTask();
        $this->createUrl();
    }

    // Создание задачи
    protected function createTask(): void
    {
        $user = User::where('password_changed_at', '>', Carbon::now()->addDay(-30))->inRandomOrder()->first();
        $performer = User::where('id', '<>', $user->id)->where('password_changed_at', '>', Carbon::now()->addDay(-30))->inRandomOrder()->first();

        $task = new Task();
        $task->user_id = $user->id;
        $task->task_performer_id = $performer->id;
        $task->start_date = now();
        $task->due_date = Carbon::now()->addDays(7);
        $task->subject = "Добавлено тестом";
        $task->importance = 'medium';
        $task->description = "Добавлено тестом";
        $task->save();

        $this->task = $task;
    }

    // Абстрактный метод для создания URL (переопределяется в дочерних классах)
    abstract protected function createUrl(): void;
}
