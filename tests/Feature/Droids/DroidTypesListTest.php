<?php

namespace Tests\Feature\Droids;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;    
use Illuminate\Support\Carbon;

class DroidTypesListTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    public function routeForDroidTypesList():string
    {
        return route('droidTypes.index');
    }

    
    /**
     * Незарегистрированный пользователь перенаправляется на /login
     */
    public function testVisitDroidTypesListAsGuest(): void
    {
        $response = $this->get($this->routeForDroidTypesList());
        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitDroidTypesListAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForDroidTypesList());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и не имею разрешения s-droid_types, перенаправляет на 404
     */
    public function testVisitDroidTypesListAsSimpleUserWithNoPermission(): void
    {
        $user = User::query()
        ->where('is_superuser', '=', false)
        ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
        ->inRandomOrder()
        ->get()
        ->first(function($user) {
            return !$user->hasPermissionTo('s-droid_types');
        });

        // Проверяем, что пользователь выбран корректно и не имеет разрешения 's-droid_types'.
        $this->assertNotNull($user, 'Не удалось найти пользователя, соответствующего условиям.');
        $this->assertFalse($user->hasPermissionTo('s-droid_types'));

        // Действуем от имени выбранного пользователя и проверяем, что доступ к списку договоров запрещен.
        $response = $this->actingAs($user)->get($this->routeForDroidTypesList());

        // Ожидаем перенаправление, так как доступ к списку договоров запрещен.
        $response->assertStatus(404);
    }

    /**
     * Если я не суперюзер и не имею разрешения s-droid_types, перенаправляет на 404
     */
    public function testVisitDroidTypesListAsSimpleUserWithCorrectPermission(): void
    {
        // Получаем список всех пользователей, которые не являются суперпользователями и изменили пароль в последние 30 дней
    $users = User::query()
    ->where('is_superuser', '=', false)
    ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
    ->inRandomOrder()
    ->get();

        // Фильтруем пользователей, у которых есть разрешение 's-droid_types'
        $user = $users->first(function($user) {
            return $user->hasPermissionTo('s-droid_types');
        });

        // Проверка, был ли найден пользователь, соответствующий условиям
        $this->assertNotNull($user, 'Не удалось найти пользователя, соответствующего условиям.');

        if ($user !== null) {
            // Проверяем, что у найденного пользователя есть разрешение 's-droid_types'
            $this->assertTrue($user->hasPermissionTo('s-droid_types'));

            // Действуем от имени выбранного пользователя и проверяем, что доступ к списку договоров разрешен.
            $response = $this->actingAs($user)->get($this->routeForDroidTypesList());

            // Ожидаем успешный ответ, так как доступ к списку договоров должен быть разрешен.
            $response->assertStatus(200)
            ->assertSee('Типы Дроидов')
            ->assertSee('Название')
            ->assertSee('Описание');

        } else {
            // Если пользователь не найден, выводим отладочную информацию
            $this->fail('Не удалось найти пользователя, соответствующего условиям. Проверьте базу данных на наличие пользователей, которые имеют разрешение "s-droid_types".');
        }
 
    }

    /**
     * Если я - суперюзер с нормальным паролем, вижу все
     */
    public function testVisitDroidTypesListAsSuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForDroidTypesList());
        // $project = Project::query()->orderByDesc('date_open')->first();

        $response->assertStatus(200)
            ->assertSee('Типы Дроидов')
            ->assertSee('Название')
            ->assertSee('Описание');

    }

}

