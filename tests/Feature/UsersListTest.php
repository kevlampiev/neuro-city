<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Carbon;

class UsersListTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;
    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitHomepageAsGuest(): void
    {
        $response = $this->get('/users');

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /exrited
     */
    public function testVisitHomepageAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get('/users');

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
        $response = $this->actingAs($user)->get(route('users'));

        $response->assertStatus(302)->assertRedirectToRoute('home');
    }



    /**
     * Если я - суперюзекр с нормальным паролем, вижу все
     */
    public function testVisitHomepageAsSuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get('users');

        $response->assertStatus(200)
        ->assertSee('Пользователи системы')
        ->assertSee('Добавить пользователя')
        ->assertSee('Роли в системе');
    }
}

