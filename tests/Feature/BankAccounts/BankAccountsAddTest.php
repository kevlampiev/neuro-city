<?php

namespace Tests\Feature\Projects;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;    
use Illuminate\Support\Carbon;
use App\Models\Company;

class BankAccountsAddTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    //Вспомогательная функция, которая возращает подопытный rout
    public function routeForTesting():string
    {
        $project =Project::query()->inRandomOrder()->first();
        return route('accounts.create');
    }

    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitBankAccountAddingAsGuest(): void
    {
        $response = $this->get($this->routeForTesting());

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitBankAccountAddingAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForTesting());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и у меня нет разрешения на редактирование договора - меня посылают на 404/
     */
    public function testVisitBankAccountAddingAsASimpleUserWithNoPermission(): void
{
    // Получаем список всех пользователей, которые не являются суперпользователями и изменили пароль в последние 30 дней
    $users = User::query()
        ->where('is_superuser', '=', false)
        ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
        ->inRandomOrder()
        ->get();

    // Фильтруем пользователей, у которых нет разрешения 'e-accounts'
    $user = $users->first(function($user) {
        return !$user->hasPermissionTo('e-accounts');
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


    /**
     * Если я не суперюзер и у меня есть разрешения на редактирование договора - открываю окно редактирования/
     */
public function testVisitBankAccountAddingAsUserWithGoodPermission(): void
{
    // Получаем список всех пользователей, которые не являются суперпользователями и изменили пароль в последние 30 дней
    $users = User::query()
        ->where('is_superuser', '=', false)
        ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
        ->inRandomOrder()
        ->get();

    // Фильтруем пользователей, у которых есть разрешение 'e-accounts'
    $user = $users->first(function ($user) {
        return $user->hasPermissionTo('e-accounts');
    });

    // Проверяем, был ли найден пользователь, соответствующий условиям
    $this->assertNotNull($user, 'Не удалось найти пользователя с разрешением "e-accounts".');

    if ($user !== null) {
        // Действуем от имени выбранного пользователя и проверяем, что доступ к редактированию договора разрешен
        $response = $this->actingAs($user)->get($this->routeForTesting());

        // Ожидаем успешный ответ с кодом 200
        $response->assertStatus(200);
    } else {
        // Если пользователь не найден, выводим отладочную информацию
        $this->fail('Не удалось найти пользователя, соответствующего условиям. Проверьте базу данных на наличие пользователей с разрешением "e-accounts".');
    }
}

    /**
     * Если я - суперюзер с нормальным паролем, вижу все
     */
    public function testVisitBankAccountAddingAsASuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForTesting());

        $response->assertStatus(200)
        ->assertSee('Добавление счета')
        ->assertSee('Номер счета')
        ->assertSee('Дата открытия')
        ->assertSee('Дата закрытия')
        ->assertSee('Владелец счета')
        ->assertSee('Банк')
        ->assertSee('Описание')
        ->assertSee('Добавить');
    }


    public function testCreateProjectSuccessfully(): void
    {
        // Получаем пользователя с разрешением на создание проектов
        $user = User::query()
            ->where('is_superuser', '=', true) // Или с конкретным разрешением, если это необходимо
            ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
            ->inRandomOrder()
            ->first();

        // Убеждаемся, что пользователь найден
        $this->assertNotNull($user, 'Не удалось найти пользователя с правами на создание проекта.');

        // Данные для нового проекта
        $accountData = [
            'account_number' => '11111111110000000000',
            'description' => 'Описание нового счета',
            'owner_id' => Company::where('our_company',true)->first()->id,
            'bank_id' => Company::where('company_type','bank')->first()->id,
            'date_open' => '2024-06-28',
            'date_close' => null,
            'created_by' => $user->id,
        ];

        // Выполняем POST-запрос для создания проекта
        $response = $this->actingAs($user)->post(route('accounts.store'), $accountData);

        // Ожидаем, что будет выполнен редирект после успешного добавления
        $response->assertStatus(302)->assertRedirect(route('accounts.index'));

        // Проверяем, что проект был добавлен в базу данных
        $this->assertDatabaseHas('bank_accounts', [
           'account_number' => '11111111110000000000',
            'description' => 'Описание нового счета',
            'created_by' => $user->id,
        ]);
    }


}

