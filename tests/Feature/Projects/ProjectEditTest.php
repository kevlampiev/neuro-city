<?php

namespace Tests\Feature\Projects;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;    
use Illuminate\Support\Carbon;

class ProjectEditTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    //Вспомогательная функция, которая возращает подопытный rout
    public function routeForProjectEdit():string
    {
        $project =Project::query()->inRandomOrder()->first();
        return route('projects.edit', ['id' => $project->id]);
    }

    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitProjectEditAsGuest(): void
    {
        $response = $this->get($this->routeForProjectEdit());

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitProjectEditAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForProjectEdit());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и у меня нет разрешения на редактирование договора - меня посылают на 404/
     */
    public function testVisitProjectEditAsASimpleUserWithNoPermission(): void
{
    // Получаем список всех пользователей, которые не являются суперпользователями и изменили пароль в последние 30 дней
    $users = User::query()
        ->where('is_superuser', '=', false)
        ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
        ->inRandomOrder()
        ->get();

    // Фильтруем пользователей, у которых нет разрешения 'e-projects'
    $user = $users->first(function($user) {
        return !$user->hasPermissionTo('e-projects');
    });

    // Проверяем, был ли найден пользователь, соответствующий условиям
    $this->assertNotNull($user, 'Не удалось найти пользователя, соответствующего условиям.');

    if ($user !== null) {
        // Действуем от имени выбранного пользователя и проверяем, что доступ к редактированию договора запрещен
        $response = $this->actingAs($user)->get($this->routeForProjectEdit());

        // Ожидаем, что доступ будет запрещен, и будет возвращен статус 404 или 403 (в зависимости от логики)
        $response->assertStatus(404);
    } else {
        // Если пользователь не найден, выводим отладочную информацию
        $this->fail('Не удалось найти пользователя, соответствующего условиям. Проверьте базу данных на наличие пользователей, которые не имеют разрешения "e-Projects".');
    }
}


    /**
     * Если я не суперюзер и у меня есть разрешения на редактирование договора - открываю окно редактирования/
     */
public function testVisitProjectEditAsUserWithGoodPermission(): void
{
    // Получаем список всех пользователей, которые не являются суперпользователями и изменили пароль в последние 30 дней
    $users = User::query()
        ->where('is_superuser', '=', false)
        ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
        ->inRandomOrder()
        ->get();

    // Фильтруем пользователей, у которых есть разрешение 'e-projects'
    $user = $users->first(function ($user) {
        return $user->hasPermissionTo('e-projects');
    });

    // Проверяем, был ли найден пользователь, соответствующий условиям
    $this->assertNotNull($user, 'Не удалось найти пользователя с разрешением "e-projects".');

    if ($user !== null) {
        // Действуем от имени выбранного пользователя и проверяем, что доступ к редактированию договора разрешен
        $response = $this->actingAs($user)->get($this->routeForProjectEdit());

        // Ожидаем успешный ответ с кодом 200
        $response->assertStatus(200);
    } else {
        // Если пользователь не найден, выводим отладочную информацию
        $this->fail('Не удалось найти пользователя, соответствующего условиям. Проверьте базу данных на наличие пользователей с разрешением "e-projects".');
    }
}

    /**
     * Если я - суперюзер с нормальным паролем, вижу все
     */
    public function testVisitProjectEditAsASuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForProjectEdit());

        $response->assertStatus(200)
        ->assertSee('Изменение проекта')
        ->assertSee('Название проекта')
        ->assertSee('Дата начала')
        ->assertSee('Дата окончания')
        ->assertSee('Описание');
    }


    public function testEditProjectSuccessfully(): void
    {
        // Получаем пользователя с правами на редактирование проекта
        $user = User::query()
            ->where('is_superuser', '=', true) // Или с конкретным разрешением
            ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
            ->inRandomOrder()
            ->first();
    
        // Убеждаемся, что пользователь найден
        $this->assertNotNull($user, 'Не удалось найти пользователя с правами на редактирование проекта.');
    
        // Получаем случайный проект
        $project = Project::inRandomOrder()->first();
    
        // Данные для обновления проекта
        $updatedData = [
            'name' => 'Обновленное название проекта',
            'description' => 'Обновленное описание проекта',
            'date_open' => '2024-07-01',
        ];
    
        // Отправляем PUT или PATCH запрос для обновления проекта
        $response = $this->actingAs($user)->put(route('projects.update', ['id' => $project->id]), $updatedData);
    
        // Ожидаем, что будет выполнен редирект после успешного обновления
        $response->assertStatus(302)->assertRedirect(route('projects.index'));
    
        // Проверяем, что проект был обновлен в базе данных
        $this->assertDatabaseHas('projects', [
            'id' => $project->id, // Убедимся, что это тот же проект
            'name' => 'Обновленное название проекта',
            'description' => 'Обновленное описание проекта',
            'date_open' => '2024-07-01',
        ]);
    }
    
}

