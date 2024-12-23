<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Carbon;

class TasksCloseTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;
    

    
    public function createTaskForUser(User $user, User $performer=null): Task
    {
        $task = new Task();
        $task->user_id = $user->id;
        $task->task_performer_id = $performer?$performer->id:$user->id;
        $task->start_date = now();
        $task->due_date = Carbon::now()->addDays(7);
        $task->subject = "Добавлено тестом";
        $task->importance = 'medium';
        $task->description = "Добавлено тестом";
        $task->save();

        return $task;
    }
   

    public function testPerfomerCanntCloseTask():void
    {
        $user = User::where('password_changed_at',">",Carbon::now()->addDay(-30))->inRandomOrder()->first();
        $performer = User::where('password_changed_at',">",Carbon::now()->addDay(-30))
            ->where('id', '<>', $user->id)
            ->inRandomOrder()->first();
        $task = $this->createTaskForUser($user, $performer);

        $response = $this->actingAs($performer)->get('markTaskAsDone', ['task' => $task]);
        $response->assertStatus(404);
    }

    public function testUserCanCloseTask():void
    {
        $user = User::where('password_changed_at', ">", Carbon::now()->addDay(-30))->inRandomOrder()->first();
        $performer = User::where('password_changed_at', ">", Carbon::now()->addDay(-30))
            ->where('id', '<>', $user->id)
            ->inRandomOrder()->first();
    
        $task = $this->createTaskForUser($user, $performer);
    
        $this->assertDatabaseHas('tasks', ['user_id' => $user->id, 'task_performer_id' => $performer->id]);
    
        // Выполняем запрос
        $response = $this->actingAs($user)->get(route('markTaskAsDone', ['task' => $task->id]));
    
        // Обновляем объект задачи из базы данных
        $task->refresh();
    
        // Проверяем статус задачи
        $this->assertEquals('complete', $task->terminate_status, 'Terminate status не обновился');
    
        // Проверяем, что задача обновлена в базе данных
        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'task_performer_id' => $performer->id,
            'terminate_status' => 'complete',
        ]);
    
        // Проверяем ответ
        $response->assertStatus(302);    }
    

        public function testPerfomerCannotCancelTask(): void
        {
            // Создание пользователя и исполнителя
            $user = User::where('password_changed_at', ">", Carbon::now()->addDay(-30))
                ->inRandomOrder()
                ->first();
            $performer = User::where('password_changed_at', ">", Carbon::now()->addDay(-30))
                ->where('id', '<>', $user->id)
                ->inRandomOrder()
                ->first();

            // Создание задачи
            $task = $this->createTaskForUser($user, $performer);

            // Проверяем, что задача создана в базе данных
            $this->assertDatabaseHas('tasks', [
                'user_id' => $user->id,
                'task_performer_id' => $performer->id,
            ]);

            // Выполняем запрос на отмену задачи исполнителем
            $response = $this->actingAs($performer)->get(route('markTaskAsCanceled', ['task' => $task->id]));

            // Обновляем объект задачи из базы данных
            $task->refresh();

            // Проверяем, что terminate_status не изменился (остался null)
            $this->assertNull($task->terminate_status, 'Terminate status изменился, но не должен был.');

            // Проверяем, что статус ответа соответствует ожидаемому (например, 404)
            $response->assertStatus(302);

            // Дополнительно: проверяем, что запись в базе данных не изменилась
            $this->assertDatabaseHas('tasks', [
                'id' => $task->id,
                'user_id' => $user->id,
                'task_performer_id' => $performer->id,
                'terminate_status' => null,
            ]);
        }

    
        public function testUserCanCancelTask():void
        {
            $user = User::where('password_changed_at', ">", Carbon::now()->addDay(-30))->inRandomOrder()->first();
            $performer = User::where('password_changed_at', ">", Carbon::now()->addDay(-30))
                ->where('id', '<>', $user->id)
                ->inRandomOrder()->first();
        
            $task = $this->createTaskForUser($user, $performer);
        
            $this->assertDatabaseHas('tasks', ['user_id' => $user->id, 'task_performer_id' => $performer->id]);
        
            // Выполняем запрос
            $response = $this->actingAs($user)->get(route('markTaskAsCanceled', ['task' => $task->id]));
        
            // Обновляем объект задачи из базы данных
            $task->refresh();
        
            // Проверяем статус задачи
            $this->assertEquals('cancel', $task->terminate_status, 'Terminate status не обновился');
        
            // Проверяем, что задача обновлена в базе данных
            $this->assertDatabaseHas('tasks', [
                'user_id' => $user->id,
                'task_performer_id' => $performer->id,
                'terminate_status' => 'cancel',
            ]);
        
            // Проверяем ответ
            $response->assertStatus(302);    }

}