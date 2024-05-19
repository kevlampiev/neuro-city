<?php

namespace Tests\Feature\Companies;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;    
use App\Models\Company;
use Illuminate\Support\Carbon;

class CompanyEditTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    //Вспомогательная функция, которая возращает подопытный rout
    public function routeForCompanyEdit():string
    {
        $Company =Company::where('our_company', true)->inRandomOrder()->first();
        return route('editCompany', ['company' => $Company]);
    }

    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitCompanyEditAsGuest(): void
    {
        $response = $this->get($this->routeForCompanyEdit());

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitCompanyEditAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForCompanyEdit());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и не юрист или экономист, перенаправляет на /
     */
    public function testVisitCompanyEditAsASimpleUser(): void
    {
        $user = User::query()->where('is_superuser',"=",false)
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        // ->whereNotIn('id', function($query){
        //     $query->select('user_id')->from('users_roles');
        // })
        ->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForCompanyEdit());

        $response->assertStatus(302);
    }


    /**
     * Если я - суперюзер с нормальным паролем, вижу все
     */
    public function testVisitCompanyEditAsASuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForCompanyEdit());

        $response->assertStatus(200)
        ->assertSee('Редактирование компании')
        ->assertSee('Руководитель');
    }

}

