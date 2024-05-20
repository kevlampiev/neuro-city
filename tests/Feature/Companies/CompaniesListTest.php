<?php

namespace Tests\Feature\Companies;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;    
use Illuminate\Support\Carbon;

class CompaniesListTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;
    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitCompaniesListAsGuest(): void
    {
        $response = $this->get(route('companies'));

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitCompaniesListAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get(route('companies'));

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер , перенаправляет на 404
     */
    public function testVisitCompaniesListAsSimpleUser(): void
    {
        $user = User::query()->where('is_superuser',"=",false)
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->inRandomOrder()->first();
        $response = $this->actingAs($user)->get(route('companies'));

        $response->assertStatus(302);
    }


    /**
     * Если я - суперюзер с нормальным паролем, вижу все
     */
    public function testVisitCompaniesListAsSuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get('companies');

        $response->assertStatus(200)
        ->assertSee('Компании группы')
        ->assertSee('Наименование')
        ->assertSee('ИНН')
        ->assertSee('ОГРН')
        ;
    }

}

