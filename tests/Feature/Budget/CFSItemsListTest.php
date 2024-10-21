<?php

namespace Tests\Feature\Budget;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;    
use Illuminate\Support\Carbon;

class CFSItemsListTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;


    public function routeForCFItemsList():string
    {
        return route('cfsGroups');
    }

    
    /**
     * Незареггистрированный пользователь перенаправляется на /login
     */
    public function testVisitCFSItemsListAsGuest(): void
    {
        $response = $this->get($this->routeForCFItemsList());

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    /**
     * Если я суперюзер и пароль изменялся давно, перенаправляет на /expired
     */
    public function testVisitCFSItemsListAsNewSuperuser(): void
    {
        $user = User::query()->where('is_superuser',"=",true)->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForCFItemsList());

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }


    /**
     * Любой юзер с непросроченным паролем может видеть план счетов. Что в этом такого
     */
    public function testVisitCFSItemsListAsProperUser(): void
    {
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->inRandomOrder()->first();
        $response = $this->actingAs($user)->get($this->routeForCFItemsList());

        $response->assertStatus(200)
        ->assertSee('Группы статей CFS')
        ->assertSee('Операционная деятельность')
        ->assertSee('Финансовая деятельность')
        ->assertSee('Инвестиционная деятельность')
        ->assertSee('Добавить статью')
        ->assertSee('Изменить')
        ->assertSee('Удалить');
    }
 
}

