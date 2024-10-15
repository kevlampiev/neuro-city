<?php

namespace Tests\Feature\DroidTypes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\DroidType;    
use Illuminate\Support\Carbon;

class DroidTypeAddTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    //Вспомогательная функция, которая возращает подопытный rout
    public function routeForDroidTypeEdit():string
    {
        $DroidType =DroidType::query()->inRandomOrder()->first();
        return route('droidTypes.create');
    }

    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitDroidTypeAddAsGuest(): void
    {
        $response = $this->get($this->routeForDroidTypeEdit());
        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitDroidTypeAddAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForDroidTypeEdit());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и у меня нет разрешения на редактирование договора - меня посылают на 404/
     */
    public function testVisitDroidTypeAddAsASimpleUserWithNoPermission(): void
    {
        // Получаем список всех пользователей, которые не являются суперпользователями и изменили пароль в последние 30 дней
        $users = User::query()
            ->where('is_superuser', '=', false)
            ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
            ->inRandomOrder()
            ->get();

        // Фильтруем пользователей, у которых нет разрешения 'e-droid_types'
        $user = $users->first(function($user) {
            return !$user->hasPermissionTo('e-droid_types');
        });

        // Проверяем, был ли найден пользователь, соответствующий условиям
        $this->assertNotNull($user, 'Не удалось найти пользователя, соответствующего условиям.');

        if ($user !== null) {
            // Действуем от имени выбранного пользователя и проверяем, что доступ к редактированию договора запрещен
            $response = $this->actingAs($user)->get($this->routeForDroidTypeEdit());

            // Ожидаем, что доступ будет запрещен, и будет возвращен статус 404 или 403 (в зависимости от логики)
            $response->assertStatus(404);
        } else {
            // Если пользователь не найден, выводим отладочную информацию
            $this->fail('Не удалось найти пользователя, соответствующего условиям. Проверьте базу данных на наличие пользователей, которые не имеют разрешения "e-droid_types".');
        }
    }


    /**
     * Если я не суперюзер и у меня есть разрешения на редактирование договора - открываю окно редактирования/
     */
public function testVisitDroidTypeEditAsUserWithGoodPermission(): void
{
    // Получаем список всех пользователей, которые не являются суперпользователями и изменили пароль в последние 30 дней
    $users = User::query()
        ->where('is_superuser', '=', false)
        ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
        ->inRandomOrder()
        ->get();

    // Фильтруем пользователей, у которых есть разрешение 'e-droid_types'
    $user = $users->first(function ($user) {
        return $user->hasPermissionTo('e-droid_types');
    });

    // Проверяем, был ли найден пользователь, соответствующий условиям
    $this->assertNotNull($user, 'Не удалось найти пользователя с разрешением "e-droid_types".');

    if ($user !== null) {
        // Действуем от имени выбранного пользователя и проверяем, что доступ к редактированию договора разрешен
        $response = $this->actingAs($user)->get($this->routeForDroidTypeEdit());

        // Ожидаем успешный ответ с кодом 200
        $response->assertStatus(200);
    } else {
        // Если пользователь не найден, выводим отладочную информацию
        $this->fail('Не удалось найти пользователя, соответствующего условиям. Проверьте базу данных на наличие пользователей с разрешением "e-droid_types".');
    }
}

    /**
     * Если я - суперюзер с нормальным паролем, вижу все
     */
    public function testVisitDroidTypeEditAsASuperuser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->where('is_superuser','=',true)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForDroidTypeEdit());

        $response->assertStatus(200)
        ->assertSee('Добавление типа')
        ->assertSee('Название модели')
        ->assertSee('Описание');
    }


    public function testCreateDroidTypeSuccessfully(): void
    {
        // Получаем пользователя с разрешением на создание проектов
        $user = User::query()
            ->where('is_superuser', '=', true) // Или с конкретным разрешением, если это необходимо
            ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
            ->inRandomOrder()
            ->first();

        // Убеждаемся, что пользователь найден
        $this->assertNotNull($user, 'Не удалось найти пользователя с правами на создание типа дроида.');

        // Данные для нового проекта
        $DroidTypeData = [
            'name' => 'Новая модель. Тест',
            'description' => 'Описание новой модели',
            'created_by' => $user->id,
        ];

        // Выполняем POST-запрос для создания проекта
        $response = $this->actingAs($user)->post(route('droidTypes.store'), $DroidTypeData);

        // Ожидаем, что будет выполнен редирект после успешного добавления
        $response->assertStatus(302)->assertRedirect(route('droidTypes.index'));

        // Проверяем, что проект был добавлен в базу данных
        $this->assertDatabaseHas('droid_types', [
            'name' => 'Новая модель. Тест',
            'description' => 'Описание новой модели',
            'created_by' => $user->id,
        ]);
    }


}

