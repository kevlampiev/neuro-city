<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;
    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitHomepageAsGuest(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если пароль изменялся давно, перенаправляет на /exrited
     */
    public function testVisitHomepageAsNewUser(): void
    {
        $user = User::query()->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если с авторизаией все в порядке, вижу страницу, но без пунктов меню для суперюзера
     */
    public function testVisitHomepageAsSimpleUser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',false)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200)
        ->assertDontSee('Пользователи')
        ->assertDontSee('Роли в системе');
    }

    /**
     * Если я - суперюзекр, вижу все
     */
    public function testVisitHomepageAsSuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200)
        ->assertSee('Пользователи')
        ->assertSee('Роли в системе');
    }
}
