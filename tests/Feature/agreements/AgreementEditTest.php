<?php

namespace Tests\Feature\Companies;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;    
use App\Models\Agreement;
use App\Models\AgreementType;
use Illuminate\Support\Carbon;

class AgreementEditTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    //Вспомогательная функция, которая возращает подопытный rout
    public function routeForAgreementEdit():string
    {
        $agreement =Agreement::inRandomOrder()->first();
        return route('editAgreement', ['agreement' => $agreement]);
    }

    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitAgreementEditAsGuest(): void
    {
        $response = $this->get($this->routeForAgreementEdit());

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitAgreementEditAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForAgreementEdit());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и не юрист или экономист, перенаправляет на /
     */
    public function testVisitAgreementEditAsASimpleUser(): void
    {
        $user = User::query()->where('is_superuser',"=",false)
        ->where('password_changed_at',">",Carbon::now()->addDay(-30))
        ->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForAgreementEdit());

        $response->assertStatus(302);
    }


    /**
     * Если я - суперюзер с нормальным паролем, вижу все
     */
    public function testVisitAgreementEditAsASuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForAgreementEdit());

        $response->assertStatus(200)
        ->assertSee('Изменение данных договора')
        ->assertSee('Наименование договора')
        ->assertSee('Продавец')
        ->assertSee('Покупатель')
        ->assertSee('Номер договора')
        ->assertSee('Срок действия');
    }


    public function testEditAgreementAsASuperuser():void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $buyer = Company::inRandomOrder()->first();
        $seller = Company::inRandomOrder()->first();
        $agreementType = AgreementType::inRandomOrder()->first();

        $response = $this->actingAs($user)->post($this->routeForAgreementEdit(), 
        [
            'name' => 'Edited By Superuser Test',
            'agr_number' => '777',
            'date_open' => "2024-06-28",
            'date_close' => null,
            'real_date_close' => null,
            'agreement_type_id' => $agreementType->id,
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id, 
            'created_by'=> $user->id,

          
        ])
        // ->assertRedirect(route('agreements'))
        ->assertSessionHas('message', 'Данные договора обновлены');
    }
}

