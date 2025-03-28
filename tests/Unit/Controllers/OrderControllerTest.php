<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Order;
use App\Models\Plants;
use App\Models\Categories;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        Role::insert([
            ['name' => 'admin'],
            ['name' => 'employee'],
            ['name' => 'client']
        ]);
        Categories::factory()->count(5)->create();
        Plants::factory()->count(5)->create();
    }

    public function test_can_get_all_orders()
    {
        $this->user = User::factory()->create(['role_id' => 2]);
        Order::factory()->count(6)->create();

        $response = $this->actingAs($this->user)->getJson('/api/reservations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['Orders']
            ]);
    }

    public function test_can_create_order()
    {
        $this->user = User::factory()->create(['role_id' => 3]);
        $orderData = [
            "plant_id" => 2,
            "quantity" => 2
        ];
        $response = $this->actingAs($this->user)
            ->postJson('/api/reservations', $orderData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['Order']
            ]);
    }

    public function test_can_get_orders_by_status()
    {
        $this->user = User::factory()->create(['role_id' => 2]);
        Order::factory()->count(3)->create(['status' => 'pending']);

        $response = $this->actingAs($this->user)
            ->getJson('/api/reservations/status/pending');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['Orders']
            ]);
    }
}
