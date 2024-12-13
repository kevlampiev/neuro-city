<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Carbon;

class TasksAddTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;
    

    
    public function createTaskForUser(User $user, User $performer=null):void
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
    }
   

    /**
     * Если я зарегистриован и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitTaskListAsExpiredPassord(): void
    {
        $user = User::query()->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get(route('addTask'));

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я - юзер с нормальным паролем, вижу свои задачи
     */
    public function testVisitUserTasksList(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->inRandomOrder()->first();
        $route = route('addTask', ['user'=>$user]);

        $response = $this->actingAs($user)->get($route);

        $response->assertStatus(200)
            ->assertSee('Добавить новую задачу')
            ->assertSee('Добавить новую задачу')
            ->assertSee('Родительская задача')
            ->assertSee('Дополнительная информация');
            
    }

    public function testPostMethodForAddingTaskWithProperUser():void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->inRandomOrder()->first();
        $taskData = [
            'user_id' => $user->id,
            'task_performer_id' => $user->id,
            'start_date' => now(),
            'due_date' => Carbon::now()->addDays(7),
            'subject' => "Добавлено тестом post",
            'importance' => 'medium',
            'description' => "Добавлено тестом",
        ];

         // Выполняем POST-запрос для создания проекта
         $response = $this->actingAs($user)->post(route('addTask'), $taskData);

         // Ожидаем, что будет выполнен редирект после успешного добавления
         $response->assertStatus(302)->assertRedirect(route('userTasks', ['user'=>$user]));
 
         // Проверяем, что проект был добавлен в базу данных
         $this->assertDatabaseHas('tasks', [
             'subject' => "Добавлено тестом post",
             'description' => "Добавлено тестом",
         ]);
    }

    public function testPostMethodForAddingTaskWithNewUser():void
    {
        $user = User::query()->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $taskData = [
            'user_id' => $user->id,
            'task_performer_id' => $user->id,
            'start_date' => now(),
            'due_date' => Carbon::now()->addDays(7),
            'subject' => "Добавлено тестом post",
            'importance' => 'medium',
            'description' => "Добавлено тестом",
        ];

         // Выполняем POST-запрос для создания проекта
         $response = $this->actingAs($user)->post(route('addTask'), $taskData);

         // Ожидаем, что будет выполнен редирект после успешного добавления
         $response->assertStatus(302)->assertRedirectToRoute('password.expired');
 
    }

    public function testPostMethodForAddingTaskWithUnathorizedUser():void
    {
        $user = User::query()->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $taskData = [
            'user_id' => $user->id,
            'task_performer_id' => $user->id,
            'start_date' => now(),
            'due_date' => Carbon::now()->addDays(7),
            'subject' => "Добавлено тестом post",
            'importance' => 'medium',
            'description' => "Добавлено тестом",
        ];

         // Выполняем POST-запрос для создания проекта
         $response = $this->post(route('addTask'), $taskData);

         // Ожидаем, что будет выполнен редирект после успешного добавления
         $response->assertStatus(302)->assertRedirectToRoute('login');
 
    }
}