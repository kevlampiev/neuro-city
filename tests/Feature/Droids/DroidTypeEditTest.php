<?php

namespace Tests\Feature\Droids;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\DroidType;    
use Illuminate\Support\Carbon;

class DroidTypeEditTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    //Вспомогательная функция, которая возращает подопытный rout
    public function routeForDroidTypeEdit():string
    {
        $droidType =DroidType::query()->inRandomOrder()->first();
        return route('droidTypes.edit', ['id' => $droidType->id]);
    }

    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitDroidTypeEditAsGuest(): void
    {
        $response = $this->get($this->routeForDroidTypeEdit());

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitDroidTypeEditAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForDroidTypeEdit());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Если я не суперюзер и у меня нет разрешения на редактирование договора - меня посылают на 404/
     */
    public function testVisitDroidTypeEditAsASimpleUserWithNoPermission(): void
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
        ->assertSee('Изменение типа')
        ->assertSee('Название модели')
        ->assertSee('Описание');
    }


    public function testEditDroidTypeSuccessfully(): void
    {
        // Получаем пользователя с правами на редактирование проекта
        $user = User::query()
            ->where('is_superuser', '=', true) // Или с конкретным разрешением
            ->where('password_changed_at', '>', Carbon::now()->addDay(-30))
            ->inRandomOrder()
            ->first();
    
        // Убеждаемся, что пользователь найден
        $this->assertNotNull($user, 'Не удалось найти пользователя с правами на редактирование проекта.');
    
        // Получаем случайный проект
        $droidType = DroidType::inRandomOrder()->first();
    
        // Данные для обновления проекта
        $updatedData = [
            'name' => 'Обновленное название модели',
            'description' => 'Обновленное описание модели',
           
        ];
    
        // Отправляем PUT или PATCH запрос для обновления проекта
        $response = $this->actingAs($user)->put(route('droidTypes.update', ['id' => $droidType->id]), $updatedData);
    
        // Ожидаем, что будет выполнен редирект после успешного обновления
        $response->assertStatus(302)->assertRedirect(route('droidTypes.index'));
    
        // Проверяем, что проект был обновлен в базе данных
        $this->assertDatabaseHas('droid_types', [
            'id' => $droidType->id, // Убедимся, что это тот же проект
            'name' => 'Обновленное название модели',
            'description' => 'Обновленное описание модели',
        ]);
    }
    
}

