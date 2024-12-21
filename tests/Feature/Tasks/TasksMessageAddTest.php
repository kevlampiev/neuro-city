<?php

namespace Tests\Feature\Tasks;

use App\Models\Task;

class TasksMessageAddTest extends TasksMessageTest
{
    // Создание URL для маршрута
    protected function createUrl(): void
    {
        $this->route = (string) route('addTaskMessage', ['task' => $this->task->id]);
    }

    public function testAddMessageUnauthorized()
    {
        $response = $this->get($this->route);
        $response->assertStatus(302)->assertRedirectToRoute('login');
    }


    public function testAddMessageAuthorized()
    {
        $response = $this->actingAs($this->task->performer)->get($this->route);
        $response->assertStatus(200)
        ->assertSee('Новое сообщение')
        ->assertSee($this->task->subject)
        ->assertSee('Изменить')
        ->assertSee('Отмена');
    }
    
    public function testPostMessageUnauthorized()
    {
        $data = [
            'user_id'=>$this->task->user->id,
            'task_id'=>$this->task->id,
            'description' => 'Добавлено тестом на добавление комментария неавторизованным пользователем'
            ];
        $response = $this->post($this->route, $data);
        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    public function testPostMessageAuthorized()
    {
        $data = [
            'user_id'=>$this->task->user->id,
            'task_id'=>$this->task->id,
            'description' => 'Добавлено тестом на добавление комментария неавторизованным пользователем'
            ];
        $response = $this->actingAs($this->task->performer)->post($this->route, $data);

        // $response->assertStatus(302) 
        // ->assertSee($this->task->subject);
        
        $this->assertDatabaseHas('messages', [
            'task_id' => $this->task->id,
            'description' => 'Добавлено тестом на добавление комментария неавторизованным пользователем',
        ]);

        $response2 = $this->actingAs($this->task->user)->get('home');
        $response2->assertSee('Получен комментарий по задаче');
    }    
}
