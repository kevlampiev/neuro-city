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
     * Если я не суперюзер и у меня нет разрешения на редактирование договора - меня посылают на 404/
     */
    public function testVisitAgreementEditAsASimpleUserWithNoPermission(): void
{
    // Получаем список всех пользователей, которые не являются суперпользователями и изменили пароль в последние 30 дней
    $users = User::query()
        ->where('is_superuser', '=', false)
        ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
        ->inRandomOrder()
        ->get();

    // Фильтруем пользователей, у которых нет разрешения 'e-agreements'
    $user = $users->first(function($user) {
        return !$user->hasPermissionTo('e-agreements');
    });

    // Проверяем, был ли найден пользователь, соответствующий условиям
    $this->assertNotNull($user, 'Не удалось найти пользователя, соответствующего условиям.');

    if ($user !== null) {
        // Действуем от имени выбранного пользователя и проверяем, что доступ к редактированию договора запрещен
        $response = $this->actingAs($user)->get($this->routeForAgreementEdit());

        // Ожидаем, что доступ будет запрещен, и будет возвращен статус 404 или 403 (в зависимости от логики)
        $response->assertStatus(404);
    } else {
        // Если пользователь не найден, выводим отладочную информацию
        $this->fail('Не удалось найти пользователя, соответствующего условиям. Проверьте базу данных на наличие пользователей, которые не имеют разрешения "e-agreements".');
    }
}


    /**
     * Если я не суперюзер и у меня есть разрешения на редактирование договора - открываю окно редактирования/
     */
public function testVisitAgreementEditAsUserWithGoodPermission(): void
{
    // Получаем список всех пользователей, которые не являются суперпользователями и изменили пароль в последние 30 дней
    $users = User::query()
        ->where('is_superuser', '=', false)
        ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
        ->inRandomOrder()
        ->get();

    // Фильтруем пользователей, у которых есть разрешение 'e-agreements'
    $user = $users->first(function ($user) {
        return $user->hasPermissionTo('e-agreements');
    });

    // Проверяем, был ли найден пользователь, соответствующий условиям
    $this->assertNotNull($user, 'Не удалось найти пользователя с разрешением "e-agreements".');

    if ($user !== null) {
        // Действуем от имени выбранного пользователя и проверяем, что доступ к редактированию договора разрешен
        $response = $this->actingAs($user)->get($this->routeForAgreementEdit());

        // Ожидаем успешный ответ с кодом 200
        $response->assertStatus(200);
    } else {
        // Если пользователь не найден, выводим отладочную информацию
        $this->fail('Не удалось найти пользователя, соответствующего условиям. Проверьте базу данных на наличие пользователей с разрешением "e-agreements".');
    }
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


    // public function testEditAgreementAsASuperuser():void
    // {
    //     $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
    //     $buyer = Company::inRandomOrder()->first();
    //     // $seller = Company::where('id', "<>",$buyer->id)->inRandomOrder()->first();
    //     $seller = $buyer;
        
    //     $agreementType = AgreementType::inRandomOrder()->first();
    //     dd($buyer);

    //     $response = $this->actingAs($user)->post($this->routeForAgreementEdit(), 
    //     [
    //         'name' => 'Edited By Superuser Test',
    //         'agr_number' => '777',
    //         'date_open' => "2024-06-28",
    //         'date_close' => null,
    //         'real_date_close' => null,
    //         'agreement_type_id' => $agreementType->id,
    //         'seller_id' => $seller->id,
    //         'buyer_id' => $buyer->id, 
    //         'created_by'=> $user->id,

          
    //     ])
    //     ->assertRedirect(route('agreements'))
    //     // ->assertSessionHas('message', 'Данные договора обновлены')
    //     ;
    // }
}

