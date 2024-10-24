<?php

namespace Tests\Feature\Companies;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;    
use Illuminate\Support\Carbon;

class BankAccountsListTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    public function routeForTesting():string
    {
        return route('accounts.index');
    }

    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitBankAccountsListAsGuest(): void
    {
        $response = $this->get($this->routeForTesting());

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitBankAccountsListAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForTesting());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и не имею разрешения s-accounts, перенаправляет на 404
     */
    public function testVisitBankAccountsListAsSimpleUserWithNoPermission(): void
    {
        // Выбираем пользователя, который не является суперпользователем, изменил пароль в последние 30 дней
        // и не имеет разрешения 's-accounts'.
        $user = User::query()
        ->where('is_superuser', '=', false)
        ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
        ->inRandomOrder()
        ->get()
        ->first(function($user) {
            return !$user->hasPermissionTo('s-accounts');
        });

        // Проверяем, что пользователь выбран корректно и не имеет разрешения 's-accounts'.
        $this->assertNotNull($user, 'Не удалось найти пользователя, соответствующего условиям.');
        $this->assertFalse($user->hasPermissionTo('s-accounts'));

        // Действуем от имени выбранного пользователя и проверяем, что доступ к списку договоров запрещен.
        $response = $this->actingAs($user)->get($this->routeForTesting());

        // Ожидаем перенаправление, так как доступ к списку договоров запрещен.
        $response->assertStatus(404);
    }

    /**
     * Если я не суперюзер и не имею разрешения s-accounts, перенаправляет на 404
     */
    public function testVisitBankAccountsListAsSimpleUserWithCorrectPermission(): void
    {
        // Получаем список всех пользователей, которые не являются суперпользователями и изменили пароль в последние 30 дней
    $users = User::query()
    ->where('is_superuser', '=', false)
    ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
    ->inRandomOrder()
    ->get();

        // Фильтруем пользователей, у которых есть разрешение 's-accounts'
        $user = $users->first(function($user) {
            return $user->hasPermissionTo('s-accounts');
        });

        // Проверка, был ли найден пользователь, соответствующий условиям
        $this->assertNotNull($user, 'Не удалось найти пользователя, соответствующего условиям.');

        if ($user !== null) {
            // Проверяем, что у найденного пользователя есть разрешение 's-accounts'
            $this->assertTrue($user->hasPermissionTo('s-accounts'));

            // Действуем от имени выбранного пользователя и проверяем, что доступ к списку договоров разрешен.
            $response = $this->actingAs($user)->get($this->routeForTesting());

            // Ожидаем успешный ответ, так как доступ к списку договоров должен быть разрешен.
            $response->assertStatus(200)
            ->assertSee('Банковские счета')
            ->assertSee('Владелец счета')
            ->assertSee('Банк')
            ->assertSee('Номер счета')
            ->assertSee('Дата открытия')
            ->assertSee('Дата закрытия');

        } else {
            // Если пользователь не найден, выводим отладочную информацию
            $this->fail('Не удалось найти пользователя, соответствующего условиям. Проверьте базу данных на наличие пользователей, которые имеют разрешение "s-accounts".');
        }
 
    }

    /**
     * Если я - суперюзер с нормальным паролем, вижу все
     */
    public function testVisitBankAccountsListAsSuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForTesting());
        // $project = Project::query()->orderByDesc('date_open')->first();

        $response->assertStatus(200)
        ->assertSee('Банковские счета')
        ->assertSee('Владелец счета')
        ->assertSee('Банк')
        ->assertSee('Номер счета')
        ->assertSee('Дата открытия')
        ->assertSee('Дата закрытия');

    }

}

