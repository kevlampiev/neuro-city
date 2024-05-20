<?php

namespace Tests\Feature\Counterparties;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;    
use App\Models\Company;
use Illuminate\Support\Carbon;

class CounterpartyEditTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    //Вспомогательная функция, которая возращает подопытный rout
    public function routeForCounterpartyEdit():string
    {
        $counterparty =Company::where('our_company', false)->inRandomOrder()->first();
        return route('editCounterparty', ['counterparty' => $counterparty]);
    }

        
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitCounterpartyEditAsGuest(): void
    {
        $response = $this->get($this->routeForCounterpartyEdit());

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitCounterpartyEditAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForCounterpartyEdit());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и не юрист или экономист, перенаправляет на /
     */
    public function testVisitCounterpartyEditAsASimpleUser(): void
    {
        $user = User::query()->where('is_superuser',"=",false)
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->whereNotIn('id', function($query){
            $query->select('user_id')->from('users_roles');
        })
        ->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForCounterpartyEdit());

        $response->assertStatus(404);
    }


    /**
     * Если я - суперюзер с нормальным паролем, вижу все
     */
    public function testVisitCounterpartyEditAsASuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForCounterpartyEdit());

        $response->assertStatus(200)
        ->assertSee('Редактирование данных о контрагенте')
        ->assertSee('Краткое наименование')
        ->assertSee('Полное наименование')
        ->assertSee('Тип контрагента');
    }

    /**
     * Если я - юрист с нормальным паролем, вижу все
     */
    public function testVisitCounterpartyEditAsALawyer(): void
    {
        $user = User::whereHas('roles', function ($query) {
            $query->where('slug', 'urist');
        })
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->first();
        
        $response = $this->actingAs($user)->get($this->routeForCounterpartyEdit());

        $response->assertStatus(200)
        ->assertSee('Редактирование данных о контрагенте')
        ->assertSee('Краткое наименование')
        ->assertSee('Полное наименование')
        ->assertSee('Тип контрагента');
    }

    /**
     * Если я - экономист с нормальным паролем, вижу все
     */
    public function testVisitCounterpartyEditAsAnEconom(): void
    {
        $user = User::whereHas('roles', function ($query) {
            $query->where('slug', 'econom');
        })
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->first();
        
        $response = $this->actingAs($user)->get($this->routeForCounterpartyEdit());

        $response->assertStatus(200)
        ->assertSee('Редактирование данных о контрагенте')
        ->assertSee('Краткое наименование')
        ->assertSee('Полное наименование')
        ->assertSee('Тип контрагента');
    }

    public function testEditNewCounterpartyAsSuperuser():void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->post($this->routeForCounterpartyEdit(), [
            'name' => 'Edited By Superuser Test',
            'fullname' => 'Edited By Superuser Test',
            'inn' => 1111111111111,
            'ogrn' => 2222222222222,
            'established_date' => fake()->date(),

        ])
        ->assertRedirect(route('counterparties'))
        ->assertSessionHas('message', 'Информация о компании изменена');

    }


    public function testEditCounterpartyAsAnEconom():void
    {
        $user = User::whereHas('roles', function ($query) {
            $query->where('slug', 'econom');
        })
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->first();
        $response = $this->actingAs($user)->post($this->routeForCounterpartyEdit(), [
            'name' => 'Edited By an economist Test',
            'fullname' => 'Edited By an economist Test',
            'inn' => 1111111111111,
            'ogrn' => 2222222222222,
            'established_date' => fake()->date(),

        ])
        ->assertRedirect(route('counterparties'))
        ->assertSessionHas('message', 'Информация о компании изменена');

    }

    public function testEditCounterpartyAsALawyer():void
    {
        $user = User::whereHas('roles', function ($query) {
            $query->where('slug', 'urist');
        })
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->first();
        $response = $this->actingAs($user)->post($this->routeForCounterpartyEdit(), [
            'name' => 'Edited By a lawyer Test',
            'fullname' => 'Edited By a lawyer Test',
            'inn' => 1111111111111,
            'ogrn' => 2222222222222,
            'established_date' => fake()->date(),

        ])
        ->assertRedirect(route('counterparties'))
        ->assertSessionHas('message', 'Информация о компании изменена');

    }
}

