<?php

namespace Tests\Feature\Projects;

use App\Models\Agreement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;    
use Illuminate\Support\Carbon;
use App\Models\BankAccount;

class PlanAccrualAddTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    //Вспомогательная функция, которая возращает подопытный rout
    public function routeForTesting():string
    {
        $agreement = Agreement::query()->inRandomOrder()->first();
        return route('plan-accruals.add', ['agreement' => $agreement->id]);
    }

    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitAddingPlanAccrualAsGuest(): void
    {
        $response = $this->get($this->routeForTesting());

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitAddingPlanAccrualAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForTesting());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и у меня нет разрешения на редактирование договора - меня посылают на 404/
     */
    public function testVisitAddingPlanAccrualAsASimpleUserWithNoPermission(): void
{
    // Получаем список всех пользователей, которые не являются суперпользователями и изменили пароль в последние 30 дней
    $users = User::query()
        ->where('is_superuser', '=', false)
        ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
        ->inRandomOrder()
        ->get();

    // Фильтруем пользователей, у которых нет разрешения 'e-accounts'
    $user = $users->first(function($user) {
        return !$user->hasPermissionTo('e-accruals');
    });

    // Проверяем, был ли найден пользователь, соответствующий условиям
    $this->assertNotNull($user, 'Не удалось найти пользователя, соответствующего условиям.');

    if ($user !== null) {
        // Действуем от имени выбранного пользователя и проверяем, что доступ к редактированию договора запрещен
        $response = $this->actingAs($user)->get($this->routeForTesting());

        // Ожидаем, что доступ будет запрещен, и будет возвращен статус 404 или 403 (в зависимости от логики)
        $response->assertStatus(404);
    } else {
        // Если пользователь не найден, выводим отладочную информацию
        $this->fail('Не удалось найти пользователя, соответствующего условиям. Проверьте базу данных на наличие пользователей, которые не имеют разрешения "e-accounts".');
    }
}


//     /**
//      * Если я не суперюзер и у меня есть разрешения на редактирование договора - открываю окно редактирования/
//      */
// public function testVisitAddingPlanAccrualAsUserWithGoodPermission(): void
// {
//     // Получаем список всех пользователей, которые не являются суперпользователями и изменили пароль в последние 30 дней
//     $users = User::query()
//         ->where('is_superuser', '=', false)
//         ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
//         ->inRandomOrder()
//         ->get();

//     // Фильтруем пользователей, у которых есть разрешение 'e-accounts'
//     $user = $users->first(function ($user) {
//         return $user->hasPermissionTo('e-accruals');
//     });

//     // Проверяем, был ли найден пользователь, соответствующий условиям
//     $this->assertNotNull($user, 'Не удалось найти пользователя с разрешением "e-accruals".');

//     if ($user !== null) {
//         // Действуем от имени выбранного пользователя и проверяем, что доступ к редактированию договора разрешен
//         $response = $this->actingAs($user)->get($this->routeForTesting());

//         // Ожидаем успешный ответ с кодом 200
//         $response->assertStatus(200);
//     } else {
//         // Если пользователь не найден, выводим отладочную информацию
//         $this->fail('Не удалось найти пользователя, соответствующего условиям. Проверьте базу данных на наличие пользователей с разрешением "e-accruals".');
//     }
// }

//     /**
//      * Если я - суперюзер с нормальным паролем, вижу все
//      */
//     public function testVisitAddingPlanAccrualAsASuperuser(): void
//     {
//         $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
//         $response = $this->actingAs($user)->get($this->routeForTesting());

//         $response->assertStatus(200)
//         ->assertSee('Создать начисление')
//         ->assertSee('Договор')
//         ->assertSee('Продукт')
//         ->assertSee('Проект')
//         ->assertSee('Количество, стоимость и итог')
//         ->assertSee('Первоначальная дата')
//         ->assertSee('Планируемая дата')
//         ->assertSee('Статья PL');
//     }


    // public function testEditAccountSuccessfully(): void
    // {
    //     // Получаем пользователя с правами на редактирование банковского счета
    //     $user = User::query()
    //         ->where('is_superuser', '=', true) // Или с конкретным разрешением
    //         ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
    //         ->inRandomOrder()
    //         ->first();
    
    //     // Убеждаемся, что пользователь найден
    //     $this->assertNotNull($user, 'Не удалось найти пользователя с правами на редактирование банковского счета.');
    
    //     // Получаем случайный счет
    //     $bankAccount = BankAccount::inRandomOrder()->first();
    
    //     // Данные для обновления банковского счета
    //     $updatedData = [
    //         'account_number' => '11111111123333333334',
    //         'description' => 'Обновленное описание банковского счета',
    //         'date_open' => $bankAccount->date_open,
    //         'owner_id' => $bankAccount->owner_id,
    //         'bank_id' => $bankAccount->bank_id,
    //      ];
    
    //     // Отправляем PUT или PATCH запрос для обновления банковского счета
    //     $response = $this->actingAs($user)->put(route('accounts.update', ['bankAccount' => $bankAccount->id]), $updatedData);
    
    //     // Ожидаем, что будет выполнен редирект после успешного обновления
    //     $response->assertStatus(302);
    
    //     // Проверяем, что счет был обновлен в базе данных
    //     $this->assertDatabaseHas('bank_accounts', [
    //         'id' => $bankAccount->id, // Убедимся, что это тот же счет
    //         'account_number' => '11111111123333333334',
    //         'description' => 'Обновленное описание банковского счета',
    //     ]);
    // }
    
}

