<?php

namespace Tests\Feature\Counterparties;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;    
use Illuminate\Support\Carbon;

class CounterpartiesListTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;
    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitCounterpartiesListAsGuest(): void
    {
        $response = $this->get(route('counterparties'));

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitCounterpartiesListAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get(route('counterparties'));

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и не юрист или экономист, перенаправляет на /
     */
    public function testVisitCounterpartiesListAsSimpleUser(): void
    {
        $user = User::query()->where('is_superuser',"=",false)
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->whereNotIn('id', function($query){
            $query->select('user_id')->from('users_roles');
        })
        ->inRandomOrder()->first();
        $response = $this->actingAs($user)->get(route('counterparties'));

        $response->assertStatus(404);
    }


    /**
     * Если я - суперюзер с нормальным паролем, вижу все
     */
    public function testVisitCounterpartiesListAsSuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get('counterparties');

        $response->assertStatus(200)
        ->assertSee('Реестр контрагентов')
        ->assertSee('Карточка')
        ->assertSee('Изменить')
        ->assertSee('Удалить');
    }

    /**
     * Если я - юрист с нормальным паролем, вижу все
     */
    public function testVisitCounterpartiesListAsALawyer(): void
    {
        $user = User::whereHas('roles', function ($query) {
            $query->where('slug', 'urist');
        })
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->first();
        
        $response = $this->actingAs($user)->get('counterparties');

        $response->assertStatus(200)
        ->assertSee('Реестр контрагентов')
        ->assertSee('Карточка')
        ->assertSee('Изменить')
        ->assertSee('Удалить');
    }

    /**
     * Если я - экономист с нормальным паролем, вижу все
     */
    public function testVisitCounterpartiesListAsAnEconom(): void
    {
        $user = User::whereHas('roles', function ($query) {
            $query->where('slug', 'econom');
        })
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->first();
        
        $response = $this->actingAs($user)->get('counterparties');

        $response->assertStatus(200)
        ->assertSee('Реестр контрагентов')
        ->assertSee('Карточка')
        ->assertSee('Изменить')
        ->assertSee('Удалить');
    }
}

