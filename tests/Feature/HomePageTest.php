<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;
    
    /**
     * A basic feature test example.
     */
    public function testVisitHomepageAsGuest(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302)->assertRedirectToRoute('login');
    }

    public function testVisitHomepageAsNewUser(): void
    {
        // $this->seed();
        $user = User::query()->where('password_changed_at',"=",null)->inRandomOrder()->first();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(302)->assertRedirectToRoute('password.expired');;
    }

    public function testVisitHomepageAsUser(): void
    {
        // $this->seed();
        $user = User::query()->where('password_changed_at',">",Carbon::now()->addDay(-30))->inRandomOrder()->first();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }
}
