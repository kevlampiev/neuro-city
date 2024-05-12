<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AddUserTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;
    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitAdduserpageAsGuest(): void
    {
        $response = $this->get(route('addUser'));

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /exrited
     */
    public function testVisitAdduserpageAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get(route('addUser'));

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
        $response = $this->actingAs($user)->get(route('addUser'));

        $response->assertStatus(302)->assertRedirectToRoute('home');
    }



    /**
     * Если я - суперюзекр с нормальным паролем, вижу все
     */
    public function testVisitAdduserpageAsSuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get(route('addUser'));

        $response->assertStatus(200)
        ->assertSee('Имя пользователя')
        ->assertSee('Добавить');
    }



     /**
     * Суперюзер с нормальным паролем может добавить нового пользователя
     */
    public function testAddANewUserAsSuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->post(route('addUser'), 
        [
            'name' => 'Added By Test',
            'email' => 'added@by.test',
            'password' => Hash::make('password'),
            'birthday' =>fake()->date(),
            'phonenumber' => 11111111111,
        ]);

        
        $response->assertStatus(302)
        ->assertRedirectToRoute('users')
        ->assertSessionHas('message', 'Добавлен новый пользователь');
    }


    /**
     * Суперюзер с нормальным паролем может модифицировать пользователя
     */
    public function testEditUserAsSuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $userToModify = User::query()->where('id',"<>",$user->id)->first();
        $response = $this->actingAs($user)->post(route('editUser', ['user'=>$userToModify]), 
        [
            'name' => 'Edited By Test',
            'email' => 'edited@by.test',
            'birthday' =>fake()->date(),
            'phonenumber' => 11111111111,
        ]);

        
        $response->assertStatus(302)
        ->assertRedirectToRoute('users')
        ->assertSessionHas('message', 'Информация о пользователе обновлена');
    }

}