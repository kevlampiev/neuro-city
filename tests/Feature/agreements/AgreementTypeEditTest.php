<?php

namespace Tests\Feature\Companies;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;    
use App\Models\AgreementType;
use Illuminate\Support\Carbon;

class AgreementTypeEditTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    //Вспомогательная функция, которая возращает подопытный rout
    public function routeForAgreementTypeEdit():string
    {
        $agreementType =AgreementType::where('system', false)->inRandomOrder()->first();
        return route('editAgrType', ['agrType' => $agreementType]);
    }

    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitAgreementTypeEditAsGuest(): void
    {
        $response = $this->get($this->routeForAgreementTypeEdit());

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitAgreementTypeEditAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForAgreementTypeEdit());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и не юрист или экономист, перенаправляет на /
     */
    public function testVisitAgreementTypeEditAsASimpleUser(): void
    {
        $user = User::query()->where('is_superuser',"=",false)
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForAgreementTypeEdit());

        $response->assertStatus(302);
    }


    /**
     * Если я - суперюзер с нормальным паролем, вижу все
     */
    public function testVisitAgreementTypeEditAsASuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForAgreementTypeEdit());

        $response->assertStatus(200)
        ->assertSee('Изменение типа договора')
        ->assertSee('Наименование типа договора')
        ->assertSee('Сфера деятельности');
    }


    public function testEditAgreementTypeAsASuperuser():void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->post($this->routeForAgreementTypeEdit(), 
        [
            'name' => 'Edited By Superuser Test',
            'segment' => 'finance',
            'system' =>false,

        ])
        ->assertRedirect(route('agrTypes'))
        ->assertSessionHas('message', 'Информация о типе договора изменена');

    }

}

