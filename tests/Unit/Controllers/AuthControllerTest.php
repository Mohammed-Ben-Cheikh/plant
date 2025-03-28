<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Traits\Jwt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, Jwt;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'role_id' => 1,
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['user', 'token']
            ]);
    }

    public function test_user_can_register()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['user', 'token']
            ]);
    }

    public function test_user_can_logout()
    {   
        $user = User::factory()->create([
            'role_id' => 1,
            'password' => Hash::make('password123')
        ]);
        $token = $this->jwtToken(['email' => $user->email, 'password' => 'password123']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');
        
        $response->assertStatus(200);
    }
}
