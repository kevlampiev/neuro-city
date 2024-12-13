<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Carbon;

class TasksListTest extends TestCase
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
        $response = $this->actingAs($user)->get(route('userTasks', ['user'=>$user]));

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я - юзер с нормальным паролем, вижу свои задачи
     */
    public function testVisitUserTasksList(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->inRandomOrder()->first();
        $route = route('userTasks', ['user'=>$user]);

        $this->createTaskForUser($user);
        $response = $this->actingAs($user)->get($route);

        $response->assertStatus(200)
            ->assertSee('Задачи, назначенные мне')
            ->assertSee('Добавлено тестом');

    }

}

