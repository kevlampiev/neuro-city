<?php

namespace Tests\Feature\Companies;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;    
use Illuminate\Support\Carbon;

class ProjectsListTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    public function routeForProjectList():string
    {
        return route('projects.index');
    }

    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitProjectsListAsGuest(): void
    {
        $response = $this->get($this->routeForProjectList());

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitProjectsListAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForProjectList());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и не имею разрешения s-Projects, перенаправляет на 404
     */
    public function testVisitProjectsListAsSimpleUserWithNoPermission(): void
    {
        // Выбираем пользователя, который не является суперпользователем, изменил пароль в последние 30 дней
        // и не имеет разрешения 's-Projects'.
        $user = User::query()
        ->where('is_superuser', '=', false)
        ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
        ->inRandomOrder()
        ->get()
        ->first(function($user) {
            return !$user->hasPermissionTo('s-projects');
        });

        // Проверяем, что пользователь выбран корректно и не имеет разрешения 's-Projects'.
        $this->assertNotNull($user, 'Не удалось найти пользователя, соответствующего условиям.');
        $this->assertFalse($user->hasPermissionTo('s-projects'));

        // Действуем от имени выбранного пользователя и проверяем, что доступ к списку договоров запрещен.
        $response = $this->actingAs($user)->get($this->routeForProjectList());

        // Ожидаем перенаправление, так как доступ к списку договоров запрещен.
        $response->assertStatus(404);
    }

    /**
     * Если я не суперюзер и не имею разрешения s-Projects, перенаправляет на 404
     */
    public function testVisitProjectsListAsSimpleUserWithCorrectPermission(): void
    {
        // Получаем список всех пользователей, которые не являются суперпользователями и изменили пароль в последние 30 дней
    $users = User::query()
    ->where('is_superuser', '=', false)
    ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
    ->inRandomOrder()
    ->get();

        // Фильтруем пользователей, у которых есть разрешение 's-Projects'
        $user = $users->first(function($user) {
            return $user->hasPermissionTo('s-projects');
        });

        // Проверка, был ли найден пользователь, соответствующий условиям
        $this->assertNotNull($user, 'Не удалось найти пользователя, соответствующего условиям.');

        if ($user !== null) {
            // Проверяем, что у найденного пользователя есть разрешение 's-Projects'
            $this->assertTrue($user->hasPermissionTo('s-projects'));

            // Действуем от имени выбранного пользователя и проверяем, что доступ к списку договоров разрешен.
            $response = $this->actingAs($user)->get($this->routeForProjectList());

            // Ожидаем успешный ответ, так как доступ к списку договоров должен быть разрешен.
            $response->assertStatus(200)
            ->assertSee('Проекты')
            ->assertSee('Название')
            ->assertSee('Описание')
            ->assertSee('Дата старта')
            ->assertSee('Дата окончания');

        } else {
            // Если пользователь не найден, выводим отладочную информацию
            $this->fail('Не удалось найти пользователя, соответствующего условиям. Проверьте базу данных на наличие пользователей, которые имеют разрешение "s-Projects".');
        }
 
    }

    /**
     * Если я - суперюзер с нормальным паролем, вижу все
     */
    public function testVisitProjectsListAsSuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForProjectList());
        // $project = Project::query()->orderByDesc('date_open')->first();

        $response->assertStatus(200)
            ->assertSee('Проекты')
            ->assertSee('Название')
            ->assertSee('Описание')
            ->assertSee('Дата старта')
            ->assertSee('Дата окончания');

    }

}

