<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Categories;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $role = Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create(['role_id' => $role->id]);
    }

    public function test_can_get_all_categories()
    {
        Categories::factory()->count(4)->create();

        $response = $this->actingAs($this->admin)->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['Categories']
            ]);
    }

    public function test_can_create_category()
    {
        $categoryData = [
            'name' => 'Test Category',
            'description' => 'Test Description',
            'image' => 'https://cdn.pixabay.com/photo/2018/05/04/20/01/website-3374825_1280.jpg' // Add a valid image field
        ];
        $response = $this->actingAs($this->admin)
            ->postJson('/api/categories', $categoryData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['Category']
            ]);
    }

    public function test_can_update_category()
    {
        $category = Categories::factory()->create();

        $updateData = [
            'name' => 'Updated Category',
            'description' => 'Updated Description',
            'image' => 'https://cdn.pixabay.com/photo/2018/05/04/20/01/website-3374825_1280.jpg' // Add a valid image field
        ];

        $response = $this->actingAs($this->admin)
            ->putJson("/api/categories/{$category->slug}", $updateData);

        $response->assertStatus(200);
    }
}
