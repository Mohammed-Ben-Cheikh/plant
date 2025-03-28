<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Plants;
use App\Models\Categories;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PlantsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $role = Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create(['role_id' => $role->id]);
    }

    public function test_can_get_all_plants()
    {
        Categories::factory()->count(4)->create();
        Plants::factory()->count(3)->create();
        $response = $this->actingAs($this->admin)->getJson('/api/plants');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['Plants']
            ]);
    }

    public function test_can_create_plant()
    {
        Categories::factory()->count(4)->create();
        $plantData = [
            'name' => $this->faker->name(),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->text(),
            'category_id' => $this->faker->numberBetween(1, 4),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'stock' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->boolean(),
            "images" => [
                [
                    "img_url" => "https://example.com/photo1.jpg",
                    "is_primary" => true
                ],
                [
                    "img_url" => "https://example.com/photo2.jpg",
                    "is_primary" => false
                ],
                [
                    "img_url" => "https://example.com/photo3.jpg",
                    "is_primary" => false
                ],
                [
                    "img_url" => "https://example.com/photo4.jpg",
                    "is_primary" => false
                ]
            ]
        ];
        $response = $this->actingAs($this->admin)
            ->postJson('/api/plants', $plantData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['Plant']
            ]);
    }

    public function test_can_get_single_plant()
    {
        Categories::factory()->count(4)->create();
        $plant = Plants::factory()->create();

        $response = $this->actingAs($this->admin)
            ->getJson("/api/plants/{$plant->slug}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['Plant']
            ]);
    }
}
