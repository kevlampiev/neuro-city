<?php

namespace Tests\Feature\Counterparties;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;    
use App\Models\Company;
use Illuminate\Support\Carbon;

class CounterpartySummaryTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    //Вспомогательная функция, которая возращает подопытный rout
    public function routeForCounterpartySummary():string
    {
        $counterparty =Company::where('our_company', false)->inRandomOrder()->first();
        return route('counterpartySummary', ['counterparty' => $counterparty]);
    }

    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitCounterpartySummaryAsGuest(): void
    {
        $response = $this->get($this->routeForCounterpartySummary());

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitCounterpartySummaryAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForCounterpartySummary());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и не юрист или экономист, перенаправляет на /
     */
    public function testVisitCounterpartySummaryAsASimpleUser(): void
    {
        $user = User::query()->where('is_superuser',"=",false)
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->whereNotIn('id', function($query){
            $query->select('user_id')->from('users_roles');
        })
        ->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForCounterpartySummary());

        $response->assertStatus(404);
    }


    /**
     * Если я - суперюзер с нормальным паролем, вижу все
     */
    public function testVisitCounterpartySummaryAsASuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForCounterpartySummary());

        $response->assertStatus(200)
        ->assertSee('Карточка контрагента')
        ->assertSee('Основные данные')
        ->assertSee('Сотрудники контрагента')
        ->assertSee('Заметки по контрагенту');
    }

    /**
     * Если я - юрист с нормальным паролем, вижу все
     */
    public function testVisitCounterpartySummaryAsALawyer(): void
    {
        $user = User::whereHas('roles', function ($query) {
            $query->where('slug', 'urist');
        })
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->first();
        
        $response = $this->actingAs($user)->get($this->routeForCounterpartySummary());

        $response->assertStatus(200)
        ->assertSee('Карточка контрагента')
        ->assertSee('Основные данные')
        ->assertSee('Сотрудники контрагента')
        ->assertSee('Заметки по контрагенту');
    }

    /**
     * Если я - экономист с нормальным паролем, вижу все
     */
    public function testVisitCounterpartySummaryAsAnEconom(): void
    {
        $user = User::whereHas('roles', function ($query) {
            $query->where('slug', 'econom');
        })
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->first();
        
        $response = $this->actingAs($user)->get($this->routeForCounterpartySummary());

        $response->assertStatus(200)
        ->assertSee('Карточка контрагента')
        ->assertSee('Основные данные')
        ->assertSee('Сотрудники контрагента')
        ->assertSee('Заметки по контрагенту');
    }
}

