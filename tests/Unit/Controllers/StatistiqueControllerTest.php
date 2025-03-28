<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Order;
use App\Models\Plants;
use App\Models\Categories;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatistiqueControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Role::insert([
            ['name' => 'admin'],
            ['name' => 'employee'],
            ['name' => 'client']
        ]);
        $this->admin = User::factory()->create(['role_id' => 1]);
        User::factory()->count(1)->create(['role_id' => 2]);
        User::factory()->count(1)->create(['role_id' => 3]);
        Categories::factory()->count(5)->create();
        Plants::factory()->count(5)->create();
        Order::factory()->count(1)->create();
        Order::factory()->count(1)->create();
        Order::factory()->count(1)->create();
    }

    public function test_can_get_order_statistics()
    {
        $response = $this->actingAs($this->admin)
                        ->getJson('/api/statistics?param=orders');
        $response->assertStatus(200)
                ->assertJsonStructure($data = [
                    'data' => ['Stats']
                ]);
    }

    public function test_can_get_user_statistics()
    {
        $response = $this->actingAs($this->admin)
                        ->getJson('/api/statistics?param=users');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => ['Stats']
                ]);
    }

    public function test_invalid_parameter_returns_error()
    {
        $response = $this->actingAs($this->admin)
                        ->getJson('/api/statistics?param=invalid');

        $response->assertStatus(400);
    }
}
