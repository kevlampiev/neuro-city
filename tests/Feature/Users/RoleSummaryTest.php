<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Carbon;

class RoleSummaryTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;
    
    //Функция выдает маршрут для роли
    public function routeForRoleSummary(string $roleName):string
    {
        $role =Role::where('slug', $roleName)->first();
        return route('roleSummary', ['role' => $role]);
    }


    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitRoleSummaryAsGuest(): void
    {
        $response = $this->get($this->routeForRoleSummary('urist'));

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /exrited
     */
    public function testVisitHomepageAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForRoleSummary('urist'));

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер, перенаправляет на /
     */
    public function testVisitUsersListAsSimpleUser(): void
    {
        $user = User::query()->where('is_superuser',"=",false)
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForRoleSummary('urist'));
        $response->assertStatus(302)->assertRedirectToRoute('home');
    }



    /**
     * Если я - суперюзекр с нормальным паролем, вижу все
     */
    public function testVisitHomepageAsSuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForRoleSummary('urist'));

        $response->assertStatus(200)
        ->assertSee('Карточка Роли')
        ->assertSee('Основная информация')
        ->assertSee('Разрешения роли')
        ->assertSee('Сотрудники, которым назначена данная роль');

    }
    
}

